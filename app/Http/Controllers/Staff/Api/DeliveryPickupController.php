<?php

namespace App\Http\Controllers\Staff\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Commission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveryPickupController extends Controller
{
    public function getTasksData(Request $request)
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        
        // Get today's bookings (both pickup and return)
        $todayBookings = Booking::where(function($query) use ($today) {
                $query->whereDate('pickup_date', $today)
                      ->orWhereDate('return_date', $today);
            })
            ->with(['customer', 'vehicle', 'staff'])
            ->get();
        
        // Transform into tasks
        $tasks = collect();
        
        foreach ($todayBookings as $booking) {
            // Pickup task
            if ($booking->pickup_date->format('Y-m-d') === $today->format('Y-m-d')) {
                $tasks->push([
                    'id' => $booking->booking_id . '_pickup',
                    'booking_code' => $booking->booking_id,
                    'customer_name' => $booking->customer->name ?? 'N/A',
                    'vehicle_model' => $booking->vehicle->name ?? 'N/A',
                    'vehicle_plate' => $booking->vehicle->plate_no ?? 'N/A',
                    'address' => 'Customer Location', // In real app, this would come from booking
                    'scheduled_time' => $booking->pickup_time,
                    'type' => 'pickup',
                    'status' => $this->getTaskStatus($booking, 'pickup'),
                    'commission' => rand(20, 100),
                    'assigned_to' => $booking->staff->name ?? 'Unassigned',
                ]);
            }
            
            // Return task
            if ($booking->return_date->format('Y-m-d') === $today->format('Y-m-d')) {
                $tasks->push([
                    'id' => $booking->booking_id . '_return',
                    'booking_code' => $booking->booking_id,
                    'customer_name' => $booking->customer->name ?? 'N/A',
                    'vehicle_model' => $booking->vehicle->name ?? 'N/A',
                    'vehicle_plate' => $booking->vehicle->plate_no ?? 'N/A',
                    'address' => 'HQ Location',
                    'scheduled_time' => $booking->return_time,
                    'type' => 'return',
                    'status' => $this->getTaskStatus($booking, 'return'),
                    'commission' => rand(20, 100),
                    'assigned_to' => $booking->staff->name ?? 'Unassigned',
                ]);
            }
        }
        
        // Apply filters
        if ($request->has('type') && $request->type) {
            $tasks = $tasks->where('type', $request->type);
        }
        
        if ($request->has('status') && $request->status) {
            $tasks = $tasks->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $tasks = $tasks->filter(function($task) use ($search) {
                return str_contains(strtolower($task['booking_code']), $search) ||
                       str_contains(strtolower($task['customer_name']), $search) ||
                       str_contains(strtolower($task['vehicle_model']), $search);
            });
        }
        
        // Month commission data
        $monthCommission = Commission::whereDate('comm_date', '>=', $monthStart)
            ->sum('total_commission');
        
        $monthTasks = $todayBookings->count() * 2; // Each booking has pickup and return
        $monthCompleted = Commission::whereDate('comm_date', '>=', $monthStart)
            ->where('status', 'completed')
            ->count();
        
        return response()->json([
            'tasks' => $tasks->values(),
            'stats' => [
                'total' => $tasks->count(),
                'pending' => $tasks->where('status', 'pending')->count(),
                'in_progress' => $tasks->where('status', 'in-progress')->count(),
                'completed' => $tasks->where('status', 'completed')->count(),
                'month_commission' => $monthCommission,
                'month_total_tasks' => $monthTasks,
                'month_completed_tasks' => $monthCompleted,
            ],
            'schedule' => $tasks->sortBy('scheduled_time')->take(5)->values(),
        ]);
    }
    
    private function getTaskStatus($booking, $type)
    {
        // Simplified logic for demo
        $statuses = ['pending', 'in-progress', 'completed'];
        return $statuses[array_rand($statuses)];
    }
}