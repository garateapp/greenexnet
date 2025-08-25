<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('bi_report_access', function ($user) {
            return in_array('bi_report_access', $user->roles->pluck('permissions')->flatten()->pluck('title')->toArray());
        });

        Gate::define('manage_bi_reports', function ($user) {
            return in_array('manage_bi_reports', $user->roles->pluck('permissions')->flatten()->pluck('title')->toArray());
        });
    }
}
