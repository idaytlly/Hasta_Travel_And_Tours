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
        'deleted_at' => 'datetime',
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

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function pickupInspection()
    {
        return $this->hasOne(Inspection::class)->where('type', 'pickup');
    }

    public function returnInspection()
    {
        return $this->hasOne(Inspection::class)->where('type', 'return');
    }

    // --- ACCESSORS ---

    public function getFullAddressAttribute()
    {
        if ($this->user) {
            return "{$this->user->street}, {$this->user->postcode} {$this->user->city}, {$this->user->state}";
        }
        return 'No Address Provided';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->trashed()) {
            return '<span class="badge bg-secondary-soft text-secondary border border-secondary-subtle px-3">Cancelled / Archived</span>';
        }
        
        if ($this->status === 'completed') {
            return '<span class="badge bg-success-soft text-success border border-success-subtle px-3">Completed</span>';
        }
        
        if ($this->isOverdue()) {
            return '<span class="badge bg-danger-soft text-danger border border-danger-subtle px-3">Overdue</span>';
        }
        
        if ($this->status === 'active' || $this->status === 'confirmed') {
            return '<span class="badge bg-primary-soft text-primary border border-primary-subtle px-3">Confirmed</span>';
        }
        
        return '<span class="badge bg-warning-soft text-warning border border-warning-subtle px-3">' . ucfirst($this->status) . '</span>';
    }

    // --- BUSINESS LOGIC ---

    public static function generateBookingReference(): string
    {
        return 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    public function isOverdue(): bool
    {
        return now()->startOfDay()->greaterThan(Carbon::parse($this->return_date)->startOfDay()) 
            && !$this->returnInspection && $this->status !== 'completed' && $this->status !== 'cancelled';
    }

    public function markAsCancelled(string $reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason
        ]);
        return $this->delete(); 
    }

    // --- SCOPES ---

    public function scopeForCarInRange(Builder $query, $carId, $startDate, $endDate)
    {
        return $query->where('car_id', $carId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('pickup_date', [$startDate, $endDate])
                ->orWhereBetween('return_date', [$startDate, $endDate])
                ->orWhere(function ($inner) use ($startDate, $endDate) {
                    $inner->where('pickup_date', '<=', $startDate)
                          ->where('return_date', '>=', $endDate);
                });
            });
    }
}