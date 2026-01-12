<?php
// app/Models/Commission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $table = 'commission';
    protected $primaryKey = 'commission_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'commission_id',
        'staff_id',
        'booking_id',
        'commission_amount',
        'commission_rate',
        'commission_status',
        'payment_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('commission_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('commission_status', 'pending');
    }

    // Attributes
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'paid' => 'success',
            'cancelled' => 'danger'
        ];
        
        $color = $badges[$this->commission_status] ?? 'secondary';
        
        return "<span class='badge bg-{$color}'>{$this->commission_status}</span>";
    }

    public function getFormattedAmountAttribute()
    {
        return 'RM ' . number_format($this->commission_amount, 2);
    }

    public function calculateCommission($bookingAmount)
    {
        // Commission calculation logic
        // Example: 5% commission for all bookings
        return $bookingAmount * 0.05;
    }
}