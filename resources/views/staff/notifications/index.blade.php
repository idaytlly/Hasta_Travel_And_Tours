@extends('layouts.staff')

@section('title', 'Notifications')

@section('content')
<style>
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1e40af;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --border-color: #e2e8f0;
        --bg-light: #f8fafc;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .notifications-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title-wrapper h2 {
        color: var(--text-dark);
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title-wrapper h2 i {
        color: var(--primary-color);
    }

    /* Filters Card */
    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-dark);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        background: var(--bg-light);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .filter-btn.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .filter-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .filter-btn.active .filter-badge {
        background: rgba(255, 255, 255, 0.2);
    }

    .filter-btn:not(.active) .filter-badge {
        background: var(--bg-light);
        color: var(--text-muted);
    }

    /* Mark All Button */
    .btn-mark-all {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-mark-all:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    /* Notifications Card */
    .notifications-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* Notification Item */
    .notification-item {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: background 0.2s;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: var(--bg-light);
    }

    .notification-item.unread {
        background: #eff6ff;
        border-left: 4px solid var(--primary-color);
    }

    .notification-item.unread:hover {
        background: #dbeafe;
    }

    /* Notification Icon */
    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .notification-icon.primary {
        background: #eff6ff;
        color: var(--primary-color);
    }

    .notification-icon.success {
        background: #f0fdf4;
        color: var(--success-color);
    }

    .notification-icon.warning {
        background: #fffbeb;
        color: var(--warning-color);
    }

    .notification-icon.danger {
        background: #fef2f2;
        color: var(--danger-color);
    }

    .notification-icon.info {
        background: #eff6ff;
        color: var(--info-color);
    }

    /* Notification Content */
    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .notification-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .notification-item.unread .notification-title {
        font-weight: 700;
    }

    .notification-time {
        font-size: 0.813rem;
        color: var(--text-muted);
        white-space: nowrap;
    }

    .notification-message {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    /* Notification Actions */
    .notification-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-dark);
        font-weight: 500;
        font-size: 0.813rem;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
    }

    .action-btn:hover {
        background: var(--bg-light);
    }

    .action-btn.btn-view {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .action-btn.btn-view:hover {
        background: var(--primary-color);
        color: white;
    }

    .action-btn.btn-mark-read {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .action-btn.btn-mark-read:hover {
        background: var(--success-color);
        color: white;
    }

    .action-btn.btn-delete {
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    .action-btn.btn-delete:hover {
        background: var(--danger-color);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--bg-light);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: var(--text-muted);
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .empty-description {
        font-size: 0.938rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-section {
            flex-direction: column;
            align-items: stretch;
        }

        .notification-header {
            flex-direction: column;
            gap: 0.5rem;
        }

        .notification-time {
            white-space: normal;
        }

        .filter-buttons {
            flex-direction: column;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid py-4 notifications-container">
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="page-title-wrapper">
            <h2>
                <i class="fas fa-bell"></i>
                Notifications
            </h2>
        </div>
        
        @if($unreadCount > 0)
        <button type="button" class="btn-mark-all" id="markAllRead">
            <i class="fas fa-check-double"></i>
            Mark All as Read
        </button>
        @endif
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="filter-buttons">
            <a href="{{ route('staff.notifications.index', ['filter' => 'all']) }}" 
               class="filter-btn {{ $filter === 'all' ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                All Notifications
                <span class="filter-badge">{{ $notifications->total() }}</span>
            </a>
            <a href="{{ route('staff.notifications.index', ['filter' => 'unread']) }}" 
               class="filter-btn {{ $filter === 'unread' ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                Unread
                @if($unreadCount > 0)
                <span class="filter-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('staff.notifications.index', ['filter' => 'read']) }}" 
               class="filter-btn {{ $filter === 'read' ? 'active' : '' }}">
                <i class="fas fa-envelope-open"></i>
                Read
                @php
                    $readCount = $notifications->total() - $unreadCount;
                @endphp
                @if($readCount > 0)
                <span class="filter-badge">{{ $readCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-card">
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
                @php
                    // Decode the notification data
                    $notificationData = $notification->data ?? [];
                    if (is_string($notificationData)) {
                        $notificationData = json_decode($notificationData, true);
                    }
                    
                    // Determine if notification is unread
                    $isUnread = is_null($notification->read_at);
                    
                    // Get icon and color from notification type or data
                    $notificationType = $notificationData['type'] ?? 'info';
                    $iconMap = [
                        'success' => ['icon' => 'fa-check-circle', 'color' => 'success'],
                        'warning' => ['icon' => 'fa-exclamation-triangle', 'color' => 'warning'],
                        'error' => ['icon' => 'fa-times-circle', 'color' => 'danger'],
                        'danger' => ['icon' => 'fa-times-circle', 'color' => 'danger'],
                        'info' => ['icon' => 'fa-info-circle', 'color' => 'info'],
                        'primary' => ['icon' => 'fa-bell', 'color' => 'primary'],
                        'booking' => ['icon' => 'fa-calendar-check', 'color' => 'primary'],
                        'car' => ['icon' => 'fa-car', 'color' => 'info'],
                        'payment' => ['icon' => 'fa-credit-card', 'color' => 'success'],
                        'system' => ['icon' => 'fa-cog', 'color' => 'warning'],
                    ];
                    
                    $iconConfig = $iconMap[$notificationType] ?? $iconMap['primary'];
                @endphp
                
                <div class="notification-item {{ $isUnread ? 'unread' : '' }}" 
                     data-notification-id="{{ $notification->id }}">
                    <div class="d-flex gap-3">
                        <!-- Icon -->
                        <div class="notification-icon {{ $iconConfig['color'] }}">
                            <i class="fas {{ $iconConfig['icon'] }}"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="notification-content">
                            <div class="notification-header">
                                <h6 class="notification-title">
                                    {{ $notificationData['title'] ?? 'New Notification' }}
                                </h6>
                                <span class="notification-time">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <p class="notification-message">
                                {{ $notificationData['message'] ?? 'No message available' }}
                            </p>
                            
                            <div class="notification-actions">
                                @if(isset($notificationData['link']) && $notificationData['link'])
                                    <a href="{{ $notificationData['link'] }}" class="action-btn btn-view">
                                        <i class="fas fa-external-link-alt"></i>
                                        View Details
                                    </a>
                                @endif
                                
                                @if($isUnread)
                                    <button type="button" class="action-btn btn-mark-read mark-read" 
                                            data-notification-id="{{ $notification->id }}">
                                        <i class="fas fa-check"></i>
                                        Mark as Read
                                    </button>
                                @endif
                                
                                <button type="button" class="action-btn btn-delete delete-notification" 
                                        data-notification-id="{{ $notification->id }}">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Pagination -->
            @if($notifications->hasPages())
            <div class="pagination-wrapper">
                {{ $notifications->links() }}
            </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h5 class="empty-title">
                    @if($filter === 'unread')
                        All Caught Up!
                    @elseif($filter === 'read')
                        No Read Notifications
                    @else
                        No Notifications Yet
                    @endif
                </h5>
                <p class="empty-description">
                    @if($filter === 'unread')
                        You have no unread notifications at the moment.
                    @elseif($filter === 'read')
                        You don't have any read notifications.
                    @else
                        You'll see notifications here when you receive them.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    // Mark single notification as read
    document.querySelectorAll('.mark-read').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            markAsRead(notificationId, this);
        });
    });
    
    // Mark all notifications as read
    const markAllButton = document.getElementById('markAllRead');
    if (markAllButton) {
        markAllButton.addEventListener('click', function() {
            if (confirm('Mark all notifications as read?')) {
                markAllAsRead(this);
            }
        });
    }
    
    // Delete notification
    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            if (confirm('Are you sure you want to delete this notification?')) {
                deleteNotification(notificationId, this);
            }
        });
    });
    
    function markAsRead(notificationId, button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Use the correct route - from your fixed routes
        fetch(`/staff/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                location.reload();
            } else if (data && data.error) {
                throw new Error(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark notification as read');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check"></i> Mark as Read';
        });
    }
    
    function markAllAsRead(button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Use the correct route - from your fixed routes
        fetch('/staff/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                location.reload();
            } else if (data && data.error) {
                throw new Error(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark all notifications as read');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-double"></i> Mark All as Read';
        });
    }
    
    function deleteNotification(notificationId, button) {
        const notificationItem = button.closest('.notification-item');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Use the correct route - from your fixed routes
        fetch(`/staff/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                // Fade out animation
                notificationItem.style.opacity = '0';
                notificationItem.style.transform = 'translateX(-20px)';
                notificationItem.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    notificationItem.remove();
                    
                    // Reload if no notifications left
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            } else if (data && data.error) {
                throw new Error(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete notification');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-trash"></i> Delete';
        });
    }
});
</script>
@endpush
@endsection