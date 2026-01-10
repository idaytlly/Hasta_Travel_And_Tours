<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'customer';
    
    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'password',
        'license_no',
        'license_expiry',
        'address',
        'date_of_birth',
        'gender',
        'is_active',
        'verification_status',
        'profile_picture',
        'emergency_contact'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'license_expiry' => 'date',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Booking::class, 'customer_id', 'booking_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $this->where('verification_status', 'verified');
    }

    // Attributes
    public function getVerificationBadgeAttribute()
    {
        $badges = [
            'verified' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger'
        ];
        
        $color = $badges[$this->verification_status] ?? 'secondary';
        
        return "<span class='badge bg-{$color}'>{$this->verification_status}</span>";
    }

    public function getTotalSpentAttribute()
    {
        return $this->payments()->paid()->sum('amount');
    }

    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }

    public function getActiveBookingsAttribute()
    {
        return $this->bookings()->whereIn('booking_status', ['pending', 'confirmed', 'active'])->get();
    }

    public function getLicenseStatusAttribute()
    {
        if (!$this->license_expiry) {
            return 'not_provided';
        }
        
        if ($this->license_expiry < now()) {
            return 'expired';
        } elseif ($this->license_expiry <= now()->addDays(30)) {
            return 'expiring_soon';
        } else {
            return 'valid';
        }
    }

    // Methods
    public function canBookVehicle()
    {
        // Check if customer can book a vehicle
        return $this->is_active && 
               $this->verification_status === 'verified' && 
               $this->license_status === 'valid';
    }

    public function getBookingHistory()
    {
        return $this->bookings()
            ->with(['vehicle', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->booking_id,
                    'vehicle' => $booking->vehicle->name . ' (' . $booking->vehicle->plate_no . ')',
                    'dates' => $booking->pickup_date->format('M d') . ' - ' . $booking->return_date->format('M d'),
                    'status' => $booking->booking_status,
                    'total' => 'RM ' . number_format($booking->total_price, 2),
                    'balance' => 'RM ' . number_format($booking->balance, 2),
                ];
            });
    }

    public static function generateCustomerId()
    {
        $date = now()->format('Ymd');
        $lastCustomer = self::orderBy('id', 'desc')->first();
        
        if ($lastCustomer) {
            $lastNumber = intval(substr($lastCustomer->id, -3));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        
        return "CUST{$date}{$nextNumber}";
    }
}