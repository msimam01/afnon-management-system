<?php

use App\Models\Models\Agent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboard;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SUPER ADMIN
Route::middleware(['auth', 'role:super-admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/users', fn() => view('super-admin.users'))->name('users');
    Route::get('/commodities', fn() => view('super-admin.commodities'))->name('commodities');
    Route::get('/tenants', fn() => view('super-admin.tenants'))->name('tenants');
    Route::get('/seasons', fn() => view('super-admin.seasons'))->name('seasons');
    Route::get('/settings', fn() => view('super-admin.settings'))->name('settings');
    Route::get('/roles', fn() => view('super-admin.roles'))->name('roles');
    Route::get('/activity-logs', fn() => view('super-admin.audit-logs'))->name('activity-logs');
});


// ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/seasons', fn() => view('admin.seasons'))->name('seasons');
    Route::get('/collection/centers', fn() => view('admin.centers'))->name('centers');
    Route::get('/applications', fn() => view('admin.applications'))->name('applications');
    Route::get('/agents', fn() => view('admin.agents'))->name('agents');
    Route::get('/commodities', fn() => view('admin.commodity'))->name('commodities');
    Route::get('/farmers', fn() => view('admin.farmers'))->name('farmers');
    Route::get('/returns', fn() => view('admin.return'))->name('returns');
    Route::get('/reports', fn() => view('admin.reports'))->name('reports');
});

// FARMER
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerDashboard::class, 'index'])->name('dashboard');
});

Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', fn() => view('agent.search'))->name('search');
    Route::get('/collection/verify', fn() => view('agent.verify-collection'))->name('verify-collection');
    Route::get('/return/verify', fn() => view('agent.verify-return'))->name('verify-return');
});

Route::get('/apply', fn() => view('apply'))->name('apply');
Route::get('/farmer/register', fn() => view('farmer.register'))->name('farmer-register');


require __DIR__ . '/auth.php';
