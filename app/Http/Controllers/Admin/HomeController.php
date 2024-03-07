<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Campus;
use App\Models\CampusProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FifthSheetImport;
use App\Models\Program;
use App\Models\University;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class HomeController extends Controller
{
  public function __construct()
  {
     
      $this->middleware('auth:admin');
     }
   
  
  /**
   * Show Admin Dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $unversities = University::count();
    $campus = Campus::count();
    $campusPrograms = CampusProgram::count();
    $programs = Program::count();
    $systemUsers = Admin::count();
    $students = User::count();
    $applications = UserApplication::count();

    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $weeklyenrolledstudents= User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
    $weeklyenrolledapplication=UserApplication::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    $monthlyData = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
    $monthlyenrolledapplication=UserApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

    $currentDate = Carbon::now()->toDateString();

// Retrieve data for the current day
     
    $dailyData =  User::whereDate('created_at', $currentDate)->count();
    $dailyenrolledapplication=UserApplication::whereDate('created_at', $currentDate)->count();



   



    $userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; 
    if(in_array('Admin',$userrole)){
    $moderators=Admin::role('moderator')->count();
     }elseif(in_array('supermoderator',$userrole)){
      $moderators=Admin::role('moderator')->where('parent_id',auth('admin')->user()->id)->count();
      $moderatorid=Admin::role('moderator')->where('parent_id',auth('admin')->user()->id)->pluck('id')->toArray();
      $students = User::whereIn('moderator_id',$moderatorid)->count();


     }elseif(in_array('moderator',$userrole)){
     
      $students = User::where('moderator_id',auth('admin')->user()->id)->count();
      $moderators=0;
     }
   
    $pageConfigs = [
      'pageHeader' => false
    ];

    // return $campusProgram;
    return view('dashboard.home', compact('weeklyenrolledapplication','monthlyenrolledapplication','dailyenrolledapplication','dailyData','monthlyData','weeklyenrolledstudents','unversities','moderators','campus', 'campusPrograms', 'programs', 'pageConfigs', 'students', 'applications', 'systemUsers'));
  }

  public function excelForm()
  {
    $file = public_path('uploads/user_documents/test.csv');
    $data = $this->csvToArray($file, $delimiter = ',');
    echo "<pre>";
    print_r($data);
    echo "<pre>";
    //return view('dashboard.excel_import.index');
  }

  public function importExcel(Request $request)
  {
    //  $request->validate(['excel'=>'required'],['excel.mimes'=>"File must be xlsx or xls type"]);
    // //  | in:xlsx,xls

    // //  $fileName = time().".".Auth::id().".".$request->file('excel')->getClientOriginalExtension();
    // //  $request->excel->move(public_path('uploads\import'),$fileName);

    // Excel::import(new FifthSheetImport,$request->file('excel'));
    $file = public_path('uploads/user_documents/test.csv');
    $this->csvToArray($file, $delimiter = ',');
    // print_r($this->csvToArray($file,$delimiter = ','));

  }

  function csvToArray($file, $delimiter = ',')
  {

    if (!file_exists($file) || !is_readable($file)):
      return false;
    endif;

    if (($handle = fopen($file, 'r')) !== false):
      while (($row = fgetcsv($handle, 1000, $delimiter)) !== false):
        $data[] = $row;
      endwhile;
      fclose($handle);
    endif;
    return $data;
  }


}
