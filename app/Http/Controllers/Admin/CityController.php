<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (request()->ajax()) {
      $cities = City::leftJoin('states', 'cities.state_id', '=', 'states.id')->select('cities.*', 'states.name as state');
      return DataTables::of($cities)
        ->make(true);
    } else {

      $breadcrumbs = [
        ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Cities"]
      ];
      return view('dashboard.cities.index', ['breadcrumbs' => $breadcrumbs]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('dashboard.cities.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), ['name' => 'required', 'state' => 'required']);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      return view('dashboard.cities.create')->withErrors($validator);
    }

    $city = new City;
    $city->name = $request->name;
    $city->state_id = $request->state;

    if ($city->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'City added successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.cities.create');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\City  $city
   * @return \Illuminate\Http\Response
   */
  public function show(City $city)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\City  $city
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {

    $city = City::find($id);

    return view('dashboard.cities.edit', ['city' => $city]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\City  $city
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $city = City::find($id);
    $validator = Validator::make($request->all(), ['name' => 'required', 'state' => 'required']);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      return view('dashboard.cities.edit', compact('city'))->withErrors($validator);
    }


    $city->name = $request->name;
    $city->state_id = $request->state;

    if ($city->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'City updated successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.cities.create');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\City  $city
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $state = City::find($id);
    $state->delete();
    if ($state->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Deleted',
        'message' => 'City deleted successfully',
        'success' => true
      ]);
    }
  }
}
