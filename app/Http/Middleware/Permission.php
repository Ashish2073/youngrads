<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /** 
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission): Response
    {
         // Check if the user is authenticated
         if (Auth::check() ) {
            // Check if the user has the required permission havePermission
            // if (Auth::user()->hasPermission($permission) || auth('admin')->user()->getRoleNames()[0]=="Admin") {
            //     return $next($request);
            // } 
            $userrole=json_decode(auth('admin')->user()->getRoleNames(),true);

            if (hasPermissionForRoles($permission,$userrole) || auth('admin')->user()->getRoleNames()[0]=="Admin") {
                return $next($request);
            }
        } 

        // Redirect or return an error response based on your needs
        return redirect()->back()->with('permissionerror', 'Unauthorized. You do not have permission to access this resource.');
    }
}
