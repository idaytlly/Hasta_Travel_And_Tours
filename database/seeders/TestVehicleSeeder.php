<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestVehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal vehicle entries for testing bookings
        $vehicles = [
            ['plate_no' => 'TEST001', 'name' => 'Perodua Axia', 'price_perHour' => 35, 'availability_status' => 'available', 'year' => 2018, 'seating_capacity' => 5],
            ['plate_no' => 'TEST002', 'name' => 'Perodua Myvi', 'price_perHour' => 40, 'availability_status' => 'available', 'year' => 2016, 'seating_capacity' => 5],
            ['plate_no' => 'TEST003', 'name' => 'Honda Jazz', 'price_perHour' => 50, 'availability_status' => 'available', 'year' => 2019, 'seating_capacity' => 5],
            ['plate_no' => 'TEST004', 'name' => 'Toyota Vios', 'price_perHour' => 55, 'availability_status' => 'available', 'year' => 2020, 'seating_capacity' => 5],
            ['plate_no' => 'TEST005', 'name' => 'Ford Ranger', 'price_perHour' => 80, 'availability_status' => 'available', 'year' => 2021, 'seating_capacity' => 5],
            ['plate_no' => 'TEST006', 'name' => 'Toyota Hilux', 'price_perHour' => 85, 'availability_status' => 'available', 'year' => 2022, 'seating_capacity' => 5],
        ];

        foreach ($vehicles as $v) {
            // Avoid duplicates
            DB::table('vehicle')->updateOrInsert(
                ['plate_no' => $v['plate_no']],
                array_merge($v, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Inserted test vehicles (TEST001..TEST006).');
    }
}
