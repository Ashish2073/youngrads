<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin;
use App\Models\MessageAttachment;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApplicationMessage extends Model
{
    protected $table = "application_message";

    use LogsActivity; 
    protected static $logOnlyDirty = true; 
    protected static $logFillable = true;
    protected $guarded = []; 

    // protected static $logAttributes=['message','admin_message_status'];

    // public function adminName(){
    //      if($this->guard == 'admin'){
    //         return $this->hasMany('App\Admin','id','user_id');
    //      }else{
    //        return $this->hasMany('App\User','id','user_id');
    //      }
    // }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

 
}
