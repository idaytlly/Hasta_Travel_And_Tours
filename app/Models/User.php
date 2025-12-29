<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'ic',
        'street',
        'city',
        'state',
        'postcode',
        'license_no',
        'password',
        'usertype',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ will automatically hash passwords
    ];

    /**
     * Relationships
     */

    // User can have many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Helper methods
     */

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->usertype === 'admin';
    }

    // Check if user is staff
    public function isStaff(): bool
    {
        return $this->usertype === 'staff';
    }

    // Check if user is a customer
    public function isCustomer(): bool
    {
        return $this->usertype === 'customer';
    }
    
}
