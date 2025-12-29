<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_id', 'user_id', 'booking_reference', 'customer_name', 
        'customer_email', 'customer_ic', 'customer_phone', 'pickup_location', 
        'dropoff_location', 'destination', 'pickup_date', 'pickup_time', 
        'return_date', 'return_time', 'duration', 'voucher', 'base_price', 
        'discount_amount', 'total_price', 'deposit_amount', 'paid_amount', 
        'status', 'payment_status', 'remarks', 'cancellation_reason',
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

    // --- RELATIONSHIPS ---

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- ACCESSORS ---

    /**
     * Formatting the user address
     */
    public function getFullAddressAttribute()
    {
        if ($this->user) {
            return "{$this->user->street}, {$this->user->postcode} {$this->user->city}, {$this->user->state}";
        }
        return 'No Address Provided';
    }

    // --- BUSINESS LOGIC ---

    public static function generateBookingReference(): string
    {
        return 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Logic for date overlap check
     */
    /**
 * Logic for date overlap check [Fixed column names]
 */
    public function scopeForCarInRange(Builder $query, $carId, $startDate, $endDate)
    {
        return $query->where('car_id', $carId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($startDate, $endDate) {
                // Changed start_date -> pickup_date and end_date -> return_date
                $q->whereBetween('pickup_date', [$startDate, $endDate])
                ->orWhereBetween('return_date', [$startDate, $endDate])
                ->orWhere(function ($inner) use ($startDate, $endDate) {
                    $inner->where('pickup_date', '<=', $startDate)
                            ->where('return_date', '>=', $endDate);
                });
            });
    }

    public function calculateDuration(): int
    {
        // Parsing dates to Carbon for accurate Malaysian holiday/weekend calculation
        $pickup = Carbon::parse($this->pickup_date);
        $return = Carbon::parse($this->return_date);
        
        $days = $pickup->diffInDays($return);
        
        // Return minimum 1 day even for short rentals
        return $days < 1 ? 1 : (int) $days;
    }

    public function calculateDeposit(float $totalPrice): float
    {
        return round($totalPrice * 0.1, 2);
    }

    public function getRemainingBalance(): float
    {
        return (float) $this->total_price - (float) $this->paid_amount;
    }

    // --- SCOPES ---

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