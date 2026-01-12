<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'booking_id',
        'pickup_date',
        'pickup_time',
        'return_date',
        'return_time',
        'total_price',
        'booking_status',
        'special_requests',
        'actual_return_date',
        'actual_return_time',
        'late_return_hours',
        'late_return_charge',
        'late_return_notes',
        'late_charge_paid',
        'late_charge_approved_by',
        'late_charge_approved_at',
        'customer_id',
        'plate_no',
        'voucher_id',
        'approved_by_staff',
        'approved_at',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
        'approved_at' => 'datetime',
        'late_charge_approved_at' => 'datetime',
        'late_charge_paid' => 'boolean',
    ];

    // Relationships
    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'plate_no', 'plate_no');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'matricNum', 'matricNum');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'booking_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Staff::class, 'approved_by_staff', 'staff_id');
    }

    public function lateChargeApprovedBy()
    {
        return $this->belongsTo(Staff::class, 'late_charge_approved_by', 'staff_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('booking_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('booking_status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('booking_status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('booking_status', 'cancelled');
    }

    // Helper methods
    public function isPending()
    {
        return $this->booking_status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->booking_status === 'confirmed';
    }

    public function isCompleted()
    {
        return $this->booking_status === 'completed';
    }

    public function isCancelled()
    {
        return $this->booking_status === 'cancelled';
    }

    public function calculateTotalHours()
    {
        $pickup = Carbon::parse($this->pickup_date);
        $return = Carbon::parse($this->return_date);
        return (int) ceil($pickup->diffInHours($return));
    }

    public function getStatusColorAttribute()
    {
        return match($this->booking_status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    // Calculate if booking is late
    public function calculateLateReturn()
    {
        if (!$this->actual_return_date || !$this->actual_return_time) {
            return null;
        }

        $scheduledReturn = Carbon::parse($this->return_date->format('Y-m-d') . ' ' . $this->return_time);
        $actualReturn = Carbon::parse($this->actual_return_date->format('Y-m-d') . ' ' . $this->actual_return_time);
        
        // Get grace period from rental rate (default 30 minutes)
        $gracePeriod = $this->vehicle->rentalRates()
            ->where('rate_type', 'normal')
            ->first()->grace_period_minutes ?? 30;
        
        // Check if actually late (after grace period)
        if ($actualReturn->gt($scheduledReturn->addMinutes($gracePeriod))) {
            $lateMinutes = $scheduledReturn->diffInMinutes($actualReturn);
            $lateHours = ceil($lateMinutes / 60); // Round up to nearest hour
            
            return [
                'late_hours' => $lateHours,
                'late_minutes' => $lateMinutes,
                'is_late' => true,
                'grace_period' => $gracePeriod,
                'scheduled_return' => $scheduledReturn,
                'actual_return' => $actualReturn,
            ];
        }
        
        return [
            'late_hours' => 0,
            'late_minutes' => 0,
            'is_late' => false,
            'grace_period' => $gracePeriod,
        ];
    }

    // Calculate late return charge
    public function calculateLateCharge()
    {
        $lateInfo = $this->calculateLateReturn();
        
        if (!$lateInfo['is_late']) {
            return 0;
        }

        // Get late return rate (could be hourly rate * penalty percentage)
        $lateRate = $this->vehicle->rentalRates()
            ->where('rate_type', 'late_return')
            ->first();
        
        if ($lateRate) {
            // Use specific late return rate
            $charge = $lateRate->rate_price * $lateInfo['late_hours'];
        } else {
            // Calculate based on normal rate with penalty
            $normalRate = $this->vehicle->rentalRates()
                ->where('rate_type', 'normal')
                ->first();
            
            if ($normalRate) {
                $penaltyMultiplier = $normalRate->late_penalty_percentage ? (1 + ($normalRate->late_penalty_percentage / 100)) : 1.5;
                $charge = ($normalRate->rate_price * $penaltyMultiplier) * $lateInfo['late_hours'];
            } else {
                // Default: 1.5x normal hourly rate
                $charge = ($this->vehicle->price_perHour * 1.5) * $lateInfo['late_hours'];
            }
        }
        
        return $charge;
    }

    // Mark vehicle as returned with late calculation
    public function markAsReturned($actualDate, $actualTime, $notes = null)
    {
        $this->update([
            'actual_return_date' => $actualDate,
            'actual_return_time' => $actualTime,
            'booking_status' => 'completed',
            'late_return_notes' => $notes,
        ]);

        // Calculate late charges
        $lateInfo = $this->calculateLateReturn();
        
        if ($lateInfo['is_late']) {
            $lateCharge = $this->calculateLateCharge();
            
            $this->update([
                'late_return_hours' => $lateInfo['late_hours'],
                'late_return_charge' => $lateCharge,
            ]);
            
            return [
                'success' => true,
                'message' => 'Vehicle returned successfully. Late return detected.',
                'late_hours' => $lateInfo['late_hours'],
                'late_charge' => $lateCharge,
                'requires_approval' => $lateCharge > 0,
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Vehicle returned on time.',
            'late_hours' => 0,
            'late_charge' => 0,
        ];
    }

    // Approve late charges
    public function approveLateCharges($staffId, $notes = null)
    {
        $this->update([
            'late_charge_approved_by' => $staffId,
            'late_charge_approved_at' => now(),
            'late_return_notes' => $notes ?: $this->late_return_notes,
        ]);
        
        // Create payment record for late charges
        if ($this->late_return_charge > 0) {
            Payment::create([
                'payment_id' => 'LATE' . strtoupper(uniqid()),
                'amount' => $this->late_return_charge,
                'payment_status' => 'pending',
                'payment_method' => 'late_charge',
                'booking_id' => $this->booking_id,
                'payment_date' => now(),
            ]);
        }
        
        return $this;
    }

    // Get total amount including late charges
    public function getTotalAmount()
    {
        return $this->total_price + $this->late_return_charge;
    }

    // Check if late charges are paid
    public function isLateChargePaid()
    {
        if ($this->late_return_charge <= 0) {
            return true;
        }
        
        return $this->late_charge_paid || 
               $this->payments()
                   ->where('payment_method', 'late_charge')
                   ->where('payment_status', 'paid')
                   ->exists();
    }

    // Helper methods
    public function isApproved()
    {
        return $this->booking_status === 'confirmed' && !empty($this->approved_by_staff);
    }

    public function hasActivePayment()
    {
        return $this->payments()->where('payment_status', 'paid')->exists();
    }

    public function canBeApproved()
    {
        return $this->booking_status === 'pending' && $this->hasActivePayment();
    }

    /*
     * Backwards-compatible accessors for older controllers and views
     * That expect properties like `id`, `status`, `booking_code`, `start_date`, `end_date`, `total_amount`, and a `customer` relation
     */
    public function getIdAttribute()
    {
        return $this->booking_id;
    }

    public function getBookingCodeAttribute()
    {
        return $this->booking_id;
    }

    public function getStatusAttribute()
    {
        return $this->booking_status;
    }

    public function getStartDateAttribute()
    {
        return $this->pickup_date ? $this->pickup_date->toDateString() : null;
    }

    public function getEndDateAttribute()
    {
        return $this->return_date ? $this->return_date->toDateString() : null;
    }

    public function getTotalAmountAttribute()
    {
        return $this->total_price;
    }

    // Provide customer as single relation (many places expect $booking->customer)
    public function getCustomerAttribute()
    {
        return $this->customers ?? null;
    }

    // Helper to return a vehicle display name used elsewhere
    public function getVehicleNameAttribute()
    {
        if ($this->vehicle) {
            // Vehicle model may store `name`; fallback to plate_no
            return trim(($this->vehicle->brand ?? '') . ' ' . ($this->vehicle->model ?? '') . ' ' . ($this->vehicle->name ?? '')) ?: ($this->vehicle->name ?? $this->plate_no);
        }

        return $this->plate_no ?: null;
    }

    /**
     * Infer pickup type when the `pickup_type` column is not present.
     * Heuristics:
     *  - If the `pickup_type` attribute exists and is not empty, return it
     *  - If `delivery_fee` > 0 => 'delivery'
     *  - If `pickup_address` exists => 'delivery'
     *  - If `special_requests` contains keywords like 'deliver' or 'delivery' => 'delivery'
     *  - Otherwise return 'self-pickup'
     */
    public function getPickupTypeAttribute()
    {
        // If column exists natively
        if (array_key_exists('pickup_type', $this->attributes) && !empty($this->attributes['pickup_type'])) {
            return $this->attributes['pickup_type'];
        }

        // delivery_fee check (may not exist on all setups)
        if (isset($this->delivery_fee) && floatval($this->delivery_fee) > 0) {
            return 'delivery';
        }

        // pickup_address present
        if (!empty($this->pickup_address)) {
            return 'delivery';
        }

        // keyword check in special_requests
        if (!empty($this->special_requests) && (stripos($this->special_requests, 'deliver') !== false || stripos($this->special_requests, 'delivery') !== false)) {
            return 'delivery';
        }

        return 'self-pickup';
    }
}

