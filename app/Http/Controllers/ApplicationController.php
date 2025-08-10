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
    ApplicationCenter,
};
use App\Helpers\SmsHelper;
use App\Models\Center;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Application::with(['farmer', 'season', 'commodities', 'farm']);

        // Filter by season
        if ($request->filled('season')) {
            $query->whereHas('season', function ($q) use ($request) {
                $q->where('name', $request->season);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search farmer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('farmer', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('bvn', 'like', "%{$search}%");
            });
        }
        $seasons = Season::all();
        $applications = $query->paginate(5);

        $collectionCenters = Center::whereIn('type', ['collection', 'both'])->get();
        $returnCenters = Center::whereIn('type', ['return', 'both'])->get();

        return view('admin.applications.index', compact('applications', 'collectionCenters', 'returnCenters', 'seasons'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $season = Season::where('status', 'open')->latest()->firstOrFail();
        $commodities = $season->commodities()->get();

        // Split into seeds and others
        $seeds = $commodities->where('category', 'seed')->values()->all();
        $others = $commodities->where('category', '!=', 'seed')->values()->all();
        return view('application.index', compact('season', 'seeds', 'others'));
    }
    protected function generateRegistrationNumber($seasonType, $year)
    {
        $tenantPrefix = strtoupper(tenant()->id ?? 'TN');
        $tenantName = substr($tenantPrefix, 0, 1) . substr($tenantPrefix, -2, length: 1);
        $lastFarmer = Farmer::whereYear('created_at', $year)->latest()->first();
        $sequence = $lastFarmer ? intval(substr($lastFarmer->registration_number, -6)) + 1 : 1;
        return "AF-" . $tenantName . '-' . strtoupper($seasonType) . "-$year-" . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    protected function generateReferenceNumber()
    {
        $tenantPrefix = strtoupper(tenant()->id ?? 'TN');
        $tenantName = substr($tenantPrefix, 0, 1) . substr($tenantPrefix, -2, length: 1);
        return 'REF-' . $tenantName . '-AFNON-' . rand(100000, 999999);
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
            $refNumber = $this->generateReferenceNumber();

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
     * Store a newly created resource in storage.
     */
    public function acknowledgment($uuid)
    {
        $application = Application::with(['farmer', 'farm', 'season', 'commodities'])->whereUuid($uuid)->firstOrFail();
        return view('application.acknowledgment', compact('application'));
    }
    public function verify($reference)
    {
        // Find application by reference number
        $application = Application::where('reference_number', $reference)
            ->with(['farmer', 'season', 'farm', 'commodities'])
            ->first();

        if (!$application) {
            return view('application.verify-not-found', [
                'reference' => $reference
            ]);
        }

        return view('application.verify', compact('application'));
    }

    public function downloadSlip($uuid)
    {
        $application = Application::whereUuid($uuid)->first();
        $application->load(['farmer', 'season', 'farm', 'commodities']);

        $pdf = Pdf::loadView('application.slip-pdf', compact('application'))
            ->setPaper('a4');

        return $pdf->download('Acknowledgement_Slip_' . $application->reference_number . '.pdf');
    }

    public function downloadVerification($reference)
    {
        $application = Application::where('reference_number', $reference)
            ->with(['farmer', 'season', 'farm', 'commodities'])
            ->firstOrFail();

        $pdf = Pdf::loadView('application.verify-pdf', compact('application'))
            ->setPaper('a4');

        return $pdf->download('Verification_' . $application->reference_number . '.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $application = Application::whereUuid($uuid)->first();
        $application->load(['farmer', 'farm', 'season', 'commodities']);

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
        $collectionCenters = Center::whereIn('type', ['collection', 'both'])->get();
        $returnCenters = Center::whereIn('type', ['return', 'both'])->get();

        return view('admin.applications.show', [
            'application' => $application,
            'allocations' => $allocations,
            'insurance_rate' => $application->insurance_rate,
            'insurance_amount' => $application->insurance_amount,
            'equity_held' => $application->equity,
            'disbursed_amount' => $application->disbursed_amount,
            'total_loan' => $application->total_loan,
            'collectionCenters' => $collectionCenters,
            'returnCenters' => $returnCenters
        ]);
    }

    public function approve(Request $request, $uuid)
    {
        $application = Application::whereUuid($uuid)->first();
        $request->validate([
            'collection_center_id' => 'nullable|exists:centers,id',
            'return_center_id' => 'nullable|exists:centers,id',
        ]);

        // Skip if already approved
        if ($application->status === 'approved') {
            ToastMagic::error('This application is already approved.');
            return redirect()->route('admin.applications.index');
        }

        // Handle "both" center logic
        $collectionCenterId = $request->collection_center_id;
        $returnCenterId = $request->return_center_id;

        if ($collectionCenterId) {
            $collectionCenter = Center::find($collectionCenterId);
            if ($collectionCenter && $collectionCenter->type === 'both') {
                $returnCenterId = $collectionCenterId;
            }
        }

        if ($returnCenterId && !$collectionCenterId) {
            $returnCenter = Center::find($returnCenterId);
            if ($returnCenter && $returnCenter->type === 'both') {
                $collectionCenterId = $returnCenterId;
            }
        }

        if (!$collectionCenterId || !$returnCenterId) {
            ToastMagic::error('Please select both a collection and return center.');
            return back();
        }

        DB::transaction(function () use ($application, $collectionCenterId, $returnCenterId) {
            // Generate random collection & return dates from season range
            $season = $application->season;
            // Pick a random collection date
            $collectionDate = \Carbon\Carbon::parse($application->season->collection_start_date)
                ->addDays(rand(
                    0,
                    \Carbon\Carbon::parse($application->season->collection_start_date)
                        ->diffInDays($application->season->collection_end_date)
                ))
                ->toDateString();

            // Get return deadline from season
            $returnDate = $application->season->return_deadline;

            // Ensure return date is after collection date
            if (\Carbon\Carbon::parse($returnDate)->lte(\Carbon\Carbon::parse($collectionDate))) {
                // If not, push return date 30 days after collection
                $returnDate = \Carbon\Carbon::parse($collectionDate)->addDays(180)->toDateString();
            }

            // Save application center assignment
            ApplicationCenter::create([
                'application_id' => $application->id,
                'collection_center_id' => $collectionCenterId,
                'return_center_id' => $returnCenterId,
                'collection_date' => $collectionDate,
                'return_date' => $returnDate,
            ]);


            // Save commodity allocations
            foreach ($application->commodities as $commodity) {
                $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
                $farmSize = $application->farm->size ?? 0;
                $allocatedQty = $qtyPerHectare * $farmSize;
                $totalValue = $allocatedQty * ($commodity->price_per_unit ?? 0);

                CommodityAllocation::create([
                    'application_id' => $application->id,
                    'commodity_name' => $commodity->name,
                    'qty_per_hectare' => $qtyPerHectare,
                    'allocated_quantity' => $allocatedQty,
                    'unit_price' => $commodity->price_per_unit ?? 0,
                    'total_value' => $totalValue,
                    'status' => 'pending',
                ]);
            }

            // Update status
            $application->update(['status' => 'approved']);

            // Send SMS
            // Send SMS notification
            $collectionCenterName = Center::find($collectionCenterId)->name;
            $returnCenterName = Center::find($returnCenterId)->name;

            $msg = "Dear {$application->farmer->full_name}, your application {$application->reference_number} has been approved.
Reg No: {$application->farmer->registration_number}.
Collection Date: {$collectionDate} at {$collectionCenterName}.
Return Date: {$returnDate} at {$returnCenterName}.";

            SmsHelper::send(
                $application->farmer->phone,
                $msg,
                env('TERMII_SENDER_ID') // Will fallback to 'Termii' if not approved yet
            );
        });

        ToastMagic::success('Application approved successfully.');
        return redirect()->route('admin.applications.index');
    }


    public function bulkApprove(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'collection_center_id' => 'nullable|exists:centers,id',
            'return_center_id' => 'nullable|exists:centers,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->application_ids as $appId) {
                $application = Application::with(['commodities', 'farm', 'season', 'farmer'])->findOrFail($appId);

                if ($application->status === 'approved') {
                    continue;
                }

                // Handle "both" type center logic
                $collectionCenterId = $request->collection_center_id;
                $returnCenterId = $request->return_center_id;

                if ($collectionCenterId) {
                    $collectionCenter = Center::find($collectionCenterId);
                    if ($collectionCenter && $collectionCenter->type === 'both') {
                        $returnCenterId = $collectionCenterId;
                    }
                }

                if ($returnCenterId && !$collectionCenterId) {
                    $returnCenter = Center::find($returnCenterId);
                    if ($returnCenter && $returnCenter->type === 'both') {
                        $collectionCenterId = $returnCenterId;
                    }
                }

                if (!$collectionCenterId || !$returnCenterId) {
                    continue;
                }

                // Generate random collection date
                $season = $application->season;
                // Pick random collection date
                $collectionDate = \Carbon\Carbon::parse($application->season->collection_start_date)
                    ->addDays(rand(
                        0,
                        \Carbon\Carbon::parse($application->season->collection_start_date)
                            ->diffInDays($application->season->collection_end_date)
                    ))
                    ->toDateString();

                // Get return deadline from season
                $returnDate = $application->season->return_deadline;

                // Ensure return date is after collection date
                if (\Carbon\Carbon::parse($returnDate)->lte(\Carbon\Carbon::parse($collectionDate))) {
                    $returnDate = \Carbon\Carbon::parse($collectionDate)->addDays(180)->toDateString();
                }

                // Save center assignment
                ApplicationCenter::create([
                    'application_id' => $application->id,
                    'collection_center_id' => $collectionCenterId,
                    'return_center_id' => $returnCenterId,
                    'collection_date' => $collectionDate,
                    'return_date' => $returnDate,
                ]);


                // Save allocations
                foreach ($application->commodities as $commodity) {
                    $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
                    $farmSize = $application->farm->size ?? 0;
                    $allocatedQty = $qtyPerHectare * $farmSize;
                    $totalValue = $allocatedQty * ($commodity->price_per_unit ?? 0);

                    CommodityAllocation::create([
                        'application_id' => $application->id,
                        'commodity_name' => $commodity->name,
                        'qty_per_hectare' => $qtyPerHectare,
                        'allocated_quantity' => $allocatedQty,
                        'unit_price' => $commodity->price_per_unit ?? 0,
                        'total_value' => $totalValue,
                        'status' => 'pending',
                    ]);
                }

                $application->update(['status' => 'approved']);

                $collectionCenterName = Center::find($collectionCenterId)->name;
                $returnCenterName = Center::find($returnCenterId)->name;

                $msg = "Dear {$application->farmer->full_name}, your application {$application->reference_number} has been approved.
Reg No: {$application->farmer->registration_number}.
Collection Date: {$collectionDate} at {$collectionCenterName}.
Return Date: {$returnDate} at {$returnCenterName}.";

                SmsHelper::send(
                    $application->farmer->phone,
                    $msg,
                    env('TERMII_SENDER_ID')
                );
            }
        });

        ToastMagic::success('Selected applications approved successfully.');
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
}
