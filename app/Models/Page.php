<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Page extends Model
{
    protected $table = "pages";
    use LogsActivity;
    use SoftDeletes;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $softDelete = true;
    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }

}
