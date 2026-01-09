<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile.edit');
    }

    public function update(Request $request)
    {
        $customers = Customer::where('email', auth()->user()->email)->first();

        $customers->update([
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

        $customer->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    public function showProfile()
    {
        // ambil logged-in customer
        $customer = Auth::guard('web')->user(); // atau guard lain ikut setup awak

        return view('customer.profile', compact('customer'));
    }
}
