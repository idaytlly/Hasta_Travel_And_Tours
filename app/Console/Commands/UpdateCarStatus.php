<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;

class UpdateCarStatus extends Command
{
    protected $signature = 'cars:update-status';
    protected $description = 'Update car status based on active bookings';

    public function handle()
    {
        // Get all active bookings that have passed their return date
        $overdueBookings = Booking::where('status', 'active')
            ->where('return_date', '<', Carbon::now()->startOfDay())
            ->get();

        foreach ($overdueBookings as $booking) {
            // If return inspection is completed, mark as available
            if ($booking->returnInspection) {
                $car = Car::find($booking->car_id);
                if ($car && $car->status === 'rented') {
                    $car->update(['status' => 'available']);
                    $this->info("Updated car ID {$car->id} to available");
                }
            }
        }

        $this->info('Car status update completed!');
    }
}