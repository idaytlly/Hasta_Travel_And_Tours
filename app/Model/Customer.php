<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

<<<<<<< Updated upstream:app/Model/Customer.php
    protected $table = 'customers';
=======
    protected $guard = 'staff';
    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';
>>>>>>> Stashed changes:app/Models/Staff.php

    protected $fillable = [
        'staff_id',
        'name',
        'email',
        'phone_no',
        'password',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Relationships
    
    // Staff can manage many vehicles
    public function managedVehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_management', 'staff_id', 'plate_no')
                    ->withTimestamps();
    }

    // Staff can manage many bookings
    public function managedBookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_management', 'staff_id', 'booking_id')
                    ->withTimestamps();
    }

    // Staff can manage many vouchers
    public function managedVouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_management', 'staff_id', 'voucher_id')
                    ->withTimestamps();
    }

    // Staff can have many commissions
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'staff_id', 'staff_id');
    }

    // Staff can conduct many inspections
    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'person_in_charge', 'staff_id');
    }

    // Staff can create many rental rates
    public function rentalRates()
    {
        return $this->hasMany(RentalRate::class, 'staff_id', 'staff_id');
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isRegularStaff()
    {
        return $this->role === 'staff';
    }

    public function getFullInfo()
    {
        return [
            'staff_id' => $this->staff_id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'phone_no' => $this->phone_no,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }

    // Check permissions
    public function canAccess($resource)
    {
        // Admin can access everything
        if ($this->isAdmin()) {
            return true;
        }

        // Manager can access most things
        if ($this->isManager()) {
            return in_array($resource, ['dashboard', 'bookings', 'vehicles', 'customers', 'vouchers', 'reports']);
        }

        // Regular staff can access basic features
        if ($this->isRegularStaff()) {
            return in_array($resource, ['dashboard', 'bookings', 'vehicles', 'customers']);
        }

        return false;
    }
}