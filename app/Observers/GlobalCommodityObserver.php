<?php

namespace App\Observers;

use App\Models\GlobalCommodity;
use App\Services\TenantSyncService;
use App\Jobs\SyncCommodityUpdateJob;
use App\Jobs\SyncCommodityDeletionJob;
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
                // Dispatch job for async processing
                SyncCommodityUpdateJob::dispatch($commodity);
                Log::info("Dispatched commodity update sync job for {$commodity->name}");

            } catch (\Exception $e) {
                Log::error("Failed to dispatch auto-sync job for commodity {$commodity->name}: " . $e->getMessage());
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
                // Dispatch job for async processing
                SyncCommodityDeletionJob::dispatch($commodity);
                Log::info("Dispatched commodity deletion sync job for {$commodity->name}");

            } catch (\Exception $e) {
                Log::error("Failed to dispatch auto-sync deletion job for commodity {$commodity->name}: " . $e->getMessage());
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
