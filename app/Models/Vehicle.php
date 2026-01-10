<?php
// app/Models/Vehicle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';
    protected $primaryKey = 'plate_no';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'plate_no',
        'name',
        'model',
        'year',
        'color',
        'vehicle_type',
        'seating_capacity',
        'transmission',
        'fuel_type',
        'mileage',
        'price_perHour',
        'availability_status',
        'image_url',
        'description',
        'features',
        'insurance_expiry',
        'last_maintenance_date',
        'next_maintenance_date'
    ];

    protected $casts = [
        'price_perHour' => 'decimal:2',
        'year' => 'integer',
        'seating_capacity' => 'integer',
        'mileage' => 'integer',
        'insurance_expiry' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'features' => 'array',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'plate_no', 'plate_no');
    }

    public function rentalRates()
    {
        return $this->hasMany(RentalRate::class, 'plate_no', 'plate_no');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'plate_no', 'plate_no');
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'plate_no', 'plate_no');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    public function scopeBooked($query)
    {
        return $query->where('availability_status', 'booked');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('availability_status', 'maintenance');
    }

    public function scopeUnavailable($query)
    {
        return $query->where('availability_status', 'unavailable');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }

    public function scopeByTransmission($query, $transmission)
    {
        return $query->where('transmission', $transmission);
    }

    public function scopeByFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    // Attributes
    public function getAvailabilityBadgeAttribute()
    {
        $badges = [
            'available' => 'success',
            'booked' => 'primary',
            'maintenance' => 'warning',
            'unavailable' => 'danger'
        ];
        
        $color = $badges[$this->availability_status] ?? 'secondary';
        
        return "<span class='badge bg-{$color}'>{$this->availability_status}</span>";
    }

    public function getFormattedPriceAttribute()
    {
        return 'RM ' . number_format($this->price_perHour, 2) . '/hour';
    }

    public function getIsAvailableAttribute()
    {
        return $this->availability_status === 'available';
    }

    public function getCurrentBookingAttribute()
    {
        return $this->bookings()
            ->whereIn('booking_status', ['confirmed', 'active'])
            ->where('return_date', '>=', now())
            ->first();
    }

    // Methods
    public function updateAvailability()
    {
        $hasActiveBooking = $this->bookings()
            ->whereIn('booking_status', ['confirmed', 'active'])
            ->where('return_date', '>=', now())
            ->exists();
        
        $hasMaintenance = $this->maintenances()
            ->where('maintenance_status', 'in_progress')
            ->exists();
        
        if ($hasMaintenance) {
            $this->availability_status = 'maintenance';
        } elseif ($hasActiveBooking) {
            $this->availability_status = 'booked';
        } else {
            $this->availability_status = 'available';
        }
        
        $this->save();
    }

    public function getMaintenanceStatus()
    {
        if ($this->next_maintenance_date && $this->next_maintenance_date <= now()->addDays(7)) {
            return 'due_soon';
        } elseif ($this->next_maintenance_date && $this->next_maintenance_date < now()) {
            return 'overdue';
        } else {
            return 'ok';
        }
    }

    public function getInsuranceStatus()
    {
        if ($this->insurance_expiry && $this->insurance_expiry <= now()->addDays(30)) {
            return 'expiring_soon';
        } elseif ($this->insurance_expiry && $this->insurance_expiry < now()) {
            return 'expired';
        } else {
            return 'valid';
        }
    }

    public function getUtilizationRate()
    {
        $totalDays = 30; // Last 30 days
        $bookedDays = $this->bookings()
            ->where('booking_status', '!=', 'cancelled')
            ->where('pickup_date', '>=', now()->subDays($totalDays))
            ->sum(DB::raw('DATEDIFF(return_date, pickup_date) + 1'));
        
        return min(100, ($bookedDays / $totalDays) * 100);
    }
}