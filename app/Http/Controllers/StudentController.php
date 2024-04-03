<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAcademic;
use Illuminate\Support\Facades\DB;
use App\Models\Test;
use App\Models\SpecialTest;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\UserDocument;
use App\Models\UserDocumentFiles;
use App\Models\UserMeta;
use App\Models\WorkExperience;
use App\Models\SubTest;
use App\Models\UserApplication;
use App\Models\UserShortlistProgram;
use Yajra\Datatables\Datatables;

use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\studyLevel;
use Str;

class StudentController extends Controller
{

	public function __construct()
	{
		$this->middleware(['auth']);
		config(['countries' => Country::all()]);
		config(['study_levels' => studyLevel::where('parent_id', 0)->orderBy('sequence', 'asc')->get()]);
	}

	public function index()
	{
		$breadcrumbs = [
			['link' => "my-account", 'name' => "Dashboard"]
		];
		$pageConfigs = [
			'pageHeader' => true
		];
		$application = UserApplication::where('user_id', Auth::id())->count();
		$shortList = UserShortlistProgram::where('user_id', Auth::id())->count();

		return view('student.index', compact('breadcrumbs', 'application', 'shortList', 'pageConfigs'));
	}

	public function editProfile()
	{
		$breadcrumbs = [
			['link' => "my-account", 'name' => "Dashboard"]
		];
		$pageConfigs = [
			'pageHeader' => true
		];
		config(['progress_detail' => auth()->user()->profileCompleteDetail()]);
		return view('student.profile.index', [
			'breadcrumbs' => $breadcrumbs,
		]);
	}

	public function profileCompleteDetail()
	{
		return auth()->user()->profileCompleteDetail();
	}

	public function stepView($step)
	{

		switch ($step) {
			case 'general_information':
				return $this->stepGeneralInformation();
				break;

			case 'education_history':
				return $this->stepEducationHistory();
				break;

			case 'test_scores':
				return $this->stepTestScores();
				break;

			case 'work_experience':
				return $this->stepWorkExprience();
				break;

			case 'background_information':
				return $this->stepBackgroundInformation();
				break;

			case 'upload_documents':
				return $this->stepUploadDocuments();
				break;

			default:
				return '<h4>Oops! Something went wrong</h4>';
		}
		return view("student.profile.steps.{$step}");
	}

	public function stepGeneralInformation()
	{
		$user = auth()->user();
		$request = request();
		$user->dob = is_null($user->dob) ? "" : date("d F Y", strtotime($user->dob));
		if (is_null($user->address)) {
			config([
				'states' => [],
				'cities' => []
			]);
		} else {
			config([
				'states' => State::where('country_id', $user->address->country_id)->get(),
				'cities' => City::where('state_id', $user->address->state_id)->get()
			]);
		}
		switch (request()->method()) {
			case 'GET':
				return view("student.profile.steps.general_information", compact('user'));
				break;

			case 'POST':
				$validations_arr = [
					'first_name' => 'required|max:255',
					'dob' => '',
					'country' => '',
					'last_name' => '',
					'gender' => '',
					'maritial_status' => '',
					'language' => '',
					'city' => '',
					'address_country' => '',
					'address' => ''
				];
				if (!empty($request->personal_number)) {
					$validations_arr['personal_number'] = 'unique:users,personal_number,' . auth()->user()->id;
				}
				if (!empty($request->passport_number)) {
					$validations_arr['passport_number'] = 'unique:users,passport,' . auth()->user()->id;
				}
				if (!empty($request->city_name)) {
					$validations_arr['city_name'] = 'required|max:255';
				}
				$validator = Validator::make(request()->all(), $validations_arr, [
					'personal_number.unique' => 'The Phone number has already been taken.'
				]);

				if ($validator->fails()) {
					$validator->errors()->add('form_error', 'Error! Please check below');
					request()->flash();
					return view('student.profile.steps.general_information', compact('user'))->withErrors($validator);
				}

				return User::updateGeneralInformation($request, $user);
				break;

			default:
				return '<h4>Oops! Something went wrong</h4>';
		}
	}

	public function stepEducationHistory()
	{
		$user = auth()->user();
		$request = request();

		switch (request()->method()) {
			case 'GET':
				return view("student.profile.steps.education_history", compact('user'));
				break;

			case 'POST':
				$validator = Validator::make(request()->all(), [
					'country' => '',
					'highest_education' => ''
				]);

				if ($validator->fails()) {
					// $validator->errors()->add('form_error', 'Error! Please check below');
					// request()->flash();
					// return view('student.profile.steps.education_history', compact('user'))->withErrors($validator);
					return response()->json([
						'success' => false,
						'code' => 'error',
						'title' => 'Error!',
						'message' => 'Please enter the correct details for Highest Education',
						'errors' => $validator->errors()
					]);
				}

				$user->createOrUpdateMeta('country_of_education', $request->country);

				$user->createOrUpdateMeta('hightest_level', $request->highest_education);

				if (request()->get('dont_prompt') == 1) {
					return response()->json([
						'success' => true
					]);
				}
				return response()->json([
					'success' => true,
					'code' => 'success',
					'title' => 'Congratulations!',
					'message' => 'Education history saved successfully'
				]);

				break;

			default:
				return '<h4>Oops! Something went wrong</h4>';
		}
	}

	public function stepTestScores()
	{
		$user = auth()->user();
		$request = request();
		config(['tests' => Test::where('parent_id', 0)->get()]);

		$userTests = SpecialTest::where('user_id', '=', Auth::id())->get();
		$testIds = [];
		foreach ($userTests as $userTest) {
			$testIds[] = $userTest->test_type_id;
		}
		switch (request()->method()) {
			case 'GET':
				return view("student.profile.steps.test_scores", compact('user', 'testIds'));
				break;

			case 'POST':

				break;

			default:
				return '<h4>Oops! Something went wrong</h4>';
		}
	}

	public function stepWorkExprience()
	{
		$user = auth()->user();
		$request = request();

		switch (request()->method()) {
			case 'GET':
				return view("student.profile.steps.work_experience");
				break;

			case 'POST':

				break;

			default:
				return '<h4>Oops! Something went wrong</h4>';
		}
	}

