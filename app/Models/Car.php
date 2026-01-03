<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cars';

    protected $fillable = [
        'name',                    // Full car name (e.g., "Toyota Vios 1.5G")
        'brand',                   // Car brand (e.g., "Toyota")
        'model',                   // Car model (e.g., "Vios")
        'year',                    // Manufacturing year
        'registration_number',     // License plate number
        'category',                // Car category (Sedan, SUV, etc.)
        'color',                   // Car color
        'transmission',            // manual/automatic
        'fuel_type',               // petrol/diesel/hybrid/electric
        'seats',                   // Number of seats
        'passengers',              // Max passengers (usually same as seats)
        'air_conditioner',         // Has AC or not
        'daily_rate',              // Daily rental rate
        'mileage',                 // Current mileage/odometer
        'status',                  // available/rented/maintenance/booked
        'is_available',            // Quick availability check
        'image',                   // Car image path
        'description',             // Car description
        'features',                // Additional features (JSON or text)
        'maintenance_records',     // Maintenance history (JSON)
        'last_maintenance_date',   // Last maintenance date
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'air_conditioner' => 'boolean',
        'year' => 'integer',
        'seats' => 'integer',
        'passengers' => 'integer',
        'daily_rate' => 'decimal:2',
        'mileage' => 'integer',
        'maintenance_records' => 'array',
        'last_maintenance_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['formatted_daily_rate'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get all bookings for this car
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get active bookings (not cancelled/completed)
     */
    public function activeBookings(): HasMany
    {
        return $this->hasMany(Booking::class)
            ->whereIn('status', ['pending', 'confirmed', 'active']);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Get only available cars
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true)
                    ->where('status', 'available');
    }

    /**
     * Scope: Filter by brand
     */
    public function scopeByBrand(Builder $query, $brand): Builder
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope: Filter by transmission
     */
    public function scopeByTransmission(Builder $query, $transmission): Builder
    {
        return $query->where('transmission', $transmission);
    }

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory(Builder $query, $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Get formatted daily rate with currency
     */
    public function getFormattedDailyRateAttribute(): string
    {
        return 'RM ' . number_format($this->daily_rate, 2);
    }

    /**
     * Get full car name (brand + model)
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if car is available for specific date range
     */
    public function isAvailableForDates($pickupDate, $returnDate): bool
    {
        // First check if car is generally available
        if (!$this->is_available || $this->status !== 'available') {
            return false;
        }

        // Check if there are any bookings that overlap with the requested dates
        return !$this->bookings()
            ->where(function ($query) use ($pickupDate, $returnDate) {
                $query->whereBetween('pickup_date', [$pickupDate, $returnDate])
                      ->orWhereBetween('return_date', [$pickupDate, $returnDate])
                      ->orWhere(function ($q) use ($pickupDate, $returnDate) {
                          $q->where('pickup_date', '<=', $pickupDate)
                            ->where('return_date', '>=', $returnDate);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->exists();
    }

    /**
     * Mark car as available
     */
    public function markAsAvailable(): void
    {
        $this->update([
            'is_available' => true,
            'status' => 'available'
        ]);
    }

    /**
     * Mark car as rented
     */
    public function markAsRented(): void
    {
        $this->update([
            'is_available' => false,
            'status' => 'rented'
        ]);
    }

    /**
     * Mark car as under maintenance
     */
    public function markAsMaintenance(): void
    {
        $this->update([
            'is_available' => false,
            'status' => 'maintenance'
        ]);
    }

    /**
     * Mark car as booked
     */
    public function markAsBooked(): void
    {
        $this->update([
            'is_available' => false,
            'status' => 'booked'
        ]);
    }

    /**
     * Check if car has active bookings
     */
    public function hasActiveBookings(): bool
    {
        return $this->activeBookings()->exists();
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'available' => 'success',
            'rented' => 'primary',
            'booked' => 'info',
            'maintenance' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get status icon for UI
     */
    public function getStatusIcon(): string
    {
        return match($this->status) {
            'available' => 'fa-check-circle',
            'rented' => 'fa-key',
            'booked' => 'fa-calendar-check',
            'maintenance' => 'fa-tools',
            default => 'fa-car'
        };
    }

    /**
     * Add maintenance record
     */
    public function addMaintenanceRecord(array $record): void
    {
        $records = $this->maintenance_records ?? [];
        $records[] = array_merge($record, [
            'created_at' => now()->toDateTimeString()
        ]);
        
        $this->update([
            'maintenance_records' => $records,
            'last_maintenance_date' => $record['date'] ?? now()
        ]);
    }

    /**
     * Get latest maintenance record
     */
    public function getLatestMaintenanceRecord(): ?array
    {
        $records = $this->maintenance_records ?? [];
        return end($records) ?: null;
    }
}