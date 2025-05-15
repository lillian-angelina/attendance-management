<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\StampCorrectionRequestController;

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 会員登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// メール認証
Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.send');

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/start', [AttendanceController::class, 'startWork'])->name('attendance.start');
    Route::post('/attendance/rest/start', [AttendanceController::class, 'startRest'])->name('attendance.rest.start');
    Route::post('/attendance/rest/end', [AttendanceController::class, 'endRest'])->name('attendance.rest.end');
    Route::post('/attendance/end', [AttendanceController::class, 'endWork'])->name('attendance.end');

    // 出勤ボタンのルート
    Route::post('/attendance/clock-in', [AttendanceController::class, 'startWork'])->name('attendance.clockIn');
    // 退勤ボタンのルート
    Route::post('/attendance/clock-out', [AttendanceController::class, 'endWork'])->name('attendance.clockOut');
    // 休憩入ボタンのルート
    Route::post('/attendance/rest-start', [AttendanceController::class, 'startRest'])->name('attendance.restStart');
    // 休憩戻ボタンのルート
    Route::post('/attendance/rest-end', [AttendanceController::class, 'endRest'])->name('attendance.restEnd');
});

Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index']);
Route::post('/attendance/{id}/request', [StampCorrectionRequestController::class, 'store'])->name('attendance.request');
Route::get('/admin/stamp_correction_requests', [StampCorrectionRequestController::class, 'index']);