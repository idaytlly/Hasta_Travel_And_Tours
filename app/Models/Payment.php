<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_id',
        'booking_id',
        'amount',
        'payment_status',
        'deposit',
        'remaining_payment',
        'payment_date',
        'payment_proof',
        'payment_method',
        'transaction_id',
        'verified_by_staff',
        'verified_at',
        'refunded_by_staff',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'verified_at' => 'datetime',
        'refunded_at' => 'datetime',
        'amount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'remaining_payment' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_id)) {
                $payment->payment_id = 'PAY-' . strtoupper(uniqid());
            }
            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }
        });
    }

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function verifiedByStaff()
    {
        return $this->belongsTo(Staff::class, 'verified_by_staff', 'staff_id');
    }

    public function refundedByStaff()
    {
        return $this->belongsTo(Staff::class, 'refunded_by_staff', 'staff_id');
    }

    // Status check methods
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    public function isRefunded()
    {
        return $this->payment_status === 'refunded';
    }

    public function isPartiallyPaid()
    {
        return $this->payment_status === 'partially_paid';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('payment_status', 'refunded');
    }

    public function scopePartiallyPaid($query)
    {
        return $query->where('payment_status', 'partially_paid');
    }

    // Get payment proof URL
    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof) {
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }

    // Calculate if full payment is complete
    public function isFullyPaid()
    {
        return $this->payment_status === 'paid' || $this->remaining_payment <= 0;
    }

    // Get payment completion percentage
    public function getPaymentProgressAttribute()
    {
        if ($this->amount <= 0) {
            return 0;
        }
        $paid = $this->amount - $this->remaining_payment;
        return round(($paid / $this->amount) * 100, 2);
    }
}