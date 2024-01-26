<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Study;
use App\Models\ProgramLevel;
use App\Models\Campus;
use App\Models\Intake;
use App\Models\CampuseProgram;
use App\Models\ProgramArea;
use App\Models\ProgramIntakes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin')->except([
      'getPrograms'
    ]);
    config([
      'study_areas' => Study::where('parent_id', 0)->get()
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $programs = Program::leftJoin('program_levels', 'programs.program_level_id', '=', 'program_levels.id')
      ->leftJoin('study_areas', 'programs.study_area_id', '=', 'study_areas.id')
      ->select('programs.id', 'programs.name as name', 'program_levels.name as program_level', 'study_areas.name as study_area', 'programs.duration')
      ->get();

    if (request()->ajax()) {
      return DataTables::of($programs)
        ->editColumn('name', function ($row) {
          return tooltip(Str::limit($row->name, 20, '...'), $row->name);
        })
        ->editColumn("duration", function ($row) {
          return $row->duration . " months";
        })
        ->rawColumns(['name'])
        ->make(true);
    } else {
      $breadcrumbs = [
        ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Programs"]
      ];
      return view('dashboard.programs.index', [
        'breadcrumbs' => $breadcrumbs
      ]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function create()
  {
    $studyAreas = Study::select('id', 'name')->get();
    $programLevels = ProgramLevel::select('id', 'name')->get();
    return view('dashboard.programs.create', compact('studyAreas', 'programLevels'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $studyAreas = Study::select('id', 'name')->get();
    $programLevels = ProgramLevel::select('id', 'name')->get();
    $validator = Validator::make($request->all(), ['name' => 'required|unique:programs,name', 'program_level_id' => 'required', 'study_area_id' => 'required', 'duration' => 'required']);

    if (!empty($request->study_area_id)) {
      $sub_areas = Study::where('parent_id', $request->study_area_id)->get();
      config([
        'sub_study_areas' => $sub_areas
      ]);
    }
    //'intake'=> 'required','program_link'=>'required',
    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      $request->flash();
      return view('dashboard.programs.create', compact('studyAreas', 'programLevels', 'campuses', 'intakes'))->withErrors($validator);
    }



    $program = new Program;
    $program->name = $request->name;
    $program->program_level_id = $request->program_level_id;
    $program->study_area_id = $request->study_area_id;
    $program->duration = $request->duration;

    if ($program->save()) {
      $program->saveSubStudyArea($request->sub_study_area_ids);

      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Program added successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.programs.create', compact('studyAreas', 'programLevels', 'campuses', 'intakes'));
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $program = Program::find($id);
    config([
      'sub_study_areas' => Study::where('parent_id', $program->study_area_id)->get()
    ]);

    $studyAreas = Study::select('id', 'name')->get();
    $programLevels = ProgramLevel::select('id', 'name')->get();

    return view('dashboard.programs.edit', compact('studyAreas', 'programLevels', 'program'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $studyAreas = Study::select('id', 'name')->get();
    $programLevels = ProgramLevel::select('id', 'name')->get();
    $program = Program::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:programs,name,' . $id,
      'program_level_id' => 'required',
      'study_area_id' => 'required',
      'duration' => 'required'
    ]);

    if (!empty($request->study_area_id)) {
      $sub_areas = Study::where('parent_id', $request->study_area_id)->get();
      config([
        'sub_study_areas' => $sub_areas
      ]);
    }

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      $request->flash();
      return view('dashboard.programs.edit', compact('studyAreas', 'programLevels', 'campuses', 'program'))->withErrors($validator);
    }


    $program = Program::findOrFail($id);
    $program->name = $request->name;
    $program->program_level_id = $request->program_level_id;
    $program->study_area_id = $request->study_area_id;
    $program->duration = $request->duration;

    if ($program->save()) {
      $program->saveSubStudyArea($request->sub_study_area_ids);
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Program updated successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.programs.edit', compact('studyAreas', 'programLevels', 'campuses', 'program'));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $program = Program::find($id);
    $program->delete();
    return response()->json([
      'code' => 'success',
      'title' => 'Deleted',
      'message' => 'Program deleted successfully',
      'success' => true
    ]);
  }

  function getPrograms(Request $request)
  {
    if (isset($request->name))
      return Program::where('name', 'LIKE', "%{$request->name}%")->select('id', 'name as text')->get();
  }
}
