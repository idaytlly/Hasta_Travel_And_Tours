<!-- resources/views/staff/partials/sidebar.blade.php -->
<div class="w-64 bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-screen">
    <!-- Logo Header -->
    <div class="p-6 bg-gradient-to-br from-red-600 to-red-700">
        <div class="flex items-center gap-3 text-white">
            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                <i data-lucide="car" class="w-7 h-7"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">CarRental</h1>
                <p class="text-sm text-red-100 font-medium">Staff Portal</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-3 overflow-y-auto">
        <!-- Main Section -->
        <div class="nav-section-title">Main</div>
        
        <a href="{{ route('staff.dashboard') }}" 
           class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Overview</span>
        </a>
        
        <a href="{{ route('staff.bookings') }}" 
           class="nav-link {{ request()->routeIs('staff.bookings') ? 'active' : '' }}">
            <i data-lucide="calendar-check" class="w-5 h-5"></i>
            <span>Bookings</span>
        </a>
        
        <a href="{{ route('staff.delivery') }}" 
           class="nav-link {{ request()->routeIs('staff.delivery') ? 'active' : '' }}" 
           id="delivery-nav-link">
            <i data-lucide="truck" class="w-5 h-5"></i>
            <span>Delivery & Pickup</span>
        </a>
        
        <!-- Management Section -->
        <div class="nav-section-title">Management</div>
        
        <a href="{{ route('staff.vehicles') }}" 
           class="nav-link {{ request()->routeIs('staff.vehicles') ? 'active' : '' }}">
            <i data-lucide="car-front" class="w-5 h-5"></i>
            <span>Vehicles</span>
        </a>
        
        <a href="{{ route('staff.customers') }}" 
           class="nav-link {{ request()->routeIs('staff.customers') ? 'active' : '' }}">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Customers</span>
        </a>
        
        <!-- Analytics Section -->
        <div class="nav-section-title">Analytics</div>
        
        <a href="{{ route('staff.reports') }}" 
           class="nav-link {{ request()->routeIs('staff.reports') ? 'active' : '' }}">
            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
            <span>Reports</span>
        </a>
        
        <!-- Admin Only: Staff Management -->
        <a href="{{ route('staff.staff-management') }}" 
           class="nav-link {{ request()->routeIs('staff.staff-management') ? 'active' : '' }}"
           id="staff-management-nav-link">
            <i data-lucide="user-cog" class="w-5 h-5"></i>
            <span>Staff Management</span>
        </a>
    </nav>
    
    <!-- Staff Info Footer -->
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        <div class="bg-white border border-gray-200 p-3 rounded-lg shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-sm" id="staff-avatar">
                    @php
                        // Helper function to get initials from name
                        function getInitials($name) {
                            if (empty($name)) return 'SM';
                            
                            $names = explode(' ', $name);
                            $initials = '';
                            
                            if (count($names) >= 2) {
                                $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names)-1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($name, 0, 2));
                            }
                            
                            return $initials;
                        }
                        
                        echo getInitials(Auth::guard('staff')->user()->name ?? '');
                    @endphp
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate" id="staff-name">
                        {{ Auth::guard('staff')->user()->name ?? 'Staff Member' }}
                    </p>
                    <p class="text-xs text-gray-500 flex items-center gap-1" id="staff-role">
                        @php
                            $role = Auth::guard('staff')->user()->role ?? 'staff';
                            $roleColor = match($role) {
                                'admin' => 'bg-purple-500',
                                'runner' => 'bg-blue-500',
                                default => 'bg-green-500',
                            };
                        @endphp
                        <span class="w-2 h-2 {{ $roleColor }} rounded-full"></span>
                        <span>{{ ucfirst($role) }}</span>
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" 
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Logout">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Hide delivery link for non-runners
    document.addEventListener('DOMContentLoaded', () => {
        const staffRole = '{{ Auth::guard("staff")->user()->role ?? "staff" }}';
        const deliveryLink = document.getElementById('delivery-nav-link');
        const staffManagementLink = document.getElementById('staff-management-nav-link');
        
        // Hide delivery for non-runners
        if (staffRole !== 'runner' && staffRole !== 'admin') {
            if (deliveryLink) deliveryLink.style.display = 'none';
        }
        
        // Hide staff management for non-admins
        if (staffRole !== 'admin') {
            if (staffManagementLink) staffManagementLink.style.display = 'none';
        }
        
        // Initialize Lucide icons
        lucide.createIcons();
    });
</script>

<style>
    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        margin-bottom: 0.25rem;
        border-radius: 0.5rem;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .nav-link:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }
    
    .nav-link.active {
        background-color: #dc2626;
        color: white;
    }
    
    .nav-link.active:hover {
        background-color: #b91c1c;
    }
    
    .nav-section-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ca3af;
        padding: 0.5rem 1rem;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .nav-section-title:first-child {
        margin-top: 0;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .nav-link {
        animation: slideIn 0.3s ease forwards;
    }
</style>