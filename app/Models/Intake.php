<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Intake extends Model
{
  protected $table = "intakes";
  use LogsActivity;
  use SoftDeletes;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $softDelete = true;
  protected $guarded = [];


  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults();
  }

  public function campusIntakeProgram()
  {
    return $this->hasMany('App\Models\CampusProgramIntake', 'intake_id', 'id');
  }

  public static function getIdNameArr()
  {
    $records = self::all();
    $nameIdArr = [];
    foreach ($records as $record) {
      $nameIdArr[strtolower($record->name)] = $record->id;
    }
    return $nameIdArr;
  }
}
