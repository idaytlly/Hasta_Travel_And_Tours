<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if staff is authenticated via cookie
        if ($request->cookie('staff_authenticated') !== 'true') {
            return redirect()->route('staff.login')
                ->with('error', 'Please login as staff first');
        }
        
        return $next($request);
    }
}