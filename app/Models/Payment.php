<?php
// app/Models/Payment.php

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
        'payment_date',
        'payment_proof',
        'payment_method',
        'transaction_id',
        'payment_notes',
        'verified_by_staff',
        'verified_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Staff::class, 'verified_by_staff', 'staff_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

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

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_by_staff');
    }

    // Attributes
    public function getIsVerifiedAttribute()
    {
        return !is_null($this->verified_by_staff);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info'
        ];
        
        $color = $badges[$this->payment_status] ?? 'secondary';
        
        return "<span class='badge bg-{$color}'>{$this->payment_status}</span>";
    }

    public function getFormattedAmountAttribute()
    {
        return 'RM ' . number_format($this->amount, 2);
    }

    public static function generatePaymentId($type = 'PAY')
    {
        $date = now()->format('Ymd');
        $lastPayment = self::where('payment_id', 'like', "{$type}{$date}%")
                          ->orderBy('payment_id', 'desc')
                          ->first();
        
        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment->payment_id, -3));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        
        return "{$type}{$date}{$nextNumber}";
    }
}