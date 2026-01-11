<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Models\Payment;
use App\Models\Staff;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'booking_id',
        'pickup_date',
        'pickup_location', 
        'pickup_details',      
        'return_date',
        'return_time',
        'dropoff_location',    
        'dropoff_details',     
        'delivery_required',   
        'signature',            
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

    /* ================= RELATIONSHIPS ================= */

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'plate_no', 'plate_no');
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

    /* ================= SCOPES ================= */

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

    /* ================= HELPERS ================= */

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
        return match ($this->booking_status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /* ================= LATE RETURN LOGIC ================= */

    public function calculateLateReturn()
    {
        if (!$this->actual_return_date || !$this->actual_return_time) {
            return null;
        }

        $scheduledReturn = Carbon::parse(
            $this->return_date->format('Y-m-d') . ' ' . $this->return_time
        );

        $actualReturn = Carbon::parse(
            $this->actual_return_date->format('Y-m-d') . ' ' . $this->actual_return_time
        );

        $gracePeriod = $this->vehicle
            ->rentalRates()
            ->where('rate_type', 'normal')
            ->first()
            ->grace_period_minutes ?? 30;

        if ($actualReturn->gt($scheduledReturn->addMinutes($gracePeriod))) {
            $lateMinutes = $scheduledReturn->diffInMinutes($actualReturn);
            $lateHours = ceil($lateMinutes / 60);

            return [
                'late_hours' => $lateHours,
                'late_minutes' => $lateMinutes,
                'is_late' => true,
                'grace_period' => $gracePeriod,
            ];
        }

        return [
            'late_hours' => 0,
            'late_minutes' => 0,
            'is_late' => false,
            'grace_period' => $gracePeriod,
        ];
    }
}
