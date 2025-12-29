<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingManagementController extends Controller
{
    /**
     * Display the list of all bookings with status filtering.
     * Matches the 'All Order', 'Pending', 'Approved', 'Rejected' tabs.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Booking::with('car')->orderBy('created_at', 'desc');

        // Apply filtering based on tabs
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $bookings = $query->get();

        return view('admin.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show the specific booking details (Admin POV with Approve/Reject).
     */
    public function show($id): View
    {
        // Use withTrashed() so admin can see details even if user cancelled it
        $booking = Booking::withTrashed()->with('car')->findOrFail($id);
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Approve the booking.
     */
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'approved'
        ]);
        
        return redirect()->route('admin.bookings.index', ['status' => 'approved'])
            ->with('success', 'Booking ID ' . $booking->id . ' has been approved.');
    }

    /**
     * Reject the booking.
     */
    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'rejected'
        ]);
        
        return redirect()->route('admin.bookings.index', ['status' => 'rejected'])
            ->with('success', 'Booking ID ' . $booking->id . ' has been rejected.');
    }
}