<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $unreadNotifications = 0;
        
        if (Auth::check()) {
            $unreadNotifications = Auth::user()->unreadNotificationsCount();
        }
        
        $view->with('unreadNotifications', $unreadNotifications);
    }
}