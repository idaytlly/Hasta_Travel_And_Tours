<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commission;
use Carbon\Carbon;

class CommissionSeeder extends Seeder
{
    public function run(): void
    {
        $commissions = [
            [
                'commission_id' => 'COMM001',
                'comm_date' => Carbon::today(),
                'comm_hour' => '4 hours',
                'reason' => 'Vehicle delivery to KLIA',
                'status' => 'pending',
                'total_commission' => 80.00,
                'staff_id' => 'STF002', // John Delivery
            ],
            [
                'commission_id' => 'COMM002',
                'comm_date' => Carbon::today(),
                'comm_hour' => '3 hours',
                'reason' => 'Pickup from customer location',
                'status' => 'completed',
                'total_commission' => 60.00,
                'staff_id' => 'STF003', // Sarah Pickup
            ],
            [
                'commission_id' => 'COMM003',
                'comm_date' => Carbon::yesterday(),
                'comm_hour' => '5 hours',
                'reason' => 'Multiple deliveries',
                'status' => 'completed',
                'total_commission' => 100.00,
                'staff_id' => 'STF002',
            ],
            [
                'commission_id' => 'COMM004',
                'comm_date' => Carbon::today()->subDays(2),
                'comm_hour' => '2 hours',
                'reason' => 'Emergency pickup',
                'status' => 'paid',
                'total_commission' => 40.00,
                'staff_id' => 'STF003',
            ],
        ];

        foreach ($commissions as $commission) {
            Commission::create($commission);
        }
    }
}