<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\StampCorrectionRequestController;
use App\Http\Controllers\Admin\AdminStampCorrectionRequestController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminStaffController;

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 会員登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// 認証ルート + メール認証
Auth::routes(['verify' => true]);

// メール認証ルート
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');

    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('attendance.create')->with('message', 'メールアドレスの認証が完了しました。ログインしてください。');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証済みユーザーのルート
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');

    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.list');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');

    // 出勤ボタンのルート
    Route::post('/attendance/start', [AttendanceController::class, 'startWork'])->name('attendance.startWork');
    // 退勤ボタンのルート
    Route::post('/attendance/end', [AttendanceController::class, 'endWork'])->name('attendance.endWork');
    // 休憩入ボタンのルート
    Route::post('/attendance/rest-start', [AttendanceController::class, 'startRest'])->name('attendance.restStart');
    // 休憩戻ボタンのルート
    Route::post('/attendance/rest-end', [AttendanceController::class, 'endRest'])->name('attendance.restEnd');
});

// 修正申請
Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('stamp_correction_request.index');
Route::post('/stamp_correction_request/store', [StampCorrectionRequestController::class, 'store'])->name('stamp_correction_request.store');

// 管理者
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/stamp_correction_request/list', [AdminStampCorrectionRequestController::class, 'index'])->name('stamp_correction_request.index');
    Route::get('/stamp_correction_request/approve/{attendanceCorrectionRequest}', [AdminStampCorrectionRequestController::class, 'showApprove'])->name('stamp_correction_request.approve.show')->whereNumber('attendanceCorrectionRequest');
    Route::post('/stamp_correction_request/approve/{attendanceCorrectionRequest}', [AdminStampCorrectionRequestController::class, 'approve'])->name('stamp_correction_request.approve');
    Route::put('/stamp_correction_request/approve/{attendanceCorrectionRequest}', [AdminStampCorrectionRequestController::class, 'update'])->name('stamp_correction_request.update');
});

// 出勤情報の詳細表示
Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');

// 管理者認証
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('auth.logout');

    // 管理者認証後のルート
    Route::middleware('auth:admin')->group(function () {
        Route::get('/attendance/list', [AdminAuthController::class, 'index'])->name('attendance.list');
        Route::get('/staff/list', [AdminStaffController::class, 'index'])->name('staff.list');
        Route::get('/attendance/staff/{staff}', [AdminStaffController::class, 'attendance'])->name('attendance.staff');
    });
});

// 出勤情報の修正申請
Route::post('/attendance/{id}/request', [StampCorrectionRequestController::class, 'store'])->name('attendance.request');
