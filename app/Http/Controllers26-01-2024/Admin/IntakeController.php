<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intake;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
class IntakeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
           $intakes = Intake::get();
           return Datatables::of($intakes)
                   ->make(true);

        }else{
            $breadcrumbs = [
                ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Intakes"]
            ];
            return view('dashboard.intake.index', [
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
        return view('dashboard.intake.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),['name' => 'required','type' => 'required']);

        if($validator->fails()){
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.intake.create')->withErrors($validator);
        }

        $program = new  Intake;
        $program->name = $request->name;
        $program->type = $request->type;

        if($program->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Intake added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.intake.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function show(Intake $intake)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $intake = Intake::findOrFail($id);
        return view('dashboard.intake.edit',compact('intake'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $intake = Intake::findOrFail($id);
        $validator = Validator::make($request->all(),['name' => 'required','type' => 'required']);

        if($validator->fails()){
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.intake.edit', compact('intake'))->withErrors($validator);
        }

        $program = Intake::findOrFail($id);
        $program->name = $request->name;
        $program->type = $request->type;

        if($program->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Intake updated successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.intake.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Intake  $intake
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $intake = Intake::findOrFail($id);
        $intake->delete();
        if($intake->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'Intake deleted successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.fee_type.edit');
        }
    }
}
