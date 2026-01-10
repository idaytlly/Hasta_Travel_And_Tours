<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $userType = $request->input('user_type', 'customer');
        
        if ($userType === 'staff') {
            // Staff authentication (simple version)
            if ($credentials['email'] === 'staff' && $credentials['password'] === 'password123') {
                // Set staff cookie and redirect to staff dashboard
                return redirect()->route('staff.dashboard')
                    ->cookie('staff_authenticated', 'true', 480); // 8 hours
            }
            
            // If staff credentials fail
            return back()->withErrors([
                'email' => 'Invalid staff credentials.',
            ])->onlyInput('email');
        } else {
            // Customer authentication (using Laravel's built-in)
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('customer.home'));
            }
            
            // If customer credentials fail
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $userType = $request->cookie('staff_authenticated') ? 'staff' : 'customer';
        
        if ($userType === 'staff') {
            // Staff logout - clear cookie
            return response()
                ->redirectToRoute('login')
                ->cookie(cookie()->forget('staff_authenticated'));
        } else {
            // Customer logout - Laravel default
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }
    }
}