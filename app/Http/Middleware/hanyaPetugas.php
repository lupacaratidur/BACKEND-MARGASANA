<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class hanyaPetugas
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->level !== 'petugas') {
            abort(403);
        }
        return $next($request);
    }
}