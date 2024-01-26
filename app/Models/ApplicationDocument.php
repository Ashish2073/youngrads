<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApplicationDocument extends Model
{
  use LogsActivity;
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults();
  }
  protected static $logOnlyDirty = true;
  protected static $logFillable = true;
  protected $guarded = [];

  //use SoftDeletes;
  protected $table = "application_documents";
  //protected $softDelete = true;

  function documentCountry()
  {
    return $this->hasMany('App\Models\ApplicationDocumentCountry', 'application_document_id', 'id');
  }

  public function hasUserDocuments()
  {
    $user_docs = UserDocument::where([
      'document_type_id' => $this->id,
      'document_type' => 'application_document'
    ]);
    return $user_docs->count();
  }


}
