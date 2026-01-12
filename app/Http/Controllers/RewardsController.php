<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

class RewardsController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get total stamps (count bookings with stamps_earned = 1)
        $currentStamps = $customer->bookings()
            ->where('stamp_awarded', true)
            ->sum('stamps_earned');
        
        // Get stamp history (bookings that earned stamps)
        $stampHistory = $customer->bookings()
            ->where('stamp_awarded', true)
            ->with('vehicle')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return (object) [
                    'created_at' => $booking->created_at,
                    'order_id' => $booking->booking_id,
                    'hours' => $booking->calculateTotalHours(),
                    'stamps_earned' => $booking->stamps_earned,
                    'status' => $booking->booking_status,
                ];
            });
        
        // Get available vouchers
        $availableVouchers = $customer->vouchers()
            ->where('voucherStatus', 'active')
            ->where('is_used', false)
            ->where('expiryDate', '>=', now())
            ->orderBy('voucherAmount', 'desc')
            ->get();
        
        return view('customer.reward', compact('currentStamps', 'stampHistory', 'availableVouchers'));
    }
}