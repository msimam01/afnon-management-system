<?php

namespace App\Http\Controllers;

use App\Models\QuotaAllocation;
use Illuminate\Http\Request;
use App\Models\Central\CentralSeason;
use App\Models\Central\CentralCommodity;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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

        $tenants = \App\Models\SuperAdmin\Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $tenantSeason = \App\Models\Season::where('global_season_id', $seasonId)->first();
            if (!$tenantSeason) {
                tenancy()->end();
                continue;
            }

            foreach ($request->allocations as $data) {
                if (
                    isset($data['tenant']) &&
                    $data['tenant'] === $tenant->id &&
                    !empty($data['quantity']) &&
                    $data['quantity'] > 0
                ) {
                    $centralCommodityId = $data['commodity_id'];
                    $qty = (int) $data['quantity'];

                    // 🛠️ Map central commodity ID to local tenant commodity
                    $localCommodity = \App\Models\Commodity::where('global_commodity_id', $centralCommodityId)->first();

                    if (!$localCommodity) {
                        tenancy()->end();
                        continue;
                    }

                    // Validate stock
                    $totalAllocated[$centralCommodityId] = ($totalAllocated[$centralCommodityId] ?? 0) + $qty;
                    if ($totalAllocated[$centralCommodityId] > $commodities[$centralCommodityId]->stock) {
                        tenancy()->end();
                        return back()->withErrors([
                            'allocations' => 'Total allocation for ' . $commodities[$centralCommodityId]->name . ' exceeds available stock (' . $commodities[$centralCommodityId]->stock . ').'
                        ])->withInput();
                    }

                    // ✅ Save using tenant-local season and commodity IDs
                    \App\Models\QuotaAllocation::updateOrCreate([
                        'season_id' => $tenantSeason->id,
                        'tenant' => $tenant->id,
                        'commodity_id' => $localCommodity->id,
                    ], [
                        'allocated_quantity' => $qty,
                    ]);
                }
            }

            tenancy()->end();
        }


        ToastMagic::success('Quota allocated successfully');
        return redirect()->route('superadmin.seasons.index')->with('success', 'Quota allocations saved for all tenants.');
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
