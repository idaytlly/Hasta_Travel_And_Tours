<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_id',
        'amount',
        'payment_status',
        'payment_date',
        'payment_proof',
        'payment_method',
        'booking_id',
        'verified_by_staff',
        'verified_at',
    ];

    protected $casts = [
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

    // Helper methods
    public function isVerified()
    {
        return $this->payment_status === 'paid' && !empty($this->verified_by_staff);
    }
}