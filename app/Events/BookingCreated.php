<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['customer', 'vehicle']);
    }

    public function broadcastOn()
    {
        return new Channel('staff-dashboard');
    }

    public function broadcastAs()
    {
        return 'booking.created';
    }

    public function broadcastWith()
    {
        return [
            'booking_id' => $this->booking->booking_id,
            'customer_name' => $this->booking->customer->name,
            'vehicle' => $this->booking->vehicle->name,
            'plate_no' => $this->booking->plate_no,
            'pickup_date' => $this->booking->pickup_date,
            'total_price' => $this->booking->total_price,
            'booking_status' => $this->booking->booking_status,
            'created_at' => $this->booking->created_at->toISOString(),
        ];
    }
}