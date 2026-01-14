<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast  // <-- Important!
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $senderId;
    public $receiverId;
    public $senderType;

    public function __construct($message, $senderId, $receiverId, $senderType)
    {
        $this->message = $message;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->senderType = $senderType;
    }

    public function broadcastOn()
    {
        // Example: channel for chat between specific users
        return new PrivateChannel('chat.' . $this->senderId . '.' . $this->receiverId);
    }
    
    // Optional: Customize the broadcast name
    public function broadcastAs()
    {
        return 'new.message';
    }
}