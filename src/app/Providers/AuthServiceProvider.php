<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\AttendanceCorrectionRequest;
use App\Policies\AttendanceCorrectionRequestPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AttendanceCorrectionRequest::class => AttendanceCorrectionRequestPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user instanceof Admin) {
                return true;
            }
        });
    }
}
