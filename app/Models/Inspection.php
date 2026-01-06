<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'type',
        'vehicle_type',
        'vehicle_brand',      // ADD THIS
        'vehicle_model',      // ADD THIS
        'vehicle_year',       // ADD THIS
        'license_plate',   
        'exterior_condition',
        'exterior_damages',
        'exterior_cleanliness',
        'interior_condition',
        'interior_damages',
        'interior_cleanliness',
        'engine_condition',
        'brake_condition',
        'tire_condition',
        'lights_condition',
        'wipers_condition',
        'horn_condition',
        'fuel_level',
        'oil_level',
        'coolant_level',
        'spare_tire',
        'jack',
        'vehicle_manual',
        'first_aid_kit',
        'fire_extinguisher',
        'helmet_condition',
        'side_mirrors',
        'mileage_reading',
        'images',
        'notes',
        'inspector_id',
        'inspected_at',
    ];

    protected $casts = [
        'images' => 'array',
        'spare_tire' => 'boolean',
        'jack' => 'boolean',
        'vehicle_manual' => 'boolean',
        'first_aid_kit' => 'boolean',
        'fire_extinguisher' => 'boolean',
        'side_mirrors' => 'boolean',
        'inspected_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}