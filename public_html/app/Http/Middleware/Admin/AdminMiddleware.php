<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has admin privileges
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Allow access
        }

        // Redirect to home or show error if not an admin
        return redirect('/')->with('error', 'Unauthorized Access');
    }
}
