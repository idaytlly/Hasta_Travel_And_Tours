<?php
// app/Models/Voucher.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    protected $primaryKey = 'voucher_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'voucher_id',
        'customer_id',
        'voucherCode',
        'voucherAmount',
        'stamp_milestone',
        'used_count',
        'is_used',
        'expiryDate',
        'voucherStatus',
        'expiryDate',
        'used_count',
        'expiryDate',
        'voucherStatus',
    ];

    protected $casts = [
        'expiryDate' => 'date',
        'is_used' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'voucher_id', 'voucher_id');
    }

    public function isValid()
    {
        return $this->voucherStatus === 'active' 
            && $this->expiryDate >= now() 
            && !$this->is_used;
    }

    public function markAsUsed()
    {
        $this->update([
            'is_used' => true,
            'voucherStatus' => 'used',
            'used_count' => $this->used_count + 1,
        ]);
    }
}