<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id', // Changed from user_id
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification (receiver).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id'); // Changed to receiver_id
    }

    /**
     * Get the sender of the notification.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope to get unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Get the icon based on notification type.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'booking' => 'fa-calendar-check',
            'inspection' => 'fa-clipboard-check',
            'maintenance' => 'fa-wrench',
            'system' => 'fa-info-circle',
            default => 'fa-bell',
        };
    }

    /**
     * Get the color based on notification type.
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'booking' => 'primary',
            'inspection' => 'warning',
            'maintenance' => 'danger',
            'system' => 'info',
            default => 'secondary',
        };
    }
}