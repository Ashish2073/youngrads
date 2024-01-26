<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('auth.passwords.email', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldpassword' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        
        if (!(Hash::check($request->get('oldpassword'), Auth::user()->password))) {
            return back()->with([
                "code" => "danger", 
                "message" => "Your \"Current Password\" does not matches with the password you provided. Please try again."
            ]);
        }

        // if(strcmp($request->get('oldpassword'), $request->get('password')) == 0){
        //     //Current password and new password are same
        //     return back()->with([
        //         "code" => "danger", 
        //         "message" => "Your current password does not matches with the password you provided. Please try again."
        //     ]);
        // }

        // $Validator = Validator::make($request->all(), [
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        // ])->validate();

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        $user->save();
        return back()->with(["code" => "success", "message" => "Password changed successfully!"]);

    }
}
