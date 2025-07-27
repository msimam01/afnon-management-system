<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome'); // central landing page
})->name('central.landing');

// Auth routes for central (e.g. super admin)
require __DIR__ . '/auth.php';

// Super Admin routes
Route::middleware(['web', 'auth', 'role:super-admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
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
