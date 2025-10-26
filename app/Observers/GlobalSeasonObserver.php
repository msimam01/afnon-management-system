<?php

namespace App\Observers;

use App\Models\GlobalSeason;
use App\Services\TenantSyncService;
use Illuminate\Support\Facades\Log;

class GlobalSeasonObserver
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
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
