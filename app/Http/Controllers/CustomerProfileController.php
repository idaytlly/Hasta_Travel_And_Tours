<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.profile.edit', compact('customer'));
    }

    public function update(Request $request)
    {
        // Get logged-in customer
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('login');
        }

        $customer->update([
            'name' => $request->name,
            'ic' => $request->ic,
            'matricNum' => $request->matricNum,
            'license_no' => $request->license_no,
            'phone_no' => $request->phone_no,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'emergency_name' => $request->emergency_name,
            'emergency_phoneNo' => $request->emergency_phoneNo,
            'emergency_relationship' => $request->emergency_relationship,
        ]);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function showProfile()
    {
        $customer = Auth::guard('customer')->user();

        return view('customer.profile', compact('customer'));
    }
}
