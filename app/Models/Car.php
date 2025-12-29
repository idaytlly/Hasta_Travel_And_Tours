<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cars';

    protected $fillable = [
        'brand', 'model', 'year', 'transmission', 'daily_rate', 
        'image', 'is_available', 'air_conditioner', 'passengers', 
        'fuel_type', 'license_plate', 'description'
    ];

    // This fixes the "available()" error
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    // This fixes the "byBrand()" error
    public function scopeByBrand(Builder $query, $brand): Builder
    {
        return $query->where('brand', $brand);
    }

    // This fixes the "byTransmission()" error
    public function scopeByTransmission(Builder $query, $transmission): Builder
    {
        return $query->where('transmission', $transmission);
    }

    // Add this inside class Car extends Model
    public function isAvailableForDates($startDate, $endDate)
    {
        // Check if there are any bookings that overlap with the requested dates
        return !$this->bookings()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            // Only count 'confirmed' or 'pending' bookings, ignore 'cancelled'
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }

        /**
         * Define the relationship to Bookings
         */
        public function bookings()
        {
            return $this->hasMany(Booking::class);
        }
}