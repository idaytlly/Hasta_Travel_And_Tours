<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'voucher_id' => 'VOUCH001',
                'voucherCode' => 'WELCOME20',
                'voucherAmount' => 20,
                'used_count' => 5,
                'expiryDate' => '2026-06-30',
                'voucherStatus' => 'active',
            ],
            [
                'voucher_id' => 'VOUCH002',
                'voucherCode' => 'SUMMER15',
                'voucherAmount' => 15,
                'used_count' => 3,
                'expiryDate' => '2026-08-31',
                'voucherStatus' => 'active',
            ],
            [
                'voucher_id' => 'VOUCH003',
                'voucherCode' => 'STUDENT10',
                'voucherAmount' => 10,
                'used_count' => 8,
                'expiryDate' => '2026-12-31',
                'voucherStatus' => 'active',
            ],
            [
                'voucher_id' => 'VOUCH004',
                'voucherCode' => 'EXPIRED50',
                'voucherAmount' => 50,
                'used_count' => 0,
                'expiryDate' => '2025-12-31',
                'voucherStatus' => 'expired',
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}