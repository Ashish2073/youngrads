<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserDocumentFiles extends Model
{
  protected $table = "user_document_files";
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];

  function getFile()
  {
    return $this->hasOne('App\Models\Files', 'id', 'file_id');
  }

  public function file()
  {
    return $this->belongsTo('App\Models\Files');

  }

  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }

}
