<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRequestsDuringMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        // Minimal stub: do not block requests during development.
        return $next($request);
    }
}
