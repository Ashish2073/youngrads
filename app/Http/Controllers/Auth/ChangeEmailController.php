<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Rules\NotCurrentEmail;
use App\Models\User;
use Exception;

class ChangeEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showLinkRequestForm()
    {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('auth.email.change', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function resend(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->sendChangeEmailVerificationNotification();
        return back()->with([
            'resent' => 'A new fresh verification link has been sent to your new email address.'
        ]);
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'confirmed', 'unique:users,email,' . auth()->user()->id, new NotCurrentEmail()]
        ]);
        
        $user = User::find(auth()->user()->id);
        $user->new_email = $request->email;
        
        if($user->save()) {
            $user->sendChangeEmailVerificationNotification();
            return back()->with([
                'code' => 'success', 
                'message' => 'A new verification link has been sent to your new email address.'
            ]);
        } else {
            return back()->with([
                'code' => 'danger',
                'message' => 'Error! Something went wrong. Please try again.'
            ]);
        }
        

    }
}
