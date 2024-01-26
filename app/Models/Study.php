<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Study extends Model
{
    protected $table = "study_areas";
    use LogsActivity;
    use SoftDeletes;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $softDelete = true;
    protected $guarded = [];

    public function programs()
    {
        return $this->hasMany('App\Models\Program', 'study_area_id', 'id');
    }

    public function hasChild()
    {
        if ($this->parent_id == 0) {
            return Study::where('parent_id', $this->id)->get()->count();
        }
        return false;
    }

    public static function getNameIdArr()
    {
        $records = self::where('parent_id', 0)->get();
        $nameIdArr = [];
        foreach ($records as $record) {
            $nameIdArr[strtolower($record->name)] = $record->id;
        }
        return $nameIdArr;
    }

    public static function getsubStudyNameIdArr()
    {
        $records = self::where('parent_id', '<>', 0)->get();
        $nameIdArr = [];
        foreach ($records as $record) {
            $nameIdArr[$record->parent_id . "__" . strtolower($record->name)] = $record->id;
        }
        return $nameIdArr;
    }

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }


}
