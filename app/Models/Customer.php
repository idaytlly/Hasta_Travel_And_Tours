<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    /**
     * Use string primary key 'customer_id'
     */
    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'email',
        'password',
        // profile fields
        'matricNum',
        'name',
        'ic_number',
        'phone_no',
        'license_expiry',
        'emergency_phoneNo',
        'emergency_name',
        'emergency_relationship',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Auto-generate a customer_id when creating a new record if not provided.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $key = $model->getKeyName();
            if (empty($model->{$key})) {
                // Try to derive next numeric suffix from last customer_id
                $last = self::orderBy('created_at', 'desc')->whereNotNull('customer_id')->first();
                if ($last && preg_match('/(\d+)$/', $last->customer_id, $m)) {
                    $num = intval($m[1]) + 1;
                } else {
                    $num = 1;
                }
                $model->{$key} = 'CUS' . str_pad($num, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Add these methods to your Customer model

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'customer_id', 'customer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }

    /**
     * Get total stamps earned from completed bookings (7+ hours)
     */
    public function getTotalStampsAttribute()
    {
        return $this->bookings()
            ->where('stamp_awarded', true)
            ->sum('stamps_earned');
    }

    /**
     * Get available vouchers
     */
    public function getAvailableVouchersAttribute()
    {
        return $this->vouchers()
            ->where('voucherStatus', 'active')
            ->where('is_used', false)
            ->where('expiryDate', '>=', now())
            ->get();
    }
}
?>

