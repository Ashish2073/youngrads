<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Admin;


class ActivityController extends Controller
{
    public function __construct()
    { 
        $this->middleware('auth:admin');

        $this->middleware('userspermission:user_activity_view',['only'=>['index']]);


        config([
            'users' => User::orderBy('name', 'asc')->get()
        ]);

        




    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "admin.home", 'name' => "Dashboard"], ['name' => 'User Activities']
        ];

        $pageConfigs = [
            //'pageHeader' => false,
            //'contentLayout' => "content-left-sidebar",
        ];

      $moderator= Admin::join('users','users.moderator_id','=','admins.id')
      ->join('model_has_roles','model_has_roles.model_id','=','admins.id')
      ->join('roles','model_has_roles.role_id','=','roles.id')
      ->select('admins.id as moderatorid','admins.email as email','roles.name as role_name',DB::raw("CONCAT(admins.first_name, ' ', admins.last_name) as full_name"))
      ->where('roles.name','moderator')
      ->where('model_has_roles.model_type','App\Models\Admin')
      ->distinct()
      ->get();

    
   
    


        $records = Activity::where('id', '!=', 0)->whereNotNull('causer_id')->orderBy('id','desc')->get();

      
        if (request()->ajax()) {

         

            if (request()->has('user_id') && !empty(request()->get('user_id'))) {

                $records=Activity::where('id', '!=', 0)->whereNotNull('causer_id')->whereIn('causer_id', request()->get('user_id'))->orderBy('id','desc')->get();
               

               
            }

            
            if (request()->has('moderator_id') && !empty(request()->get('moderator_id'))) {
  

                $records=Activity::where('id', '!=', 0)->whereNotNull('causer_id')->whereIn('causer_id', request()->get('moderator_id'))->where('causer_type','=','App\Models\Admin')->orderBy('id','desc')->get();
               

               
            }

      

            return Datatables::of($records)
          
                ->editColumn('created_at', function ($row) {
                    
                    return date("d M Y h:i A", strtotime($row->created_at));
                })
                ->editColumn('description', function ($row) {
                    if (!is_null($row->subject_type) && !empty($row->subject_type)) {
                        $arr = explode("\\", $row->subject_type);
                        $on = $arr[count($arr) - 1];
                        $on = ucfirst($on);
                    } else {
                        $on = '';
                    }
                    return ucfirst($row->description) . ' ' . $on;
                })
                ->editColumn('ip_address', function ($row) {
                    return $row->ip_address ?? "N/A";
                })
                ->addColumn('user', function ($row) {
                    switch ($row->causer_type) {
                        case 'App\Models\Admin':
                            $admin = Admin::where('id', $row->causer_id)->first();
                            if ($admin != null) {
                                return $admin->first_name . " - " . $admin->email . "<span class='text-dark'>(System)</span>";
                            }
                            break;

                        case 'App\Models\User':
                            $user = User::where('id', $row->causer_id)->first();
                            if ($user != null) {
                                return $user->name . " - " . $user->email . "<span class='text-dark'>(Student)</span>";
                            }
                            break;

                        default:
                            return "N/A";
                            break;
                    }
                    return "N/A";
                })
                ->rawColumns(['user'])
                ->make(true);
        } else {
            return view('dashboard.activities.index', compact('breadcrumbs', 'pageConfigs','moderator'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
