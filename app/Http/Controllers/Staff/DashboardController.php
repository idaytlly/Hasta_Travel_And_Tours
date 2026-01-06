<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('staff.dashboard');
    }

    public function getDashboardData(Request $request)
    {
        $currentMonth = now();
        
        $data = [
            'activeRentals' => Booking::where('status', 'active')->count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'vehiclesInMaintenance' => Car::where('status', 'maintenance')->count(),
            'currentRevenue' => Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->sum('total_price'),
            'currentMonthBookings' => Booking::whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->count(),
            'availableVehicles' => Car::where('status', 'available')->count(),
            'totalVehicles' => Car::count(),
            'activePercent' => $this->calculateBookingPercentage('active'),
            'confirmedPercent' => $this->calculateBookingPercentage('confirmed'),
            'pendingPercent' => $this->calculateBookingPercentage('pending'),
            'completedPercent' => $this->calculateBookingPercentage('completed'),
            'cancelledPercent' => $this->calculateBookingPercentage('cancelled'),
        ];

        return response()->json($data);
    }

    public function getChartData(Request $request)
    {
        $range = $request->get('range', '6M');
        $labels = [];
        $revenues = [];

        if ($range === '6M') {
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->format('M');
                $revenues[] = Booking::where('payment_status', 'paid')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_price');
            }
        } elseif ($range === '1Y') {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->format('M');
                $revenues[] = Booking::where('payment_status', 'paid')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_price');
            }
        } else {
            // Last 12 months by quarter
            for ($i = 3; $i >= 0; $i--) {
                $date = now()->subQuarters($i);
                $labels[] = 'Q' . ceil($date->month / 3) . ' ' . $date->format('Y');
                $revenues[] = Booking::where('payment_status', 'paid')
                    ->whereYear('created_at', $date->year)
                    ->whereBetween('created_at', [
                        $date->startOfQuarter(),
                        $date->endOfQuarter()
                    ])
                    ->sum('total_price');
            }
        }

        return response()->json([
            'labels' => $labels,
            'revenues' => $revenues
        ]);
    }

    private function calculateBookingPercentage($status)
    {
        $total = Booking::count();
        if ($total === 0) return 0;
        
        $count = Booking::where('status', $status)->count();
        return round(($count / $total) * 100);
    }
}