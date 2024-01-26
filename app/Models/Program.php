<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProgramArea;
use Spatie\Activitylog\LogOptions;

class Program extends Model
{
    protected $table = "programs";
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];

    protected $softDelete = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function saveSubStudyArea($ids)
    {
        ProgramArea::where('program_id', $this->id)->delete();
        foreach ($ids ?? [] as $id) {
            $program_area = new ProgramArea;
            $program_area->study_area_id = $id;
            $program_area->program_id = $this->id;
            $program_area->save();
        }
    }

    public function subAreaIds()
    {
        return ProgramArea::where('program_id', $this->id)->pluck('study_area_id')->toArray();
    }

    public function programLevel()
    {
        return $this->belongsTo('App\Models\ProgramLevel', 'program_level_id');
    }

    public function studyArea()
    {
        return $this->belongsTo('App\Models\Study', 'study_area_id');
    }

    public function disciplineAreas()
    {
        return $this->hasMany('App\Models\ProgramArea', 'program_id');
    }

    public function campusProgram()
    {
        return $this->hasMany('App\Models\CampusProgram', 'program_id', 'id');
    }

    public static function getNameIdArr()
    {
        $records = self::all();
        $nameIdArr = [];
        foreach ($records as $record) {
            $nameIdArr[strtolower($record->name)] = $record->id;
        }
        return $nameIdArr;
    }
}
