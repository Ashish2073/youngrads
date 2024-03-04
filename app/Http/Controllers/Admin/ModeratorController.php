<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin'); 

        $this->middleware('userspermission:moderators_view',['only'=>['index']]);
        $this->middleware('userspermission:moderators_add',['only'=>['create','store']]);
        $this->middleware('userspermission:moderators_edit',['only'=>['edit','update']]);
        $this->middleware('userspermission:moderators_delete',['only'=>['destroy']]); 
     
    }  
     

    public function index(Request $request){

        // $users = Admin::find(10);

        // dd( $users->getRoleNames());
        $moderator=Admin::role('moderator')->with(['supermoderator','student'])->get();
   
        

    
        if(request()->ajax()) { 

            if((session('permissionerror'))){ 
               
           
                return response()->json(['errorpermissionmessage'=>session('permissionerror')]);
              


            }

            $moderators=Admin::role('moderator')->with(['supermoderator','student'])->get();



            if(isset($request->moderators) && !isset($request->supermoderators)){
                $moderators=Admin::role('moderator')->with(['supermoderator','student'])
                ->whereIn('id', ($request->moderators))->get();
               
            }
            if(!isset($request->moderators) && isset($request->supermoderators)){
                $moderators=Admin::role('moderator')->with(['supermoderator','student'])
                ->whereHas('supermoderator', function ($query) use ($request) {
                     $query->whereIn('id', $request->supermoderators);
                 })->get();

            }
            
            if(isset($request->moderators) && ($request->supermoderators)){

                 
                $moderators=Admin::role('moderator')->with(['supermoderator','student'])
                ->whereIn('id', ($request->moderators))
                ->whereHas('supermoderator', function ($query) use ($request) {
                     $query->whereIn('id', $request->supermoderators);
                 })->get();
    

            }
            
            if(isset($request->supermoderators) && $request->supermoderators[0]=="0" ){
                 
                
                $moderators=Admin::role('moderator')->with(['supermoderator','student'])
                ->where('parent_id', null)->get();


            }
               

                
        
           
           
               
            
          
             return Datatables::of($moderators)

            ->addColumn('moderator_checkbox',function ($row){
                return "<input class='moderator-checkbox' hidden name='moderatorid[]' type='checkbox' value='$row->id' />";

            })
            
                ->editColumn('moderator_username', function($row) {
                    
 
                    return $row->username ?? "N/A";
                })->editColumn('supermoderator',function($row){
                    // return $row->getRoleNames();  <button class='btn btn-danger role-view btn-icon btn-round'  onclick='userRole($row->id)' ><i class='feather icon-eye'></i></button>";
                   
                  return $row->supermoderator->username ?? "N/A";

                
 

                })->editColumn('moderator_name',function($row){
                    return ($row->first_name." ".$row->last_name)?? "N/A";

                })->editColumn('supermoderator_name',function($row){
                    if(isset($row->supermoderator->first_name) && isset($row->supermoderator->last_name)){
   
                        return $row->supermoderator->first_name." ".$row->supermoderator->last_name;


                    }else{
                       return  "N/A";
                    }

                })
                
                ->editColumn('email',function($row){
                return ($row->email)?? "N/A";

            })->editColumn('studentcount',function($row){
                return ($row->student->count())?? "N/A";

            })
                ->rawColumns(['moderator_checkbox','moderator_username','supermoderator','moderator_name','supermoderator_name','email','studentcount'])
                ->make(true);
        } else {

            $roles = Role::where('name','!=','Admin')->select('name', 'id')->get();
        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Moderators"]
        ];

        $supermoderator=Admin::role('supermoderator')->get();

        $moderator=Admin::role('moderator')->get();


        return view('dashboard.moderator.index', compact('breadcrumbs','supermoderator','moderator'));
    }
    }


    public function create(){
      
    
      
        return view('dashboard.moderator.create');
    }


    
    public function edit($id)
    {

        $user = Admin::find($id);
        // $roles = Role::pluck('name', 'id');
       
        return view('dashboard.moderator.edit', compact('user'));

    
    }


    public function store(Request $request)
    { 

          
     
        $validations_arr = [ 
            'first_name' => 'required|max:255',
             'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|min:6|confirmed',
           
           
        ];

        $validator = Validator::make($request->all(), $validations_arr);

        if ($validator->fails()) {
            $validator->errors()->add('form_error', 'Error! Please check below');
            $request->flash();
           
            return view('dashboard.moderator.create')->withErrors($validator);
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
            $user->update([
                'username' => strtoupper('YGMOD'.$user->id)

            ]);

            
            $user->assignRole('moderator');

            activity('Moderator Created')  
            ->causedBy(Auth::guard('admin')->user())
            ->withProperties(['ip' => $request->ip()])
            ->log('Moderator Created');

        
           
         
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


    public function update(Request $request, $id)
    {
       
       
 
        $user =  Admin::findOrFail($id);
     
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
          
            return view('dashboard.moderator.edit', compact('user'))->withErrors($validator);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if($user->save()) {
            // $user->syncRoles([$request->role]);
              
            $user->syncRoles('moderator');


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




}
