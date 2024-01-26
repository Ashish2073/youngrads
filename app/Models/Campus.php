<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Campus extends Model
{
    protected $table = "campus";
    use LogsActivity;
    use SoftDeletes;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];
    protected $softDelete = true;

    public function getUniversity()
    {
        return $this->hasOne('App\Models\University', 'id', 'university_id');
    }

    public function getAddress()
    {
        return $this->hasOne('App\Models\Address', 'id', 'addresse_id');
    } 

    public function getLogo()
    {
        if (is_null($this->logo) || empty($this->logo)) {
            return asset('uploads/program_logo/download.png');
        } else {
            return asset('uploads/program_logo/' . $this->logo);
        }
    }

    public function university()
    {
        return $this->belongsTo('App\Models\University', 'university_id');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address', 'address_id');
    }

    public function campusProgram()
    {
        return $this->hasMany('App\Models\CampusProgram', 'campus_id', 'id');
    }

    public static function getUnivIdCampusNameArr()
    {
        $records = Campus::all();
        $univIdCampusArr = [];
        foreach ($records as $record) {
            $univIdCampusArr[$record->university_id . "__" . strtolower($record->name)] = $record->id;
        }
        return $univIdCampusArr;
    }

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }


}
