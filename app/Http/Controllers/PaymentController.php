<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;

class PaymentController extends Controller
{
     public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $payment = $booking->payments()->first(); // get associated payment
        return view('payments.create', compact('booking', 'payment'));
    }

    public function store(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $payment = $booking->payments()->first();

        // Dummy logic: mark payment as paid
        $payment->update([
            'payment_status' => 'paid',
            'remaining_payment' => 0,
            'payment_date' => now(),
        ]);

        // Mark booking as confirmed
        $booking->update(['booking_status' => 'confirmed']);

        return redirect()->route('bookings.show', $booking->booking_id)
                         ->with('success', 'Payment completed successfully!');
    }
}
