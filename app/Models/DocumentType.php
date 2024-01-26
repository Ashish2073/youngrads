<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\UserDocument;
use Spatie\Activitylog\LogOptions;

class DocumentType extends Model
{
    use SoftDeletes;
    protected $table = "document_types";
    protected $softDelete = true;
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];
    public function documentFile()
    {
        return $this->hasOne('App\Models\UserDocument', 'document_type_id', 'id');
    }

    public function hasUserDocuments()
    {
        $user_docs = UserDocument::where([
            'document_type_id' => $this->id,
            'document_type' => 'document_types'
        ]);
        return $user_docs->count();
    }

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }

}
