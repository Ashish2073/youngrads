<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MessageAttachment extends Model
{
  protected $table = "message_attachments";
  use LogsActivity;
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];
  public function getActivitylogOptions(): LogOptions
  {
    //use Spatie\Activitylog\LogOptions
    return LogOptions::defaults();
  }
}
