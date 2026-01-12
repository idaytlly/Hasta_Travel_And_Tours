<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Remove previous test payments
        DB::table('payment')->where('payment_id', 'like', 'PAY%')->delete();

        // Ensure any payments for our seeded bookings are removed to avoid duplicates
        DB::table('payment')->whereIn('booking_id', ['BK2026010001','BK2026010002','BK2026010004'])->delete();

        // Create payments for bookings
        $payments = [
            [
                'payment_id' => 'PAY' . time() . rand(100,999),
                'booking_id' => 'BK2026010002',
                'amount' => 480.00,
                'payment_status' => 'paid',
                'payment_date' => now()->subDay()->toDateString(),
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TXN' . rand(10000, 99999),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'payment_id' => 'PAY' . (time()+1) . rand(100,999),
                'booking_id' => 'BK2026010001',
                'amount' => 100.00,
                'payment_status' => 'pending',
                'payment_date' => now()->toDateString(),
                'payment_method' => 'bank_transfer',
                'transaction_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 'PAY' . (time()+2) . rand(100,999),
                'booking_id' => 'BK2026010004',
                'amount' => 80.00,
                'payment_status' => 'paid',
                'payment_date' => now()->subDays(2)->toDateString(),
                'payment_method' => 'cash',
                'transaction_id' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ];

        foreach ($payments as $p) {
            DB::table('payment')->insert($p);
        }

        $this->command->info('Seeded payments.');
    }
}
