<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
     use HasFactory;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'pickup_date',
        'return_date',
        'total_hours',
        'total_price',
        'booking_status',
        'matricNum',
        'plate_no',
        'voucher_id',
        'delivery_charge',
        'deposit',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'return_date' => 'datetime',
        'total_hours' => 'integer',
        'total_price' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    // Relationships
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
}
