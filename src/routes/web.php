<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// 会員登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// メール認証
Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/start', [AttendanceController::class, 'startWork'])->name('attendance.start');
    Route::post('/attendance/rest/start', [AttendanceController::class, 'startRest'])->name('attendance.rest.start');
    Route::post('/attendance/rest/end', [AttendanceController::class, 'endRest'])->name('attendance.rest.end');
    Route::post('/attendance/end', [AttendanceController::class, 'endWork'])->name('attendance.end');
});