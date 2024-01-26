<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Currency extends Model
{
    protected $table = "currencies";
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];

    public static function getIdNameArr()
    {
        $records = self::all();
        $nameIdArr = [];
        foreach ($records as $record) {
            $nameIdArr[strtolower($record->code)] = $record->id;
        }
        return $nameIdArr;
    }

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }
}
