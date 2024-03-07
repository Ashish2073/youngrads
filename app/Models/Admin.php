<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification as Notification;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;


class Admin extends Authenticatable
{

    use LogsActivity,Notifiable;  

     protected static $logOnlyDirty = true;
    protected static $logFillable = true;

  

    protected static $logAttributes=['username','email'];
   

    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    const ROLE_ADMIN = 'Admin';
    const GUARD_ADMIN = 'admin';

    protected $guard = 'admin';
    protected $softDelete = true;
    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Custom password reset notification.
     *
     * @return void
     */

   




    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Notification($token));
    } 

    public function profileImage()
    {
        if ($this->profile_image) {
            return asset("uploads/profile_pic/" . $this->profile_image);
        } else {
            return asset('images/portrait/small/avatar-s-11.jpg');
        }
    } 

    public function getInitials()
    {
        $first = auth('admin')->user()->first_name[0];
        $second = auth('admin')->user()->last_name[0] ?? "";
        return $first . $second;
    }

    public function getFullName()
    {
        return $this->name . " " . $this->last_name;
    }



    public function supermoderator()
    {
        return $this->belongsTo(Admin::class, 'parent_id');
    }


    public function student()
    {
        return $this->hasMany(User::class, 'moderator_id');
    }



    public static  function getunreadmessage($i)
    {

        if (auth('admin')->check()) {
			if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
               $userid=auth('admin')->user()->username;
			   $message_status_type="admin_message_status";
			   $role="admin" ;

               $userunreadMessage=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
               ->select('users_applications.id as application_id','application_message.admin_message_status','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.admin_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='".$i."')) as count"),
               )->where('application_message.admin_message_status','unread')
               ->where('application_message.user_id','!=',$userid)
               ->where('application_message.message_scenario',$i)->get();





			}elseif(in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))){
				$userid=auth('admin')->user()->username;
				$message_status_type="moderator_message_status";
				$role="moderator";
                $userss_id=User::where('moderator_id',Auth::id())->pluck('id')->toArray();
               
                
                $userunreadMessage=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
                ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.moderator_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='".$i."')) as count"))  
                ->whereIn('users_applications.user_id',$userss_id)
                
                ->where('application_message.moderator_message_status','unread')
                ->where('application_message.user_id','!=',$userid)
                ->where('application_message.message_scenario',$i)->get();





			}elseif(in_array('supermoderator',json_decode(auth('admin')->user()->getRoleNames()))){

                $userid=auth('admin')->user()->username;
				$message_status_type="supermoderator_message_status";
				$role="supermoderator";
                $moderatorsid=Admin::where('parent_id',Auth::id())->pluck('id')->toArray();

                $userss_id=User::whereIn('moderator_id',$moderatorsid)->pluck('id')->toArray();

                $userunreadMessage=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
                ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.moderator_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='".$i."')) as count"))  
                ->whereIn('users_applications.user_id',$userss_id)
                
                ->where('application_message.supermoderator_message_status','unread')
                ->where('application_message.user_id','!=',$userid)
                ->where('application_message.message_scenario',$i)->get();




            }
			
		} elseif(auth('web')->check()) {
			$userid=Auth::id();
			$message_status_type="user_message_status";
			
			$role="user";
            $userunreadMessage=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
            ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.user_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='".$i."')) as count"))  
            ->where('users_applications.user_id',$userid)
            
            ->where('application_message.user_message_status','unread')
            ->where('application_message.user_id','!=',$userid)
            ->where('application_message.message_scenario','0')->get();


		}

        
        return $userunreadMessage;
    }


    // public static  function getadminmoderatorunreadmessage()
    // {

    //     if (auth('admin')->check()) {
	// 		if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
    //            $userid=auth('admin')->user()->username;
	// 		   $message_status_type="admin_message_status";
	// 		   $role="admin" ;

    //            $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //            ->select('users_applications.id as application_id','application_message.admin_message_status','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.admin_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='1')) as count"),
    //            )->where('application_message.admin_message_status','unread')
    //            ->where('application_message.user_id','!=',$userid)
    //            ->where('application_message.message_scenario','1')->get();






    //         }elseif(in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))){
	// 			$userid=auth('admin')->user()->username;
	// 			$message_status_type="moderator_message_status";
	// 			$role="moderator";
    //             $userss_id=User::where('moderator_id',Auth::id())->pluck('id')->toArray();
               
                
    //             $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //             ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.moderator_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='1')) as count"))  
    //             ->whereIn('users_applications.user_id',$userss_id)
                
    //             ->where('application_message.moderator_message_status','unread')
    //             ->where('application_message.user_id','!=',$userid)
    //             ->where('application_message.message_scenario','1')->get();





	// 		}elseif(in_array('supermoderator',json_decode(auth('admin')->user()->getRoleNames()))){

    //             $userid=auth('admin')->user()->username;
	// 			$message_status_type="supermoderator_message_status";
	// 			$role="supermoderator";
    //             $moderatorsid=Admin::where('parent_id',Auth::id())->pluck('id')->toArray();

    //             $userss_id=User::whereIn('moderator_id',$moderatorsid)->pluck('id')->toArray();

    //             $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //             ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.moderator_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='1')) as count"))  
    //             ->whereIn('users_applications.user_id',$userss_id)
                
    //             ->where('application_message.supermoderator_message_status','unread')
    //             ->where('application_message.user_id','!=',$userid)
    //             ->where('application_message.message_scenario','1')->get();




    //         }
			
	// 	} elseif(auth('web')->check()) {
	// 		$userid=Auth::id();
	// 		$message_status_type="user_message_status";
			
	// 		$role="user";
    //         $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //         ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.user_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='1')) as count"))  
    //         ->where('users_applications.user_id',$userid)
            
    //         ->where('application_message.user_message_status','unread')
    //         ->where('application_message.user_id','!=',$userid)
    //         ->where('application_message.message_scenario','1')->get();
	// 	}

       



			
		
       
    
       
    
        
    //     return $userunreadMessageadminmoderator;
    // }


    // public static  function getadmintomoderatorunreadmessage()
    // {

    //     if (auth('admin')->check()) {
	// 		if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
    //            $userid=auth('admin')->user()->username;
	// 		   $message_status_type="admin_message_status";
	// 		   $role="admin" ;

    //            $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //            ->select('users_applications.id as application_id','application_message.admin_message_status','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.admin_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='2')) as count"),
    //            )->where('application_message.admin_message_status','unread')
    //            ->where('application_message.user_id','!=',$userid)
    //            ->where('application_message.message_scenario','2')->get();






    //         }elseif(in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))){
	// 			$userid=auth('admin')->user()->username;
	// 			$message_status_type="moderator_message_status";
	// 			$role="moderator";
    //             $userss_id=User::where('moderator_id',Auth::id())->pluck('id')->toArray();
               
                
    //             $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //             ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.moderator_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='2')) as count"))  
    //             ->whereIn('users_applications.user_id',$userss_id)
                
    //             ->where('application_message.moderator_message_status','unread')
    //             ->where('application_message.user_id','!=',$userid)
    //             ->where('application_message.message_scenario','2')->get();





	// 		}elseif(in_array('supermoderator',json_decode(auth('admin')->user()->getRoleNames()))){

    //             $userid=auth('admin')->user()->username;
	// 			$message_status_type="supermoderator_message_status";
	// 			$role="supermoderator";
    //             $moderatorsid=Admin::where('parent_id',Auth::id())->pluck('id')->toArray();

    //             $userss_id=User::whereIn('moderator_id',$moderatorsid)->pluck('id')->toArray();

    //             $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //             ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.moderator_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='2')) as count"))  
    //             ->whereIn('users_applications.user_id',$userss_id)
                
    //             ->where('application_message.supermoderator_message_status','unread')
    //             ->where('application_message.user_id','!=',$userid)
    //             ->where('application_message.message_scenario','2')->get();




    //         }
			
	// 	} elseif(auth('web')->check()) {
	// 		$userid=Auth::id();
	// 		$message_status_type="user_message_status";
			
	// 		$role="user";
    //         $userunreadMessageadminmoderator=ApplicationMessage::join('users_applications','users_applications.id','=','application_message.application_id')
    //         ->select('users_applications.id as application_id','users_applications.application_number as application_number','application_message.created_at as time','application_message.message as message', DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.user_message_status = 'unread' && application_id = users_applications.id && application_message.message_scenario='2')) as count"))  
    //         ->where('users_applications.user_id',$userid)
            
    //         ->where('application_message.user_message_status','unread')
    //         ->where('application_message.user_id','!=',$userid)
    //         ->where('application_message.message_scenario','2')->get();
	// 	}

       



			
		
       
    
       
    
        
    //     return $userunreadMessageadminmoderator;
    // }






}
