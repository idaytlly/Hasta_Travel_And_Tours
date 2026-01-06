<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class ProfileController extends Controller
{
    /**
     * Show the profile view page
     */
    public function show()
    {
        $user = auth()->user()->load('customer');
        
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get(); // assuming you use Laravel notifications

        return view('profile.notifications', compact('notifications'));
    }


    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = auth()->user()->load('customer');
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile
     */
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
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update users table data
        $userData = $request->only(['name', 'email', 'phone']);
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }
        $user->update($userData);

        // Prepare data for customers table
        $customerData = [
            'name' => $request->name,
            'ic' => $request->ic,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => trim("{$request->street}, {$request->city}, {$request->state}, {$request->postcode}", ", "),
            'licenceNo' => $request->license_no,
        ];

        // Update or create customer linked to this user
        // Use 'users_id' to match your database column name
        Customer::updateOrCreate(
            ['user_id' => $user->id],  
            $customerData
        );

        // Reload customer relation
        $user->load('customer');

        return back()->with('success', 'Profile updated successfully');
    }
}