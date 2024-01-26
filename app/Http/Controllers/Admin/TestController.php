<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TEST;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
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
            $tests = Test::where('parent_id', 0);
            return Datatables::of($tests)
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Test"]
            ];
            return view('dashboard.test.index', [
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
        return view('dashboard.test.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['test_name' => 'required']);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.test.create')->withErrors($validator);
        }

        $test = new Test;
        $test->test_name = $request->test_name;

        if ($test->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Test added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.test.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TEST  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TEST  $test
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $test = Test::findOrFail($id);
        return view('dashboard.test.edit', compact('test'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TEST  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $test = Test::findorFail($id);
        $validator = Validator::make($request->all(), ['test_name' => 'required']);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.test.edit', compact('test'))->withErrors($validator);
        }


        $test->test_name = $request->test_name;

        if ($test->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Test updated successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.test.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TEST  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        //
    }
}
