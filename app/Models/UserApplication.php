<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use App\Models\ApplicationTimeline;
use App\Notifications\ApplicationStatusUpdate;
use Spatie\Activitylog\Traits\LogsActivity;


class UserApplication extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = "users_applications";
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];
    const ARCHIVE = 'archive';
    const PENDING = 'pending';
    const SUBMITEED_TO_YGRAD = 'submit_to_ygrad';
    const APPLICANT_ACTION_REQUIRED = 'applicant_action_required';

    const ACTIVE = "active";
    const INACTIVE = "inactive";

    public static function getNextApplicationNumber()
    {
        $record = self::orderBy('id', 'desc')->limit(1)->get();
        if ($record->count() == 0) {
            $application_number = "YG" . date('ym', time()) . "1000";
        } else {
            $record = $record[0];
            if (is_null($record->application_number) || empty($record->application_number)) {
                $sr_no = 1000;
            } else {
                $sr_no = substr($record->application_number, 6);
                $sr_no = intval($sr_no) + 1;
            }
            $application_number = "YG" . date('ym', time()) . $sr_no;
        }
        return $application_number;
    }

    public function campusProgram()
    {
        return $this->belongsTo('App\Models\CampusProgram', 'campus_program_id');
    }

    public function intake()
    {
        return $this->belongsTo('App\Models\Intake', 'intake_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function requiredDocuments()
    {
        // Get Country of Application
        try {
            $country_id = $this->campusProgram->campus->address->country->id;
        } catch (\Exception $e) {
            return [];
        }

        $application_documents = ApplicationDocument::join('application_document_countries', 'application_document_countries.application_document_id', '=', 'application_documents.id')
            ->where('application_document_countries.country_id', $country_id)->select('application_documents.*')->get();

        foreach ($application_documents as $document) {
            $document->uploaded = UserDocument::where([
                'application_id' => $this->id,
                'user_id' => $this->user_id,
                'document_type' => 'application_document',
                'document_type_id' => $document->id
            ])->get();
        }
        return $application_documents;
    }

    public function isCompleted($progress)
    {
        // Check information Except Documents
        $result = $progress['general_information']['status'] &&
            $progress['education_history']['status'] &&
            $progress['test_scores']['status'] &&
            $progress['work_experience']['status'] &&
            $progress['background_information']['status'];

        if (!$result) {
            return false;
        }

        // Checking Application Specific documents
        $application_docs = $this->requiredDocuments();
        if (!empty($application_docs)) {
            $i = 0;
            foreach ($application_docs as $doc) {
                $i += $doc->uploaded->count();
            }
            if ($i < $application_docs->count()) {
                return false;
            }
        }
        // Checking Profile Documents
        try {
            $program_level = $this->campusProgram->program->programLevel;
            $study_level = $program_level->studyLevel;
            $documents = User::uploadDocumentList(auth()->user(), $program_level->study_level_id);
        } catch (\Exception $e) {
            $documents = [];
        }

        foreach ($documents ?? [] as $key => $document) {
            $document_type = $document['document_type'];
            $i = 0;
            $document_count = 0;
            foreach ($document['document_lists'] ?? [] as $list) {
                if ($document_type == 'study_levels' && $list['name'] == 'Other') {
                    continue;
                }
                $document_count++;
                foreach ($list['document_list'] ?? [] as $list) {
                    $i += $list->documents->count();
                }
            }
            if ($i < $document_count) {
                return false;
            }
        }

        return true;
    }

    public function createActivity()
    {
        if (auth()->check()) {
            $user_id = auth()->user()->id;
            $guard = "App\Models\User";
        } else if (auth('admin')->check()) {
            $user_id = auth('admin')->user()->id;
            $guard = "App\Models\Admin";
        }

        $application = ApplicationTimeline::create([
            'application_id' => $this->id,
            'status' => $this->status,
            'user_id' => $user_id,
            'user_type' => $guard
        ]);
        if ($application) {
            if ($this->status == 'pending' || $this->status == 'archive') {
            } else {
                $user = User::find($this->user_id);

                $user->notify(new ApplicationStatusUpdate($this));
            }
        }
        return $application;
    }

    public function timeline()
    {
        return $this->hasMany('App\Models\ApplicationTimeline', 'application_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }
}
