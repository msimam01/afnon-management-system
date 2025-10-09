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
use App\Http\Controllers\EnquiryController;

Route::get('/', function () {
    return view('welcome'); // central landing page
})->name('central.landing');

// Public enquiry routes (Central)
Route::post('/enquiries', [EnquiryController::class, 'store'])->name('central.enquiries.store');

// Auth routes for central (e.g. super admin)
require __DIR__ . '/auth.php';

// Additional enquiry route (backup)
Route::post('/contact', [EnquiryController::class, 'store'])->name('contact.store');


// Super Admin routes with role check only
Route::middleware(['web', 'auth', 'central.user.active', 'central-activity-log', 'role:super-admin', 'block-tenant-access'])->prefix('super-admin')->name('superadmin.')->group(function () {

    // Dashboard - Most Important
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])
        ->name('dashboard');

    // Tenant Management - High Priority
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])
            ->name('index');
        Route::get('/create', [TenantController::class, 'create'])
            ->name('create');
        Route::post('/', [TenantController::class, 'store'])
            ->name('store');
        Route::get('/{tenant}', [TenantController::class, 'show'])
            ->name('show');
        Route::patch('/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])
            ->name('toggle-status');
        Route::post('/{tenant}/suspend', [TenantController::class, 'suspend'])
            ->name('suspend');
    });

    // User Management - High Priority
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('index');
        Route::post('/store', [UserController::class, 'store'])
            ->name('store');
        Route::get('/{uuid}/edit', [UserController::class, 'edit'])
            ->name('edit');
        Route::put('/{uuid}/update', [UserController::class, 'update'])
            ->name('update');
        Route::post('/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('toggle-status');
        Route::delete('/{uuid}', [UserController::class, 'destroy'])
            ->name('destroy');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])
            ->name('bulk-action');
    });

    // Role and Permission Management - Medium Priority
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('index');
        Route::get('/create', [RoleController::class, 'create'])
            ->name('create');
        Route::post('/store', [RoleController::class, 'store'])
            ->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])
            ->name('edit');
        Route::delete('/{role}/delete', [RoleController::class, 'destroy'])
            ->name('destroy');
        Route::put('/{role}/update', [RoleController::class, 'update'])
            ->name('update');
        Route::post('/{role}/permissions', [RoleController::class, 'togglePermission'])
            ->name('toggle-permission');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])
            ->name('index');
        Route::get('/create', [PermissionController::class, 'create'])
            ->name('create');
        Route::post('/store', [PermissionController::class, 'store'])
            ->name('store');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
            ->name('edit');
        Route::put('/{permission}/update', [PermissionController::class, 'update'])
            ->name('update');
        Route::delete('/{permission}/delete', [PermissionController::class, 'destroy'])
            ->name('destroy');
    });

    // Activity Logs - Medium Priority
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogsController::class, 'index'])
            ->name('index');
        Route::get('/export/csv', [LogsController::class, 'export'])
            ->name('export');
        Route::get('/api/statistics', [LogsController::class, 'statistics'])
            ->name('statistics');
        Route::get('/{uuid}', [LogsController::class, 'show'])
            ->name('show');
    });

    // System Settings - Low Priority
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])
            ->name('index');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'store'])
            ->name('store');
    });

    // Enquiries Management - Medium Priority
    Route::prefix('enquiries')->name('enquiries.')->group(function () {
        Route::get('/', [\App\Http\Controllers\EnquiryController::class, 'index'])
            ->name('index');
        Route::get('/{enquiry}', [\App\Http\Controllers\EnquiryController::class, 'show'])
            ->name('show');
        Route::post('/{enquiry}/mark-spam', [\App\Http\Controllers\EnquiryController::class, 'markAsSpam'])
            ->name('mark-spam');
        Route::post('/{enquiry}/mark-not-spam', [\App\Http\Controllers\EnquiryController::class, 'markAsNotSpam'])
            ->name('mark-not-spam');
        Route::delete('/{enquiry}', [\App\Http\Controllers\EnquiryController::class, 'destroy'])
            ->name('destroy');
    });

    // Profile Management - Always accessible
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    });
});


// Route::get('/apply', fn() => view('apply'))->name('apply');
Route::get('/farmer/register', fn() => view('farmer.register'))->name('farmer-register');
