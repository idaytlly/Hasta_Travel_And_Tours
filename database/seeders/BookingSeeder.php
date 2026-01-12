<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up test bookings created by previous runs
        DB::table('booking')->where('booking_id', 'like', 'BK2026%')->delete();

        // Get example customers and vehicles
        $customers = Customer::take(5)->get();
        $vehicles = Vehicle::take(6)->get();

        if ($customers->isEmpty() || $vehicles->isEmpty()) {
            $this->command->error('Please seed vehicles and customers first.');
            return;
        }

        $now = Carbon::now();

        $bookings = [
            // Pending booking (needs approval)
            [
                'booking_id' => 'BK2026010001',
                'pickup_date' => $now->copy()->addDays(2)->toDateString(),
                'pickup_time' => '10:00:00',
                'return_date' => $now->copy()->addDays(4)->toDateString(),
                'return_time' => '15:00:00',
                'total_price' => 320.00,
                'booking_status' => 'pending',
                'special_requests' => 'GPS required',
                'customer_id' => $customers[0]->customer_id,
                'plate_no' => $vehicles[0]->plate_no,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],

            // Confirmed booking (delivery scheduled) -> will show in delivery tasks
            [
                'booking_id' => 'BK2026010002',
                'pickup_date' => $now->copy()->addDay()->toDateString(),
                'pickup_time' => '09:00:00',
                'return_date' => $now->copy()->addDays(3)->toDateString(),
                'return_time' => '11:00:00',
                'total_price' => 480.00,
                'booking_status' => 'confirmed',
                'special_requests' => 'Deliver to customer address',
                'customer_id' => $customers[1]->customer_id,
                'plate_no' => $vehicles[1]->plate_no,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],

            // Active booking (vehicle out for rental) -> pickup task on return
            [
                'booking_id' => 'BK2026010003',
                'pickup_date' => $now->copy()->subDays(1)->toDateString(),
                'pickup_time' => '08:00:00',
                'return_date' => $now->copy()->addDays(1)->toDateString(),
                'return_time' => '10:00:00',
                'total_price' => 200.00,
                'booking_status' => 'active',
                'special_requests' => null,
                'customer_id' => $customers[2]->customer_id,
                'plate_no' => $vehicles[2]->plate_no,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],

            // Completed booking (returned)
            [
                'booking_id' => 'BK2026010004',
                'pickup_date' => $now->copy()->subDays(6)->toDateString(),
                'pickup_time' => '08:30:00',
                'return_date' => $now->copy()->subDays(3)->toDateString(),
                'return_time' => '09:30:00',
                'actual_return_date' => $now->copy()->subDays(3)->toDateString(),
                'actual_return_time' => '10:45:00',
                'late_return_hours' => 1,
                'late_return_charge' => 80.00,
                'total_price' => 600.00,
                'booking_status' => 'completed',
                'special_requests' => 'Customer paid late charge',
                'customer_id' => $customers[3]->customer_id,
                'plate_no' => $vehicles[3]->plate_no,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(3),
            ],

            // Cancelled booking
            [
                'booking_id' => 'BK2026010005',
                'pickup_date' => $now->copy()->addDays(5)->toDateString(),
                'pickup_time' => '14:00:00',
                'return_date' => $now->copy()->addDays(7)->toDateString(),
                'return_time' => '12:00:00',
                'total_price' => 400.00,
                'booking_status' => 'cancelled',
                'special_requests' => null,
                'customer_id' => $customers[4]->customer_id,
                'plate_no' => $vehicles[4]->plate_no,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ];

        foreach ($bookings as $b) {
            DB::table('booking')->insert($b);
        }

        $this->command->info('Seeded booking records (pending/confirmed/active/completed/cancelled).');
    }
}
