<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Remove non-essential test customers
        Customer::where('email', 'like', '%@example.com')->delete();

        $customers = [
            [
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Ahmad Bin Ali',
                'ic_number' => '900101-01-1234',
                'phone_no' => '1122334455',
                'license_no' => 'B1234567',
                'license_expiry' => Carbon::now()->addYears(2)->toDateString(),
            ],
            [
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Siti Nurhaliza',
                'ic_number' => '880512-14-5678',
                'phone_no' => '1199887766',
                'license_no' => 'C9876543',
                'license_expiry' => Carbon::now()->addYears(3)->toDateString(),
            ],
            [
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'name' => 'John Lim',
                'ic_number' => '750908-09-2222',
                'phone_no' => '1188776655',
                'license_no' => 'D1122334',
                'license_expiry' => Carbon::now()->addYears(1)->toDateString(),
            ],
            [
                'email' => 'ali@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Ali Bin Abu',
                'ic_number' => '930408-12-6789',
                'phone_no' => '1177665544',
                'license_no' => 'E4433221',
                'license_expiry' => Carbon::now()->addYears(4)->toDateString(),
            ],
            [
                'email' => 'sarah@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Sarah Tan',
                'ic_number' => '940501-05-3333',
                'phone_no' => '1166554433',
                'license_no' => 'F5566778',
                'license_expiry' => Carbon::now()->addYears(2)->toDateString(),
            ],
        ];

        // Remove any existing customers with these emails to avoid conflicts
        $emails = array_map(fn($c) => $c['email'], $customers);
        Customer::whereIn('email', $emails)->delete();

        foreach ($customers as $c) {
            $attrs = array_merge($c, ['created_at' => now(), 'updated_at' => now()]);

            // Force a unique customer_id for seed data to avoid collisions
            $attrs['customer_id'] = 'CUS' . strtoupper(Str::random(6));

            Customer::create($attrs);
        }

        $this->command->info('Seeded customers (replaced with deterministic ids).');
    }
}
