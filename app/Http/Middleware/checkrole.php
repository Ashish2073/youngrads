<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;

class checkrole
{
    use ThrottlesLogins;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    



    public function handle(Request $request, Closure $next): Response
    {
        
   

     

        if (Auth::check()) {
            $userrole=json_decode(auth('admin')->user()->getRoleNames(),true);
            if(!empty($userrole)){
                return $next($request); 
            }
           
  
        }   
      
        Auth::guard('admin')->logout();
        return redirect()->back()->with('error', 'Unauthorized. You do not have assign role .');

       
    } 
}
