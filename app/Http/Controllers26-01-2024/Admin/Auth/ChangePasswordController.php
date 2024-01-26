<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldpassword' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        if (!(Hash::check($request->get('oldpassword'), auth()->user('admin')->password))) {
            return back()->with([
                "code" => "danger", 
                "error" => "Invalid current password. Please try again."
            ]);
        }

        //Change Password
        $user = Auth::user('admin');
        $user->password = bcrypt($request->get('password'));
        $user->save();
        return back()->with(["code" => "success", "success" => "Password changed successfully!"]);

    }
}
