<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function home()
    {
        return view('customer.home');
    }

    public function rewards()
    {
        // Fetch user's current stamp data
        // Replace with actual DB queries as needed
        $currentStamps = 4; // example: user has 4 stamps
        
        // Stamp history with hours booked
        $stampHistory = [
            (object)[
                'created_at' => now()->subDays(10),
                'order_id' => 1001,
                'hours' => 7,
                'stamps_earned' => 1,
                'status' => 'completed'
            ],
            (object)[
                'created_at' => now()->subDays(7),
                'order_id' => 1002,
                'hours' => 14,
                'stamps_earned' => 2,
                'status' => 'completed'
            ],
            (object)[
                'created_at' => now()->subDays(3),
                'order_id' => 1003,
                'hours' => 7,
                'stamps_earned' => 1,
                'status' => 'completed'
            ],
        ];

        return view('customer.reward', compact('currentStamps', 'stampHistory'));
    }
}
