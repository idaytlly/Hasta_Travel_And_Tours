<?php
// app/Models/Inspection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspections';
    protected $primaryKey = 'inspection_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'inspection_id',
        'booking_id',
        'plate_no',
        'person_in_charge',
        'inspection_date',
        'fuel_level',
        'mileage_before',
        'mileage_after',
        'damage_notes',
        'photo_evidence',
        'inspection_status',
        'notes'
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
        'mileage_before' => 'integer',
        'mileage_after' => 'integer',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'plate_no', 'plate_no');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'person_in_charge', 'staff_id');
    }
}