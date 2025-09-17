<?php

use Illuminate\Support\Facades\Route;
use App\Models\Central\CentralCommodity;
use App\Http\Controllers\SyncLogController;
use App\Http\Controllers\CentralSeasonController;
use App\Http\Controllers\QuotaAllocationController;
use App\Http\Controllers\SuperAdmin\LogsController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\CentralCommodityController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\ProfileController;
use App\Http\Controllers\SuperAdmin\PermissionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

Route::get('/', function () {
    return view('welcome'); // central landing page
})->name('central.landing');

// Auth routes for central (e.g. super admin)
require __DIR__ . '/auth.php';


// Super Admin routes with comprehensive permission checks
Route::middleware(['web', 'auth', 'central.user.active', 'central-activity-log', 'role:super-admin', 'block-tenant-access'])->prefix('super-admin')->name('superadmin.')->group(function () {

    // Dashboard - Most Important
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:view_superadmin_dashboard');

    // Tenant Management - High Priority
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])
            ->name('index')
            ->middleware('permission:manage_tenants');
        Route::get('/create', [TenantController::class, 'create'])
            ->name('create')
            ->middleware('permission:create_tenant');
        Route::post('/', [TenantController::class, 'store'])
            ->name('store')
            ->middleware('permission:create_tenant');
        Route::get('/{tenant}', [TenantController::class, 'show'])
            ->name('show')
            ->middleware('permission:read_tenant');
        Route::patch('/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])
            ->name('toggle-status')
            ->middleware('permission:change_tenant_status');
        Route::post('/{tenant}/suspend', [TenantController::class, 'suspend'])
            ->name('suspend')
            ->middleware('permission:suspend_tenant');
    });

    // User Management - High Priority
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('index')
            ->middleware('permission:manage_central_users');
        Route::post('/store', [UserController::class, 'store'])
            ->name('store')
            ->middleware('permission:create_central_user');
        Route::get('/{uuid}/edit', [UserController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:update_central_user');
        Route::put('/{uuid}/update', [UserController::class, 'update'])
            ->name('update')
            ->middleware('permission:update_central_user');
        Route::post('/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('toggle-status')
            ->middleware('permission:change_central_user_status');
        Route::delete('/{uuid}', [UserController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete_central_user');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])
            ->name('bulk-action')
            ->middleware('permission:manage_central_users');
    });

    // Role and Permission Management - Medium Priority
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('index')
            ->middleware('permission:manage_central_roles_permissions');
        Route::get('/create', [RoleController::class, 'create'])
            ->name('create')
            ->middleware('permission:create_central_role');
        Route::post('/store', [RoleController::class, 'store'])
            ->name('store')
            ->middleware('permission:create_central_role');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:update_central_role');
        Route::delete('/{role}/delete', [RoleController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete_central_role');
        Route::put('/{role}/update', [RoleController::class, 'update'])
            ->name('update')
            ->middleware('permission:update_central_role');
        Route::post('/{role}/permissions', [RoleController::class, 'togglePermission'])
            ->name('toggle-permission')
            ->middleware('permission:assign_central_permissions');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])
            ->name('index')
            ->middleware('permission:manage_central_roles_permissions');
        Route::get('/create', [PermissionController::class, 'create'])
            ->name('create')
            ->middleware('permission:create_central_permission');
        Route::post('/store', [PermissionController::class, 'store'])
            ->name('store')
            ->middleware('permission:create_central_permission');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:update_central_permission');
        Route::put('/{permission}/update', [PermissionController::class, 'update'])
            ->name('update')
            ->middleware('permission:update_central_permission');
        Route::delete('/{permission}/delete', [PermissionController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete_central_permission');
    });

    // Activity Logs - Medium Priority
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogsController::class, 'index'])
            ->name('index')
            ->middleware('permission:view_central_activity_logs');
        Route::get('/export/csv', [LogsController::class, 'export'])
            ->name('export')
            ->middleware('permission:export_central_activity_logs');
        Route::get('/api/statistics', [LogsController::class, 'statistics'])
            ->name('statistics')
            ->middleware('permission:view_central_system_statistics');
        Route::get('/{uuid}', [LogsController::class, 'show'])
            ->name('show')
            ->middleware('permission:view_central_activity_logs');
    });

    // System Settings - Low Priority
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])
            ->name('index')
            ->middleware('permission:manage_central_system_settings');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'store'])
            ->name('store')
            ->middleware('permission:update_system_configuration');
    });

    // Profile Management - Always accessible
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit')
            ->middleware('permission:view_own_profile');
        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update')
            ->middleware('permission:update_own_profile');
    });
});


// Route::get('/apply', fn() => view('apply'))->name('apply');
Route::get('/farmer/register', fn() => view('farmer.register'))->name('farmer-register');
