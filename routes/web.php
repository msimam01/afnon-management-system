<?php

use Illuminate\Support\Facades\Route;
use App\Models\Central\CentralCommodity;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CentralSeasonController;
use App\Http\Controllers\QuotaAllocationController;
use App\Http\Controllers\CentralCommodityController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

Route::get('/', function () {
    return view('welcome'); // central landing page
})->name('central.landing');

// Auth routes for central (e.g. super admin)
require __DIR__ . '/auth.php';

// Super Admin routes
Route::middleware(['web', 'auth', 'role:super-admin', 'block-tenant-access'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    // Route::resource('commodities', CentralCommodityController::class);
    Route::get('/commodities', [CentralCommodityController::class, 'index'])->name('commodities.index');
    Route::get('/commodities/create', [CentralCommodityController::class, 'create'])->name('commodities.create');
    Route::post('/commodities/store', [CentralCommodityController::class, 'store'])->name('commodities.store');
    Route::get('/commodities/{uuid}/edit', [CentralCommodityController::class, 'edit'])->name('commodities.edit');
    Route::put('/commodities/{uuid}/update', [CentralCommodityController::class, 'update'])->name('commodities.update');
    Route::delete('/commodities/{uuid}/destroy', [CentralCommodityController::class, 'destroy'])->name('commodities.destroy');
    Route::get('seasons', [CentralSeasonController::class, 'index'])->name('seasons.index');
    Route::get('seasons/create', [CentralSeasonController::class, 'create'])->name('seasons.create');
    Route::post('seasons', [CentralSeasonController::class, 'store'])->name('seasons.store');
    Route::post('seasons/{season}/sync', [CentralSeasonController::class, 'syncToTenants'])->name('seasons.sync');
    Route::get('seasons/{season}/quotas', [QuotaAllocationController::class, 'create'])->name('seasons.quotas.create');
    Route::post('seasons/{season}/close', [CentralSeasonController::class, 'close'])->name('seasons.close');
    Route::post('seasons/{season}/reopen', [CentralSeasonController::class, 'reopen'])->name('seasons.reopen');

    Route::post('seasons/{season}/quotas', [QuotaAllocationController::class, 'store'])->name('seasons.quotas.store');
});


// Route::prefix('agent')->name('agent.')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::get('/search', fn() => view('agent.search'))->name('search');
//     Route::get('/collection/verify', fn() => view('agent.verify-collection'))->name('verify-collection');
//     Route::get('/return/verify', fn() => view('agent.verify-return'))->name('verify-return');
// });

Route::get('/apply', fn() => view('apply'))->name('apply');
Route::get('/farmer/register', fn() => view('farmer.register'))->name('farmer-register');


require __DIR__ . '/auth.php';
