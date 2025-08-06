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
    private function generateRegistrationNumber($seasonType, $year)
    {
        $count = Farmer::whereYear('created_at', $year)->count() + 1;
        $prefix = strtoupper($seasonType); // DRY, WET, etc.
        $serial = str_pad($count, 6, '0', STR_PAD_LEFT);

        return "AF/{$prefix}-{$year}/{$serial}";
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|unique:farmers,phone',
            'nin' => 'required|digits:11|unique:farmers,nin',
            'bvn' => 'required|digits:11|unique:farmers,bvn',
            'state' => 'required|string',
            'lga' => 'required|string',
            'address' => 'required|string',
            'farm_location' => 'required|string',
            'farm_size' => 'required|numeric|min:0.1',
            'season_id' => 'required|exists:seasons,id',
            'seed_id' => 'required|exists:commodities,id', // assume one seed must be selected
        ]);

        DB::beginTransaction();

        try {
            $season = Season::with('commodities')->findOrFail($request->season_id);
            $year = \Carbon\Carbon::parse($season->start_date)->year;

            // Generate unique registration number
            $regNumber = $this->generateRegistrationNumber($season->type ?? 'GEN', $year);

            // Create farmer
            $farmer = Farmer::create([
                'uuid' => Str::uuid(),
                'registration_number' => $regNumber,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'nin' => $request->nin,
                'bvn' => $request->bvn,
                'state' => $request->state,
                'lga' => $request->lga,
                'address' => $request->address,
                'cluster' => $request->cluster_location,
            ]);

            // Create farm
            $farm = Farm::create([
                'uuid' => Str::uuid(),
                'farmer_id' => $farmer->id,
                'location' => $request->farm_location,
                'size' => $request->farm_size,
            ]);

            $farmSize = $request->farm_size;
            $seedId = $request->seed_id;

            $seasonCommodities = $season->commodities;

            // Split into seed + other
            $seed = $seasonCommodities->firstWhere('id', $seedId);
            if (!$seed) {
                ToastMagic::error('Selected seed is not valid for this season.');
                return back()->withErrors(['seed_id' => 'Selected seed is not valid for this season.']);
            }

            $otherCommodities = $seasonCommodities->where('id', '!=', $seedId);

            // Commodity Calculations
            $loanTotal = $seed->price_per_unit;
            $breakdown = [];

            $breakdown[] = [
                'commodity_id' => $seed->id,
                'quantity' => 1,
                'unit_price' => $seed->price_per_unit,
                'total' => $seed->price_per_unit,
            ];

            foreach ($otherCommodities as $item) {
                $qty = round($item->quantity_per_hectare * $farmSize);
                $total = $qty * $item->price_per_unit;
                $loanTotal += $total;

                $breakdown[] = [
                    'commodity_id' => $item->id,
                    'quantity' => $qty,
                    'unit_price' => $item->price_per_unit,
                    'total' => $total,
                ];
            }

            // Financials
            $insuranceRate = $season->insurance_rate;
            $insuranceAmount = ($insuranceRate / 100) * $loanTotal;
            $equity = 0.5 * $loanTotal;
            $disbursed = $loanTotal - $equity - $insuranceAmount;

            // Create application
            $application = Application::create([
                'uuid' => Str::uuid(),
                'farmer_id' => $farmer->id,
                'farm_id' => $farm->id,
                'season_id' => $season->id,
                'insurance_rate' => $insuranceRate,
                'insurance_amount' => $insuranceAmount,
                'total_loan' => $loanTotal,
                'equity' => $equity,
                'disbursed_amount' => $disbursed,
            ]);

            foreach ($breakdown as $item) {
                ApplicationCommodity::create([
                    'uuid' => Str::uuid(),
                    'application_id' => $application->id,
                    'commodity_id' => $item['commodity_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            ToastMagic::success('Application submitted successfully!');
            return response()->json([
                'message' => 'Application submitted successfully!',
                'application_id' => $application->uuid,
                'registration_number' => $farmer->registration_number,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            ToastMagic::error('An unexpected error occurred. Please try again.');
            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function acknowledgment($uuid)
    {
        $application = Application::with(['farmer', 'farm', 'season', 'commodities'])->whereUuid($uuid)->firstOrFail();
        return view('admin.applications.acknowledgment', compact('application'));
    }



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
