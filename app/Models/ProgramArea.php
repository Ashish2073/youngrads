<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Study;
use Spatie\Activitylog\LogOptions;

class ProgramArea extends Model
{
    protected $table = "program_study_areas";
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function studyArea()
    {
        return $this->belongsTo('App\Models\Study', 'study_area_id');
    }

    public static function getIdNameArr($programId)
    {
        $records = self::where('program_id', $programId);
        $nameIdArr = [];
        foreach ($records as $record) {
            $nameIdArr["program_" . $record->program_id . "__" . "study_area_" . $record->study_area_id] = $record->id;
        }
        return $nameIdArr;
    }


}
