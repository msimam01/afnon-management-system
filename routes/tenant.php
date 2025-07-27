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
Route::middleware(['web', InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class, 'auth', 'role:admin', 'tenant'])
    ->prefix('admin')->name('admin.')->group(function () {
        // Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('seasons', fn() => view('admin.seasons'))->name('seasons');
        Route::get('collection/centers', fn() => view('admin.centers'))->name('centers');
        Route::get('applications', fn() => view('admin.applications'))->name('applications');
        Route::get('agents', fn() => view('admin.agents'))->name('agents');
        Route::get('commodities', fn() => view('admin.commodity'))->name('commodities');
        Route::get('farmers', fn() => view('admin.farmers'))->name('farmers');
        Route::get('returns', fn() => view('admin.return'))->name('returns');
        Route::get('reports', fn() => view('admin.reports'))->name('reports');
        Route::get('receipts', [MonetaryReturnController::class, 'index'])->name('receipts');
        Route::get('receipts/{id}', [MonetaryReturnController::class, 'show'])->name('eceipts.show');
        Route::post('receipts/{id}/verify', [MonetaryReturnController::class, 'verify'])->name('receipts.verify');
        Route::post('receipts/{id}/reject', [MonetaryReturnController::class, 'reject'])->name('receipts.reject');
    });
