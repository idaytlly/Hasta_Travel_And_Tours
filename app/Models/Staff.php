<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';  // Explicitly set table name
    protected $guard = 'staff';
    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staff_id',
        'name',
        'email',
        'phone_no',
        'ic_number', 
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        // REMOVED 'password' => 'hashed' - this can cause issues
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

    public function isRegularStaff()
    {
        return $this->role === 'staff';
    }

    public function isRunner()
    {
        return $this->role === 'runner';
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

    // Check permissions (simplified - no manager)
    public function canAccess($resource)
    {
        // Admin can access everything
        if ($this->isAdmin()) {
            return true;
        }

        // Regular staff can access basic features
        if ($this->isRegularStaff()) {
            return in_array($resource, ['dashboard', 'bookings', 'vehicles', 'customers']);
        }

        // Runner can only access delivery
        if ($this->isRunner()) {
            return $resource === 'delivery';
        }

        return false;
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'staff_id'; // Staff authenticates using email
    }

    public function creator()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }
}