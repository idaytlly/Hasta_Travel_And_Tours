<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate and store the result in $validated
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:15',
            'ic'         => 'nullable|digits:12|unique:users,ic,' . $user->id,
            'street'     => 'nullable|string|max:255',
            'city'       => 'nullable|string|max:100',
            'state'      => 'nullable|string|max:100',
            'postcode'   => 'nullable|string|max:10',
            'license_no' => 'nullable|string|max:50',
        ]);

        // Update user with only validated data
        //$user->update($validated);
        $user->update($request->all());
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

}
?>
