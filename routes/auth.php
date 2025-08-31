<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomForgotPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware(['guest', 'block-tenant-access'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('central/login', [CustomLoginController::class, 'create'])->name('central.login.form');

    // Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/central/login', [CustomLoginController::class, 'store'])->middleware('guest')->name('central.login');
    // Central password reset routes
    Route::get('/central/forgot-password', [CustomForgotPasswordController::class, 'showLinkRequestForm'])->name('central.forgot.password');
    Route::post('/central/forgot-password', [CustomForgotPasswordController::class, 'sendResetLinkEmail'])->name('central.send.reset.link');
    Route::get('/central/reset-password/{token}', [CustomForgotPasswordController::class, 'showResetForm'])->name('central.show.reset.form');
    Route::put('/central/reset-password', [CustomForgotPasswordController::class, 'reset'])->name('central.reset.password');

    // Laravel Password facade compatibility routes for central domain
    Route::get('/password/reset/{token}', [CustomForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/email', [CustomForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

});

Route::middleware(['auth', 'central.user.active', 'block-tenant-access'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

});

// Central logout route (needs to be accessible for authenticated users)
Route::post('central/logout', [CustomLoginController::class, 'destroy'])
    ->middleware(['auth', 'central.user.active', 'block-tenant-access'])
    ->name('central.logout');
