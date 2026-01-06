<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose(View $view): void
    {
        $unreadNotifications = 0;
        
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && method_exists($user, 'notifications')) {
                $unreadNotifications = $user->notifications()->where('is_read', false)->count();
            }
        }
        
        $view->with('unreadNotifications', $unreadNotifications);
    }
}