<?php

namespace App\Http\Controllers\Agent;

use App\Models\Season;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ReturnVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\CollectionVerification;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\CommodityMarketPrice;
use App\Models\StockAdjustment;

class AgentVerificationController extends Controller
{
    public function assignedFarmers(Request $request)
    {
        $agent = optional(Auth::guard('tenant')->user())->agent;
        $filter = $request->get('filter');
        $season = $request->get('season');
        $status = $request->get('status');
        $seasons = Season::where('status', 'open')->get();

        // If agent profile or center is not set yet, avoid querying by center and show empty state
        if (!$agent || empty($agent->center_id)) {
            if ($request->ajax()) {
                return response()->json([
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => 0,
                ]);
            }
            return view('agent.verify-collection', compact('seasons'))
                ->with('agentSetupRequired', true);
        }

        $appsQuery = Application::with([
                'farmer:id,full_name,registration_number,phone,bvn,nin,address',
                'farm:id,size,location',
                'season:id,name,status,loan_type',
                'commodity_allocations',
                'applicationCenter',
                'collectionVerification',
                'monetaryReturn'
            ])
            ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'payment_status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
            ->whereHas('applicationCenter', fn($q) => $q->where('collection_center_id', $agent->center_id))
            ->where('status', 'approved')
            ->when($filter, fn($q) => $q->whereHas('farmer', fn($f) =>
                $f->where('full_name', 'like', "%$filter%")
                    ->orWhere('registration_number', 'like', "%$filter%"))
                    ->orWhere('reference_number', 'like', "%$filter%"))
            ->when($season, fn($q) => $q->whereHas('season', fn($s) => $s->where('slug', $season)))
            ->get()
            ->map(function ($app) use ($status) {
                $app->collection_status = $app->collectionVerification()->exists() ? 'verified' : 'pending';
                $app->encrypted_id = Crypt::encryptString($app->id); // Add encrypted ID for secure URLs
                return $app;
            })
            ->filter(function ($app) use ($status) {
                return !$status || $app->collection_status === $status;
            });

        // Paginate the collection manually for demonstration purposes
        $perPage = 3;
        $page = $request->get('page', 1);
        $pagedApps = $appsQuery->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedResult = [
            'data' => $pagedApps,
            'current_page' => $page,
            'last_page' => (int) ceil($appsQuery->count() / $perPage),
            'total' => $appsQuery->count(),
        ];

        return $request->ajax()
            ? response()->json($paginatedResult)
            : view('agent.verify-collection', compact('seasons'));
    }

