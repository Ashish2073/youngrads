<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class University extends Model
{
  protected $table = "universities";
  use SoftDeletes; 
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];
  protected $softDelete = true;


  public function campus()
  {
    return $this->hasMany('App\Models\Campus');
  }

  public static function getNameIdIndexedArray()
  {
    $records = self::all();
    $nameIdArr = [];
    foreach ($records as $record) {
      $nameIdArr[strtolower($record->name)] = $record->id;
    }
    return $nameIdArr;
  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }
}
