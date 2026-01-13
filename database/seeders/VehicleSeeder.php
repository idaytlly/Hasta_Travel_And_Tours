<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Staff;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // First, get existing staff IDs
        $staffIds = Staff::pluck('staff_id')->toArray();
        
        // If no staff exists yet, use null for staff_id
        $defaultStaffId = !empty($staffIds) ? $staffIds[0] : null;
        
        $vehicles = [
            [
                'plate_no' => 'ABC1234',
                'name' => 'Toyota Hilux',
                'color' => 'White',
                'year' => 2023,
                'vehicle_type' => 'car',
                'roadtax_expiry' => '2026-12-31',
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'seating_capacity' => 5,
                'price_perHour' => 150.00,
                'features' => json_encode(['GPS', 'Bluetooth', 'Reverse Camera', 'Air Conditioning']),
                'display_image' => 'hilux_display.jpg',
                'images' => json_encode(['hilux1.jpg', 'hilux2.jpg']),
                'description' => 'Powerful pickup truck',
                'distance_travelled' => 15000.5,
                'availability_status' => 'available',
                'maintenance_notes' => 'Last serviced on 2025-12-15',
                'staff_id' => $defaultStaffId, // Use the existing staff ID
            ],
            [
                'plate_no' => 'DEF5678',
                'name' => 'Honda Civic',
                'color' => 'Red',
                'year' => 2024,
                'vehicle_type' => 'car',
                'roadtax_expiry' => '2026-11-30',
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'seating_capacity' => 5,
                'price_perHour' => 80.00,
                'features' => json_encode(['GPS', 'Apple CarPlay', 'Android Auto', 'Sunroof']),
                'display_image' => 'civic_display.jpg',
                'images' => json_encode(['civic1.jpg', 'civic2.jpg']),
                'description' => 'Fuel-efficient sedan',
                'distance_travelled' => 5000.2,
                'availability_status' => 'booked',
                'maintenance_notes' => 'New vehicle',
                'staff_id' => $defaultStaffId,
            ],
            [
                'plate_no' => 'GHI9012',
                'name' => 'Toyota Innova',
                'color' => 'Silver',
                'year' => 2023,
                'vehicle_type' => 'car',
                'roadtax_expiry' => '2026-10-31',
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'seating_capacity' => 7,
                'price_perHour' => 120.00,
                'features' => json_encode(['DVD Player', 'Rear AC', 'Power Sliding Doors']),
                'display_image' => 'innova_display.jpg',
                'images' => json_encode(['innova1.jpg', 'innova2.jpg']),
                'description' => 'Family MPV',
                'distance_travelled' => 25000.0,
                'availability_status' => 'available',
                'maintenance_notes' => 'Regular maintenance',
                'staff_id' => $defaultStaffId,
            ],
            [
                'plate_no' => 'JKL3456',
                'name' => 'Perodua Myvi',
                'color' => 'Blue',
                'year' => 2024,
                'vehicle_type' => 'car',
                'roadtax_expiry' => '2026-09-30',
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'seating_capacity' => 5,
                'price_perHour' => 70.00,
                'features' => json_encode(['Keyless Entry', 'Push Start', 'Touchscreen']),
                'display_image' => 'myvi_display.jpg',
                'images' => json_encode(['myvi1.jpg', 'myvi2.jpg']),
                'description' => 'Popular hatchback',
                'distance_travelled' => 8000.5,
                'availability_status' => 'available',
                'maintenance_notes' => 'Regular service completed',
                'staff_id' => $defaultStaffId,
            ],
            [
                'plate_no' => 'MNO7890',
                'name' => 'Mercedes E-Class',
                'color' => 'Black',
                'year' => 2023,
                'vehicle_type' => 'car',
                'roadtax_expiry' => '2026-08-31',
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'seating_capacity' => 5,
                'price_perHour' => 300.00,
                'features' => json_encode(['Panoramic Sunroof', 'Massage Seats', 'Heads-up Display']),
                'display_image' => 'mercedes_display.jpg',
                'images' => json_encode(['mercedes1.jpg', 'mercedes2.jpg']),
                'description' => 'Luxury sedan',
                'distance_travelled' => 12000.0,
                'availability_status' => 'available',
                'maintenance_notes' => 'Premium service',
                'staff_id' => $defaultStaffId,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}