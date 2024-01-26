<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CampusProgramTest extends Model
{
  use SoftDeletes;
  protected $table = "campus_program_test";
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];
  protected $softDelete = true;

  public function test()
  {
    return $this->belongsTo('App\Models\Test', 'test_id');
  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }


}
