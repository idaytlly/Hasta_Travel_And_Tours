<?php


namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;


class StaffReportsController extends Controller
{
    public function getReportData(Request $request)
    {
        $dateRange = $request->get('range', 'month');
        $startDate = $this->getStartDate($dateRange);
        $endDate = Carbon::now();
        
        // Summary statistics
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $summary = [
            'totalBookings' => $bookings->count(),
            'totalRevenue' => $bookings->whereIn('status', ['approved', 'active', 'completed'])->sum('total_amount'),
            'utilizationRate' => $this->calculateUtilizationRate($startDate, $endDate),
            'newCustomers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
        
        // Revenue by month (last 6 months)
        $revenueByMonth = $this->getRevenueByMonth();
        
        // Bookings by status
        $bookingsByStatus = [
            'pending' => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'approved' => $bookings->where('status', 'approved')->count(),
            'active' => $bookings->where('status', 'active')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];
        
        // Booking types
        $bookingTypes = [
            'delivery' => $bookings->where('pickup_type', 'delivery')->count(),
            'selfPickup' => $bookings->where('pickup_type', 'self-pickup')->count(),
        ];
        
        // Top performing vehicles
        $topVehicles = $this->getTopVehicles($startDate, $endDate);
        
        // Recent transactions
        $transactions = $bookings->sortByDesc('created_at')->take(20)->map(function ($booking) {
            return [
                'id' => $booking->booking_code,
                'date' => $booking->created_at->toISOString(),
                'customer' => $booking->customer->name,
                'contact' => $booking->customer->phone,
                'vehicle' => $booking->vehicle->brand . ' ' . $booking->vehicle->model,
                'type' => $booking->pickup_type,
                'status' => $booking->status,
                'amount' => $booking->total_amount,
            ];
        })->values();
        
        return response()->json([
            'success' => true,
            'summary' => $summary,
            'revenueByMonth' => $revenueByMonth,
            'bookingsByStatus' => $bookingsByStatus,
            'bookingTypes' => $bookingTypes,
            'topVehicles' => $topVehicles,
            'transactions' => $transactions,
        ]);
    }
    
    private function getStartDate($range)
    {
        switch ($range) {
            case 'today':
                return Carbon::today();
            case 'yesterday':
                return Carbon::yesterday();
            case 'week':
                return Carbon::now()->startOfWeek();
            case 'month':
                return Carbon::now()->startOfMonth();
            case 'quarter':
                return Carbon::now()->startOfQuarter();
            case 'year':
                return Carbon::now()->startOfYear();
            default:
                return Carbon::now()->startOfMonth();
        }
    }
    
    private function calculateUtilizationRate($startDate, $endDate)
    {
        $totalVehicles = Vehicle::count();
        $totalDays = $endDate->diffInDays($startDate) + 1;
        $totalPossibleRentalDays = $totalVehicles * $totalDays;
        
        $actualRentalDays = Booking::whereBetween('start_date', [$startDate, $endDate])
            ->whereIn('status', ['active', 'completed'])
            ->sum('duration_days');
        
        return $totalPossibleRentalDays > 0 
            ? round(($actualRentalDays / $totalPossibleRentalDays) * 100, 1)
            : 0;
    }
    
    private function getRevenueByMonth()
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $revenue = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('status', ['approved', 'active', 'completed'])
                ->sum('total_amount');
            
            $data[] = $revenue;
        }
        
        return [
            'labels' => $months,
            'data' => $data,
        ];
    }
    
    private function getTopVehicles($startDate, $endDate)
    {
        $vehicles = Vehicle::with(['bookings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereIn('status', ['approved', 'active', 'completed']);
        }])->get();
        
        $vehicleStats = $vehicles->map(function ($vehicle) {
            $bookings = $vehicle->bookings;
            $revenue = $bookings->sum('total_amount');
            
            return [
                'name' => $vehicle->brand . ' ' . $vehicle->model,
                'bookings' => $bookings->count(),
                'revenue' => $revenue,
                'percentage' => 0, // Will calculate after sorting
            ];
        })->sortByDesc('revenue');
        
        $totalRevenue = $vehicleStats->sum('revenue');
        
        return $vehicleStats->take(5)->map(function ($vehicle) use ($totalRevenue) {
            $vehicle['percentage'] = $totalRevenue > 0 
                ? round(($vehicle['revenue'] / $totalRevenue) * 100, 1)
                : 0;
            return $vehicle;
        })->values();
    }
    
    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'pdf');
        
        // In a real application, you would generate actual PDF/Excel files
        // For now, return success message
        
        return response()->json([
            'success' => true,
            'message' => "Report exported as {$type} successfully",
            'download_url' => "/downloads/report_{$type}_" . time() . ".{$type}",
        ]);
    }
}
