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

        Route::model('attendanceCorrectionRequest', AttendanceCorrectionRequest::class);
    }
}
