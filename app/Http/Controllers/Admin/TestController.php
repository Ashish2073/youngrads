<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TEST;
use App\Models\SubTest;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $parent_test = TEST::where('parent_id', 0)->get();
        config([
            'parent_test' => $parent_test
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
            $tests = Test::where('parent_id', 0);
            $testDeatailsData = Test::query()
                ->leftJoin('special_test_sub', 'tests.id', '=', 'special_test_sub.test_id')
                ->select('tests.id as id', 'special_test_sub.name as childtestname', 'tests.test_name as testname', 'tests.max as testsmax', 'special_test_sub.max as childtestmax');




            return Datatables::of($testDeatailsData)
                ->addColumn('testname', function ($row) {
                    return ($row->testname) ? ucwords($row->testname) : 'N/A';

                })
                ->addColumn('testsmax', function ($row) {
                    return $row->testsmax ?? 'N/A';

                })
                ->addColumn('childtestname', function ($row) {
                    return ($row->childtestname) ? ucwords($row->childtestname) : 'N/A';

                })

                ->addColumn('childtestmax', function ($row) {
                    return $row->childtestmax ?? 'N/A';

                })

                ->rawColumns(['testname', 'testsmax', 'childtestname', 'childtestmax', 'id'])
                ->make(true);


        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"],
                ['name' => "Test"]
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
        $validator = Validator::make($request->all(), [
            'test_name' => 'required',
            function ($attribute, $value, $fail) {
                $records = SubTest::where(['name' => $value, 'test_id' => request()->get('parent_id')])
                    ->get();
                if ($records->count() > 0) {
                    $fail("This {$attribute} has already been taken.");
                }
            }
        ]);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.test.create')->withErrors($validator);
        }

        if ($request->parent_id == 0) {
            $test = new Test;
            $test->test_name = $request->test_name;
            $test->parent_id = 0;
            $test->max = $request->test_number;
            $test->save();

        } else {
            $test = new SubTest;
            $test->name = $request->test_name;
            $test->test_id = $request->parent_id;
            $test->max = $request->test_number;
            $test->save();
        }



        // $childtest=new SubTest;
        // $childtest->name


        if ($test) {
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
    public function edit($string)
    {


        $str = $string;
        $length = strlen($str);
        $array[0] = substr($str, 0, $length - 1); // "Speaking"
        $array[1] = substr($str, -1);

        $test = TEST::findOrFail($array[1]);
        $childtest = SubTest::where('name', $array[0])->where('test_id', $array[1])->get();




        return view('dashboard.test.edit', compact('test', 'childtest'));
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

        ;
        $validator = Validator::make($request->all(), ['test_name' => 'required']);


        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.test.edit', compact('test'))->withErrors($validator);
        }



        $testnew = TEST::findorFail($request->parent_id);



        // $testnew->test_name = $request->test_name;
        // $testnew->max = $request->test_number_max;
        // $testnew->save();


        $test = TEST::where('id', $request->parent_id)->update([
            'test_name' => $request->test_name,
            'max' => $request->test_number_max,
        ]);

        if ($testnew) {
            $childtest = SubTest::where('id', $request->child_id)->where('test_id', $request->parent_id)->update([
                'name' => $request->sub_test_name,
                'max' => $request->sub_test_number_max
            ]);

        }


        if ($testnew) {
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
