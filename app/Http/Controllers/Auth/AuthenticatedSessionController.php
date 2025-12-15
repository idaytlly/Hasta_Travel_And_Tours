<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    // Show login page
    public function create()
    {
        return view('auth.login');
    }

    // Handle login submission
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role or default dashboard
            return $this->redirectUser(Auth::user());
        }

        // Failed login
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    // Logout user
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // Redirect based on user type
    private function redirectUser($user)
    {
        return match($user->usertype) {
            'admin' => redirect()->route('admin.home'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}
