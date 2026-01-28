<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken
{
    public function handle(Request $request, Closure $next)
    {
        // Minimal stub: skip CSRF checks for now so dev routes work.
        return $next($request);
    }
}
