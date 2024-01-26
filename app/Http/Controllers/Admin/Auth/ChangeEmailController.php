<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class ChangeEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|confirmed|unique:admins,email,' . auth()->user('admin')->id
        ]);


        $admin = Admin::find(auth()->user('admin')->id);
        $admin->email = $request->get('email');
        if ($admin->save()) {
            return redirect()->back()->with("success-email", "Email changed successfully!");
        } else {
            return redirect()->back()->with("error-email", "Error while changing your email address.");
        }
    }
}
