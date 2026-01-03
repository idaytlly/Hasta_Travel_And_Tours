<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, unread, read
        
        // FIXED: Use receiver_id instead of user_id
        $query = Notification::where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }
        
        $notifications = $query->paginate(20);
        
        // FIXED: Use receiver_id instead of user_id
        $unreadCount = Notification::where('receiver_id', Auth::id())
            ->unread()
            ->count();
        
        return view('staff.notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // FIXED: Use receiver_id instead of user_id
        if ($notification->receiver_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        // FIXED: Use receiver_id instead of user_id
        Notification::where('receiver_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification)
    {
        // FIXED: Use receiver_id instead of user_id
        if ($notification->receiver_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    /**
     * Display the staff profile settings.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('staff.settings.profile', compact('user'));
    }

    /**
     * Update the staff profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('staff.settings.profile')
            ->with('success', 'Profile updated successfully');
    }

    /**
     * Update the staff password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('staff.settings.profile')
            ->with('success', 'Password updated successfully');
    }
}