<?php

use App\Http\Controllers\Admin\CenterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MonetaryReturnController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Http\Controllers\Tenant\Admin\CommodityController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Agent\DashboardController as AgentDashboard;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboard;
use App\Http\Controllers\Tenant\Admin\Applications\ApplicationApprovalController;
use App\Http\Controllers\Tenant\Admin\Centers\CollectionCenterController;
use App\Http\Controllers\Tenant\Admin\Centers\CollectionCenters;
use App\Http\Controllers\Tenant\Admin\Centers\ReturningCenterController;

// Apply tenancy middleware
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Public tenant landing (can be same view)
    if (tenancy()->initialized) {
        Route::get('/', function () {
            return view('tenant_landing'); // tenant-specific welcome
        })->name('tenant.landing');
    }
    // Tenant login routes (you can also keep them in auth.php if reused)
    Route::get('/login', [App\Http\Controllers\Auth\TenantLoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Auth\TenantLoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\TenantLoginController::class, 'destroy'])->name('tenant.logout');
    // Admin routes inside tenant
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('collection/centers', fn() => view('admin.centers'))->name('centers');
        Route::get('agents', fn() => view('admin.agents'))->name('agents');
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
        });
        Route::prefix('commodities')->name('commodities.')->group(function () {
            Route::get('/', [CommodityController::class, 'index'])->name('index');
            Route::get('/create', [CommodityController::class, 'create'])->name('create');
            Route::post('/', [CommodityController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [CommodityController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [CommodityController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [CommodityController::class, 'destroy'])->name('destroy');

            // Import
            Route::get('/import/global', [CommodityController::class, 'importForm'])->name('importForm');
            Route::post('/import/{id}', [CommodityController::class, 'import'])->name('import');
            Route::post('/import-bulk', [CommodityController::class, 'importBulk'])->name('importBulk');
            Route::post('/{uuid}/sync', [CommodityController::class, 'sync'])->name('sync');
        });
        Route::prefix('centers')->name('centers.')->group(function () {
            Route::get('/', [CollectionCenters::class, 'index'])->name('index');
            Route::get('/create', [CollectionCenters::class, 'create'])->name('create');
            Route::post('/', [CollectionCenters::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [CollectionCenters::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [CollectionCenters::class, 'update'])->name('update');
            Route::delete('/{uuid}', [CollectionCenters::class, 'destroy'])->name('destroy');
        });
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])->name('index');
            Route::get('/{uuid}/show', [ApplicationController::class, 'show'])->name('show');
            Route::put('/{uuid}/approve', [ApplicationController::class, 'approve'])->name('approve');
            Route::post('/applications/bulk-approve', [ApplicationController::class, 'bulkApprove'])
                ->name('bulk-approve');
        });

        Route::resource('seasons', \App\Http\Controllers\Tenant\Admin\SeasonController::class);
        Route::get('seasons/{season}/export', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'export'])->name('seasons.export');
        Route::put('seasons/{season}/close', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'close'])->name('seasons.close');
        Route::put('seasons/{season}/reopen', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'reopen'])->name('seasons.reopen');
        Route::get('farmers', fn() => view('admin.farmers'))->name('farmers');
        Route::get('returns', fn() => view('admin.return'))->name('returns');
        Route::get('reports', fn() => view('admin.reports'))->name('reports');
        Route::get('receipts', [MonetaryReturnController::class, 'index'])->name('receipts');
        Route::get('receipts/{id}', [MonetaryReturnController::class, 'show'])->name('eceipts.show');
        Route::post('receipts/{id}/verify', [MonetaryReturnController::class, 'verify'])->name('receipts.verify');
        Route::post('receipts/{id}/reject', [MonetaryReturnController::class, 'reject'])->name('receipts.reject');
    });
    Route::get('applications', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('applications/{uuid}/slip', [ApplicationController::class, 'acknowledgment'])->name('applications.slip');
    Route::get('/verify/{reference}', [ApplicationController::class, 'verify'])->name('applications.verify');
    Route::get('/applications/{uuid}/slip/pdf', [ApplicationController::class, 'downloadSlip'])
        ->name('applications.slip.pdf');
    Route::get('/verify/{reference}/pdf', [ApplicationController::class, 'downloadVerification'])
        ->name('applications.verify.pdf');
    Route::post('/verify-bvn', [ApplicationController::class, 'verifyBVN'])->name('bvn.verify');



    // Farmer routes inside tenant
    Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/dashboard', [FarmerDashboard::class, 'index'])->name('dashboard');
        // Add more farmer routes here
    });

    // Agent routes inside tenant
    Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
        Route::get('/dashboard', [AgentDashboard::class, 'index'])->name('dashboard');
        // Add more agent routes here
    });

    // Additional tenant-specific routes...
});
