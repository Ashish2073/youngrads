<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['request']->server->set('HTTP', true);
        Activity::saving(function (Activity $activity) {
            $activity->ip_address = request()->ip();
        });
        Schema::defaultStringLength(191);
        if (request()->segment(1) != 'admin') {
            Config::set('custom.custom.sidebarCollapsed', true);
        }
        $this->app->bind('path.public', function () {
            return base_path() . '/public';
        });
    }
}
