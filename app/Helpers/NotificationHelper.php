<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\Customer;

class NotificationHelper
{
    /**
     * Create a new booking notification.
     */
    public static function createBookingNotification(Customer $user, string $title, string $message, ?string $link = null, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'message',
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Create a new inspection notification.
     */
    public static function createInspectionNotification(Customer $user, string $title, string $message, ?string $link = null, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'inspection',
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Create a new maintenance notification.
     */
    public static function createMaintenanceNotification(User $user, string $title, string $message, ?string $link = null, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'maintenance',
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Create a new system notification.
     */
    public static function createSystemNotification(User $user, string $title, string $message, ?string $link = null, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Notify all staff members.
     */
    public static function notifyAllStaff(string $type, string $title, string $message, ?string $link = null, ?array $data = null): void
    {
        $staffUsers = User::whereIn('role', ['staff', 'admin'])->get();
        
        foreach ($staffUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'data' => $data,
            ]);
        }
    }

    /**
     * Notify specific users.
     */
    public static function notifyUsers(array $userIds, string $type, string $title, string $message, ?string $link = null, ?array $data = null): void
    {
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'data' => $data,
            ]);
        }
    }
}