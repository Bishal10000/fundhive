<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // If user is not logged in, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // If user does not have the required role, abort
        if (!Auth::user()->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
