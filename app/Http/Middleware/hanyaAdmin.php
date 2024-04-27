<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class hanyaAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403);
        }
        return $next($request);
    }
}