<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'SAVE10',
                'description' => '10% discount on total booking',
                'type' => 'percentage',
                'value' => 10,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'usage_limit' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'SAVE20',
                'description' => '20% discount on total booking',
                'type' => 'percentage',
                'value' => 20,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'usage_limit' => 50,
                'is_active' => true,
            ],
            [
                'code' => 'FIRSTTIME',
                'description' => '15% discount for first-time customers',
                'type' => 'percentage',
                'value' => 15,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addYear(),
                'usage_limit' => null, // Unlimited
                'is_active' => true,
            ],
            [
                'code' => 'FLAT50',
                'description' => 'RM50 flat discount',
                'type' => 'fixed',
                'value' => 50,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'usage_limit' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
