<?php
// app/Http/Controllers/Staff/CustomerController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Payment;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        
        // Filters
        if ($request->has('status') && $request->status != 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'verified') {
                $query->where('verification_status', 'verified');
            } elseif ($request->status === 'pending') {
                $query->where('verification_status', 'pending');
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_no', 'LIKE', "%{$search}%")
                  ->orWhere('license_no', 'LIKE', "%{$search}%");
            });
        }
        
        $customers = $query->withCount(['bookings', 'payments' => function($query) {
            $query->where('payment_status', 'paid');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        
        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('is_active', true)->count(),
            'verified' => Customer::where('verification_status', 'verified')->count(),
            'new_today' => Customer::whereDate('created_at', today())->count(),
        ];
        
        return view('staff.customers.index', compact('customers', 'stats'));
    }

    public function show($id)
    {
        $customer = Customer::withCount(['bookings', 'payments' => function($query) {
            $query->where('payment_status', 'paid');
        }])->findOrFail($id);
        
        // Recent bookings
        $recentBookings = $customer->bookings()
            ->with(['vehicle', 'payments'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Recent payments
        $recentPayments = Payment::whereHas('booking', function($query) use ($id) {
                $query->where('customer_id', $id);
            })
            ->with(['booking.vehicle'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();
        
        // Statistics
        $customerStats = [
            'total_spent' => $recentPayments->where('payment_status', 'paid')->sum('amount'),
            'completed_bookings' => $customer->bookings()->where('booking_status', 'completed')->count(),
            'cancelled_bookings' => $customer->bookings()->where('booking_status', 'cancelled')->count(),
            'pending_payments' => $recentPayments->where('payment_status', 'pending')->count(),
        ];
        
        return view('staff.customers.show', compact('customer', 'recentBookings', 'recentPayments', 'customerStats'));
    }

    public function create()
    {
        return view('staff.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'phone_no' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'license_no' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'license_no' => $request->license_no,
            'license_expiry' => $request->license_expiry,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'emergency_contact' => $request->emergency_contact,
            'is_active' => true,
            'verification_status' => 'verified', // Auto-verify for staff-created accounts
        ]);

        return redirect()->route('staff.customers.show', $customer->id)
                       ->with('success', 'Customer created successfully!');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('staff.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone_no' => 'required|string|max:20',
            'license_no' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'verification_status' => 'in:verified,pending,rejected'
        ]);

        $customer->update($request->except('password'));
        
        return redirect()->route('staff.customers.show', $customer->id)
                       ->with('success', 'Customer updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'is_active' => 'boolean',
            'verification_status' => 'in:verified,pending,rejected',
            'status_notes' => 'nullable|string|max:500'
        ]);

        $customer->update([
            'is_active' => $request->is_active,
            'verification_status' => $request->verification_status
        ]);

        return back()->with('success', 'Customer status updated successfully!');
    }

    public function bookingHistory($id)
    {
        $customer = Customer::findOrFail($id);
        $bookings = $customer->bookings()
            ->with(['vehicle', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('staff.customers.booking-history', compact('customer', 'bookings'));
    }

    public function paymentHistory($id)
    {
        $customer = Customer::findOrFail($id);
        $payments = Payment::whereHas('booking', function($query) use ($id) {
                $query->where('customer_id', $id);
            })
            ->with(['booking.vehicle'])
            ->orderBy('payment_date', 'desc')
            ->paginate(20);
        
        return view('staff.customers.payment-history', compact('customer', 'payments'));
    }

    public function resetPassword(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $customer->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password reset successfully!');
    }
}