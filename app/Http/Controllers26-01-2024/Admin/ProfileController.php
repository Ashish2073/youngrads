<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        request()->flash();
        $user = Admin::findOrFail($id);
        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Edit Profile"]
        ];
        return view('dashboard.profile.edit', compact('user', 'breadcrumbs'));
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
        $user = Admin::findOrFail($id);

        $validations_arr = [
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $id,
            'last_name' => 'required'
        ];
        
        $request->validate($validations_arr);

        if(!empty($request->password)) {
            $validations_arr['password'] = 'required|confirmed|min:6';
        }
       
        

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        if($user->save()) {
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Profile updated successfully',
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
