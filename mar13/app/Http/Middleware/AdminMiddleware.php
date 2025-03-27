<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // First check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Then check if user is admin
        if (Auth::user()->usertype !== 'admin') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }

        // If all checks pass, proceed
        return $next($request);
    }
}
