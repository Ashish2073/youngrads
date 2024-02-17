<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Modifier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class ModifiersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 

    public function index(){

        // $users = Modifier::find(10);

        // dd( $users->getRoleNames());
    
        if(request()->ajax()) { 
            $users = Modifier::all();
            return Datatables::of($users)
                ->editColumn('last_name', function($row) {
                    return $row->last_name ?? "N/A";
                })->addColumn('role',function($row){
                    // return $row->getRoleNames();
                    return "<button class='btn btn-danger role-delete btn-icon btn-round'  onclick='userRole($row->id)' ><i class='feather icon-eye'></i></button>";


                })
                ->rawColumns(['email','role'])
                ->make(true);
        } else {


        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Modifiers Users"]
        ];
        return view('dashboard.modifiers.index', compact('breadcrumbs'));
    }
    }

    public function create(){
        $roles = Role::where('name','!=','Admin')->get();
    
      
        return view('dashboard.modifiers.create', compact('roles'));
    }


    public function store(Request $request)
    {

          
     
        $validations_arr = [
            'first_name' => 'required|max:255',
             'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:modifiers,email',
            'password' => 'required|min:6|confirmed',
           
        ];

        $validator = Validator::make($request->all(), $validations_arr);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            $roles = Role::pluck('name', 'id');
            return view('dashboard.modifiers.create', compact('roles'))->withErrors($validator);
        }

        $user = Modifier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if($user) {
            
            $user->assignRole($request->rolename);
        
           
         
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

    public function edit($id)
    {
        $user = Modifier::find($id);
        // $roles = Role::pluck('name', 'id');
        $roles = Role::where('name','!=','Admin')->get();

         $user->role =($user->getRoleNames());

       
       
    

  

       
        return view('dashboard.modifiers.edit', compact('user', 'roles'));

        // if(auth('admin')->user()->id == $user->id){
        //     $edit_link = route('admin.profile', $user->id);
        //     return view('dashboard.inc.info', [
        //         'message' => "Please <a href='$edit_link'>click</a> here to edit your own profile"
        //     ]);
        // }else{
        // return view('dashboard.users.edit', compact('user', 'roles'));
        // }
    }


    public function update(Request $request, $id)
    {
       
       
 
        $user =  Modifier::findOrFail($id);
        $roles = Role::where('name','!=','Admin')->get();
        // $user->role = $user->getRoleNames()[0];

        $validations_arr = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:modifiers,email,' . $id,
            // 'role' => 'required'
        ];
        if(!empty($request->password)) {
            $validations_arr['password'] = 'required|confirmed|min:6';
        }
        $validator = Validator::make($request->all(), $validations_arr);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            if(is_array($user->getRoleNames()) && !empty($user->getRoleNames())) {
                $user->role = $user->getRoleNames()[0]['name'];
            } else {
                $user->role = "";
            }
            return view('dashboard.modifiers.edit', compact('user', 'roles'))->withErrors($validator);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if($user->save()) {
            // $user->syncRoles([$request->role]);
              
            $user->syncRoles($request->rolename);


            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'User updated successfully',
                'success' => true
            ]);
        } else {
            return response()->json([
                'code' => 'error',
                'title' => 'Error',
                'message' => 'Something went wrong.',
                'success' => false
            ]);
        }
    }

    public function destroy($id)
    {
        // if (Auth::user()->id == $id) {
        //     flash()->warning('Deletion of currently logged in user is not allowed :(')->important();
        //     return redirect()->back();
        // }

        // if (User::findOrFail($id)->delete()) {
        //     flash()->success('User has been deleted');
        // } else {
        //     flash()->success('User not deleted');
        // }

        // return redirect()->back();

        $user = Modifier::find($id);
        $user->delete();
          if($user->save()){
              return response()->json([
                  'code' => 'success',
                  'title' => 'Deleted',
                  'message' => 'User Successfully Deleted',
                  'success' => true
              ]);
          }
    }

    public function userRoles(Request $request){
        $user=Modifier::find($request->id);
       
        return $user->getRoleNames();


    }



} 