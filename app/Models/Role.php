<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\Admin;

class Role extends \Spatie\Permission\Models\Role
{
   use SoftDeletes,LogsActivity;
   protected $softDelete = "true";

     

   protected static $logOnlyDirty = true;
  protected static $logFillable = true; 

  protected static $logAttributes=['name'];
 
  public function getActivitylogOptions(): LogOptions
  {
      return LogOptions::defaults();
  }
 
}