	public function stepBackgroundInformation()
	{
		$user = auth()->user();
		$request = request();
		switch (request()->method()) {
			case 'GET':
				return view("student.profile.steps.background_information", compact('user'));
				break;

			case 'POST':
				$user->createOrUpdateMeta('applied_visa', $request->applied_visa);

				$user->createOrUpdateMeta('visa_refusal', $request->visa_refusal);


				if ($request->applied_visa == 0 && $request->visa_refusal == 0) {
					$user->createOrUpdateMeta('visa_refusal_details', "");
				} else {
					$user->createOrUpdateMeta('visa_refusal_details', $request->visa_refusal_details);
				}

				return response()->json([
					'success' => true,
					'title' => 'Congratulations!',
					'message' => 'Background information saved successfully!',
					'code' => 'success'
				]);

				break;

			default:
				return '<h4>Oops! Something went wrong</h4>';
		}
	}

	public function stepUploadDocuments()
	{
		$user = auth()->user();
		$request = request();

		config(['documents' => User::uploadDocumentList($user)]);
		config(['other_documents' => User::otherDocumentList($user)]);
		switch ($request->method()) {
			case 'GET':
				return view('student.profile.steps.upload_documents');
				break;

			case 'POST':
				break;

			default:
				return '<h4>Oops! Something went wrong.</h4>';
		}
	}

	public function deleteDocument($id)
	{
		try {
			$user_document = UserDocument::findOrFail($id);
			$files_to_delete = $user_document->deleteFileRecord();
			foreach ($files_to_delete as $file_to_delete) {
				unlink(public_path($file_to_delete));
			}
			$user_document->delete();
			return response()->json([
				'success' => true,
				'title' => 'Congratulations',
				'code' => 'success',
				'message' => 'Document deleted successfully!',
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => true,
				'title' => 'Error',
				'code' => 'error',
				'message' => 'Something went wrong.',
			]);
		}

	}



	public function edit($id)
	{
		$user = User::find($id);
		$pageConfigs = [
			'pageHeader' => true
		];
		$breadcrumbs = [
			['link' => "my-account", 'name' => "Dashboard"],
			['name' => "Setting"]
		];
		return view('student.edit', [
			'user' => $user,
			'pageConfigs' => $pageConfigs,
			'breadcrumbs' => $breadcrumbs
		]);
	}

	public function emailChange($id, Request $request)
	{
		$user = User::find($id);

		$validator = Validator::make($request->input(), [
			'email' => 'required',
			'confirm_email' => 'required|same:email|unique:users,email',
		], [
			'confirm_email.same' => "Confirm email is match with email",
			'confirm_email.unique' => "Email address has already been taken."
		]);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.change_email', compact('user'))->withErrors($validator);
		}

