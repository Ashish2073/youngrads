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
use App\Models\Modifier;
use Illuminate\Support\Facades\DB;
use App\Models\UserAcademic;
use App\Models\UserApplication;
use App\Models\UserDocument;
use App\Models\UserShortlistProgram;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentData;

class StudentController extends Controller
{
	// use Authorizable;

	public function __construct()
	{
		$this->middleware('auth:admin')->except('profileResume');
	}

	public function index(Request $request)
	{
	
		if (request()->ajax()) {
			
			
		  
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
				     
			      if(($request->get('email')!=null)||($request->get('id')!=null)||($request->get('personal_number')!=null)){
				
			    //    $users=User::select('id','name','last_name','email','personal_number','passport','dob')->whereIn('id',($request->get('id')!=null)?($request->get('id')):[DB::raw('id')])
				//    ->whereIn('email',($request->get('email')!=null)?($request->get('email')):[DB::raw('email')])
				// ->whereIn('personal_number',($request->get('personal_number')!=null)?($request->personal_number):[(DB::raw('personal_number'))])
				//    ->get();

				   $users = User::select('id', 'name', 'last_name', 'email', 'personal_number', 'passport', 'dob')
                            ->whereIn('id', ($request->get('id') != null) ? $request->get('id') : [DB::raw('id')])
                             ->whereIn('email', ($request->get('email') != null) ? $request->get('email') : [DB::raw('email')])
                              ->where(function ($query) use ($request) {
								if(($request->has('personal_number'))){
									$query->whereIn('personal_number', $request->get('personal_number'));
								}else{
									$query->whereIn('personal_number', ($request->get('personal_number') != null) ? $request->personal_number : [DB::raw('personal_number')])
									->orWhereNull('personal_number');
								}

                              })
                            ->get();


				  }else{
					
					$users=User::leftJoin('modifiers','modifiers.id','=','users.moderator_id')
					->select('users.id as id', 'users.name as name', 'users.last_name as last_name', 'users.email as email', 'users.personal_number as personal_number', 'users.passport as passport', 'users.dob as dob','modifiers.username as moderator_username')->get();


				
					
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
				->addColumn('delete', function ($row) {
					return "<button class='btn btn-danger student-delete btn-icon btn-round' data-id={$row->id}><i class='fa fa-trash'></i></button>";
				})
				->addColumn('shortlist', function ($row) {
					return "<button class='btn btn-outline-primary student-shortlist btn-sm' data-id={$row->id}><i class='feather icon-check-circle'></i> Shortlisted Programs</button>";
				})
				->rawColumns(['email', 'delete', 'shortlist','moderator_checkbox'])
				->make(true); 
		} else {
			$userId=User::select('id')->get();
			$userEmail=User::select('email')->get();
			$userPhone=User::select('personal_number')->get();
			$moderator=Modifier::select('id','username')->role('moderator')->get();

	
			
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

	public function filterstudentdata(Request $request){
		

	}
}
