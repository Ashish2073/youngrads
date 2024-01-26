<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class SpecialTest extends Model
{
  protected $table = "user_special_tests";
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];

  public function getSubScore()
  {
    return $this->hasMany('App\Models\SubTest', 'test_id', 'test_type_id')
      ->join('special_test_sub', 'user_sub_test_score.sub_id', '=', 'special_test_sub.id')
      ->where('user_id', '=', $this->user_id);
  }

  function getType()
  {
    return $this->hasOne('App\Models\Test', 'id', 'test_type_id');
  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }

}
