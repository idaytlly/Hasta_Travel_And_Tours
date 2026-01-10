<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        // profile fields
        'matricNum',
        'name',
        'ic',
        'phone_no',
        'address',
        'state',
        'city',
        'postcode',
        'license_no',
        'emergency_phoneNo',
        'emergency_name',
        'emergency_relationship',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
?>

