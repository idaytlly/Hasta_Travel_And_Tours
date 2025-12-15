<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    public function run()
    {
        Car::create([
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2023,
            'daily_rate' => 50.00,
            'transmission' => 'Automatic',
            'image' => 'civic.jpg', // Ensure this image file exists in public/car_images/
            'is_available' => true,
        ]);

        Car::create([
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year' => 2024,
            'daily_rate' => 65.00,
            'transmission' => 'Automatic',
            'image' => 'camry.jpg',
            'is_available' => true,
        ]);
        // Add more cars here...
    }
}
