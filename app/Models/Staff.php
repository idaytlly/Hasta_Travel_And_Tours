<?php

// app/Models/Staff.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'staffID';

    protected $fillable = [
        'userID',
        'staff_type',
        'hire_date',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    /**
     * Get the user that owns the staff profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}