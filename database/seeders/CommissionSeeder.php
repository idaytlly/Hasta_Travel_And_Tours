<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionSeeder extends Seeder
{
    public function run(): void
    {
        // Remove old test commissions
        DB::table('commission')->where('commission_id', 'like', 'COMM%')->delete();

        $commissions = [
            [
                'commission_id' => 'COMM' . time() . '001',
                'staff_id' => 'STR001',
                'comm_date' => now()->toDateString(),
                'comm_hour' => now()->toTimeString(),
                'reason' => 'Delivery for BK2026010002',
                'status' => 'completed',
                'total_commission' => 80,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'commission_id' => 'COMM' . (time()+1) . '002',
                'staff_id' => 'STR002',
                'comm_date' => now()->toDateString(),
                'comm_hour' => now()->toTimeString(),
                'reason' => 'Pickup for BK2026010003',
                'status' => 'completed',
                'total_commission' => 80,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
        ];

        foreach ($commissions as $c) {
            DB::table('commission')->insert($c);
        }

        $this->command->info('Seeded commission records for runners.');
    }
}
