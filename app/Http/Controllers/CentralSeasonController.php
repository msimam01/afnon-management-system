<?php

namespace App\Http\Controllers;

use App\Models\SyncLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use Stancl\Tenancy\TenantManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Central\CentralSeason;
use App\Models\Central\CentralCommodity;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CentralSeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = CentralSeason::latest()->get();
        return view('super-admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        $commodities = CentralCommodity::all();
        return view('super-admin.seasons.create', compact('commodities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'return_deadline' => 'required|date|after:end_date',
            'budget' => 'required|numeric',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'commodities' => 'required|array|min:1',
        ]);

        // Extract year and type
        $year = date('Y', strtotime($validated['start_date']));
        $seasonType = strtolower(str_contains($validated['name'], 'wet') ? 'wet' : 'dry');

        // Prevent duplicate for same year & type
        $exists = CentralSeason::whereYear('start_date', $year)
            ->where('name', 'like', "%$seasonType%")
            ->exists();

        if ($exists) {
            ToastMagic::error("A '$seasonType' season already exists for $year.");
            return back()->withErrors(['name' => "A '$seasonType' season already exists for $year."])->withInput();
        }

        $season = CentralSeason::create([
            'uuid' => Str::uuid(),
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'return_deadline' => $validated['return_deadline'],
            'budget' => $validated['budget'],
            'insurance_rate' => $validated['insurance_rate'],
            'send_reminder_after_days' => $validated['send_reminder_after_days'],
            'commodities' => json_encode($validated['commodities']),
        ]);

        ToastMagic::success('Season created. Please assign commodity quotas.');
        return redirect()->route('superadmin.seasons.quotas.create', $season->id);
    }


    public function syncToTenants(CentralSeason $season)
    {
        $tenants = Tenant::all();
        $allocations = \App\Models\QuotaAllocation::where('season_id', $season->id)->get();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            // Step 1: Sync season
            $tenantSeason = \App\Models\Season::firstOrCreate(
                ['global_season_id' => $season->id],
                [
                    'uuid' => Str::uuid(),
                    'name' => $season->name,
                    'start_date' => $season->start_date,
                    'end_date' => $season->end_date,
                    'budget' => $season->budget,
                    'return_deadline' => $season->return_deadline,
                    'insurance_rate' => $season->insurance_rate,
                    'send_reminder_after_days' => $season->send_reminder_after_days,
                    'status' => $season->status,
                    'is_global' => true,
                ]
            );

            // Step 2: Sync commodities used in this season
            $commodityNames = json_decode($season->commodities ?? '[]', true);

            $globalCommodities = \App\Models\Central\CentralCommodity::on('central')
                ->whereIn('name', $commodityNames)->get();

            foreach ($globalCommodities as $global) {
                \App\Models\Commodity::firstOrCreate(
                    ['global_commodity_id' => $global->id],
                    [
                        'uuid' => Str::uuid(),
                        'name' => $global->name,
                        'category' => $global->category,
                        'type' => $global->type,
                        'unit' => $global->unit,
                        'price_per_unit' => $global->price_per_unit,
                        'quantity_per_hectare' => $global->quantity_per_hectare,
                        'stock' => 0,
                        'is_global' => true,
                        'global_commodity_id' => $global->id,
                        'season_id' => $tenantSeason->id,
                    ]
                );
            }

            // Step 3: Sync allocations into local tenant
            $tenantId = $tenant->id;

            foreach ($allocations->where('tenant', $tenantId) as $allocation) {
                // Match global commodity ID to tenant's local commodity ID
                $localCommodity = \App\Models\Commodity::where('global_commodity_id', $allocation->commodity_id)->first();
                if (!$localCommodity) {
                    Log::warning("Missing local commodity for global ID {$allocation->commodity_id} in tenant {$tenantId}");
                    continue;
                }
                if (!$localCommodity) continue;

                \App\Models\QuotaAllocation::updateOrCreate([
                    'season_id' => $tenantSeason->id,
                    'tenant' => $tenantId,
                    'commodity_id' => $localCommodity->id,  // ✅ Correct local ID
                ], [
                    'allocated_quantity' => $allocation->allocated_quantity,
                ]);
            }


            tenancy()->end(); // ⛔️ Exit tenant DB context

            // ✅ Log sync centrally
            SyncLog::create([
                'tenant_id' => $tenant->id,
                'type' => 'season',
                'item_id' => $season->id,
                'synced_at' => now(),
            ]);
        }

        ToastMagic::success("Season and quotas successfully synced to all tenants.");
        return redirect()->back();
    }


    public function close(CentralSeason $season)
    {
        $season->update(['status' => 'closed']);
        ToastMagic::success('Season closed.');
        return back();
    }

    public function reopen(CentralSeason $season)
    {
        $season->update(['status' => 'open']);
        ToastMagic::success('Season reopened.');
        return back();
    }
}
