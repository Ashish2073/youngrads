<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Admin;
use App\Events\Sendmailmodifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class ModifiersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('userspermission:modifiers_view',['only'=>['index']]);
        $this->middleware('userspermission:modifiers_add',['only'=>['create','store']]);
        $this->middleware('userspermission:modifiers_edit',['only'=>['edit','update']]);
        $this->middleware('userspermission:modifiers_delete',['only'=>['destroy']]); 
    } 

    public function index(Request $request){

        // $users = Admin::find(10);

        // dd( $users->getRoleNames());
    
        if(request()->ajax()) { 

            if((session('permissionerror'))){
               
           
                return response()->json(['errorpermissionmessage'=>session('permissionerror')]);
              


            }

            if($request->get('rolename')!=null){
                $rolename=$request->get('rolename');
                $users=Admin::role($rolename)->get();

                
  
            }else{
                
                $users = Admin::where('is_super','!=','1')->get();
            }
          
            return Datatables::of($users)
                ->editColumn('last_name', function($row) {
                    
 
                    return $row->last_name ?? "N/A";
                })->addColumn('role',function($row){
                    // return $row->getRoleNames();  <button class='btn btn-danger role-view btn-icon btn-round'  onclick='userRole($row->id)' ><i class='feather icon-eye'></i></button>";
                   
                    $modifersrole=json_decode($row->getRoleNames(),true);
                    if(count($modifersrole)==0){
                        $rolesHtml="<label for='positiveNumber' id='userrole'><div  class='role-card-1 btn btn-danger'>
                        <h5>No Role</h5>
                        </div>";

                        return $rolesHtml;

 
                    }

                    $rolesHtml="<label for='positiveNumber' id='userrole'>";

                    foreach($modifersrole as $role){
                       
                     
                        $rolesHtml=$rolesHtml."
                        <div class='role-card-1'>
                        <h5>$role</h5>
                        </div>";

                    }

                    $rolesHtml=$rolesHtml.'</label>';

                    return  $rolesHtml;

                   
                //     return "<label for='positiveNumber' id='userrole'><div class='role-card-1'>
                               
                //     <h5>moderator</h5>
                // </div><div class='role-card-1'>
                   
                //     <h5>creater</h5>
                // </div><div class='role-card-1'>
                   
                //     <h5>viewer</h5>
                // </div><div class='role-card-1'>
                   
                //     <h5>watcher</h5> 
                // </div></label>";
                  
 

                })
                ->rawColumns(['email','role'])
                ->make(true);
        } else {

            $roles = Role::where('name','!=','Admin')->select('name', 'id')->get();
        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Modifiers"]
        ];
        return view('dashboard.modifiers.index', compact('breadcrumbs','roles'));
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
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'rolename'=>'required',
           
        ];

        $validator = Validator::make($request->all(), $validations_arr);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
            $roles = Role::pluck('name', 'id');
            return view('dashboard.modifiers.create', compact('roles'))->withErrors($validator);
        }

        // $user = Admin::create([
        //     'first_name' => $request->first_name,
        //     'last_name' => $request->last_name,
        //     'email' => $request->email,
        //     'username'=>strtoupper($user->id .'YGMOD'),
        //     'password' => Hash::make($request->password)
        // ]);
       

         
            $user = Admin::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        
             // Update the 'username' field after creating the user
           
        
        if($user) {
            if(in_array('supermoderator',$request->rolename)){
                $title_role="YGSUPERMOD";
            }elseif(in_array('moderator',$request->rolename)){
                $title_role="YGMODER";
            }else{
                $title_role="YGMODFIR";
            }
            $user->update([
                'username' => strtoupper($request->first_name.$title_role.$user->id)

            ]);

            
            $user->assignRole($request->rolename);
 
            if($user->hasRole($request->rolename)){ 
                $user->modifier_password=$request->password;
                event(new Sendmailmodifier($user));

            }


        
           
         
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'User added successfully and mail send to users email',
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
       
       
 
        $user =  Admin::findOrFail($id);
        $roles = Role::where('name','!=','Admin')->get();
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

            if($user){ 
                if(isset($request->password) && !empty($request->password)){
                    $user->modifier_password=$request->password;
                }
               
                event(new Sendmailmodifier($user));

            }





            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'User updated successfully and updated credential send to users mail',
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

    public function userRoles(Request $request){
        $user=Admin::find($request->id);
       
        return $user->getRoleNames();


    }



} 
