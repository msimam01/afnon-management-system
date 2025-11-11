<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\GlobalCommodity;
use App\Models\GlobalSeason;
use App\Models\GlobalTenantAllocation;
use App\Models\SuperAdmin\Tenant;
use App\Services\TenantSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GlobalTenantAllocationController extends Controller
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display allocation overview for a season
     */
    public function index($seasonUuid, Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $currentPage = $request->input('page', 1);

        $season = GlobalSeason::where('uuid', $seasonUuid)
            ->with('commodities')
            ->firstOrFail();

        // Get all allocations for this season with tenant and commodity relationships
        $query = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->with(['tenant', 'commodity']);

        // Get the total count of unique tenants
        $totalTenants = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->select('tenant_id')
            ->distinct()
            ->count();

        // Get all allocations grouped by tenant with all commodities
        $tenantAllocations = $query->get()
            ->groupBy('tenant_id');

        $allocations = collect();

        // Process each tenant's allocations
        foreach ($tenantAllocations as $tenantId => $tenantAllocationGroup) {
            $tenant = $tenantAllocationGroup->first()->tenant;

            // Get the last sync time for this tenant and season
            $lastSync = DB::table('sync_logs')
                ->where('tenant_id', $tenant->id)
                ->where('season_id', $season->id)
                ->where('status', 'success')
                ->latest('created_at')
                ->first();

            // Get all allocations for this tenant
            $allocationsData = $tenantAllocationGroup->map(function($allocation) use ($season) {
                $commodity = $allocation->commodity;
                $pivot = $season->commodities()->find($commodity->id)->pivot;
                $totalStock = $pivot ? $pivot->stock : 0;

                return (object)[
                    'id' => $allocation->id,
                    'tenant_id' => $allocation->tenant_id,
                    'global_season_id' => $allocation->global_season_id,
                    'global_commodity_id' => $allocation->global_commodity_id,
                    'allocated_stock' => $allocation->allocated_stock,
                    'commodity' => $commodity,
                    'total_stock' => $totalStock
                ];
            });

            $allocations->push((object)[
                'tenant' => $tenant,
                'allocations' => $allocationsData,
                'last_sync' => $lastSync ? $lastSync->created_at : null,
                'total_stock' => $allocationsData->sum('allocated_stock'),
                'commodities_count' => $allocationsData->count()
            ]);
        }

        // Create a paginator instance
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $allocations->forPage($currentPage, $perPage),
            $totalTenants,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page',
            ]
        );

        // Calculate remaining stock per commodity
        $commodityStats = $season->commodities->map(function($commodity) use ($season) {
            $pivot = $season->commodities()->find($commodity->id)->pivot;
            $currentAvailableStock = $pivot ? $pivot->stock : 0;

            // Originally, the stock in pivot was the total_stock, now this contains remaining stock after allocations
            // So we need the original total stock. For now, we'll calculate it by summing allocations
            $totalAllocated = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('global_commodity_id', $commodity->id)
                ->sum('allocated_stock');

            $totalStock = $currentAvailableStock + $totalAllocated;

            return [
                'id' => $commodity->id,
                'uuid' => $commodity->uuid,
                'name' => $commodity->name,
                'unit' => $commodity->unit,
                'total_stock' => $totalStock,
                'allocated' => $totalAllocated,
                'remaining' => $currentAvailableStock,
                'percentage_allocated' => $totalStock > 0 ? min(round(($totalAllocated / $totalStock) * 100, 2), 100) : 0
            ];
        });

        return view('global.allocations.index', [
            'season' => $season,
            'allocations' => $allocations,
            'commodityStats' => $commodityStats,
            'paginator' => $paginator
        ]);
    }

    /**
     * Display allocation form for a season
     */
    public function create($seasonUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)
            ->with('commodities')
            ->firstOrFail();

        $allocatedTenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->distinct()
            ->pluck('tenant_id')
            ->toArray();

        $tenants = Tenant::active()
            ->whereNotIn('id', $allocatedTenantIds)
            ->get();

        // Get existing allocations
        $existingAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->get()
            ->groupBy('tenant_id');

        // Calculate available stock per commodity
        $availableStock = [];
        foreach ($season->commodities as $commodity) {
            $allocated = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('global_commodity_id', $commodity->id)
                ->sum('allocated_stock');

            $availableStock[$commodity->id] = [
                'total' => $commodity->pivot->stock,
                'allocated' => $allocated,
                'remaining' => $commodity->pivot->stock - $allocated
            ];
        }

        return view('global.allocations.create', compact('season', 'tenants', 'existingAllocations', 'availableStock'));
    }

    /**
     * Get real-time available stock (AJAX endpoint)
     */
    public function getAvailableStock($seasonUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();

        $availableStock = $season->commodities->map(function($commodity) use ($season) {
            $allocated = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('global_commodity_id', $commodity->id)
                ->sum('allocated_stock');

            return [
                'commodity_id' => $commodity->id,
                'name' => $commodity->name,
                'unit' => $commodity->unit,
                'total_stock' => $commodity->pivot->stock,
                'allocated' => $allocated,
                'remaining' => $commodity->pivot->stock - $allocated
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $availableStock
        ]);
    }

    /**
     * Store allocations and sync to tenants
     */
    public function store(Request $request, $seasonUuid)
    {
        // Debug: Dump the raw request data

        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();

        // Custom validation for allocations
        $allocations = $request->input('allocations', []);

        // Debug: Log the raw allocations data
        Log::info('Raw allocations data:', $allocations);

        // Filter out empty allocations (no tenant selected) and validate structure
        $validAllocations = [];
        $validationErrors = [];

        foreach ($allocations as $index => $allocation) {
            if (empty($allocation['tenant_id'])) {
                Log::info("Skipping allocation at index {$index}: No tenant selected");
                continue;
            }

            // Ensure commodities array exists and has at least one non-zero allocation
            if (empty($allocation['commodities']) || !is_array($allocation['commodities'])) {
                $validationErrors[] = "No commodities allocated for tenant at position " . ($index + 1);
                continue;
            }

            $hasValidCommodity = false;
            $tenantCommodities = [];

            foreach ($allocation['commodities'] as $commodity) {
                if (empty($commodity['commodity_id']) || empty($commodity['allocated_stock'])) {
                    continue;
                }

                $allocatedStock = (float) $commodity['allocated_stock'];
                if ($allocatedStock <= 0) {
                    continue;
                }

                $hasValidCommodity = true;
                $tenantCommodities[] = [
                    'commodity_id' => $commodity['commodity_id'],
                    'allocated_stock' => $allocatedStock
                ];
            }

            if (!$hasValidCommodity) {
                $validationErrors[] = "At least one commodity with a quantity greater than 0 must be selected for tenant at position " . ($index + 1);
                continue;
            }

            $validAllocations[] = [
                'tenant_id' => $allocation['tenant_id'],
                'commodities' => $tenantCommodities
            ];

            Log::info("Valid allocation for tenant {$allocation['tenant_id']} with " . count($tenantCommodities) . " commodities");
        }

        if (!empty($validationErrors)) {
            return back()->withErrors(['allocations' => $validationErrors])->withInput();
        }

        if (empty($validAllocations)) {
            return back()->withErrors(['allocations' => 'No valid allocations found. Please ensure at least one tenant with valid commodity allocations is selected.'])->withInput();
        }

        // Validate the filtered data
        $validated = [
            'allocations' => $validAllocations
        ];

        try {
            DB::beginTransaction();

            // Validate total allocations don't exceed available stock
            $commodityTotals = [];
            foreach ($validated['allocations'] as $allocation) {
                foreach ($allocation['commodities'] as $commodity) {
                    $commodityId = $commodity['commodity_id'];
                    $commodityTotals[$commodityId] = ($commodityTotals[$commodityId] ?? 0) + $commodity['allocated_stock'];
                }
            }

            // Check against available stock
            foreach ($commodityTotals as $commodityId => $totalRequested) {
                $commodity = $season->commodities->firstWhere('id', $commodityId);

                if (!$commodity) {
                    throw new \Exception("Commodity not found in season");
                }

                $existingAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
                    ->where('global_commodity_id', $commodityId)
                    ->sum('allocated_stock');

                $available = $commodity->pivot->stock - $existingAllocations;

                if ($totalRequested > $available) {
                    $commodityName = $commodity->name;
                    throw new \Exception("Insufficient stock for {$commodityName}. Available: {$available}, Requested: {$totalRequested}");
                }
            }

            $syncResults = [];

            // Process each allocation
            foreach ($validated['allocations'] as $allocation) {
                $tenantId = $allocation['tenant_id'];

                foreach ($allocation['commodities'] as $commodity) {
                    $commodityId = $commodity['commodity_id'];
                    $allocatedStock = (float) $commodity['allocated_stock'];

                    if ($allocatedStock <= 0) {
                        continue;
                    }

                    // Check if allocation already exists
                    $existingAllocation = GlobalTenantAllocation::where([
                        'tenant_id' => $tenantId,
                        'global_season_id' => $season->id,
                        'global_commodity_id' => $commodityId
                    ])->first();

                    if ($existingAllocation) {
                        // Update existing allocation
                        $existingAllocation->update(['allocated_stock' => $allocatedStock]);
                        Log::info("Updated allocation for tenant {$tenantId} and commodity {$commodityId}");
                    } else {
                        // Create new allocation
                        GlobalTenantAllocation::create([
                            'tenant_id' => $tenantId,
                            'global_season_id' => $season->id,
                            'global_commodity_id' => $commodityId,
                            'allocated_stock' => $allocatedStock,
                        ]);
                        Log::info("Created new allocation for tenant {$tenantId} and commodity {$commodityId}");
                    }


                }

                // Add sync result for this tenant
                $syncResults[] = [
                    'tenant_id' => $tenantId,
                    'success' => true,
                    'message' => 'Allocation processed successfully'
                ];
            }

            // Sync allocations to tenants
            $syncResults = [];
            foreach ($validated['allocations'] as $allocation) {
                $tenantId = $allocation['tenant_id'];
                $tenant = Tenant::find($tenantId);

                if (!$tenant || !$tenant->isActive()) {
                    $syncResults[] = [
                        'tenant_id' => $tenantId,
                        'success' => false,
                        'message' => 'Tenant inactive or not found'
                    ];
                    continue;
                }

                try {
                    $result = $this->syncService->syncSeasonToTenant($season, $tenantId);
                    $syncResults[] = [
                        'tenant_id' => $tenantId,
                        'success' => $result['success'],
                        'message' => $result['message']
                    ];
                } catch (\Exception $e) {
                    $syncResults[] = [
                        'tenant_id' => $tenantId,
                        'success' => false,
                        'message' => 'Sync failed: ' . $e->getMessage()
                    ];
                }
            }

            DB::commit();

            $successCount = collect($syncResults)->where('success', true)->count();
            $totalCount = count($syncResults);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => "Allocations created and synced to {$successCount} of {$totalCount} tenants",
                    'sync_results' => $syncResults
                ], 201);
            }

            return redirect()->route('global.allocations.index', $season->uuid)
                ->with('success', "Allocations created and synced to {$successCount} of {$totalCount} tenants successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating allocations: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to create allocations',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show bulk edit form for all tenant allocations in a season
     */
    public function editAll($seasonUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)
            ->with('commodities')
            ->firstOrFail();

        // Get all allocated tenants for this season
        $allocatedTenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->distinct()
            ->pluck('tenant_id')
            ->toArray();

        $tenants = Tenant::active()
            ->whereIn('id', $allocatedTenantIds)
            ->get();

        // Get all existing allocations grouped by tenant
        $existingAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->with('tenant', 'commodity')
            ->get()
            ->groupBy('tenant_id');

        // Calculate available stock per commodity
        $availableStock = [];
        foreach ($season->commodities as $commodity) {
            $allocated = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('global_commodity_id', $commodity->id)
                ->sum('allocated_stock');

            $availableStock[$commodity->id] = [
                'total' => $commodity->pivot->stock,
                'allocated' => $allocated,
                'remaining' => $commodity->pivot->stock - $allocated
            ];
        }

        return view('global.allocations.edit-all', compact('season', 'tenants', 'existingAllocations', 'availableStock'));
    }

    /**
     * Update all allocations and sync to tenants
     */
    public function updateAll(Request $request, $seasonUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();

        $allocations = $request->input('allocations', []);

        Log::info('Bulk update allocations data:', $allocations);

        try {
            DB::beginTransaction();

            // Track which tenants actually had changes for syncing
            $tenantsWithChanges = [];
            $updatedCounts = 0;

            foreach ($allocations as $tenantId => $allocation) {
                if (empty($allocation['commodities']) || !is_array($allocation['commodities'])) {
                    continue;
                }

                $tenant = Tenant::find($tenantId);
                if (!$tenant || !$tenant->isActive()) {
                    continue;
                }

                // Get current allocations for this tenant
                $currentAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
                    ->where('tenant_id', $tenantId)
                    ->get()
                    ->keyBy('global_commodity_id');

                $tenantHadChanges = false;

                // Process each commodity allocation - only commodities associated with this season
                foreach ($season->commodities as $commodity) {
                    $commodityId = $commodity->id;

                    // Check if this commodity is in the submitted data
                    if (isset($allocation['commodities'][$commodityId])) {
                        $data = $allocation['commodities'][$commodityId];
                        $requestedStock = (float)($data['allocated_stock'] ?? 0);
                    } else {
                        // If commodity not in form, assume keep current (or set to 0 if user intends)
                        // For safety, keep current value if not explicitly set
                        $requestedStock = $currentAllocations->get($commodityId)->allocated_stock ?? 0;
                    }

                    // Get current allocation
                    $currentAllocation = $currentAllocations->get($commodityId)->allocated_stock ?? 0;
                    $difference = $requestedStock - $currentAllocation;

                    // Skip if no change
                    if ($difference == 0) {
                        continue;
                    }

                    // Validate stock availability if increasing
                    if ($difference > 0) {
                        $totalStock = $commodity->pivot->stock;
                        $otherAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
                            ->where('global_commodity_id', $commodityId)
                            ->where('tenant_id', '!=', $tenantId)
                            ->sum('allocated_stock');

                        $available = $totalStock - $otherAllocations - $currentAllocation;
                        if ($difference > $available) {
                            throw new \Exception("Insufficient stock for {$commodity->name}. Available: {$available}, Requested: {$requestedStock}");
                        }
                    }

                    // Update or create allocation
                    if ($requestedStock > 0) {
                        GlobalTenantAllocation::updateOrCreate(
                            [
                                'global_season_id' => $season->id,
                                'tenant_id' => $tenantId,
                                'global_commodity_id' => $commodityId
                            ],
                            ['allocated_stock' => $requestedStock]
                        );
                        Log::info("Updated allocation for tenant {$tenantId} and commodity {$commodityId} to {$requestedStock}");
                    } else {
                        // Delete if set to 0
                        $allocationRecord = $currentAllocations->get($commodityId);
                        if ($allocationRecord) {
                            $allocationRecord->delete();
                            Log::info("Deleted allocation for tenant {$tenantId} and commodity {$commodityId}");
                        }
                    }



                    $tenantHadChanges = true;
                    $updatedCounts++;
                }

                if ($tenantHadChanges) {
                    $tenantsWithChanges[] = $tenantId;
                }
            }

            // Sync only tenants that had changes
            $finalSyncResults = [];
            foreach ($tenantsWithChanges as $tenantId) {
                $tenant = Tenant::find($tenantId);
                if (!$tenant || !$tenant->isActive()) {
                    continue;
                }

                try {
                    $result = $this->syncService->syncSeasonToTenant($season, $tenantId);
                    $finalSyncResults[] = [
                        'tenant_id' => $tenantId,
                        'success' => $result['success'],
                        'message' => $result['message']
                    ];
                } catch (\Exception $e) {
                    $finalSyncResults[] = [
                        'tenant_id' => $tenantId,
                        'success' => false,
                        'message' => 'Sync failed: ' . $e->getMessage()
                    ];
                }
            }

            DB::commit();

            $successCount = collect($finalSyncResults)->where('success', true)->count();
            $totalCount = count($finalSyncResults);

            if ($updatedCounts === 0) {
                return redirect()->route('global.allocations.index', $season->uuid)
                    ->with('info', 'No changes were made to allocations.');
            }

            return redirect()->route('global.allocations.index', $season->uuid)
                ->with('success', "Updated {$updatedCounts} commodity allocations and synced to {$successCount} of {$totalCount} tenants successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating allocations: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show edit form for a specific tenant's allocation
     */
    public function edit($seasonUuid, $tenantId)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)
            ->with('commodities')
            ->firstOrFail();

        $tenant = Tenant::findOrFail($tenantId);

        // Get all allocations for this tenant in the current season
        $allocations = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->where('tenant_id', $tenantId)
            ->with('commodity')
            ->get()
            ->keyBy('global_commodity_id');

        // Ensure we have an allocation entry for each commodity, even if it's 0
        $allocations = $season->commodities->mapWithKeys(function ($commodity) use ($allocations, $tenantId, $season) {
            $allocation = $allocations->get($commodity->id) ?? new GlobalTenantAllocation([
                'global_season_id' => $season->id,
                'tenant_id' => $tenantId,
                'global_commodity_id' => $commodity->id,
                'allocated_stock' => 0,
                'commodity' => $commodity
            ]);

            return [$commodity->id => $allocation];
        });

        // Calculate available stock for each commodity
        $availableStock = [];
        foreach ($season->commodities as $commodity) {
            // Get total allocations for this commodity across all tenants
            $totalAllocated = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('global_commodity_id', $commodity->id)
                ->sum('allocated_stock');

            // Get current tenant's allocation for this commodity
            $currentAllocation = $allocations->get($commodity->id)->allocated_stock ?? 0;

            // Calculate available stock (total stock - allocations from other tenants)
            $otherAllocations = $totalAllocated - $currentAllocation;
            $available = $commodity->pivot->stock - $otherAllocations;

            $availableStock[$commodity->id] = [
                'total' => $commodity->pivot->stock,
                'other_allocations' => $otherAllocations,
                'current_allocation' => $currentAllocation,
                'available' => max(0, $available),
                'available_including_current' => max(0, $available + $currentAllocation)
            ];
        }

        return view('global.allocations.edit', compact('season', 'tenant', 'allocations', 'availableStock'));
    }

    /**
     * Update a tenant's allocation
     */
    public function update(Request $request, $seasonUuid, $tenantId)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)
            ->with('commodities')
            ->firstOrFail();

        $tenant = Tenant::findOrFail($tenantId);

        // Get current allocations for validation
        $currentAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->where('tenant_id', $tenantId)
            ->get()
            ->keyBy('global_commodity_id');

        // Process and validate the input
        $commodities = collect($request->input('commodities', []))->mapWithKeys(function ($item) {
            return [$item['commodity_id'] => [
                'commodity_id' => $item['commodity_id'],
                'allocated_stock' => (float)$item['allocated_stock']
            ]];
        });

        // Validate each commodity
        $validated = $request->validate([
            'commodities' => 'required|array|min:1',
            'commodities.*.commodity_id' => 'required|exists:global_commodities,id',
            'commodities.*.allocated_stock' => 'required|numeric|min:0',
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // First pass: Validate stock availability
            foreach ($validated['commodities'] as $item) {
                $commodityId = $item['commodity_id'];
                $requestedStock = (float)$item['allocated_stock'];
                $currentAllocation = $currentAllocations->get($commodityId)->allocated_stock ?? 0;
                $difference = $requestedStock - $currentAllocation;

                // Only check if increasing allocation
                if ($difference > 0) {
                    $commodity = $season->commodities->find($commodityId);

                    if (!$commodity) {
                        throw new \Exception("Commodity not found in season");
                    }

                    $totalStock = $commodity->pivot->stock;

                    // Get allocations from other tenants
                    $otherAllocations = GlobalTenantAllocation::where('global_season_id', $season->id)
                        ->where('global_commodity_id', $commodityId)
                        ->where('tenant_id', '!=', $tenantId)
                        ->sum('allocated_stock');

                    $available = $totalStock - $otherAllocations - $currentAllocation;

                    if ($difference > $available) {
                        return back()->withErrors([
                            'commodities.' . $commodityId . '.allocated_stock' =>
                                "Not enough stock available. Only {$available} units remaining for this commodity."
                        ])->withInput();
                    }
                }
            }

            // Second pass: Update allocations
            $updatedAllocations = [];

            foreach ($validated['commodities'] as $item) {
                $commodityId = $item['commodity_id'];
                $allocatedStock = (float)$item['allocated_stock'];

                // Get the current allocation amount (0 if new)
                $currentAllocation = $currentAllocations->get($commodityId)->allocated_stock ?? 0;
                $difference = $allocatedStock - $currentAllocation;

                // Update or create the allocation
                $allocation = GlobalTenantAllocation::updateOrCreate(
                    [
                        'global_season_id' => $season->id,
                        'tenant_id' => $tenantId,
                        'global_commodity_id' => $commodityId
                    ],
                    ['allocated_stock' => $allocatedStock]
                );

                $updatedAllocations[] = $allocation;



                // If allocation is zero, delete the record to keep the database clean
                if ($allocatedStock == 0) {
                    $allocation->delete();
                }
            }

            // Sync to tenant database
            $syncResult = $this->syncService->syncSeasonToTenant($season, $tenantId);

            if (!isset($syncResult['success']) || !$syncResult['success']) {
                throw new \Exception($syncResult['message'] ?? 'Failed to sync with tenant database');
            }

            DB::commit();

            return redirect()
                ->route('global.allocations.edit', ['seasonUuid' => $season->uuid, 'tenantId' => $tenantId])
                ->with('success', 'Allocations updated and synced successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update allocations: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()
                ->with('error', 'Failed to update allocations: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Sync specific tenant allocation
     */
    public function sync($seasonUuid, $tenantId)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();
        $tenant = Tenant::findOrFail($tenantId);

        if (!$tenant->isActive()) {
            return response()->json([
                'message' => 'Cannot sync to inactive tenant'
            ], 422);
        }

        try {
            $result = $this->syncService->syncSeasonToTenant($season, $tenantId);

            return response()->json([
                'message' => $result['success'] ? 'Season synced successfully' : 'Sync failed',
                'data' => $result
            ], $result['success'] ? 200 : 500);

        } catch (\Exception $e) {
            Log::error("Error syncing season {$season->id} to tenant {$tenantId}: " . $e->getMessage());

            return response()->json([
                'message' => 'Failed to sync season',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync season to all allocated tenants
     */
    public function syncAll($seasonUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();

        $tenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->distinct()
            ->pluck('tenant_id');

        if ($tenantIds->isEmpty()) {
            return response()->json([
                'message' => 'No tenant allocations found for this season'
            ], 422);
        }

        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($tenantIds as $tenantId) {
            $tenant = Tenant::find($tenantId);

            if (!$tenant || !$tenant->isActive()) {
                $results[$tenantId] = [
                    'success' => false,
                    'message' => 'Tenant inactive or not found'
                ];
                $failCount++;
                continue;
            }

            try {
                $result = $this->syncService->syncSeasonToTenant($season, $tenantId);
                $results[$tenantId] = $result;

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
                $failCount++;
            }
        }

        return response()->json([
            'message' => "Sync completed: {$successCount} succeeded, {$failCount} failed",
            'results' => $results
        ]);
    }

    /**
     * Delete allocation for a tenant
     */
    public function destroy($seasonUuid, $tenantId)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();
        $tenant = Tenant::findOrFail($tenantId);

        try {
            DB::beginTransaction();

            // Get the allocations before deleting them for sync purposes
            $allocations = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('tenant_id', $tenantId)
                ->get();

            if ($allocations->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No allocations found for this tenant'
                ], 404);
            }

            // Restore the allocated stock back to the global commodity
            foreach ($allocations as $allocation) {
                $season->commodities()->updateExistingPivot($allocation->global_commodity_id, [
                    'stock' => DB::raw('stock + ' . $allocation->allocated_stock)
                ]);
            }

            // Delete the allocations
            $deleted = GlobalTenantAllocation::where('global_season_id', $season->id)
                ->where('tenant_id', $tenantId)
                ->delete();

            if ($deleted === 0) {
                throw new \Exception('Failed to delete allocations');
            }

            // Sync the deletion to the tenant using the dedicated allocation deletion method
            $result = $this->syncService->syncAllocationDeletionToTenant($season, $tenantId);

            if (!$result['success']) {
                throw new \Exception('Failed to sync deletion to tenant: ' . ($result['message'] ?? 'Unknown error'));
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allocation deleted and synced successfully',
                    'redirect' => route('global.allocations.index', $season->uuid)
                ]);
            }

            return redirect()
                ->route('global.allocations.index', $season->uuid)
                ->with('success', 'Allocation deleted and synced successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting allocation: ' . $e->getMessage());
            Log::error($e);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete allocation',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to delete allocation: ' . $e->getMessage()]);
        }
    }
}
