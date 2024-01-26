<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CampusProgram;
use Yajra\Datatables\Datatables;
use App\Models\Program;
use App\Models\Campus;
use App\Models\Intake;
use App\Models\CampusProgramIntake;
use Illuminate\Support\Facades\Validator;
use App\Models\Feetype;
use App\Models\Currency;
use App\Models\CampusProgramFee;
use App\Models\University;
use App\Models\Test;
use App\Models\CampusProgramTest;
use Illuminate\Validation\Rule;
use CampusProgramFees;

class CampusProgramController extends Controller
{
    public $templateData;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->templateData = [
            'universties' => University::all(),
            'programs' => Program::all(),
            'intakes' => Intake::all(),
            'feeTypes' => Feetype::all(),
            'currencies' => Currency::all(),
            'tests' => Test::where('parent_id', 0)->get(),
        ];
        config([
            'universties' => University::all(),
            'programs' => Program::all(),
        ]);
        if (!empty(request()->university)) {
            config([
                'campuses' => Campus::where('university_id', request()->university)->get()
            ]);
        } else {
            config([
                'campuses' => []
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campusPrograms = CampusProgram::join('campus', 'campus_programs.campus_id', '=', 'campus.id')
            ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
            ->leftJoin('universities', 'universities.id', '=', 'campus.university_id')
            ->select('campus_programs.id', 'universities.name as university', 'campus.name as campus', 'programs.name as program')
            ->get();
        if (request()->ajax()) {
              return Datatables::of($campusPrograms)
                ->addColumn('action', function ($row) {
                    return "<a href=" . route('admin.campus-program.edit', $row->id) . " class='btn btn-primary'>Update</a>";
                })

                ->addColumn('university', function ($row) {
                    return $row->university;
                })
                ->addColumn('campus', function ($row) {
                    return $row->campus;
                })
                
                ->addColumn('program', function ($row) {
                    return $row->program;
                })








                ->rawColumns(['action','university','campus','program'])
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => 'Campus Program']
            ];
            return view('dashboard.campus_program.index', [
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
        $breadcrumbs = [
            ['link' => "admin.home", 'name' => "Dashboard"],
            ['link' => 'admin.campus-programs', 'name' => 'Campus Program'],
            ['name' => 'Create Campus Program']
        ];
        $this->templateData['breadcrumbs'] = $breadcrumbs;
        return view('dashboard.campus_program.create')->with($this->templateData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campus = $request->campus;
        $program = $request->program;
        session()->put('error', 'Error! Please check below.');

        if (!empty($request->get('university'))) {
            config([
                'campuses' => Campus::where('university_id', $request->get('university'))->get()
            ]);
        }
        $request->validate([
            'campus' => 'required',
            'program' => ['required', Rule::unique('campus_programs', 'program_id')->where(function ($query) use ($campus, $program) {
                return $query->where('campus_id', '=', $campus);
            })],
            'intakes' => 'required|array|min:1',
            'campus_program_duration' => 'required|numeric',
        ], ['program.unique' => "Program already exists in the Campus."]);

        session()->forget('error');
        $campusProgram = CampusProgram::create([
            'campus_id' => $request->campus,
            'program_id' => $request->program,
            'entry_requirment' => $request->entry_requirement,
            'campus_program_duration' => $request->campus_program_duration
        ]);

        if ($campusProgram) {
            // Storing Intakes
            foreach ($request->intakes as $intake) {
                CampusProgramIntake::create(['campus_program_id' => $campusProgram->id, 'intake_id' => $intake]);
            }

            // Storing Fees
            foreach ($request->fees as $fee) {
                // if (empty($fee['price']) || empty($fee['currency'])) {
                //     continue;
                // }
                CampusProgramFee::create([
                    'campus_program_id' => $campusProgram->id,
                    'fee_type_id' => $fee['id'],
                    'fee_price' => $fee['price'] ?? 0,
                    'fee_currency' => $fee['currency'] ?? 1
                ]);
            }

            // Storing Test Scores
            foreach ($request->test as $test) {

                if (empty($test['score']))
                    continue;

                CampusProgramTest::create([
                    'campus_program_id' => $campusProgram->id,
                    'test_id' => $test['type'],
                    'score' => $test['score'],
                    'show_in_front' => $test['show'] ?? 0
                ]);
            }
            ;

            return redirect()->route('admin.campus-programs')->with('success', 'Campus Program Added Successfully');
        } else {
            return redirect()->route('admin.campus-programs')->with('error', 'Something Went Wrong');
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
    public function edit(Request $request, $id)
    {
        $campus_program = CampusProgram::findOrFail($id);
        $campus_program->university_id = $campus_program->campus->university->id;
        config([
            'campuses' => Campus::where('university_id', $campus_program->university_id)->get()
        ]);
        $this->templateData['campusProgram'] = $campus_program;

        $breadcrumbs = [
            ['link' => "admin.home", 'name' => "Dashboard"], ['link' => 'admin.campus-programs', 'name' => 'Campus Program'], ['name' => 'Create Campus Program']
        ];
        $this->templateData['breadcrumbs'] = $breadcrumbs;
        $this->templateData['intakeIds'] = CampusProgramIntake::where('campus_program_id', '=', $id)->pluck('intake_id')->toArray();

        $this->templateData['campus'] = Campus::select('name', 'id')->find($this->templateData['campusProgram']->campus_id);
        $this->templateData['programs'] = Program::all();
        $programFees = Feetype::all();
        $tests = Test::all('id', 'test_name');

        $campusProgramFee = [];
        foreach ($programFees as $programFee) {
            $value = CampusProgramFee::where('campus_program_id', '=', $id)->where('fee_type_id', $programFee->id)->first();
            if ($value) {
                $campusProgramFee[$programFee->id] = $value->toArray();
            } else {
                $campusProgramFee[$programFee->id] = [
                    'fee_type_id' => '',
                    'fee_price' => '',
                    'fee_currency' => ''
                ];
            }
        }
        foreach ($tests as $test) {
            $score = CampusProgramTest::where('campus_program_id', '=', $id)->where('test_id', $test->id)->first();
            if ($score)
                $campusProgramTest[$test->id] = $score->toArray();
            else
                $campusProgramTest[$test->id] = ['type' => '', 'score' => '', 'show_in_front' => ''];
        }

        $this->templateData['campusProgramFees'] = $campusProgramFee;
        $this->templateData['campusProgramTest'] = $campusProgramTest;

        return view('dashboard.campus_program.edit')->with($this->templateData);
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
        $campusProgram = CampusProgram::findOrFail($id);
        if (request()->get('university')) {
            config([
                'campuses' => Campus::where('university_id', request()->university)->get()
            ]);
        } else {
            config([
                'campuses' => []
            ]);
        }

        session()->put('error', 'Error! Please check below.');
        $campus = $request->campus;
        $request->validate([
            'campus' => 'required',
            'program' => ['required', Rule::unique('campus_programs', 'program_id')->where(function ($query) use ($campus) {
                return $query->where('campus_id', '=', $campus);
            })->ignore($id)],
            'intakes' => 'required|array|min:1',
            'campus_program_duration' => 'required|numeric',
        ], ['program.unique' => "Program already exists in the Campus"]);
        session()->forget('error');

        $campusProgram->campus_id = $request->campus;
        $campusProgram->program_id = $request->program;
        $campusProgram->entry_requirment = $request->entry_requirement;
        $campusProgram->campus_program_duration = $request->campus_program_duration;

        if ($campusProgram->save()) {
            CampusProgramIntake::where('campus_program_id', "=", $campusProgram->id)->delete();
            foreach ($request->intakes as $intake) {
                CampusProgramIntake::create(['campus_program_id' => $campusProgram->id, 'intake_id' => $intake]);
            }

            CampusProgramFee::where('campus_program_id', $campusProgram->id)->delete();
            foreach ($request->fees as $fee) {
                // if (empty($fee['price']) || empty($fee['currency'])) {
                //     continue;
                // }
                CampusProgramFee::create([
                    'campus_program_id' => $campusProgram->id,
                    'fee_type_id' => $fee['id'],
                    'fee_price' => $fee['price'] ?? 0,
                    'fee_currency' => $fee['currency'] ?? 1
                ]);
            }

            CampusProgramTest::where('campus_program_id', $campusProgram->id)->delete();

            foreach ($request->test as $test) {

                if (empty($test['score']))
                    continue;

                CampusProgramTest::create([
                    'campus_program_id' => $campusProgram->id,
                    'test_id' => $test['type'],
                    'score' => $test['score'],
                    'show_in_front' => $test['show'] ?? 0
                ]);
            }
            ;

            return back()->with('success', 'Campus Program updated Successfully');
        } else {
            return back()->with('error', 'Something Went Wrong');
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
        $campusProgram = CampusProgram::findOrFail($id);
        $intakes = $campusProgram->intakes;
        $fees = $campusProgram->fees;
        $tests = $campusProgram->tests;


        if ($campusProgram->delete()) {
            if ($fees->count() > 0) {
                $fee = CampusProgramFee::where('campus_program_id', '=', $id)->delete();
            }

            if ($intakes->count() > 0) {
                $intake = CampusProgramIntake::where('campus_program_id', '=', $id)->delete();
            }
            if ($tests->count() > 0) {
                $test = CampusProgramTest::where('campus_program_id', '=', $id)->delete();
            }
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Success!',
                'message' => 'Campus Program deleted successfully.'
            ]);

        } else {
            return response()->json([
                'success' => false,
                'code' => 'danger',
                'title' => 'Error!',
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function validations()
    {
        // return [
        //     'campus' => 'required',
        //     'program' => 'required',
        //     'intakes' => 'required|array|min:1',
        // ];
    }

    public function getCampus($id)
    {
        return Campus::select('name', 'id')->where('university_id', '=', $id)->get();
    }
}
