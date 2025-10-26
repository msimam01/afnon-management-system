<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TenantSyncService;
use App\Models\GlobalCommodity;
use App\Models\GlobalCommodityMarketPrice;
use App\Models\GlobalSeason;
use App\Observers\GlobalCommodityObserver;
use App\Observers\GlobalCommodityMarketPriceObserver;
use App\Observers\GlobalSeasonObserver;
use App\Observers\GlobalTenantAllocationObserver;
use App\Models\GlobalTenantAllocation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantSyncService::class, function ($app) {
            return new TenantSyncService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the GlobalCommodity observer
        GlobalCommodity::observe(GlobalCommodityObserver::class);

        // Register the GlobalCommodityMarketPrice observer
        GlobalCommodityMarketPrice::observe(GlobalCommodityMarketPriceObserver::class);

        // Register the GlobalSeason observer
        GlobalSeason::observe(GlobalSeasonObserver::class);

        // Register the GlobalTenantAllocation observer
        GlobalTenantAllocation::observe(GlobalTenantAllocationObserver::class);
    }
}
