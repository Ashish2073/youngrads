<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Suspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // if (!Route::is('admin.*')) {
            if(Auth::check()) {
                if(auth()->user()->user_status == "suspended") {
                    Auth::logout();
                    return redirect(route('login'))->with([
                        'suspended_profile' => true
                    ]);;
                }
            }
        // } 
        return $response;
    }
}
