<?php

namespace App\Services;

use App\Models\GlobalSeason;
use App\Models\GlobalCommodity;
use App\Models\GlobalCommodityMarketPrice;
use App\Models\GlobalTenantAllocation;
use App\Models\SuperAdmin\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TenantSyncService
{
    /**
     * Sync a season and its allocations to a specific tenant's database.
     *
     * @param GlobalSeason $season
     * @param string $tenantId
     * @return array
     */
    public function syncSeasonToTenant(GlobalSeason $season, string $tenantId): array
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);

            if (!$tenant->isActive()) {
                return [
                    'success' => false,
                    'message' => 'Cannot sync to inactive tenant',
                ];
            }

            // Set up tenant database connection
            $this->setTenantConnection($tenant);

            // Begin transaction on tenant database
            DB::connection('tenant')->beginTransaction();

            // Close all open seasons before syncing the new season
            $this->closeOpenSeasonsForTenant($tenant);

            // Sync season
            $this->syncSeason($season);

            // Sync associated commodities
            $this->syncSeasonCommodities($season);

            // Sync allocations for this tenant
            $this->syncAllocations($season, $tenantId);

            DB::connection('tenant')->commit();

            // Log success in sync_logs
            $this->logSync($season, $tenant, 'success');

            return [
                'success' => true,
                'message' => "Season {$season->name} synced successfully to tenant {$tenant->name}",
            ];
        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            Log::error("Failed to sync season {$season->id} to tenant {$tenantId}: " . $e->getMessage());

            // Log failure in sync_logs
            $this->logSync($season, $tenant, 'failed', $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Sync commodity updates to all tenants with allocations for seasons using this commodity.
     *
     * @param GlobalCommodity $commodity
     * @return array
     */
    public function syncCommodityUpdate(GlobalCommodity $commodity): array
    {
        $results = [];
        $seasonIds = $commodity->seasons()->pluck('global_seasons.id');
        $tenantIds = GlobalTenantAllocation::whereIn('global_season_id', $seasonIds)
            ->distinct()
            ->pluck('tenant_id');

        // Also sync to tenants that just have the season (fallback)
        if ($tenantIds->isEmpty()) {
            $tenantIds = GlobalTenantAllocation::whereIn('global_season_id', $seasonIds)
                ->distinct()
                ->pluck('tenant_id');
        }

        // If still no tenants, include ALL active tenants (commodity might be synced broadly)
        if ($tenantIds->isEmpty()) {
            $tenantIds = \App\Models\SuperAdmin\Tenant::active()->pluck('id');
        }

        \Log::info("Syncing commodity update to tenants", [
            'commodity_id' => $commodity->id,
            'commodity_name' => $commodity->name,
            'season_ids' => $seasonIds->toArray(),
            'tenant_count' => $tenantIds->count()
        ]);

        // Get all season names in one query to improve performance and correctness
        $seasons = \App\Models\GlobalSeason::whereIn('id', $seasonIds)->pluck('name', 'id');

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = \App\Models\SuperAdmin\Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                \Log::info("Syncing commodity {$commodity->id} to tenant {$tenant->name}", [
                    'commodity' => $commodity->name,
                    'seasons' => $seasons->toArray()
                ]);

                // For commodity updates, we need to update all season-specific commodity records
                foreach ($seasons as $seasonId => $seasonName) {
                    $seasonCommodityUuid = md5($commodity->uuid . $seasonId);
                    $fullCommodityName = $commodity->name . ' - ' . $seasonName;

                    \Log::info("Updating season commodity", [
                        'tenant' => $tenant->name,
                        'uuid' => $seasonCommodityUuid,
                        'name' => $fullCommodityName,
                        'season_id' => $seasonId
                    ]);

                    // Use updateOrInsert to either update existing or create new
                    DB::connection('tenant')->table('commodities')->updateOrInsert(
                        ['uuid' => $seasonCommodityUuid],
                        [
                            'name' => $fullCommodityName,
                            'category' => $commodity->category->name,
                            'unit' => $commodity->unit,
                            'stock' => 0,
                            'price_per_unit' => $commodity->price_per_unit,
                            'quantity_per_hectare' => $commodity->quantity_per_hectare,
                            'updated_at' => now(),
                        ]
                    );

                    \Log::info("Successfully synced season commodity to tenant {$tenant->name}", [
                        'uuid' => $seasonCommodityUuid,
                        'name' => $fullCommodityName
                    ]);
                }

                $updatedCount = $seasons->count();
                $results[$tenantId] = [
                    'success' => true,
                    'message' => "Commodity {$commodity->name} synced to tenant {$tenant->name} ({$updatedCount} season records)",
                ];
            } catch (\Exception $e) {
                \Log::error("Failed to sync commodity {$commodity->id} to tenant {$tenantId}: " . $e->getMessage());
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Sync market price deletion to all tenants that had the market price.
     *
     * @param GlobalCommodityMarketPrice $marketPrice
     * @return array
     */
    public function syncMarketPriceDeletionToTenants(GlobalCommodityMarketPrice $marketPrice): array
    {
        $results = [];

        // Find tenants that had allocations for this combination (and thus had the market price)
        $tenantIds = GlobalTenantAllocation::where('global_commodity_id', $marketPrice->global_commodity_id)
            ->where('global_season_id', $marketPrice->global_season_id)
            ->distinct()
            ->pluck('tenant_id');

        // Also check if there are other allocations for the season (fallback)
        if ($tenantIds->isEmpty()) {
            $tenantIds = GlobalTenantAllocation::where('global_season_id', $marketPrice->global_season_id)
                ->distinct()
                ->pluck('tenant_id');
        }

        // If still no tenants found, check if there are ANY active tenants - market prices might sync to all
        if ($tenantIds->isEmpty()) {
            $tenantIds = \App\Models\SuperAdmin\Tenant::active()->pluck('id');
        }

        \Log::info("Syncing market price deletion to tenants", [
            'market_price_id' => $marketPrice->id,
            'tenant_ids' => $tenantIds->toArray(),
            'tenant_count' => $tenantIds->count()
        ]);

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = \App\Models\SuperAdmin\Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                // Method 1: Try to delete by UUID directly (if UUIDs were kept consistent)
                $deleted = DB::connection('tenant')
                    ->table('commodity_market_prices')
                    ->where('uuid', $marketPrice->uuid)
                    ->delete();

                \Log::info("Attempted UUID-based deletion", [
                    'tenant' => $tenant->name,
                    'uuid' => $marketPrice->uuid,
                    'deleted_count' => $deleted
                ]);

                // If UUID deletion didn't work, try Method 2: Find by commodity and season
                if ($deleted == 0) {
                    // Get tenant's commodity and season IDs
                    $globalCommodityUuid = $marketPrice->commodity->uuid;
                    $seasonCommodityUuid = md5($globalCommodityUuid . $marketPrice->global_season_id);

                    $tenantCommodity = DB::connection('tenant')
                        ->table('commodities')
                        ->where('uuid', $seasonCommodityUuid)
                        ->first();

                    \Log::info("Looking for tenant commodity", [
                        'tenant' => $tenant->name,
                        'global_commodity_uuid' => $globalCommodityUuid,
                        'season_commodity_uuid' => $seasonCommodityUuid,
                        'commodity_found' => $tenantCommodity ? true : false
                    ]);

                    if ($tenantCommodity) {
                        $tenantSeason = DB::connection('tenant')
                            ->table('seasons')
                            ->where('uuid', $marketPrice->season->uuid)
                            ->first();

                        \Log::info("Looking for tenant season", [
                            'tenant' => $tenant->name,
                            'global_season_uuid' => $marketPrice->season->uuid,
                            'season_found' => $tenantSeason ? true : false
                        ]);

                        if ($tenantSeason) {
                            // Delete the tenant market price by commodity and season
                            $deleted = DB::connection('tenant')
                                ->table('commodity_market_prices')
                                ->where('commodity_id', $tenantCommodity->id)
                                ->where('season_id', $tenantSeason->id)
                                ->delete();

                            \Log::info("Commodity-season deletion result", [
                                'tenant' => $tenant->name,
                                'commodity_id' => $tenantCommodity->id,
                                'season_id' => $tenantSeason->id,
                                'deleted_count' => $deleted
                            ]);
                        }
                    }
                }

                $results[$tenantId] = [
                    'success' => $deleted > 0,
                    'message' => $deleted > 0
                        ? "Market price deleted successfully from tenant {$tenant->name}"
                        : "No market price found to delete from tenant {$tenant->name}",
                ];

                \Log::info("Final deletion result for tenant {$tenant->name}", $results[$tenantId]);

            } catch (\Exception $e) {
                \Log::error("Failed to sync market price deletion {$marketPrice->id} to tenant {$tenantId}: " . $e->getMessage());
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        \Log::info("Completed market price deletion sync", [
            'global_market_price_id' => $marketPrice->id,
            'tenants_processed' => count($results),
            'successful_deletions' => collect($results)->where('success', true)->count()
        ]);

        return $results;
    }

    /**
     * Sync market price to all tenants that have the related commodity and season.
     *
     * @param GlobalCommodityMarketPrice $marketPrice
     * @return array
     */
    public function syncMarketPriceToTenants(GlobalCommodityMarketPrice $marketPrice): array
    {
        $results = [];

        // Find tenants that have allocations for this combination of commodity and season
        // First check direct allocations
        $tenantIds = GlobalTenantAllocation::where('global_commodity_id', $marketPrice->global_commodity_id)
            ->where('global_season_id', $marketPrice->global_season_id)
            ->distinct()
            ->pluck('tenant_id');

        // If no direct allocations found, sync to tenants that have the season (broader approach)
        // This ensures market prices are available to tenants with the season even if allocations come later
        if ($tenantIds->isEmpty()) {
            $tenantIds = GlobalTenantAllocation::where('global_season_id', $marketPrice->global_season_id)
                ->distinct()
                ->pluck('tenant_id');
        }

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = \App\Models\SuperAdmin\Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                // Get tenant's commodity and season IDs
                $globalCommodityUuid = $marketPrice->commodity->uuid;
                $seasonCommodityUuid = md5($globalCommodityUuid . $marketPrice->global_season_id);

                $tenantCommodity = DB::connection('tenant')
                    ->table('commodities')
                    ->where('uuid', $seasonCommodityUuid)
                    ->first();

                if (!$tenantCommodity) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant commodity not found',
                    ];
                    continue;
                }

                $tenantSeason = DB::connection('tenant')
                    ->table('seasons')
                    ->where('uuid', $marketPrice->season->uuid)
                    ->first();

                if (!$tenantSeason) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant season not found',
                    ];
                    continue;
                }

                // Sync the market price data
                DB::connection('tenant')->table('commodity_market_prices')->updateOrInsert(
                    [
                        'commodity_id' => $tenantCommodity->id,
                        'season_id' => $tenantSeason->id,
                    ],
                    [
                        'current_price' => $marketPrice->current_price,
                        'uuid' => $marketPrice->uuid, // Keep the same UUID for consistency
                        'created_at' => $marketPrice->created_at,
                        'updated_at' => now(),
                    ]
                );

                $results[$tenantId] = [
                    'success' => true,
                    'message' => "Market price synced successfully to tenant {$tenant->name}",
                ];
            } catch (\Exception $e) {
                \Log::error("Failed to sync market price {$marketPrice->id} to tenant {$tenantId}: " . $e->getMessage());
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Close all open seasons in the current tenant database before syncing new season.
     *
     * @param Tenant $tenant
     * @return void
     */
    protected function closeOpenSeasonsForTenant(Tenant $tenant): void
    {
        // Find all seasons with status 'open'
        $openSeasons = DB::connection('tenant')
            ->table('seasons')
            ->where('status', 'open')
            ->get();

        if ($openSeasons->isEmpty()) {
            Log::info("No open seasons found to close for tenant {$tenant->name}");
            return;
        }

        // Update all open seasons to 'closed'
        $updatedCount = DB::connection('tenant')
            ->table('seasons')
            ->where('status', 'open')
            ->update([
                'status' => 'closed',
                'updated_at' => now()
            ]);

        Log::info("Closed {$updatedCount} open seasons for tenant {$tenant->name}", [
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->name,
            'seasons_closed' => $openSeasons->pluck('name')->toArray()
        ]);

        // Log each closed season
        foreach ($openSeasons as $season) {
            Log::info("Season closed during sync", [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'season_uuid' => $season->uuid,
                'season_name' => $season->name,
                'action' => 'automatically_closed_before_new_season_sync'
            ]);
        }
    }

    /**
     * Close a season across all allocated tenants.
     *
     * @param GlobalSeason $season
     * @return array
     */
    public function closeSeasonGlobally(GlobalSeason $season): array
    {
        $results = [];
        $tenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->distinct()
            ->pluck('tenant_id');

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                // Update season status in tenant database
                DB::connection('tenant')->table('seasons')
                    ->where('uuid', $season->uuid)
                    ->update(['status' => 'closed', 'updated_at' => now()]);

                // Log success in sync_logs
                $this->logSync($season, $tenant, 'success');

                $results[$tenantId] = [
                    'success' => true,
                    'message' => "Season {$season->name} closed successfully for tenant {$tenant->name}",
                ];
            } catch (\Exception $e) {
                Log::error("Failed to close season {$season->id} for tenant {$tenantId}: " . $e->getMessage());

                // Log failure in sync_logs
                $this->logSync($season, $tenant, 'failed', $e->getMessage());

                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Set up the tenant database connection.
     *
     * @param Tenant $tenant
     * @return void
     */
    protected function setTenantConnection(Tenant $tenant): void
    {
        // Get the tenant database name using the tenancy package method
        $databaseName = $tenant->database()->getName();

        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $databaseName,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        // Reconnect to the tenant database
        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    /**
     * Sync season data to tenant's database.
     *
     * @param GlobalSeason $season
     * @return void
     */
    protected function syncSeason(GlobalSeason $season): void
    {
        DB::connection('tenant')->table('seasons')->updateOrInsert(
            ['uuid' => $season->uuid],
            [
                'name' => $season->name,
                'type' => $season->type,
                'loan_type' => $season->loan_type,
                'start_date' => $season->start_date,
                'end_date' => $season->end_date,
                'collection_start_date' => $season->collection_start_date,
                'collection_end_date' => $season->collection_end_date,
                // 'budget' => $season->budget,
                'status' => $season->status,
                'return_deadline' => $season->return_deadline,
                'insurance_rate' => $season->insurance_rate,
                'send_reminder_after_days' => $season->send_reminder_after_days,
                'created_at' => $season->created_at,
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Sync season's commodities to tenant's database.
     *
     * @param GlobalSeason $season
     * @return void
     */
    protected function syncSeasonCommodities(GlobalSeason $season): void
    {
        $commodities = $season->commodities()->withPivot('stock')->get();

        // Get the tenant's season ID using the UUID
        $tenantSeason = DB::connection('tenant')
            ->table('seasons')
            ->where('uuid', $season->uuid)
            ->first();

        if (!$tenantSeason) {
            \Log::error("Tenant season not found for global season: {$season->id} when syncing commodities");
            return;
        }

        foreach ($commodities as $commodity) {
            // Create a unique identifier for this commodity+season combination by hashing
            $seasonCommodityUuid = md5($commodity->uuid . $season->id);

            // Check if we already have this commodity for this season
            $tenantCommodity = DB::connection('tenant')
                ->table('commodities')
                ->where('uuid', $seasonCommodityUuid)
                ->first();

            if (!$tenantCommodity) {
                // Insert a new commodity record specifically for this season
                $commodityId = DB::connection('tenant')->table('commodities')->insertGetId([
                    'uuid' => $seasonCommodityUuid,
                    'name' => $commodity->name . ' - ' . $season->name,
                    'category' => $commodity->category->name,
                    'unit' => $commodity->unit,
                    'price_per_unit' => $commodity->price_per_unit,
                    'quantity_per_hectare' => $commodity->quantity_per_hectare,
                    'stock' => $commodity->pivot->stock ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $tenantCommodity = (object) [
                    'id' => $commodityId,
                    'uuid' => $seasonCommodityUuid,
                    'name' => $commodity->name . ' - ' . $season->name,
                    'category' => $commodity->category_id,
                    'unit' => $commodity->unit,
                    'price_per_unit' => $commodity->price_per_unit,
                    'quantity_per_hectare' => $commodity->quantity_per_hectare,
                ];
            }

            if (!$tenantCommodity) {
                \Log::warning("Failed to create tenant commodity for global commodity: {$commodity->id}");
                continue;
            }

            // Update the pivot table with the correct stock for this season's commodity
            DB::connection('tenant')->table('commodity_seasons')->updateOrInsert(
                [
                    'season_id' => $tenantSeason->id,
                    'commodity_id' => $tenantCommodity->id,
                ],
                [
                    'stock' => $commodity->pivot->stock,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Also update the commodity's stock to match the pivot
            DB::connection('tenant')
                ->table('commodities')
                ->where('id', $tenantCommodity->id)
                ->update(['stock' => $commodity->pivot->stock]);
        }
    }

    /**
     * Sync allocations for a season and tenant to tenant's database.
     *
     * @param GlobalSeason $season
     * @param string $tenantId
     * @return void
     */
    protected function syncAllocations(GlobalSeason $season, string $tenantId): void
    {
        \Log::info('Starting syncAllocations', [
            'season_id' => $season->id,
            'tenant_id' => $tenantId,
            'season_uuid' => $season->uuid
        ]);

        // Eager load the commodity relationship to avoid N+1 queries
        $allocations = GlobalTenantAllocation::with('commodity')
            ->where('global_season_id', $season->id)
            ->where('tenant_id', $tenantId)
            ->get();

        \Log::debug('Found allocations to sync', [
            'count' => $allocations->count(),
            'allocations' => $allocations->map(function($a) {
                return [
                    'id' => $a->id,
                    'commodity_id' => $a->global_commodity_id,
                    'commodity_uuid' => $a->commodity?->uuid,
                    'stock' => $a->allocated_stock
                ];
            })
        ]);

        // Get the tenant's season ID using the UUID
        $tenantSeason = DB::connection('tenant')
            ->table('seasons')
            ->where('uuid', $season->uuid)
            ->first();

        if (!$tenantSeason) {
            $error = "Tenant season not found for global season: {$season->id} (UUID: {$season->uuid}) when syncing allocations";
            \Log::error($error);
            throw new \RuntimeException($error);
        }

        // Get all tenant commodities in one query for better performance
        // Tenant commodity UUID is md5(global_commodity_uuid + season_id)
        $commodityUuids = $allocations->pluck('commodity.uuid')->filter()
            ->map(fn($uuid) => md5($uuid . $season->id))
            ->toArray();

        \Log::debug('Fetching tenant commodities', ['uuids' => $commodityUuids]);

        $tenantCommodities = collect();
        if (!empty($commodityUuids)) {
            $tenantCommodities = DB::connection('tenant')
                ->table('commodities')
                ->whereIn('uuid', $commodityUuids)
                ->get()
                ->keyBy('uuid');
        }

        \Log::debug('Found tenant commodities', [
            'count' => $tenantCommodities->count(),
            'commodities' => $tenantCommodities->map(fn($c) => [
                'id' => $c->id,
                'uuid' => $c->uuid,
                'name' => $c->name
            ])->values()
        ]);

        // Track which commodities we've processed
        $processedCommodityIds = [];
        $syncResults = [];

        foreach ($allocations as $allocation) {
            if (!$allocation->commodity) {
                $error = "Commodity not found for allocation ID: {$allocation->id}";
                \Log::error($error);
                $syncResults[] = ['success' => false, 'message' => $error];
                continue;
            }

            $commodityUuid = $allocation->commodity->uuid;
            $tenantCommodityUuid = md5($commodityUuid . $season->id);
            $tenantCommodity = $tenantCommodities->get($tenantCommodityUuid);

            if (!$tenantCommodity) {
                $error = "Tenant commodity not found for global commodity UUID: {$commodityUuid}";
                \Log::error($error);
                $syncResults[] = ['success' => false, 'message' => $error];
                continue;
            }

            try {
                // Fetch existing tenant allocation to calculate proper available_stock
                $existingAllocation = DB::connection('tenant')
                    ->table('allocations')
                    ->where('season_id', $tenantSeason->id)
                    ->where('commodity_id', $tenantCommodity->id)
                    ->first();

                $oldAllocatedStock = $existingAllocation ? $existingAllocation->allocated_stock : 0;
                $oldAvailableStock = $existingAllocation ? $existingAllocation->available_stock : 0;
                $distributed = $oldAllocatedStock - $oldAvailableStock;

                // Calculate new available stock: new_allocation - distributed, but never below 0
                $newAvailableStock = max(0, $allocation->allocated_stock - $distributed);

                // Prevent updates that would make total distributed > new allocation
                if ($distributed > $allocation->allocated_stock) {
                    $error = "Cannot update allocation: distributed amount ({$distributed}) exceeds new allocation ({$allocation->allocated_stock}) for commodity {$commodityUuid}";
                    \Log::error($error, [
                        'allocation_id' => $allocation->id,
                        'old_allocated' => $oldAllocatedStock,
                        'old_available' => $oldAvailableStock,
                        'distributed' => $distributed,
                        'new_allocation' => $allocation->allocated_stock
                    ]);
                    $syncResults[] = ['success' => false, 'message' => $error];
                    continue;
                }

                // Update or create the allocation with proper available_stock calculation
                DB::connection('tenant')->table('allocations')->updateOrInsert(
                    [
                        'season_id' => $tenantSeason->id,
                        'commodity_id' => $tenantCommodity->id,
                    ],
                    [
                        'allocated_stock' => $allocation->allocated_stock,
                        'available_stock' => $newAvailableStock,
                        'created_at' => $allocation->created_at ?? now(),
                        'updated_at' => now(),
                    ]
                );

                // Log the allocation change
                \Log::info('Allocation updated', [
                    'tenant_id' => $tenantId,
                    'season_id' => $tenantSeason->id,
                    'commodity_id' => $tenantCommodity->id,
                    'commodity_uuid' => $commodityUuid,
                    'old_allocated_stock' => $oldAllocatedStock,
                    'old_available_stock' => $oldAvailableStock,
                    'distributed' => $distributed,
                    'new_allocated_stock' => $allocation->allocated_stock,
                    'new_available_stock' => $newAvailableStock,
                    'change_type' => $allocation->allocated_stock > $oldAllocatedStock ? 'increase' : ($allocation->allocated_stock < $oldAllocatedStock ? 'decrease' : 'no_change')
                ]);

                // Track that we've processed this commodity
                $processedCommodityIds[] = $tenantCommodity->id;
                $syncResults[] = [
                    'success' => true,
                    'commodity_id' => $tenantCommodity->id,
                    'commodity_uuid' => $commodityUuid,
                    'stock' => $allocation->allocated_stock,
                    'available_stock' => $newAvailableStock
                ];

                \Log::debug('Synced allocation', [
                    'season_id' => $tenantSeason->id,
                    'commodity_id' => $tenantCommodity->id,
                    'allocated_stock' => $allocation->allocated_stock,
                    'available_stock' => $newAvailableStock
                ]);

            } catch (\Exception $e) {
                $error = "Failed to sync allocation for commodity {$commodityUuid}: " . $e->getMessage();
                \Log::error($error);
                $syncResults[] = ['success' => false, 'message' => $error];
            }
        }

        // Clean up any allocations that might have been removed
        if (!empty($processedCommodityIds)) {
            try {
                $deleted = DB::connection('tenant')
                    ->table('allocations')
                    ->where('season_id', $tenantSeason->id)
                    ->whereNotIn('commodity_id', $processedCommodityIds)
                    ->delete();

                if ($deleted > 0) {
                    \Log::info("Cleaned up $deleted old allocations for season {$tenantSeason->id}");
                }
            } catch (\Exception $e) {
                \Log::error("Failed to clean up old allocations: " . $e->getMessage());
            }
        }

        \Log::info('Finished syncAllocations', [
            'season_id' => $season->id,
            'tenant_id' => $tenantId,
            'results' => $syncResults
        ]);
    }

    /**
     * Log sync attempt to sync_logs table.
     *
     * @param GlobalSeason $season
     * @param Tenant $tenant
     * @param string $status
     * @param string|null $errorMessage
     * @return void
     */
    protected function logSync(GlobalSeason $season, Tenant $tenant, string $status, ?string $errorMessage = null): void
    {
        DB::connection('mysql')->table('sync_logs')->insert([
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->name,
            'season_id' => $season->id,
            'season_name' => $season->name,
            'status' => $status,
            'error_message' => $errorMessage,
            'synced_at' => $status === 'success' ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Sync season updates to all tenants that have the season.
     *
     * @param GlobalSeason $season
     * @return array
     */
    public function syncSeasonUpdate(GlobalSeason $season): array
    {
        $results = [];

        // Find tenants that have allocations for this season
        $tenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->distinct()
            ->pluck('tenant_id');

        Log::info("Syncing season update to tenants", [
            'season_id' => $season->id,
            'season_name' => $season->name,
            'tenant_count' => $tenantIds->count()
        ]);

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = \App\Models\SuperAdmin\Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                Log::info("Updating season {$season->id} for tenant {$tenant->name}", [
                    'season_uuid' => $season->uuid,
                    'season_name' => $season->name
                ]);

                // Update the season record in tenant database
                DB::connection('tenant')->table('seasons')->updateOrInsert(
                    ['uuid' => $season->uuid],
                    [
                        'name' => $season->name,
                        'type' => $season->type,
                        'loan_type' => $season->loan_type,
                        'start_date' => $season->start_date,
                        'end_date' => $season->end_date,
                        'collection_start_date' => $season->collection_start_date,
                        'collection_end_date' => $season->collection_end_date,
                        'status' => $season->status,
                        'return_deadline' => $season->return_deadline,
                        'insurance_rate' => $season->insurance_rate,
                        'send_reminder_after_days' => $season->send_reminder_after_days,
                        'updated_at' => now(),
                    ]
                );

                $results[$tenantId] = [
                    'success' => true,
                    'message' => "Season {$season->name} updated successfully for tenant {$tenant->name}",
                ];

                Log::info("Season update result for tenant {$tenant->name}", $results[$tenantId]);

            } catch (\Exception $e) {
                Log::error("Failed to sync season update {$season->id} to tenant {$tenantId}: " . $e->getMessage());
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        Log::info("Completed season update sync", [
            'global_season_id' => $season->id,
            'global_season_name' => $season->name,
            'tenants_processed' => count($results),
            'successful_updates' => collect($results)->where('success', true)->count()
        ]);

        return $results;
    }

    /**
     * Sync allocation deletions to a specific tenant (used by allocation deletion).
     * This bypasses the active tenant check since we want to clean up inactive tenants too.
     *
     * @param GlobalSeason $season
     * @param string $tenantId
     * @return array
     */
    public function syncAllocationDeletionToTenant(GlobalSeason $season, string $tenantId): array
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);

            // Set up tenant database connection (even for inactive tenants)
            $this->setTenantConnection($tenant);

            // Begin transaction on tenant database
            DB::connection('tenant')->beginTransaction();

            // Get the tenant's season record to check for cleanup
            $tenantSeason = DB::connection('tenant')
                ->table('seasons')
                ->where('uuid', $season->uuid)
                ->first();

            if (!$tenantSeason) {
                \Log::warning("No tenant season found for allocation deletion", [
                    'season_uuid' => $season->uuid,
                    'tenant_id' => $tenantId
                ]);
                // Still return success as there's nothing to clean up
                return [
                    'success' => true,
                    'message' => "No allocations found to delete for tenant {$tenant->name}",
                ];
            }

            // Remove season allocations for this tenant
            $allocationsDeleted = DB::connection('tenant')->table('allocations')
                ->where('season_id', $tenantSeason->id)
                ->delete();

            \Log::info("Deleted tenant allocations", [
                'season_id' => $tenantSeason->id,
                'tenant_id' => $tenantId,
                'allocations_deleted' => $allocationsDeleted
            ]);

            // If this was the last allocation for this season, clean up related data
            $remainingAllocations = DB::connection('tenant')
                ->table('allocations')
                ->where('season_id', $tenantSeason->id)
                ->count();

            if ($remainingAllocations == 0) {
                \Log::info("No more allocations for season, cleaning up related data", [
                    'season_id' => $tenantSeason->id,
                    'tenant_id' => $tenantId
                ]);

                // Delete season-specific commodities (they won't be used without allocations)
                $seasonCommoditiesDeleted = DB::connection('tenant')
                    ->table('commodities')
                    ->where('name', 'like', '%' . $season->name . '%')
                    ->delete();

                // Delete commodity-season pivot records
                $commoditySeasonsDeleted = DB::connection('tenant')
                    ->table('commodity_seasons')
                    ->where('season_id', $tenantSeason->id)
                    ->delete();

                // Delete season itself
                $seasonDeleted = DB::connection('tenant')
                    ->table('seasons')
                    ->where('id', $tenantSeason->id)
                    ->delete();

                // Delete related market prices
                $marketPricesDeleted = DB::connection('tenant')
                    ->table('commodity_market_prices')
                    ->where('season_id', $tenantSeason->id)
                    ->delete();

                \Log::info("Complete season cleanup completed", [
                    'tenant_id' => $tenantId,
                    'season_commodities_deleted' => $seasonCommoditiesDeleted,
                    'commodity_seasons_deleted' => $commoditySeasonsDeleted,
                    'season_deleted' => $seasonDeleted,
                    'market_prices_deleted' => $marketPricesDeleted
                ]);
            }

            DB::connection('tenant')->commit();

            // Log success in sync_logs
            $this->logSync($season, $tenant, 'success');

            return [
                'success' => true,
                'message' => "Allocations and related data deleted successfully from tenant {$tenant->name}",
            ];
        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            Log::error("Failed to sync allocation deletion {$season->id} to tenant {$tenantId}: " . $e->getMessage());

            // Log failure in sync_logs - note: tenant might be inactive so we can't get tenant object
            DB::connection('mysql')->table('sync_logs')->insert([
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'season_id' => $season->id,
                'season_name' => $season->name,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Sync season deletions to all tenants that had the season.
     *
     * @param GlobalSeason $season
     * @return array
     */
    public function syncSeasonDeletionToTenants(GlobalSeason $season): array
    {
        $results = [];

        // Find tenants that have allocations for this season
        $tenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
            ->distinct()
            ->pluck('tenant_id');

        Log::info("Syncing season deletion to tenants", [
            'season_id' => $season->id,
            'season_name' => $season->name,
            'tenant_count' => $tenantIds->count()
        ]);

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = \App\Models\SuperAdmin\Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                // Delete the season record and related data from tenant database
                $seasonDeleted = DB::connection('tenant')
                    ->table('seasons')
                    ->where('uuid', $season->uuid)
                    ->delete();

                // Also clean up related data that depends on the season
                if ($seasonDeleted > 0) {
                    // Delete commodity-season relationships
                    DB::connection('tenant')
                        ->table('commodity_seasons')
                        ->where('season_id', function ($query) use ($season) {
                            $query->select('id')
                                ->from('seasons')
                                ->where('uuid', $season->uuid)
                                ->limit(1);
                        })
                        ->delete();

                    // Delete allocations for this season
                    DB::connection('tenant')
                        ->table('allocations')
                        ->where('season_id', function ($query) use ($season) {
                            $query->select('id')
                                ->from('seasons')
                                ->where('uuid', $season->uuid)
                                ->limit(1);
                        })
                        ->delete();

                    // Delete applications for this season
                    DB::connection('tenant')
                        ->table('applications')
                        ->where('season_id', function ($query) use ($season) {
                            $query->select('id')
                                ->from('seasons')
                                ->where('uuid', $season->uuid)
                                ->limit(1);
                        })
                        ->delete();

                    // Delete related market prices
                    DB::connection('tenant')
                        ->table('commodity_market_prices')
                        ->where('season_id', function ($query) use ($season) {
                            $query->select('id')
                                ->from('seasons')
                                ->where('uuid', $season->uuid)
                                ->limit(1);
                        })
                        ->delete();
                }

                $results[$tenantId] = [
                    'success' => $seasonDeleted > 0,
                    'message' => $seasonDeleted > 0
                        ? "Season {$season->name} deleted successfully from tenant {$tenant->name}"
                        : "No season record found to delete from tenant {$tenant->name}",
                ];

                Log::info("Season deletion result for tenant {$tenant->name}", $results[$tenantId]);

            } catch (\Exception $e) {
                Log::error("Failed to sync season deletion {$season->id} to tenant {$tenantId}: " . $e->getMessage());
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        Log::info("Completed season deletion sync", [
            'global_season_id' => $season->id,
            'global_season_name' => $season->name,
            'tenants_processed' => count($results),
            'successful_deletions' => collect($results)->where('success', true)->count()
        ]);

        return $results;
    }

    /**
     * Sync commodity deletion to all tenants that had the commodity.
     *
     * @param GlobalCommodity $commodity
     * @return array
     */
    public function syncCommodityDeletionToTenants(GlobalCommodity $commodity): array
    {
        $results = [];

        // Find tenants that had allocations for seasons using this commodity
        $seasonIds = $commodity->seasons()->pluck('global_seasons.id');
        $tenantIds = GlobalTenantAllocation::whereIn('global_season_id', $seasonIds)
            ->distinct()
            ->pluck('tenant_id');

        // If no direct allocations, check if there are ANY tenants with seasons using this commodity
        if ($tenantIds->isEmpty()) {
            $tenantIds = GlobalTenantAllocation::whereIn('global_season_id', $seasonIds)
                ->distinct()
                ->pluck('tenant_id');
        }

        // As a final fallback, check ALL active tenants since commodities might be synced broadly
        if ($tenantIds->isEmpty()) {
            $tenantIds = \App\Models\SuperAdmin\Tenant::active()->pluck('id');
        }

        \Log::info("Syncing commodity deletion to tenants", [
            'commodity_id' => $commodity->id,
            'commodity_name' => $commodity->name,
            'season_ids' => $seasonIds->toArray(),
            'tenant_count' => $tenantIds->count()
        ]);

        foreach ($tenantIds as $tenantId) {
            try {
                $tenant = \App\Models\SuperAdmin\Tenant::findOrFail($tenantId);

                if (!$tenant->isActive()) {
                    $results[$tenantId] = [
                        'success' => false,
                        'message' => 'Tenant is inactive',
                    ];
                    continue;
                }

                // Set up tenant database connection
                $this->setTenantConnection($tenant);

                // Delete all tenant commodity records that match this global commodity
                // We need to delete by UUID patterns since commodities have season-specific UUIDs
                $deletedCount = 0;

                foreach ($seasonIds as $seasonId) {
                    $seasonCommodityUuid = md5($commodity->uuid . $seasonId);

                    $deleted = DB::connection('tenant')
                        ->table('commodities')
                        ->where('uuid', $seasonCommodityUuid)
                        ->delete();

                    $deletedCount += $deleted;

                    \Log::info("Deleted commodity from tenant season", [
                        'tenant' => $tenant->name,
                        'commodity_uuid' => $seasonCommodityUuid,
                        'season_id' => $seasonId,
                        'deleted_count' => $deleted
                    ]);
                }

                $results[$tenantId] = [
                    'success' => $deletedCount > 0,
                    'message' => $deletedCount > 0
                        ? "Commodity {$commodity->name} deleted from tenant {$tenant->name} ({$deletedCount} records)"
                        : "No commodity records found to delete from tenant {$tenant->name}",
                ];

                \Log::info("Commodity deletion result for tenant {$tenant->name}", $results[$tenantId]);

            } catch (\Exception $e) {
                \Log::error("Failed to sync commodity deletion {$commodity->id} to tenant {$tenantId}: " . $e->getMessage());
                $results[$tenantId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        \Log::info("Completed commodity deletion sync", [
            'global_commodity_id' => $commodity->id,
            'global_commodity_name' => $commodity->name,
            'tenants_processed' => count($results),
            'successful_deletions' => collect($results)->where('success', true)->count()
        ]);

        return $results;
    }
}
