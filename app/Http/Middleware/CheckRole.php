<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check() || !auth()->user()->hasRole($role)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }


        return $next($request);
    }
}