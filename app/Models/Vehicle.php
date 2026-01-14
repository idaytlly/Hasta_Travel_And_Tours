<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $primaryKey = 'plate_no';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = ['pickup_location' => 'array',];

    
    protected $table = 'vehicle'; // Explicitly set table name
    
    protected $fillable = [
        'plate_no',
        'name',
        'color',
        'year',
        'vehicle_type',
        'roadtax_expiry',
        'transmission',
        'fuel_type',
        'seating_capacity',
        'price_perHour',
        'display_image',
        'images',
        'description',
        'distance_travelled',
        'availability_status',
        'maintenance_notes',
        'staff_id',
    ];
    
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'images' => 'array',
            'year' => 'integer',
            'seating_capacity' => 'integer',
            'price_perHour' => 'decimal:2',
            'distance_travelled' => 'decimal:2',
        ];
    }
    
    // Relationship with staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }


    /**
     * Get the vehicle's image URLs
     * Images are stored in: storage/app/public/vehicles/{plate_no}/
     */
    public function getImageUrls(): array
    {
        $images = [];
        
        // Check if images are stored as JSON in database
        if ($this->images && is_array($this->images)) {
            foreach ($this->images as $imagePath) {
                $images[] = Storage::url($imagePath);
            }
        }
        
        return $images;
    }

    /**
     * Get the first image URL
     */
    public function getFirstImageUrl(): ?string
    {
        $images = $this->getImageUrls();
        return $images[0] ?? null;
    }

    /**
     * Relationship with bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'plate_no', 'plate_no');
    }

    /**
     * Scope for available vehicles
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    /**
     * Scope for vehicles under maintenance
     */
    public function scopeMaintenance($query)
    {
        return $query->where('availability_status', 'maintenance');
    }

    /**
     * Scope for booked vehicles
     */
    public function scopeBooked($query)
    {
        return $query->where('availability_status', 'booked');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'RM ' . number_format($this->price_perHour, 2) . '/hour';
    }

    /**
     * Check if vehicle is available
     */
    public function isAvailable(): bool
    {
        return $this->availability_status === 'available';
    }

    /**
     * Check if vehicle is under maintenance
     */
    public function isUnderMaintenance(): bool
    {
        return $this->availability_status === 'maintenance';
    }

    /**
     * Update vehicle status
     */
    public function updateStatus(string $status, ?string $notes = null): bool
    {
        $this->availability_status = $status;
        
        if ($status === 'maintenance' && $notes) {
            $this->maintenance_notes = $notes;
        }
        
        return $this->save();
    }
}