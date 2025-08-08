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
    Season
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $seasons = Season::where('status', 'open')->get();
        // return  view('application.index', compact('seasons'));
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
        return "AF/$tenantName-" . strtoupper($seasonType) . "-$year-" . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    protected function generateReferenceNumber()
    {
        $tenantPrefix = strtoupper(tenant()->id ?? 'TN');
        $tenantName = substr($tenantPrefix, 0, 1) . substr($tenantPrefix, -2, length: 1);
        return 'REF/' . $tenantName . '-AFNON-' . rand(100000, 999999);
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
    // public function downloadPDF($id)
    // {
    //     $application = Application::with('commodities')->findOrFail($id);
    //     $pdf = Pdf::loadView('applications.acknowledgment-pdf', compact('application'));

    //     return $pdf->download("acknowledgment-{$application->reference_number}.pdf");
    // }


    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
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
