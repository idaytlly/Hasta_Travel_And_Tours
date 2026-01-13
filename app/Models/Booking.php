<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    // Fix 1: Define the correct table name
    protected $table = 'booking';

    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'booking_id',
        'customer_id',
        'plate_no',
        'vehicle_id',
        'pickup_date',
        'pickup_time',
        'pickup_location',
        'pickup_details',
        'return_date',
        'return_time',
        'dropoff_location',
        'dropoff_details',
        'delivery_required',
        'total_price',
        'booking_status',
        'voucher_id',
        'signature',
        'special_requests',
        'stamps_earned',         // NEW
        'stamp_awarded',         // NEW
        'actual_return_date',
        'actual_return_time',
        'late_return_hours',
        'late_return_charge',
        'late_return_notes',
        'late_charge_paid',
        'late_charge_approved_by',
        'late_charge_approved_at',
        'approved_by_staff',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'late_charge_approved_at' => 'datetime',
        'late_charge_paid' => 'boolean',
        'delivery_required' => 'boolean',
        'stamp_awarded' => 'boolean',  // NEW
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

    /* ================= HELPERS ================= */

    public function calculateTotalHours()
    {
        $pickup = Carbon::parse($this->pickup_date . ' ' . $this->pickup_time);
        $return = Carbon::parse($this->return_date . ' ' . $this->return_time);

        return (int) ceil($pickup->diffInHours($return));
    }

    public function approvedBy()
    {
        return $this->belongsTo(Staff::class, 'approved_by_staff', 'staff_id');
    }

    public function lateChargeApprovedBy()
    {
        return $this->belongsTo(Staff::class, 'late_charge_approved_by', 'staff_id');
    }

}

