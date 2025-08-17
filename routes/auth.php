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

    Route::get('central/login', [CustomLoginController::class, 'create']);

    // Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/central/login', [CustomLoginController::class, 'store'])->middleware('guest')->name('central.login');
    Route::domain('afnon.com')->group(function(){
        Route::get('/forgot-password', [CustomForgotPasswordController::class, 'showLinkRequestForm'])->name('central.password.request');
        Route::post('/forgot-password', [CustomForgotPasswordController::class, 'sendResetLinkEmail'])->name('central.password.email');
        Route::get('/reset-password/{token}', [CustomForgotPasswordController::class, 'showResetForm'])->name('central.password.reset');
        Route::put('/reset-password', [CustomForgotPasswordController::class, 'reset'])->name('central.password.update');
    });

});

Route::middleware(['auth', 'block-tenant-access'])->group(function () {
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

    Route::domain('afnon.com')->middleware(['auth', 'block-tenant-access'])->group(function () {
        Route::post('logout', [CustomLoginController::class, 'destroy'])
            ->name('central.logout');
    });
    

});
