<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to login
        if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Log the user in and immediately check the 'usertype' column
        return match($user->usertype) {
            'admin' => redirect()->intended(route('admin.dashboard')), 
            'staff' => redirect()->intended(route('staff.dashboard')),
            default => redirect()->intended(route('profile.edit')),
        };
    }

        // If login fails
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    /**
     * Logout the authenticated user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page or home page
        return redirect()->route('login');
    }
}