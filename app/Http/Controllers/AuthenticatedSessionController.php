<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\Customer;

class AuthenticatedSessionController extends Controller
{
    // Show the login form with both customer and staff options
    public function create()
    {
        // If already authenticated, redirect to appropriate dashboard
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }
        
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }
        
        return view('auth.login');
    }

    // Handle an incoming authentication request for both customer and staff
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:customer,staff', // Added user_type
        ]);

        $remember = $request->boolean('remember');
        $userType = $request->input('user_type');

        if ($userType === 'staff') {
            // Staff authentication
            return $this->authenticateStaff($request, $credentials, $remember);
        } else {
            // Customer authentication
            return $this->authenticateCustomer($request, $credentials, $remember);
        }
    }

    /**
     * Authenticate staff user
     */
    protected function authenticateStaff(Request $request, array $credentials, bool $remember)
    {
        $staff = Staff::where('email', $credentials['email'])->first();

        if (!$staff) {
            return back()->withErrors([
                'email' => 'Staff account not found.',
            ])->withInput($request->only('email', 'user_type'));
        }

        // Check if account is active
        if (!$staff->is_active) {
            return back()->withErrors([
                'email' => 'Your staff account has been deactivated. Please contact administrator.',
            ])->withInput($request->only('email', 'user_type'));
        }

        // Verify password
        if (!Hash::check($credentials['password'], $staff->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials for staff account.',
            ])->withInput($request->only('email', 'user_type'));
        }

        // Attempt to authenticate using staff guard
        if (Auth::guard('staff')->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        )) {
            $request->session()->regenerate();

            // Redirect based on role
            return $this->redirectStaffBasedOnRole($staff);
        }

        return back()->withErrors([
            'email' => 'Authentication failed for staff account.',
        ])->withInput($request->only('email', 'user_type'));
    }

    /**
     * Redirect staff based on their role
     */
    protected function redirectStaffBasedOnRole($staff)
    {
        // You can customize redirects based on role
        $intended = session()->pull('url.intended');
        
        if ($intended) {
            return redirect()->to($intended);
        }

        // Default redirect to staff dashboard
        return redirect()->intended(route('staff.dashboard'));
    }

    /**
     * Authenticate customer user
     */
    protected function authenticateCustomer(Request $request, array $credentials, bool $remember)
    {
        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('customer.home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'user_type'));
    }

    // Log the user out of the application
    public function destroy(Request $request)
    {
        // Determine which guard is active
        if (Auth::guard('staff')->check()) {
            // Staff logout
            Auth::guard('staff')->logout();
            $redirectRoute = 'staff.login';
        } elseif (Auth::guard('customer')->check()) {
            // Customer logout
            Auth::guard('customer')->logout();
            $redirectRoute = 'guest.home';
        } else {
            // Default logout for web guard
            Auth::logout();
            $redirectRoute = '/';
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute);
    }

    /**
     * Show staff login form (optional separate route)
     */
    public function showStaffLogin()
    {
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }

        return view('staff.auth.login');
    }

    /**
     * Show customer login form (default)
     */
    public function showCustomerLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        return view('auth.login', ['user_type' => 'customer']);
    }
}