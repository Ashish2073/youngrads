<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UnivCampusImport;
use App\Imports\CampusProgramImport;

use App\Models\Program;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Import"]
        ];
        return view('dashboard.import.index', [
            'breadcrumbs' => $breadcrumbs
        ]); 
    }
 
    public function importUnivCampus()
    {
        $records = Excel::import(new UnivCampusImport, request()->file('univ_campus_file'));
        return response()->json([
            'success' => true,
            'title' => 'Success',
            'code' => 'success',
            'message' => 'File Imported Successfully!',
        ]);
    }

    public function importPrograms()
    {
        $import = new CampusProgramImport();
        Excel::import($import, request()->file('programs_file'));
        return response()->json($import->response);
    }
}
