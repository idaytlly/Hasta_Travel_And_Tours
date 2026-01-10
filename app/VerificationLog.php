<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationLog extends Model
{
    protected $table = 'verification_logs';
    
    protected $fillable = [
        'booking_id',
        'staff_id',
        'staff_name',
        'verified_at'
    ];
    
    protected $casts = [
        'verified_at' => 'datetime'
    ];
    
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
    
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }
}