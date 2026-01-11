<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Customer;

class AuthenticatedSessionController extends Controller
{
    // Show login form
    public function create()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }
        
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard.index');
        }
        
        return view('auth.login');
    }

    // Handle login
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');
        $userType = $request->input('user_type');

        if ($userType === 'staff') {
            return $this->authenticateStaff($request, $credentials, $remember);
        }

        return $this->authenticateCustomer($request, $credentials, $remember);
    }

    protected function authenticateStaff(Request $request, array $credentials, bool $remember)
    {
        if (Auth::guard('staff')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectStaffBasedOnRole(Auth::guard('staff')->user());
        }

        return back()->withErrors([
            'email' => 'Invalid staff credentials.',
        ])->withInput($request->only('email', 'user_type'));
    }

    protected function redirectStaffBasedOnRole($staff)
    {
        // Example: differentiate admin vs staff
        if ($staff->role === 'admin') {
            return redirect()->intended(route('staff.dashboard.index')); // same dashboard, admin features
        }

        return redirect()->intended(route('staff.dashboard.index')); // staff dashboard
    }

    protected function authenticateCustomer(Request $request, array $credentials, bool $remember)
    {
        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('customer.home'));
        }

        return back()->withErrors([
            'email' => 'Invalid customer credentials.',
        ])->withInput($request->only('email', 'user_type'));
    }

    // Logout
    public function destroy(Request $request)
    {
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        } elseif (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // always go back to login
    }
}
