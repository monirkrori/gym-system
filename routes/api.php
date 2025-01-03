<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Routes for authentication
Route::prefix('auth')->group(function () {
    // Register route
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

    // Login route
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    // Email verification routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Resend verification email
        Route::post('/email/resend', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return response()->json(['message' => 'Verification email resent successfully.']);
        })->name('verification.resend');

        // Logout route
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });

    // Verify email
    Route::get('/verify-email/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json(['message' => 'Email verified successfully.']);
    })->middleware('signed')->name('verification.verify');

    // Forgot password route
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // Reset password route
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
        ->name('password.reset');
});
