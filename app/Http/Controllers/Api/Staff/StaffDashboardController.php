<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
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
        
        $monthRevenue = Booking::whereDate('created_at', '>=', $thisMonth)
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->sum('total_amount');
            
        $lastMonthRevenue = Booking::whereBetween('created_at', [$lastMonth, $thisMonth])
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->sum('total_amount');
            
        $revenueChange = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
        
        // Get recent bookings
        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->customer->name,
                    'vehicle_name' => $booking->vehicle->brand . ' ' . $booking->vehicle->model,
                    'status' => $booking->status,
                    'total_amount' => $booking->total_amount,
                    'created_at' => $booking->created_at->toISOString(),
                ];
            });
        
        // Get today's schedule
        $schedule = collect([]);
        
        // Pickups today
        $pickupsToday = Booking::whereDate('start_date', $today)
            ->whereIn('status', ['approved', 'confirmed'])
            ->with(['customer', 'vehicle'])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => 'Vehicle Pickup - ' . $booking->customer->name,
                    'time' => Carbon::parse($booking->start_date)->format('h:i A'),
                    'type' => 'Pickup',
                    'completed' => false,
                ];
            });
        
        // Returns today
        $returnsToday = Booking::whereDate('end_date', $today)
            ->where('status', 'active')
            ->with(['customer', 'vehicle'])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => 'Vehicle Return - ' . $booking->customer->name,
                    'time' => Carbon::parse($booking->end_date)->format('h:i A'),
                    'type' => 'Return',
                    'completed' => false,
                ];
            });
        
        $schedule = $pickupsToday->merge($returnsToday)->sortBy('time')->values();
        
        // Get vehicle status
        $vehicleStatus = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('status', 'available')->count(),
            'rented' => Vehicle::where('status', 'rented')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => [
                'todayBookings' => $todayBookings,
                'todayBookingsChange' => $todayBookingsChange,
                'activeBookings' => Booking::where('status', 'active')->count(),
                'pendingApprovals' => Booking::where('status', 'pending')->count(),
                'monthRevenue' => $monthRevenue,
                'revenueChange' => $revenueChange,
            ],
            'recentBookings' => $recentBookings,
            'schedule' => $schedule,
            'vehicleStatus' => $vehicleStatus,
        ]);
    }
}