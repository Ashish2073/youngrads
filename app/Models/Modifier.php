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

class Modifier extends Model
{
    use HasFactory;
    use LogsActivity; 
  
    protected static $logOnlyDirty = true;
    protected $table="modifiers";
   protected static $logFillable = true;
   use HasRoles;
   use Notifiable;
   use SoftDeletes;
 

   protected $guard = 'modifier';
   protected $softDelete = true;
   protected $guarded = [];

   const ROLE_MODERATOR = 'Modifier'; 
   const GUARD_MODERATOR = 'Modifier';
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
    $first = auth('modifier')->user()->first_name[0];
    $second = auth('modifier')->user()->last_name[0] ?? "";
    return $first . $second;
}

public function getFullName()
{
    return $this->name . " " . $this->last_name;
}




}
