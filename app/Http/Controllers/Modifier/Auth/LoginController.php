<?php

namespace App\Http\Controllers\Modifier\Auth;
use Auth; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Auth\ThrottlesLogins;


class LoginController extends Controller
{
   
    use ThrottlesLogins;
     public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('modifier.auth.login', [
            'pageConfigs' => $pageConfigs
        ]);
    }



    public function login(Request $request)
    {

        $this->validator($request);

        //check if the user has too many login attempts.
        if ($this->hasTooManyLoginAttempts($request)) {
            //Fire the lockout event.
            $this->fireLockoutEvent($request);

            //redirect the user back after lockout.
            return $this->sendLockoutResponse($request);
        }

        //attempt login. 
      
        if (Auth::guard('admin')->attempt($request->only('username', 'password'), $request->filled('remember'))) {
            //Authenticated
            
            activity('login')
            ->causedBy(Auth::guard('admin')->user())
            ->withProperties(['ip' => $request->ip()])
            ->log('modifier Login');
            
         
            return redirect()
                ->intended(route('admin.home'))
                ->with('status', 'You are Logged in as Modifer!');
        }

        //keep track of login attempts from the user.
        $this->incrementLoginAttempts($request);

        //Authentication failed
        return $this->loginFailed();
    }



    public function logout(Request $request)
    {
  
        activity('logout')
        ->causedBy(Auth::guard('admin')->user())
        ->withProperties(['ip' => $request->ip()])
        ->log('Modifier logout');


        Auth::guard('admin')->logout();
      


        return redirect()
            ->route('modifier.login')
            ->with('status', 'Modifier has been logged out!');
    }

    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'username' => 'required|exists:admins|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'username.exists' => 'These credentials do not match our records.',
        ];

        //validate the request. 
        $request->validate($rules, $messages);
    }


    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Login failed, please try again!');
    }

    
    public function username()
    {
        return 'username';
    }


   
}
