<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Booking;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    // Staff views their commissions
    public function index()
    {
        $commissions = Commission::where('staff_id', auth()->id())
            ->with(['booking', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalPending = Commission::where('staff_id', auth()->id())
            ->where('status', 'pending')
            ->sum('amount');
        
        $totalApproved = Commission::where('staff_id', auth()->id())
            ->where('status', 'approved')
            ->sum('amount');
        
        $totalEarned = Commission::where('staff_id', auth()->id())
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        return view('staff.commissions.index', compact('commissions', 'totalPending', 'totalApproved', 'totalEarned'));
    }

    // Show create form
    public function create()
    {
        $bookings = Booking::whereIn('status', ['active', 'completed'])
            ->with('car', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('staff.commissions.create', compact('bookings'));
    }

    // Store new commission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'service_type' => 'required|string',
            'description' => 'required|string|min:10',
            'amount' => 'required|numeric|min:1|max:1000',
        ]);

        Commission::create([
            'staff_id' => auth()->id(),
            'booking_id' => $validated['booking_id'],
            'service_type' => $validated['service_type'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'status' => 'pending',
        ]);

        return redirect()->route('staff.commissions.index')
            ->with('success', 'Commission submitted successfully! Waiting for admin approval.');
    }
}