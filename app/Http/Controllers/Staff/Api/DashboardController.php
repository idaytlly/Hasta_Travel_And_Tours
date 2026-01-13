<?php

namespace App\Http\Controllers\Staff\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Commission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        
        // Dashboard stats
        $todayBookings = Booking::whereDate('pickup_date', $today)->count();
        $activeBookings = Booking::where('booking_status', 'active')->count();
        $pendingApprovals = Booking::where('booking_status', 'pending')->count();
        
        // This month's revenue (from completed bookings)
        $monthRevenue = Payment::where('payment_status', 'paid')
            ->whereDate('payment_date', '>=', $monthStart)
            ->sum('amount');
        
        // Recent bookings (last 5)
        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($booking) {
                return [
                    'booking_code' => $booking->booking_id,
                    'customer_name' => $booking->customer->name ?? 'N/A',
                    'vehicle_name' => $booking->vehicle->name ?? 'N/A',
                    'status' => $booking->booking_status,
                    'total_amount' => $booking->total_price,
                    'pickup_date' => $booking->pickup_date->format('d M Y'),
                ];
            });
        
        // Today's schedule (deliveries and pickups)
        $schedule = Booking::whereDate('pickup_date', $today)
            ->orWhereDate('return_date', $today)
            ->with(['customer', 'vehicle'])
            ->get()
            ->map(function($booking) use ($today) {
                $type = $booking->pickup_date->format('Y-m-d') === $today->format('Y-m-d') 
                    ? 'Delivery' 
                    : 'Return';
                
                return [
                    'id' => $booking->booking_id,
                    'title' => "{$type} - {$booking->customer->name}",
                    'time' => $type === 'Delivery' 
                        ? $booking->pickup_time 
                        : $booking->return_time,
                    'type' => $type,
                    'completed' => $booking->booking_status === 'completed',
                    'vehicle' => $booking->vehicle->name,
                ];
            });
        
        // Vehicle status summary
        $vehicleStatus = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('availability_status', 'available')->count(),
            'rented' => Vehicle::where('availability_status', 'booked')->count(),
            'maintenance' => Vehicle::where('availability_status', 'maintenance')->count(),
        ];
        
        return response()->json([
            'stats' => [
                'todayBookings' => $todayBookings,
                'activeBookings' => $activeBookings,
                'pendingApprovals' => $pendingApprovals,
                'monthRevenue' => $monthRevenue,
                'todayBookingsChange' => 12, // Mock data for demo
                'revenueChange' => 8, // Mock data for demo
            ],
            'recentBookings' => $recentBookings,
            'schedule' => $schedule,
            'vehicleStatus' => $vehicleStatus,
        ]);
    }
}