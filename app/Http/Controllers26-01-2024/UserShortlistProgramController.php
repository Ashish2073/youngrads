<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserShortlistProgram;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\UserApplication;

class UserShortlistProgramController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {

    $breadcrumbs = [
      ['link' => "my-account", 'name' => "Dashboard"], ['name' => "Shortlisted Programs"]
    ];
    $shortlistPrograms = UserShortlistProgram::join('campus_programs', 'users_shortlist_programs.campus_program_id', '=', 'campus_programs.id')
      ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
      ->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
      ->join('universities', 'campus.university_id', '=', 'universities.id')
      ->select('users_shortlist_programs.id as id', 'universities.name as university', 'campus.name as campus', 'users_shortlist_programs.campus_program_id as campus_program_id', 'programs.name as program')->where('users_shortlist_programs.user_id', '=', Auth::id())
      ->get();

    if (request()->ajax()) {
      return Datatables::of($shortlistPrograms)
        ->editColumn('program', function ($row) {
          return tooltip(Str::limit($row->program, 40, '...'), $row->program);
        })
        ->addColumn('action', function ($row) {
          return "<a title='View Details' class='btn btn-sm btn-icon btn-outline-primary ' href=" . route('program-details', $row->campus_program_id) . "><i class='fa fa-list'></i></a> <button title='Remove from Shortlist' class='btn btn-sm btn-icon btn-outline-danger remove' style='margin: 0 1%' data-id=" . $row->id . "><i class='fa fa-trash'></i></button>";
        })
        ->addColumn('check_apply', function ($row) {
          $html = "";
          $count = UserApplication::where([['user_id', '=', Auth::id()], ['campus_program_id', '=', $row->campus_program_id]])->count();
          $html = "<button type='button' class='btn btn-sm btn-outline-primary apply' data-id=" . $row->campus_program_id . "  data-toggle='modal' data-target='#apply-model'><i class='fa fa-bullseye' aria-hidden='true'></i> Apply Now</button></div>";
          if ($count > 0) {
            $html .= "<br><a class='btn btn-sm btn-outline-dark mt-1' href='" . route('applications', 'id=' . $row->campus_program_id) . "'>Submitted Applications</a>";
          } else {
          }
          return $html;
        })
        ->rawColumns(['program', 'action', 'check_apply'])
        ->make(true);
    } else {
      return view('user_shortlist_program.index', compact('breadcrumbs'));
    }
  }

  public function addProgram(Request $request)
  {
    // $shortlistProgram = new UserShortlistProgram;
    // $shortlistProgram->user_id = Auth::id();
    // $shortlistProgram->campus_program_id = $request->campus_program_id;
    $shortlistProgram = UserShortlistProgram::create([
      'user_id' => Auth::id(),
      'campus_program_id' => $request->campus_program_id
    ]);
    if ($shortlistProgram) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Program Shortlisted',
        'success' => true,
        'id' => $shortlistProgram->id
      ]);
    } else {
      return response()->json([
        'code' => 'error',
        'title' => 'Ops',
        'message' => 'Something went wrong',
        'success' => false
      ]);
    }
  }

  public function removeProgram(Request $request)
  {
    $deleteProgram = UserShortlistProgram::find($request->id)->delete();

    if ($deleteProgram) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Program has been removed from Shortlisted Programs',
        'success' => true,
      ]);
    } else {
      return response()->json([
        'code' => 'error',
        'title' => 'Ops',
        'message' => 'Something went wrong',
        'success' => false
      ]);
    }
  }

  public static function checkProgram($id)
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
}
