<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Modifires;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ModifiresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 

    public function index(){
        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Modifires Users"]
        ];
        return view('dashboard.modifires.index', compact('breadcrumbs'));
    }

    public function create(){
        $roles = Role::pluck('name', 'id');
    
      
        return view('dashboard.modifires.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $validations_arr = [
            'first_name' => 'required|max:255',
             'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:modifires,email',
            'password' => 'required|min:6|confirmed',
          
        ];

        $validator = Validator::make($request->all(), $validations_arr);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            $roles = Role::pluck('name', 'id');
            return view('dashboard.users.create', compact('roles'))->withErrors($validator);
        }

        $user = Modifires::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if($user) {
             $user->syncRoles([$request->role]);
         
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'User added successfully',
                'success' => true
            ]);
          } else {
            return response()->json([
                'code' => 'danger',
                'title' => 'Error',
                'message' => 'Something went wrong.',
                'success' => false
            ]);
        }

    }
} 
