<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Models\{
    Farmer,
    Farm,
    Application,
    ApplicationCommodity,
    Commodity,
    Season,
    CommodityAllocation,
    CommodityMarketPrice,
    ApplicationCenter,
};
use App\Helpers\SmsHelper;
use App\Models\Center;
use App\Services\ApplicationCacheService;
use App\Services\CommodityDisbursementService;
use App\Services\PerformanceOptimizationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource with performance optimization
     */
    public function index(Request $request)
    {
        $filters = $request->only(['season', 'status', 'search']);
        $perPage = 20; // Increased for better UX

        // Optimized query with selective loading and eager loading
        $query = Application::with([
            'farmer:id,full_name,phone,bvn,nin,registration_number,cluster',
            'season:id,name,status',
            'commodities:id,name,unit,price_per_unit',
            'farm:id,size,location'
        ])->select([
            'id', 'uuid', 'farmer_id', 'farm_id', 'season_id', 'status',
            'total_loan', 'disbursed_amount', 'created_at', 'reference_number'
        ])->orderBy('created_at', 'desc');

        // Apply filters with optimized queries
        if (!empty($filters['season'])) {
            $query->whereHas('season', function ($q) use ($filters) {
                $q->where('name', $filters['season']);
            });
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $query->whereHas('farmer', function ($q) use ($filters) {
                $q->where('full_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('registration_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        $applications = $query->paginate($perPage);

        // Use performance optimization service for cached data
        $seasons = PerformanceOptimizationService::getSeasonsList();
        $centers = PerformanceOptimizationService::getCentersList();
        $stats = PerformanceOptimizationService::getApplicationStats($filters['season'] ?? null);

        // Add response caching headers for better performance
        return Response::view('admin.applications.index', compact(
            'applications',
            'seasons',
            'stats'
        ) + [
            'collectionCenters' => $centers['collection'],
            'returnCenters' => $centers['return']
        ])->header('Cache-Control', 'public, max-age=300'); // 5 minutes
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $season = Season::where('status', 'open')->latest()->first();

        if (!$season) {
            // If admin is logged in → send them to season creation page
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                ToastMagic::error('Please create an open season before accepting applications.');
                return redirect()
                    ->route('admin.seasons.create');
            }

            // If public user → show custom no-season page
            return view('application.no-season');
        }

        $commodities = $season->commodities()->get();
        $seeds = $commodities->where('category', 'seed')->values()->all();
        $others = $commodities->where('category', '!=', 'seed')->values()->all();

        return view('application.index', compact('season', 'seeds', 'others'));
    }


    protected function generateRegistrationNumber($seasonType, $year)
    {
        // Generate tenant prefix dynamically from tenant name
        $tenantPrefix = 'TN'; // Default fallback
        if (tenant()) {
            $tenantName = tenant()->id ?? '';
            if (!empty($tenantName)) {
                $prefix = strtoupper(substr($tenantName, 0, 2));
                // If name is too short, pad with 'X'
                if (strlen($prefix) < 2) {
                    $prefix = str_pad($prefix, 2, 'X', STR_PAD_RIGHT);
                }
                $tenantPrefix = $prefix;
            }
        }

        $shortYear = substr($year, -2); // Get last 2 digits of year

        // Get the last farmer for this year to generate sequence
        $lastFarmer = Farmer::whereYear('created_at', $year)->latest()->first();
        $sequence = $lastFarmer ? intval(substr($lastFarmer->registration_number, -4)) + 1 : 1;

        // Format: REG-KN-DRY-25-AF-0001
        return "REG-" . $tenantPrefix . "-" . strtoupper($seasonType) . "-" . $shortYear . "-AF" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    protected function generateReferenceNumber($seasonType)
    {
        // Generate tenant prefix dynamically from tenant name
        $tenantPrefix = 'TN'; // Default fallback
        if (tenant()) {
            $tenantName = tenant()->id ?? '';
            if (!empty($tenantName)) {
                $prefix = strtoupper(substr($tenantName, 0, 2));
                // If name is too short, pad with 'X'
                if (strlen($prefix) < 2) {
                    $prefix = str_pad($prefix, 2, 'X', STR_PAD_RIGHT);
                }
                $tenantPrefix = $prefix;
            }
        }

        $shortYear = substr(now()->year, -2); // Get last 2 digits of current year

        // Get current season to determine season type
        $currentSeason = Season::where('status', 'active')->first();

        // Generate unique 4-digit sequence
        $sequence = rand(1000, 9999);

        // Format: AF:REF-KN-DRY-25-0001
        return "AF:REF-" . $tenantPrefix . "-" . strtoupper($seasonType) . "-" . $shortYear . "-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    public function store(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'nin' => 'required|string',
            'bvn' => 'required|string',
            'state' => 'required|string',
            'lga' => 'required|string',
            'address' => 'required|string',
            'farm_location' => 'required|string',
            'farm_size' => 'required|numeric|min:0.1',
            'cluster_location' => 'nullable|string',
            'season_id' => 'required|exists:seasons,id',
            'selected_seed' => 'required|exists:commodities,id',
        ]);
        $phoneExist = Application::where('season_id', $validated['season_id'])->whereHas('farmer', function ($q) use ($validated) {
            $q->where('phone', $validated['phone']);
        })->exists();

        $existing = Application::where('season_id', $validated['season_id'])
            ->whereHas('farmer', function ($q) use ($validated) {
                $q->where('nin', $validated['nin'])
                    ->orWhere('bvn', $validated['bvn']);
            })
            ->exists();

        if ($phoneExist) {
            ToastMagic::error('The provided phone number has already been used for this season.');
            return back()->withErrors([
                'phone' => 'This phone number has already been used for this season.',
            ])->withInput();
        }
        if ($existing) {
            ToastMagic::error('The provided NIN or BVN has already been used for this season.');
            return back()->withErrors([
                'nin' => 'This NIN has already been used for this season.',
                'bvn' => 'This BVN has already been used for this season.',
            ])->withInput();
        }


        DB::beginTransaction();

        try {
            // Create Farmer
            $season = Season::findOrFail($validated['season_id']);
            $registrationNumber = $this->generateRegistrationNumber($season->type, now()->year);
            // return $registrationNumber;
            $farmer = Farmer::create([
                'uuid' => Str::uuid(),
                'registration_number' => $registrationNumber,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'nin' => $validated['nin'],
                'bvn' => $validated['bvn'],
                'state' => $validated['state'],
                'lga' => $validated['lga'],
                'address' => $validated['address'],
                'cluster' => $validated['cluster_location'],
            ]);

            // Create Farm
            $farm = $farmer->farms()->create([
                'uuid' => Str::uuid(),
                'location' => $validated['farm_location'],
                'size' => $validated['farm_size'],
            ]);


            // Reference
            $refNumber = $this->generateReferenceNumber($season->type);

            // Seed Commodity
            $seed = Commodity::findOrFail($validated['selected_seed']);
            $seedQty = $seed->quantity_per_hectare * $farm->size;
            $seedVal = $seedQty * $seed->price_per_unit;

            // Other Commodities
            $others = Commodity::where('category', '!=', 'seed')
                ->whereHas('seasons', fn($q) => $q->where('season_id', $season->id))
                ->get();

            $otherTotal = 0;
            $applicationCommodities = [];

            foreach ($others as $item) {
                $qty = $item->quantity_per_hectare * $farm->size;
                $val = $qty * $item->price_per_unit;
                $otherTotal += $val;
                $applicationCommodities[] = [
                    'commodity_id' => $item->id,
                    'quantity' => $qty,
                ];
            }

            $insuranceRate = $season->insurance_rate ?? 1;
            $totalLoan = $seedVal + $otherTotal;
            $insuranceAmount = $totalLoan * ($insuranceRate / 100);
            $finalTotal = $totalLoan + $insuranceAmount;
            $equity = $finalTotal / 2;

            // Create Application
            $application = Application::create([
                'uuid' => Str::uuid(),
                'farmer_id' => $farmer->id,
                'farm_id' => $farm->id,
                'season_id' => $season->id,
                'insurance_rate' => $insuranceRate,
                'insurance_amount' => $insuranceAmount,
                'total_loan' => $finalTotal,
                'equity' => $equity,
                'disbursed_amount' => $equity,
                'reference_number' => $refNumber,
            ]);

            // Attach seed and others
            $application->applicationCommodities()->create([
                'uuid' => Str::uuid(),
                'commodity_id' => $seed->id,
                'quantity' => $seedQty,
            ]);

            foreach ($applicationCommodities as $item) {
                $application->applicationCommodities()->create([
                    'uuid' => Str::uuid(),
                    'commodity_id' => $item['commodity_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            ToastMagic::success('Application submitted successfully.');
            return redirect()->route('applications.slip', ['uuid' => $application->uuid]);
        } catch (\Throwable $e) {
            DB::rollback();
            report($e);
            ToastMagic::error('An error occurred. Please try again.');
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Show acknowledgment slip with caching and performance optimization
     */
    public function acknowledgment($uuid)
    {
        // Use cached application data
        $application = Application::findByUuidCached($uuid);

        if (!$application) {
            abort(404, 'Application not found');
        }

        // Add response caching for static content
        return Response::view('application.acknowledgment', compact('application'))
            ->header('Cache-Control', 'public, max-age=1800') // 30 minutes
            ->header('ETag', md5($application->updated_at . $uuid));
    }
    public function verify($reference)
    {
        // Use cached application lookup for better performance
        $application = Application::findByReferenceCached($reference);

        if (!$application) {
            // Return not-found view without caching to avoid cache tagging issues
            return view('application.verify-not-found', [
                'reference' => $reference
            ]);
        }

        // Return verification view with basic caching headers
        return Response::view('application.verify', compact('application'))
            ->header('Cache-Control', 'public, max-age=1800') // 30 minutes
            ->header('ETag', md5($application->updated_at . $reference));
    }

    public function downloadSlip($uuid)
    {
        // Use cached application data
        $application = Application::findByUuidCached($uuid);

        if (!$application) {
            abort(404, 'Application not found');
        }

        // Generate PDF without caching for now (to avoid cache tagging issues)
        $pdf = Pdf::loadView('application.slip-pdf', compact('application'))
            ->setPaper('a4');

        $filename = 'Acknowledgement_Slip_' . $application->reference_number . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadVerification($reference)
    {
        // Use cached application data
        $application = Application::findByReferenceCached($reference);

        if (!$application) {
            abort(404, 'Application not found');
        }

        // Generate PDF without caching for now (to avoid cache tagging issues)
        $pdf = Pdf::loadView('application.verify-pdf', compact('application'))
            ->setPaper('a4');

        $filename = 'Verification_' . $application->reference_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        // Eager load to minimize queries
        $application = Application::with([
            'farmer:id,full_name,registration_number,phone,bvn,nin,address',
            'farm:id,size,location',
            'season',
            'commodities:id,name,quantity_per_hectare,price_per_unit,unit',
            'applicationCommodities.commodity',
            'commodity_allocations.commodity'
        ])->whereUuid($uuid)->firstOrFail();

        // Auto-calculate allocation based on qty_per_hectare × farm size
        $allocations = $application->commodities->map(function ($commodity) use ($application) {
            $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
            $farmSize = $application->farm->size ?? 0;
            $allocatedQty = $qtyPerHectare * $farmSize;

            return [
                'commodity' => $commodity->name,
                'qty_per_hectare' => $qtyPerHectare,
                'farm_size' => $farmSize,
                'allocated_quantity' => $allocatedQty,
                'unit_price' => $commodity->price_per_unit ?? 0,
                'total_value' => $allocatedQty * ($commodity->price_per_unit ?? 0),
            ];
        });
        // Fetch minimal center fields
        $collectionCenters = Center::whereIn('type', ['collection', 'both'])
            ->select('id', 'name', 'type')
            ->get();
        $returnCenters = Center::whereIn('type', ['return', 'both'])
            ->select('id', 'name', 'type')
            ->get();

        // Calculate proportional commodity disbursement
        $disbursementSummary = CommodityDisbursementService::getDisbursementSummary($application);

        return view('admin.applications.show', [
            'application' => $application,
            'allocations' => $allocations,
            'insurance_rate' => $application->insurance_rate,
            'insurance_amount' => $application->insurance_amount,
            'equity_held' => $application->equity,
            'disbursed_amount' => $application->disbursed_amount,
            'total_loan' => $application->total_loan,
            'collectionCenters' => $collectionCenters,
            'returnCenters' => $returnCenters,
            'disbursementSummary' => $disbursementSummary
        ]);
    }

    public function approve(Request $request, $uuid)
    {
        $request->validate([
            'collection_center_id' => 'required|exists:centers,id',
            'return_center_id' => 'required|exists:centers,id',
        ]);

        // Use select to limit data fetched
        $application = Application::select('id', 'uuid', 'status', 'season_id', 'farm_id', 'farmer_id')
            ->with([
                'farm:id,size',
                'season:id,collection_start_date,collection_end_date,return_deadline',
                'farmer:id,full_name,phone,registration_number',
                'commodities:id,name,quantity_per_hectare,price_per_unit'
            ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($application->status === 'approved') {
            ToastMagic::info('Application already approved.');
            return back();
        }

        // Optimize date generation
        $collectionStartDate = \Carbon\Carbon::parse($application->season->collection_start_date);
        $collectionEndDate = \Carbon\Carbon::parse($application->season->collection_end_date);
        $daysDiff = $collectionStartDate->diffInDays($collectionEndDate);

        $collectionDate = $collectionStartDate->addDays(rand(0, $daysDiff))->toDateString();
        $returnDate = max($application->season->return_deadline, \Carbon\Carbon::parse($collectionDate)->addDays(180)->toDateString());

        // Pre-calculate farm size to avoid repeated access
        $farmSize = $application->farm->size ?? 0;

        // Prepare allocation data with optimized loop - only 50% of commodities are allocated
        $allocations = $application->commodities->map(function ($commodity) use ($application, $farmSize) {
            $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
            $calculatedQty = $qtyPerHectare * $farmSize;
            $allocatedQty = $calculatedQty * 0.5; // Only 50% is allocated, other 50% held as equity

            // Fetch price from commodity market price first, fallback to commodities table
            $unitPrice = $this->getCommodityPrice($commodity->id, $application->season_id) ?? $commodity->price_per_unit ?? 0;

            return [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'application_id' => $application->id,
                'commodity_name' => $commodity->name,
                'qty_per_hectare' => $qtyPerHectare,
                'allocated_quantity' => $allocatedQty,
                'unit_price' => $unitPrice,
                'total_value' => $allocatedQty * $unitPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::transaction(function () use ($application, $allocations, $collectionDate, $returnDate, $request) {
            // Insert or update ApplicationCenter
            \App\Models\ApplicationCenter::updateOrInsert(
                ['application_id' => $application->id],
                [
                    'collection_center_id' => $request->input('collection_center_id'),
                    'return_center_id' => $request->input('return_center_id'),
                    'collection_date' => $collectionDate,
                    'return_date' => $returnDate,
                    'updated_at' => now(),
                ]
            );

            // Bulk insert allocations if any
            if (!empty($allocations)) {
                \App\Models\CommodityAllocation::insert($allocations);
            }

            // Update status with minimal query
            $application->update(['status' => 'approved', 'updated_at' => now()]);
        });

        ToastMagic::success('Application approved successfully.');
        return back();
    }



    public function bulkApprove(Request $request)
    {
        // Optimize center check with exists()
        if (!Center::exists()) {
            ToastMagic::error('No centers have been created yet. Please create a collection/return center before bulk approval.');
            return back();
        }

        $validated = $request->validate([
            'application_ids' => 'required|array|max:100', // Limit bulk operations
            'application_ids.*' => 'integer',
            'collection_center_id' => [
                'required',
                Rule::exists('centers', 'id')->where(function ($q) {
                    $q->whereIn('type', ['collection', 'both']);
                }),
            ],
            'return_center_id' => [
                'required',
                Rule::exists('centers', 'id')->where(function ($q) {
                    $q->whereIn('type', ['return', 'both']);
                }),
            ],
        ]);

        $applicationIds = $validated['application_ids'];
        $collectionCenterId = (int) $validated['collection_center_id'];
        $returnCenterId = (int) $validated['return_center_id'];

        // Optimize: Get already assigned applications in one query
        $assignedIds = ApplicationCenter::whereIn('application_id', $applicationIds)
            ->pluck('application_id')
            ->toArray();

        // Optimize: Select only needed fields and use efficient eager loading
        $applications = Application::select('id', 'status', 'season_id', 'farm_id', 'farmer_id')
            ->with([
                'farm:id,size',
                'season:id,collection_start_date,collection_end_date,return_deadline',
                'farmer:id,full_name,phone,registration_number',
                'commodities:id,name,quantity_per_hectare,price_per_unit'
            ])
            ->whereIn('id', $applicationIds)
            ->where('status', 'pending') // Only get pending applications
            ->whereNotIn('id', $assignedIds) // Exclude already assigned
            ->get();

        if ($applications->isEmpty()) {
            ToastMagic::info('No eligible applications found for bulk approval.');
            return back();
        }

        $bulkAllocations = [];
        $bulkCenters = [];
        $bulkUpdates = [];
        $now = now();

        // Optimize: Process applications in chunks to reduce memory usage
        $applications->chunk(50)->each(function ($chunk) use (&$bulkAllocations, &$bulkCenters, &$bulkUpdates, $collectionCenterId, $returnCenterId, $now) {
            foreach ($chunk as $application) {
                // Optimize date generation
                $collectionStartDate = \Carbon\Carbon::parse($application->season->collection_start_date);
                $collectionEndDate = \Carbon\Carbon::parse($application->season->collection_end_date);
                $daysDiff = $collectionStartDate->diffInDays($collectionEndDate);

                $collectionDate = $collectionStartDate->addDays(rand(0, $daysDiff))->toDateString();
                $returnDate = max($application->season->return_deadline, \Carbon\Carbon::parse($collectionDate)->addDays(180)->toDateString());

                $bulkCenters[] = [
                    'application_id' => $application->id,
                    'collection_center_id' => $collectionCenterId,
                    'return_center_id' => $returnCenterId,
                    'collection_date' => $collectionDate,
                    'return_date' => $returnDate,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Pre-calculate farm size
                $farmSize = $application->farm->size ?? 0;

                // Optimize commodity processing - only 50% of commodities are allocated
                foreach ($application->commodities as $commodity) {
                    $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
                    $calculatedQty = $qtyPerHectare * $farmSize;
                    $allocatedQty = $calculatedQty * 0.5; // Only 50% is allocated, other 50% held as equity

                    // Fetch price from commodity market price first, fallback to commodities table
                    $unitPrice = $this->getCommodityPrice($commodity->id, $application->season_id) ?? $commodity->price_per_unit ?? 0;

                    $bulkAllocations[] = [
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'application_id' => $application->id,
                        'commodity_name' => $commodity->name,
                        'qty_per_hectare' => $qtyPerHectare,
                        'allocated_quantity' => $allocatedQty,
                        'unit_price' => $unitPrice,
                        'total_value' => $allocatedQty * $unitPrice,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $bulkUpdates[] = $application->id;
            }
        });

        // Optimize: Use single transaction with chunked inserts
        DB::transaction(function () use ($bulkCenters, $bulkAllocations, $bulkUpdates, $now) {
            // Insert centers in chunks
            if (!empty($bulkCenters)) {
                collect($bulkCenters)->chunk(500)->each(function ($chunk) {
                    \App\Models\ApplicationCenter::insert($chunk->toArray());
                });
            }

            // Insert allocations in chunks
            if (!empty($bulkAllocations)) {
                collect($bulkAllocations)->chunk(500)->each(function ($chunk) {
                    \App\Models\CommodityAllocation::insert($chunk->toArray());
                });
            }

            // Update application statuses in chunks
            if (!empty($bulkUpdates)) {
                collect($bulkUpdates)->chunk(500)->each(function ($chunk) use ($now) {
                    \App\Models\Application::whereIn('id', $chunk->toArray())
                        ->update(['status' => 'approved', 'updated_at' => $now]);
                });
            }
        });

        $processedCount = count($bulkUpdates);

        // Clear performance caches after bulk operations
        PerformanceOptimizationService::clearCaches();

        ToastMagic::success("Bulk approval completed successfully. {$processedCount} applications approved.");
        return back();
    }

    public function reject(Request $request, $uuid)
    {
        $application = Application::where('uuid', $uuid)->firstOrFail();

        if ($application->status === 'rejected') {
            ToastMagic::info('Application already rejected.');
            return back();
        }

        $application->update(['status' => 'rejected']);

        // Clear performance caches after status change
        PerformanceOptimizationService::clearCaches();

        // Queue SMS job (use Laravel Jobs)
        // dispatch(new \App\Jobs\SendSmsJob([
        //     'phone' => $application->farmer->phone,
        //     'message' => "Dear {$application->farmer->full_name}, your application {$application->reference_number} has been rejected."
        // ]));

        ToastMagic::success('Application rejected successfully.');
        return back();
    }

    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'integer',
            'rejection_note' => 'nullable|string|max:1000',
        ]);

        $rejectedCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($validated, &$rejectedCount, &$skippedCount) {
            $applications = Application::with(['farmer:id,full_name,registration_number,phone'])
                ->whereIn('id', $validated['application_ids'])
                ->get();

            foreach ($applications as $application) {
                if ($application->status !== 'pending') {
                    $skippedCount++;
                    continue;
                }

                $application->update([
                    'status' => 'rejected',
                    'note' => $validated['rejection_note'] ?? 'Application rejected by admin.'
                ]);

                $rejectedCount++;
            }
        });

        // Build feedback message
        $message = "Bulk rejection completed: {$rejectedCount} applications rejected";
        if ($skippedCount > 0) {
            $message .= ", {$skippedCount} skipped (not pending)";
        }
        $message .= ".";

        ToastMagic::success($message);
        return back();
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }

    /**
     * Get commodity price from market price table first, fallback to commodities table
     */
    private function getCommodityPrice($commodityId, $seasonId)
    {
        // First try to get price from commodity market price table
        $marketPrice = CommodityMarketPrice::where('commodity_id', $commodityId)
            ->where('season_id', $seasonId)
            ->first();

        if ($marketPrice && $marketPrice->current_price) {
            return $marketPrice->current_price;
        }

        // Fallback to commodity table price
        $commodity = Commodity::find($commodityId);
        return $commodity ? $commodity->price_per_unit : 0;
    }
}
