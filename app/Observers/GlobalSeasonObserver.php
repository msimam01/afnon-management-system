<?php

namespace App\Observers;

use App\Models\GlobalSeason;
use App\Services\TenantSyncService;
use App\Jobs\SyncSeasonUpdateJob;
use App\Jobs\SyncSeasonDeletionJob;
use App\Models\SuperAdmin\Tenant;
use Illuminate\Support\Facades\Log;

class GlobalSeasonObserver
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Handle the GlobalSeason "created" event.
     */
    public function created(GlobalSeason $season)
    {
        Log::info("Global season created: {$season->id} - {$season->name}");

        // Check if auto-sync is enabled (enabled by default like other syncs)
        if (config('app.auto_sync_seasons', true)) {
            try {
                // For new seasons, sync to all active tenants so they're available
                // This ensures seasons are ready when allocations are made later
                $tenants = Tenant::active()->get();

                $results = [];
                foreach ($tenants as $tenant) {
                    try {
                        $result = $this->syncService->syncSeasonToTenant($season, $tenant->id);
                        $results[$tenant->id] = $result;
                    } catch (\Exception $e) {
                        Log::error("Failed to sync new season {$season->name} to tenant {$tenant->name}: " . $e->getMessage());
                        $results[$tenant->id] = [
                            'success' => false,
                            'message' => $e->getMessage(),
                        ];
                    }
                }

                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("New season {$season->name} synced to {$successCount} of {$totalCount} active tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync new season {$season->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the GlobalSeason "updated" event.
     */
    public function updated(GlobalSeason $season)
    {
        Log::info("Global season updated: {$season->id} - {$season->name}");

        // Check if auto-sync is enabled (enabled by default like other syncs)
        if (config('app.auto_sync_seasons', true)) {
            try {
                $results = $this->syncService->syncSeasonUpdate($season);
                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("Season {$season->name} synced to {$successCount} of {$totalCount} tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync season {$season->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the GlobalSeason "deleted" event.
     */
    public function deleted(GlobalSeason $season)
    {
        Log::info("Global season deleted: {$season->id} - {$season->name}");

        // Check if auto-sync is enabled (enabled by default like other syncs)
        if (config('app.auto_sync_seasons', true)) {
            try {
                $results = $this->syncService->syncSeasonDeletionToTenants($season);
                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("Season {$season->name} deletion synced to {$successCount} of {$totalCount} tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync season deletion {$season->name}: " . $e->getMessage());
            }
        }
    }
}
