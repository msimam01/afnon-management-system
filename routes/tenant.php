<?php

use App\Models\Agent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BVNController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FarmerPaymentController;
use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\AgentCollectionController;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\CommodityCategoryController;
use App\Http\Controllers\Agent\MonetaryReturnController;
use App\Http\Controllers\CommodityMarketPriceController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Http\Controllers\Tenant\Admin\CommodityController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Agent\AgentVerificationController;
use App\Http\Controllers\Auth\TenantForgotPasswordController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\Admin\Centers\CollectionCenters;
use App\Http\Controllers\Admin\MonetaryReturnVerificationController;
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
    'check-tenant-status',
    'tenant-activity-log',
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

        // Unified dashboard route - redirects based on user role
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    });
    // Admin routes inside tenant with comprehensive permission checks
    Route::middleware(['auth:tenant', 'tenant.user.active', 'tenant-activity-log', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard - Most Important
        Route::get('dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:view_admin_dashboard');

        // Applications - High Priority (Core Business Function)
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_applications');
            Route::get('/{uuid}', [ApplicationController::class, 'show'])
                ->name('show')
                ->middleware('permission:read_application');
            Route::put('/{uuid}/approve', [ApplicationController::class, 'approve'])
                ->name('approve')
                ->middleware('permission:approve_application');
            Route::put('/{uuid}/reject', [ApplicationController::class, 'reject'])
                ->name('reject')
                ->middleware('permission:reject_application');
            Route::post('/bulk-approve', [ApplicationController::class, 'bulkApprove'])
                ->name('bulk-approve')
                ->middleware('permission:bulk_approve_applications');
            Route::post('/bulk-reject', [ApplicationController::class, 'bulkReject'])
                ->name('bulk-reject')
                ->middleware('permission:bulk_reject_applications');
        });

        // Verifications - High Priority (Core Business Function)
        Route::prefix('verifications')->name('verifications.')->group(function () {
            Route::get('/', [AdminVerificationController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_verifications');
            Route::post('/bulk-approve', [AdminVerificationController::class, 'bulkApprove'])
                ->name('bulk-approve')
                ->middleware('permission:bulk_verify_items');
            Route::post('/verify', [AdminVerificationController::class, 'verifySingle'])
                ->name('verify')
                ->middleware('permission:update_verification');
        });

        // API routes for verifications (separate from main verifications routes)
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/verifications', [AdminVerificationController::class, 'getVerifications'])
                ->name('verifications')
                ->middleware('permission:read_verification');
        });

        // User Management - High Priority
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_users');
            Route::post('/store', [UserController::class, 'store'])
                ->name('store')
                ->middleware('permission:create_user');
            Route::get('/{uuid}/edit', [UserController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_user');
            Route::put('/{uuid}/update', [UserController::class, 'update'])
                ->name('update')
                ->middleware('permission:update_user');
            Route::post('/toggle-status', [UserController::class, 'toggleStatus'])
                ->name('toggle-status')
                ->middleware('permission:change_user_status');
            Route::delete('/{uuid}', [UserController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_user');
            Route::post('/bulk-action', [UserController::class, 'bulkAction'])
                ->name('bulk-action')
                ->middleware('permission:bulk_user_actions');
        });

        // Agent Management - High Priority
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AgentController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_agents');
            Route::post('/store', [AgentController::class, 'store'])
                ->name('store')
                ->middleware('permission:create_agent');
            Route::get('/{uuid}/edit', [AgentController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_agent');
            Route::put('/{uuid}/update', [AgentController::class, 'update'])
                ->name('update')
                ->middleware('permission:update_agent');
            Route::delete('/{uuid}/delete', [AgentController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_agent');
        });
        // Commodity Management - Medium Priority
        Route::prefix('commodities')->name('commodities.')->group(function () {
            Route::get('/', [CommodityController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_commodities');
            Route::get('/create', [CommodityController::class, 'create'])
                ->name('create')
                ->middleware('permission:create_commodity');
            Route::post('/', [CommodityController::class, 'store'])
                ->name('store')
                ->middleware('permission:create_commodity');
            Route::get('/{uuid}/edit', [CommodityController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_commodity');
            Route::put('/{uuid}', [CommodityController::class, 'update'])
                ->name('update')
                ->middleware('permission:update_commodity');
            Route::delete('/{uuid}', [CommodityController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_commodity');
            Route::post('/market-price', [CommodityMarketPriceController::class, 'store'])
                ->name('market-price')
                ->middleware('permission:manage_market_prices');
            Route::post('/category', [CommodityCategoryController::class, 'store'])
                ->name('category')
                ->middleware('permission:manage_commodity_categories');
        });

        // Center Management - Medium Priority
        Route::prefix('centers')->name('centers.')->group(function () {
            Route::get('/', [CollectionCenters::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_centers');
            Route::get('/create', [CollectionCenters::class, 'create'])
                ->name('create')
                ->middleware('permission:create_center');
            Route::post('/', [CollectionCenters::class, 'store'])
                ->name('store')
                ->middleware('permission:create_center');
            Route::get('/{uuid}/edit', [CollectionCenters::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_center');
            Route::put('/{uuid}', [CollectionCenters::class, 'update'])
                ->name('update')
                ->middleware('permission:update_center');
            Route::delete('/{uuid}', [CollectionCenters::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_center');
        });

        // Season Management - Medium Priority
        Route::prefix('seasons')->name('seasons.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_seasons');
            Route::get('/create', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'create'])
                ->name('create')
                ->middleware('permission:create_season');
            Route::post('/', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'store'])
                ->name('store')
                ->middleware('permission:create_season');
            Route::get('/{season}', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'show'])
                ->name('show')
                ->middleware('permission:read_season');
            Route::get('/{season}/edit', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_season');
            Route::put('/{season}', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'update'])
                ->name('update')
                ->middleware('permission:update_season');
            Route::delete('/{season}', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_season');
            Route::get('/{season}/export', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'export'])
                ->name('export')
                ->middleware('permission:export_season_data');
            Route::put('/{season}/close', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'close'])
                ->name('close')
                ->middleware('permission:close_season');
            Route::put('/{season}/reopen', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'reopen'])
                ->name('reopen')
                ->middleware('permission:reopen_season');
        });

        // Monetary Returns - Medium Priority
        Route::get('monetary-returns', [MonetaryReturnVerificationController::class, 'index'])
            ->name('monetary-returns')
            ->middleware('permission:manage_monetary_returns');

        // Reports - Medium Priority
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('applications', [AdminReportController::class, 'applications'])
                ->name('applications')
                ->middleware('permission:view_application_reports');
            Route::get('collections', [AdminReportController::class, 'collections'])
                ->name('collections')
                ->middleware('permission:view_verification_reports');
            Route::get('returns', [AdminReportController::class, 'returns'])
                ->name('returns')
                ->middleware('permission:view_verification_reports');
            Route::get('export', [AdminReportController::class, 'export'])
                ->name('export')
                ->middleware('permission:export_reports');
            Route::get('export-excel', [AdminReportController::class, 'exportExcel'])
                ->name('exportExcel')
                ->middleware('permission:export_reports');
            Route::get('export-collections', [AdminReportController::class, 'exportCollections'])
                ->name('exportCollections')
                ->middleware('permission:export_reports');
            Route::get('export-returns', [AdminReportController::class, 'exportReturns'])
                ->name('exportReturns')
                ->middleware('permission:export_reports');
        });

        // Role and Permission Management - Low Priority
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_roles_permissions');
            Route::get('/create', [RoleController::class, 'create'])
                ->name('create')
                ->middleware('permission:create_role');
            Route::post('/store', [RoleController::class, 'store'])
                ->name('store')
                ->middleware('permission:create_role');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_role');
            Route::delete('/{role}/delete', [RoleController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_role');
            Route::put('/{role}/update', [RoleController::class, 'update'])
                ->name('update')
                ->middleware('permission:update_role');
            Route::post('/{role}/permissions', [RoleController::class, 'togglePermission'])
                ->name('toggle-permission')
                ->middleware('permission:assign_permissions');
        });

        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])
                ->name('index')
                ->middleware('permission:manage_roles_permissions');
            Route::get('/create', [PermissionController::class, 'create'])
                ->name('create')
                ->middleware('permission:create_permission');
            Route::post('/store', [PermissionController::class, 'store'])
                ->name('store')
                ->middleware('permission:create_permission');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:update_permission');
            Route::put('/{permission}/update', [PermissionController::class, 'update'])
                ->name('update')
                ->middleware('permission:update_permission');
            Route::delete('/{permission}/delete', [PermissionController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:delete_permission');
        });

        // Activity Logs - Low Priority
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LogController::class, 'index'])
                ->name('index')
                ->middleware('permission:view_activity_logs');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\LogController::class, 'export'])
                ->name('export')
                ->middleware('permission:export_activity_logs');
            Route::get('/api/statistics', [\App\Http\Controllers\Admin\LogController::class, 'statistics'])
                ->name('statistics')
                ->middleware('permission:view_system_statistics');
            Route::get('/{uuid}', [\App\Http\Controllers\Admin\LogController::class, 'show'])
                ->name('show')
                ->middleware('permission:view_activity_logs');
        });

        // Settings - Low Priority
        Route::get('settings', [\App\Http\Controllers\Tenant\Admin\SettingController::class, 'index'])
            ->name('settings')
            ->middleware('permission:manage_settings');
        Route::post('settings', [\App\Http\Controllers\Tenant\Admin\SettingController::class, 'store'])
            ->name('settings.store')
            ->middleware('permission:update_system_settings');
    });


    Route::get('applications', [ApplicationController::class, 'create'])->name('applications.create')->middleware('app.rate.limit');
    Route::post('applications/store', [ApplicationController::class, 'store'])->name('applications.store')->middleware('app.rate.limit');
    Route::get('applications/{uuid}/slip', [ApplicationController::class, 'acknowledgment'])->name('applications.slip');
    Route::get('/verify/{reference}', [ApplicationController::class, 'verify'])->name('applications.verify');
    Route::get('/applications/{uuid}/slip/pdf', [ApplicationController::class, 'downloadSlip'])
        ->name('applications.slip.pdf');
    Route::get('/verify/{reference}/pdf', [ApplicationController::class, 'downloadVerification'])
        ->name('applications.verify.pdf');
    Route::post('/verify-bvn', [BVNController::class, 'verifyBVN'])
        ->name('bvn.verify');

    // Farmer Payment Portal Routes (Public - no authentication required)
    Route::prefix('farmer/payment')->name('farmer.payment.')->group(function () {
        Route::get('/', [\App\Http\Controllers\FarmerPaymentController::class, 'index'])->name('index');
        Route::get('/lookup', [\App\Http\Controllers\FarmerPaymentController::class, 'index'])->name('lookup');
        Route::post('/lookup', [\App\Http\Controllers\FarmerPaymentController::class, 'lookup'])->name('lookup.post');
        Route::post('/initiate', [\App\Http\Controllers\FarmerPaymentController::class, 'initiatePayment'])->name('initiate');
        Route::get('/callback', [\App\Http\Controllers\FarmerPaymentController::class, 'paymentCallback'])->name('callback');
        Route::get('/receipt/{txRef}', [\App\Http\Controllers\FarmerPaymentController::class, 'receipt'])->name('receipt');
    });

    // Agent routes with comprehensive permission checks
    Route::middleware(['auth:tenant', 'tenant.user.active', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {

        // Dashboard - Most Important
        Route::get('dashboard', [AgentDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:view_agent_dashboard');

        // Verify Collection - High Priority (Primary Function)
        Route::get('verify-collection', [AgentVerificationController::class, 'assignedFarmers'])
            ->name('verify.collection')
            ->middleware('permission:verify_collection');
        Route::post('verify-collection', [AgentVerificationController::class, 'storeCollection'])
            ->name('verify.collection.submit')
            ->middleware('permission:verify_collection');

        // Verify Return - High Priority (Primary Function)
        Route::get('verify-return', [AgentVerificationController::class, 'assignedReturns'])
            ->name('verify.return')
            ->middleware('permission:verify_return');
        Route::post('verify-return', [AgentVerificationController::class, 'storeReturn'])
            ->name('verify.return.submit')
            ->middleware('permission:verify_return');

        // Monetary Return - Medium Priority
        Route::get('monetary-return', [MonetaryReturnController::class, 'index'])
            ->name('monetary-return')
            ->middleware('permission:manage_monetary_return');

        // Payment Processing
        Route::post('generate-payment/{application}', [MonetaryReturnController::class, 'generatePayment'])
            ->name('generatePayment')
            ->middleware('permission:create_monetary_return');

        Route::get('/payment/callback', [MonetaryReturnController::class, 'paymentCallback'])
            ->name('payment.callback')
            ->middleware('permission:manage_monetary_return');
    });
    // Additional tenant-specific routes...
});
