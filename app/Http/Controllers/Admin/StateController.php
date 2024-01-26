<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
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
    $states = state::leftJoin('countries', 'states.country_id', '=', 'countries.id')->select('states.*', 'countries.name as country')->get();
    if (request()->ajax()) {
      return DataTables::of($states)
        ->make(true);
    } else {
      $breadcrumbs = [
        ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "States"]
      ];

      return view('dashboard.states.index', ['breadcrumbs' => $breadcrumbs]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('dashboard.states.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), ['name' => 'required', 'country' => 'required']);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      return view('dashboard.states.create')->withErrors($validator);
    }

    $state = new State;
    $state->name = $request->name;
    $state->country_id = $request->country;
    if ($state->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'State added successfully',
        'success' => true
      ]);

    } else {
      return view('dashboard.states.create');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\State  $state
   * @return \Illuminate\Http\Response
   */
  public function show(State $state)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\State  $state
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $state = State::find($id);

    return view('dashboard.states.edit', ['state' => $state]);
  }

  /** 
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\State  $state
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $state = State::find($id);
    $validator = Validator::make($request->all(), ['name' => 'required', 'country' => 'required']);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      return view('dashboard.states.edit', compact('edit'))->withErrors($validator);
    }

    $state->name = $request->name;
    $state->country_id = $request->country;
    if ($state->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'State updated successfully',
        'success' => true
      ]);

    } else {
      return view('dashboard.states.edit');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\State  $state
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $state = State::find($id);
    $state->delete();
    if ($state->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Deleted',
        'message' => 'State deleted successfully',
        'success' => true
      ]);
    }
  }




}
