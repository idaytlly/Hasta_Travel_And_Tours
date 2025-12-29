<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_id',
        'user_id',
        'booking_reference',
        'customer_name',
        'customer_email',
        'customer_ic',
        'customer_phone',
        'pickup_location',
        'dropoff_location',
        'destination',
        'pickup_date',
        'pickup_time',
        'return_date',
        'return_time',
        'duration',
        'voucher',
        'base_price',
        'discount_amount',
        'total_price',
        'deposit_amount',
        'paid_amount',
        'status',
        'payment_status',
        'remarks',
        'cancellation_reason',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'base_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    // Relationships
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- FIXED ACCESSOR ---
    /**
     * Accessing address from the User table as requested
     */
    public function getFullAddressAttribute()
    {
        if ($this->user) {
            return "{$this->user->street}, {$this->user->postcode} {$this->user->city}, {$this->user->state}";
        }
        return 'No Address Provided';
    }

    // Static Methods
    public static function generateBookingReference(): string
    {
        return 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    // Business Logic
    public function calculateDuration(): int
    {
        $pickup = Carbon::parse($this->pickup_date);
        $return = Carbon::parse($this->return_date);
        return max(1, $pickup->diffInDays($return));
    }

    public function calculateDeposit(float $totalPrice): float
    {
        return round($totalPrice * 0.1, 2);
    }

    public function getRemainingBalance(): float
    {
        return $this->total_price - $this->paid_amount;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('pickup_date', '>', now())
                     ->whereIn('status', ['confirmed', 'pending']);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'in_progress']);
    }
}