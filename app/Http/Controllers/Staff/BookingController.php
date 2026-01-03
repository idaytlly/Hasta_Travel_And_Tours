<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display staff dashboard
     */
    public function dashboard()
    {
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $activeBookings = Booking::where('status', 'active')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        
        // Add this line to get all cars
        $cars = Car::all();
        
        // Get recent bookings
        $recentBookings = Booking::with(['car', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('staff.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'activeBookings',
            'completedBookings',
            'cars',  // Add this to the compact list
            'recentBookings'
        ));
    }

    /**
     * Display all bookings
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        
        $query = Booking::with(['car', 'user'])
            ->orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $bookings = $query->paginate(20);
        
        return view('staff.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking): View
    {
        $booking->load(['car', 'user', 'inspections']);
        
        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Confirm a booking
     */
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->update(['status' => 'confirmed']);
        
        // 🔔 NOTIFICATION: Notify customer about confirmation
        if ($booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Booking Confirmed',
                "Your booking #{$booking->booking_reference} for {$booking->car->name} has been confirmed by our team.",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'car_name' => $booking->car->name
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Booking confirmed successfully');
    }

    /**
     * Approve a booking (alternative to confirm)
     */
    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be approved.');
        }

        $booking->update(['status' => 'approved']);
        
        // 🔔 NOTIFICATION: Notify customer about approval
        if ($booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Booking Approved',
                "Your booking #{$booking->booking_reference} for {$booking->car->name} has been approved.",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'car_name' => $booking->car->name
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Booking approved successfully');
    }


    /**
     * Mark booking as active (car picked up)
     */
    public function activate(Booking $booking)
    {
        if (!in_array($booking->status, ['confirmed', 'approved', 'pending'])) {
            return redirect()->back()->with('error', 'Invalid booking status for activation.');
        }

        $booking->update([
            'status' => 'active',
            'actual_pickup_date' => now()
        ]);

        // Update car status
        if ($booking->car) {
            $booking->car->update(['status' => 'rented']);
        }
        
        // 🔔 NOTIFICATION: Notify customer that car is picked up
        if ($booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Car Picked Up',
                "Your rental for {$booking->car->name} is now active. Enjoy your trip!",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Booking activated successfully');
    }

    /**
     * Complete a booking
     */
    public function complete(Booking $booking)
    {
        if ($booking->status !== 'active') {
            return redirect()->back()->with('error', 'Only active bookings can be completed.');
        }

        $booking->update([
            'status' => 'completed',
            'actual_return_date' => now()
        ]);

        // Update car status back to available
        if ($booking->car) {
            $booking->car->update([
                'status' => 'available',
                'is_available' => true
            ]);
        }
        
        // 🔔 NOTIFICATION: Thank customer for rental
        if ($booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Rental Completed',
                "Thank you for renting {$booking->car->name}! Your booking #{$booking->booking_reference} has been completed successfully.",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Booking completed successfully');
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $reason = $request->cancellation_reason;

        // Update car status if applicable
        if ($booking->car) {
            $booking->car->update([
                'status' => 'available',
                'is_available' => true
            ]);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now()
        ]);
        
        // 🔔 NOTIFICATION: Notify customer about cancellation
        if ($booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Booking Cancelled',
                "Your booking #{$booking->booking_reference} has been cancelled. Reason: {$reason}",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'reason' => $reason
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Booking cancelled successfully');
    }

    /**
     * Update booking status (general method)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,confirmed,active,completed,cancelled,rejected'
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update(['status' => $newStatus]);

        // Update car status based on booking status
        if ($booking->car) {
            $carStatus = match($newStatus) {
                'approved', 'confirmed' => 'booked',
                'active' => 'rented',
                'completed', 'cancelled', 'rejected' => 'available',
                default => $booking->car->status
            };
            
            $booking->car->update(['status' => $carStatus]);
        }

        // 🔔 NOTIFICATION: Notify customer about status change
        if ($booking->user && $oldStatus !== $newStatus) {
            $statusMessages = [
                'approved' => 'Your booking has been approved',
                'confirmed' => 'Your booking has been confirmed',
                'active' => 'Your rental is now active',
                'completed' => 'Your rental has been completed',
                'cancelled' => 'Your booking has been cancelled',
                'rejected' => 'Your booking has been rejected'
            ];

            if (isset($statusMessages[$newStatus])) {
                NotificationHelper::createBookingNotification(
                    $booking->user,
                    'Booking Status Updated',
                    "{$statusMessages[$newStatus]} - Booking #{$booking->booking_reference}",
                    route('bookings.show', $booking->booking_reference),
                    [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully'
        ]);
    }

    /**
     * Add a note to booking
     */
    public function addNote(Request $request, Booking $booking)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $notes = $booking->notes ?? [];
        $notes[] = [
            'staff_id' => auth()->id(),
            'staff_name' => auth()->user()->name,
            'note' => $request->note,
            'created_at' => now()->toDateTimeString()
        ];

        $booking->update(['notes' => $notes]);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully'
        ]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded'
        ]);

        $booking->update([
            'payment_status' => $request->payment_status
        ]);

        // 🔔 NOTIFICATION: Notify customer if payment status changes to paid
        if ($request->payment_status === 'paid' && $booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Payment Confirmed',
                "Your payment for booking #{$booking->booking_reference} has been verified and confirmed.",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }
}