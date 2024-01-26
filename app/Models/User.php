<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Notifications\StudentEmailChange;
use Illuminate\Support\Facades\URL;
use App\Models\UserMeta;
use App\Models\City;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable implements MustVerifyEmail
{
    use LogsActivity;
    use SoftDeletes;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $softDelete = true;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'username', 'password', 'contact_number', 'bio', 'address', 'hotel_name', 'city', 'state', 'country', 'paypal_email', 'provider_id', 'profile_img', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public static function updateGeneralInformation($request, $user)
    {
        $user->name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->dob = !empty($request->dob) ? date('Y-m-d', strtotime($request->dob)) : null;
        $user->country = $request->country;
        $user->maritial_status = $request->maritial_status;
        $user->gender = $request->gender;
        $user->personal_number = $request->personal_number;
        $user->language = $request->language;
        $user->postal = $request->postal;
        $user->passport = $request->passport_number;



        if ($user->save()) {
            $address = is_null($user->address_id) || empty($user->address_id) ? new Address : Address::find($user->address_id);
            $address->address = $request->address;
            $address->country_id = $request->address_country;
            $address->state_id = $request->state;
            if ($request->city == "new-city") {
                if (City::where('name', $request->city_name)->get()->count() > 0) {
                    $address->city_id = City::where('name', $request->city_name)->first()->id;
                } else {
                    $newcity = new City;
                    $newcity->name = $request->city_name;
                    $newcity->state_id = $request->state;
                    $newcity->save();
                    $address->city_id = $newcity->id;
                }
            } else {
                $address->city_id = $request->city;
            }
            if ($address->save()) {
                $user->address_id = $address->id;
                $user->save();
            }

            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'General Information saved successfully',
                'success' => true
            ]);
        } else {
            return view('student.profile.steps.general_information', compact('user'));
        }
    }

    public function profileUrl()
    {
        return url(route('viewprofile', $this->id));
    }

    public function getInitials()
    {
        $first = auth()->user()->name[0];
        $second = auth()->user()->last_name[0] ?? "";
        return $first . $second;
    }


    public function userStatus()
    {
        $result = [];
        $result['statusText'] = strtoupper($this->user_status) ?? "N/A";
        switch ($this->user_status) {
            case 'approved':
                $result['statusClass'] = 'success';
                break;

            case 'unapproved':
                $result['statusClass'] = 'warning';
                break;

            case 'suspended':
                $result['statusClass'] = 'danger';
                break;

            default:
                $result['statusClass'] = 'info';
                break;
        }
        return $result;
    }

    public function getFullName()
    {
        return $this->name . " " . $this->last_name;
    }

    public function sendChangeEmailVerificationNotification()
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );
        $this->notify(new StudentEmailChange($verifyUrl));
    }

    public function routeNotificationForMail($notification)
    {
        if (isset($notification->to_address)) {
            return $this->new_email;
        }
        return $this->email;
    }
 
    public function getprofileImg()
    {
        if ($this->profile_img) {
            return asset("uploads/profile_pic/student/" . $this->profile_img);
        } else {
            return asset('images/portrait/small/avatar-s-11.jpg');
        }
    }

    public function meta($key)
    {
        $meta = UserMeta::where([
            'user_id' => $this->id,
            'meta_key' => $key
        ])->first();

        return !is_null($meta) ? $meta->meta_value : "";
    }

    public function createOrUpdateMeta($key, $value)
    {
        $data = UserMeta::where([
            'meta_key' => $key,
            'user_id' => $this->id
        ])->get();

        if ($data->count() > 0) {
            $record = UserMeta::where(['meta_key' => $key, 'user_id' => $this->id])->first();
            $record->meta_value = $value;
            $record->save();
        } else {
            $record = new UserMeta;
            $record->meta_key = $key;
            $record->meta_value = $value;
            $record->user_id = $this->id;
            $record->save();
        }
        return true;
    }

    public function academics()
    {
        return $this->hasMany('App\Models\UserAcademic');
    }

    public function tests()
    {
        return $this->hasMany('App\Models\SpecialTest');
    }

    public static function uploadDocumentList($user, $level_ids = null)
    {
        $document_lists = [];

        if (is_null($level_ids)) {
            $study_levels = studyLevel::join('user_academics', 'user_academics.study_levels_id', '=', 'study_levels.id')
                ->where('user_academics.user_id', $user->id)
                ->select('study_levels.*', 'user_academics.sub_other as sub_other')
                ->orderBy('study_levels.sequence', 'asc')
                ->get();
        } else {
            $study_levels = studyLevel::whereIn('id', explode(",", $level_ids))
                ->where('study_levels.parent_id', 0)
                ->select('study_levels.*')->get();
        }

        $document_lists['StudyLevel']['group_name'] = 'Education History';
        $document_lists['StudyLevel']['document_type'] = 'study_levels';

        foreach ($study_levels as $study_level) {
            if ($study_level->name == "Other") {
                // $study_level->name = "Other - " . $study_level->sub_other;
            }
            $main_docs = studyLevel::where('parent_id', $study_level->id)->get();
            foreach ($main_docs as $main_doc) {
                $user_document = UserDocument::where([
                    'user_id' => $user->id,
                    'document_type_id' => $main_doc->id,
                    'document_type' => 'study_levels'
                ])->get();
                $main_doc->documents = $user_document;
            }
            $document_lists['StudyLevel']['document_lists'][] = [
                'name' => $study_level->name,
                'id' => $study_level->id,
                'document_list' => $main_docs
            ];
        }

        $tests = Test::join('user_special_tests', 'user_special_tests.test_type_id', '=', 'tests.id')
            ->where('user_special_tests.user_id', $user->id)
            ->select('tests.*')->get();
        $document_lists['Test']['group_name'] = 'Test Scores';
        $document_lists['Test']['document_type'] = 'tests';

        foreach ($tests as $test) {
            $main_docs = Test::where('parent_id', $test->id)->select('tests.*', 'test_name as name')->get();
            foreach ($main_docs as $main_doc) {
                $user_document = UserDocument::where([
                    'user_id' => $user->id,
                    'document_type_id' => $main_doc->id,
                    'document_type' => 'tests'
                ])->get();
                $main_doc->documents = $user_document;
            }

            $document_lists['Test']['document_lists'][] = [
                'name' => $test->test_name,
                'id' => $test->id,
                'document_list' => $main_docs
            ];
        }

        $documents = DocumentType::select('document_types.*', 'title as name')->get();
        $document_lists['Document']['group_name'] = 'Mandatory Documents';
        $document_lists['Document']['document_type'] = 'document_types';
        foreach ($documents as $document) {
            $user_document = UserDocument::where([
                'user_id' => $user->id,
                'document_type_id' => $document->id,
                'document_type' => 'document_types'
            ])->get();

            $document->documents = $user_document;

            $document_lists['Document']['document_lists'][] = [
                'name' => $document->title,
                'id' => $document->id,
                'document_list' => [$document]
            ];
        }

        return $document_lists;
    }

    public static function otherDocumentList($user)
    {
        $user_documents = UserDocument::where(['user_id' => $user->id, 'document_type' => 'other'])->get();
        return $user_documents;
    }

    public function getRequiredStudyLevels($application = null)
    {

        $required_levels = [];

        if (is_null($application)) {
            $h_academic = studyLevel::find($this->meta('hightest_level'));
            $require_levels = studyLevel::where('sequence', '>=', $h_academic->sequence)
                ->where('parent_id', 0)
                ->whereNotIn('name', ['Other', 'UG Diploma', 'PG Diploma'])
                ->orderBy('sequence', 'asc')
                ->get();
        } else {
            try {
                $levels = $application->campusProgram->program->programLevel->study_level_id;
            } catch (\Exception $e) {
                $levels = 0;
            }

            $require_levels = studyLevel::whereIn('id', explode(",", $levels))
                ->where('parent_id', 0)
                // ->whereNotIn('name', ['Other', 'UG Diploma', 'PG Diploma'])
                ->orderBy('sequence', 'asc')
                ->get();
        }

        foreach ($require_levels as $require_level) {
            $academic = UserAcademic::where('study_levels_id', $require_level->id)
                ->where('user_id', $this->id)
                ->get();
            if ($academic->count() == 0) {
                $required_levels[] = $require_level;
            }
        }

        return $required_levels;
    }



    public function citizenship()
    {
        return $this->belongsTo('App\Models\Country', 'country');
    }

    public function isCompleted()
    {
        $progress = $this->profileCompleteDetail();
        $is_completed = $progress['general_information']['status']
            && $progress['education_history']['status']
            && $progress['test_scores']['status']
            && $progress['work_experience']['status']
            && $progress['background_information']['status']
            && $progress['upload_documents']['status'];
        return $is_completed;
    }


    public function profileCompleteDetail($application = null)
    {
        $user = $this;
        $profile = [
            'general_information' => ['status' => true],
            'education_history' => ['status' => true, 'require_levels' => []],
            'test_scores' => ['status' => true],
            'work_experience' => ['status' => true],
            'background_information' => ['status' => true],
            'upload_documents' => ['status' => true]
        ];
        $fields = [
            'name', 'last_name', 'email', 'language', 'personal_number', 'gender', 'maritial_status', 'dob', 'country',
            'passport', 'postal', 'address_id'
        ];
        // Check for General information
        foreach ($fields as $field) {
            if ($user->$field == "" || is_null($user->$field)) {
                $profile['general_information']['status'] = false;
            }
        }
        if (!is_null($user->address_id) || $user->address_id != "") {
            $fields = ['country_id', 'state_id', 'city_id', 'address'];
            foreach ($fields as $field) {
                if ($user->address->$field == null) {
                    $profile['general_information']['status'] = false;
                }
            }
        }

        // Check Education history
        // 1. Get highest level of education
        // 2. Check if user have
        $fields = ['country_of_education', 'hightest_level'];
        foreach ($fields as $field) {
            if ($user->meta($field) == '') {
                $profile['education_history']['status'] = false;
            }
        }

        if (!is_null($application)) {
            $required_levels = $user->getRequiredStudyLevels($application);
            if (count($required_levels) > 0) {
                $profile['education_history']['status'] = false;
                $profile['education_history']['require_levels'] = $required_levels;
            }
        } else {
            if ($user->meta('hightest_level') != '') {
                $required_levels = $user->getRequiredStudyLevels($application);
                if (count($required_levels) > 0) {
                    $profile['education_history']['status'] = false;
                    $profile['education_history']['require_levels'] = $required_levels;
                }
            }
        }



        // Check Test Scores
        $test_scores = SpecialTest::where([
            'user_id' => $user->id
        ])->get();
        if ($test_scores->count() == 0) {
            // $profile['test_scores'] = ['status' => false];
        }

        // Check Work Experience
        $work_exps = WorkExperience::where([
            'user_id' => $user->id
        ])->get();
        if ($work_exps->count() == 0) {
            // $profile['work_experience'] = ['status' => false];
        }

        // Check Background Information
        // $fields = ['visa_refusal', 'applied_visa'];
        // foreach ($fields as $field) {
        // 	if ($user->meta($field) == "") {
        //         continue;
        //     }
        // }

        if ($user->meta('visa_refusal') == 1 || $user->meta('applied_visa') == 1) {
            if ($user->meta('visa_refusal_details') == '') {
                $profile['background_information']['status'] = false;
            }
        }

        // Check Documents
        $document_lists = User::uploadDocumentList(auth()->user());
        foreach ($document_lists as $document) {
            foreach ($document['document_lists'] ?? [] as $list) {
                if ($document['document_type'] == 'study_levels' && $list['name'] == 'Other') {
                    continue;
                }
                foreach ($list['document_list'] ?? [] as $list_doc) {
                    if ($list_doc->documents->count() == 0) {
                        $profile['upload_documents']['status'] = false;
                    }
                }
            }
        }
        return $profile;
    }
}
