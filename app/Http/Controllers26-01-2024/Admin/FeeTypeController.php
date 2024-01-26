<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feetype;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class FeeTypeController extends Controller
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
        $feeType =  Feetype::get();
        if (request()->ajax()) {
            return Datatables::of($feeType)
                ->make(true);
        }
        else{
            $breadcrumbs = [
                ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Fee Types"]
            ];
            return view('dashboard.fee_type.index', [
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
        return view('dashboard.fee_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),['name' => 'required']);

        if($validator->fails()){
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.fee_type.create')->withErrors($validator);
        }

        $feetype = new  Feetype;
        $feetype->name = $request->name;

        if($feetype->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Fee Type Added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.fee_type.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feetype  $feetype
     * @return \Illuminate\Http\Response
     */
    public function show(Feetype $feetype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feetype  $feetype
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feetype = Feetype::findOrFail($id);
        return view('dashboard.fee_type.edit',compact('feetype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feetype  $feetype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $feetype = Feetype::findOrFail($id);
        $validator = Validator::make($request->all(),['name' => 'required']);

        if($validator->fails()){
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.fee_type.edit',compact('feetype'))->withErrors($validator);
        }

        $feetype = Feetype::findorFail($id);
        $feetype->name = $request->name;

        if($feetype->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Fee Type Edit successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.fee_type.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feetype  $feetype
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feeType = Feetype::findOrFail($id);
        $feeType->delete();
        if($feeType->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'Feetype Successfully Deleted',
                'success' => true
            ]);
        } else {
            return view('dashboard.fee_type.edit');
        }
    }
}
