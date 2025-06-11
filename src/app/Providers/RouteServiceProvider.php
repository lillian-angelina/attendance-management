<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Models\AttendanceCorrectionRequest;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        parent::boot();

        // モデルバインディングの追加（オプション）
        Route::model('attendanceCorrectionRequest', AttendanceCorrectionRequest::class);
    }
}
