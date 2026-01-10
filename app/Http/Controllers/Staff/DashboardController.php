<?php
// app/Http/Controllers/Staff/DashboardController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Staff;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        
        // Today's date
        $today = now();
        
        // Get statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::pending()->count(),
            'confirmed_bookings' => Booking::confirmed()->count(),
            'active_bookings' => Booking::active()->count(),
            'today_pickups' => Booking::whereDate('pickup_date', $today)->confirmed()->count(),
            'today_returns' => Booking::whereDate('return_date', $today)->active()->count(),
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::available()->count(),
            'vehicles_in_maintenance' => Vehicle::maintenance()->count(),
            'total_customers' => Customer::count(),
            'new_customers_today' => Customer::whereDate('created_at', $today)->count(),
            'total_revenue' => Payment::paid()->sum('amount'),
            'today_revenue' => Payment::paid()->whereDate('payment_date', $today)->sum('amount'),
            'pending_payments' => Payment::pending()->count(),
            'late_returns' => Booking::where('late_return_hours', '>', 0)->whereNull('late_charge_approved_by')->count(),
        ];

        // Recent bookings
        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Upcoming pickups (next 3 days)
        $upcomingPickups = Booking::with(['customer', 'vehicle'])
            ->whereIn('booking_status', ['confirmed'])
            ->whereBetween('pickup_date', [$today, $today->copy()->addDays(3)])
            ->orderBy('pickup_date')
            ->limit(5)
            ->get();

        // Overdue returns
        $overdueReturns = Booking::with(['customer', 'vehicle'])
            ->active()
            ->where('return_date', '<', $today)
            ->orderBy('return_date')
            ->limit(5)
            ->get();

        // Recent payments
        $recentPayments = Payment::with(['booking.customer'])
            ->where('payment_status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Vehicle utilization
        $vehicleUtilization = Vehicle::withCount(['bookings' => function($query) use ($today) {
                $query->where('booking_status', '!=', 'cancelled')
                      ->where('pickup_date', '>=', $today->copy()->subDays(30));
            }])
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue data for chart
        $monthlyRevenue = Payment::select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_status', 'paid')
            ->where('payment_date', '>=', $today->copy()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Booking status distribution
        $bookingStatusData = Booking::select('booking_status', DB::raw('COUNT(*) as count'))
            ->groupBy('booking_status')
            ->get()
            ->pluck('count', 'booking_status');

        return view('staff.dashboard.index', compact(
            'staff',
            'stats',
            'recentBookings',
            'upcomingPickups',
            'overdueReturns',
            'recentPayments',
            'vehicleUtilization',
            'monthlyRevenue',
            'bookingStatusData'
        ));
    }

    public function bookings()
    {
        return redirect()->route('staff.bookings.index');
    }

    public function vehicles()
    {
        $vehicles = Vehicle::with(['currentBooking'])->orderBy('name')->get();
        return view('staff.vehicles.index', compact('vehicles'));
    }

    public function customers()
    {
        $customers = Customer::withCount(['bookings', 'payments' => function($query) {
            $query->where('payment_status', 'paid');
        }])->orderBy('created_at', 'desc')->get();
        
        return view('staff.customers.index', compact('customers'));
    }

    public function reports()
    {
        $staff = Auth::guard('staff')->user();
        
        // Default date range: last 30 days
        $startDate = now()->subDays(30);
        $endDate = now();

        // Revenue by vehicle type
        $revenueByVehicleType = Payment::select(
                'vehicle.vehicle_type',
                DB::raw('SUM(payment.amount) as total')
            )
            ->join('booking', 'payment.booking_id', '=', 'booking.booking_id')
            ->join('vehicle', 'booking.plate_no', '=', 'vehicle.plate_no')
            ->where('payment.payment_status', 'paid')
            ->whereBetween('payment.payment_date', [$startDate, $endDate])
            ->groupBy('vehicle.vehicle_type')
            ->get();

        // Top customers by spending
        $topCustomers = Customer::select(
                'customers.*',
                DB::raw('SUM(payment.amount) as total_spent'),
                DB::raw('COUNT(DISTINCT booking.booking_id) as bookings_count')
            )
            ->leftJoin('booking', 'customers.id', '=', 'booking.customer_id')
            ->leftJoin('payment', 'booking.booking_id', '=', 'payment.booking_id')
            ->where('payment.payment_status', 'paid')
            ->whereBetween('payment.payment_date', [$startDate, $endDate])
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Daily booking count
        $dailyBookings = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Vehicle utilization
        $vehicleUtilization = Vehicle::with(['bookings' => function($query) use ($startDate, $endDate) {
                $query->where('booking_status', '!=', 'cancelled')
                      ->whereBetween('pickup_date', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($vehicle) use ($startDate, $endDate) {
                $totalDays = $startDate->diffInDays($endDate);
                $bookedDays = $vehicle->bookings->sum(function($booking) {
                    return $booking->pickup_date->diffInDays($booking->return_date) + 1;
                });
                
                $utilizationRate = ($totalDays > 0) ? ($bookedDays / $totalDays) * 100 : 0;
                
                return [
                    'vehicle' => $vehicle->name . ' (' . $vehicle->plate_no . ')',
                    'utilization' => round($utilizationRate, 2)
                ];
            })
            ->sortByDesc('utilization')
            ->take(10);

        // Late returns statistics
        $lateReturnsStats = Booking::select(
                DB::raw('SUM(late_return_charge) as total_charges'),
                DB::raw('AVG(late_return_hours) as avg_late_hours'),
                DB::raw('COUNT(*) as count')
            )
            ->where('late_return_hours', '>', 0)
            ->whereBetween('actual_return_date', [$startDate, $endDate])
            ->first();

        return view('staff.reports.index', compact(
            'revenueByVehicleType',
            'topCustomers',
            'dailyBookings',
            'vehicleUtilization',
            'lateReturnsStats'
        ));
    }
}