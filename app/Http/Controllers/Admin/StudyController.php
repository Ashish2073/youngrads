<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Study;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\ProgramArea;
use Str;

class StudyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $study_areas = Study::where('parent_id', 0)->get();
        config([
            'study_areas' => $study_areas
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $records = Study::leftJoin('study_areas as parent_study', 'study_areas.parent_id', '=', 'parent_study.id')
                ->select('study_areas.*', "parent_study.name as parent_name");
            return DataTables::of($records)
                ->editColumn('parent_name', function ($row) {
                    if ($row->parent_id == 0) {
                        return "N/A";
                    }
                    return \Str::limit($row->parent_name, 40, "...");
                })
                ->editColumn('name', function ($row) {

                    return \Str::limit($row->name, 40, "...");
                })
                ->make(true);

        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Study Areas"]
            ];
            return view('dashboard.study_area.index', [
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
        return view('dashboard.study_area.create');
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
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    $records = Study::where(['name' => $value, 'parent_id' => request()->get('parent_id')])
                        ->get();
                    if ($records->count() > 0) {
                        $fail("This {$attribute} has already been taken.");
                    }
                }
            ]

        ]);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.study_area.create')->withErrors($validator);
        }

        $program = new Study;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name, "-");
        $program->parent_id = $request->parent_id;

        if ($program->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Study added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.study_area.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function show(Study $study)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $study = Study::findOrFail($id);
        return view('dashboard.study_area.edit', compact('study'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $records = Study::where('name', request()->get('name'))->where('id', '<>', $id);
                    if (request()->has('parent_id')) {
                        $records->where('parent_id', request()->get('parent_id'));
                    } else {
                        $records->where('parent_id', 0);
                    }
                    $records = $records->get();
                    if ($records->count() > 0) {
                        $fail("This {$attribute} has already been taken.");
                    }
                }
            ]
        ]);

        $study = Study::find($id);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.study_area.edit', compact('study'))->withErrors($validator);
        }

        $program = Study::findOrFail($id);
        $program->name = $request->name;
        $program->parent_id = $request->parent_id;

        if ($program->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Study area updated successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.study_area.edit', compact('study'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Study  $study
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $study = Study::findOrFail($id);
        $study->delete();
        if ($study->save()) {
            ProgramArea::where('study_area_id', $id)->delete();
            Program::where('study_area_id', $id)->update(['study_area_id' => null]);
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'Study area deleted successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.study_area.edit');
        }
    }

    public function getSubStudyAreas($id)
    {
        return Study::where('parent_id', $id)->get();
    }


}
