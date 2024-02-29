<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Authorizable;

class AdminController extends Controller
{
    // use Authorizable;

    public function __construct()
    {
        $this->middleware('auth:admin');
    } 

    public function index()
    {
        if(request()->ajax()) { 
            $users = Admin::role('Admin')->get();
            return Datatables::of($users)
                ->editColumn('last_name', function($row) {
                    return $row->last_name ?? "N/A";
                })
                ->rawColumns(['email'])
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Admin Users"]
            ];
            return view('dashboard.users.index', compact('breadcrumbs'));
        }
    }

    public function create()
    {
        $roles = Role::pluck('name', 'id');
        request()->flash();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validations_arr = [
            'first_name' => 'required|max:255',
             'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            // 'role' => 'required'
        ];

        $validator = Validator::make($request->all(), $validations_arr);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            $roles = Role::pluck('name', 'id');
            return view('dashboard.users.create', compact('roles'))->withErrors($validator);
        }

        $user = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if($user) {
            // $user->syncRoles([$request->role]);
            $user->syncRoles(['Admin']);
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
        $user = Admin::find($id);
        $roles = Role::pluck('name', 'id');

        $user->role = isset($user->getRoleNames()[0]) ? $user->getRoleNames()[0] :  "";

        if(auth('admin')->user()->id == $user->id){
            $edit_link = route('admin.profile', $user->id);
            return view('dashboard.inc.info', [
                'message' => "Please <a href='$edit_link'>click</a> here to edit your own profile"
            ]);
        }else{
        return view('dashboard.users.edit', compact('user', 'roles'));
        }
    }
 
    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);
        $roles = Role::pluck('name', 'id');
        // $user->role = $user->getRoleNames()[0];

        $validations_arr = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $id,
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
            return view('dashboard.users.edit', compact('user', 'roles'))->withErrors($validator);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        if($user->save()) {
            // $user->syncRoles([$request->role]);
            $user->syncRoles(['Admin']);
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

        $user = Admin::find($id);
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

    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if (!$user->hasAllRoles($roles)) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);
        return $user;
    }

    function chageProfilePic(Request $request){

        $validator = Validator::make($request->all(),['profile' => 'required|image|mimes:jpeg,png,jpg,gif,png,ico|max:2048'],
        [ 'profile.mimes'=>'Please add profile picture with JPEG,PNG,GIF format',
          'profile.max'=>'Image size must be less than 2MB.'
        ]);

        if($validator->fails()){
            // $validator->errors()->add('form_error', 'Error! Please check below');
            // $request->flash();
            return response()->json([
                'success'=>false,
                'error' => $validator->errors()->all()
            ]);
        }
        $fileName = time().".".Auth::id().".".$request->profile->extension();

        $request->profile->move(public_path('uploads/profile_pic'),$fileName);
        $admin = Admin::find(Auth::id());
        //$admin->profile_image = "uploads\profile_pic".$fileName;
         $admin->profile_image = $fileName;
        if($admin->save()){
        return response()->json([
            'success' =>true,
            'image' => asset("uploads/profile_pic/".$fileName),
             'message' => "Profile picture changed successfully"
            ]);
        }else{

            return response()->json([
                'success' =>false,
                 'message' => "Somthing Went Wrong"
                ]);

        }

    }
}
