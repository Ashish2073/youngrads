<?php

namespace App\Http\Controllers;

// Core
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Rules\UserApplicationAuthentication;
use Illuminate\Support\Facades\DB;
use App\Models\AddDataLimit;

// Notifications
use App\Notifications\Application;

// Events
use App\Events\ApplicationUpdated;

// Third Party Packages
use Yajra\Datatables\Datatables;

// Models
use App\Models\CampusProgram;
use App\Models\UserApplication;
use App\Models\Admin;
use App\Models\ApplicationTimeline;
use App\Models\Intake;
use App\Models\User;

class UserApplicationController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index($id, Request $request)
	{
		$intakes = CampusProgram::join('campus_program_intakes', 'campus_programs.id', '=', 'campus_program_intakes.campus_program_id')
			->join('intakes', 'campus_program_intakes.intake_id', '=', 'intakes.id')
			->select('intakes.id as id', 'intakes.name as name')
			->where('campus_programs.id', $id)
			// ->groupBy('campus_programs.id')
			->distinct('intakes.id')
			->get();

		$intakeIds = UserApplication::where('user_id', '=', Auth::id())->where('campus_program_id', '=', $id)->get();
		$userIntakeIds = [];
		$programIntakes = [];
		foreach ($intakeIds as $intakeId) {
			$userIntakeIds[] = $intakeId->intake_id;
		}

		foreach ($intakes as $intake) {
			$programIntakes[] = $intake->id;
		}

		$request->session()->put('id', $id);

		// if ($userIntakeIds == $programIntakes) {
		//   return view('application.already_apply');
		// } else {
		return view('application.apply', ['id' => $id, 'intakes' => $intakes]);
	}




	public function store(Request $request)
	{
         $dataCount=AddDataLimit::where('model_name','App/Model/AddDataLimit')->where('action','create')->select('count')->get();
		$countData=json_decode($dataCount,true)[0]['count'];
		
		$validator = Validator::make($request->all(), [
            'year' => ['required', new UserApplicationAuthentication($countData)],
            'intake' => ['required',new UserApplicationAuthentication($countData)],
            
        ]);

		if ($validator->fails()) { 
            $errors = $validator->errors();

         
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        }






		$intakes = CampusProgram::join('campus_program_intakes', 'campus_programs.id', '=', 'campus_program_intakes.campus_program_id')
			->join('intakes', 'campus_program_intakes.intake_id', '=', 'intakes.id')
			->select('intakes.id as id', 'intakes.name as name')
			->where('campus_programs.id', $request->session()->get('id'))->get();

		$id = $request->session()->get('id');



		

		$intakeIds = UserApplication::where('user_id', '=', Auth::id())->select('intake_id')->get();
		$intake = $request->intake;
		$year = $request->year;

		$intakeRecord = UserApplication::where('user_id', '=', Auth::id())->where('intake_id', '=', $intake)->where('year', '=', $year)->where('campus_program_id', '=', $id)->get();

		foreach ($intakeIds as $intakeId) {
			$userIntakeIds[] = $intakeId->intake_id;
		}
		$validator = Validator::make($request->all(), ['intake' => ['required', function ($attribute, $value, $fail) use ($intakeRecord, $request) {
			if ($intakeRecord->count() > 0) {
				$application_url = route('applications') . "?id=" . $intakeRecord[0]->campus_program_id . "&intake=" . $request->intake . "&year=" . $request->year;
				$fail('You have already applied for the selected Year & Intake. <a href="' . $application_url . '">Click here</a> to check your application ');
			}
		},], 'year' => 'required',]);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('application.apply', compact('id', 'intakes', 'userIntakeIds'))->withErrors($validator);
		}

		$userApplication = new UserApplication;
		$userApplication->user_id = Auth::id();
		$userApplication->intake_id = $request->intake;
		$userApplication->year = $request->year;
		$userApplication->campus_program_id = $id;
		$userApplication->application_number = UserApplication::getNextApplicationNumber();
		$userApplication->status = UserApplication::PENDING;
		$userApplication->admin_status = UserApplication::ACTIVE;

		if ($userApplication->save()) {
			$userApplication->createActivity();
			//Notification::route('mail', config('setting.AdminEmail'))->notify(new Application);
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Your application created successfully.',
				'success' => true,
				'application_url' => route('application', $userApplication->id)
			]);
		} else {
			return view('application.apply', compact('id', 'intakes'));
		}
	}

	function allApplication(Request $request)
	{

		$breadcrumbs = [
			['link' => "my-account", 'name' => "Dashboard"], ['name' => 'Application']
		];

		$pageConfigs = [
			//'pageHeader' => false,
			//'contentLayout' => "content-left-sidebar",
			'bodyClass' => 'chat-application',
		];

		$userApplications = UserApplication::join('users', 'users_applications.user_id', '=', 'users.id')
			->join('campus_programs', 'users_applications.campus_program_id', '=', 'campus_programs.id')
			->join('intakes', 'users_applications.intake_id', '=', 'intakes.id')
			->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			->join('universities', 'campus.university_id', '=', 'universities.id')
			->join('programs', 'campus_programs.program_id', "=", 'programs.id');
		$userApplications->select('intakes.name as intake', 'users_applications.campus_program_id', 'users_applications.application_number', 'users_applications.id', 'users_applications.year', 'status', 'users_applications.created_at as apply_date', 'users.name as first', 'users.last_name as last_name', 'campus.name as campus', 'universities.name as university', 'users_applications.id as application_id', 'programs.name as program', DB::raw("(SELECT count(*) FROM application_message WHERE application_message.user_id != '" . Auth::id() . "' && application_message.message_status = 'unread' && application_id = users_applications.id) as count"))->where('users_applications.user_id', '=', Auth::id());

		$programs = clone ($userApplications);
		$programs = $programs->groupBy('users_applications.campus_program_id')->get();

		if (isset($request->id)) {
			$userApplications->where('users_applications.campus_program_id', '=', $request->id);
		}

		if (isset($request->intake)) {
			$userApplications->where('users_applications.intake_id', '=', $request->intake);
		}

		if (isset($request->year)) {
			$userApplications->where('users_applications.year', '=', $request->year);
		}

		$result = $userApplications->get();

		$intakes = Intake::join('users_applications', 'users_applications.intake_id', '=', 'intakes.id')
			->where('user_id', auth()->user()->id)
			->groupBy('users_applications.intake_id')->get();
		$years = UserApplication::where('user_id', auth()->user()->id)->groupBy('year')->get();


		if (request()->ajax()) {
			return Datatables::of($userApplications)

				->editColumn('university', function ($row) {
					return tooltip(Str::limit($row->university, 40, '...'), $row->university);
				})
				->editColumn('program', function ($row) {
					return tooltip(Str::limit($row->program, 40, '...'), $row->program);
				})
				->editColumn('campus', function ($row) {
					return tooltip(Str::limit($row->campus, 40, '...'), $row->campus);
				})
				->editColumn('apply_date', function ($row) {
					return date('d M Y', strtotime($row->apply_date));
				})
				->editColumn('year', function ($row) {
					return $row->intake . " - " . $row->year;
				})
				->editColumn('status', function ($row) {
					$status_meta = config('setting.application.status_meta.' . $row->status);
					$class = "badge-" . $status_meta['color'];
					$icon = $status_meta['icon_class'];
					return "<span class='p-50 badge $class font-weight-bold status' data-id='" . $row->application_id . "'><i class='$icon'></i> " . config('setting.application.status')[$row->status] . "</span>";
				})
				->editColumn('id', function ($row) {
					$url = route('application', $row->id);
					if ($row->status == UserApplication::ARCHIVE) {
						return "N/A";
					}
					return "<a href='{$url}'>View</a>";
				})

				->editColumn('count', function ($row) {

					$html = "";
					if ($row->status == UserApplication::ARCHIVE) {
						$html .= "<button data-msg='Are you sure want to recover this application?' title='Recover Application' data-url='" . route('recover-application', $row->application_id) . "' class='btn btn-sm btn-icon btn-outline-danger  mr-1 action-application' data-id='" . $row->application_id . "'><i class='fa fa-undo'></i></button>";
					} else {
						$html .= "<button data-msg='Are you sure want to archive this application?' title='Archive Application' data-url='" . route('archive-application', $row->application_id) . "' class='btn btn-sm btn-icon btn-outline-danger  mr-1 action-application' data-id='" . $row->application_id . "'><i class='fa fa-archive'></i></button>";
					}

					if ($row->status != UserApplication::ARCHIVE) {
						if ($row->count > 0) {
							$count = '<span class="badge badge-pill badge-danger badge-sm badge-up">' . $row->count . '</span>';
						} else {
							$count = '';
						}
						$html .= '<div class="avatar bg-primary user-message" data-id="' . $row->application_id . '" data-toggle="modal" data-target="#dynamic-modal">
							<div class="avatar-content position-relative">
							<i class="avatar-icon feather icon-message-circle"></i>
							' . $count . '
							</div>
						</div>';
					}
					return $html;
				})
				->rawColumns(['apply_date', 'name', 'id', 'action', 'status', 'university', 'program', 'campus', 'count', 'programs', 'intakes', 'years'])
				->make(true);
		} else {
			return view('application.index', compact('breadcrumbs', 'pageConfigs', 'intakes', 'years', 'programs'));
		}
	}

	public function show($id)
	{
		$application = UserApplication::findOrFail($id);
		if ($application->status == UserApplication::ARCHIVE) {
			abort(403, "This application is archived.");
		}

		if ($application->user_id != auth()->user()->id) {
			abort(403, "Access Denied");
		}

		$pageConfigs = [
			'bodyClass' => 'chat-application',
		];
		$breadcrumbs = [
			['link' => "my-account", 'name' => "Dashboard"], ['link' => 'applications', 'name' => 'Applications'], ['name' => $application->application_number]
		];

		return view('application.show', compact('application', 'pageConfigs', 'breadcrumbs'));

	}

	function removeApplication(Request $request)
	{
		$deleteApplication = UserApplication::find($request->id)->delete();
		if ($deleteApplication) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Applied course has been deleted',
				'success' => true,
			]);
		} else {
			return response()->json([
				'code' => 'error',
				'title' => 'Oops',
				'message' => 'Something went wrong',
				'success' => false
			]);
		}
	}

	public function archive(Request $request, $id)
	{
		$application = UserApplication::findOrFail($id);
		$application->status = UserApplication::ARCHIVE;
		if ($application->save()) {
			$application->createActivity();
			return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Success',
				'message' => 'Application archived successfully'
			]);
		} else {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Error',
				'message' => 'Something went wrong'
			]);
		}
	}

	public function recover(Request $request, $id)
	{
		$application = UserApplication::findOrFail($id);

		$timeline = ApplicationTimeline::where('application_id', $id)->orderBy('created_at', 'desc')->skip(1)->limit(2)->get();
		if ($timeline->count() == 0) {
			$status = UserApplication::PENDING;
		} else {
			$status = $timeline[0]->status;
		}

		$application->status = $status;

		if ($application->save()) {
			$application->createActivity();
			return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Success',
				'message' => 'Application recovered successfully'
			]);
		} else {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Error',
				'message' => 'Something went wrong'
			]);
		}
	}

	public function documentView($id)
	{
		$application = UserApplication::findOrFail($id);
		$user = auth()->user();

		// Get Program Level of Application
		try {
			$program_level = $application->campusProgram->program->programLevel;
			config(['documents' => User::uploadDocumentList($user, $program_level->study_level_id)]);
			config(['other_documents' => User::otherDocumentList($user)]);
		} catch (\Exception $e) {
			config(['documents' => []]);
			config(['other_documents' => []]);
		}


		return view('application.documents', compact('application'));
	}

	public function submitApplication(Request $request)
	{
		if (!request()->has('application_id')) {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Error',
				'message' => 'Invalid Request, Please try again.'
			]);
		}

		$application = UserApplication::where(['id' => request()->get('application_id'), 'user_id' => auth()->user()->id])->first();

		if (is_null($application)) {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Access Denied',
				'message' => 'You are not allowed to perform this operation.'
			]);
		}

		if ($application->status == UserApplication::SUBMITEED_TO_YGRAD) {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Already Submitted',
				'message' => 'You have already submitted the application.'
			]);
		}

		if ($application->status != UserApplication::PENDING && $application->status != UserApplication::APPLICANT_ACTION_REQUIRED) {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Access Denied',
				'message' => 'You are not allowed to perform this operation.'
			]);
		}

		$progress_detail = auth()->user()->profileCompleteDetail($application);


		if (!$application->isCompleted($progress_detail)) {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Incomplete Profile',
				'message' => 'Please complete your profile to submit an application',

			]);
		}

		$application->status = UserApplication::SUBMITEED_TO_YGRAD;
		$application->admin_status = UserApplication::ACTIVE;

		if ($application->save()) {
			$application->createActivity();
			Admin::find(1)->notify(new Application());
			return response()->json([
				'success' => true,
				'submitted' => true,
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Your application has been submitted successfully'
			]);
		} else {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Error',
				'message' => 'Something went wrong.'
			]);
		}
	}
}
