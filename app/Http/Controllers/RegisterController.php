<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Show registration form
    public function create()
    {
        return view('auth.register');
    }

    // Handle registration form submission
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Create user
        $customer = Customer::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log in the user
        auth()->login($customer);

        // Redirect after registration
        return redirect()->route('customer.home'); // change to your desired route
    }
}
