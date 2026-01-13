<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Staff;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing records
        $customers = Customer::all();
        $vehicles = Vehicle::all();
        $staff = Staff::all();
        
        // Get staff IDs that exist
        $staffIds = $staff->pluck('staff_id')->toArray();
        $vehiclePlates = $vehicles->pluck('plate_no')->toArray();
        $customerIds = $customers->pluck('customer_id')->toArray();
        
        if (empty($staffIds) || empty($vehiclePlates) || empty($customerIds)) {
            $this->command->warn('Missing required data for bookings. Skipping...');
            return;
        }
        
        $statuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        
        // Today's booking
        Booking::create([
            'booking_id' => 'BOOK001',
            'customer_id' => $customerIds[0] ?? null,
            'plate_no' => $vehiclePlates[0] ?? null,
            'pickup_date' => Carbon::today(),
            'pickup_time' => '09:00:00',
            'return_date' => Carbon::today()->addDays(2),
            'return_time' => '17:00:00',
            'total_price' => 7200.00,
            'booking_status' => 'confirmed',
            'special_requests' => 'Need child seat',
            'approved_by_staff' => $staffIds[3] ?? null, // STF004
            'approved_at' => Carbon::now()->subHours(2),
        ]);

        // Active booking (currently rented)
        Booking::create([
            'booking_id' => 'BOOK002',
            'customer_id' => $customerIds[1] ?? null,
            'plate_no' => $vehiclePlates[1] ?? null,
            'pickup_date' => Carbon::yesterday(),
            'pickup_time' => '14:00:00',
            'return_date' => Carbon::today()->addDays(1),
            'return_time' => '14:00:00',
            'total_price' => 3840.00,
            'booking_status' => 'active',
            'approved_by_staff' => $staffIds[4] ?? null, // STF005
            'approved_at' => Carbon::now()->subDays(1),
        ]);

        // Pending approval booking (no approved_by_staff)
        Booking::create([
            'booking_id' => 'BOOK003',
            'customer_id' => $customerIds[2] ?? null,
            'plate_no' => $vehiclePlates[3] ?? null,
            'pickup_date' => Carbon::today()->addDays(3),
            'pickup_time' => '10:00:00',
            'return_date' => Carbon::today()->addDays(5),
            'return_time' => '10:00:00',
            'total_price' => 3360.00,
            'booking_status' => 'pending',
            'special_requests' => 'Early morning pickup',
            'approved_by_staff' => null, // Pending, so no approval yet
        ]);

        // Completed booking with late return
        Booking::create([
            'booking_id' => 'BOOK004',
            'customer_id' => $customerIds[3] ?? null,
            'plate_no' => $vehiclePlates[4] ?? null,
            'pickup_date' => Carbon::today()->subDays(5),
            'pickup_time' => '11:00:00',
            'return_date' => Carbon::today()->subDays(2),
            'return_time' => '11:00:00',
            'total_price' => 21600.00,
            'booking_status' => 'completed',
            'actual_return_date' => Carbon::today()->subDays(1),
            'actual_return_time' => '15:00:00',
            'late_return_hours' => 28,
            'late_return_charge' => 8400.00,
            'late_return_notes' => 'Customer stuck in traffic',
            'late_charge_paid' => true,
            'late_charge_approved_by' => $staffIds[3] ?? null, // STF004
            'late_charge_approved_at' => Carbon::now()->subHours(12),
            'approved_by_staff' => $staffIds[3] ?? null, // STF004
            'approved_at' => Carbon::now()->subDays(6),
        ]);

        // More bookings
        for ($i = 5; $i <= 15; $i++) {
            $startDate = Carbon::now()->addDays(rand(-10, 20));
            $duration = rand(1, 7);
            $vehicleIndex = array_rand($vehiclePlates);
            $customerIndex = array_rand($customerIds);
            $status = $statuses[array_rand($statuses)];
            
            $booking = [
                'booking_id' => 'BOOK' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'customer_id' => $customerIds[$customerIndex],
                'plate_no' => $vehiclePlates[$vehicleIndex],
                'pickup_date' => $startDate,
                'pickup_time' => sprintf('%02d:00:00', rand(8, 18)),
                'return_date' => $startDate->copy()->addDays($duration),
                'return_time' => sprintf('%02d:00:00', rand(8, 18)),
                'total_price' => Vehicle::find($vehiclePlates[$vehicleIndex])->price_perHour * 24 * $duration,
                'booking_status' => $status,
                'special_requests' => rand(0, 1) ? 'Additional driver requested' : null,
            ];
            
            // Only add approval if status is not pending
            if ($status !== 'pending' && rand(0, 1)) {
                $booking['approved_by_staff'] = $staffIds[array_rand($staffIds)];
                $booking['approved_at'] = Carbon::now()->subDays(rand(1, 10));
            }
            
            Booking::create($booking);
        }
    }
}