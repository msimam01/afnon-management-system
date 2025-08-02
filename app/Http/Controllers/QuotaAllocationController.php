<?php

namespace App\Http\Controllers;

use App\Models\QuotaAllocation;
use Illuminate\Http\Request;
use App\Models\Central\CentralSeason;
use App\Models\Central\CentralCommodity;

class QuotaAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($seasonId)
    {
        $season = CentralSeason::findOrFail($seasonId);
        $commodities = CentralCommodity::all();
        $allocations = QuotaAllocation::where('season_id', $seasonId)->get();

        // Hardcoded tenant names (or replace with DB logic later)
        $tenants = \Stancl\Tenancy\Database\Models\Tenant::pluck('id')->toArray();


        return view('super-admin.quotas.create', compact('season', 'commodities', 'allocations', 'tenants'));
    }


    public function store(Request $request, $seasonId)
    {
        $request->validate([
            'allocations' => 'required|array',
        ]);

        $commodities = CentralCommodity::all()->keyBy('id');
        $totalAllocated = [];

        foreach ($request->allocations as $data) {
            if (!empty($data['quantity']) && $data['quantity'] > 0) {
                $commodityId = $data['commodity_id'];
                $qty = (int) $data['quantity'];

                $totalAllocated[$commodityId] = ($totalAllocated[$commodityId] ?? 0) + $qty;

                if ($totalAllocated[$commodityId] > $commodities[$commodityId]->stock) {
                    return back()->withErrors([
                        'allocations' => 'Total allocation for ' . $commodities[$commodityId]->name . ' exceeds available stock (' . $commodities[$commodityId]->stock . ').'
                    ])->withInput();
                }

                QuotaAllocation::updateOrCreate([
                    'season_id' => $seasonId,
                    'tenant' => $data['tenant'],
                    'commodity_id' => $commodityId,
                ], [
                    'allocated_quantity' => $qty,
                ]);
            }
        }

        return redirect()->route('superadmin.seasons.index')->with('success', 'Quota allocations saved.');
    }


    /**
     * Display the specified resource.
     */
    public function show(QuotaAllocation $quotaAllocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuotaAllocation $quotaAllocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotaAllocation $quotaAllocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuotaAllocation $quotaAllocation)
    {
        //
    }
}
