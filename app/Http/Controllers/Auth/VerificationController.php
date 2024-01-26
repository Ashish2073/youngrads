<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Auth;

class VerificationController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth')->except(['verify', 'resend']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    public function verify(Request $request)
    {
        
        if(auth()->check()) {
            $user = auth()->user();
        } else {
            $user = User::findOrFail($request->route('id'));
        }
        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if(is_null($user->new_email)) {
            if ($user->hasVerifiedEmail()) {
                return redirect($this->redirectPath());
            }
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            Auth::login($user);
        }

        if(!is_null($user->new_email)) {
            // check if email has taken by someone else during wating for verification
            $user_record = User::where([
                'email' => $user->new_email
            ])->get();

            if($user_record->count() == 0) {
                $user = User::find($user->id);
                $user->email = $user->new_email;
                $user->new_email = null;
                $user->save();
            } else {
                session([
                    'code' => 'danger',
                    'message' => 'Oops! "'. $user->new_email .'" has taken by someone else'
                ]);
                return redirect()->route('my-account');
            }
        }
        session(['code' => 'success', 'message' => 'Email address verified successfully!']);
        return redirect()->route('my-account')->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $user_id = $request->get('user');
        $user = User::findOrFail($user_id);
        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new Response('', 204)
                        : redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return $request->wantsJson()
                    ? new Response('', 202)
                    : back()->with('resent', true);
    }
}
