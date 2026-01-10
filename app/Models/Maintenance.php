<?php
// app/Models/Maintenance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenance';
    protected $primaryKey = 'maintenance_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'maintenance_id',
        'plate_no',
        'staff_id',
        'maintenance_date',
        'maintenance_type',
        'maintenance_status',
        'description',
        'cost',
        'next_maintenance_date',
        'notes'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
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
}