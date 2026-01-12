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
        'voucherCode',
        'voucherAmount',
        'voucherType',
        'voucherStatus',
        'expiryDate',
        'used_count',
        'max_usage',
        'minimum_spend',
        'description'
    ];

    protected $casts = [
        'voucherAmount' => 'decimal:2',
        'expiryDate' => 'date',
        'minimum_spend' => 'decimal:2',
        'used_count' => 'integer',
        'max_usage' => 'integer',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'voucher_id', 'voucher_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('voucherStatus', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('voucherStatus', 'expired');
    }

    public function scopeAvailable($query)
    {
        return $query->where('voucherStatus', 'active')
                    ->where(function($q) {
                        $q->whereNull('expiryDate')
                          ->orWhere('expiryDate', '>=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('max_usage')
                          ->orWhereRaw('used_count < max_usage');
                    });
    }

    // Attributes
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'expired' => 'danger',
            'inactive' => 'secondary'
        ];
        
        $color = $badges[$this->voucherStatus] ?? 'secondary';
        
        return "<span class='badge bg-{$color}'>{$this->voucherStatus}</span>";
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiryDate && $this->expiryDate < now();
    }

    public function getIsFullyUsedAttribute()
    {
        return $this->max_usage && $this->used_count >= $this->max_usage;
    }

    public function getIsAvailableAttribute()
    {
        return $this->voucherStatus === 'active' && 
               !$this->is_expired && 
               !$this->is_fully_used;
    }

    public function getFormattedAmountAttribute()
    {
        if ($this->voucherType === 'percentage') {
            return $this->voucherAmount . '%';
        }
        
        return 'RM ' . number_format($this->voucherAmount, 2);
    }

    // Methods
    public function applyDiscount($totalAmount)
    {
        if (!$this->is_available) {
            return $totalAmount;
        }

        if ($this->minimum_spend && $totalAmount < $this->minimum_spend) {
            return $totalAmount;
        }

        if ($this->voucherType === 'percentage') {
            $discount = $totalAmount * ($this->voucherAmount / 100);
        } else {
            $discount = $this->voucherAmount;
        }

        return max(0, $totalAmount - $discount);
    }

    public function canBeApplied($totalAmount)
    {
        if (!$this->is_available) {
            return false;
        }

        if ($this->minimum_spend && $totalAmount < $this->minimum_spend) {
            return false;
        }

        return true;
    }

    public static function generateVoucherCode($length = 8)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $code;
    }

    public static function generateVoucherId()
    {
        $date = now()->format('Ymd');
        $lastVoucher = self::where('voucher_id', 'like', "VCH{$date}%")
                          ->orderBy('voucher_id', 'desc')
                          ->first();
        
        if ($lastVoucher) {
            $lastNumber = intval(substr($lastVoucher->voucher_id, -3));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        
        return "VCH{$date}{$nextNumber}";
    }
}