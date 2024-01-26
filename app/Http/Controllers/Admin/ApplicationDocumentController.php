<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\ApplicationDocument;
use App\Models\ApplicationDocumentCountry;

class ApplicationDocumentController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth:admin');
		config(['contries' => Country::get()]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		// foreach($applicationDocument->documentCountry as $documentCountry){
		//       echo $documentCountry->country->name."<br>";
		// }

		if (request()->ajax()) {
			$applicationDocument = ApplicationDocument::get();
			return Datatables::of($applicationDocument)
				->addColumn('countries', function ($row) {
					$str = "";
					foreach ($row->documentCountry as $documentCountry) {
						$str .= $documentCountry->country->name . ", ";
					}

					return substr($str, 0, -2);
				})
				->rawColumns(['countries'])
				->make(true);
		} else {
			$breadcrumbs = [
				['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Application Document"]
			];
			return view('dashboard.application_docs.index', ['breadcrumbs' => $breadcrumbs]);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('dashboard.application_docs.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), $this->validations());

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('dashboard.application_docs.create')->withErrors($validator);
		}

		$application = new ApplicationDocument;
		$application->name = $request->name;
		$application->required = $request->document_required;
		$applicationDocument = $application->save();

		foreach ($request->countries as $country) {
			$applicationDocumentCountry = ApplicationDocumentCountry::create(['application_document_id' => $application->id, 'country_id' => $country]);
		}
		if ($applicationDocument || $applicationDocumentCountry) {

			return response()->json([
				'code' => 'success',
				'title' => 'Success',
				'message' => 'Application document added successfully',
				'success' => true
			]);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$countryId = [];
		$applicationDocument = ApplicationDocument::find($id);

		foreach ($applicationDocument->documentCountry as $documentCountry) {
			$countryId[] = $documentCountry->country->id;
		}

		return view('dashboard.application_docs.edit', ['applicationDocument' => $applicationDocument, 'countryId' => $countryId]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$countryId = [];
		$applicationDocument = ApplicationDocument::find($id);

		foreach ($applicationDocument->documentCountry as $documentCountry) {
			$countryId[] = $documentCountry->country->id;
		}

		$validator = Validator::make($request->all(), $this->validations($id));

		if ($validator->fails()) {
			$validator->errors()->add('form_error', 'Error! Please check below');
			$request->flash();
			return view('dashboard.application_docs.edit', ['applicationDocument' => $applicationDocument, 'countryId' => $countryId])->withErrors($validator);
		}

		$applicationDocument->name = $request->name;
		$applicationDocument->required = $request->document_required;
		$applicationDocum = $applicationDocument->save();
		ApplicationDocumentCountry::where('application_document_id', '=', $id)->delete();
		foreach ($request->countries as $country) {
			$applicationDocumentCountry = ApplicationDocumentCountry::create(['application_document_id' => $id, 'country_id' => $country]);
		}
		if ($applicationDocument || $applicationDocumentCountry) {

			return response()->json([
				'code' => 'success',
				'title' => 'Success',
				'message' => 'Application document updated successfully.',
				'success' => true
			]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$record = ApplicationDocument::findOrFail($id);

		if ($record->delete()) {
			ApplicationDocumentCountry::where('application_document_id', $id)->delete();
			return response()->json([
				'success' => true,
				'code' => 'success',
				'title' => 'Success',
				'message' => 'Application document deleted successfully!'
			]);
		} else {
			return response()->json([
				'success' => false,
				'code' => 'error',
				'title' => 'Error',
				'message' => 'Something went wrong!'
			]);
		}

	}


	public function validations($id = "")
	{
		return [
			'name' => 'required|unique:application_documents,name,' . $id,
			'countries' => 'required|array|min:1',
			'document_required' => 'required',
		];
	}
}
