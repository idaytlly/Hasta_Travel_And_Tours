<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    // Show registration form
    public function create()
    {
        return view('auth.register');
    }

    // Handle registration form submission
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ic' => 'required|digits:12|unique:users,ic',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user with hashed password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ic' => $request->ic,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'usertype' => 'user', // default regular user
        ]);

        // Auto-login after registration
        Auth::login($user);

        // Redirect based on user type
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->usertype === 'staff') {
            return redirect()->route('staff.dashboard');
        } else {
            return redirect()->route('home'); // regular user
        }
    }
}
