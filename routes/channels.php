<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

// Private chat channel
Broadcast::channel('chat.{senderId}.{receiverId}', function ($user, $senderId, $receiverId) {
    // User can join if they're either the sender or receiver
    return $user->id == $senderId || $user->id == $receiverId;
});

// Customer bookings channel
Broadcast::channel('user.{userId}.bookings', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Staff bookings channel
Broadcast::channel('staff.{staffId}.bookings', function ($user, $staffId) {
    return $user->id == $staffId && $user->type == 'staff'; // Adjust based on your user type check
});

// General staff notifications
Broadcast::channel('staff.notifications', function ($user) {
    return $user->type == 'staff'; // Staff users only
});

// General customer notifications  
Broadcast::channel('customer.notifications', function ($user) {
    return $user->type == 'customer'; // Customer users only
});