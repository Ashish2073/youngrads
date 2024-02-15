<?php

namespace App\Models;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification as Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Modifires extends Model
{
    use HasFactory;
    use LogsActivity; 
  
    protected static $logOnlyDirty = true;
    protected $table="modifires";
   protected static $logFillable = true;
   use HasRoles;
   use Notifiable;
   use SoftDeletes;


   protected $guard = 'moderators';
   protected $softDelete = true;
   protected $guarded = [];

   const ROLE_MODERATOR = 'Moderator';
   const GUARD_MODERATOR = 'moderators';
   protected $hidden = [
    'password', 'remember_token',
];

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
    $first = auth('moderator')->user()->first_name[0];
    $second = auth('moderator')->user()->last_name[0] ?? "";
    return $first . $second;
}

public function getFullName()
{
    return $this->name . " " . $this->last_name;
}




}
