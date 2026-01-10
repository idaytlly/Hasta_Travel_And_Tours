<div class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <!-- Logo -->
    <div class="p-6 bg-red-600">
        <div class="flex items-center gap-2 text-white">
            <i data-lucide="car" class="w-8 h-8"></i>
            <div>
                <h1 class="text-xl font-bold">CarRental</h1>
                <p class="text-sm text-red-100">Staff Portal</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto">
        <a href="{{ route('staff.dashboard') }}" 
           class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Overview</span>
        </a>
        
        <a href="{{ route('staff.bookings') }}" 
           class="nav-link {{ request()->routeIs('staff.bookings') ? 'active' : '' }}">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            <span>Bookings</span>
        </a>
        
        <a href="{{ route('staff.delivery') }}" 
           class="nav-link {{ request()->routeIs('staff.delivery') ? 'active' : '' }}"
           id="delivery-nav-link">
            <i data-lucide="truck" class="w-5 h-5"></i>
            <span>Delivery & Pickup</span>
        </a>
        
        <a href="{{ route('staff.reports') }}" 
           class="nav-link {{ request()->routeIs('staff.reports') ? 'active' : '' }}">
            <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            <span>Reports & Analytics</span>
        </a>
        
        <a href="{{ route('staff.vehicles') }}" 
           class="nav-link {{ request()->routeIs('staff.vehicles') ? 'active' : '' }}">
            <i data-lucide="car" class="w-5 h-5"></i>
            <span>Vehicles</span>
        </a>
        
        <a href="{{ route('staff.customers') }}" 
           class="nav-link {{ request()->routeIs('staff.customers') ? 'active' : '' }}">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Customers</span>
        </a>
    </nav>

    <!-- Staff Info -->
    <div class="p-4 border-t border-gray-200">
        <div class="bg-red-50 p-3 rounded-lg">
            <p class="text-sm text-red-800 font-medium" id="staff-name">Staff Member</p>
            <p class="text-xs text-red-600 mt-1" id="staff-role">Staff</p>
        </div>
    </div>
</div>

<script>
    // Hide delivery link for non-runners
    document.addEventListener('DOMContentLoaded', () => {
        const staffRole = localStorage.getItem('staff_role') || 'staff';
        const deliveryLink = document.getElementById('delivery-nav-link');
        
        if (staffRole !== 'runner' && staffRole !== 'admin') {
            deliveryLink.style.display = 'none';
        }
    });
</script>