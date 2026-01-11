<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Same view for both
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('staff')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $staff = Auth::guard('staff')->user();
            
            // Redirect based on role
            switch ($staff->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'manager':
                    return redirect()->intended('/manager/dashboard');
                case 'staff':
                    return redirect()->intended('/staff/dashboard');
                case 'runner':
                    return redirect()->intended('/runner/dashboard');
                default:
                    return redirect()->intended('/staff/dashboard');
            }
        }

        // If staff login fails, check if it's a customer
        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/customer/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}