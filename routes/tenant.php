<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboard;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\MonetaryReturnController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// use App\Http\Controllers\Auth\TenantLoginController;

// Tenant login (no auth required)
Route::middleware(['web', InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class])
    ->group(function () {
        Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.login');
        Route::post('/login', [TenantLoginController::class, 'login'])->name('tenant.login.submit');
    });

// Protected tenant routes (must be logged in)
Route::middleware(['web', InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class, 'auth'])
    ->group(function () {
        // Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/seasons', fn() => view('admin.seasons'))->name('admin.seasons');
        Route::get('/admin/collection/centers', fn() => view('admin.centers'))->name('admin.centers');
        Route::get('/admin/applications', fn() => view('admin.applications'))->name('admin.applications');
        Route::get('/admin/agents', fn() => view('admin.agents'))->name('admin.agents');
        Route::get('/admin/commodities', fn() => view('admin.commodity'))->name('admin.commodities');
        Route::get('/admin/farmers', fn() => view('admin.farmers'))->name('admin.farmers');
        Route::get('/admin/returns', fn() => view('admin.return'))->name('admin.returns');
        Route::get('/admin/reports', fn() => view('admin.reports'))->name('admin.reports');
        Route::get('/admin/receipts', [MonetaryReturnController::class, 'index'])->name('admin.receipts');
        Route::get('/admin/receipts/{id}', [MonetaryReturnController::class, 'show'])->name('admin.eceipts.show');
        Route::post('/admin/receipts/{id}/verify', [MonetaryReturnController::class, 'verify'])->name('admin.receipts.verify');
        Route::post('/admin/receipts/{id}/reject', [MonetaryReturnController::class, 'reject'])->name('admin.receipts.reject');
    });
