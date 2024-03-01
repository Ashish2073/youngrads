<?php

namespace App\Http\Controllers\Admin;

// Core
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentApplication;

// Third Party Packages
use Yajra\Datatables\Datatables;

// Events
use App\Events\ApplicationUpdated;

// Models
use App\Models\UserApplication;
use App\Models\ApplicationMessage;
use App\Models\Campus;
use App\Models\Program;
use App\Models\University;
use App\Models\User; 
use App\Models\AddDataLimit;
use App\Models\Admin;
 
class ApplicationController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:admin');
		$this->middleware('userspermission:applications_view',['only'=>['index']]);
	}

	public function index(Request $request)

	{

	
		$breadcrumbs = [
			['link' => "admin.home", 'name' => "Dashboard"], ['name' => 'Application']
		];

		$pageConfigs = [
			//'pageHeader' => false, 
			//'contentLayout' => "content-left-sidebar",
			'bodyClass' => 'chat-application',
			'sidebarCollapsed' => true
		];


		if (auth('admin')->check()) {
			if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
               $userid=auth('admin')->user()->username;
			   $message_status_type="admin_message_status";
			   $role="admin" ;



			}elseif(in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))){
				$userid=auth('admin')->user()->username;
				$message_status_type="moderator_message_status";
				$role="moderator";
			}else{
				$userid=0;
				$message_status_type="moderator_message_status";
			}
			
		} elseif(auth('web')->check()) {
			$userid=Auth::id();
			$message_status_type="user_message_status";
			
			$role="user";
		}


		   


			if((session()->has('used_campus_program'))){
		     $usedCampusProgram=session()->get('used_campus_program'); 
			
			
			$usedCampusProgramUniversityId=$usedCampusProgram[0];
			$usedCampusProgramCampusId=$usedCampusProgram[1];
			$usedCampusProgramId=$usedCampusProgram[2];


			$userApplications = UserApplication::join('users', 'users_applications.user_id', '=', 'users.id')
			->leftJoin('admins','users.moderator_id','=','admins.id')
			->join('campus_programs', 'users_applications.campus_program_id', '=', 'campus_programs.id')
			->join('intakes', 'users_applications.intake_id', '=', 'intakes.id')
			->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			->join('programs', 'campus_programs.program_id', "=", 'programs.id')
			->join('universities', 'campus.university_id', '=', 'universities.id')
			->select('intakes.name as intake','admins.username as moderator_username','users_applications.admin_status', 'status', 'users_applications.application_number', 'users_applications.year', 'users_applications.created_at as apply_date', 'users.name as first', 'users.last_name as last_name', 'campus.name as campus', 'universities.name as university', 'users_applications.id as application_id', 'programs.name as program', 'users_applications.user_id as user_id', 'users_applications.is_favorite as favorite',
			DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid. "'  && application_message.$message_status_type = 'unread' && application_id = users_applications.id && message_scenario='0')) as count"),
			DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid  . "' && application_message.$message_status_type = 'unread' && application_id = users_applications.id && message_scenario='1' )) as moderatortoadmincount"))
			->where('campus.university_id','=',$usedCampusProgramUniversityId)
			->where('campus_programs.campus_id','=',$usedCampusProgramCampusId)
			->where('campus_programs.program_id','=',$usedCampusProgramId)
			->groupBy('users_applications.id');
			    
		    

			}else{

			
				$userApplications = UserApplication::join('users', 'users_applications.user_id', '=', 'users.id')
				->leftJoin('admins','users.moderator_id','=','admins.id')
				->join('campus_programs', 'users_applications.campus_program_id', '=', 'campus_programs.id')
				->join('intakes', 'users_applications.intake_id', '=', 'intakes.id')
				->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
				->join('programs', 'campus_programs.program_id', "=", 'programs.id')
				->join('universities', 'campus.university_id', '=', 'universities.id')
				->select('intakes.name as intake','admins.username as moderator_username', 'users_applications.admin_status', 'status', 'users_applications.application_number', 'users_applications.year', 'users_applications.created_at as apply_date', 'users.name as first', 'users.last_name as last_name', 'campus.name as campus', 'universities.name as university', 'users_applications.id as application_id', 'programs.name as program', 'users_applications.user_id as user_id', 'users_applications.is_favorite as favorite',
				 DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.$message_status_type = 'unread' && application_id = users_applications.id && message_scenario='0')) as count"),
				 DB::raw("(SELECT count(*) FROM application_message WHERE (application_message.user_id != '" . $userid . "' && application_message.$message_status_type = 'unread' && application_id = users_applications.id && message_scenario='1')) as moderatortoadmincount"))
				 
				 
			    ->groupBy('users_applications.id');

				

			}
			
		
			
	
			




			

		if ($request->has('program')) {
			$userApplications->whereIn('programs.id', $request->program);
		} 

		if ($request->has('university')) {
			$userApplications->whereIn('universities.id', $request->university);
		}

		if ($request->has('campus')) {
			$userApplications->whereIn('campus.id', $request->campus);
		}

		if ($request->has('status')) {
			$userApplications->whereIn('users_applications.status', $request->status);
		}

		if ($request->has('moderator_id') || in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))) {
			 
			if(isset($request->moderator_id)){ 
				
				$userApplications->whereIn('admins.id',$request->moderator_id );
			}else{
			
				$userApplications->whereIn('admins.id',[auth('admin')->user()->id] );
			}
		
		}

		if(isset($request->application_id)){
           $userApplications->whereIn('users_applications.id',$request->application_id);
			

		}

		switch (request()->get('view')) {
			case UserApplication::INACTIVE:
				$userApplications->where('admin_status', UserApplication::INACTIVE);
				break;

			case 'favourite':
				$userApplications->where('admin_status', UserApplication::ACTIVE)->where('is_favorite', 1);
				break;

			default:
				$userApplications->where('admin_status', UserApplication::ACTIVE);
		}






		//  $result =  $userApplications->get();application_moderator_admin_id
		if (request()->ajax()) {
			session()->forget('application_id_message');

			session()->forget('application_moderator_admin_id');
			
			return Datatables::of($userApplications)

				->editColumn('university', function ($row) {
				
					return tooltip(Str::limit($row->university, 25, '...'), $row->university);
				})
				->editColumn('program', function ($row) {
					return tooltip(Str::limit($row->program, 25, '...'), $row->program);
				})
				->editColumn('campus', function ($row) {
					return tooltip(Str::limit($row->campus, 25, '...'), $row->campus);
				})
				->editColumn('apply_date', function ($row) {
					return date('d M Y', strtotime($row->apply_date));
				})
				->editColumn('year', function ($row) {
					return $row->intake . "-" . $row->year;
				})
				->editColumn('application_number', function ($row) {
					return $row->application_number ?? "N/A";
				})
				->addColumn('moderator_username', function ($row) {
					return $row->moderator_username ?? "N/A";
				})
				->addColumn('toggle_status', function ($row) {
					if ($row->admin_status == UserApplication::ACTIVE) {
						$color = "danger";
						$text = ucfirst(UserApplication::INACTIVE);
					}
					if ($row->admin_status == UserApplication::INACTIVE) {
						$color = "success";
						$text = ucfirst(UserApplication::ACTIVE);
					}
					session()->forget('used_campus_program');

					return "<button class='btn btn-$color application-toggle-status btn-icon btn-round' data-id={$row->application_id}>
						Make {$text}
					</button>";
				})
				->editColumn('favorite', function ($row) {

					if ($row->favorite == 0) {
						$html = "<button class='btn favorite' data-id='" . $row->application_id . "'>";
						$html .= "<i class='feather icon-heart pink text-danger'></i>";
						$html .= "</button>";
					} else {
						$html = "<button class='btn favorite' data-id='" . $row->application_id . "'>";
						$html .= "<i class='fa fa-heart  text-danger'></i>";
						$html .= "</button>";
					}

					return $html;
				}) 
				->addColumn('name', function ($row) {
					return "<a href='javascript:void(0)' class='profile' data-id='" . $row->user_id . "' data-application='" . $row->application_id . "'>" . $row->first . " " . $row->last_name . "</a>";
				})
				->editColumn('count', function ($row) {

					$html = "<button class='btn btn-icon btn-outline-primary admin-message' data-id='" . $row->application_id . "' data-toggle='modal' data-target='#dynamic-modal' >";
					// $html .= "<i class='ficon feather icon-bell'></i><span
					// class='badge badge-pill badge-primary badge-up'>5</span>";
	
					$html .= "<i class='ficon feather icon-message-circle'></i>";
					// $row->count = 5;
					if ($row->count > 0) {
						$html .= "<span class= 
            badge badge-pill badge-default badge-up'>$row->count</span>";
					}
					$html .= "</button>";
					// return $html;
					if ($row->count > 0) {
						$count = '<span class="badge badge-pill badge-danger badge-sm badge-up">' . $row->count . '</span>';
					} else {
						$count = '';
					}
					$html = '<div class="avatar bg-primary admin-message" data-custom="0" data-id="' . $row->application_id . '" data-toggle="modal" data-target="#dynamic-modal">
            <div class="avatar-content position-relative">
              <i class="avatar-icon feather icon-message-circle"></i>
              ' . $count . '
            </div>
          </div>';
					return $html;
				})
				//  ->editColumn('count',function($row){
				//     return $row->count;

				//  })

				->editColumn('moderatortoadmincount', function ($row) {

					$html = "<button class='btn btn-icon btn-outline-primary admin-message' data-id='" . $row->application_id . "' data-toggle='modal' data-target='#dynamic-modal' >";
					// $html .= "<i class='ficon feather icon-bell'></i><span
					// class='badge badge-pill badge-primary badge-up'>5</span>";
	
					$html .= "<i class='ficon feather icon-message-circle'></i>";
					// $row->count = 5;
					if ($row->moderatortoadmincount > 0) {
						$html .= "<span class=
            badge badge-pill badge-default badge-up'>$row->moderatortoadmincount</span>";
					}
					$html .= "</button>";
					// return $html;
					if ($row->moderatortoadmincount > 0) {
						$count = '<span class="badge badge-pill badge-danger badge-sm badge-up">' . $row->moderatortoadmincount . '</span>';
					} else {
						$count = '';
					}
					$html = '<div class="avatar bg-primary admin-message" data-custom="1" data-id="' . $row->application_id . '" data-toggle="modal" data-target="#dynamic-modal">
            <div class="avatar-content position-relative">
              <i class="avatar-icon feather icon-message-circle"></i>
              ' . $count . '
            </div>
          </div>';
					return $html;
				})





				->editColumn('status', function ($row) {

					if ($row->status == 'pending') {
						$class = 'badge-warning';
					} elseif ($row->status == 'open') {
						$class = 'badge-success';
					} elseif ($row->status == "close") {
						$class = 'badge-danger';
					} else {
						$class = 'badge-success';
					}
					$status_meta = config('setting.application.status_meta.' . $row->status);
					$class = "badge-" . $status_meta['color'];
					$icon = $status_meta['icon_class'];
	 				return "<span class='p-50 badge $class font-weight-bold status' data-id='" . $row->application_id . "' data-toggle='modal' data-target='#apply-model'><i class='$icon'></i> " . config('setting.application.status')[$row->status] . "</span>";
				})
				->rawColumns(['favorite', 'toggle_status', 'apply_date', 'name', 'message', 'status', 'campus','moderatortoadmincount','university','moderator_username', 'program', 'count', 'delete'])
				->make(true);
		} else {
			$univs = University::all();
			$programs = Program::all();
			$campuses = Campus::all();
			$limitApplyApplication=AddDataLimit::where('model_name','App/Model/AddDataLimit')->where('action','create')->select('count')->get();
			
			if(!in_array('Admin', json_decode(auth('admin')->user()->getRoleNames()))){
			$moderator=Admin::select('id','username')->where('username',auth()->guard('admin')->user()->username)->role('moderator')->get();

		}else{
			$moderator=Admin::select('id','username')->role('moderator')->get();


		}

		$application_numbers=UserApplication::select('id','application_number')->get();

 


			return view('dashboard.applications.index', compact('breadcrumbs', 'pageConfigs', 'univs', 'programs', 'campuses','limitApplyApplication','moderator','application_numbers'));
		}
	}

	public function applicationMessage(Request $request,$id)
	{
		if (auth('admin')->check()) {
			if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
               $userid=auth('admin')->user()->username;
			   $message_status_type="admin_message_status";
			   $role="admin" ;
			   $messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $id)->update(['admin_message_status' => 'read']);
			
			   activity('Read ApplicationMessage')  
				->causedBy(Auth::guard('admin')->user())
				->withProperties(['ip' => $request->ip()])
				->log('Read ApplicationMessage');
			
			
			
			
			
			
			
			}else{
				$userid=auth('admin')->user()->username;
				$message_status_type="moderator_message_status";
				$role="moderator";
				$messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $id)->update(['moderator_message_status' => 'read']);
			
				
				activity('Read ApplicationMessage')  
				->causedBy(Auth::guard('admin')->user())
				->withProperties(['ip' => $request->ip()])
				->log('Read ApplicationMessage');
			
			
			
			
			}
			
		} elseif(auth('web')->check()) {
			$userid=Auth::id();
			$message_status_type="user_message_status";
			
			$role="user";

			activity('Read ApplicationMessage')  
				->causedBy(Auth::guard('web')->user())
				->withProperties(['ip' => $request->ip()])
				->log('Read ApplicationMessage');
			$messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $id)->update(['user_message_status' => 'read']);
		}
		



		

		return view('application_message.index', ['id' => $id, 'gaurd' => 'admin', 'auth' =>$userid ]);
	}

	public function status($id)
	{
		$application = UserApplication::find($id);
		return view('dashboard.applications.status', compact('id', 'application'));
	}

	public function updateStatus(Request $request)
	{
		$userApplication = UserApplication::find($request->id);
		$old_status = $userApplication->status;
		$userApplication->status = $request->status;
		if ($userApplication->save()) { 

		

			if ($request->status != $old_status) {
				$userApplication->createActivity();
			}
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Application status has been changed',
				'success' => true
			]);
		} else {
			return response()->json([
				'code' => 'error',
				'title' => 'error',
				'message' => 'Application status has been changed',
				'success' => false
			]);
		}
	}

	public function test()
	{
		return $userApplications = UserApplication::join('users', 'users_applications.user_id', '=', 'users.id')
			->join('campus_programs', 'users_applications.campus_program_id', '=', 'campus_programs.id')
			->join('intakes', 'users_applications.intake_id', '=', 'intakes.id')
			->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			->join('programs', 'campus_programs.program_id', "=", 'programs.id')
			->join('universities', 'campus.university_id', '=', 'universities.id')
			->select('intakes.name as intake', 'status', 'users_applications.created_at as apply_date', 'users.name as first', 'users.last_name as last_name', 'campus.name as campus', 'universities.name as university', 'users_applications.id as application_id', 'programs.name as program', 'users_applications.user_id as user_id', DB::raw("(SELECT count(*) FROM application_message WHERE user_id != " . Auth::id() . " && message_status = 'unread' && application_id = users_applications.id ) as count"))->toSql();
	}

	function setFavorite(Request $request)
	{
		$application = UserApplication::find($request->id);
		$application->is_favorite = $request->favorite;
		if ($application->save()) {

			//  if($application->is_favorite == 0){
			//     return response()->json([
			//       'code' => 'success',
			//       'title' => 'Congratulations',
			//       'message' => 'Application is set unfavorite',
			//       'success' => true
			//   ]);
			//  }else{
			//     return response()->json([
			//       'code' => 'success',
			//       'title' => 'Congratulations',
			//       'message' => 'Application Application is set favorite',
			//       'success' => true
			//   ]);
			//  }

		}
	}

	function favoriteApplicatons(Request $request)
	{
		$breadcrumbs = [
			['link' => "admin.applications-all", 'name' => "Applications"], ['name' => 'Favorite Applications']
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
			->join('programs', 'campus_programs.program_id', "=", 'programs.id')
			->join('universities', 'campus.university_id', '=', 'universities.id')
			->select('intakes.name as intake', 'status', 'users_applications.created_at as apply_date', 'users.name as first', 'users.last_name as last_name', 'campus.name as campus', 'universities.name as university', 'users_applications.id as application_id', 'programs.name as program', 'users_applications.user_id as user_id', 'users_applications.is_favorite as favorite', DB::raw("(SELECT count(*) FROM application_message WHERE application_message.user_id != '" . Auth::id() . "' && application_message.message_status = 'unread' && application_id = users_applications.id) as count"))->where('is_favorite', 1)
			->groupBy('users_applications.id');

		if (isset($request->program)) {
			$userApplications->where('programs.id', '=', $request->program);
		}

		if (isset($request->university)) {
			$userApplications->where('universities.id', '=', $request->university);
		}

		if (isset($request->campus)) {
			$userApplications->where('campus.id', '=', $request->campus);
		}

		//  $result =  $userApplications->get();
		if (request()->ajax()) {
			return Datatables::of($userApplications)

				->editColumn('university', function ($row) {
					return tooltip(Str::limit($row->university, 25, '...'), $row->university);
				})
				->editColumn('program', function ($row) {
					return tooltip(Str::limit($row->program, 25, '...'), $row->program);
				})
				->editColumn('campus', function ($row) {
					return tooltip(Str::limit($row->campus, 25, '...'), $row->campus);
				})
				->editColumn('apply_date', function ($row) {
					return date('d M Y h:i A', strtotime($row->apply_date));
				})
				->editColumn('favorite', function ($row) {

					if ($row->favorite == 0) {
						$html = "<button class='btn favorite' data-id='" . $row->application_id . "'>";
						$html .= "<i class='feather icon-heart pink text-danger'></i>";
						$html .= "</button>";
					} else {
						$html = "<button class='btn favorite' data-id='" . $row->application_id . "'>";
						$html .= "<i class='fa fa-heart  text-danger'></i>";
						$html .= "</button>";
					}

					return $html;
				})
				->addColumn('name', function ($row) {
					return "<a href='javascript:void(0)' class='profile' data-id='" . $row->user_id . "' data-application='" . $row->application_id . "'>" . $row->first . " " . $row->last_name . "</a>";
				})
				->editColumn('count', function ($row) {

					$html = "<button class='btn admin-message' data-id='" . $row->application_id . "' data-toggle='modal' data-target='#dynamic-modal' >";
					// $html .= "<i class='ficon feather icon-bell'></i><span
					// class='badge badge-pill badge-primary badge-up'>5</span>";
	
					$html .= "<i class='ficon feather icon-message-circle'></i>";
					if ($row->count > 0) {
						$html .= "<span class=
            badge badge-pill badge-default badge-up'>$row->count</span>";
					}
					$html .= "</button>";
					return $html;
				})
				//  ->editColumn('count',function($row){
				//     return $row->count;

				//  })
				->editColumn('status', function ($row) {

					if ($row->status == 'pending') {
						$class = 'badge-warning';
					} elseif ($row->status == 'open') {
						$class = 'badge-info';
					} else {
						$class = 'badge-success';
					}

					return "<span class='badge $class status' data-id='" . $row->application_id . "' data-toggle='modal' data-target='#apply-model'>" . ucfirst($row->status) . "</span>";
				})
				->rawColumns(['favorite', 'apply_date', 'name', 'message', 'status', 'campus', 'university', 'program', 'count'])
				->make(true);
		} else {
			return view('dashboard.applications.favorite', compact('breadcrumbs', 'pageConfigs'));
		}
	}

	function setPriority(Request $request)
	{
		//  echo $request->id."-".$request->priority;
		$application = UserApplication::find($request->id);
		$application->priority = $request->priority;
		if ($application->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Application priority is set',
				'success' => true
			]);
		}
	}

	function destroy($id)
	{
		$application = UserApplication::find($id);
		$application->delete();
		if ($application->save()):
			return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Application deleted successfully'
			]);
		endif;
	}

	public function toggleAdminStatus($id)
	{
		$application = UserApplication::findOrFail($id);

		if ($application->admin_status == UserApplication::ACTIVE) {
			$application->admin_status = UserApplication::INACTIVE;
		} else {
			$application->admin_status = UserApplication::ACTIVE;
		}


		if ($application->save()) {
			return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Success!',
				'message' => 'Operation performed successfully.'
			]);
		} else {
			return response()->json([
				'success' => false,
				'code' => 'danger',
				'title' => 'Oops!',
				'message' => 'Something went wrong.'
			]);
		}

	}
	
	
	public function applicationallow(Request $request){
		// AddDataLimit
		$userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? [];
		if(hasPermissionForRoles('application_apply_limit_add', $userrole) || auth('admin')->user()->getRoleNames()[0] == 'Admin'){
		$validator = Validator::make($request->all(), [
           
            'count' => ['required','numeric','min:0']
            
        ]);

		if ($validator->fails()) { 
            $errors = $validator->errors(); 

			

         
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        }

		$DataLimit=AddDataLimit::updateOrCreate(['model_name'=>'App/Model/AddDataLimit'],[
			'action'=>'create','count'=>$request->count
		]);


	   return response()->json([
			'Data'=>json_decode($DataLimit,true)
		]);

	    }else{

		
			return response()->json([
				'authorization'=>false,
				'message' => 'You Have Not Permission',
				'errors'=>"You have not permission",


			],422);
			
	      }

		// if ($DataLimit->wasRecentlyCreated) {
		// 	// The record was just created
		// 	return "Number of Application  was created.";
		// } else {
		// 	// The record was updated
		// 	return "Record was updated.";
		// }


 
 
	}

	public function get_student_application_data()
    {
        return Excel::download(new StudentApplication, 'studentsapplication.xlsx');
    }
	
	
	
	
}
