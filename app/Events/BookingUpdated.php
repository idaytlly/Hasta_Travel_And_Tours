<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $action;

    public function __construct(Booking $booking, $action = 'updated')
    {
        $this->booking = $booking->load(['customer', 'vehicle']);
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('staff-dashboard');
    }

    public function broadcastAs()
    {
        return 'booking.updated';
    }

    public function broadcastWith()
{
    return [
        'booking_id' => $this->booking->booking_id ?? $this->booking->id,
        'customer_name' => $this->booking->customer->name ?? 'N/A',
        'vehicle' => $this->booking->vehicle->name ?? 
                    ($this->booking->vehicle->brand . ' ' . $this->booking->vehicle->model) ??
                    $this->booking->vehicle->plate_no,
        'booking_status' => $this->booking->booking_status ?? $this->booking->status, // Handle both
        'action' => $this->action,
        'updated_at' => $this->booking->updated_at->toISOString(),
    ];
}
}