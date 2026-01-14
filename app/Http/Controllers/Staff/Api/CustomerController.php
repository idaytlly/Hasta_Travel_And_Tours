<?php

namespace App\Http\Controllers\Staff\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getCustomersData(Request $request)
    {
        $query = Customer::query();
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_no', 'like', "%{$search}%")
                  ->orWhere('ic_number', 'like', "%{$search}%");
            });
        }
        
        // Status filter (mock implementation)
        if ($request->has('status') && $request->status) {
            // In real app, you'd have a status column
            // For demo, we'll simulate based on booking count
            $query->whereHas('bookings', function($q) use ($request) {
                if ($request->status === 'vip') {
                    $q->where('total_price', '>', 5000);
                }
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'bookings':
                $query->withCount('bookings')->orderBy('bookings_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $customers = $query->get()->map(function($customer) {
            $totalBookings = $customer->bookings()->count();
            $totalSpent = $customer->bookings()->sum('total_price');
            
            // Determine status based on activity
            $status = 'active';
            if ($totalBookings > 10) {
                $status = 'vip';
            } elseif ($totalBookings === 0) {
                $status = 'inactive';
            }
            
            return [
                'id' => $customer->customer_id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone_no,
                'ic_number' => $customer->ic_number,
                'total_bookings' => $totalBookings,
                'total_spent' => $totalSpent,
                'status' => $status,
                'joined_date' => $customer->created_at->format('Y-m-d H:i:s'),
                'last_booking' => $customer->bookings()->latest()->first()->created_at->format('Y-m-d H:i:s') ?? $customer->created_at->format('Y-m-d H:i:s'),
            ];
        });
        
        return response()->json($customers);
    }
}