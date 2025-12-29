<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'brand' => 'Perodua',
                'model' => 'Axia',
                'year' => 2018,
                'transmission' => 'Automatic',
                'daily_rate' => 340.00,
                'image' => 'axia.jpg',
                'is_available' => true,
                'air_conditioner' => true,
                'passengers' => 5,
                'fuel_type' => 'Petrol',
                'license_plate' => 'WXY1234',
                'description' => 'Compact and fuel-efficient car perfect for city driving.',
            ],
            [
                'brand' => 'Perodua',
                'model' => 'Myvi',
                'year' => 2020,
                'transmission' => 'Automatic',
                'daily_rate' => 380.00,
                'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=300&fit=crop',
                'is_available' => true,
                'air_conditioner' => true,
                'passengers' => 5,
                'fuel_type' => 'Petrol',
                'license_plate' => 'WXY5678',
                'description' => 'Popular Malaysian car with great reliability.',
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Vios',
                'year' => 2019,
                'transmission' => 'Automatic',
                'daily_rate' => 450.00,
                'image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400&h=300&fit=crop',
                'is_available' => true,
                'air_conditioner' => true,
                'passengers' => 5,
                'fuel_type' => 'Petrol',
                'license_plate' => 'WXY9012',
                'description' => 'Comfortable sedan ideal for long trips.',
            ],
            [
                'brand' => 'Honda',
                'model' => 'City',
                'year' => 2021,
                'transmission' => 'Automatic',
                'daily_rate' => 480.00,
                'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=400&h=300&fit=crop',
                'is_available' => true,
                'air_conditioner' => true,
                'passengers' => 5,
                'fuel_type' => 'Petrol',
                'license_plate' => 'WXY3456',
                'description' => 'Modern sedan with advanced safety features.',
            ],
            [
                'brand' => 'Proton',
                'model' => 'X50',
                'year' => 2022,
                'transmission' => 'Automatic',
                'daily_rate' => 550.00,
                'image' => 'https://images.unsplash.com/photo-1619405399517-d7fce0f13302?w=400&h=300&fit=crop',
                'is_available' => true,
                'air_conditioner' => true,
                'passengers' => 5,
                'fuel_type' => 'Petrol',
                'license_plate' => 'WXY7890',
                'description' => 'Stylish SUV with spacious interior.',
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}