<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware 
// the reason why we create adminmiddleware is so to restrict the access to admin page to the authorized user (which is Auth)
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == User::ADMIN_ROLE_ID){  
            return $next($request);
        }

        return redirect()->route('index'); // go to homepage if the above condition is not true (no access to admin pages)
    }
    // Request $request -> This parameter represents the incoming HTTP request from login form. It handles the request from the login form
    // Closure $next -> A closure that represents the next step in the middleware. What will happen next after checking the condition to be true
    // Auth::check() -> check if the current user is authenticated
    // && -> 2 conditions must be true. 1st condition and 2nd condition must be true. 
    // Check the user's role id must be exactly the same as admin role id
    // if its true, then return to $next($request); -> If the user is authenticated and has an admin role, the middleware allows the request to proceed (to access the admin functions later on)
}
