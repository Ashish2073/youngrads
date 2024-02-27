<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request, Response;  
use \Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Campus;
use App\Models\University;
use Illuminate\Support\Facades\DB;
use App\Imports\ForthSheetImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\City;
use App\Models\State;
use App\Models\Country;


class CampusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('selectCampus');
        $this->middleware('userspermission:campus_view',['only'=>['index']]);

        $this->middleware('userspermission:campus_add',['only'=>['create','store']]);
        $this->middleware('userspermission:campus_edit',['only'=>['edit','update']]);
        $this->middleware('userspermission:campus_delete',['only'=>['destroy']]); 
        $this->middleware('userspermission:campus_details_view',['only'=>['addDetails']]); 
        $this->middleware('userspermission:campus_details_add',['only'=>['saveDetails']]); 



    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if(($request->get('universityid')!=null)|| ($request->get('campusid')!=null) || ($request->get('websitename')!=null)){
           
        
           
            $Campus = Campus::join('universities', 'campus.university_id', '=', 'universities.id')
            ->select('campus.id', 'campus.name as campus', 'universities.name as university', 'campus.logo', 'campus.cover', 'campus.website')
            ->whereIn('campus.id', $request->get('campusid') ? $request->get('campusid') : [DB::raw('campus.id')])
            ->whereIn('universities.id', $request->get('universityid') ? $request->get('universityid') : [DB::raw('universities.id')])
             ->get();

            // if(count(json_decode($Campus,true))==0){
            //     $Campus = Campus::join('universities', 'campus.university_id', '=', 'universities.id')
            //     ->select('campus.id', 'campus.name as campus', 'universities.name as university', 'campus.logo', 'campus.cover', 'campus.website')
            //     ->whereIn('campus.id', $request->get('campusid') ? $request->get('campusid') : [DB::raw('campus.id')])
            //     ->whereIn('universities.id', $request->get('universityid') ? $request->get('universityid') : [DB::raw('universities.id')]) 
            //     ->get();

            // }

           

          
        }else{ 
        $Campus = Campus::join('universities', 'campus.university_id', '=', 'universities.id')
            ->select('campus.id', 'campus.name as campus', 'universities.name as university', 'campus.logo', 'campus.cover', 'campus.website')
            ->get();
        }

        if (request()->ajax()) {


            if((session('permissionerror'))){
               
           
                return response()->json(['errorpermissionmessage'=>session('permissionerror')]);
              


            }







            return Datatables::of($Campus)
                ->editColumn('logo', function ($row) {
                    session()->forget('used_university');
                    if ($row->logo == '') {
                        return "N/A";
                    } else {
                        return "<img src='" . asset("uploads/program_logo/" . $row->logo) . "' width='150' height=150 />";
                    }
                })
                ->editColumn('cover', function ($row) {
                    if ($row->cover == '') {
                        return "N/A";
                    } else {

                        return "<img src='" . asset("uploads/program_cover/" . $row->cover) . "' width='150' height=150 />";
                    }
                })
                ->editColumn('website', function ($row) {
                    if ($row->website == '') {
                        return "N/A";
                    } else {

                        return "<a href='" . $row->website . "' class='a-link' target='blank'> $row->website</a>";
                    }
                }) 

                ->addColumn('action', function ($row) { 



                    return "<a class='btn btn-primary btn-sm a-link' href=" . route('admin.campus-details', $row->id) . ">Add Details</a>";
                })
                ->rawColumns(['logo', 'cover', 'website', 'action'])
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Campuses"]
            ];
            $university=University::select('id','name')->get(); 
            $campus=Campus::select('id','name','website')->get();
            return view('dashboard.campus.index', [
                'breadcrumbs' => $breadcrumbs,'university'=>$university,'campus'=>$campus,
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
        config([
            'countries' => Country::all()
        ]);
        config([
            'states' => [],
            'cities' => [],
        ]);
        config([
            'universities' => University::all(),
        ]);
        return view('dashboard.campus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) {
                    $records = Campus::where('name', $value)
                        ->where('university_id', request()->get('university'))->get();
                    if ($records->count() > 0) {
                        $fail("This {$attribute} has already been taken.");
                    }
                }
            ],
            'university' => 'required',
            'website' => 'required|url',
            'country' => 'required',
            'state' => 'required',
            'address' => 'required|max:255'
        ]);

        config([
            'countries' => Country::all()
        ]);

        if (!empty($request->country)) {
            config(['states' => State::where('country_id', $request->country_id)->get()]);
        } else {
            config(['states' => []]);
        }

        if (!empty($request->state)) {
            config(['cities' => City::where('state_id', $request->state_id)->get()]);
        } else {
            config(['cities' => []]);
        }

        config([
            'universities' => University::all(),
        ]);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.campus.create')->withErrors($validator);
        }

        // Upload assets
        $logo = '';
        $cover = '';
        if (isset($request->logo)) {
            $logo = "logo" . "_" . time() . "_" . Auth::id() . "." . $request->file('logo')->getClientOriginalExtension();
            $request->logo->move(public_path('uploads/program_logo'), $logo);
        }
        if (isset($request->cover)) {
            $cover = 'cover' . "_" . time() . "_" . Auth::id() . "." . $request->file('cover')->getClientOriginalExtension();
            $request->cover->move(public_path('uploads/program_cover'), $cover);
        }

        $campus = new Campus;
        $campus->name = $request->name;
        $campus->university_id = $request->university;
        $campus->logo = $logo;
        $campus->cover = $cover;
        $campus->website = $request->website;

        if ($campus->save()) {
            $address = new Address;
            $address->address = $request->address;
            $address->country_id = $request->country;
            $address->state_id = $request->state;

            if ($request->city == "new-city") {
                if (City::where('name', $request->city_name)->get()->count() > 0) {
                    $address->city_id = City::where('name', $request->new_city)->first()->id;
                } else {
                    $newcity = new City;
                    $newcity->name = $request->new_city;
                    $newcity->state_id = $request->state;
                    $newcity->save();
                    $address->city_id = $newcity->id;
                }
            } else {
                $address->city_id = $request->city;
            }
            if ($address->save()) {
                $campus->address_id = $address->id;
                $campus->save();
            }

            $address->save();

            $campus->address_id = $address->id;
            $campus->save();

            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Campus added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.campus.create', compact('universities'));
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campus = Campus::findOrFail($id);



        config(['countries' => Country::all()]);

        if (is_null($campus->address_id)) {
            config([
                'states' => [],
                'cities' => [],
            ]);
        } else {
            config([
                'states' => State::where('country_id', $campus->address->country_id)->get(),
                'cities' => City::where('state_id', $campus->address->state_id)->get(),
            ]);
        }

        config([
            'universities' => University::all(),
        ]);

        return view('dashboard.campus.edit', compact('campus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campus = Campus::findOrFail($id);

        config([
            'countries' => Country::all()
        ]);

        if (!empty($request->country)) {
            config(['states' => State::where('country_id', $request->country)->get()]);
        } else {
            config(['states' => []]);
        }

        if (!empty($request->state)) {
            config(['cities' => City::where('state_id', $request->state)->get()]);
        } else {
            config(['cities' => []]);
        }

        config([
            'universities' => University::all(),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $records = Campus::where('name', $value)
                        ->where('university_id', request()->get('university'))
                        ->where('id', '<>', $id)
                        ->get();
                    if ($records->count() > 0) {
                        $fail("This {$attribute} has already been taken.");
                    }
                }
            ],
            'university' => 'required',
            'website' => 'required|url',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            return view('dashboard.campus.edit', compact('campus'))->withErrors($validator);
        }


        $campus->name = $request->name;
        $campus->university_id = $request->university;
        $campus->website = $request->website;

        if (isset($request->logo)) {
            $logo = "logo" . "_" . time() . "_" . Auth::id() . "." . $request->file('logo')->getClientOriginalExtension();
            $request->logo->move(public_path('uploads/program_logo'), $logo);
            if ($campus->logo != '')
                unlink(public_path('uploads/program_logo/' . $campus->logo));
            $campus->logo = $logo;
        }
        if (isset($request->cover)) {
            $cover = 'cover' . "_" . time() . "_" . Auth::id() . "." . $request->file('cover')->getClientOriginalExtension();
            $request->cover->move(public_path('uploads/program_cover'), $cover);
            if ($campus->cover != '')
                unlink(public_path('uploads/program_cover/' . $campus->cover));
            $campus->cover = $cover;
        }

        if ($campus->save()) {

            $address = is_null($campus->address_id) || empty($campus->address_id) ? new Address : Address::find($campus->address_id);
            $address->address = $request->address;
            $address->country_id = $request->country;
            $address->state_id = $request->state;
            if ($request->city == "new-city") {
                if (City::where('name', $request->city_name)->get()->count() > 0) {
                    $address->city_id = City::where('name', $request->new_city)->first()->id;
                } else {
                    $newcity = new City;
                    $newcity->name = $request->new_city;
                    $newcity->state_id = $request->state;
                    $newcity->save();
                    $address->city_id = $newcity->id;
                }
            } else {
                $address->city_id = $request->city;
            }
            if ($address->save()) {
                $campus->address_id = $address->id;
                $campus->save();
            }

            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Campus updated successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.campus.edit', compact('universities', 'campus', 'countries', 'cities', 'states', 'address'));
        }
    }

    public function addDetails($id)
    {
        $breadcrumbs = [
            ['link' => "admin.campuses", 'name' => "Campus"], ['name' => "Add Details"]
        ];
        $campus = Campus::select('about_us', 'feature', 'name')->find($id);
        return view('dashboard.campus.campus_details', compact('campus', 'id', 'breadcrumbs'));
    }

    public function saveDetails(Request $request)
    {
        $campus = Campus::find($request->id);
        $campus->about_us = $request->aboutUs;
        $campus->feature = $request->feature;

        if ($campus->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Campus details added successfully',
                'success' => true
            ]);
        }
        //  echo $request->aboutUs;
        //  echo $request->feature;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campus = Campus::find($id);
        $campus->delete();
        if ($campus->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Deleted',
                'message' => 'Campus deleted successfully',
                'success' => true
            ]);
        }

    }

    public function excelImport()
    {
        //campus importing
        //$file = public_path('excel_data/campus.xlsx');
        //$data = Excel::import(new ThirdSheetImport,$file);

        // //study_areas importing
        // $file = public_path('excel_data/study_areas.xlsx');
        // $data = Excel::import(new SecondSheetImport,$file);

        //Universtiy importing
        // $file = public_path('excel_data/university.xlsx');
        // $data = Excel::import(new FirstSheetImport,$file);

        //program importing
        $file = public_path('excel_data/program.xlsx');
        $data = Excel::import(new ForthSheetImport, $file);
    }

    public function selectCampus(Request $request)
    {
        if (isset($request->name))
            return Campus::where('name', 'LIKE', "%{$request->name}%")->select('id', 'name as text')->get();
    }
}
