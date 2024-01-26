<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserAcademic extends Model
{
  protected $table = "user_academics";
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];
  function getStudyLevel()
  {
    return $this->hasOne('App\Models\studyLevel', 'id', 'study_levels_id');
  }

  function getCountry()
  {
    return $this->hasOne('App\Models\Country', 'id', 'country');
  }

  function getDocumentType()
  {
    return $this->hasMany('App\Models\studyLevel', 'parent_id', 'study_levels_id');
  }
  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }
}
