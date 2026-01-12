<?php
// app/Http/Controllers/Staff/PaymentController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.customer', 'booking.vehicle']);
        
        // Filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('payment_status', $request->status);
        }
        
        if ($request->has('method') && $request->method != 'all') {
            $query->where('payment_method', $request->method);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_id', 'LIKE', "%{$search}%")
                  ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('booking', function($q) use ($search) {
                      $q->where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhereHas('customer', function($q2) use ($search) {
                            $q2->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('vehicle', function($q2) use ($search) {
                            $q2->where('plate_no', 'LIKE', "%{$search}%")
                               ->orWhere('name', 'LIKE', "%{$search}%");
                        });
                  });
            });
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Payment::count(),
            'paid' => Payment::paid()->sum('amount'),
            'pending' => Payment::pending()->count(),
            'failed' => Payment::failed()->count(),
            'refunded' => Payment::refunded()->count(),
            'today' => Payment::whereDate('created_at', today())->sum('amount'),
        ];
        
        return view('staff.payments.index', compact('payments', 'stats'));
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.customer', 'booking.vehicle', 'verifiedBy'])
                         ->findOrFail($id);
        
        return view('staff.payments.show', compact('payment'));
    }

    public function verifyPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->payment_status !== 'pending') {
            return back()->with('error', 'Only pending payments can be verified.');
        }

        $request->validate([
            'verification_notes' => 'nullable|string|max:500'
        ]);

        $payment->update([
            'payment_status' => 'paid',
            'verified_by_staff' => Auth::guard('staff')->user()->staff_id,
            'verified_at' => now(),
            'payment_notes' => $request->verification_notes ?: 'Payment verified by staff'
        ]);

        // Update booking status if it's the first payment
        $booking = $payment->booking;
        if ($booking && $booking->booking_status === 'pending') {
            // Check if total paid equals or exceeds required amount
            $totalPaid = $booking->payments()->where('payment_status', 'paid')->sum('amount');
            $requiredAmount = $booking->total_price * 0.3; // 30% deposit required
            
            if ($totalPaid >= $requiredAmount) {
                $booking->update([
                    'booking_status' => 'confirmed',
                    'approved_by_staff' => Auth::guard('staff')->user()->staff_id,
                    'approved_at' => now()
                ]);
            }
        }

        return back()->with('success', 'Payment verified successfully!');
    }

    public function markAsFailed(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $request->validate([
            'failure_reason' => 'required|string|max:500'
        ]);

        $payment->update([
            'payment_status' => 'failed',
            'payment_notes' => $request->failure_reason
        ]);

        return back()->with('success', 'Payment marked as failed.');
    }

    public function processRefund(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->payment_status !== 'paid') {
            return back()->with('error', 'Only paid payments can be refunded.');
        }

        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'refund_reason' => 'required|string|max:500'
        ]);

        // Create refund payment record
        $refundPayment = Payment::create([
            'payment_id' => Payment::generatePaymentId('REF'),
            'booking_id' => $payment->booking_id,
            'amount' => $request->refund_amount,
            'payment_status' => 'refunded',
            'payment_method' => 'refund_' . $payment->payment_method,
            'payment_date' => now(),
            'payment_notes' => $request->refund_reason,
            'verified_by_staff' => Auth::guard('staff')->user()->staff_id,
            'verified_at' => now()
        ]);

        // Update original payment status if fully refunded
        if ($request->refund_amount >= $payment->amount) {
            $payment->update(['payment_status' => 'refunded']);
        }

        return back()->with('success', 'Refund processed successfully! Refund ID: ' . $refundPayment->payment_id);
    }

    public function recordManualPayment(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,other',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $payment = Payment::create([
            'payment_id' => Payment::generatePaymentId('MAN'),
            'booking_id' => $booking->booking_id,
            'amount' => $request->amount,
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'transaction_id' => $request->transaction_id,
            'payment_notes' => $request->notes,
            'verified_by_staff' => Auth::guard('staff')->user()->staff_id,
            'verified_at' => now()
        ]);

        return redirect()->route('staff.bookings.show', $booking->booking_id)
                       ->with('success', 'Manual payment recorded successfully!');
    }
}