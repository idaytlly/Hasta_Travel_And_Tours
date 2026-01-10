<div class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <p class="text-sm text-gray-600 mt-1">{{ now()->format('l, F d, Y') }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                    <span id="notification-badge" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                        0
                    </span>
                </button>
                
                <!-- Notification Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                        <button @click="markAllAsRead()" class="text-xs text-red-600 hover:text-red-700">
                            Mark all read
                        </button>
                    </div>
                    <div id="notifications-list" class="max-h-96 overflow-y-auto">
                        <div class="p-8 text-center text-gray-500">
                            <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                            <p>No notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Toggle (Demo) -->
            <button onclick="toggleRole()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm border border-gray-300">
                <span id="role-toggle-text">Switch Role</span>
            </button>
            
            <!-- New Rental Button -->
            <button onclick="openNewRentalModal()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>New Rental</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Load notifications
    async function loadNotifications() {
        const staffId = localStorage.getItem('staff_id') || 'STAFF001';
        try {
            const result = await apiCall(`/notifications/staff/${staffId}`);
            if (result.success) {
                const notifications = result.data.notifications;
                const unreadCount = result.data.unread_count;
                
                // Update badge
                const badge = document.getElementById('notification-badge');
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
                
                // Update list
                displayNotifications(notifications);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    function displayNotifications(notifications) {
        const list = document.getElementById('notifications-list');
        
        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                    <p>No notifications</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        list.innerHTML = notifications.map(notif => `
            <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer ${!notif.read ? 'bg-red-50' : ''}"
                 onclick="markNotificationAsRead('${notif.notification_id}')">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 ${getNotificationIconBg(notif.type)} p-2 rounded-lg">
                        <i data-lucide="${getNotificationIcon(notif.type)}" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-800">
                            ${notif.title}
                            ${!notif.read ? '<span class="inline-block w-2 h-2 bg-red-500 rounded-full ml-2"></span>' : ''}
                        </h4>
                        <p class="text-sm text-gray-600 mt-1">${notif.message}</p>
                        <p class="text-xs text-gray-500 mt-2">${formatDateTime(notif.created_at)}</p>
                    </div>
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    function getNotificationIcon(type) {
        const icons = {
            'booking': 'calendar',
            'delivery': 'truck',
            'approval': 'check-circle',
            'commission': 'dollar-sign',
            'reminder': 'clock',
            'alert': 'alert-circle'
        };
        return icons[type] || 'bell';
    }

    function getNotificationIconBg(type) {
        const colors = {
            'booking': 'bg-blue-500',
            'delivery': 'bg-red-500',
            'approval': 'bg-green-500',
            'commission': 'bg-yellow-500',
            'reminder': 'bg-purple-500',
            'alert': 'bg-orange-500'
        };
        return colors[type] || 'bg-gray-500';
    }

    async function markNotificationAsRead(notificationId) {
        try {
            await apiCall(`/notifications/mark-read/${notificationId}`, 'POST');
            loadNotifications();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async function markAllAsRead() {
        const staffId = localStorage.getItem('staff_id') || 'STAFF001';
        try {
            await apiCall(`/notifications/mark-all-read/${staffId}`, 'POST');
            loadNotifications();
            showToast('All notifications marked as read', 'success');
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }

    // Load on page load
    document.addEventListener('DOMContentLoaded', () => {
        loadNotifications();
        // Refresh every 30 seconds
        setInterval(loadNotifications, 30000);
        
        // Update role toggle text
        updateRoleToggleText();
    });

    // Role toggle function
    function toggleRole() {
        const currentRole = localStorage.getItem('staff_role') || 'staff';
        const roles = ['staff', 'admin', 'runner'];
        const currentIndex = roles.indexOf(currentRole);
        const newRole = roles[(currentIndex + 1) % roles.length];
        
        localStorage.setItem('staff_role', newRole);
        location.reload();
    }

    function updateRoleToggleText() {
        const currentRole = localStorage.getItem('staff_role') || 'staff';
        const roleText = document.getElementById('role-toggle-text');
        if (roleText) {
            roleText.textContent = `Role: ${currentRole.charAt(0).toUpperCase() + currentRole.slice(1)}`;
        }
    }

    // New rental modal (placeholder)
    function openNewRentalModal() {
        showToast('New Rental feature coming soon!', 'success');
    }
</script>