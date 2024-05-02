<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\ProgramLevel;
use App\Models\Study;
use App\Models\Program;
use App\Models\Intake;
use App\Models\City;
use App\Models\CampusProgram;
use App\Models\CampusProgramIntake;
use App\Models\CampusProgramFee;
use App\Models\Feetype;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\CampusProgramTest;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use App\Models\UserShortlistProgram;
use Config;
use App\Models\Test;
use App\Models\University;
use Lcobucci\JWT\Signer\Ecdsa;

class CourseFinderController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            Config::set('custom.custom.mainLayoutType', 'horizontal');
            Config::set('custom.custom.pageHeader', false);
            return redirect(route('course-finder-guest'));
        }

        $programLevels = ProgramLevel::select('name', 'id')->orderBy('name')->get();
        $countries = DB::table('countries')->select('id', 'name')->orderBy('name', 'asc')->get();
        $studyAreas = Study::select('id', 'name')->where('parent_id', 0)->orderBy('name')->get();
        $intakes = Intake::select('id', 'name', 'group_name', 'type')->orderBy('sequence')->get();
        $programs = Program::select('id', 'name')->orderBy('name')->get();
        $selectedProgram = $request->program;
        $selectedIntake = $request->intake;
        $selectedYear = $request->year;
        $breadcrumbs = [
            ['link' => "my-account", 'name' => "Dashboard"],
            ['name' => 'Course Finder']
        ];

        $min = CampusProgramFee::select('fee_price')->orderBy('fee_price', 'asc')->first();
        $max = CampusProgramFee::select(DB::raw('CAST(fee_price AS UNSIGNED) as max_fee'))->orderBy('max_fee', 'DESC')->first();
        $feeTypes = Feetype::select('id', 'name')->orderBy('name', 'asc')->get();
        foreach ($feeTypes as $feeType) {
            $slugs[] = Str::slug($feeType->name, '_');
        }
        $special_tests = Test::orderBy('test_name', 'asc')->where('parent_id', 0)->get();

        config([
            'universities' => CampusProgram::join('campus', 'campus.id', '=', 'campus_programs.campus_id')
                ->join('universities', 'universities.id', '=', 'campus.university_id')
                ->select('universities.*')->groupBy('universities.name')
                ->orderBy('universities.id', 'asc')
                ->get(),
            'program_levels' => ProgramLevel::all()
        ]);

        return view('course_finder.index', compact(
            'programLevels',
            'countries',
            'studyAreas',
            'intakes',
            'selectedProgram',
            'selectedIntake',
            'selectedYear',
            'programs',
            'breadcrumbs',
            'max',
            'min',
            'feeTypes',
            'slugs',
            'special_tests'
        )
        );
    }

    function guestUserResult(Request $request)
    {

        $countries = DB::table('countries')->select('id', 'name')->get();
        $programs = Program::select('id', 'name')->get();
        return view('course_finder.guest_search', compact('countries', 'programs'));
    }

    function guestUserResultNew(Request $request)
    {
        $countries = DB::table('countries')->select('id', 'name')->get();
        $programs = Program::select('id', 'name')->get();
        return view('course_finder.guest_search_new', compact('countries', 'programs'));
    }

    public function getPrograms(Request $request)
    {


        return Program::whereIn('study_area_id', $request->id)->select('name', 'id')->orderBy('name')->get();
    }

    public function searchStore(Request $request)
    {

        $programLevels = ProgramLevel::select('name', 'id')->get();
        $countries = DB::table('countries')->select('id', 'name')->get();
        $studyAreas = Study::select('id', 'name')->get();
        $intakes = Intake::select('id', 'name')->orderBy('sequence')->get();
        $breadcrumbs = [
            ['link' => "my-account", 'name' => "Dashboard"]
        ];
        return view('course_finder.index', compact('programLevels', 'countries', 'studyAreas', 'intakes'));
    }

    public function finder()
    {

        // $duration = [];
        // foreach($r['data'] as $key => $value) {
        //     $duration[] = ($value['program']['duration']);
        // }
        // dd($duration);
        // $results = CampusProgram::with([
        //     'intakes.intake',
        //     'fees' => function($q) {
        //         $q->selectRaw('*, CAST( fee_price as UNSIGNED ) as price ');
        //     },
        //     'fees.fee',
        //     'fees.currency',
        //     'tests.test',
        //     'campus.university',
        //     'campus.address.country',
        //     'campus.address.state', 
        //     'campus.address.city', 
        //     'program',
        //     'program.studyArea',
        //     'program.programLevel',
        //     'program.disciplineAreas',
        //     'program.disciplineAreas.studyArea',
        // ]);
        $feeTypes = Feetype::all();
        config(['fee_types' => $feeTypes]);

        if (request()->ajax()) {
            $query = "";

            foreach ($feeTypes as $feeType) {
                $slug = Str::slug($feeType->name, '_');
                // $query .= "CAST(GROUP_CONCAT(if(fee_types.name='" . $feeType->name . "', IFNULL(campus_program_fees.fee_price,0),0)) AS UNSIGNED) as '" . $slug . "', ";
                $query .= "CAST(GROUP_CONCAT(if(fee_types.name='" . $feeType->name . "', campus_program_fees.fee_price, NULL)) AS UNSIGNED) as '" . $slug . "', ";
            }

            $results = CampusProgram::join('campus', 'campus_programs.campus_id', '=', 'campus.id')
                ->join('universities', 'campus.university_id', '=', 'universities.id')
                ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
                ->leftJoin('addresses', 'campus.address_id', "=", 'addresses.id')
                ->leftJoin('countries', 'addresses.country_id', '=', 'countries.id')
                ->leftJoin('cities', 'addresses.city_id', '=', 'cities.id')
                ->leftJoin('states', 'addresses.state_id', '=', 'states.id')
                ->leftJoin('study_areas', 'programs.study_area_id', '=', 'study_areas.id')
                ->leftJoin('program_study_areas', 'program_study_areas.program_id', '=', 'programs.id')
                ->leftJoin('campus_program_fees', 'campus_programs.id', '=', 'campus_program_fees.campus_program_id')
                ->leftJoin('campus_program_intakes', 'campus_programs.id', '=', 'campus_program_intakes.campus_program_id')
                ->leftJoin('campus_program_test', 'campus_programs.id', '=', 'campus_program_test.campus_program_id')
                ->leftJoin('intakes', 'campus_program_intakes.intake_id', '=', 'intakes.id')
                ->leftJoin('fee_types', 'campus_program_fees.fee_type_id', '=', 'fee_types.id')
                ->select('campus_programs.id', 'programs.duration', 'countries.name', 'fee_types.name as fee_name', DB::raw(substr($query, 0, -2)));


            $d = $results->groupBy('campus_programs.id')->toSql();
            // echo $d;
            // die;
            return Datatables::of($results)
                ->addColumn('duration', function ($row) {
                    return $row->duration ?? "N/A";
                })
                ->addColumn('country', function ($row) {
                    return $row->name ?? "N/A";
                })
                // ->addColumn('application_fee', function($row) {
                //     $fee_str = 'N/A';
                //     foreach($row->fees ?? [] as $fee) {
                //         if($fee->fee_type_id == 2) {
                //             return $fee->price;
                //         }
                //     }
                //     return $fee_str;
                // })
                // ->orderByNullsLast()
                ->make(true);
        } else {
            return view('finder');
        }
    }

    function getCampusProgram(Request $request)
    {

        if (!auth()->check()) {
            // return [];
        }


        $feeTypes = Feetype::all();
        $query = "";



        foreach ($feeTypes as $feeType) {
            $slug = Str::slug($feeType->name, '_');

            $slugs[] = Str::slug($feeType->name, '_');
            // $query .= "CAST(GROUP_CONCAT(if(fee_types.name='" . $feeType->name . "', IFNULL(campus_program_fees.fee_price,0),0)) AS UNSIGNED) as '" . $slug . "', ";
            $query .= "CAST(GROUP_CONCAT(if(fee_types.name='" . $feeType->name . "', campus_program_fees.fee_price, NULL)) AS UNSIGNED) as '" . $slug . "', ";
        }
        // $data = CampusProgram::join('campus', 'campus_programs.campus_id', '=', 'campus.id')->get();
        // ->join('universities', 'campus.university_id', '=', 'universities.id')->get();

        // ;

        // auth()->user()->id ?? 0


        $data = CampusProgram::join('campus', 'campus_programs.campus_id', '=', 'campus.id')
            ->join('universities', 'campus.university_id', '=', 'universities.id')

            ->leftJoin('users_shortlist_programs', function ($join) {
                $join->on('users_shortlist_programs.campus_program_id', '=', 'campus_programs.id');
                $join->where('users_shortlist_programs.user_id', auth()->user()->id ?? 0);
            })

            ->leftJoin('addresses', 'campus.address_id', "=", 'addresses.id')
            ->leftJoin('countries', 'addresses.country_id', '=', 'countries.id')
            ->leftJoin('states', 'addresses.state_id', '=', 'states.id')
            ->leftJoin('cities', 'addresses.city_id', '=', 'cities.id')

            ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
            ->leftJoin('study_areas', 'programs.study_area_id', '=', 'study_areas.id')
            ->leftJoin('program_study_areas', 'program_study_areas.program_id', '=', 'programs.id')

            ->leftJoin('campus_program_fees', 'campus_programs.id', '=', 'campus_program_fees.campus_program_id')
            ->leftJoin('campus_program_intakes', 'campus_programs.id', '=', 'campus_program_intakes.campus_program_id')
            ->leftJoin('campus_program_test', 'campus_programs.id', '=', 'campus_program_test.campus_program_id')

            ->join('intakes', 'campus_program_intakes.intake_id', '=', 'intakes.id')
            ->leftJoin('fee_types', 'campus_program_fees.fee_type_id', '=', 'fee_types.id');




        // What you want to Study?
        if (isset($request->what)) {
            $data->where(function ($query) {
                $query->where('programs.name', 'like', '%' . request()->get('what') . '%');
                $query->orWhere('study_areas.name', 'like', '%' . request()->get('what') . '%');

                $programExist = Program::where('programs.name', request()->get('what'))->exists();
                $studyExist = study::where('study_areas.name', request()->get('what'))->exists();

                // Perform the search query
                if ($programExist) {
                    Program::where('programs.name', request()->get('what'))->increment('search_count');
                } elseif (!$programExist && !$studyExist) {
                    $programSearchCount = Program::where('programs.name', 'like', '%' . request()->get('what') . '%')->increment('search_count');
                }

                if ($studyExist) {
                    study::where('study_areas.name', request()->get('what'))->increment('search_count');
                } elseif (!$programExist && !$studyExist) {
                    $studyareaSearchCount = study::where('study_areas.name', 'like', '%' . request()->get('what') . '%')->increment('search_count');
                }




            });



        }


        // where do you want to study?
        if (isset($request->where)) {
            $data->where(function ($query) {


                $countryExist = Country::where('countries.name', request()->get('where'))->exists();
                $campusExist = Campus::where('campus.name', request()->get('where'))->exists();
                $universityExist = University::where('universities.name', request()->get('where'))->exists();


                $query->where('countries.name', 'like', '%' . request()->get('where') . '%');

                if ($countryExist) {
                    $countrySearchCount = Country::where('countries.name', request()->get('where'))->increment('search_count');
                } elseif (!$campusExist && !$universityExist && !$countryExist) {
                    $countrySearchCount = Country::where('countries.name', 'like', '%' . request()->get('where') . '%')->increment('search_count');
                }





                $query->orWhere('states.name', 'like', '%' . request()->get('where') . '%');





                $query->orWhere('cities.name', 'like', '%' . request()->get('where') . '%');




                $query->orWhere('campus.name', 'like', '%' . request()->get('where') . '%');


                if ($campusExist) {
                    Campus::where('campus.name', request()->get('where'))->increment('search_count');
                } elseif (!$campusExist && !$universityExist && !$countryExist) {
                    $campusSearchCount = Campus::where('campus.name', 'like', '%' . request()->get('where') . '%')->increment('search_count');

                }




                $query->orWhere('universities.name', 'like', '%' . request()->get('where') . '%');

                if ($universityExist) {
                    University::where('universities.name', request()->get('where'))->increment('search_count');
                } elseif (!$campusExist && !$universityExist && !$countryExist) {
                    $universitySearchCount = University::where('universities.name', 'like', '%' . request()->get('where') . '%')->increment('search_count');
                }




                $query->orWhere('addresses.address', 'like', '%' . request()->get('where') . '%');



            });
            // $data->where('countries.name', 'like', '%' . $request->where . '%')
            //     ->orWhere('states.name', 'like', '%' . $request->where . '%')
            //     ->orWhere('cities.name', 'like', '%' . $request->where . '%')
            //     ->orWhere('campus.name', 'like', '%' . $request->where . '%')
            //     ->orWhere('universities.name', 'like', '%' . $request->where . '%')
            //     ->orWhere('addresses.address', 'like', '%' . $request->where . '%');
        }

        if (!auth()->check()) {

        }
        // Program Levels
        if (isset($request->program_levels) && !empty($request->program_levels)) {
            $data->whereIn('programs.program_level_id', $request->program_levels);
        }

        // Intakes
        if (isset($request->intakes) && !empty($request->intakes)) {
            $data->whereIn('campus_program_intakes.intake_id', $request->intakes);
        }

        // Duration
        if (isset($request->duration)) {
            $getMonths = function ($v) {
                return ($v * 12);
            };
            $first = 0;
            $data->where(function ($query) use ($request, $first, $getMonths) {
                foreach ($request->duration as $year) {
                    if ($first == 0) {
                        $query->whereBetween('campus_programs.campus_program_duration', array_map($getMonths, json_decode($year)));
                    } else {
                        $query->orWhereBetween('campus_programs.campus_program_duration', array_map($getMonths, json_decode($year)));
                    }
                    $first++;
                }
            });
            // foreach ($request->duration as $year) {

            //     if($first == 0) {
            //         $data->whereBetween('programs.duration', array_map($getMonths, json_decode($year)));
            //     } else {
            //         $data->orWhereBetween('programs.duration', array_map($getMonths, json_decode($year)));
            //     }
            //     $first++;
            // }
        }

        // Special Tests
        if (isset($request->special_tests) && !empty($request->special_tests)) {
            $qry = "";
            // $data->whereIn('campus_program_test.test_id', $request->special_tests)
            //     ->where('campus_program_test.show_in_front', '=', 1);

            $data->where(function ($query) {
                $query->whereIn('campus_program_test.test_id', request()->get('special_tests'));
                $query->where('campus_program_test.show_in_front', 1);
            });
            // foreach ($request->special_tests as $test) {
            //     $qry .= "campus_program_test.test_id = $test || ";
            // }
            // $qry = substr($qry, 0, -3);
            // $data->whereRaw($qry . "&& campus_program_test.show_in_front = 1");
        }

        // Country
        if (isset($request->country_id) && !empty($request->country_id)) {
            $data->whereIn('addresses.country_id', $request->country_id);
            $studyareaSearchCount = Country::whereIn('id', $request->country_id)->increment('search_count');

        }

        // Study Area
        if (isset($request->study_area)) {
            $data->whereIn('programs.study_area_id', $request->study_area);
            $studyareaSearchCount = Study::whereIn('id', $request->study_area)->increment('search_count');
        }

        // Discipline 
        if (isset($request->discipline)) {

            $data->whereIn('program_study_areas.study_area_id', $request->discipline);


            $studyareaSearchCount = Study::whereIn('id', $request->discipline)->increment('search_count');




        }

        // Universities
        if (isset($request->univs) && !empty($request->univs)) {
            $universitySearchCount = University::whereIn('id', $request->univs)->increment('search_count');
            $data->whereIn('universities.id', $request->univs);
        }

        // Program Fees
        if (isset($request->fee)) {
            $str = "";
            foreach ($feeTypes as $feeType) {
                if ($feeType->name == "Admission Fees")
                    continue;
                $fee_slug = Str::slug($feeType->name, '_');

                if ($request->fee[$fee_slug][0] == 0) {
                    $include_null = "";
                    // $include_null = " OR {$fee_slug} IS NULL";   
                } else {
                    $include_null = "";
                }
                $str .= "`" . $fee_slug . "` >= {$request->fee[$fee_slug][0]} &&`" . $fee_slug . "` <= {$request->fee[$fee_slug][1]} {$include_null}  and ";
            }

            $data->havingRaw(substr($str, 0, -4));
        }

        $data->select('campus_programs.id as campusprogram_id', 'campus_programs.campus_program_duration as duration', 'campus.name as campus', 'universities.id as univ_id', 'universities.name as universtiy', 'programs.name as program', 'campus.id as campus_id', 'intakes.name as intake', 'users_shortlist_programs.id as shortlist_id', DB::raw(substr($query, 0, -2)), 'study_areas.*', 'countries.name as country', DB::raw('GROUP_CONCAT(intakes.name) as intake_names'))->groupBy('campusprogram_id');


        // Cloning results for getting universities
        $start = $request['start'];
        $length = $request['length'];
        $totalCount = count($data->get());
        $result = $data->skip($start)->limit($length);

        $dataTable = Datatables::of($result)
            ->addColumn('row', function ($row) {
                return view('course_finder.result', compact('row'))->render();
            })
            ->editColumn('logo', function ($row) {
                return '';
            })
            ->setTotalRecords($totalCount);



        foreach ($slugs as $fee) {
            $name = $fee;
            $dataTable->editColumn($name, function ($row) use ($name) {

                if ($row->$name == null) {
                    return 0;
                } else {
                    return $row->$name;
                }
            });
        }

        return $dataTable->rawColumns(['row', 'logo'])
            ->make(true);
    }


    function autocompleteCourse(Request $request)
    {
        $data1 = Program::where('name', 'LIKE', "%{$request['query']}%")->select('name')->groupBy('name')->get()->toArray();
        $data2 = DB::table('study_areas')->where('name', 'LIKE', "%{$request['query']}%")->select('name')->groupBy('name')->get()->toArray();
        $data = array_merge($data1, $data2);
        return response()->json($data);
    }

    function autocompleteCountries(Request $request)
    {
        $data1 = Country::where('name', 'LIKE', "%{$request['query']}%")->select('name')->groupBy('name')->get()->toArray();
        $data2 = Campus::where('name', 'LIKE', "%{$request['query']}%")->select('name')->groupBy('name')->get()->toArray();
        $data3 = array_merge($data1, $data2);
        $data4 = University::where('name', 'LIKE', "%{$request['query']}%")->select('name')->groupBy('name')->get()->toArray();
        $data5 = array_merge($data3, $data4);
        return response()->json($data5);
    }

    public function programDetails($id)
    {
        if (!auth()->check()) {
            Config::set('custom.custom.mainLayoutType', 'horizontal');
            Config::set('custom.custom.pageHeader', false);
        }
        $campusProgram = CampusProgram::find($id);

        $campusIntakesIds = CampusProgramIntake::select('intake_id')->where('campus_program_id', '=', $id)->get();

        $campus = Campus::select('campus.name as campus', 'universities.name as university', 'logo', 'cover', 'campus.id as campus_id', 'addresses.country_id')
            ->join('universities', 'campus.university_id', '=', 'universities.id')
            ->leftjoin('addresses', 'campus.address_id', '=', 'addresses.id')
            ->where('campus.id', $campusProgram->campus_id)->get();
        $program = Program::select('programs.name as program', 'program_levels.name as program_level', 'study_areas.name as study', 'programs.duration as duration')
            ->leftJoin('program_levels', 'programs.program_level_id', '=', 'program_levels.id')
            ->leftJoin('study_areas', 'programs.study_area_id', '=', 'study_areas.id')
            ->where('programs.id', $campusProgram->program_id)->get();

        $countryId = isset($campus[0]) ? $campus[0]->country_id : "";
        $country = Country::find($countryId);
        $countryName = (!empty($country)) ? $country->name : "N/A";
        foreach ($campusIntakesIds as $intakeId) {
            $intakeIds[] = $intakeId->intake_id;
        }

        $campusIntakesFees = CampusProgramFee::where('campus_program_id', '=', $id)
            ->join('fee_types', 'campus_program_fees.fee_type_id', '=', 'fee_types.id')
            ->join('currencies', 'campus_program_fees.fee_currency', '=', 'currencies.id')
            ->select('fee_types.name as name', 'fee_price', 'currencies.symbol as currency', 'currencies.code as code')->get();


        $intakes = Intake::whereIn('id', $intakeIds)->select('name', 'id')->orderBy('sequence')->get();
        $breadcrumbs = [
            ['link' => "course-finder", 'name' => "Search Programs"],
            ['name' => $program[0]->program]
        ];


        $testScores = CampusProgramTest::where('campus_program_id', '=', $id)->where('show_in_front', '=', 1)
            ->join('tests', 'campus_program_test.test_id', '=', 'tests.id')
            ->select('tests.test_name as test', 'campus_program_test.score as score')
            ->get();

        return view('course_finder.full_details', compact('campus', 'campusProgram', 'program', 'intakes', 'testScores', 'campusIntakesFees', 'breadcrumbs', 'id', 'countryName'));
    }

    function test()
    {
        $feeTypes = Feetype::all();
        $query = "";
        //CAST(fee_price AS UNSIGNED)
        foreach ($feeTypes as $feeType) {

            $query .= "CAST(GROUP_CONCAT(if(fee_types.name='" . $feeType->name . "', campus_program_fees.fee_price,NULL)) AS UNSIGNED) as " . substr($feeType->name, 0, -3) . ", ";
        }

        $campus = CampusProgram::leftJoin('campus_program_fees', 'campus_programs.id', '=', 'campus_program_fees.campus_program_id')
            ->leftJoin('fee_types', 'campus_program_fees.fee_type_id', '=', 'fee_types.id')

            ->select('campus_programs.id as id', DB::raw(substr($query, 0, -2)))->groupBy('campus_programs.id')
            ->orderBy('Admission', 'Asc')->get();
        return $campus;
    }
    public function checkProgram($id)
    {
        $shortList = UserShortlistProgram::where([['user_id', Auth::id()], ['campus_program_id', $id]]);
        $count = $shortList->count();
        if ($count > 0) {
            $shortList->select('id')->first();
            return ['count' => $count, 'id' => $shortList->select('id')->first()->id];
        } else {
            return ['count' => $count, 'id' => ''];
        }
    }

    public function campusePage($id)
    {
        $campus = Campus::find($id);

        $programs = CampusProgram::where('campus_id', '=', $id)
            ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
            ->leftJoin('study_areas', 'programs.study_area_id', '=', 'study_areas.id')
            ->leftJoin('program_levels', 'programs.program_level_id', '=', 'program_levels.id')
            ->select('campus_programs.id as campus_program', 'programs.name as program_name', 'campus_programs.campus_program_duration as duration', 'programs.course_link as program_link', 'study_areas.name as study_area', 'program_levels.name as program_level')
            ->get();
        if (request()->ajax()) {
            return Datatables::of($programs)
                ->editColumn('program_link', function ($row) {
                    return "<a href='" . $row->program_link . "'>Website</a>";
                })
                ->editColumn('duration', function ($row) {
                    return $row->duration . " Months";
                })
                ->addColumn('action', function ($row) {
                    return view('course_finder.campus_program', compact('row'))->render();
                })
                ->rawColumns(['program_link', 'action'])
                ->make(true);
        } else {
            //return $campus->getAddress;
            if (auth()->check()) {
                $breadcrumbs = [
                    ['link' => "my-account", 'name' => "Dashboard"],
                    ['link' => "course-finder", 'name' => 'Course Finder']
                ];
                return view('course_finder.campus_details', compact('campus', 'id', 'breadcrumbs'));
            } else {

                return view('course_finder.campus_details', compact('campus', 'id'));
            }
        }
    }

    function countryAutoComplete()
    {
        $q = request()->get('qry');

        $Countryresult = DB::table('countries')->select('id as value', 'name as text')->where('name', 'LIKE', "%{$q}%")->get();

        return $Countryresult;
    }

    function programAutoComplete()
    {
        $q = request()->get('qry');
        return Program::where('name', 'LIKE', "%{$q}%")->select('id as value', 'name as text')->get();
    }
}
