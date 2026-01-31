<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if staff is authenticated using Laravel's auth guard
        if (!Auth::guard('staff')->check()) {
            return redirect('/staff')
                ->with('error', 'Please login as staff first');
        }
        
        return $next($request);
    }
}