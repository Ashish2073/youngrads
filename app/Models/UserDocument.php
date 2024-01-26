<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Files;
use App\Models\UserDocumentFiles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserDocument extends Model
{
    protected $table = "user_documents";
    // protected $fillable  = ['user_id','document_type_id','lable_id'];
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];

    /**
     * Deletes the user document files records
     * @return - list of filename to delete that can be used to delete files
     * after successfull operation.
     */
    public function deleteFileRecord()
    {
        $files_to_delete = [];
        $user_files = UserDocumentFiles::where([
            'user_document_id' => $this->id,
        ])->get();
        foreach ($user_files as $user_file) {
            $file = Files::find($user_file->file_id);
            $files_to_delete[] = $file->location;

            $file->delete();
        }

        foreach ($user_files as $user_file) {
            $user_file->delete();
        }

        return $files_to_delete;
    }

    public function documentFile()
    {
        return $this->hasOne('App\Models\UserDocumentFiles', 'user_document_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }
}
