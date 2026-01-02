<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(){
    return view('profile.edit', [
        'user' => auth()->user(),
    ]);
    }

    public function update(Request $request)
    {
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string',
        'ic' => 'nullable|string',
        'street' => 'nullable|string',
        'city' => 'nullable|string',
        'state' => 'nullable|string',
        'postcode' => 'nullable|string',
        'license_no' => 'nullable|string',
        'password' => 'nullable|string|min:8|confirmed', // password_confirmation required
    ]);

    $data = $request->only([
        'name', 'email', 'phone', 'ic', 
        'street', 'city', 'state', 'postcode', 'license_no'
    ]);

    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    $user->update($data);

    return back()->with('success', 'Profile updated successfully');
    }

}
