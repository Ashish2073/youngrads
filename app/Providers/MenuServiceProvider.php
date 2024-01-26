<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
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
    public function boot(): void
    { {
            view()->composer("*", function ($view) {



                // get all data from menu.json file
                if (request()->segment(1) == 'admin') {
                    $verticalMenuData = (object) ['menu' => config('menu')];
                } else {

                    $student_menu = config('student_menu');
                    // array_unshift($student_menu, $profile_menu);
                    $verticalMenuData = (object) ['menu' => $student_menu];
                }
                $horizontalMenuData = (object) ['menu' => []];

                // Share all menuData to all the views
                \View::share('menuData', [$verticalMenuData, $horizontalMenuData]);
            });
        }
    }
}
