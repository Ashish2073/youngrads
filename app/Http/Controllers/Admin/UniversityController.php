<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class UniversityController extends Controller  
{

    public function __construct()
    {
        $this->middleware('auth:admin')->except('selectUniverstiy');
        $this->middleware('userspermission:universities_view',['only'=>['index']]);
        $this->middleware('userspermission:universities_add',['only'=>['create','store']]);
        $this->middleware('userspermission:universities_edit',['only'=>['edit','update']]);
        $this->middleware('userspermission:universities_delete',['only'=>['destroy']]); 

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

        $universities = University::all(); 
 
        if (request()->ajax()) {

            if((session('permissionerror'))){
               
           
                return response()->json(['errorpermissionmessage'=>session('permissionerror')]);
              


            }
          

            return Datatables::of($universities)
            
        
            
                ->rawColumns(['name'])

                ->make(true);



        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Universities"]
            ];
            return view('dashboard.university.index', [
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

        return view('dashboard.university.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['university' => 'required|unique:universities,name']);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.university.create')->withErrors($validator);
        }

        $university = new University;
        $university->name = $request->university;

        if ($university->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'University added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.university.create');
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
        $university = University::findOrFail($id);
        return view('dashboard.university.edit', compact('university'));
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
        $university = University::findOrFail($id);
        $validator = Validator::make($request->all(), ['university' => 'required|unique:universities,name,' . $id]);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.university.edit', compact('university'))->withErrors($validator);
        }

        $university = University::findOrFail($id);
        $university->name = $request->university;

        if ($university->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'University updated successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.university.edit');
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
        $university = University::findOrFail($id);
        $university->delete();
        if ($university->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'University deleted successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.university.edit');
        }
    }
    public function selectUniverstiy(Request $request)
    {
        if (isset($request->name))
            return University::where('name', 'LIKE', "%{$request->name}%")->select('id', 'name as text')->get();
    }
}
