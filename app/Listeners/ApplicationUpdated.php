<?php

namespace App\Listeners;

use App\Models\ApplicationTimeline;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ApplicationUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $application = $event->application;
        
        if(auth()->check()) {
            $user_id = auth()->user()->id;
            $guard = "App\Models\User";
        } else if(auth('admin')->check()) {
            $user_id = auth('admin')->user()->id;
            $guard = "App\Models\Admin";
        }
        
        return ApplicationTimeline::create([
            'application_id' => $application->id,
            'status' => $application->status,
            'user_id' => $user_id,
            'user_type' => $guard
        ]);
    }
}
