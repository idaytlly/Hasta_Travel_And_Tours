<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer; // kalau pakai model User untuk registration
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'matricNum' => 'required|string|max:255|unique:customers,matricNum',
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:customers,email',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        // Create user
        Customer::create([
            'matricNum' => $request->matricNum,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // Redirect to login or dashboard
        return redirect()->route('login')->with('success', 'Account created successfully. Please login.');
    }
}
