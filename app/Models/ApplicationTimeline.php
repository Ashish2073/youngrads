<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApplicationTimeline extends Model
{
    //
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

}
