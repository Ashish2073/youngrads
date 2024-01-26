<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Admin;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

        'App\Model' => 'App\Policies\ModelPolicy',


    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Passport::routes();
        //
//        if(auth('admin')->check()) {
        Gate::before(function ($user, $ability) {
            return auth('admin')->user()->hasRole('Admin', 'admin') ? true : null;
        });
        //        }
    }
}
