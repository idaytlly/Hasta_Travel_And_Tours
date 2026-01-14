<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call seeders in correct order to respect foreign key constraints
        $this->call([
            StaffSeeder::class,      // First create staff
            CustomerSeeder::class,   // Then customers
            VehicleSeeder::class,    // Then vehicles (needs staff_id)
            VoucherSeeder::class,    // Then vouchers
            BookingSeeder::class,    // Then bookings (needs customers and vehicles)
            PaymentSeeder::class,    // Then payments (needs bookings)
            CommissionSeeder::class, // Then commissions (needs staff)
        ]);
    }
}