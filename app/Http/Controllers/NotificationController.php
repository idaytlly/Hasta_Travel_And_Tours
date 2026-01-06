<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // CUSTOMER & STAFF inbox
    public function index()
    {
        $notifications = Notification::where(function ($q) {
            $q->where('receiver_id', auth()->id())
              ->orWhereNull('receiver_id'); // announcements
        })->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    // CUSTOMER sends message to admin
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $admins = User::where('usertype', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'message' => $request->message,
                'type' => 'message',
                'sender_id' => auth()->id(),
                'receiver_id' => $admin->id
            ]);
        }

        return back()->with('success', 'Message sent to admin');
    }

    // ADMIN announcement (broadcast)
    public function createAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required'
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => 'announcement',
            'sender_id' => auth()->id(),
            'receiver_id' => null
        ]);

        return back()->with('success', 'Announcement published');
    }

    // Mark as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return back();
    }
}

