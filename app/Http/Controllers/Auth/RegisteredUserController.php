<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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
            'email' => 'required|email|unique:customers,email',
            'ic' => 'required|digits:12|unique:customers,ic',
            'phone_no' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user with hashed password
        $customers = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'matricNum' => $request->matricNum,
            'ic' => $request->ic,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'usertype' => 'user', // default regular user
        ]);

        // Auto-login after registration
        Auth::login($customers);

        return redirect()->route('home');
    }
}
