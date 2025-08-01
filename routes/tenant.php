<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MonetaryReturnController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Agent\DashboardController as AgentDashboard;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboard;

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
        Route::get('applications', fn() => view('admin.applications'))->name('applications');
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

        Route::resource('seasons', SeasonController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('farmers', fn() => view('admin.farmers'))->name('farmers');
        Route::get('returns', fn() => view('admin.return'))->name('returns');
        Route::get('reports', fn() => view('admin.reports'))->name('reports');
        Route::get('receipts', [MonetaryReturnController::class, 'index'])->name('receipts');
        Route::get('receipts/{id}', [MonetaryReturnController::class, 'show'])->name('eceipts.show');
        Route::post('receipts/{id}/verify', [MonetaryReturnController::class, 'verify'])->name('receipts.verify');
        Route::post('receipts/{id}/reject', [MonetaryReturnController::class, 'reject'])->name('receipts.reject');
    });

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
