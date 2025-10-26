<?php

namespace App\Observers;

use App\Models\GlobalCommodityMarketPrice;
use App\Services\TenantSyncService;
use Illuminate\Support\Facades\Log;

class GlobalCommodityMarketPriceObserver
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Handle the GlobalCommodityMarketPrice "created" event.
     */
    public function created(GlobalCommodityMarketPrice $marketPrice)
    {
        Log::info("Global market price created: {$marketPrice->id}");
        $this->syncToTenants($marketPrice, 'created');
    }

    /**
     * Handle the GlobalCommodityMarketPrice "updated" event.
     */
    public function updated(GlobalCommodityMarketPrice $marketPrice)
    {
        Log::info("Global market price updated: {$marketPrice->id}");
        $this->syncToTenants($marketPrice, 'updated');
    }

    /**
     * Handle the GlobalCommodityMarketPrice "deleted" event.
     */
    public function deleted(GlobalCommodityMarketPrice $marketPrice)
    {
        Log::info("Global market price deleted: {$marketPrice->id}");
        $this->syncDeletionToTenants($marketPrice);
    }

    /**
     * Sync market price to all relevant tenants.
     */
    private function syncToTenants(GlobalCommodityMarketPrice $marketPrice, string $event)
    {
        // Check if auto-sync is enabled (you can add this to config)
        if (config('app.auto_sync_market_prices', true)) {
            Log::info("Auto-syncing market price {$event}: {$marketPrice->commodity->name} - {$marketPrice->season->name}");

            try {
                $results = $this->syncService->syncMarketPriceToTenants($marketPrice);

                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("Market price synced to {$successCount} of {$totalCount} tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync market price {$marketPrice->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Sync market price deletion to all relevant tenants.
     */
    private function syncDeletionToTenants(GlobalCommodityMarketPrice $marketPrice)
    {
        // Check if auto-sync is enabled (you can add this to config)
        if (config('app.auto_sync_market_prices', true)) {
            Log::info("Auto-syncing market price deletion: {$marketPrice->commodity->name} - {$marketPrice->season->name}");

            try {
                $results = $this->syncService->syncMarketPriceDeletionToTenants($marketPrice);

                $successCount = collect($results)->where('success', true)->count();
                $totalCount = count($results);

                Log::info("Market price deletion synced to {$successCount} of {$totalCount} tenants");

            } catch (\Exception $e) {
                Log::error("Failed to auto-sync market price deletion {$marketPrice->id}: " . $e->getMessage());
            }
        }
    }
}
