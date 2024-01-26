<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class studyLevel extends Model
{
  use LogsActivity;
  use SoftDeletes;

  public $table = "study_levels";
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $softDelete = true;

  function getDocument()
  {
    return $this->hasMany('App\Models\Document', 'sub_type', 'id');
  }
  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }
}
