<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- ADD THIS LINE

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // This line will now work!
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}