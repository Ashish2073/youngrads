<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Test extends Model
{
  protected $table = "tests";
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];
  protected $softDelete = true;

  function campusProgramTest()
  {
    return $this->hasMany('App\Models\CampusProgramTest', 'test_id', 'id');
  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }
}
