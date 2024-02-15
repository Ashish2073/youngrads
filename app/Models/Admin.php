<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification as Notification;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;


class Admin extends Authenticatable
{

    use LogsActivity; 
     protected static $logOnlyDirty = true;
    protected static $logFillable = true;

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

}
