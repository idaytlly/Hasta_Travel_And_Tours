<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Don't redirect if already on correct dashboard
                if ($request->is('staff/*') || $request->is('admin/*')) {
                    return $next($request);
                }
                
                // Redirect based on usertype
                if (in_array($user->usertype, ['staff', 'admin'])) {
                    return redirect('/staff/dashboard');
                }
                
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}