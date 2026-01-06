<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff'; 
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'password',
        'usertype',
        // add other fields
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
