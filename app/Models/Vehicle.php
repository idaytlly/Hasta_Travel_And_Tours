<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    protected $table = 'vehicle';
    // Primary key is plate_no (string), not auto-incrementing
    protected $primaryKey = 'plate_no';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'plate_no',
        'brand',
        'model',
        'year',
        'category',
        'image',
        'price_per_hour',
        'availability_status'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get a full URL to the vehicle image.
     *
     * Priority:
     * 1. If image exists under public/ (e.g. public/car_images/...), use asset()
     * 2. If image exists on the public storage disk (storage/app/public/...), use Storage::url()
     * 3. Fallback to a placeholder in public/images/placeholder-car.png
     */
    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return asset('images/placeholder-car.png');
        }

        // If file exists directly under public/, return that URL
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        // If file exists on the public storage disk (storage/app/public), return its storage URL
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image); // typically /storage/<path>
        }

        // Final fallback
        return asset('images/placeholder-car.png');
    }
}

?>