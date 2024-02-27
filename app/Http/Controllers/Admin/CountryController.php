<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:admin');
      $this->middleware('userspermission:countries_view',['only'=>['index']]);
    }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (request()->ajax()) {
      $countries = Country::all();
      return DataTables::of($countries)
        ->make(true);
    } else {
      $breadcrumbs = [
        ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Country"]
      ];
      return view('dashboard.country.index', ['breadcrumbs' => $breadcrumbs]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('dashboard.country.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'code' => 'required|max:2',
      'phone_code' => 'required|numeric|digits_between:1,4',
    ]);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      return view('dashboard.country.create')->withErrors($validator);
    }

    $country = new Country;
    $country->name = $request->name;
    $country->code = Str::upper($request->code);
    $country->phonecode = $request->phone_code;

    if ($country->save()) {

      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Country Added successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.country.create');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Country  $country
   * @return \Illuminate\Http\Response
   */
  public function show(Country $country)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Country  $country
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $country =  Country::find($id);
    return view('dashboard.country.edit', ['country' => $country]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Country  $country
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
      $country = Country::find($id);

      $validator = Validator::make($request->all(), [
        'name' => 'required',
        'code' => 'required|max:2',
        'phone_code' => 'required|numeric|digits_between:1,4',
      ]);

      if ($validator->fails()) {
        $validator->errors()->add('form_error', 'Error! Please check below');
        return view('dashboard.country.edit',compact('country'))->withErrors($validator);
      }

      $country->name = $request->name;
      $country->code = Str::upper($request->code);
      $country->phonecode = $request->phone_code;

      if ($country->save()) {

        return response()->json([
          'code' => 'success',
          'title' => 'Congratulations',
          'message' => 'Country updated successfully',
          'success' => true
        ]);
      } else {
        return view('dashboard.edit.create');
      }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Country  $country
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      $country = Country::find($id);
      $country->delete();
        if($country->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'Country deleted successfully',
                'success' => true
            ]);
        }
  }
}
