<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Get the authenticated user
                $user = Auth::user();

                // 1. Redirect based on usertype
                if ($user->usertype === 'admin') {
                    return redirect()->route('admin.dashboard'); // Ensure this route exists!
                }

                // 2. Default redirect for regular users to the Welcome page
                // This uses the HOME constant from RouteServiceProvider
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}