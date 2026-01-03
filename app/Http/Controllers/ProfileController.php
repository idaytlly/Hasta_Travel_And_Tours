<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;  // make sure to import the Customer model

class ProfileController extends Controller
{
        public function edit()
    {
        $user = auth()->user()->load('customer');  // load customer relation
        return view('profile.edit', [
            'user' => $user,
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
        Customer::updateOrCreate(
            ['userID' => $user->id],
            $customerData
        );

        // Reload customer relation
        $user->load('customer');

        return back()->with('success', 'Profile updated successfully');
}

}
