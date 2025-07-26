<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Auth\TenantLoginController;

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

Route::middleware([
    'web',
    'auth',
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
])->group(function () {
    // Show login form
    Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.login');

    // Handle login POST
    Route::post('/login', [TenantLoginController::class, 'login'])->name('tenant.login.submit');
});
