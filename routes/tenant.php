<?php

use App\Models\Agent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BVNController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\MonetaryReturnController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\AgentCollectionController;
use App\Http\Controllers\Admin\AdminReportController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Http\Controllers\Tenant\Admin\CommodityController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Agent\AgentVerificationController;
use App\Http\Controllers\Auth\TenantForgotPasswordController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\Admin\Centers\CollectionCenters;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Agent\DashboardController as AgentDashboard;
use App\Http\Controllers\Tenant\Admin\RolePermissions\RoleController;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboard;
use App\Http\Controllers\Tenant\Admin\Centers\ReturningCenterController;
use App\Http\Controllers\Tenant\Admin\Centers\CollectionCenterController;
use App\Http\Controllers\Tenant\Admin\RolePermissions\PermissionController;
use App\Http\Controllers\Tenant\Admin\Applications\ApplicationApprovalController;

// Apply tenancy middleware
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant.user.active',
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
    Route::post('/logout', [App\Http\Controllers\Auth\TenantLoginController::class, 'logout'])->name('tenant.logout');
    Route::get('/forgot-password', [TenantForgotPasswordController::class, 'showLinkRequestForm'])->name('tenant.password.request');
    Route::post('/forgot-password', [TenantForgotPasswordController::class, 'sendResetLinkEmail'])->name('tenant.password.email');
    Route::get('/reset-password/{token}', [TenantForgotPasswordController::class, 'showResetForm'])->name('tenant.password.reset');
    Route::put('/reset-password', [TenantForgotPasswordController::class, 'reset'])->name('tenant.password.update');

    Route::middleware(['auth:tenant', 'tenant.user.active'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
    // Admin routes inside tenant
    Route::middleware(['auth:tenant', 'tenant.user.active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('settings', [\App\Http\Controllers\Tenant\Admin\SettingController::class, 'index'])->name('settings');
        Route::post('settings', [\App\Http\Controllers\Tenant\Admin\SettingController::class, 'store'])->name('settings.store');


        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('collection/centers', fn() => view('admin.centers'))->name('centers');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{uuid}/update', [UserController::class, 'update'])->name('update');
            Route::post('/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{uuid}', [UserController::class, 'destroy'])->name('destroy'); // USE UUID
            Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
        });

        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AgentController::class, 'index'])->name('index');
            Route::post('/store', [AgentController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [AgentController::class, 'edit'])->name('edit');
            Route::put('/{uuid}/update', [AgentController::class, 'update'])->name('update');
            Route::delete('/{uuid}/delete', [AgentController::class, 'destroy'])->name('destroy');
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
            Route::put('/{uuid}/reject', [ApplicationController::class, 'reject'])->name('reject');
            Route::post('/applications/bulk-approve', [ApplicationController::class, 'bulkApprove'])
                ->name('bulk-approve');
            Route::post('/applications/bulk-reject', [ApplicationController::class, 'bulkReject'])
                ->name('bulk-reject');
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
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::get('/create', [PermissionController::class, 'create'])->name('create');
            Route::post('/store', [PermissionController::class, 'store'])->name('store');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
            Route::put('/{permission}/update', [PermissionController::class, 'update'])->name('update');
            Route::delete('/{permission}/delete', [PermissionController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('applications', [AdminReportController::class, 'applications'])->name('applications');
            Route::get('export', [AdminReportController::class, 'export'])
                ->name('export');

        });

        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('index');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\LogController::class, 'export'])->name('export');
            Route::get('/api/statistics', [\App\Http\Controllers\Admin\LogController::class, 'statistics'])->name('statistics');
            Route::get('/{uuid}', [\App\Http\Controllers\Admin\LogController::class, 'show'])->name('show');
        });

        // Route for the main admin verification page
        Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');

        // API endpoint to get verification data
        Route::get('/api/verifications', [AdminVerificationController::class, 'getVerifications'])->name('api.verifications');

        // Route for bulk approval
        Route::post('/verifications/bulk-approve', [AdminVerificationController::class, 'bulkApprove'])->name('verifications.bulk-approve');

        // Route for single item verification
        Route::post('/verifications/verify', [AdminVerificationController::class, 'verifySingle'])->name('verifications.verify');

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
    Route::post('applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('applications/{uuid}/slip', [ApplicationController::class, 'acknowledgment'])->name('applications.slip');
    Route::get('/verify/{reference}', [ApplicationController::class, 'verify'])->name('applications.verify');
    Route::get('/applications/{uuid}/slip/pdf', [ApplicationController::class, 'downloadSlip'])
        ->name('applications.slip.pdf');
    Route::get('/verify/{reference}/pdf', [ApplicationController::class, 'downloadVerification'])
        ->name('applications.verify.pdf');
    Route::post('/verify-bvn', [BVNController::class, 'verifyBVN'])
        ->name('bvn.verify');


    Route::middleware(['auth:tenant', 'tenant.user.active', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
        Route::get('dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

        Route::get('verify-collection', [AgentVerificationController::class, 'assignedFarmers'])->name('verify.collection');
        Route::post('verify-collection', [AgentVerificationController::class, 'storeCollection'])->name('verify.collection.submit');

        Route::get('verify-return', [AgentVerificationController::class, 'assignedReturns'])->name('verify.return');
        Route::post('verify-return', [AgentVerificationController::class, 'storeReturn'])->name('verify.return.submit');

    });
    // Additional tenant-specific routes...
});
