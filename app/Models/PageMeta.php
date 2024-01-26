<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PageMeta extends Model
{
    protected $table = "pages_meta";
    protected $fillable = ['page_id', 'meta_key', 'meta_value'];
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }

}
