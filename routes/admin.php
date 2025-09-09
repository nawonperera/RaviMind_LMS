<?php

//Admin routes file for managing admin-specific functionalities

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
============================================================
=                 Admin Guest Authentication               =
=  These routes are for admins who are NOT logged in yet.  =
=  Includes register, login, forgot password, and reset.   =
============================================================
*/


Route::group(["middleware" => "guest:admin", "prefix" => "admin", "as" => "admin."], function () {
    // "guest" means these routes are accessible only to users who are not logged in.

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    // `create` shows the reset password form, `store` saves the new password from the form.

});

/*
============================================================
=                  Admin Authentication Routes             =
=  These routes handle email verification, password reset, =
=  password confirmation, and admin logout functionality.  =
============================================================
*/

Route::group(["middleware" => "auth:admin", "prefix" => "admin", "as" => "admin."], function () {
    // "middleware('auth')"----> auth middleware give access to the following routes only to authenticated users.
    // "auth:admin" means these routes are accessible only to users who are logged in as admins.

    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    ============================================================
    =                     Admin Dashboard Route                =
    ============================================================
    */

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
