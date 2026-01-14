<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function getDashboardData()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Get statistics
        $todayBookings = Booking::whereDate('created_at', $today)->count();
        $yesterdayBookings = Booking::whereDate('created_at', $yesterday)->count();
        $todayBookingsChange = $yesterdayBookings > 0 
            ? round((($todayBookings - $yesterdayBookings) / $yesterdayBookings) * 100, 1)
            : 0;
        
        // Calculate month revenue from payments
        $monthRevenue = Payment::whereDate('payment_date', '>=', $thisMonth)
            ->where('payment_status', 'paid')
            ->sum('amount');
            
        $lastMonthRevenue = Payment::whereBetween('payment_date', [$lastMonth, $thisMonth])
            ->where('payment_status', 'paid')
            ->sum('amount');
            
        $revenueChange = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
        
        // Get recent bookings with proper relationships
        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->booking_id,
                    'booking_code' => $booking->booking_id,
                    'customer_name' => $booking->customer->name ?? 'Unknown',
                    'vehicle_name' => ($booking->vehicle->name ?? 'Unknown Vehicle') . ' (' . ($booking->plate_no ?? '') . ')',
                    'status' => $booking->booking_status,
                    'total_amount' => $booking->total_price,
                    'created_at' => $booking->created_at->toISOString(),
                    'pickup_date' => $booking->pickup_date,
                ];
            });
        
        // Get today's schedule
        $schedule = collect([]);
        
        // Pickups today
        $pickupsToday = Booking::whereDate('pickup_date', $today)
            ->whereIn('booking_status', ['confirmed'])
            ->with(['customer', 'vehicle'])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => 'pickup-' . $booking->booking_id,
                    'title' => 'Vehicle Pickup - ' . ($booking->customer->name ?? 'Unknown'),
                    'time' => Carbon::parse($booking->pickup_time)->format('h:i A'),
                    'type' => 'Pickup',
                    'completed' => false,
                    'booking_id' => $booking->booking_id,
                ];
            });
        
        // Returns today
        $returnsToday = Booking::whereDate('return_date', $today)
            ->where('booking_status', 'active')
            ->with(['customer', 'vehicle'])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => 'return-' . $booking->booking_id,
                    'title' => 'Vehicle Return - ' . ($booking->customer->name ?? 'Unknown'),
                    'time' => Carbon::parse($booking->return_time)->format('h:i A'),
                    'type' => 'Return',
                    'completed' => false,
                    'booking_id' => $booking->booking_id,
                ];
            });
        
        $schedule = $pickupsToday->merge($returnsToday)->sortBy('time')->values();
        
        // Get vehicle status
        $vehicleStatus = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('availability_status', 'available')->count(),
            'rented' => Vehicle::whereIn('availability_status', ['booked', 'in-use'])->count(),
            'maintenance' => Vehicle::where('availability_status', 'maintenance')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => [
                'todayBookings' => $todayBookings,
                'todayBookingsChange' => $todayBookingsChange,
                'activeBookings' => Booking::where('booking_status', 'active')->count(),
                'pendingApprovals' => Booking::where('booking_status', 'pending')->count(),
                'monthRevenue' => $monthRevenue,
                'revenueChange' => $revenueChange,
            ],
            'recentBookings' => $recentBookings,
            'schedule' => $schedule,
            'vehicleStatus' => $vehicleStatus,
        ]);
    }

    public function getStats()
    {
        $today = now();
        
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('booking_status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('booking_status', 'confirmed')->count(),
            'active_bookings' => Booking::where('booking_status', 'active')->count(),
            'pending_payments' => Payment::where('payment_status', 'pending')->count(),
            'today_revenue' => Payment::where('payment_status', 'paid')
                ->whereDate('payment_date', $today)
                ->sum('amount'),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    public function getRecentBookings()
    {
        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'booking_id' => $booking->booking_id,
                    'customer_name' => $booking->customer->name ?? 'Unknown',
                    'vehicle' => $booking->vehicle->name ?? 'Unknown Vehicle',
                    'plate_no' => $booking->plate_no,
                    'pickup_date' => $booking->pickup_date,
                    'total_price' => $booking->total_price,
                    'booking_status' => $booking->booking_status,
                    'created_at' => $booking->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'bookings' => $recentBookings
        ]);
    }

    public function getTodaySchedule()
    {
        $today = Carbon::today();
        
        $pickups = Booking::whereDate('pickup_date', $today)
            ->whereIn('booking_status', ['confirmed'])
            ->with(['customer', 'vehicle'])
            ->get();
            
        $returns = Booking::whereDate('return_date', $today)
            ->where('booking_status', 'active')
            ->with(['customer', 'vehicle'])
            ->get();

        return response()->json([
            'success' => true,
            'pickups' => $pickups,
            'returns' => $returns
        ]);
    }
}