<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $primaryKey = 'inspection_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'inspection_id',
        'booking_id',
        'inspection_type',
        'inspection_date',
        'fuel_level',
        'inspection_status',
        'damage_notes',
        'photo_evidence',
        'person_in_charge',
        'car_photos',
        'fuel_photo',
        'remarks',
        'signature',
        'inspected_by',
        'inspected_at',
    ];

    protected $casts = [
        'car_photos' => 'array',
        'inspection_date' => 'date',
        'inspected_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the inspection
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Get the customer who performed the inspection
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'inspected_by', 'customer_id');
    }

    /**
     * Get the staff in charge
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'person_in_charge', 'staff_id');
    }

    /**
     * Get front photo URL
     */
    public function getFrontPhotoUrlAttribute()
    {
        $photos = $this->car_photos;
        return isset($photos['front']) ? asset('storage/' . $photos['front']) : null;
    }

    /**
     * Get back photo URL
     */
    public function getBackPhotoUrlAttribute()
    {
        $photos = $this->car_photos;
        return isset($photos['back']) ? asset('storage/' . $photos['back']) : null;
    }

    /**
     * Get left photo URL
     */
    public function getLeftPhotoUrlAttribute()
    {
        $photos = $this->car_photos;
        return isset($photos['left']) ? asset('storage/' . $photos['left']) : null;
    }

    /**
     * Get right photo URL
     */
    public function getRightPhotoUrlAttribute()
    {
        $photos = $this->car_photos;
        return isset($photos['right']) ? asset('storage/' . $photos['right']) : null;
    }

    /**
     * Get fuel photo URL
     */
    public function getFuelPhotoUrlAttribute()
    {
        return $this->fuel_photo ? asset('storage/' . $this->fuel_photo) : null;
    }

    /**
     * Get signature URL
     */
    public function getSignatureUrlAttribute()
    {
        return $this->signature ? asset('storage/' . $this->signature) : null;
    }

    /**
     * Check if all car photos are uploaded
     */
    public function getHasAllCarPhotosAttribute()
    {
        $photos = $this->car_photos;
        return isset($photos['front']) && isset($photos['back']) && 
               isset($photos['left']) && isset($photos['right']);
    }

    /**
     * Get inspection type label
     */
    public function getTypeLabel()
    {
        return $this->inspection_type === 'pickup' ? 'Pick-up' : 'Drop-off';
    }

    /**
     * Check if this is a customer inspection
     */
    public function isCustomerInspection()
    {
        return !empty($this->booking_id) && !empty($this->inspection_type);
    }

    /**
     * Check if this is a staff inspection
     */
    public function isStaffInspection()
    {
        return !empty($this->person_in_charge);
    }
}