<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Authorizable;
use App\Models\City;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\UserMeta;
use App\Models\SpecialTest;
use App\Models\State;
use App\Models\Test;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use App\Models\UserAcademic;
use App\Models\UserApplication;
use App\Models\UserDocument;
use App\Models\UserShortlistProgram;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentData;
use Carbon\Carbon;
use Auth; 

class StudentController extends Controller
{
	// use Authorizable; 

	public function __construct()
	{
		$this->middleware('auth:admin')->except('profileResume');  
		$this->middleware('userspermission:students_view',['only'=>['index']]);  
	}

	public function index(Request $request)
	{
	 
		if (request()->ajax()) {
			
			$userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; 
		  
				// if((($request->get('id')!=null) && $request->get('email')==null && $request->get('personal_number')==null)){
				// 	$users=User::whereIn('id',$request->get('id'))->get();

				// }elseif((($request->get('email')!=null) && $request->get('id')==null && $request->get('personal_number')==null)){
				// 	$users=User::whereIn('email',$request->get('email'))->get();
				// }elseif((($request->get('personal_number')!=null) && $request->get('id')==null && $request->get('email')==null)){
				// 	$users=User::whereIn('personal_number',$request->get('personal_number'))->get();
					
				// }elseif((($request->get('personal_number')!=null) && $request->get('id')!=null && $request->get('email')==null)){
				// 	$users=User::whereIn('personal_number',$request->get('personal_number'))->whereIn('id',$request->get('id'))->get();
				// }elseif((($request->get('personal_number')==null) && $request->get('id')!=null && $request->get('email')!=null)){
				// 	$users=User::whereIn('email',$request->get('email'))->whereIn('id',$request->get('id'))->get();
				// }elseif((($request->get('personal_number')!=null) && $request->get('id')==null && $request->get('email')!=null)){
				// 	$users=User::whereIn('email',$request->get('email'))->whereIn('id',$request->get('id'))->get(); 
				// }
				
				// else{
				// 	$users=User::all();
				// }
				     
			      if(($request->get('email')!=null)||($request->get('id')!=null)||($request->get('personal_number')!=null)||($request->get('moderator_filter_id')!=null)||session()->has('used_modifier') || $request->get('scenario')){

			
				 
			    //    $users=User::select('id','name','last_name','email','personal_number','passport','dob')->whereIn('id',($request->get('id')!=null)?($request->get('id')):[DB::raw('id')])
				//    ->whereIn('email',($request->get('email')!=null)?($request->get('email')):[DB::raw('email')])
				// ->whereIn('personal_number',($request->get('personal_number')!=null)?($request->personal_number):[(DB::raw('personal_number'))])
				//    ->get();

			 

				   $usersdata = User::leftJoin('admins','admins.id','=','users.moderator_id')->select('users.id as id', 'users.name as name', 'users.last_name as last_name','users.moderator_id as moderator_id', 'users.email as email', 'users.personal_number as personal_number', 'users.passport as passport', 'users.dob as dob','admins.username as moderator_username','users.created_at as created_at')
                             ->whereIn('users.id', ($request->get('id') != null) ? $request->get('id') : [DB::raw('users.id')])
                             ->whereIn('users.email', ($request->get('email') != null) ? $request->get('email') : [DB::raw('users.email')])
						     ->where(function ($query) use ($request) {
								if(($request->has('personal_number'))){
									$query->whereIn('users.personal_number', $request->get('personal_number'));
								}else{
									$query->whereIn('users.personal_number', [DB::raw('users.personal_number')])
									->orWhereNull('users.personal_number');
								}

                              })->where(function ($query) use ($request) {
								
								if(($request->has('moderator_filter_id')) || session()->has('used_modifier') ){ 

									

									if($request->get('moderator_filter_id')!=[0] || session()->has('used_modifier') ){
                                          
										$id=$request->get('moderator_filter_id')??[session()->get('used_modifier')];

										session()->forget('used_modifier');
									
										
									
										$query->whereIn('users.moderator_id', $id)->orWhereNull('users.moderator_id');
								  }else{
								
									 $query->whereNull('users.moderator_id');
								}
									
								}else{
									$query->whereIn('users.moderator_id',[DB::raw('users.moderator_id')])
									->orWhereNull('users.moderator_id');
								}

                              });
                          

				if($request->get('scenario')=="weeklydata"){

					$startOfWeek = Carbon::now()->startOfWeek();
                     $endOfWeek = Carbon::now()->endOfWeek();
					 $users =   $usersdata->whereBetween('users.created_at', [$startOfWeek, $endOfWeek])->get();


				}elseif($request->get('scenario')=="monthlydata"){
					$startOfMonth = Carbon::now()->startOfMonth();
					$endOfMonth = Carbon::now()->endOfMonth();
					 $users =   $usersdata->whereBetween('users.created_at', [$startOfMonth, $endOfMonth])->get();
				}elseif($request->get('scenario')=="dailydata"){

					$currentDate = Carbon::now()->toDateString();
					$users =   $usersdata->whereDate('users.created_at', $currentDate )->get();
				}else{
					$users =   $usersdata->get();
				}	  




							



						


				  }else{ 

				
					if(in_array('Admin',$userrole) || (!in_array('supermoderator',$userrole) && !in_array('moderator',$userrole))){
					$users=User::leftJoin('admins','admins.id','=','users.moderator_id')
					->select('users.id as id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.personal_number as personal_number', 'users.passport as passport', 'users.dob as dob','admins.username as moderator_username')->get();
					}elseif(in_array('supermoderator',$userrole)){
						$moderatorsid=Admin::where('parent_id',auth('admin')->user()->id)->pluck('id')->toArray();
						if(count($moderatorsid)>0){
							$userss_id=User::whereIn('moderator_id',$moderatorsid)->pluck('id')->toArray();

							$users=User::leftJoin('admins','admins.id','=','users.moderator_id')
					         ->select('users.id as id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.personal_number as personal_number', 'users.passport as passport', 'users.dob as dob','admins.username as moderator_username')
					          ->whereIn('users.id',$userss_id)
					           ->orWhere('users.moderator_id',null)
					          ->get();

						}else{

							$users=User::leftJoin('admins','admins.id','=','users.moderator_id')
					         ->select('users.id as id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.personal_number as personal_number', 'users.passport as passport', 'users.dob as dob','admins.username as moderator_username')				          
					           ->where('users.moderator_id',null)
					          ->get();

						}

					}elseif(in_array('moderator',$userrole)){
						$moderatorsid=auth('admin')->user()->id;
					
						$users=User::leftJoin('admins','admins.id','=','users.moderator_id')
					         ->select('users.id as id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.personal_number as personal_number', 'users.passport as passport', 'users.dob as dob','admins.username as moderator_username')
					          ->where('users.moderator_id',$moderatorsid)
					          ->get();

					}

				
					
				  }
			
			return Datatables::of($users)
				// ->addColumn('action', function($row){
				//   return "<button class='btn btn-primary'>View Profile</button>";
				//  })
				->addColumn('moderator_checkbox',function ($row){
					return "<input class='moderator-checkbox' hidden name='studentid[]' type='checkbox' value='$row->id' />";

				})
				
				->addColumn('student_id', function ($row) {
					
					return  "young_stu_" . $row->id;
				})

				->addColumn('moderator_username', function ($row) {
					
					return   ($row->moderator_username)?? "N/A";
				})
				 
				->addColumn('name', function ($row) {
					return $row->name . " " . $row->last_name;
				})

				->addColumn('email', function ($row) {
					return "<a href='mailto:" . $row->email . "' class='a-link'>" . $row->email . "</a>";
				})
				->addColumn('personal_number', function ($row) {
					return $row->personal_number ?? "N/A";
				})
				->addColumn('passport', function ($row) {
					return $row->passport ?? "N/A";
				})
				->addColumn('dob', function ($row) {
					
					return $row->dob ? date("d M Y", strtotime($row->dob)) : "N/A";
				})
				->addColumn('action', function ($row) {
					$userrole=json_decode(auth('admin')->user()->getRoleNames(),true) ?? [];
					if(hasPermissionForRoles('students_delete',$userrole)|| auth('admin')->user()->getRoleNames()[0]=="Admin"){
					return "<button class='btn btn-danger student-delete btn-icon btn-round' data-id={$row->id}><i class='fa fa-trash'></i></button>";
					}
				})
				//<button class='btn btn-primary student-edit btn-icon btn-round' data-id={$row->id}><i class='fa fa-edit'></i></button>";
				// ->addColumn('edit', function ($row) {
				// 	return "<button class='btn btn-danger student-delete btn-icon btn-round' data-id={$row->id}><i class='fa fa-edit'></i></button>";
				// })
				->addColumn('shortlist', function ($row) {
					return "<button class='btn btn-outline-primary student-shortlist btn-sm' data-id={$row->id}><i class='feather icon-check-circle'></i> Shortlisted Programs</button>";
					
				})
				->rawColumns(['email', 'action', 'shortlist','moderator_checkbox'])
				->make(true); 
		} else {
		

			$userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; 

			if(in_array('Admin',$userrole) || (!in_array('supermoderator',$userrole) && !in_array('moderator',$userrole) )){
			$moderator=Admin::select('id','username')->role('moderator')->get();
			$userId=User::select('id')->get();
			$userEmail=User::select('email')->get();
			$userPhone=User::select('personal_number')->get();
			
		
		}elseif(in_array('supermoderator',$userrole)){
				$moderator=Admin::select('id','username')->role('moderator')->where('parent_id',auth('admin')->user()->id)->get();
				$moderatorid=Admin::role('moderator')->where('parent_id',auth('admin')->user()->id)->pluck('id')->toArray();



				$userId=User::select('id')->whereIn('moderator_id',$moderatorid)->get();
			$userEmail=User::select('email')->whereIn('moderator_id',$moderatorid)->get();
			$userPhone=User::select('personal_number')->whereIn('moderator_id',$moderatorid)->get();

			}elseif(in_array('moderator',$userrole)){

				$moderator=Admin::where('id',auth('admin')->user()->id)->select('id','username')->role('moderator')->get();
				$moderatorid=auth('admin')->user()->id;
				
				$userId=User::select('id')->where('moderator_id',$moderatorid)->get();
				$userEmail=User::select('email')->where('moderator_id',$moderatorid)->get();
				$userPhone=User::select('personal_number')->where('moderator_id',$moderatorid)->get();

			}

	
			
			$breadcrumbs = [
				['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Students"]
			];
			return view('dashboard.students.index', [
				'breadcrumbs' => $breadcrumbs,'userId'=>$userId,'userEmail'=>$userEmail,'userPhone'=>$userPhone,'moderator'=>$moderator
			]);
		}
	}

	       public function profileResume($id, Request $request)

	     {

		$applicationId = ($request->application) ? $request->application : '';
		$refusalCountry = '';
		$resusalType = '';
		$appliedCountry = '';
		$users = User::find($id);
		$address = Address::find($users->address_id);
		$educations = UserAcademic::where('user_id', "=", $id)->get();
		$visaRefusal = UserMeta::where('user_id', '=', $id)->select('meta_key', 'meta_value')->where('meta_key', 'visa_refusal')->select('meta_value')->first();
		$appliedVisa = UserMeta::where('user_id', '=', $id)->select('meta_key', 'meta_value')->where('meta_key', 'applied_visa')->select('meta_value')->first();
		$applied = $appliedVisa->meta_value ?? '';
		$refuse = $visaRefusal->meta_value ?? '';
		$addressCountry = "N/A";
		$addressCity = "N/A";
		$addressState = "N/A";

		if ($address != NULL) {
			$addressCountry = Country::find($address->country_id)->name ?? "N/A";
			$addressState = State::find($address->state_id)->name ?? "N/A";
			$addressCity = City::find($address->city_id)->name ?? "N/A";
		}

		// if ($applied == 1) {
		//   $countryId = UserMeta::where('user_id', '=', $id)->select('meta_key', 'meta_value')->where('meta_key', 'applied_visa_country')->select('meta_value')->first();
		//   $Country = DB::table('countries')->where('id', $countryId->meta_value)->get();
		//   $appliedCountry = $Country[0]->name;
		// }
		$appliedCountry = "";

		// if ($refuse == 1) {
		//   $countryId = UserMeta::where('user_id', '=', $id)->select('meta_value')->where('meta_key', 'visa_refusal_country')->first();
		//   $Country = DB::table('countries')->where('id', $countryId->meta_value)->get();
		//   $refusalType = UserMeta::where('user_id', '=', $id)->select('meta_value')->where('meta_key', 'visa_refusal_type')->first();

		//   $refusalCountry = $Country[0]->name;
		//   $resusalType = $refusalType->meta_value;
		// }

		$refusalCountry = "";
		$resusalType = "";

		$userTests = SpecialTest::where('user_id', '=', $id)->get();
		$UserDocuments = UserDocument::where('user_id', '=', $id)
			->join('document_types', 'user_documents.document_type_id', '=', 'document_types.id')
			->join('user_document_files', 'user_documents.id', '=', 'user_document_files.user_document_id')
			->join('files', 'user_document_files.file_id', '=', 'files.id')
			->select('document_types.title as title', 'files.title as file', 'user_documents.document_type_id as type_id')
			->get();
		$user = User::find($id);
		$document_lists = User::uploadDocumentList($user);
		$other_docs = User::otherDocumentList($user);
		if (isset($request->application)) {
			$application = UserApplication::find($request->application);
			$application_docs = $application->requiredDocuments();
		} else {
			$application = null;
			$application_docs = null; 
		}
		$documentTypes = DocumentType::all();
		return view('student.profile', compact('users', 'application', 'application_docs', 'document_lists', 'other_docs', 'educations', 'userTests', 'UserDocuments', 'documentTypes', 'applied', 'refuse', 'appliedCountry', 'refusalCountry', 'resusalType', 'applicationId', 'addressCountry', 'addressState', 'addressCity', 'address'));
	}

	function shortlistApplication($id)
	{
		$shortlists = UserShortlistProgram::join('campus_programs', 'users_shortlist_programs.campus_program_id', '=', 'campus_programs.id')
			->join('programs', 'campus_programs.program_id', '=', 'programs.id')
			->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			->join('universities', 'campus.university_id', '=', 'universities.id')
			->select('universities.name as university', 'campus.name as campus', 'programs.name as program', 'campus_programs.id as campus_program_id')
			->where('user_id', '=', $id)
			->get();
		return view('dashboard.students.shortlist', ['shortlists' => $shortlists]);
	}

	function destroy($id)
	{
		$role = User::find($id);
		$role->delete();
		if ($role->save()):
			return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'User deleted successfully'
			]);
		endif;
	}


	public function get_student_data(){
		return Excel::download(new StudentData, 'studentsdata.xlsx');
	}

	public function edit($id)  
	{
	  $user = User::find($id);
	  $moderator=Admin::select('id','username')->role('moderator')->get();


	  return view('dashboard.students.edit', compact('user','moderator'));
	}

	public function moderator_assign_to_students(Request $request){

	




		 $userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; 
		if(hasPermissionForRoles('assign_students_to_moderator_assign', $userrole) || auth('admin')->user()->getRoleNames()[0] == 'Admin'){



			$validator = Validator::make($request->all(), [
				'checkedValues' => 'required',
				'moderatorid'=>'required'
			
				
			],['checkedValues.required'=>'Please Select Student','moderatorid.required'=>'Please Select Moderator']);
	
			// Check if validation fails
			if ($validator->fails()) {
				// Return a JSON response with validation errors
				return response()->json(['errors' => $validator->errors()], 422);
			}
	
		
			
		

    
	
			$user=User::whereIn('id',$request->checkedValues)->update(['moderator_id'=>$request->moderatorid]);
		    if($user){

				activity('Assign')
				->causedBy(Auth::guard('admin')->user())
				->withProperties(['ip' => $request->ip()])
				->log('Moderator assign to students');


			   return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Moderator assign to student successfully'
			 ]);
		}

		

	}else{
		return response()->json([
			'error' => true,
			'code' => 'fail',
			'title' => 'Not Permission',
			'message' => 'You have not permisson'
		]);

	}

	}



	public function moderator_dissociate_to_students(Request $request){

		$userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; 
	   if(hasPermissionForRoles('assign_students_to_moderator_dissociate', $userrole) || auth('admin')->user()->getRoleNames()[0] == 'Admin'){


		$validator = Validator::make($request->all(), [
			'checkedValues' => 'required',
		
			
		],['checkedValues.required'=>'Please Select Student']);

		// Check if validation fails
		if ($validator->fails()) {
			// Return a JSON response with validation errors
			return response()->json(['errors' => $validator->errors()], 422);
		}




	   

   
	  
		   $user=User::whereIn('id',$request->checkedValues)->orWhere('moderator_id',$request->moderatorid)->update(['moderator_id'=>null]);
		   if($user){

			activity('Dissociate')
            ->causedBy(Auth::guard('admin')->user())
            ->withProperties(['ip' => $request->ip()])
            ->log('Dissociate moderator to students');




			  return response()->json([
			   'success' => true,
			   'code' => 'success',
			   'title' => 'Congratulations',
			   'message' => 'Moderator Dissociate To students successfully'
			]);
	   }

	

   }else{
	   return response()->json([
		   'error' => true,
		   'code' => 'fail',
		   'title' => 'Not Permission',
		   'message' => 'You have not permisson'
	   ]);

   }

   }




public function update(Request $request ,$id){
	// dd($request,$id);

}









}
