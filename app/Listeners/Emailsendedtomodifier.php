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
        
  
        
        
        $modifiercreadational = [
            'username' => $event->userdata->username,
            
        ];
        
        if (isset($event->userdata->modifier_password)) {
            $modifiercreadational['password'] = $event->userdata->modifier_password;
        }
         
        if (isset($event->userdata->roles)) {
            $modifiercreadational['userroles'] = $event->userdata->roles;
        }
         
      
        
     
    
 
        Mail::to($event->userdata->email)->send(new ModifierRoleProfileMail($modifiercreadational));

       
    }
}
