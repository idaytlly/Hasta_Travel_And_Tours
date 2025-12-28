<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'plateNo',
        'brand',
        'model',
        'year',
        'carType',
        'transmission',
        'daily_rate',
        'image',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'daily_rate' => 'decimal:2'
    ];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('carType', $type);
    }

    public function scopeOfBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function getFormattedPriceAttribute()
    {
        return 'RM' . number_format($this->daily_rate, 0);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->brand} {$this->model} {$this->year}");
    }
}