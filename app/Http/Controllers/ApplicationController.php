<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Farmer;
use App\Models\Season;
use App\Models\Commodity;
use App\Models\Application;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplicationCommodity;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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

        $commodities = Commodity::where('season_id', $season->id)->get();

        $seeds = $commodities->where('category', 'seed');
        $others = $commodities->where('category', '!=', 'seed');

        return view('application.index', compact('season', 'seeds', 'others'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    return $request;
        $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|unique:farmers,phone',
            'nin' => 'required|digits:11|unique:farmers,nin',
            'bvn' => 'required|digits:11|unique:farmers,bvn',
            'state' => 'required|string',
            'lga' => 'required|string',
            'address' => 'required|string',
            'location' => 'required|string',
            'size' => 'required|numeric|min:0.1',
            'seed' => 'required|exists:commodities,id',
            'season_id' => 'required|exists:seasons,id',
        ]);

        $farmer = Farmer::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'nin' => $request->nin,
            'bvn' => $request->bvn,
            'state' => $request->state,
            'lga' => $request->lga,
            'address' => $request->address,
            'registration_number' => 'FARM-' . strtoupper(Str::random(6)),
        ]);

        $farm = Farm::create([
            'farmer_id' => $farmer->id,
            'location' => $request->location,
            'size' => $request->size,
        ]);

        $season = Season::find($request->season_id);
        $insuranceRate = $season->insurance_rate ?? 1;

        $commodities = Commodity::where('season_id', $season->id)->get();

        $selectedSeed = $commodities->firstWhere('id', $request->seed);
        $total = $request->size * $selectedSeed->quantity_per_hectare * $selectedSeed->price_per_unit;

        $application = Application::create([
            'uuid' => Str::uuid(),
            'farmer_id' => $farmer->id,
            'farm_id' => $farm->id,
            'season_id' => $season->id,
            'insurance_rate' => $insuranceRate,
            'insurance_amount' => $insurance = $total * ($insuranceRate / 100),
            'total_loan' => $total += $insurance,
            'equity' => $equity = $total / 2,
            'disbursed_amount' => $equity,
        ]);

        // Application Commodities
        $rows = [];
        foreach ($commodities as $item) {
            $quantity = $item->quantity_per_hectare * $farm->size;
            $rows[] = [
                'uuid' => Str::uuid(),
                'application_id' => $application->id,
                'commodity_id' => $item->id,
                'quantity' => (int) $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ApplicationCommodity::insert($rows);

        ToastMagic::success('Application submitted');
        return redirect()->route('applications.slip', $application->uuid);
    }
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