		$user->new_email = $request->confirm_email;
		if ($user->save()) {
			$user->sendChangeEmailVerificationNotification();
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'A new verification link has been sent to your new email address.',
				'success' => true
			]);
		}
	}

	function emailUpdate($token, $id)
	{
		echo "test.$id";
	}

	public function update(Request $request, $id)
	{
		$validation_arr = [
			'first_name' => 'required',
			// 'password' => ['string', 'min:8', 'confirmed'],
		];
		if (!empty($request->password)) {
			$validation_arr['password'] = 'required|confirmed|min:6';
		}

		$request->validate($validation_arr);


		$user = User::findOrFail($id);
		$user->name = $request->first_name;
		$user->last_name = $request->last_name;
		// $user->email = $request->email;
		if (!empty($request->password)) {
			$user->password = bcrypt($request->password);
		}
		if ($user->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Profile updated successfully',
				'success' => true
			]);
		} else {
			return response()->json([
				'code' => 'error',
				'title' => 'Error',
				'message' => 'Something went wrong.',
				'success' => false
			]);
		}
	}

	function completeProfile()
	{
		$breadcrumbs = [
			['link' => "my-account", 'name' => "Dashboard"],
			['name' => "Profile"]
		];

		$user = User::find(Auth::id());
		$country = DB::table('countries')->where('id', $user->country)->select('name', 'id')->get();
		$testTypes = Test::select('test_name', 'id')->get();

		$userTests = SpecialTest::where('user_id', '=', Auth::id())->get();
		$testIds = [];
		foreach ($userTests as $userTest) {
			$testIds[] = $userTest->test_type_id;
		}
		$testScore = isset($testScore) ? $testScore : [];
		$documentTyps = DocumentType::select('id', 'title', 'document_limit')->get();
		$files = UserDocumentFiles::join('files', 'user_document_files.file_id', '=', 'files.id')
			->join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')->where('user_id', '=', Auth::id())->select('title', 'document_type_id', 'user_document_files.id as id', 'user_document_id', 'file_id')
			->get();

		$userMeta = UserMeta::where('user_id', '=', Auth::id())->get();
		$educationCountry = $userMeta->where('meta_key', 'country_of_education')->first();
		$appliedVisa = $userMeta->where('meta_key', 'applied_visa')->first();
		//education country Id
		$educationCountryId = isset($educationCountry->meta_value) ? $educationCountry->meta_value : '';
		//  $appliedVisaCountry =  DB::table('countries')->where('id', $appliedVisaCountryId)->select('name','id')->get();

		$appliedVisa = isset($appliedVisa->meta_value) ? $appliedVisa->meta_value : '';
		$refuseVisa = $userMeta->where('meta_key', 'visa_refusal')->first();
		$refuseVisa = isset($refuseVisa) ? $refuseVisa->meta_value : '';
		$refuseVisaType = $userMeta->where('meta_key', 'visa_refusal_type')->first();
		$refuseVisaType = isset($refuseVisaType) ? $refuseVisaType->meta_value : '';
		$highestEducation = $userMeta->where('meta_key', 'hightest_level')->first();
		$highestEducation = isset($highestEducation) ? $highestEducation->meta_value : "";
		//  //refuse country id
		//  $refuseCountryId = $userMeta->where('meta_key','visa_refusal_country')->first();
		//  $refuseCountryId = isset($refuseCountryId->meta_value)? $refuseCountryId->meta_value : '';
		//  $refuseCountry =  DB::table('countries')->where('id', $refuseCountryId)->select('name','id')->get();
		$studyLevels = DB::table('study_levels')->orderBy('sequence', 'desc')->get();
		$address = Address::find($user->address_id);
		$addressCountry = "";
		$state = "";
		$city = "";

		$states = [];
		$countries = [];
		if (!(empty($address))) {
			$addressCountry = DB::table('countries')->where('id', $address->country_id)->select('id', 'name')->get();
			$state = DB::table('states')->where('id', $address->state_id)->select('id', 'name')->get();
			$states = DB::table('states')->where('country_id', $address->country_id)->get();
			$city = DB::table('cities')->where('id', $address->city_id)->select('id', 'name')->get();
			$cities = DB::table('cities')->where('state_id', $address->state_id)->get();
		}

		$userStudyLevel = UserAcademic::where('user_id', '=', Auth::id())->orderBy('study_levels_id', 'asc')->get();
		$userStudyIds = [];
		$userEducationDocs = UserDocumentFiles::where('table_name', '=', 'study_levels')->get();
		foreach ($userEducationDocs as $userEducationDoc) {
			$userStudyIds[] = $userEducationDoc->table_id;
		}
		$userTest = SpecialTest::where('user_id', '=', Auth::id())->get();
		$userTestIds = [];
		$userTestDocs = UserDocumentFiles::join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')
			->where('table_name', '=', 'tests')
			->where('user_documents.user_id', '=', Auth::id())
			->select('document_type_id')
			->get();
		$countries = Country::get();
		foreach ($userTestDocs as $userTestDoc) {
			$userTestIds[] = $userTestDoc->document_type_id;
		}
		if ($user->dob == null) {
			$user->dob = "";
		} else {
			$user->dob = date("d F Y", strtotime($user->dob));
		}

		return view('student.complete_profile', [
			'user' => $user,
			'breadcrumbs' => $breadcrumbs,
			'country' => $country,
			'testTypes' => $testTypes,
			'testScore' => $testScore,
			'documentTyps' => $documentTyps,
			'files' => $files,
			'appliedVisa' => $appliedVisa,
			'refuseVisa' => $refuseVisa,
			'refuseVisaType' => $refuseVisaType,
			'addressCountry' => $addressCountry,
			'state' => $state,
			'city' => $city,
			'address' => $address,
			'testIds' => $testIds,
			'studyLevels' => $studyLevels,
			'userStudyLevels' => $userStudyLevel,
			'userTests' => $userTest,
			'userStudyIds' => $userStudyIds,
			'userTestIds' => $userTestIds,
			'highestEducation' => $highestEducation,
			'countries' => $countries,
			'educationCountryId' => $educationCountryId,
			'states' => $states,
			'cities' => $cities
		]);
	}

	public static function checkUserLimit($id)
	{

		$fileCount = UserDocumentFiles::join('files', 'user_document_files.file_id', '=', 'files.id')
			->join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')->where([
					['user_id', '=', Auth::id()],
					['document_type_id', '=', $id]
				])->select('title', 'document_type_id', 'user_document_files.id as id', 'user_document_id', 'file_id')->count();

		$documentType = DocumentType::where('id', $id)->select('document_limit')->first();


		if ($fileCount < $documentType->document_limit)
			return true;
		else
			return false;
	}

	function changeProfilePic(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			['profile' => 'required|image|mimes:jpeg,png,jpg,gif,png,ico|max:2048'],
			[
				'profile.mimes' => 'Please add profile picture with JPEG,PNG,GIF format',
				'profile.max' => 'Image size must be less than 2MB.'
			]
		);

		if ($validator->fails()) {
			// $validator->errors()->add('form_error', 'Error! Please check below');
			// $request->flash();
			return response()->json([
				'success' => false,
				'error' => $validator->errors()->all()
			]);
		}
		$fileName = time() . "." . Auth::id() . "." . $request->profile->extension();


		$admin = User::find(Auth::id());
		$admin->profile_img = $fileName;
		if ($admin->save()) {
			$request->profile->move(public_path('uploads/profile_pic/student'), $fileName);
			return response()->json([
				'success' => true,
				'image' => asset("uploads/profile_pic/student/" . $fileName),
				'message' => "Profile picture changed successfully"
			]);
		} else {

			return response()->json([
				'success' => false,
				'message' => "Somthing Went Wrong"
			]);
		}
	}

	function generalInforamtion(Request $request)
	{

		$user = User::find(Auth::id());

		$address = Address::find($user->address_id);
		$addressCountry = "";
		$state = "";
		$city = "";

		$country = DB::table('countries')->where('id', $user->country)->select('name', 'id')->get();
		$countries = Country::get();
		$states = [];
		$cities = [];
		if (!(empty($address))) {
			$addressCountry = DB::table('countries')->where('id', $address->country_id)->select('id', 'name')->get();
			$state = DB::table('states')->where('id', $address->state_id)->select('id', 'name')->get();
			$states = DB::table('states')->where('country_id', $address->country_id)->get();
			$city = DB::table('cities')->where('id', $address->city_id)->select('id', 'name')->get();
			$cities = DB::table('cities')->where('state_id', $address->state_id)->get();
		}

		$validator = Validator::make($request->all(), [
			'first_name' => 'required',
			//'email' => 'required',
			'dob' => 'required',
			'country' => 'required',
			'personal_number' => 'required',
			'last_name' => 'required',
			'gender' => 'required',
			'maritial_status' => 'required',
			// 'passport_number' => ['required', 'regex:/^[A-PR-WYa-pr-wy][1-9]\\d\\s?\\d{4}[1-9]$/'],
			'passport_number' => ['required'],
			'language' => 'required',
			'city' => 'required',
			'address_country' => 'required',
			'address' => 'required'
		]);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.general_information', compact('user', 'addressCountry', 'state', 'city', 'country', 'countries', 'states', 'cities'))->withErrors($validator);
		}

		$address = new Address;
		$address->address = $request->address;
		$address->country_id = $request->address_country;
		$address->state_id = $request->state;
		$address->city_id = $request->city;
		$address->save();

		$user->name = $request->first_name;
		$user->last_name = $request->last_name;
		//$user->email = $request->email;
		$user->dob = date('Y-m-d', strtotime($request->dob));
		$user->country = $request->country;
		$user->maritial_status = $request->maritial_status;
		$user->gender = $request->gender;
		$user->personal_number = $request->personal_number;
		$user->language = $request->language;
		$user->postal = $request->postal;
		$user->passport = $request->passport_number;
		$user->address_id = $address->id;



		if ($user->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Personal Information saved successfully',
				'success' => true
			]);
		} else {
			return view('student.general_information', compact('user', 'addressCountry', 'state', 'city', 'country', 'countries', 'states', 'cities'));
		}
	}

	function addEducation()
	{
		if (auth()->user()->meta('hightest_level') == '') {
			return 'Please Select Highest Level of Education and try again.';
		}
		$studyLevels = DB::table('study_levels')->where('parent_id', '=', 0)->orderBy('sequence', 'asc')->get();
		$highest_education = studyLevel::where('id', auth()->user()->meta('hightest_level'))->first();

		$academics = UserAcademic::where('user_id', '=', Auth::id())->get();
		$countries = Country::get();
		$studyIds = [];
		foreach ($academics as $academic) {
			$studyIds[] = $academic->study_levels_id;
		}
		return view('student.education_add', compact('studyLevels', 'studyIds', 'countries', 'highest_education'));
	}

	function editEducation(Request $request)
	{
		if (auth()->user()->meta('hightest_level') == '') {
			return 'Please Select Highest Level of Education and try again.';
		}
		$studyLevels = DB::table('study_levels')->get();
		$academics = UserAcademic::find($request->id);
		$countries = Country::get();
		return view('student.education_edit', compact('studyLevels', 'academics', 'countries'));
	}

	function educationHistory(Request $request)
	{
		$validations = [
			// 'study_level' => 'required',
			// 'year' => 'required|numeric',
			// 'board' => 'required|numeric',
			// 'marks' => 'required|numeric',
			// 'marks_unit' => 'required',
			// 'country' => 'required',
			// 'start_date' => 'required',
			// 'end_date' => 'required',
			// 'language' => 'required|max:255',
			// 'qualification' => 'required|max:255'
			// 'study_level' => 'required|unique:user_academics,study_levels_id'
		];
		$validator = Validator::make($request->all(), $validations, ['study_level.unique' => 'Aleardy Added']);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.education_add')->withErrors($validator);
		}

		$academics = UserAcademic::where('user_id', Auth::id())->first();
		$userAcademic = new UserAcademic;

		$userAcademic->user_id = Auth::id();
		$userAcademic->study_levels_id = $request->study_level;
		$userAcademic->year_of_passing = $request->year;
		$userAcademic->board_name = $request->board;
		$userAcademic->marks = $request->marks;
		$userAcademic->marks_unit = $request->marks_unit;
		$userAcademic->country = $request->country;
		$userAcademic->start_date = date('Y-m-d', strtotime($request->start_date));
		$userAcademic->end_date = date('Y-m-d', strtotime($request->end_date));
		$userAcademic->language = $request->language;
		$userAcademic->qualification = $request->qualification;
		if ($request->study_level == 20) {
			$userAcademic->sub_other = $request->sub_other;
		}

		if ($userAcademic->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Education History saved successfully',
				'success' => true
			]);
		} else {
			return view('student.education_history');
		}
	}

	function updateEducation($id, Request $request)
	{

		$userAcademic = UserAcademic::find($id);

		$userAcademic->user_id = Auth::id();
		$userAcademic->study_levels_id = $request->study_level;
		$userAcademic->year_of_passing = $request->year;
		$userAcademic->board_name = $request->board;
		$userAcademic->marks = $request->marks;
		$userAcademic->marks_unit = $request->marks_unit;
		$userAcademic->country = $request->country;
		$userAcademic->start_date = date('Y-m-d', strtotime($request->start_date));
		$userAcademic->end_date = date('Y-m-d', strtotime($request->end_date));
		$userAcademic->language = $request->language;
		$userAcademic->qualification = $request->qualification;

		if ($request->study_level == 20) {
			$userAcademic->sub_other = $request->sub_other;
		}

		if ($userAcademic->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Education History updated successfully',
				'success' => true
			]);
		}
	}

	function educationDelete(Request $request)
	{
		$userAcademic = UserAcademic::find($request->id);

		if ($userAcademic->delete()) {

			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Education history deleted successfully',
				'success' => true
			]);
		}
	}



	public function educationListing()
	{
		$education = UserAcademic::join('study_levels', 'user_academics.study_levels_id', '=', 'study_levels.id')
		    ->join('countries','user_academics.country','=','countries.id')
			->where('user_id', '=', Auth::id())->select('study_levels.name as study_level', 'user_academics.*','countries.name as country_name')
			->get();

		return Datatables::of($education)
			->addColumn('marks', function ($row) {
				if ($row->marks_unit == 'percentage') {
					return $row->marks . "  %";
				} else {
					return $row->marks . " " . $row->marks_unit;
				}
			})
			->addColumn('study_level', function ($row) {
				if ($row->study_level == 'Other') {
					return $row->study_level . " ( " . $row->sub_other . " )";
				} else {
					return $row->study_level;
				}
			})
			->addColumn('start_date', function ($row) {
				return date('d-F-Y', strtotime($row->start_date));
			})
			->addColumn('language', function ($row) {
				return $row->language;
			})
			->addColumn('end_date', function ($row) {
				return date('d-F-Y', strtotime($row->end_date));
			})
			->addColumn('country', function ($row) {
				return $row->country_name ?? "";
			})
			->addColumn('action', function ($row) {
				$html = "<div>";
				$html .= "<button class='btn btn-icon mr-1 btn-outline-primary education-edit' data-id='" . $row->id . "'><i class='fa fa-pencil'></i></button>";
				$html .= "<button class='btn btn-icon btn-outline-danger education-delete' data-id='" . $row->id . "'><i class='fa fa-trash'></i></button>";
				$html .= "</div>";
				return $html;
			})
			->rawColumns(['action','language','country','marks','study_level','start_date','end_date'])
			->make(true);
	}

	function highestEducation(Request $request)
	{

		$country = $this->updateMeta(Auth::id(), 'country_of_education', $request->country);
		$highestEducation = $this->updateMeta(Auth::id(), 'hightest_level', $request->highest_education);

		if ($country && $highestEducation) {

			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Highest Education added successfully',
				'success' => true
			]);
		}
	}

	function testScoreAdd(Request $request)
	{

		$subTests = DB::table('special_test_sub')->where('test_id', '=', $request->id)->get();
		try {
			$testName = Test::find($request->id)->test_name;
			$test_record = Test::find($request->id);
		} catch (\Exception $e) {
			$testName = "";
		}
		return view('student.special_test.create', ['test_record' => $test_record, 'subTests' => $subTests, 'id' => $request->id, 'name' => $testName]);
	}

	function testScoreStore(Request $request)
	{
		
        
		$subTests = DB::table('special_test_sub')->where('test_id', '=', $request->test_type)->get();
		$testName = Test::find($request->test_type)->test_name;
		
		$testMin=Test::find($request->test_type)->min;
		$testMax=Test::find($request->test_type)->max;
		$test_record = Test::find($request->test_type);

		// $validator = Validator::make($request->all(), [
		// 	'score' => 'required|numeric'|function($attribute,$value,$fail) use($testMin,$testMax,$testName){
		// 		if($value>$testMin && $value<$testMin ){
		// 			$fail($testName." ".'should between'." ".$testMin." "."to".$testMax);

		// 		}


		// 	},
		// 	'exam_date' => 'required',
		// ]);

		

		$validator = Validator::make($request->all(), [
			'score' => [
				'required',
				'numeric',
				function ($attribute, $value, $fail) use ($testMin, $testMax, $testName) {
					
					if ($value < $testMin && $value > $testMax) {
						$fail($testName." " .'score vale should be between ' . $testMin . ' and ' . $testMax);
					}
				},
			],
			'exam_date' => 'required',
		]);



		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.special_test.create', ['test_record' => $test_record, 'subTests' => $subTests, 'id' => $request->id, 'name' => $testName])->withErrors($validator);
		}

		$userTest = new SpecialTest;
		$userTest->user_id = Auth::id();
		$userTest->test_type_id = $request->test_type;
		$userTest->exam_date = date('Y-m-d', strtotime($request->exam_date));
		$userTest->score = $request->score;

		$i = 0;
		if(isset($request->subscore)){
		foreach ($request->subscore as $subTest) {
			if ($subTest != "") {
				SubTest::create([
					'user_id' => Auth::id(),
					'test_id' => $request->test_type,
					'sub_id' => $request->subtype[$i],
					'score' => $subTest
				]);
			}
			$i++;
		}
	}
		if ($userTest->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Test score added successfully',
				'success' => true
			]);
		}
	}

	function testScoreList()
	{
		$tests = SpecialTest::where('user_id', '=', Auth::id());
		return Datatables::of($tests)
			->addColumn('test', function ($row) {
				return view('student.special_test.listing', compact('row'))->render();
			})
			->addColumn('action', function ($row) {
				$html = "<div class='text-center'>";
				$html .= "<button class='mr-md-1 mb-md-1  btn-icon  btn btn-outline-primary test-edit' data-id='" . $row->id . "' data-test='" . $row->test_type_id . "'><i class='fa fa-pencil'></i></button>";
				$html .= "<button class='mb-md-1 btn-icon btn btn-outline-danger test-delete' data-id='" . $row->id . "' data-test='" . $row->test_type_id . "'><i class='fa fa-trash'></i></button>";
				$html .= "</div>";
				return $html;
			})
			->rawColumns(['test', 'action'])
			->make(true);
	}

	function testScoreDelete(Request $request)
	{
		$test = SpecialTest::where([['user_id', '=', Auth::id()], ['test_type_id', '=', $request->test_id]])->delete();
		$subTest = SubTest::where([['user_id', '=', Auth::id()], ['test_id', '=', $request->test_id]])->delete();
		if ($test && $subTest) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Test score deleted successfully',
				'success' => true
			]);
		}
	}

	function testScoreEdit(Request $request)
	{

		$test = SpecialTest::where([['user_id', '=', Auth::id()], ['test_type_id', '=', $request->test_id]])->get();
		$subTestScores = SubTest::where([['user_id', '=', Auth::id()], ['test_id', '=', $request->test_id]])->select('score', 'sub_id')->get();
		$subTests = DB::table('special_test_sub')->where('test_id', '=', $request->test_id)->get();
		$i = 0;
		$subScores = [];
		if (count($subTestScores) == 0) {
			foreach ($subTests as $subTest) {
				$subScores[$i] = "";
				$i++;
			}
		} else {
			foreach ($subTestScores as $subTestScore) {
				$subScores += [$subTestScore->sub_id => $subTestScore->score];
			}
		}
		$testName = Test::find($request->test_id)->test_name;
		$test_record = Test::find($request->test_id);
		return view('student.special_test.edit', compact('subScores', 'test_record', 'test', 'subTests', 'testName'));
	}

	function testScoreUpdate($id, Request $request)
	{

		$userTest = SpecialTest::find($id);

		$subTestScores = SubTest::where([['user_id', '=', Auth::id()], ['test_id', '=', $userTest->test_type_id]]);

		if ($subTestScores->count() > 0) {
			$subTestScores->delete();
		}

		$subTest = SubTest::where([['user_id', '=', Auth::id()], ['test_id', '=', $userTest->test_type_id]])->delete();
		$userTest->user_id = Auth::id();
		$userTest->test_type_id = $request->test_type;
		$userTest->exam_date = date('Y-m-d', strtotime($request->exam_date));
		$userTest->score = $request->score;

		$i = 0;
		foreach ($request->subscore as $subTest) {
			if ($subTest != "") {
				SubTest::create([
					'user_id' => Auth::id(),
					'test_id' => $request->test_type,
					'sub_id' => $request->subtype[$i],
					'score' => $subTest
				]);
			}
			$i++;
		}

		if ($userTest->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Test Score updated successfully',
				'success' => true
			]);
		}
	}

	public function userDocument(Request $request)
	{
		$tableName = $request->table_name;
		$tableId = $request->table_id;
		$type = $request->type;
		$limit = $request->limit;
		$documentNames = $this->documentName($tableName, $tableId);
		return view('student.documents.create', compact('tableName', 'tableId', 'type', 'limit', 'documentNames'));
	}

	function userDocumentStore(Request $request)
	{
		
		$tableName = $request->table_name;
		$tableId = $request->table_id;

		$type = $request->type;
		$limit = $request->limit;
		$documentNames = $this->documentName($tableName, $tableId);
		$validator = Validator::make($request->all(), ['document.*' => 'required|mimes:jpg,pdf'], ['document.required' => "Please add all document"]);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.documents.create', compact('tableName', 'tableId', 'type', 'limit', 'documentNames'))->withErrors($validator);
		}
		$i = 1;
		foreach ($request->document as $document) {

			$fileName = $request->table_id . "." . Auth::id() . $i . "." . time() . "." . $document->getClientOriginalExtension();

			$userDocument = userDocument::create(['user_id' => Auth::id(), 'document_type_id' => $request->table_id]);

			$files = Files::create([
				'title' => $fileName,
				'location' => "user_documents",
				'extension' => $document->getClientOriginalExtension()
			]);

			$userDocumentFiles = UserDocumentFiles::create([
				'user_document_id' => $userDocument->id,
				'file_id' => $files->id,
				'table_name' => $tableName,
				'table_id' => $request->table_id,
				'type' => $request->type,
			]);

			$document->move(public_path('user_documents'), $fileName);
			chmod(public_path('user_documents'.'/'.$fileName), 0755);

			$i++;
		}

		if ($userDocumentFiles) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Document added successfully',
				'success' => true
			]);
		}
	}

	function DocumentListing()
	{
		$documents = UserDocumentFiles::join('files', 'user_document_files.file_id', '=', 'files.id')
			->join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')->where('user_id', '=', Auth::id())->select('title', 'user_document_files.id as id', 'user_document_files.table_name as table', 'user_document_id', 'file_id', 'user_document_files.type as type', 'user_document_files.table_id as table_id', 'user_documents.document_type_id as doc_id')->groupBy('user_document_files.table_id');

		return Datatables::of($documents)
			->editColumn('title', function ($row) {
				$i = 0;
				$html = "";
				$files = UserDocumentFiles::where('table_id', '=', $row->table_id)->get();
				
				//echo $row->table."-".$row->table_id;
				$documentNames = $this->documentName($row->table, $row->doc_id);
				foreach ($files as $file) {
					$html .= "<a href='" . asset('user_documents/' . $file->getFile->title) . "' download>$documentNames[$i]</a>, ";
					$i++;
				}
				return rtrim($html, ", ");
			})
			->addColumn('action', function ($row) {
				$html = "<div>";
				$html .= "<button class='btn btn-icon mr-1 btn-outline-primary mr-1 document-edit' data-id='" . $row->table_id . "'><i class='fa fa-pencil'></i></button>";
				$html .= "<button class='btn btn-icon btn-outline-danger document-delete' data-id='" . $row->id . "'><i class='fa fa-trash'></i></button>";
				$html .= "</div>";
				return $html;
			})
			->rawColumns(['action', 'title'])
			->make('true');
	}

	function documentEdit(Request $request)
	{
		$files = UserDocumentFiles::where('table_id', '=', $request->id)
			->join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')
			->select('user_documents.document_type_id as doc_id', 'user_document_files.*')
			->get();
		$documents = [];
		$id = $request->id;
		$documentNames = $this->documentName($files[0]->table_name, $files[0]->doc_id);
		foreach ($files as $file) {
			$documents[] = $file->getFile->title;
		}

		return view('student.documents.edit', compact('documents', 'id', 'documentNames'));
	}

	function documentUpdate($id, Request $request)
	{

		
		$files = UserDocumentFiles::where('table_id', '=', $request->id)
			->join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')
			->select('user_documents.document_type_id as doc_id', 'user_document_files.*')
			->get();
		$documents = [];
		$id = $request->id;
		foreach ($files as $file) {
			$documents[] = $file->getFile->title;
		}
		$documentNames = $this->documentName($files[0]->table_name, $files[0]->doc_id);
		$validator = Validator::make($request->all(), ['document.*' => 'mimes:jpg,pdf'], ['document.required' => "Please add all document"]);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.documents.edit', compact('documents', 'id', ''))->withErrors($validator);
		}

		$files = UserDocumentFiles::where('table_id', '=', $id)->get();
		$i = 0;
		foreach ($files as $file) {

			if (!empty($request->document[$i])) {

				$file = Files::find($file->file_id);
				$oldFile = $file->title;
				$newFile = $id . "." . Auth::id() . $i . "." . time() . "." . $request->document[$i]->getClientOriginalExtension();
				$file->title = $newFile;
				$file->extension = $request->document[$i]->getClientOriginalExtension();
				$file->save();

				$request->document[$i]->move(public_path('user_documents'), $newFile);
				unlink(public_path('user_documents/' . $oldFile));
			}

			$i++;
		}

		return response()->json([
			'code' => 'success',
			'title' => 'Congratulations',
			'message' => 'Documents updated successfully',
			'success' => true
		]);
	}

	function documentDelete(Request $request)
	{
		// echo $request->id;
		$userDocumentFiles = UserDocumentFiles::find($request->id);
		$userDocument = UserDocument::find($userDocumentFiles->user_document_id);
		$file = Files::find($userDocumentFiles->file_id);
		$fileName = $file->title;
		if ($userDocumentFiles->delete() && $userDocument->delete() && $file->delete()) {

			unlink(public_path('user_documents/' . $fileName));

			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Document deleted successfully',
				'success' => true
			]);
		}
		// return $file->title;


	}

	public function uploadDocument(Request $request)
	{
		
		// NOTES
		// Steps to upload
		// 1. Upload file and get file_id - file_id
		// 2. Create record in user_document - user_document_id
		// 3. Store relation of Step 1 & Step 2 - user_document_files

		// How to check if file exists and upload
		// 1. Find in user_documents with document_type and document_type_id
		// 2. Delete the record in user_document_files and files && store files to delete
		// 3. Following Step 3 of Upload
		// 4. Delete Files

		// Form validation

		

		$validation_arr = [
			'document_type' => 'required',
			'document_file' => 'required'
		];
		if (request()->get('document_type') != "other") {
			$validation_arr['document_type_id'] = 'required';
		}
		$validator = Validator::make($request->all(), $validation_arr);

		if ($validator->fails()) {
		}
		// Prepare filename
		if ($request->document_file) {


			$fileName = Str::slug($request->document_name, "_") . "_" . Auth::id() . "_" . time() . "." . $request->document_file->getClientOriginalExtension();



			// Upload File - At present only one file at a time
			$upload_result = $request->document_file->move(public_path('user_documents'), $fileName);
			if (!$upload_result) {
				return response()->json([
					'success' => false,
					'code' => 'error',
					'title' => 'Error!',
					'message' => 'Something went wrong.'
				]);
			}
		} else {
			$fileName = "";
		}

		$result = $this->transactionalQuery($fileName, $request);

		if ($result['success']) {
			foreach ($result['files_to_delete'] as $file_name) {
				unlink(public_path($file_name));
			}
			return response()->json([
				'success' => true,
				'title' => 'Congratulations!',
				'message' => 'Document uploaded successfully',
				'code' => 'success'
			]);
		} else {
			unlink(public_path("user_documents/" . $fileName));
			return response()->json([
				'success' => false,
				'title' => 'Error!',
				'message' => 'Something went wrong.',
				'code' => 'error'
			]);
		}
	}

	function transactionalQuery($fileName, $request)
	{
		$files_to_delete = [];
		try {
			DB::transaction(function () use ($fileName, $request) {
				if ($request->document_type != "other") {
					// Find Record
					$where_cond = [
						'document_type' => $request->document_type,
						'document_type_id' => $request->document_type_id,
						'user_id' => auth()->user()->id
					];

					if ($request->has('application_id')) {
						$where_cond['application_id'] = $request->application_id;
					}

					$user_document_record = UserDocument::where($where_cond)->get();


					if ($user_document_record->count() == 0) {

						// Create User Document
						$user_document_record = new UserDocument;
						$user_document_record->document_type = $request->document_type;
						$user_document_record->document_type_id = $request->document_type_id;
						$user_document_record->user_id = auth()->user()->id;
						if ($request->has('application_id')) {
							$user_document_record->application_id = $request->application_id;
						}
						$user_document_record->save();
					} else {
						$user_document_record = $user_document_record[0];
						$files_to_delete = $user_document_record->deleteFileRecord();
					}
				} else {

					if (!empty(request()->get('document_id'))) {
						$user_document_record = UserDocument::findOrFail(request()->get('document_id'));
						$user_document_record->document_name = request()->get('document_name');
						$user_document_record->save();
						if (!empty($fileName)) {
							$files_to_delete = $user_document_record->deleteFileRecord();
						}
					} else {
						// Create User Document
						$user_document_record = new UserDocument;
						$user_document_record->document_type = $request->document_type;
						// $user_document_record->document_type_id = 0;
						$user_document_record->user_id = auth()->user()->id;
						$user_document_record->document_name = $request->document_name;
						$user_document_record->save();
					}


				}

				if (!empty($fileName)) {
					// Create File 
					$file = new Files;
					$file->title = $fileName;
					$file->location = "user_documents/" . $fileName;
					$file->extension = $request->document_file->getClientOriginalExtension();
					$file->save();

					// Store Relation in user document file
					$user_file = new UserDocumentFiles;
					$user_file->user_document_id = $user_document_record->id;
					$user_file->file_id = $file->id;
					$user_file->save();
				}
			});
		} catch (\Exception $e) {
			return [
				'success' => false,
				'files_to_delete' => []
			];
		}
		return [
			'success' => true,
			'files_to_delete' => $files_to_delete
		];
	}

	function deleteFile(Request $request)
	{

		$userDocumentFiles = UserDocumentFiles::find($request->id)->delete();
		$userDocument = UserDocument::find($request->document_id)->delete();
		$file = Files::find($request->id);
		$fileName = $file->title;
		$filesDelete = $file->delete();
		if ($userDocumentFiles && $userDocument && $filesDelete) {

			unlink(public_path('user_documents/' . $fileName));

			$documentTyps = DocumentType::select('id', 'title', 'document_limit')->get();

			$files = UserDocumentFiles::join('files', 'user_document_files.file_id', '=', 'files.id')
				->join('user_documents', 'user_document_files.user_document_id', '=', 'user_documents.id')->where('user_id', '=', Auth::id())->select('title', 'document_type_id', 'user_document_files.id as id', 'user_document_id', 'file_id')
				->get();


			return view('student.upload_documents', compact('documentTyps', 'files'));
		} else {
		}
	}

	public function backgroundInformation(Request $request)
	{
		$userMeta = UserMeta::where('user_id', '=', Auth::id())->get();
		$appliedVisaCountryId = $userMeta->where('meta_key', 'applied_visa_country')->first();
		$appliedVisa = $userMeta->where('meta_key', 'applied_visa')->first();
		//applied country id
		$appliedVisaCountryId = isset($appliedVisaCountryId->meta_value) ? $appliedVisaCountryId->meta_value : '';
		$appliedVisaCountry = DB::table('countries')->where('id', $appliedVisaCountryId)->select('name', 'id')->get();

		$appliedVisa = isset($appliedVisa->meta_value) ? $appliedVisa->meta_value : '';
		$refuseVisa = $userMeta->where('meta_key', 'visa_refusal')->first();
		$refuseVisa = isset($refuseVisa) ? $refuseVisa->meta_value : '';
		$refuseVisaType = $userMeta->where('meta_key', 'visa_refusal_type')->first();
		$refuseVisaType = isset($refuseVisaType) ? $refuseVisaType->meta_value : '';

		//refuse country id
		$refuseCountryId = $userMeta->where('meta_key', 'visa_refusal_country')->first();
		$refuseCountryId = isset($refuseCountryId->meta_value) ? $refuseCountryId->meta_value : '';
		$refuseCountry = DB::table('countries')->where('id', $refuseCountryId)->select('name', 'id')->get();


		$validator = Validator::make($request->all(), [
			'visa_refusal_details' => "required_if:visa_refusal,==,1",
		], [
			'applied_visa_country.required_if' => 'Please Select country when applied visa is yes',
			'visa_refusal_country.required_if' => 'Please Select country when visa refusal is yes',
			'visa_refusal_details.required_if' => 'Please fill visa refusal details when visa refusal is yes'
		]);

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('student.background_infomation', compact('appliedVisa', 'appliedVisaCountry', 'refuseVisa', 'refuseCountry', 'refuseVisaType'))->withErrors($validator);
		}

		$userId = Auth::id();

		//applied visa
		if ($request->applied_visa == 0) {
			$apliedVisa = $this->updateMeta(Auth::id(), 'applied_visa', $request->applied_visa);
			$deleteVisaCountry = UserMeta::where('user_id', Auth::id())->where('meta_key', 'applied_visa_country')->delete();
		}


		if ($request->applied_visa == 1) {
			$apliedVisa = $this->updateMeta(Auth::id(), 'applied_visa', $request->applied_visa);
			//$apliedVisaCountry = $this->updateMeta(Auth::id(),'applied_visa_country',$request->applied_visa_country);
		}

		//refuse visa
		if ($request->visa_refusal == 0) {
			$refuseVisa = $this->updateMeta(Auth::id(), 'visa_refusal', $request->visa_refusal);
			$deleterefuseCountry = UserMeta::where('user_id', Auth::id())->where('meta_key', 'visa_refusal_country')->delete();
			$deleterefuseType = UserMeta::where('user_id', Auth::id())->where('meta_key', 'visa_refusal_type')->delete();
		}

		if ($request->visa_refusal == 1) {

			$refuseVisa = $this->updateMeta(Auth::id(), 'visa_refusal', $request->visa_refusal);
			// $refuseVisaCountry = $this->updateMeta(Auth::id(),'visa_refusal_country', $request->visa_refusal_country);
			$refuseVisaType = $this->updateMeta(Auth::id(), 'visa_refusal_type', $request->visa_refusal_details);
		}

		return response()->json([
			'success' => true
		]);
	}

	public function updateMeta($user_id, $key, $value)
	{
		$meta = UserMeta::where('user_id', $user_id)->where('meta_key', $key)->first();
		$userMeta = isset($meta) ? $meta : new UserMeta;
		$userMeta->meta_key = $key;
		$userMeta->meta_value = $value;
		$userMeta->user_id = $user_id;
		return $userMeta->save();
	}

	function workExperience()
	{
		return view('student.work_experience.create');
	}

	function workExperienceStore(Request $request)
	{

		$workExperience = new WorkExperience;
		$workExperience->user_id = Auth::id();
		$workExperience->organization = $request->organization;
		$workExperience->position = $request->position;
		$workExperience->profile = $request->job_profile;
		$workExperience->sallery_mode = $request->mode;
		$workExperience->is_working = isset($request->current_working) ? $request->current_working : 0;
		$workExperience->work_upto = isset($request->current_working) ? null : date('Y-m-d', strtotime($request->working_upto));
		$workExperience->work_from = date('Y-m-d', strtotime($request->working_from));

		if ($workExperience->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Work experience added successfully.',
				'success' => true
			]);
		}
	}

	function workExperienceList()
	{

		$workExperience = WorkExperience::where('user_id', Auth::id());

		return Datatables::of($workExperience)
			->addColumn('action', function ($row) {
				$html = "<div>";
				$html .= "<button class='btn mr-1 btn-outline-primary btn-icon experience-edit' data-id='" . $row->id . "'><i class='fa fa-pencil'></i></button>";
				$html .= "<button class='btn btn-icon btn-outline-danger experience-delete' data-id='" . $row->id . "'><i class='fa fa-trash'></i></button>";
				$html .= "</div>";
				return $html;
			})
			->editColumn('is_working', function ($row) {
				if ($row->is_working == 1) {
					return "Yes";
				} else {
					return "No";
				}
			})
			->editColumn('work_from', function ($row) {
				return date('d M Y', strtotime($row->work_from));
			})
			->editColumn('work_upto', function ($row) {
				if ($row->work_upto != "")
					return date('d M Y', strtotime($row->work_upto));
				else
					return "N/A";
			})
			->rawColumns(['action']) 
			->make(true);
	}

	public function deleteExperience(Request $request)
	{
		$workExperience = WorkExperience::find($request->id);
		if ($workExperience->delete()) {

			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Work experience deleted successfully',
				'success' => true
			]);
		}
	}

	public function editExperience(Request $request)
	{
		$workExperience = WorkExperience::find($request->id);
		return view('student.work_experience.edit', compact('workExperience'));
	}

	public function updateExperience($id, Request $request)
	{

		$workExperience = WorkExperience::find($id);
		$workExperience->user_id = Auth::id();
		$workExperience->organization = $request->organization;
		$workExperience->position = $request->position;
		$workExperience->profile = $request->job_profile;
		$workExperience->sallery_mode = $request->mode;
		$workExperience->is_working = isset($request->current_working) ? $request->current_working : 0;
		$workExperience->work_upto = isset($request->current_working) ? null : date('Y-m-d', strtotime($request->working_upto));
		$workExperience->work_from = date('Y-m-d', strtotime($request->working_from));

		if ($workExperience->save()) {
			return response()->json([
				'code' => 'success',
				'title' => 'Congratulations',
				'message' => 'Work experience updated successfully',
				'success' => true
			]);
		}
	}

	function documentName($tableName, $tableId)
	{

		$documentNames = [];

		if ($tableName == "study_levels"):
			$userAcademic = UserAcademic::find($tableId);
			if ($userAcademic->study_levels_id != 8):
				$documentArr = $userAcademic->getDocumentType;
				foreach ($documentArr as $documentName):
					$documentNames[] = $documentName->name;
				endforeach;
			else:
				for ($i = 1; $i < 6; $i++):
					$documentNames[] = "other-" . $i;
				endfor;
			endif;
			return $documentNames;
		elseif ($tableName == "tests"):
			$testId = SpecialTest::find($tableId)->test_type_id;
			$testName = Test::find($testId);
			array_push($documentNames, $testName->test_name);
			return $documentNames;
		else:
			$test = DocumentType::find($tableId);
			array_push($documentNames, $test->title);
			return $documentNames;
		endif;
	}
	//end of above function

}
//end of class
