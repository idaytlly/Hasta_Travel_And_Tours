<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $vehicles = [
            [
                'plate_no' => 'UTM 3365',
                'name' => 'Perodua Axia 1st Gen',
                'vehicle_type' => 'Car',
                'image' => 'car_images/axia.jpg',
                'price_perHour' => 35.00,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'VC 6522',
                'name' => 'Perodua Myvi 2nd Gen',
                'vehicle_type' => 'Car',
                'image' => 'car_images/myvi.jpg',
                'price_perHour' => 40.00,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'QRP 5205',
                'name' => 'Motor Honda Dash 125',
                'vehicle_type' => 'Bike',
                'image' => 'car_images/dash125.jpg',
                'price_perHour' => 10.00,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('vehicle')->insert($vehicles);
    }
}
