<?php
// app/Http/Controllers/Staff/ReportController.php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function getReportData(Request $request)
    {
        // Debug: Check if method is called
        \Log::info('ReportController called', $request->all());
        
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Calculate date range
        $dates = $this->calculateDateRange($period, $startDate, $endDate);
        
        // Get data
        $summary = $this->getSummaryData($dates);
        $charts = $this->getChartData($dates);
        $tables = $this->getTableData($dates);
        
        return response()->json([
            'summary' => $summary,
            'charts' => $charts,
            'tables' => $tables
        ]);
    }
    
    private function calculateDateRange($period, $customStart = null, $customEnd = null)
    {
        if ($customStart && $customEnd) {
            return [
                'start' => Carbon::parse($customStart)->startOfDay(),
                'end' => Carbon::parse($customEnd)->endOfDay()
            ];
        }
        
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'yesterday':
                $yesterday = $now->copy()->subDay();
                return [
                    'start' => $yesterday->startOfDay(),
                    'end' => $yesterday->endOfDay()
                ];
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'quarter':
                return [
                    'start' => $now->copy()->startOfQuarter(),
                    'end' => $now->copy()->endOfQuarter()
                ];
            case 'year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }
    
    private function getSummaryData($dates)
    {
        // Total Bookings
        $totalBookings = DB::table('bookings')
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->count();
        
        // Total Revenue
        $totalRevenue = DB::table('bookings')
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->sum('total_amount');
        
        // Active Rentals
        $activeRentals = DB::table('bookings')
            ->where('status', 'active')
            ->whereDate('pickup_date', '<=', now())
            ->whereDate('return_date', '>=', now())
            ->count();
        
        // New Customers
        $newCustomers = DB::table('customers')
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->count();
        
        // Calculate previous period for comparison
        $prevDates = [
            'start' => $dates['start']->copy()->subMonth(),
            'end' => $dates['end']->copy()->subMonth()
        ];
        
        $prevBookings = DB::table('bookings')
            ->whereBetween('created_at', [$prevDates['start'], $prevDates['end']])
            ->count();
            
        $prevRevenue = DB::table('bookings')
            ->whereBetween('created_at', [$prevDates['start'], $prevDates['end']])
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->sum('total_amount');
        
        $bookingChange = $prevBookings > 0 
            ? round((($totalBookings - $prevBookings) / $prevBookings) * 100, 1)
            : 0;
            
        $revenueChange = $prevRevenue > 0 
            ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
            : 0;
        
        return [
            'total_bookings' => $totalBookings,
            'total_revenue' => (float) $totalRevenue,
            'active_rentals' => $activeRentals,
            'new_customers' => $newCustomers,
            'booking_change' => $bookingChange,
            'revenue_change' => $revenueChange
        ];
    }
    
    private function getTableData($dates)
    {
        // Recent Bookings (last 5)
        $recentBookings = DB::table('bookings as b')
            ->join('customers as c', 'b.customer_id', '=', 'c.id')
            ->select(
                'b.id as booking_id',
                'c.name as customer_name',
                'b.status',
                'b.total_amount as amount',
                'b.created_at'
            )
            ->whereBetween('b.created_at', [$dates['start'], $dates['end']])
            ->orderBy('b.created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Popular Vehicles (top 5 by bookings)
        $popularVehicles = DB::table('booking_vehicle as bv')
            ->join('vehicles as v', 'bv.vehicle_id', '=', 'v.id')
            ->join('bookings as b', 'bv.booking_id', '=', 'b.id')
            ->select(
                'v.name',
                'v.category',
                DB::raw('COUNT(bv.booking_id) as bookings'),
                DB::raw('SUM(b.total_amount) as revenue')
            )
            ->whereBetween('b.created_at', [$dates['start'], $dates['end']])
            ->whereIn('b.status', ['approved', 'active', 'completed'])
            ->groupBy('v.id', 'v.name', 'v.category')
            ->orderBy('bookings', 'desc')
            ->limit(5)
            ->get();
        
        return [
            'recent_bookings' => $recentBookings,
            'popular_vehicles' => $popularVehicles
        ];
    }
    
    private function getChartData($dates)
    {
        // Revenue by month (last 6 months)
        $revenueData = [];
        $revenueLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $revenue = DB::table('bookings')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereIn('status', ['approved', 'active', 'completed'])
                ->sum('total_amount');
            
            $revenueData[] = (float) $revenue;
            $revenueLabels[] = $date->format('M Y');
        }
        
        // Vehicle utilization (top 5)
        $vehicles = DB::table('vehicles')
            ->select('name')
            ->limit(5)
            ->get();
            
        $utilizationData = [];
        $utilizationLabels = [];
        
        foreach ($vehicles as $vehicle) {
            // Simplified calculation - in real app, calculate actual utilization
            $utilizationData[] = rand(60, 95);
            $utilizationLabels[] = $vehicle->name;
        }
        
        return [
            'revenue' => [
                'labels' => $revenueLabels,
                'data' => $revenueData
            ],
            'utilization' => [
                'labels' => $utilizationLabels,
                'data' => $utilizationData
            ]
        ];
    }
}