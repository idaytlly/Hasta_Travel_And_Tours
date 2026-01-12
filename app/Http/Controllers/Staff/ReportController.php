<?php
// app/Http/Controllers/Staff/ReportController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\Customer;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default date range: last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Revenue report
        $revenueData = Payment::select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Booking statistics
        $bookingStats = Booking::select(
                'booking_status',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('booking_status')
            ->get();
        
        // Vehicle utilization
        $vehicleUtilization = Vehicle::withCount(['bookings' => function($query) use ($startDate, $endDate) {
                $query->where('booking_status', '!=', 'cancelled')
                      ->whereBetween('pickup_date', [$startDate, $endDate]);
            }])
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();
        
        // Top customers
        $topCustomers = Customer::select(
                'customers.*',
                DB::raw('COUNT(booking.booking_id) as bookings_count'),
                DB::raw('SUM(payment.amount) as total_spent')
            )
            ->leftJoin('booking', 'customers.id', '=', 'booking.customer_id')
            ->leftJoin('payment', 'booking.booking_id', '=', 'payment.booking_id')
            ->where('payment.payment_status', 'paid')
            ->whereBetween('booking.created_at', [$startDate, $endDate])
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();
        
        // Summary statistics
        $summary = [
            'total_revenue' => Payment::where('payment_status', 'paid')
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount'),
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_bookings' => Booking::where('booking_status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'new_customers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'average_booking_value' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->avg('total_price'),
        ];
        
        return view('staff.reports.index', compact(
            'revenueData',
            'bookingStats',
            'vehicleUtilization',
            'topCustomers',
            'summary',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $bookings = Booking::with(['customer', 'vehicle', 'payments'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        $fileName = 'bookings_report_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        
        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Booking ID',
                'Customer Name',
                'Customer Email',
                'Vehicle',
                'Plate No',
                'Pickup Date',
                'Return Date',
                'Total Price',
                'Status',
                'Payment Status',
                'Payment Amount',
                'Created At'
            ]);
            
            // Data
            foreach ($bookings as $booking) {
                $payment = $booking->payments->first();
                
                fputcsv($file, [
                    $booking->booking_id,
                    $booking->customer->name,
                    $booking->customer->email,
                    $booking->vehicle->name,
                    $booking->vehicle->plate_no,
                    $booking->pickup_date->format('Y-m-d'),
                    $booking->return_date->format('Y-m-d'),
                    $booking->total_price,
                    $booking->booking_status,
                    $payment ? $payment->payment_status : 'N/A',
                    $payment ? $payment->amount : '0',
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}