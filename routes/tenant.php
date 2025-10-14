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
use App\Http\Controllers\Auth\TenantForcePasswordChangeController;
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

    // Public enquiry routes for tenants
    Route::post('/enquiries', [\App\Http\Controllers\EnquiryController::class, 'store'])->name('tenant.enquiries.store');

    // Tenant login routes (you can also keep them in auth.php if reused)
    Route::get('/login', [App\Http\Controllers\Auth\TenantLoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Auth\TenantLoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\TenantLoginController::class, 'logout'])->name('tenant.logout');
    Route::get('/forgot-password', [TenantForgotPasswordController::class, 'showLinkRequestForm'])->name('tenant.password.request');
    Route::post('/forgot-password', [TenantForgotPasswordController::class, 'sendResetLinkEmail'])->name('tenant.password.email');
    Route::get('/reset-password/{token}', [TenantForgotPasswordController::class, 'showResetForm'])->name('tenant.password.reset');
    Route::put('/reset-password', [TenantForgotPasswordController::class, 'reset'])->name('tenant.password.update');

    // Force password change routes for tenants (outside tenant.user.active middleware to allow access)
    Route::middleware(['auth:tenant'])->group(function () {
        Route::get('/force-password-change', [TenantForcePasswordChangeController::class, 'show'])->name('tenant.password.force.change');
        Route::post('/force-password-change', [TenantForcePasswordChangeController::class, 'update'])->name('tenant.password.force.change.update');
        Route::put('/force-password-change', [TenantForcePasswordChangeController::class, 'update'])->name('tenant.password.force.change.update.put');
    });

    Route::middleware(['auth:tenant', 'tenant.user.active'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Unified dashboard route - redirects based on user role
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    });
    // Admin routes inside tenant with role check only
    Route::middleware(['auth:tenant', 'tenant.user.active', 'tenant-activity-log', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard - Most Important
        Route::get('dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        // Applications - High Priority (Core Business Function)
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])
                ->name('index');
            Route::get('/{uuid}', [ApplicationController::class, 'show'])
                ->name('show');
            Route::put('/{uuid}/approve', [ApplicationController::class, 'approve'])
                ->name('approve');
            Route::put('/{uuid}/reject', [ApplicationController::class, 'reject'])
                ->name('reject');
            Route::post('/bulk-approve', [ApplicationController::class, 'bulkApprove'])
                ->name('bulk-approve');
            Route::post('/bulk-reject', [ApplicationController::class, 'bulkReject'])
                ->name('bulk-reject');
        });

        // Verifications - High Priority (Core Business Function)
        Route::prefix('verifications')->name('verifications.')->group(function () {
            Route::get('/', [AdminVerificationController::class, 'index'])
                ->name('index');
            Route::post('/bulk-approve', [AdminVerificationController::class, 'bulkApprove'])
                ->name('bulk-approve');
            Route::post('/verify', [AdminVerificationController::class, 'verifySingle'])
                ->name('verify');
        });

        // API routes for verifications (separate from main verifications routes)
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/verifications', [AdminVerificationController::class, 'getVerifications'])
                ->name('verifications');
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

        // Agent Management - High Priority
        Route::prefix('agents')->name('agents.')->group(function () {
            Route::get('/', [AgentController::class, 'index'])
                ->name('index');
            Route::post('/store', [AgentController::class, 'store'])
                ->name('store');
            Route::get('/{uuid}/edit', [AgentController::class, 'edit'])
                ->name('edit');
            Route::put('/{uuid}/update', [AgentController::class, 'update'])
                ->name('update');
            Route::delete('/{uuid}/delete', [AgentController::class, 'destroy'])
                ->name('destroy');
        });
        // Commodity Management - Medium Priority
        Route::prefix('commodities')->name('commodities.')->group(function () {
            Route::get('/', [CommodityController::class, 'index'])
                ->name('index');
            Route::get('/create', [CommodityController::class, 'create'])
                ->name('create');
            Route::post('/', [CommodityController::class, 'store'])
                ->name('store');
            Route::get('/{uuid}/edit', [CommodityController::class, 'edit'])
                ->name('edit');
            Route::put('/{uuid}', [CommodityController::class, 'update'])
                ->name('update');
            Route::delete('/{uuid}', [CommodityController::class, 'destroy'])
                ->name('destroy');
            Route::post('/market-price', [CommodityMarketPriceController::class, 'store'])
                ->name('market-price');
            Route::post('/category', [CommodityCategoryController::class, 'store'])
                ->name('category');
        });

        // Center Management - Medium Priority
        Route::prefix('centers')->name('centers.')->group(function () {
            Route::get('/', [CollectionCenters::class, 'index'])
                ->name('index');
            Route::get('/create', [CollectionCenters::class, 'create'])
                ->name('create');
            Route::post('/', [CollectionCenters::class, 'store'])
                ->name('store');
            Route::get('/{uuid}/edit', [CollectionCenters::class, 'edit'])
                ->name('edit');
            Route::put('/{uuid}', [CollectionCenters::class, 'update'])
                ->name('update');
            Route::delete('/{uuid}', [CollectionCenters::class, 'destroy'])
                ->name('destroy');
        });

        // Season Management - Medium Priority
        Route::prefix('seasons')->name('seasons.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'index'])
                ->name('index');
            Route::get('/create', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'create'])
                ->name('create');
            Route::post('/', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'store'])
                ->name('store');
            Route::get('/{season}', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'show'])
                ->name('show');
            Route::get('/{season}/edit', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'edit'])
                ->name('edit');
            Route::put('/{season}', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'update'])
                ->name('update');
            Route::delete('/{season}', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'destroy'])
                ->name('destroy');
            Route::get('/{season}/export', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'export'])
                ->name('export');
            Route::put('/{season}/close', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'close'])
                ->name('close');
            Route::put('/{season}/reopen', [\App\Http\Controllers\Tenant\Admin\SeasonController::class, 'reopen'])
                ->name('reopen');
        });

        // Monetary Returns - Medium Priority
        Route::get('transactions', [MonetaryReturnVerificationController::class, 'index'])
            ->name('monetary-returns');
        Route::get('transactions/{uuid}', [MonetaryReturnVerificationController::class, 'show'])
            ->name('monetary-returns.show');
        Route::get('transactions/{uuid}/report', [MonetaryReturnVerificationController::class, 'generateReport'])
            ->name('monetary-returns.report');
        Route::get('transactions/{uuid}/export', [MonetaryReturnVerificationController::class, 'export'])
            ->name('monetary-returns.export');

        // Reports - Medium Priority
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('applications', [AdminReportController::class, 'applications'])
                ->name('applications');
            Route::get('collections', [AdminReportController::class, 'collections'])
                ->name('collections');
            Route::get('returns', [AdminReportController::class, 'returns'])
                ->name('returns');
            Route::get('transactions', [MonetaryReturnVerificationController::class, 'reports'])
                ->name('monetary-returns');
            Route::get('transactions/export-all', [MonetaryReturnVerificationController::class, 'exportAll'])
                ->name('monetary-returns.export.all');
            Route::get('transactions/pdf', [MonetaryReturnVerificationController::class, 'exportPdf'])
                ->name('monetary-returns.pdf');
            Route::get('export', [AdminReportController::class, 'export'])
                ->name('export');
            Route::get('export-excel', [AdminReportController::class, 'exportExcel'])
                ->name('exportExcel');
            Route::get('export-collections', [AdminReportController::class, 'exportCollections'])
                ->name('exportCollections');
            Route::get('export-returns', [AdminReportController::class, 'exportReturns'])
                ->name('exportReturns');

            // Season Reports
            Route::prefix('seasons')->name('seasons.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\SeasonReportController::class, 'index'])
                    ->name('index');
                Route::get('{season}', [\App\Http\Controllers\Admin\SeasonReportController::class, 'show'])
                    ->name('show');
                Route::get('{season}/pdf', [\App\Http\Controllers\Admin\SeasonReportController::class, 'exportPdf'])
                    ->name('pdf');
                Route::get('{season}/excel', [\App\Http\Controllers\Admin\SeasonReportController::class, 'exportExcel'])
                    ->name('excel');
            });
        });

        // Role and Permission Management - Low Priority
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

        // Activity Logs - Low Priority
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LogController::class, 'index'])
                ->name('index');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\LogController::class, 'export'])
                ->name('export');
            Route::get('/api/statistics', [\App\Http\Controllers\Admin\LogController::class, 'statistics'])
                ->name('statistics');
            Route::get('/{uuid}', [\App\Http\Controllers\Admin\LogController::class, 'show'])
                ->name('show');
        });

        // Settings - Low Priority
        Route::get('settings', [\App\Http\Controllers\Tenant\Admin\SettingController::class, 'index'])
            ->name('settings');
        Route::post('settings', [\App\Http\Controllers\Tenant\Admin\SettingController::class, 'store'])
            ->name('settings.store');

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
    });


    Route::get('apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('applications/store', [ApplicationController::class, 'store'])->name('applications.store');
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
            ->name('dashboard');

        // Verify Collection - High Priority (Primary Function)
        Route::get('verify-collection', [AgentVerificationController::class, 'assignedFarmers'])
            ->name('verify.collection');
        Route::post('verify-collection', [AgentVerificationController::class, 'storeCollection'])
            ->name('verify.collection.submit');

        // Verify Return - High Priority (Primary Function)
        Route::get('verify-return', [AgentVerificationController::class, 'assignedReturns'])
            ->name('verify.return');
        Route::post('verify-return', [AgentVerificationController::class, 'storeReturn'])
            ->name('verify.return.submit');

        // Transactions - Medium Priority
        Route::get('transactions', [MonetaryReturnController::class, 'index'])
            ->name('monetary-return');
        Route::get('transactions/{uuid}', [MonetaryReturnController::class, 'show'])
            ->name('monetary-returns.show');
        Route::get('transactions/{uuid}/receipt', [MonetaryReturnController::class, 'receipt'])
            ->name('monetary-returns.receipt');

        // Payment Processing
        Route::post('generate-payment/{application}', [MonetaryReturnController::class, 'generatePayment'])
            ->name('generatePayment');

        Route::get('/payment/callback', [MonetaryReturnController::class, 'paymentCallback'])
            ->name('payment.callback');
    });
    // Additional tenant-specific routes...
});
