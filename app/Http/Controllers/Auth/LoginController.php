<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        if (!empty(request()->get('redirect_to'))) {
            session()->put('url.intended', request()->get('redirect_to'));
        }
        return view('auth.login', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function facebookLogin()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function socialLogin($provider)
    {
        switch ($provider) {
            case 'google':
                return Socialite::driver('google')->redirect();
                break;

            case 'facebook':
                return Socialite::driver('facebook')->redirect();
                break;

            default:
                abort(422, "Invalid Social Provider");
        }
    }

    public function providerCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        if ($provider == 'facebook') {
            $findUser = User::where('provider_id', $user->id)->first();
        }
        if ($provider == 'google') {
            $findUser = User::where('provider_id', $user->id)->first();
        }

        if ($findUser) {
            Auth::login($findUser);
        } else {
            
            $user_record = User::where('email', $user->email)->count();
            if($user_record > 0) {
                return redirect(route('login'))->with([
                    'code' => 'danger',
                    'title' => 'Oops!',
                    'message' => 'Email address has already been taken.'
                ]);
            }

            $name = $user->name;
            $name = explode(" " , $name);
            if(count($name) == 2) {
                $first = $name[0];
                $last = $name[1];
            } else {
                $first = $name[0];
                $last = "";
            }
            $newUser = User::create([
                'name' => $first,
                'last_name' => $last,
                'email' => $user->email,
                'provider_id' => $user->id,
                'profile_img' => $user->avatar,
                'email_verified_at' => date("Y-m-d H:i:s")
            ]);

            Auth::login($newUser);
        }
        return redirect($this->redirectTo());
    }

    public function redirectTo()
    {
        return route('my-account');
        // return redirect()->intended(route('my-account'));
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasVerifiedEmail()) {
            $verification_link = URL::signedRoute('verification.resend', ['user' => auth()->user()->id]);
            
            Auth::logout();
            return back()->with([
                'unverified' => true,
                'code' => 'danger',
                'message' => 'Your email verification is pending. Please verify your email address.',
                'verification_link' => $verification_link
            ]);
        }
    }
}
