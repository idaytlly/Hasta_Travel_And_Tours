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
        'role', // make sure 'role' exists if you are checking for admin/staff
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
        'password' => 'hashed',
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

    // Check if user is staff (including admin)
    public function isStaff(): bool
    {
        return in_array($this->role, ['staff', 'admin']);
    }

    // Check if user is a customer
    public function isCustomer(): bool
    {
        return $this->usertype === 'customer';
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount(): int
    {
        // Uses Laravel's built-in notifications()
        return $this->unreadNotifications()->count();
    }
}
