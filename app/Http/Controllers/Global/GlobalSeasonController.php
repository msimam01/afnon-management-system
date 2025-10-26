<?php

namespace App\Http\Controllers\Global;

use App\Models\GlobalSeason;
use App\Models\GlobalCommodity;
use App\Models\GlobalTenantAllocation;
use App\Services\TenantSyncService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class GlobalSeasonController extends Controller
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $seasons = GlobalSeason::with(['commodities', 'tenantAllocations.tenant'])
            ->withCount(['tenantAllocations', 'commodities'])
            ->latest()
            ->paginate($request->has('per_page') ? $request->per_page : 10);

        // Add some statistics to each season
        $seasons->getCollection()->transform(function ($season) {
            $totalAllocated = $season->tenantAllocations->sum('allocated_stock');
            $totalStock = $season->commodities->sum('pivot.stock');
            
            $season->allocated_percentage = $totalStock > 0 ? min(round(($totalAllocated / $totalStock) * 100, 2), 100) : 0;
            $season->total_tenants = $season->tenantAllocations->groupBy('tenant_id')->count();
            $season->total_commodities = $season->commodities->count();
            
            return $season;
        });

        return view('global.seasons.index', [
            'seasons' => $seasons,
            'totalSeasons' => GlobalSeason::count(),
            'activeSeasons' => GlobalSeason::where('status', 'active')->count(),
            'upcomingSeasons' => GlobalSeason::where('status', 'upcoming')->count(),
            'completedSeasons' => GlobalSeason::where('status', 'completed')->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commodities = GlobalCommodity::with('category')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'uuid' => $c->uuid,
                'name' => $c->name,
                'category' => $c->category->name,
                'unit' => $c->unit,
            ]);

        return view('global.seasons.create', compact('commodities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = date('Y', strtotime($request->start_date));
                    $exists = GlobalSeason::where('name', $value)
                        ->where('type', $request->type)
                        ->whereYear('start_date', $year)
                        ->exists();
                    
                    if ($exists) {
                        $fail('A ' . $request->type . ' season with this name already exists for year ' . $year . '.');
                    }
                }
            ],
            'type' => 'required|in:dry,wet',
            'loan_type' => 'required|in:co-funded,complete-loan',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'collection_start_date' => 'required|date|after_or_equal:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:open,closed',
            'return_deadline' => 'nullable|required_if:loan_type,complete-loan|date|after:end_date',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'commodities' => 'required|array|min:1',
            'commodities.*.id' => 'required|exists:global_commodities,id',
            'commodities.*.stock' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Close any currently open seasons if the new one is being opened
            if ($validated['status'] === 'open') {
                GlobalSeason::where('status', 'open')->update(['status' => 'closed']);
            }

            $season = GlobalSeason::create([
                'uuid' => Str::uuid(),
                'name' => $validated['name'],
                'type' => $validated['type'],
                'loan_type' => $validated['loan_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'collection_start_date' => $validated['collection_start_date'],
                'collection_end_date' => $validated['collection_end_date'],
                'budget' => $validated['budget'] ?? null,
                'status' => $validated['status'] ?? 'open',
                'return_deadline' => $validated['return_deadline'] ?? null,
                'insurance_rate' => $validated['insurance_rate'],
                'send_reminder_after_days' => $validated['send_reminder_after_days'] ?? 7,
            ]);

            foreach ($validated['commodities'] as $commodity) {
                $season->commodities()->attach($commodity['id'], [
                    'stock' => $commodity['stock']
                ]);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Season created successfully',
                    'data' => $season->load('commodities')
                ], 201);
            }

            return redirect()->route('global.seasons.show', $season->uuid)
                ->with('success', 'Season created successfully. You can now allocate it to tenants.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating season: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to create season',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create season: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)
            ->with(['commodities'])
            ->firstOrFail();

        $commodities = GlobalCommodity::with('category')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'uuid' => $c->uuid,
                'name' => $c->name,
                'category' => $c->category->name,
                'unit' => $c->unit,
                'current_stock' => $season->commodities->firstWhere('id', $c->id)->pivot->stock ?? 0,
            ]);

        return view('global.seasons.edit', compact('season', 'commodities'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)
            ->with(['commodities', 'tenantAllocations.tenant'])
            ->firstOrFail();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $season
            ]);
        }

        $availableCommodities = GlobalCommodity::with('category')
            ->whereNotIn('id', $season->commodities->pluck('id'))
            ->get();

        // Get allocation summary by tenant
        $allocationSummary = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->with(['tenant', 'commodity'])
            ->get()
            ->groupBy('tenant_id')
            ->map(function ($allocations, $tenantId) {
                $tenant = $allocations->first()->tenant;
                return [
                    'tenant_id' => $tenantId,
                    'tenant_name' => $tenant->name,
                    'tenant_status' => $tenant->status,
                    'commodities' => $allocations->map(function ($allocation) {
                        return [
                            'commodity_name' => $allocation->commodity->name,
                            'allocated_stock' => $allocation->allocated_stock,
                        ];
                    }),
                    'total_allocated' => $allocations->sum('allocated_stock')
                ];
            });

        // Get sync logs
        $syncLogs = DB::connection('mysql')
            ->table('sync_logs')
            ->where('season_id', $season->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('global.seasons.show', [
            'season' => $season,
            'availableCommodities' => $availableCommodities,
            'allocationSummary' => $allocationSummary,
            'syncLogs' => $syncLogs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)->firstOrFail();
        
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $season) {
                    $year = date('Y', strtotime($request->start_date ?? $season->start_date));
                    $query = GlobalSeason::where('name', $value)
                        ->where('type', $request->type ?? $season->type)
                        ->whereYear('start_date', $year)
                        ->where('id', '!=', $season->id);
                    
                    if ($query->exists()) {
                        $fail('A ' . ($request->type ?? $season->type) . ' season with this name already exists for year ' . $year . '.');
                    }
                }
            ],
            'type' => 'sometimes|required|in:dry,wet',
            'loan_type' => 'sometimes|required|in:co-funded,complete-loan',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'collection_start_date' => 'sometimes|required|date|after_or_equal:end_date',
            'collection_end_date' => 'sometimes|required|date|after:collection_start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:open,closed',
            'return_deadline' => 'nullable|required_if:loan_type,complete-loan|date|after:end_date',
            'insurance_rate' => 'sometimes|required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // If this season is being opened, close all other open seasons
            if (isset($validated['status']) && $validated['status'] === 'open' && $season->status !== 'open') {
                GlobalSeason::where('status', 'open')
                    ->where('id', '!=', $season->id)
                    ->update(['status' => 'closed']);
            }

            $season->update([
                'name' => $validated['name'] ?? $season->name,
                'type' => $validated['type'] ?? $season->type,
                'loan_type' => $validated['loan_type'] ?? $season->loan_type,
                'start_date' => $validated['start_date'] ?? $season->start_date,
                'end_date' => $validated['end_date'] ?? $season->end_date,
                'collection_start_date' => $validated['collection_start_date'] ?? $season->collection_start_date,
                'collection_end_date' => $validated['collection_end_date'] ?? $season->collection_end_date,
                'budget' => $validated['budget'] ?? $season->budget,
                'status' => $validated['status'] ?? $season->status,
                'return_deadline' => $validated['return_deadline'] ?? $season->return_deadline,
                'insurance_rate' => $validated['insurance_rate'] ?? $season->insurance_rate,
                'send_reminder_after_days' => $validated['send_reminder_after_days'] ?? $season->send_reminder_after_days,
            ]);

            // $commodities = [];
            // foreach ($validated['commodities'] as $commodity) {
            //     $commodities[$commodity['id']] = [
            //         'stock' => $commodity['stock']
            //     ];
            // }
            // $season->commodities()->sync($commodities);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Season updated successfully',
                    'data' => $season->fresh()->load('commodities')
                ]);
            }

            ToastMagic::success('Season updated successfully. Remember to sync changes to tenants if needed.');
            return redirect()->route('global.seasons.show', $season->uuid);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating season: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to update season',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update season: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)->firstOrFail();

        if ($season->tenantAllocations()->exists()) {
            return response()->json([
                'message' => 'Cannot delete season as it has tenant allocations',
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $season->commodities()->detach();
            $season->delete();
            
            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Season deleted successfully'
                ]);
            }

            return redirect()->route('global.seasons.index')
                ->with('success', 'Season deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting season: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to delete season',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a commodity to the season.
     */
    public function addCommodity(Request $request, $uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)->firstOrFail();
        
        $validated = $request->validate([
            'commodity_id' => 'required|exists:global_commodities,id',
            'stock' => 'required|numeric|min:0',
        ]);

        try {
            if ($season->commodities()->where('global_commodity_id', $validated['commodity_id'])->exists()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'This commodity is already added to the season.'
                    ], 422);
                }
                return back()->with('error', 'This commodity is already added to the season.');
            }

            $season->commodities()->attach($validated['commodity_id'], [
                'stock' => $validated['stock'],
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Commodity added to season successfully.'
                ]);
            }

            return back()->with('success', 'Commodity added to season successfully.');

        } catch (\Exception $e) {
            \Log::error('Error adding commodity to season: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to add commodity to season.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to add commodity to season.');
        }
    }

    /**
     * Update a commodity in the season.
     */
    public function updateCommodity(Request $request, $seasonUuid, $commodityUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();
        $commodity = GlobalCommodity::where('uuid', $commodityUuid)->firstOrFail();
        
        $validated = $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        try {
            $season->commodities()->updateExistingPivot($commodity->id, [
                'stock' => $validated['stock'],
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Commodity updated successfully.'
                ]);
            }

            return back()->with('success', 'Commodity updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Error updating commodity: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to update commodity.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to update commodity.');
        }
    }

    /**
     * Remove a commodity from the season.
     */
    public function removeCommodity($seasonUuid, $commodityUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();
        $commodity = GlobalCommodity::where('uuid', $commodityUuid)->firstOrFail();
        
        try {
            $season->commodities()->detach($commodity->id);
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Commodity removed from season successfully.'
                ]);
            }
            
            return back()->with('success', 'Commodity removed from season successfully.');

        } catch (\Exception $e) {
            \Log::error('Error removing commodity from season: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to remove commodity from season.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to remove commodity from season.');
        }
    }

    /**
     * Close the season and propagate to tenants.
     */
    public function close($uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)->firstOrFail();
        
        try {
            DB::beginTransaction();
            
            $season->update(['status' => 'closed']);
            
            // Sync status to all allocated tenants
            $syncResults = $this->syncService->closeSeasonGlobally($season);
            
            DB::commit();
            
            $successCount = collect($syncResults)->where('success', true)->count();
            $totalCount = count($syncResults);
            
            $message = "Season closed successfully. Synced to {$successCount} of {$totalCount} tenants.";
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'sync_results' => $syncResults
                ]);
            }
            
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error closing season: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to close season.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to close season.');
        }
    }

    /**
     * Reopen the season.
     */
    public function reopen($uuid)
    {
        $season = GlobalSeason::where('uuid', $uuid)->firstOrFail();
        
        try {
            $season->update(['status' => 'open']);
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Season has been reopened successfully.'
                ]);
            }
            
            return back()->with('success', 'Season has been reopened successfully. You may need to sync this change to tenants.');

        } catch (\Exception $e) {
            \Log::error('Error reopening season: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to reopen season.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to reopen season.');
        }
    }
}