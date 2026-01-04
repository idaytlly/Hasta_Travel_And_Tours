<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'voucher_id' => Str::uuid(),        // generate unique ID
                'voucherCode' => 'SAVE10',
                'voucherAmount' => 10,
                'used_count' => 0,
                'expiryDate' => Carbon::now()->addMonths(6),
                'voucherStatus' => 'active',
            ],
            [
                'voucher_id' => Str::uuid(),
                'voucherCode' => 'SAVE20',
                'voucherAmount' => 20,
                'used_count' => 0,
                'expiryDate' => Carbon::now()->addMonths(3),
                'voucherStatus' => 'active',
            ],
            [
                'voucher_id' => Str::uuid(),
                'voucherCode' => 'FIRSTTIME',
                'voucherAmount' => 15,
                'used_count' => 0,
                'expiryDate' => Carbon::now()->addYear(),
                'voucherStatus' => 'active',
            ],
            [
                'voucher_id' => Str::uuid(),
                'voucherCode' => 'FLAT50',
                'voucherAmount' => 50,
                'used_count' => 0,
                'expiryDate' => Carbon::now()->addMonths(2),
                'voucherStatus' => 'active',
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
