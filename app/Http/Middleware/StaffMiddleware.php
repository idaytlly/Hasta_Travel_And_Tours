<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user login
        if (Auth::check() && Auth::user()->role === 'staff') {
            return $next($request);
        }

        // Kalau bukan staff, redirect ke home atau abort 403
        return redirect()->route('home')->with('error', 'You dont have any access to this site.');
        // atau boleh juga: abort(403);
    }
}
