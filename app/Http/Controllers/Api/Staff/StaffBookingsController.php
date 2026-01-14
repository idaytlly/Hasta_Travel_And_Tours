<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffBookingsController extends Controller
{
    public function getBookings(Request $request)
    {
        $bookings = Booking::with(['customer', 'vehicle'])
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->customer->name,
                    'customer_phone' => $booking->customer->phone,
                    'customer_email' => $booking->customer->email,
                    'customer_ic' => $booking->customer->ic_number,
                    'vehicle_name' => $booking->vehicle->brand . ' ' . $booking->vehicle->model,
                    'vehicle_plate' => $booking->vehicle->plate_number,
                    'start_date' => $booking->start_date,
                    'end_date' => $booking->end_date,
                    'duration_days' => $booking->duration_days,
                    'pickup_type' => $booking->pickup_type,
                    'status' => $booking->status,
                    'daily_rate' => $booking->daily_rate,
                    'delivery_fee' => $booking->delivery_fee ?? 0,
                    'total_amount' => $booking->total_amount,
                    'created_at' => $booking->created_at->toISOString(),
                ];
            });
        
        return response()->json([
            'success' => true,
            'bookings' => $bookings,
        ]);
    }
    
    public function getBookingDetails($id)
    {
        $booking = Booking::with(['customer', 'vehicle'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->customer->name,
                'customer_phone' => $booking->customer->phone,
                'customer_email' => $booking->customer->email,
                'customer_ic' => $booking->customer->ic_number,
                'vehicle_name' => $booking->vehicle->brand . ' ' . $booking->vehicle->model,
                'vehicle_plate' => $booking->vehicle->plate_number,
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
                'duration_days' => $booking->duration_days,
                'pickup_type' => $booking->pickup_type,
                'pickup_address' => $booking->pickup_address,
                'status' => $booking->status,
                'daily_rate' => $booking->daily_rate,
                'delivery_fee' => $booking->delivery_fee ?? 0,
                'total_amount' => $booking->total_amount,
                'created_at' => $booking->created_at->toISOString(),
            ],
        ]);
    }
    
    public function approveBooking($id)
{
    $booking = Booking::findOrFail($id);
    
    if ($booking->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Only pending bookings can be approved'
        ], 400);
    }
    
    $booking->status = 'confirmed';
    $booking->save();
    
    // 🔥 ADD BROADCAST
    try {
        broadcast(new \App\Events\BookingUpdated($booking, 'approved'))->toOthers();
    } catch (\Exception $e) {
        \Log::error('Approve booking broadcast failed: ' . $e->getMessage());
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Booking approved successfully',
        'booking' => $booking,
    ]);
}
    
    public function verifyBooking($id)
{
    $booking = Booking::findOrFail($id);
    
    if ($booking->status !== 'confirmed') {
        return response()->json([
            'success' => false,
            'message' => 'Only confirmed bookings can be verified'
        ], 400);
    }
    
    $booking->status = 'approved';
    $booking->payment_verified_at = now();
    $booking->save();
    
    // 🔥 ADD BROADCAST
    try {
        broadcast(new \App\Events\BookingUpdated($booking, 'verified'))->toOthers();
    } catch (\Exception $e) {
        \Log::error('Verify booking broadcast failed: ' . $e->getMessage());
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Payment verified successfully',
        'booking' => $booking,
    ]);
}
    
    public function cancelBooking($id)
{
    $booking = Booking::findOrFail($id);
    
    if (in_array($booking->status, ['completed', 'cancelled'])) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot cancel completed or already cancelled bookings'
        ], 400);
    }
    
    $booking->status = 'cancelled';
    $booking->cancelled_at = now();
    $booking->save();
    
    // Update vehicle status back to available
    $booking->vehicle->status = 'available';
    $booking->vehicle->save();
    
    // 🔥 ADD BROADCAST
    try {
        broadcast(new \App\Events\BookingUpdated($booking, 'cancelled'))->toOthers();
    } catch (\Exception $e) {
        \Log::error('Cancel booking broadcast failed: ' . $e->getMessage());
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Booking cancelled successfully',
        'booking' => $booking,
    ]);
}
}
