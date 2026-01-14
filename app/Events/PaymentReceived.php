<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment->load(['booking.customer']);
    }

    public function broadcastOn()
    {
        return new Channel('staff-dashboard');
    }

    public function broadcastAs()
    {
        return 'payment.received';
    }

    public function broadcastWith()
    {
        return [
            'booking_id' => $this->payment->booking_id,
            'customer_name' => $this->payment->booking->customer->name,
            'amount' => $this->payment->amount,
            'payment_status' => $this->payment->payment_status,
            'created_at' => $this->payment->created_at->toISOString(),
        ];
    }
}