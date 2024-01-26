<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class City extends Model
{
  protected $table = "cities";
  public $timestamps = false;
  use LogsActivity;
  use SoftDeletes;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $softDelete = true;
  protected $guarded = [];


  function getState()
  {
    return $this->hasOne('App\Models\State', 'id', 'state_id');
  }

  function address()
  {
    return $this->hasMany('App\Models\Address', 'city_id', 'id');
  }

  public static function getCityNameIdArr()
  {
    $records = City::all();
    $cityNameIdArr = [];
    foreach ($records as $record) {
      $cityNameIdArr[$record->country_id . "__". $record->state_id . "__" . strtolower($record->name)] = $record->id;
      
    
    }
    return  $cityNameIdArr;

  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }


}
