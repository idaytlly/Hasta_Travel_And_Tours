<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Portal') - CarRental</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
        .nav-link {
            @apply flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-200;
        }
        .nav-link.active {
            @apply bg-red-600 text-white hover:bg-red-700 hover:text-white;
        }
        .spinner {
            @apply border-4 border-gray-200 border-t-red-600 rounded-full w-8 h-8 animate-spin;
        }
        .toast {
            @apply fixed top-4 right-4 z-50 transform transition-all duration-300 translate-x-full opacity-0;
        }
        .toast.show {
            @apply translate-x-0 opacity-100;
        }
        
        * {
            @apply transition-colors duration-200;
        }
        
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            @apply bg-gray-100;
        }
        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 rounded-full;
        }
        ::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400;
        }
        
        .notification-badge {
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ping {
            75%, 100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Include Sidebar Partial -->
        @include('staff.partials.sidebar')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">@yield('page-title')</h2>
                        <p class="text-sm text-gray-600" id="current-time"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Real-time indicator -->
                        <div id="realtime-indicator" class="flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-green-700 font-medium" id="connection-status">Live</span>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" onclick="toggleNotifications()">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full hidden" id="notification-dot"></span>
                            </button>
                            <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h4 class="font-semibold text-gray-800">Notifications</h4>
                                </div>
                                <div id="notifications-list" class="max-h-64 overflow-y-auto">
                                    <!-- Notifications will be added here -->
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <button onclick="clearNotifications()" class="text-sm text-red-600 hover:text-red-700">Clear All</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <div class="bg-white rounded-lg shadow-lg border-l-4 p-4 flex items-start gap-3 min-w-[300px]" id="toast-content">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0" id="toast-icon"></i>
            <div class="flex-1">
                <p class="font-semibold text-gray-800" id="toast-message">Success</p>
                <p class="text-sm text-gray-600" id="toast-description"></p>
            </div>
            <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Global Scripts -->
<script>
    // ===================== REAL-TIME SETUP =====================
    console.log('🚀 Initializing real-time connection...');
    
    // 🔥 FIXED: Initialize Laravel Echo with REVERB (not Pusher)
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: '{{ config('reverb.app.key') }}',
        wsHost: '{{ config('reverb.app.host') }}',
        wsPort: {{ config('reverb.app.port') }},
        wssPort: {{ config('reverb.app.port') }},
        forceTLS: false,
        enabledTransports: ['ws', 'wss']
    });

    // Connection monitoring
    let connectionRetries = 0;
    const maxRetries = 3;
    let notifications = [];
    let notificationCount = 0;

    function updateConnectionStatus(status, isConnected = true) {
        const indicator = document.getElementById('realtime-indicator');
        const statusText = document.getElementById('connection-status');
        
        if (isConnected) {
            indicator.className = 'flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg';
            statusText.textContent = 'Live';
            connectionRetries = 0;
        } else {
            indicator.className = 'flex items-center gap-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg';
            statusText.textContent = status;
        }
    }

    // Listen for connection events (Reverb compatible)
    window.Echo.connector.socket.on('connect', () => {
        console.log('✅ Connected to Reverb WebSocket server');
        updateConnectionStatus('Live', true);
        subscribeToChannels();
    });

    window.Echo.connector.socket.on('disconnect', () => {
        console.log('❌ Disconnected from WebSocket server');
        updateConnectionStatus('Offline', false);
        
        if (connectionRetries < maxRetries) {
            connectionRetries++;
            setTimeout(() => {
                console.log(`🔄 Reconnecting... (Attempt ${connectionRetries}/${maxRetries})`);
                window.Echo.connect();
            }, 3000);
        }
    });

    window.Echo.connector.socket.on('connecting', () => {
        console.log('🔄 Connecting to WebSocket server...');
        updateConnectionStatus('Connecting...', false);
    });

    window.Echo.connector.socket.on('error', (error) => {
        console.error('WebSocket error:', error);
        updateConnectionStatus('Error', false);
    });

    // Subscribe to channels
    function subscribeToChannels() {
        console.log('📡 Subscribing to channels...');
        
        // Subscribe to staff dashboard channel
        window.Echo.channel('staff-dashboard')
            .listen('.booking.created', (data) => {
                console.log('📢 New Booking:', data);
                addNotification({
                    type: 'booking',
                    title: 'New Booking',
                    message: `${data.customer_name} booked ${data.vehicle}`,
                    data: data,
                    time: new Date().toLocaleTimeString()
                });
                
                showToast(`New booking from ${data.customer_name}`, 'success');
                playNotificationSound();
                
                // Trigger dashboard refresh if on dashboard page
                if (typeof refreshDashboard === 'function') {
                    refreshDashboard();
                }
            })
            .listen('.booking.updated', (data) => {
                console.log('📢 Booking Updated:', data);
                addNotification({
                    type: 'booking_update',
                    title: 'Booking Updated',
                    message: `Booking ${data.booking_id} - ${data.action}`,
                    data: data,
                    time: new Date().toLocaleTimeString()
                });
                
                showToast(`Booking ${data.booking_id} updated`, 'info');
                
                if (typeof refreshDashboard === 'function') {
                    refreshDashboard();
                }
            })
            .listen('.payment.received', (data) => {
                console.log('💰 Payment Received:', data);
                addNotification({
                    type: 'payment',
                    title: 'Payment Received',
                    message: `RM${data.amount} from ${data.customer_name}`,
                    data: data,
                    time: new Date().toLocaleTimeString()
                });
                
                showToast(`Payment of RM${data.amount} received`, 'success');
                playNotificationSound();
                
                if (typeof refreshDashboard === 'function') {
                    refreshDashboard();
                }
            });
        
        console.log('✅ Subscribed to staff-dashboard channel');
    }

        // ===================== NOTIFICATION SYSTEM =====================
        function addNotification(notification) {
            notification.id = Date.now() + Math.random();
            notification.time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            notifications.unshift(notification);
            notificationCount++;
            
            updateNotificationBadge();
            updateNotificationsList();
            
            // Store in localStorage
            localStorage.setItem('staff_notifications', JSON.stringify(notifications.slice(0, 50)));
        }

        function updateNotificationBadge() {
            const badge = document.getElementById('notification-dot');
            if (notificationCount > 0) {
                badge.classList.remove('hidden');
                badge.textContent = notificationCount > 9 ? '9+' : notificationCount;
                badge.classList.add('notification-badge');
            } else {
                badge.classList.add('hidden');
            }
        }

        function updateNotificationsList() {
            const list = document.getElementById('notifications-list');
            if (!list) return;
            
            list.innerHTML = notifications.slice(0, 10).map(notif => `
                <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer" onclick="viewNotification('${notif.id}')">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full ${getNotificationColor(notif.type)}"></div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">${notif.title}</p>
                            <p class="text-sm text-gray-600">${notif.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${notif.time}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getNotificationColor(type) {
            const colors = {
                booking: 'bg-green-500',
                payment: 'bg-blue-500',
                booking_update: 'bg-orange-500'
            };
            return colors[type] || 'bg-gray-500';
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('hidden');
        }

        function clearNotifications() {
            notifications = [];
            notificationCount = 0;
            updateNotificationBadge();
            updateNotificationsList();
            localStorage.removeItem('staff_notifications');
            document.getElementById('notification-dropdown').classList.add('hidden');
        }

        function viewNotification(id) {
            const notification = notifications.find(n => n.id === id);
            if (notification) {
                console.log('Viewing notification:', notification);
                // Here you can redirect to the relevant page
                if (notification.type === 'booking') {
                    window.location.href = `/staff/bookings/${notification.data.booking_id}`;
                }
            }
        }

        // ===================== UTILITY FUNCTIONS =====================
        function playNotificationSound() {
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwF');
                audio.volume = 0.3;
                audio.play().catch(e => console.log('Sound play failed:', e));
            } catch (e) {
                console.log('Sound play error:', e);
            }
        }

        // Toast Notification System
        function showToast(message, type = 'success', description = '') {
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toast-content');
            const toastIcon = document.getElementById('toast-icon');
            const toastMessage = document.getElementById('toast-message');
            const toastDesc = document.getElementById('toast-description');
            
            const types = {
                success: { icon: 'check-circle', color: 'border-green-500', iconColor: 'text-green-500' },
                error: { icon: 'x-circle', color: 'border-red-500', iconColor: 'text-red-500' },
                warning: { icon: 'alert-triangle', color: 'border-orange-500', iconColor: 'text-orange-500' },
                info: { icon: 'info', color: 'border-blue-500', iconColor: 'text-blue-500' }
            };
            
            const config = types[type] || types.success;
            
            toastContent.className = `bg-white rounded-lg shadow-lg ${config.color} border-l-4 p-4 flex items-start gap-3 min-w-[300px]`;
            toastIcon.setAttribute('data-lucide', config.icon);
            toastIcon.className = `w-5 h-5 flex-shrink-0 ${config.iconColor}`;
            toastMessage.textContent = message;
            toastDesc.textContent = description;
            
            toast.classList.add('show');
            
            lucide.createIcons();
            
            setTimeout(() => hideToast(), 5000);
        }
        
        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('show');
        }

        // Initialize Lucide icons
        lucide.createIcons();
        
        // CSRF token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = now.toLocaleDateString('en-MY', options);
            }
        }
        updateTime();
        setInterval(updateTime, 60000);

        // API Request Helper
        async function apiRequest(url, options = {}) {
            try {
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        ...options.headers
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('API Request Error:', error);
                showToast('An error occurred. Please try again.', 'error');
                throw error;
            }
        }

        // Load saved notifications on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedNotifications = localStorage.getItem('staff_notifications');
            if (savedNotifications) {
                notifications = JSON.parse(savedNotifications);
                notificationCount = notifications.length;
                updateNotificationBadge();
                updateNotificationsList();
            }
            
            console.log('🏁 Staff portal initialized');
        });

        // Close notification dropdown when clicking outside
        document.addEventListener('click', (event) => {
            const dropdown = document.getElementById('notification-dropdown');
            const button = event.target.closest('button[onclick="toggleNotifications()"]');
            
            if (!button && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>