<?php

use App\Http\Controllers\TeacherAuth\AuthenticatedSessionController;
use App\Http\Controllers\TeacherAuth\ConfirmablePasswordController;
use App\Http\Controllers\TeacherAuth\EmailVerificationNotificationController;
use App\Http\Controllers\TeacherAuth\EmailVerificationPromptController;
use App\Http\Controllers\TeacherAuth\NewPasswordController;
use App\Http\Controllers\TeacherAuth\PasswordController;
use App\Http\Controllers\TeacherAuth\PasswordResetLinkController;
use App\Http\Controllers\TeacherAuth\RegisteredUserController;
use App\Http\Controllers\TeacherAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:teacher')->group(function () {
    Route::get('teacher/register', [RegisteredUserController::class, 'create'])
                ->name('teacher.register');

    Route::post('teacher/register', [RegisteredUserController::class, 'store']);

    Route::get('teacher/login', [AuthenticatedSessionController::class, 'create'])
                ->name('teacher.login');

    Route::post('teacher/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('teacher/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('teacher.password.request');

    Route::post('teacher/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('teacher.password.email');

    Route::get('teacher/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('teacher.password.reset');

    Route::post('teacher/reset-password', [NewPasswordController::class, 'store'])
                ->name('teacher.password.store');
});

Route::middleware('auth:teacher')->group(function () {
    Route::get('teacher/verify-email', EmailVerificationPromptController::class)
                ->name('teacher.verification.notice');

    Route::get('teacher/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('teacher.verification.verify');

    Route::post('teacher/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('teacher.verification.send');

    Route::get('teacher/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('teacher.password.confirm');

    Route::post('teacher/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('teacher/password', [PasswordController::class, 'update'])->name('teacher.password.update');

    Route::post('teacher/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('teacher.logout');
});
