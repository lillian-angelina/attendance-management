<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\StampCorrectionRequestController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminStaffController;

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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/attendance/list', [AdminAuthController::class, 'index'])->name('attendance.list');
    Route::get('/staff/list', [AdminStaffController::class, 'index'])->name('admin.staff.list');
    Route::get('/staff/{staff}/attendance', [AdminStaffController::class, 'attendance'])->name('staff.attendance');
});

Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index']);
Route::post('/attendance/{id}/request', [StampCorrectionRequestController::class, 'store'])->name('attendance.request');
Route::get('/admin/stamp_correction_requests', [StampCorrectionRequestController::class, 'index']);
Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [StampCorrectionRequestController::class, 'approve'])->name('admin.stamp_correction_request.approve');

// 修正申請詳細（承認画面）
Route::get('/stamp_correction_request/approve/{attendance_correction_request}', [StampCorrectionRequestController::class, 'showApprove'])
    ->middleware('auth:admin') // 管理者ログイン時のみアクセス可能にする
    ->name('admin.stamp_correction_request.approve');