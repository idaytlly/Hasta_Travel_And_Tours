<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];
}
