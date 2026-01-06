<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Staff;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try staff first
        if (Auth::guard('staff')->attempt($request->only('email', 'password'))) {
            return redirect()->route('staff.dashboard');
        }

        // Try customer
        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
