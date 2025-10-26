<?php

namespace App\Observers;

use App\Models\GlobalCommodity;
use App\Services\TenantSyncService;
use Illuminate\Support\Facades\Log;

class GlobalCommodityObserver
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Handle the GlobalCommodity "updated" event.
     */
    public function updated(GlobalCommodity $commodity)
    {
        Log::info("Global commodity updated: {$commodity->id} - {$commodity->name}");

        // Check if auto-sync is enabled (enabled by default like market prices)
        if (config('app.auto_sync_commodities', true)) {
            try {
                $results = $this->syncService->syncCommodityUpdate($commodity);
                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("Commodity {$commodity->name} synced to {$successCount} of {$totalCount} tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync commodity {$commodity->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the GlobalCommodity "deleted" event.
     */
    public function deleted(GlobalCommodity $commodity)
    {
        Log::info("Global commodity deleted: {$commodity->id} - {$commodity->name}");

        // Check if auto-sync is enabled (enabled by default like market prices)
        if (config('app.auto_sync_commodities', true)) {
            try {
                $results = $this->syncService->syncCommodityDeletionToTenants($commodity);
                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("Commodity {$commodity->name} deletion synced to {$successCount} of {$totalCount} tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync commodity deletion {$commodity->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the GlobalCommodity "deleting" event.
     */
    public function deleting(GlobalCommodity $commodity)
    {
        // Allow deletion but log it
        Log::info("Processing deletion of commodity: {$commodity->name} (associated with {$commodity->seasons()->count()} seasons)");
    }
}
