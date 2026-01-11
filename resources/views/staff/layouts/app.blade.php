<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Portal') - CarRental</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Custom Styles */
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
            @apply fixed top-4 right-4 z-50 transform transition-all duration-300;
        }
        .toast.show {
            @apply translate-x-0 opacity-100;
        }
        .toast.hide {
            @apply translate-x-full opacity-0;
        }
        
        /* Smooth transitions */
        * {
            @apply transition-colors duration-200;
        }
        
        /* Custom scrollbar */
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
                        <div class="flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-green-700 font-medium">Live</span>
                        </div>
                        
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" onclick="toggleNotifications()">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full" id="notification-dot"></span>
                        </button>
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
    <div id="toast" class="toast hide">
        <div class="bg-white rounded-lg shadow-lg border-l-4 p-4 flex items-start gap-3 min-w-[300px]" id="toast-content">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0" id="toast-icon"></i>
            <div class="flex-1">
                <p class="font-semibold text-gray-800" id="toast-message">Success</p>
            </div>
            <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Global Scripts -->
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Setup CSRF token for all AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('current-time').textContent = now.toLocaleDateString('en-MY', options);
        }
        updateTime();
        setInterval(updateTime, 60000);

        // Toast Notification System
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toast-content');
            const toastIcon = document.getElementById('toast-icon');
            const toastMessage = document.getElementById('toast-message');
            
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
            
            toast.classList.remove('hide');
            toast.classList.add('show');
            
            lucide.createIcons();
            
            setTimeout(() => hideToast(), 5000);
        }
        
        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('show');
            toast.classList.add('hide');
        }

        // Toggle notifications
        function toggleNotifications() {
            showToast('No new notifications', 'info');
        }

        // Format currency
        function formatCurrency(amount) {
            return 'RM ' + parseFloat(amount).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-MY', options);
        }
        
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('en-MY', options);
        }

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
    </script>

    @stack('scripts')
</body>
</html>