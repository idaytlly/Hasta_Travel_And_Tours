<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingApprovedNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in database only
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Booking Approved!',
            'message' => "Your booking #{$this->booking->booking_reference} has been approved by our team.",
            'type' => 'success',
            'link' => route('bookings.show', $this->booking->booking_reference),
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'car_name' => $this->booking->car->name ?? 'Unknown Vehicle',
        ];
    }
}