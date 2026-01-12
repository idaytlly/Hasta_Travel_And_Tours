<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'voucher';

    protected $primaryKey = 'voucher_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'voucher_id',
        'voucherCode',
        'voucherAmount',
        'used_count',
        'expiryDate',
        'voucherStatus',
    ];

    protected $casts = [
        'expiryDate' => 'date',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'voucher_id', 'voucher_id');
    }

    // Helper methods
    public function isValid()
    {
        return $this->voucherStatus === 'active' && $this->expiryDate >= now();
    }
}