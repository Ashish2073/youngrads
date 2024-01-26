<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;

class Contact extends Model
{
    protected $table = "contacts";
    use LogsActivity;
    use Notifiable;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $fillable = ['name', 'email', 'message'];

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }

}
