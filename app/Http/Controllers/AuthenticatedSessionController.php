<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Clear any previous intended URL first
        $request->session()->forget('url.intended');

        // Try Customer guard first
        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('customer.home');
        }

        // Try Staff guard second
        if (Auth::guard('staff')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $staff = Auth::guard('staff')->user();
            
            // Check if staff is active
            if (!$staff->is_active) {
                Auth::guard('staff')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is inactive. Please contact administrator.',
                ]);
            }
            
            // Redirect based on role
            return $this->redirectStaffBasedOnRole($staff);
        }

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Redirect staff based on their role
     */
    private function redirectStaffBasedOnRole($staff)
    {
        switch ($staff->role) {
            case 'admin':
                return redirect()->route('staff.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'runner':
                return redirect()->route('staff.delivery');
            default:
                return redirect()->route('staff.dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // Logout from the appropriate guard
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        } elseif (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}