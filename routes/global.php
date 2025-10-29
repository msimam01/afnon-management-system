<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Global\GlobalSeasonController as GlobalSeasonController;
use App\Http\Controllers\Global\GlobalCommodityController;
use App\Http\Controllers\Global\GlobalCommodityCategoryController;
use App\Http\Controllers\Global\GlobalCommodityMarketPriceController;
use App\Http\Controllers\Global\GlobalTenantAllocationController;

/*
|--------------------------------------------------------------------------
| Global Admin API Routes
|--------------------------------------------------------------------------
|
| These routes are for managing global resources that are shared across
| all tenants. These routes are protected and should only be accessible
| by users with appropriate permissions in the central application.
|
*/

// API Routes
Route::middleware(['api', 'auth:api', 'central.user.active', 'role:super-admin'])
    ->prefix('api/global')
    ->name('api.global.')
    ->group(function () {
        // Global Seasons API
        Route::apiResource('seasons', GlobalSeasonController::class);

        // Reports API
        Route::post('reports/season-allocation', [App\Http\Controllers\ReportController::class, 'seasonAllocationReport'])->name('reports.season-allocation');
        Route::post('reports/tenant-distribution', [App\Http\Controllers\ReportController::class, 'tenantDistributionReport'])->name('reports.tenant-distribution');
        Route::post('reports/return-compliance', [App\Http\Controllers\ReportController::class, 'returnComplianceReport'])->name('reports.return-compliance');
    });

// Web Routes
Route::middleware(['web', 'auth', 'central.user.active', 'central-activity-log', 'role:super-admin'])
    ->prefix('global')
    ->name('global.')
    ->group(function () {
        // Global Seasons Web Routes
        Route::resource('seasons', GlobalSeasonController::class)->except(['show']);
        Route::get('seasons/{season}', [GlobalSeasonController::class, 'show'])->name('seasons.show');
        Route::post('seasons/{season}/close', [GlobalSeasonController::class, 'close'])->name('seasons.close');
        Route::post('seasons/{season}/reopen', [GlobalSeasonController::class, 'reopen'])->name('seasons.reopen');
        Route::post('seasons/{season}/add-commodity', [GlobalSeasonController::class, 'addCommodity'])->name('seasons.add-commodity');
        Route::put('seasons/{season}/commodities/{commodity}', [GlobalSeasonController::class, 'updateCommodity'])->name('seasons.update-commodity');
        Route::delete('seasons/{season}/commodities/{commodity}', [GlobalSeasonController::class, 'removeCommodity'])->name('seasons.remove-commodity');

        // Season Allocations - Custom routes first (before resource to avoid conflicts)
        Route::prefix('seasons/{seasonUuid}/allocations')->name('allocations.')->group(function () {
            Route::get('/', [GlobalTenantAllocationController::class, 'index'])->name('index');
            Route::get('/create', [GlobalTenantAllocationController::class, 'create'])->name('create');
            Route::get('/edit-all', [GlobalTenantAllocationController::class, 'editAll'])->name('edit-all');
            Route::post('/', [GlobalTenantAllocationController::class, 'store'])->name('store');
            Route::put('/update-all', [GlobalTenantAllocationController::class, 'updateAll'])->name('update-all');
            Route::get('/available-stock', [GlobalTenantAllocationController::class, 'getAvailableStock'])->name('available-stock');
            Route::get('/tenants/{tenantId}/edit', [GlobalTenantAllocationController::class, 'edit'])->name('edit');
            Route::put('/tenants/{tenantId}', [GlobalTenantAllocationController::class, 'update'])->name('update');
            Route::delete('/tenants/{tenantId}', [GlobalTenantAllocationController::class, 'destroy'])->name('destroy');
            Route::post('/tenants/{tenant}/sync', [GlobalTenantAllocationController::class, 'sync'])->name('sync');
            Route::post('/sync-all', [GlobalTenantAllocationController::class, 'syncAll'])->name('sync-all');
        });

        // Season Allocations - Resource routes
        Route::resource('seasons.allocations', GlobalTenantAllocationController::class)->except(['show']);
        Route::get('commodities', [GlobalCommodityController::class, 'index'])->name('commodities.index');
        Route::get('commodities/create', [GlobalCommodityController::class, 'create'])->name('commodities.create');
        Route::post('commodities', [GlobalCommodityController::class, 'store'])->name('commodities.store');
        Route::get('commodities/{uuid}/edit', [GlobalCommodityController::class, 'edit'])->name('commodities.edit');
        Route::put('commodities/{uuid}', [GlobalCommodityController::class, 'update'])->name('commodities.update');
        Route::delete('commodities/{uuid}', [GlobalCommodityController::class, 'destroy'])->name('commodities.destroy');

        // Global Commodity Categories
        Route::resource('commodity-categories', GlobalCommodityCategoryController::class)
            ->names('commodity-categories')
            ->parameters(['commodity-categories' => 'category']);

        // Global Commodity Market Prices
        Route::resource('commodity-market-prices', GlobalCommodityMarketPriceController::class)->names('commodity-market-prices');

        // Reports Web Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Global\ReportsController::class, 'index'])->name('index');
            Route::get('/season-allocation', [App\Http\Controllers\Global\ReportsController::class, 'seasonAllocation'])->name('season-allocation');
            Route::get('/tenant-distribution', [App\Http\Controllers\Global\ReportsController::class, 'tenantDistribution'])->name('tenant-distribution');
            Route::get('/return-compliance', [App\Http\Controllers\Global\ReportsController::class, 'returnCompliance'])->name('return-compliance');

            // Export routes
            Route::post('/season-allocation/export', [App\Http\Controllers\Global\ReportsController::class, 'exportSeasonAllocation'])->name('season-allocation.export');
            Route::post('/tenant-distribution/export', [App\Http\Controllers\Global\ReportsController::class, 'exportTenantDistribution'])->name('tenant-distribution.export');
            Route::post('/return-compliance/export', [App\Http\Controllers\Global\ReportsController::class, 'exportReturnCompliance'])->name('return-compliance.export');
            Route::post('/farmers-export', [App\Http\Controllers\Global\ReportsController::class, 'exportFarmers'])->name('farmers-export');
        });

        // Sync routes
        Route::prefix('sync')->group(function () {
            Route::get('data', function () {
                return response()->json(['message' => 'Sync data endpoint will be implemented soon']);
            })->name('sync.data');

            Route::post('report', function () {
                return response()->json(['message' => 'Report data endpoint will be implemented soon']);
            })->name('report.data');
        });
    });
