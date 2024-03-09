<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Sendmailmodifier;
use Illuminate\Support\Facades\Mail;
use App\Mail\ModifierRoleProfileMail;

class Emailsendedtomodifier
{
    /**
     * Create the event listener.
     */
   
    public function __construct()
    {
       
    }

    /**
     * Handle the event.
     */
    public function handle(Sendmailmodifier  $event): void
    {
       
        $modifiercreadational=[
       'password'=>json_decode($event->userdata->modifier_password),
       'username'=>$event->userdata->username,
       'userroles'=>$event->userdata->roles,

        ]; 

    
 
        Mail::to($event->userdata->email)->send(new ModifierRoleProfileMail($modifiercreadational));

       
    }
}
