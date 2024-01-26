<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class State extends Model
{
  protected $table = "states";
  //public $timestamps = false;
  protected $softDelete = true;
  use SoftDeletes;
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];


  public function getCountry()
  {
    return $this->hasOne('App\Models\Country', 'id', 'country_id');
  }

  public function address()
  {
    return $this->hasMany('App\Models\Address', 'state_id', 'id');
  }

  public static function getStateNameIdArr()
  { 
    $records = State::all();
    $countryIdStateArr = [];
    foreach ($records as $record) {
      $countryIdStateArr[$record->country_id . "__" . strtolower($record->name)] = $record->id;
    }
    return  $countryIdStateArr;
  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }


}
