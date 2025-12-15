<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price_per_day', 'image_url'];

    // One car can have many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
