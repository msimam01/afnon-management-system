<?php

use Illuminate\Support\Facades\Route;
use App\Models\Central\CentralCommodity;
use App\Http\Controllers\SyncLogController;
use App\Http\Controllers\CentralSeasonController;
use App\Http\Controllers\QuotaAllocationController;
use App\Http\Controllers\SuperAdmin\LogsController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\CentralCommodityController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');
    Route::get('settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])->name('settings');
    Route::post('settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'store'])->name('settings.store');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{uuid}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{uuid}/update', [UserController::class, 'update'])->name('update');
        Route::patch('/{uuid}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');

    });
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::delete('/{role}/delete', [RoleController::class, 'destroy'])->name('destroy');
        Route::put('/{role}/update', [RoleController::class, 'update'])->name('update');
        Route::post('/{role}/permissions', [RoleController::class, 'togglePermission'])
            ->name('toggle-permission'); // (group already has name('roles.'))


    });
    
});


// Route::get('/apply', fn() => view('apply'))->name('apply');
Route::get('/farmer/register', fn() => view('farmer.register'))->name('farmer-register');


require __DIR__ . '/auth.php';
