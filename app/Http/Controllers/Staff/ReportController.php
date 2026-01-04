<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $reportType = $request->input('report_type', 'revenue');
        
        // Set default date range
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(6);
                $endDate = Carbon::now();
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29);
                $endDate = Carbon::now();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                if ($request->has(['start_date', 'end_date'])) {
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }
        
        // Get data based on report type
        $reportData = [];
        
        switch ($reportType) {
            case 'revenue':
                $reportData = $this->getRevenueReport($startDate, $endDate);
                break;
            case 'bookings':
                $reportData = $this->getBookingsReport($startDate, $endDate);
                break;
            case 'vehicles':
                $reportData = $this->getVehiclesReport($startDate, $endDate);
                break;
            case 'customers':
                $reportData = $this->getCustomersReport($startDate, $endDate);
                break;
        }
        
        return view('staff.reports.index', [
            'reportType' => $reportType,
            'dateRange' => $dateRange,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'reportData' => $reportData,
        ]);
    }
    
    private function getRevenueReport($startDate, $endDate)
    {
        // Total revenue
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');
        
        // Revenue by status
        $revenueByStatus = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, SUM(total_price) as revenue, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Revenue by month (for chart)
        $revenueByMonth = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $month = $current->format('Y-m');
            $monthName = $current->format('M Y');
            
            $revenue = Booking::where('payment_status', 'paid')
                ->whereYear('created_at', $current->year)
                ->whereMonth('created_at', $current->month)
                ->sum('total_price');
            
            $revenueByMonth[$monthName] = $revenue;
            
            $current->addMonth();
        }
        
        // Top revenue vehicles
        $topVehicles = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('car')
            ->selectRaw('car_id, SUM(total_price) as revenue, COUNT(*) as bookings')
            ->groupBy('car_id')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();
        
        return [
            'total_revenue' => $totalRevenue,
            'revenue_by_status' => $revenueByStatus,
            'revenue_by_month' => $revenueByMonth,
            'top_vehicles' => $topVehicles,
            'booking_count' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'paid_count' => Booking::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];
    }
    
    private function getBookingsReport($startDate, $endDate)
    {
        // Overall stats
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedBookings = Booking::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $cancelledBookings = Booking::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $activeBookings = Booking::where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Bookings by status
        $bookingsByStatus = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Bookings by month
        $bookingsByMonth = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $monthName = $current->format('M Y');
            
            $count = Booking::whereYear('created_at', $current->year)
                ->whereMonth('created_at', $current->month)
                ->count();
            
            $bookingsByMonth[$monthName] = $count;
            
            $current->addMonth();
        }
        
        // Most booked vehicles
        $popularVehicles = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with('car')
            ->selectRaw('car_id, COUNT(*) as bookings')
            ->groupBy('car_id')
            ->orderByDesc('bookings')
            ->take(10)
            ->get();
        
        return [
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'active_bookings' => $activeBookings,
            'bookings_by_status' => $bookingsByStatus,
            'bookings_by_month' => $bookingsByMonth,
            'popular_vehicles' => $popularVehicles,
        ];
    }
    
    private function getVehiclesReport($startDate, $endDate)
    {
        // Vehicle statistics
        $totalVehicles = Car::count();
        $availableVehicles = Car::where('is_available', true)->count();
        $rentedVehicles = Car::where('is_available', false)->count();
        
        // Vehicles by category
        $vehiclesByCategory = Car::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');
        
        // Most profitable vehicles
        $profitableVehicles = Booking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('car')
            ->selectRaw('car_id, SUM(total_price) as revenue, COUNT(*) as bookings')
            ->groupBy('car_id')
            ->orderByDesc('revenue')
            ->get();
        
        // Utilization rate (days rented vs total days in period)
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        
        $vehiclesWithUtilization = Car::with(['bookings' => function($query) use ($startDate, $endDate) {
            $query->where('status', 'completed')
                  ->whereBetween('created_at', [$startDate, $endDate]);
        }])->get()->map(function($car) use ($daysInPeriod) {
            $daysRented = $car->bookings->sum(function($booking) {
                $start = Carbon::parse($booking->start_date);
                $end = Carbon::parse($booking->end_date);
                return $start->diffInDays($end) + 1;
            });
            
            $utilizationRate = ($daysInPeriod > 0) ? ($daysRented / $daysInPeriod) * 100 : 0;
            
            return [
                'vehicle' => $car,
                'days_rented' => $daysRented,
                'utilization_rate' => round($utilizationRate, 2),
            ];
        })->sortByDesc('utilization_rate')->take(10);
        
        return [
            'total_vehicles' => $totalVehicles,
            'available_vehicles' => $availableVehicles,
            'rented_vehicles' => $rentedVehicles,
            'vehicles_by_category' => $vehiclesByCategory,
            'profitable_vehicles' => $profitableVehicles,
            'utilization_vehicles' => $vehiclesWithUtilization,
        ];
    }
    
    private function getCustomersReport($startDate, $endDate)
    {
        // Customer statistics
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Top customers by bookings
        $topCustomers = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->selectRaw('user_id, COUNT(*) as bookings, SUM(total_price) as total_spent')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();
        
        // Customer growth by month
        $customerGrowth = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $monthName = $current->format('M Y');
            
            $count = User::where('role', 'customer')
                ->whereYear('created_at', $current->year)
                ->whereMonth('created_at', $current->month)
                ->count();
            
            $customerGrowth[$monthName] = $count;
            
            $current->addMonth();
        }
        
        return [
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'top_customers' => $topCustomers,
            'customer_growth' => $customerGrowth,
        ];
    }
    
    public function export(Request $request)
    {
        $type = $request->input('type', 'pdf');
        $reportType = $request->input('report_type', 'revenue');
        $dateRange = $request->input('date_range', 'this_month');
        
        // Generate report data (similar to index method)
        // Then export based on type
        
        // For now, just return a message
        return back()->with('success', 'Export feature coming soon!');
    }
}