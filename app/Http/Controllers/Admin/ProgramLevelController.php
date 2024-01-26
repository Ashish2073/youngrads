<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramLevel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProgramLevelController extends Controller
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
        $programs = ProgramLevel::get();

        if (request()->ajax()) {
            return DataTables::of($programs)
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Program Levels"]
            ];
            return view('dashboard.program_level.index', [
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
        return view('dashboard.program_level.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required', 'slug' => 'required|unique:program_levels']);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.program_level.create')->withErrors($validator);
        }

        $program = new ProgramLevel;
        $program->name = $request->name;
        $program->slug = $request->slug;

        if ($program->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Program Level added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.program_level.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProgramLevel  $programLevel
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramLevel $programLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProgramLevel  $programLevel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $program = ProgramLevel::findOrFail($id);
        return view('dashboard.program_level.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProgramLevel  $programLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['name' => 'required', 'slug' => 'required|unique:program_levels,slug,' . $id]);
        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.program_level.edit')->withErrors($validator);
        }

        $program = ProgramLevel::findorFail($id);
        $program->name = $request->name;
        $program->slug = $request->slug;
        if ($program->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Program Level updated successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.program_level.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProgramLevel  $programLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $programLevel = ProgramLevel::findOrFail($id);
        $programLevel->delete();
        if ($programLevel->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'Program Level deleted successfully',
                'success' => true
            ]);
        }
    }
}
