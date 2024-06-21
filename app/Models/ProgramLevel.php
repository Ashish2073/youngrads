<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;


class ProgramLevel extends Model
{

    protected $table = "program_levels";
    use SoftDeletes;
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];
    protected $softDelete = true;

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }


    public function getProgram()
    {
        return $this->hasMany('App\Models\Program', 'study_area_id', 'id');
    }

    public function studyLevel()
    {
        return $this->belongsTo('App\Models\studyLevel', 'study_level_id');
    }

    public static function getNameIdArr()
    {
        $records = self::all();
        $nameIdArr = [];
        foreach ($records as $record) {
            // $nameIdArr[(strtolower($record->name))] = $record->id;
            $nameIdArr[str_replace(' ', '', strtolower($record->name))] = $record->id;
        }
        return $nameIdArr;
    }



}
