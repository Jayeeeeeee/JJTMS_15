<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate; // Uncomment if not already

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // Add your model policies here if any
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define Gates for roles
        \Illuminate\Support\Facades\Gate::define('isAdmin', function ($user) {
            return $user->hasRole('Admin');
        });

        \Illuminate\Support\Facades\Gate::define('isTeamLeader', function ($user) {
            return $user->hasRole('Team Leader');
        });

        \Illuminate\Support\Facades\Gate::define('isTeamMember', function ($user) {
            return $user->hasRole('Team Member');
        });
    }
}
