<?php


namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;


class StaffDeliveryController extends Controller
{
    /**
     * Get delivery/pickup tasks for runners
     */
    public function getTasks(Request $request)
    {
        $staffId = $request->user()->staff_id ?? 'STAFF001';
        
        // Get bookings that need delivery/pickup
        $tasks = DB::table('booking')
            ->join('customers', 'booking.customer_id', '=', 'customers.customer_id')
            ->join('vehicle', 'booking.plate_no', '=', 'vehicle.plate_no')
            ->select(
                'booking.booking_id',
                'booking.pickup_date',
                'booking.pickup_time',
                'booking.return_date',
                'booking.return_time',
                'booking.booking_status',
                'customers.name as customer_name',
                'customers.phone_no',
                'vehicle.name as vehicle_name',
                'vehicle.plate_no'
            )
            ->whereIn('booking.booking_status', ['confirmed', 'active'])
            ->orderBy('booking.pickup_date')
            ->orderBy('booking.pickup_time')
            ->get();
            
        // Transform to delivery tasks
        $deliveryTasks = [];
        
        foreach ($tasks as $task) {
            // Pickup task
            if ($task->booking_status == 'confirmed') {
                $deliveryTasks[] = [
                    'id' => 'DT' . $task->booking_id . '-P',
                    'bookingId' => $task->booking_id,
                    'type' => 'delivery',
                    'customer' => $task->customer_name,
                    'phone' => $task->phone_no ? '+60' . $task->phone_no : 'N/A',
                    'vehicle' => $task->vehicle_name,
                    'plateNumber' => $task->plate_no,
                    'address' => 'Customer Address', // Add address field to customers table
                    'scheduledTime' => $task->pickup_date . ' ' . $task->pickup_time,
                    'status' => 'pending',
                    'commission' => 80, // From commission table or fixed rate
                    'notes' => 'Please call customer 30 mins before delivery'
                ];
            }
            
            // Return/Pickup task
            if ($task->booking_status == 'active') {
                $deliveryTasks[] = [
                    'id' => 'DT' . $task->booking_id . '-R',
                    'bookingId' => $task->booking_id,
                    'type' => 'pickup',
                    'customer' => $task->customer_name,
                    'phone' => $task->phone_no ? '+60' . $task->phone_no : 'N/A',
                    'vehicle' => $task->vehicle_name,
                    'plateNumber' => $task->plate_no,
                    'address' => 'Customer Address',
                    'scheduledTime' => $task->return_date . ' ' . $task->return_time,
                    'status' => 'pending',
                    'commission' => 80,
                    'notes' => 'Return from completed rental'
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'tasks' => $deliveryTasks
        ]);
    }
    
    /**
     * Get task details
     */
    public function getTaskDetails($id)
    {
        // Extract booking ID from task ID (format: DTBOOKING_ID-P or -R)
        $parts = explode('-', $id);
        $bookingId = str_replace('DT', '', $parts[0]);
        
        $booking = DB::table('booking')
            ->join('customers', 'booking.customer_id', '=', 'customers.customer_id')
            ->join('vehicle', 'booking.plate_no', '=', 'vehicle.plate_no')
            ->select(
                'booking.*',
                'customers.name as customer_name',
                'customers.phone_no',
                'vehicle.name as vehicle_name',
                'vehicle.plate_no'
            )
            ->where('booking.booking_id', $bookingId)
            ->first();
            
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
        
        $isPickup = isset($parts[1]) && $parts[1] == 'R';
        
        return response()->json([
            'success' => true,
            'task' => [
                'id' => $id,
                'bookingId' => $booking->booking_id,
                'type' => $isPickup ? 'pickup' : 'delivery',
                'customer' => $booking->customer_name,
                'phone' => $booking->phone_no ? '+60' . $booking->phone_no : 'N/A',
                'vehicle' => $booking->vehicle_name,
                'plateNumber' => $booking->plate_no,
                'scheduledTime' => $isPickup ? 
                    $booking->return_date . ' ' . $booking->return_time :
                    $booking->pickup_date . ' ' . $booking->pickup_time,
                'status' => $booking->booking_status,
                'commission' => 80,
                'notes' => $booking->special_requests ?? ''
            ]
        ]);
    }
    
    /**
     * Start a delivery task
     */
    public function startTask($id, Request $request)
    {
        $staffId = $request->user()->staff_id ?? 'STAFF001';
        
        // Extract booking ID
        $parts = explode('-', $id);
        $bookingId = str_replace('DT', '', $parts[0]);
        
        // Update booking status to active if it's a delivery
        if (!isset($parts[1]) || $parts[1] == 'P') {
            DB::table('booking')
                ->where('booking_id', $bookingId)
                ->update([
                    'booking_status' => 'active',
                    'updated_at' => Carbon::now()
                ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Task started successfully'
        ]);
    }
    
    /**
     * Complete a delivery task
     */
    public function completeTask($id, Request $request)
    {
        $staffId = $request->user()->staff_id ?? 'STAFF001';
        
        // Extract booking ID
        $parts = explode('-', $id);
        $bookingId = str_replace('DT', '', $parts[0]);
        $isPickup = isset($parts[1]) && $parts[1] == 'R';
        
        // Update booking status
        if ($isPickup) {
            DB::table('booking')
                ->where('booking_id', $bookingId)
                ->update([
                    'booking_status' => 'completed',
                    'actual_return_date' => Carbon::now()->toDateString(),
                    'actual_return_time' => Carbon::now()->toTimeString(),
                    'updated_at' => Carbon::now()
                ]);
                
            // Update vehicle availability
            $booking = DB::table('booking')->where('booking_id', $bookingId)->first();
            if ($booking) {
                DB::table('vehicle')
                    ->where('plate_no', $booking->plate_no)
                    ->update(['availability_status' => 'available']);
            }
        }
        
        // Record commission
        $commission = 80; // Fixed or calculate based on distance/time
        $this->recordCommission($staffId, $commission, 'Delivery for booking ' . $bookingId);
        
        return response()->json([
            'success' => true,
            'message' => 'Task completed successfully',
            'commission' => $commission
        ]);
    }
    
    /**
     * Get runner's commission
     */
    public function getCommission(Request $request)
    {
        $staffId = $request->user()->staff_id ?? 'STAFF001';
        $thisMonth = Carbon::now()->startOfMonth();
        
        $totalCommission = DB::table('commission')
            ->where('staff_id', $staffId)
            ->where('comm_date', '>=', $thisMonth)
            ->sum('total_commission');
            
        $completedDeliveries = DB::table('commission')
            ->where('staff_id', $staffId)
            ->where('comm_date', '>=', $thisMonth)
            ->count();
            
        return response()->json([
            'success' => true,
            'totalCommission' => round(floatval($totalCommission), 2),
            'completedDeliveries' => $completedDeliveries
        ]);
    }
    
    /**
     * Record commission for staff
     */
    private function recordCommission($staffId, $amount, $reason)
    {
        DB::table('commission')->insert([
            'commission_id' => 'COMM' . time() . rand(100, 999),
            'staff_id' => $staffId,
            'comm_date' => Carbon::now()->toDateString(),
            'comm_hour' => Carbon::now()->toTimeString(),
            'reason' => $reason,
            'status' => 'completed',
            'total_commission' => $amount,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}