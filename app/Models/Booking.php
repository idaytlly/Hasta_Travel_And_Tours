<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'car_id', 'start_date', 'end_date', 'document_path', 'status'
    ];

    // Automatically cast these to Carbon instances
    protected $dates = ['start_date', 'end_date'];

    // Relationship to the car
    public function car()
{
    return $this->belongsTo(Car::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}



}