    public function storeCollection(Request $request)
    {
        Log::info('DEBUG: storeCollection called', [
            'request_all' => $request->all(),
            'is_tenant_request' => tenancy()->initialized,
            'current_tenant' => tenancy()->tenant?->domain,
        ]);

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'photo' => 'required|image|max:4096',
            'collected_quantities' => 'required|array',
            'collected_quantities.*' => 'numeric|min:0',
            'collection_notes' => 'nullable|string',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
            'signature_data' => 'nullable|string', // Updated from 'signature' to 'signature_data'
        ]);

        Log::info('DEBUG: Validation passed');

        // Validate location coordinates are provided (required for verification)
        if (
            $request->input('location_lat') === null ||
            $request->input('location_lng') === null ||
            !is_numeric($request->location_lat) ||
            !is_numeric($request->location_lng)
        ) {
            Log::warning('DEBUG: Location coordinates validation failed', [
                'location_lat' => $request->input('location_lat'),
                'location_lng' => $request->input('location_lng'),
            ]);
            return response()->json(['message' => 'Location coordinates are required for verification'], 422);
        }

        // Validate signature is provided
        if (!$request->signature_data || !is_string($request->signature_data)) {
            Log::warning('DEBUG: Signature validation failed', ['signature_provided' => is_string($request->signature_data)]);
            return response()->json(['message' => 'Signature is required for verification'], 422);
        }

        Log::info('DEBUG: Post-validation checks passed');

        $agent = optional(Auth::guard('tenant')->user())->agent;
        $application = Application::with(['season:id,loan_type', 'monetaryReturn', 'commodity_allocations', 'applicationCommodities.commodity:id,name,category'])->findOrFail($request->application_id);

        Log::info('DEBUG: Agent and application loaded', [
            'agent_id' => $agent?->id,
            'agent_center_id' => $agent?->center_id,
            'application_center_collection_id' => $application->applicationCenter->collection_center_id,
        ]);

        if (!$agent || empty($agent->center_id) || $application->applicationCenter->collection_center_id !== $agent->center_id) {
            Log::warning('DEBUG: Authorization failed');
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        // Enforce payment before collection for co-funded seasons
        if ($application->season && $application->season->loan_type === 'co-funded') {
            if (($application->monetaryReturn?->status ?? null) !== 'paid' && ($application->payment_status ?? null) !== 'paid') {
                Log::warning('DEBUG: Payment required for co-funded season');
                ToastMagic::error('Payment required before collection for co-funded seasons.');
                return response()->json(['message' => 'Payment required before collection'], 422);
            }
        }

        // Validate individual collected quantities don't exceed allocated
        Log::info('DEBUG: Validating collected quantities');
        foreach ($application->commodity_allocations as $allocation) {
            $collected = $request->collected_quantities[$allocation->id] ?? 0;
            if ($collected < 0 || $collected > $allocation->allocated_quantity) {
                Log::warning('DEBUG: Quantity validation failed', [
                    'allocation_id' => $allocation->id,
                    'collected' => $collected,
                    'allocated' => $allocation->allocated_quantity,
                ]);
                ToastMagic::error("Collected quantity for {$allocation->commodity_name} must be between 0 and {$allocation->allocated_quantity}.");
                return response()->json(['message' => "Collected quantity for {$allocation->commodity_name} must be between 0 and {$allocation->allocated_quantity}."], 422);
            }
        }

        Log::info('DEBUG: All validations passed, starting transaction', [
            'application_id' => $application->id,
            'commodity_allocations_count' => $application->commodity_allocations->count(),
        ]);

        // Enable DB query logging to see all INSERTs
        DB::listen(function ($query) {
            Log::info('DEBUG: DB Query', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
                'connection' => $query->connectionName,
            ]);
        });

        // TEMPORARILY REMOVE TRANSACTION TO TEST IF DATA SAVES WITHOUT IT
        // Comment out the DB::transaction wrapper below and uncomment the block inside to test without transaction


        try {
            DB::transaction(function () use ($application, $agent, $request) {

                Log::info('DEBUG: Inside transaction block');

                // Step 1: Store the collection photo (shared for all verifications in this batch)
                $photoPath = $request->file('photo')->store('collections/photos', 'public');
                Log::info('DEBUG: Photo stored', ['path' => $photoPath]);

                // Step 2: Process signature - decode base64 and save as image file if needed
                $signaturePath = $this->processSignature($request->signature_data, 'collections');
                Log::info('DEBUG: Signature processed', ['path' => $signaturePath]);

                // Step 3: Log location coordinates for traceability
                Log::info('DEBUG: Collection verification location captured', [
                    'application_id' => $application->id,
                    'agent_id' => $agent->id,
                    'latitude' => $request->location_lat,
                    'longitude' => $request->location_lng,
                ]);

                // Step 4: Prepare collected quantities data for all commodities
                $collectedQuantities = [];
                $fraudFlag = false;

                Log::info('DEBUG: Processing commodity allocations for JSON storage', ['count' => count($application->commodity_allocations)]);
                foreach ($application->commodity_allocations as $allocation) {
                    // Get collected quantity for this commodity
                    $collectedQuantity = $request->collected_quantities[$allocation->id] ?? 0;

                    // Find commodity by name
                    $commodity = \App\Models\Commodity::where('name', $allocation->commodity_name)->first();
                    if (!$commodity) {
                        Log::warning('DEBUG: Commodity not found', ['commodity_name' => $allocation->commodity_name]);
                        continue; // Skip if commodity not found (should not happen)
                    }

                    // Store collected quantity with commodity info
                    $collectedQuantities[$allocation->id] = [
                        'commodity_id' => $commodity->id,
                        'commodity_name' => $allocation->commodity_name,
                        'allocated_quantity' => $allocation->allocated_quantity,
                        'collected_quantity' => $collectedQuantity,
                    ];

                    // Check for fraud indicators (set flag if any commodity has issues)
                    // Distance check: if agent location and farmer location available, check if >10km
                    if ($request->location_lat && $request->location_lng && $application->farm && $application->farm->location_lat && $application->farm->location_lng) {
                        $distance = $this->calculateDistance($request->location_lat, $request->location_lng, $application->farm->location_lat, $application->farm->location_lng);
                        if ($distance > 10) {
                            $fraudFlag = true; // Remote verification suspicious
                            Log::info('DEBUG: Fraud flag set due to distance', ['distance' => $distance]);
                        }
                    }

                    // Collected > allocated check (though validation prevents, if any)
                    if ($collectedQuantity > $allocation->allocated_quantity) {
                        $fraudFlag = true;
                        Log::info('DEBUG: Fraud flag set due to excess collection', ['collected' => $collectedQuantity, 'allocated' => $allocation->allocated_quantity]);
                    }
                }

                // Step 4b: Create single collection verification record for the application
                Log::info('DEBUG: Preparing to create single CollectionVerification', [
                    'data' => [
                        'application_id' => $application->id,
                        'agent_id' => $agent->id,
                        'commodity_photo' => $photoPath,
                        'collected_quantities' => $collectedQuantities,
                        'collection_notes' => $request->collection_notes,
                        'location_lat' => $request->location_lat,
                        'location_lng' => $request->location_lng,
                        'signature' => $signaturePath,
                        'fraud_flag' => $fraudFlag,
                    ]
                ]);

                $verification = CollectionVerification::create([
                    'application_id' => $application->id,
                    'agent_id' => $agent->id,
                    'commodity_photo' => $photoPath,
                    'collected_quantities' => $collectedQuantities,
                    'collection_notes' => $request->collection_notes,
                    'location_lat' => $request->location_lat,
                    'location_lng' => $request->location_lng,
                    'signature' => $signaturePath,
                    'fraud_flag' => $fraudFlag,
                ]);

                Log::info('DEBUG: CollectionVerification created successfully', ['verification_id' => $verification->id]);

                // Step 5: Process stock adjustments for each commodity
                foreach ($collectedQuantities as $allocationId => $quantityData) {
                    $shortage = $quantityData['allocated_quantity'] - $quantityData['collected_quantity'];
                    Log::info('DEBUG: Calculated shortage', ['allocation_id' => $allocationId, 'shortage' => $shortage]);

                    if ($shortage > 0) {
                        Log::info('DEBUG: Incrementing allocation stock', ['shortage' => $shortage]);
                        // Add the shortage back to tenant stock in allocations table
                        $affected = \App\Models\Allocation::where('season_id', $application->season_id)
                            ->where('commodity_id', $quantityData['commodity_id'])
                            ->increment('allocated_stock', $shortage);
                        Log::info('DEBUG: Allocation updated', ['affected_rows' => $affected]);

                        // Log this adjustment in stock_adjustments table for audit
                        $stockAdjustment = StockAdjustment::create([
                            'commodity_id' => $quantityData['commodity_id'],
                            'season_id' => $application->season_id,
                            'quantity' => $shortage,
                            'type' => 'increase',
                            'reason' => 'Farmer collected less than approved',
                            'verified_by' => $agent->id,
                        ]);
                        Log::info('DEBUG: StockAdjustment created', ['stock_adjustment_id' => $stockAdjustment->id]);
                    }
                }

                Log::info('DEBUG: Transaction about to complete', [
                    'verification_created' => true,
                    'stock_adjustments_attempted' => count(array_filter($collectedQuantities, function($data) {
                        return ($data['allocated_quantity'] - $data['collected_quantity']) > 0;
                    })),
                ]);


            });
        } catch (\Exception $transactionException) {
            Log::error('DEBUG: Transaction exception', [
                'message' => $transactionException->getMessage(),
                'trace' => $transactionException->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to process collection verification: ' . $transactionException->getMessage()], 500);
        }


        // After transaction (or without), check if records exist
        $verificationCount = CollectionVerification::where('application_id', $application->id)->count();
        Log::info('DEBUG: Post-transaction verification count check', [
            'application_id' => $application->id,
            'verifications_in_db' => $verificationCount,
        ]);

        if ($verificationCount === 0) {
            Log::error('DEBUG: NO VERIFICATION RECORDS FOUND AFTER PROCESSING - INVESTIGATE!');
        }

        Log::info('DEBUG: Method completed successfully', [
            'application_id' => $application->id,
            'verification_created' => true,
            'verifications_in_db' => $verificationCount,
        ]);

        ToastMagic::success('Collection submitted successfully');
        return response()->json(['message' => 'Collection submitted successfully']);
    }

public function assignedReturns(Request $request)
{
    $agent = optional(Auth::guard('tenant')->user())->agent;
    $filter = $request->get('filter');
    $season = $request->get('season');
    $status = $request->get('status');

    if (!$agent || empty($agent->center_id)) {
        if ($request->ajax()) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
            ]);
        }
        $seasons = Season::all();
        return view('agent.verify-return', compact('seasons'))
            ->with('agentSetupRequired', true);
    }

    $apps = Application::with([
            'farmer:id,full_name,registration_number,phone,bvn,nin,address',
            'farm:id,size,location',
            'season:id,name,status,loan_type',
            'applicationCenter',
            'commodity_allocations',
            'applicationCommodities.commodity:id,name,category,unit,price_per_unit',
            'collectionVerification' // eager load for checking collection
        ])
        ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
        ->whereHas('applicationCenter', fn($q) => $q->where('return_center_id', $agent->center_id))
        ->whereHas('season', fn($s) => $s->where('loan_type', 'complete-loan'))
        ->whereHas('collectionVerification') // <--- Only include if farmer collected commodity
        ->whereDoesntHave('monetaryReturn')
        ->when($filter, fn($q) => $q->whereHas('farmer', fn($f) =>
            $f->where('full_name', 'like', "%$filter%")
              ->orWhere('registration_number', 'like', "%$filter%")
        ))
        ->when($season, fn($q) => $q->whereHas('season', fn($s) => $s->where('slug', $season)))
        ->get()
        ->map(function ($app) {
            $app->return_status = $app->returnVerification()->exists() ? 'verified' : 'pending';
            // Compute expected return for complete-loan seasons based on seed current market price
            $expected = [
                'commodity' => null,
                'unit' => null,
                'price_used' => null,
                'quantity' => null,
            ];
            // Identify seed commodity from application commodities
            $seedLine = optional($app->applicationCommodities)->first(function ($line) {
                return optional($line->commodity)->category === 'seed';
            });
            if ($seedLine && $seedLine->commodity) {
                $expected['commodity'] = $seedLine->commodity->name;
                $expected['unit'] = $seedLine->commodity->unit;
                // Get current market price for this seed in this season
                $market = CommodityMarketPrice::where('commodity_id', $seedLine->commodity->id)
                    ->where('season_id', $app->season_id)
                    ->first();
                $price = $market && $market->current_price ? $market->current_price : ($seedLine->commodity->price_per_unit ?? null);
                if ($price && $price > 0) {
                    $expected['price_used'] = $price;
                    $expected['quantity'] = round(($app->total_loan ?? 0) / $price, 2);
                }
            }
            $app->expected_return = $expected;
            return $app;
        })
        ->map(function ($app) {
            $app->return_status = $app->returnVerification()->exists() ? 'verified' : 'pending';
            $app->encrypted_id = Crypt::encryptString($app->id); // Add encrypted ID for secure URLs
            // Compute expected return for complete-loan seasons based on seed current market price
            $expected = [
                'commodity' => null,
                'unit' => null,
                'price_used' => null,
                'quantity' => null,
            ];
            // Identify seed commodity from application commodities
            $seedLine = optional($app->applicationCommodities)->first(function ($line) {
                return optional($line->commodity)->category === 'seed';
            });
            if ($seedLine && $seedLine->commodity) {
                $expected['commodity'] = $seedLine->commodity->name;
                $expected['unit'] = $seedLine->commodity->unit;
                // Get current market price for this seed in this season
                $market = CommodityMarketPrice::where('commodity_id', $seedLine->commodity->id)
                    ->where('season_id', $app->season_id)
                    ->first();
                $price = $market && $market->current_price ? $market->current_price : ($seedLine->commodity->price_per_unit ?? null);
                if ($price && $price > 0) {
                    $expected['price_used'] = $price;
                    $expected['quantity'] = round(($app->total_loan ?? 0) / $price, 2);
                }
            }
            $app->expected_return = $expected;
            return $app;
        })
        ->filter(fn($app) => !$status || $app->return_status === $status)
        ->values();

    if ($request->ajax()) {
        $perPage = 10;
        $page = $request->get('page', 1);
        $paged = $apps->slice(($page - 1) * $perPage, $perPage)->values();
        return response()->json([
            'data' => $paged,
            'current_page' => $page,
            'last_page' => ceil($apps->count() / $perPage),
            'total' => $apps->count(),
        ]);
    }

    $seasons = Season::all();
    return view('agent.verify-return', compact('seasons'));
}


    public function showCollectionVerification($uuid)
    {
        $agent = optional(Auth::guard('tenant')->user())->agent;

        // Check if the parameter is an encrypted ID (token) or UUID
        try {
            $applicationId = Crypt::decryptString($uuid);
            $application = Application::with([
                'farmer:id,full_name,registration_number,phone,bvn,nin,address',
                'farm:id,size,location',
                'season:id,name,status,loan_type',
                'commodity_allocations',
                'applicationCenter',
                'collectionVerification',
                'monetaryReturn'
            ])
            ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'payment_status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
            ->findOrFail($applicationId);
        } catch (\Exception $e) {
            // If decryption fails, treat it as UUID
            $application = Application::with([
                'farmer:id,full_name,registration_number,phone,bvn,nin,address',
                'farm:id,size,location',
                'season:id,name,status,loan_type',
                'commodity_allocations',
                'applicationCenter',
                'collectionVerification',
                'monetaryReturn'
            ])
            ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'payment_status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
            ->whereUuid($uuid)->firstOrFail();
        }

        if (!$application || !$agent || empty($agent->center_id) || !$application->applicationCenter || $application->applicationCenter->collection_center_id !== $agent->center_id) {
            abort(403, 'Not authorized for this application');
        }

        // Check if already verified
        if ($application->collectionVerification()->exists()) {
            ToastMagic::info('This application has already been verified');
            return redirect()->route('agent.verify.collection');
        }

        return view('agent.collection-verification', compact('application'));
    }

    public function showReturnVerification($encryptedId)
    {
        $applicationId = Crypt::decryptString($encryptedId);
        $agent = optional(Auth::guard('tenant')->user())->agent;
        $application = Application::with([
            'farmer:id,full_name,registration_number,phone,bvn,nin,address',
            'farm:id,size,location',
            'season:id,name,status,loan_type',
            'applicationCenter',
            'commodity_allocations',
            'applicationCommodities.commodity:id,name,category,unit,price_per_unit',
            'collectionVerification.agent.user:id,name', // eager load collection verification with agent and user
            'returnVerification'
        ])
        ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
        ->findOrFail($applicationId);

        if (!$agent || empty($agent->center_id) || $application->applicationCenter->return_center_id !== $agent->center_id) {
            abort(403, 'Not authorized for this application');
        }

        // Check if already verified
        if ($application->returnVerification()->exists()) {
            ToastMagic::info('This application has already been verified');
            return redirect()->route('agent.verify.return');
        }

        // Check if collection was done first
        if (!$application->collectionVerification()->exists()) {
            ToastMagic::error('Collection must be verified before processing returns');
            return redirect()->route('agent.verify.return');
        }

        // Compute expected return for complete-loan seasons based on seed current market price
        $expected = [
            'commodity' => null,
            'unit' => null,
            'price_used' => null,
            'quantity' => null,
        ];
        $seedLine = optional($application->applicationCommodities)->first(function ($line) {
            return optional($line->commodity)->category === 'seed';
        });
        if ($seedLine && $seedLine->commodity) {
            $expected['commodity'] = $seedLine->commodity->name;
            $expected['unit'] = $seedLine->commodity->unit;
            $market = CommodityMarketPrice::where('commodity_id', $seedLine->commodity->id)
                ->where('season_id', $application->season_id)
                ->first();
            $price = $market && $market->current_price ? $market->current_price : ($seedLine->commodity->price_per_unit ?? null);
            if ($price && $price > 0) {
                $expected['price_used'] = $price;
                $expected['quantity'] = round(($application->total_loan ?? 0) / $price, 2);
            }
        }

        return view('agent.return-verification', compact('application', 'expected'));
    }

    public function storeReturn(Request $request)
    {
        Log::info('DEBUG: storeReturn method called', [
            'request_all' => $request->all(),
            'is_tenant_request' => tenancy()->initialized,
            'current_tenant' => tenancy()->tenant?->domain,
        ]);

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'photo' => 'required|image|max:4096',
            'returned_quantity' => 'required|numeric|min:0',
            'shortfall_reason' => 'nullable|string',
            'signature_data' => 'nullable|string',
            'signature_type' => 'nullable|string',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
        ]);

        Log::info('DEBUG: Return validation passed');

        // Validate location coordinates are provided (required for verification)
        if (
            $request->input('location_lat') === null ||
            $request->input('location_lng') === null ||
            !is_numeric($request->location_lat) ||
            !is_numeric($request->location_lng)
        ) {
            Log::warning('DEBUG: Return location coordinates validation failed', [
                'location_lat' => $request->input('location_lat'),
                'location_lng' => $request->input('location_lng'),
            ]);
            return response()->json(['message' => 'Location coordinates are required for verification'], 422);
        }

        // Validate signature is provided
        if (!$request->signature_data || !is_string($request->signature_data)) {
            Log::warning('DEBUG: Return signature validation failed', ['signature_provided' => is_string($request->signature_data)]);
            return response()->json(['message' => 'Signature is required for verification'], 422);
        }

        Log::info('DEBUG: Return post-validation checks passed');

        $agent = optional(Auth::guard('tenant')->user())->agent;
        $application = Application::with(['applicationCommodities.commodity:id,name,category,unit,price_per_unit', 'season:id,loan_type'])->findOrFail($request->application_id);

        Log::info('DEBUG: Return agent and application loaded', [
            'agent_id' => $agent?->id,
            'agent_center_id' => $agent?->center_id,
            'application_center_return_id' => $application->applicationCenter->return_center_id,
        ]);

        if (!$agent || empty($agent->center_id) || $application->applicationCenter->return_center_id !== $agent->center_id) {
            Log::warning('DEBUG: Return authorization failed');
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        // Compute expected return for complete-loan seasons
        $expectedQuantity = 0.0;
        $seedLine = optional($application->applicationCommodities)->first(function ($line) {
            return optional($line->commodity)->category === 'seed';
        });
        if ($seedLine && $seedLine->commodity) {
            $market = CommodityMarketPrice::where('commodity_id', $seedLine->commodity->id)
                ->where('season_id', $application->season_id)
                ->first();
            $price = $market && $market->current_price ? $market->current_price : ($seedLine->commodity->price_per_unit ?? null);
            if ($price && $price > 0) {
                $expectedQuantity = round(($application->total_loan ?? 0) / $price, 2);
            }
        }

        $variance = $expectedQuantity - $request->returned_quantity;
        $partialReturn = $variance > 0;

        // Process signature - decode base64 and save as image file
        $signaturePath = $this->processSignature($request->signature_data, 'returns');

        // Log location coordinates for traceability
        Log::info('Return verification location captured', [
            'application_id' => $application->id,
            'agent_id' => $agent->id,
            'latitude' => $request->location_lat,
            'longitude' => $request->location_lng,
        ]);

        $data = [
            'application_id' => $application->id,
            'agent_id' => $agent->id,
            'returned_commodity_photo' => $request->file('photo')->store('returns/photos', 'public'),
            'expected_quantity' => $expectedQuantity,
            'returned_quantity' => $request->returned_quantity,
            'variance' => $variance,
            'shortfall_reason' => $request->shortfall_reason,
            'partial_return' => $partialReturn,
            'returned_photo' => $request->hasFile('returnedPhoto') ? $request->file('returnedPhoto')->store('returns/items', 'public') : null,
            'signature' => $signaturePath,
            'location_lat' => $request->location_lat,
            'location_lng' => $request->location_lng,
        ];

        $returnVerification = ReturnVerification::create($data);

        ToastMagic::success('Return submitted successfully');
        return response()->json(['message' => 'Return submitted successfully']);
    }

    /**
     * Process signature data - decode base64 and save as image file if needed
     */
    private function processSignature($signatureData, $type = 'collections')
    {
        // Check if it's a base64 data URL
        if (Str::startsWith($signatureData, 'data:image/')) {
            // Extract base64 data from data URL
            $data = explode(',', $signatureData);
            $base64Data = $data[1] ?? $signatureData;

            // Decode base64
            $decodedData = base64_decode($base64Data);

            // Generate unique filename
            $filename = 'signature_' . time() . '_' . Str::random(10) . '.png';
            $path = "signatures/{$type}/" . $filename;

            // Store file in public disk
            Storage::disk('public')->put($path, $decodedData);

            Log::info("Signature image saved", [
                'path' => $path,
                'type' => $type,
                'size' => strlen($decodedData)
            ]);

            // Return the file path for database storage
            return $path;
        }

        // If not base64, assume it's already a file path
        return $signatureData;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Generate and download PDF for collection verification
     */
    public function downloadCollectionPDF($applicationId)
    {
        try {
            $agent = optional(Auth::guard('tenant')->user())->agent;
            if (!$agent || empty($agent->center_id)) {
                abort(403, 'Agent not authorized');
            }

            $application = Application::with([
                'farmer:id,full_name,registration_number,phone,address',
                'farm:id,size,location',
                'season:id,name,loan_type',
                'applicationCenter:id,collection_center_id,return_center_id',
                'commodity_allocations:id,commodity_name,allocated_quantity,allocated_quantity',
                'applicationCommodities.commodity:id,name,unit',
                'collectionVerification.agent.user:id,name',
                'tenant:id'
            ])->findOrFail($applicationId);

            // Validate relationships and authorization
            if (!$application->applicationCenter ||
                $application->applicationCenter->collection_center_id !== $agent->center_id) {
                abort(403, 'Not authorized to view this collection PDF');
            }

            if (!$application->collectionVerification()->exists()) {
                abort(404, 'Collection verification not found');
            }

            $collectionVerification = $application->collectionVerification->first();

            // Validate verification has required data
            if (!$collectionVerification) {
                abort(404, 'Collection verification data not found');
            }

            $fileName = sprintf(
                'farmer_%s_collection_%s_season_%s.pdf',
                $application->farmer->registration_number ?? 'unknown',
                $collectionVerification->id,
                str_replace(' ', '_', $application->season->name ?? 'unknown')
            );

            $pdfData = [
                'organization_name' => 'Association of Farmers in the Northeast of Nigeria',
                'report_type' => 'Farmer Collection Verification Report',
                'season_name' => $application->season->name ?? 'N/A',
                'tenant_name' => $application->tenant->name ?? 'N/A',
                'farmer' => $application->farmer,
                'farm' => $application->farm,
                'application' => $application,
                'agent' => optional($collectionVerification->agent),
                'verification' => $collectionVerification,
                'commodity_allocations' => $application->commodity_allocations ?? collect(),
                'qr_data' => url("/agent/collections/{$applicationId}/verify"),
                'generated_at' => now()->toDateTimeString(),
                'system_name' => 'AFNON Management System'
            ];

            $pdf = Pdf::loadView('agent.pdf.collection', $pdfData);
            return $pdf->download("collection_{$application->reference_number}.pdf");

        } catch (\Exception $e) {
            Log::error('Collection PDF Generation Error', [
                'error' => $e->getMessage(),
                'applicationId' => $applicationId,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Error generating PDF report. Please try again.');
        }
    }

    /**
     * Generate and download PDF for return verification
     */
    public function downloadReturnPDF($applicationId)
    {
        try {
            $agent = optional(Auth::guard('tenant')->user())->agent;
            if (!$agent || empty($agent->center_id)) {
                abort(403, 'Agent not authorized');
            }

            $application = Application::with([
                'farmer:id,full_name,registration_number,phone,address',
                'farm:id,size,location',
                'season:id,name,loan_type',
                'applicationCenter:id,collection_center_id,return_center_id',
                'commodity_allocations:id,commodity_name,allocated_quantity,collected_quantity',
                'applicationCommodities.commodity:id,name,unit',
                'returnVerification.agent.user:id,name',
                'collectionVerification:id,application_id', // For checking collection exists
                'tenant:id'
            ])->findOrFail($applicationId);

            // Validate relationships and authorization
            if (!$application->applicationCenter ||
                $application->applicationCenter->return_center_id !== $agent->center_id) {
                abort(403, 'Not authorized to view this return PDF');
            }

            if (!$application->returnVerification()->exists()) {
                abort(404, 'Return verification not found');
            }

            if (!$application->collectionVerification) {
                abort(404, 'Collection verification must exist before return verification');
            }

            $returnVerification = $application->returnVerification->first();

            // Validate verification has required data
            if (!$returnVerification) {
                abort(404, 'Return verification data not found');
            }

            $fileName = sprintf(
                'farmer_%s_return_%s_season_%s.pdf',
                $application->farmer->registration_number ?? 'unknown',
                $returnVerification->id,
                str_replace(' ', '_', $application->season->name ?? 'unknown')
            );

            $pdfData = [
                'organization_name' => 'Association of Farmers in the Northeast of Nigeria',
                'report_type' => 'Farmer Return Verification Report',
                'season_name' => $application->season->name ?? 'N/A',
                'tenant_name' => $application->tenant->name ?? 'N/A',
                'farmer' => $application->farmer,
                'farm' => $application->farm,
                'application' => $application,
                'agent' => optional($returnVerification->agent),
                'verification' => $returnVerification,
                'commodity_allocations' => $application->commodity_allocations ?? collect(),
                'qr_data' => url("/agent/returns/" . Crypt::encryptString($applicationId) . "/verify"),
                'generated_at' => now()->toDateTimeString(),
                'system_name' => 'AFNON Management System'
            ];

            $pdf = Pdf::loadView('agent.pdf.return', $pdfData);
            return $pdf->download("return_{$application->reference_number}.pdf");

        } catch (\Exception $e) {
            Log::error('Return PDF Generation Error', [
                'error' => $e->getMessage(),
                'applicationId' => $applicationId,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Error generating PDF report. Please try again.');
        }
    }

    /**
     * Helper method to generate and download PDF with proper error handling and logging
     */
    private function generatePDF($view, $data, $fileName, $verificationType)
    {
        try {
            // Check if public storage disk is available, fallback to local if missing
            if (!Storage::disk('public')->exists('.')) {
                Log::warning('Public storage disk not properly configured, using local disk instead');
                // Continue anyway as Storage::url() should still work
            }

            // Generate PDF with DomPDF
            $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');

            // Create file path and store PDF
            $filePath = "pdfs/verifications/{$fileName}";
            $result = Storage::disk('public')->put($filePath, $pdf->output());

            if ($result === false) {
                throw new \Exception('Failed to save PDF to storage');
            }

            // Log successful PDF generation
            Log::info("{$verificationType} PDF Generated Successfully", [
                'farmer_id' => $data['farmer']->id ?? null,
                'farmer_name' => $data['farmer']->full_name ?? 'Unknown',
                'verification_id' => $data['verification']->id ?? null,
                'file_path' => $filePath,
                'generated_by' => optional(auth('tenant')->user())->id,
                'ip_address' => request()->ip(),
            ]);

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error("{$verificationType} PDF Generation Failed", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'data_keys' => array_keys($data),
            ]);
            throw $e; // Re-throw to be handled by calling method
        }
    }
}
