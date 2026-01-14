<?php
// app/Models/RentalRate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalRate extends Model
{
    use HasFactory;

    protected $table = 'rental_rate';
    protected $primaryKey = 'rate_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rate_id',
        'rate_name',
        'rate_type',
        'hours',
        'rate_price',
        'description',
        'late_penalty_percentage',
        'grace_period_minutes',
        'is_active',
        'plate_no',
        'staff_id'
    ];

    protected $casts = [
        'rate_price' => 'decimal:2',
        'late_penalty_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'grace_period_minutes' => 'integer',
        'hours' => 'integer',
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'plate_no', 'plate_no');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNormalRates($query)
    {
        return $query->where('rate_type', 'normal');
    }

    public function scopeLateRates($query)
    {
        return $query->where('rate_type', 'late_return');
    }

    public function scopeOvertimeRates($query)
    {
        return $query->where('rate_type', 'overtime');
    }

    // Methods
    public function calculateLateCharge($basePrice)
    {
        if (!$this->late_penalty_percentage) {
            return $basePrice * 1.5; // Default 50% penalty
        }
        
        return $basePrice * (1 + ($this->late_penalty_percentage / 100));
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->hours === 1) {
            return 'RM ' . number_format($this->rate_price, 2) . '/hour';
        }
        
        return 'RM ' . number_format($this->rate_price, 2) . ' for ' . $this->hours . ' hours';
    }
}