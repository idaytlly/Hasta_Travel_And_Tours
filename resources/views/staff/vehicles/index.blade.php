{{-- resources/views/staff/vehicles/index.blade.php --}}
@extends('staff.layouts.app')

@section('title', 'Fleet Management')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
        <a href="{{ route('staff.dashboard') }}" class="hover:text-gray-900 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-900 font-medium">Fleet</span>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Fleet Management</h1>
            <p class="text-gray-600">Manage your vehicle fleet and availability</p>
        </div>
        <a href="{{ route('staff.vehicles.create') }}" 
           class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2 shadow-lg hover:shadow-xl">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span class="font-semibold">Add Vehicle</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $vehicles->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="car" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Available</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $vehicles->where('availability_status', 'available')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Booked</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $vehicles->where('availability_status', 'booked')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Maintenance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $vehicles->where('availability_status', 'maintenance')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="wrench" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <!-- Tabs -->
            <div class="flex items-center gap-8">
                <button class="relative pb-1 text-sm font-semibold text-red-600">
                    All Vehicles
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-red-600 rounded-full"></div>
                </button>
                <a href="{{ route('staff.vehicles.index', ['status' => 'available']) }}" 
                   class="pb-1 text-sm font-medium text-gray-500 hover:text-gray-900 transition">
                    Available
                </a>
                <a href="{{ route('staff.vehicles.index', ['status' => 'booked']) }}" 
                   class="pb-1 text-sm font-medium text-gray-500 hover:text-gray-900 transition">
                    Booked
                </a>
                <a href="{{ route('staff.vehicles.index', ['status' => 'maintenance']) }}" 
                   class="pb-1 text-sm font-medium text-gray-500 hover:text-gray-900 transition">
                    Maintenance
                </a>
            </div>
            
            <!-- Filter Toggle -->
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    Showing <span class="font-bold text-gray-900">{{ $vehicles->count() }}</span> of {{ $vehicles->total() }}
                </span>
                <button onclick="toggleFilters()" 
                        class="px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Filter</span>
                </button>
            </div>
        </div>

        <!-- Collapsible Filters -->
        <div id="filters-panel" class="hidden border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('staff.vehicles.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Search</label>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Name, plate number..." 
                                   class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <!-- Vehicle Type -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Vehicle Type</label>
                        <div class="relative">
                            <i data-lucide="car" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <select name="vehicle_type" class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                <option value="">All Types</option>
                                <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                                <option value="motorcycle" {{ request('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Status</label>
                        <div class="relative">
                            <i data-lucide="circle" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <select name="status" class="pl-10 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                            Apply Filters
                        </button>
                        <a href="{{ route('staff.vehicles.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition">
                            Clear All
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Vehicle List -->
    <div class="space-y-4">
        @forelse($vehicles as $vehicle)
            <div class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200 group"
                 x-data="{ showActions: false }"
                 @mouseenter="showActions = true"
                 @mouseleave="showActions = false">
                <div class="flex items-center p-5 gap-5">
                   <!-- Vehicle Image -->
                    <div class="flex-shrink-0">
                        <div class="relative w-24 h-24 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-200 group-hover:border-red-200 transition overflow-hidden">
                            @if($vehicle->display_image)
                                <!-- Use display_image if it exists -->
                                <img src="{{ asset('car_images/' . $vehicle->display_image) }}" alt="{{ $vehicle->name }}" 
                                    class="w-full h-full object-cover">
                            @elseif($vehicle->images)
                                <!-- Fallback to first detail image if no display_image -->
                                @php 
                                    $images = is_array($vehicle->images) ? $vehicle->images : json_decode($vehicle->images, true);
                                    $firstImage = !empty($images) ? reset($images) : null;
                                @endphp
                                @if($firstImage)
                                    <img src="{{ asset('car_images/' . $firstImage) }}" alt="{{ $vehicle->name }}" 
                                        class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="{{ $vehicle->vehicle_type == 'motorcycle' ? 'bike' : 'car' }}" class="w-12 h-12 text-gray-300"></i>
                                @endif
                            @else
                                <!-- Fallback to icon if no images at all -->
                                <i data-lucide="{{ $vehicle->vehicle_type == 'motorcycle' ? 'bike' : 'car' }}" class="w-12 h-12 text-gray-300"></i>
                            @endif
                            
                            <!-- Status Indicator -->
                            <div class="absolute top-2 right-2 w-3 h-3 rounded-full
                                {{ $vehicle->availability_status == 'available' ? 'bg-green-500' : '' }}
                                {{ $vehicle->availability_status == 'booked' ? 'bg-blue-500' : '' }}
                                {{ $vehicle->availability_status == 'maintenance' ? 'bg-orange-500' : '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $vehicle->name }}</h3>
                            @if($vehicle->vehicle_type == 'motorcycle')
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-red-100 rounded-full">
                                    <i data-lucide="bike" class="w-3.5 h-3.5 text-red-600"></i>
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 font-mono mb-2">{{ $vehicle->plate_no }}</p>
                        
                        <!-- Quick Details -->
                        <div class="flex items-center flex-wrap gap-4 text-sm text-gray-600 mb-2">
                            <span class="flex items-center gap-1">
                                <i data-lucide="palette" class="w-3.5 h-3.5"></i>
                                {{ $vehicle->color }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ $vehicle->year }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                {{ $vehicle->seating_capacity }} seats
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="fuel" class="w-3.5 h-3.5"></i>
                                {{ ucfirst($vehicle->fuel_type) }}
                            </span>
                        </div>
                        
                        <!-- Status & Price -->
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold
                                {{ $vehicle->availability_status == 'available' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $vehicle->availability_status == 'booked' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $vehicle->availability_status == 'maintenance' ? 'bg-orange-100 text-orange-700' : '' }}">
                                {{ ucfirst($vehicle->availability_status) }}
                            </span>
                            <span class="text-sm font-bold text-gray-900">
                                RM{{ number_format($vehicle->price_perHour, 2) }}/hour
                            </span>
                        </div>
                    </div>

                    <!-- Actions (Hidden by default, shown on hover) -->
                    <div class="flex items-center gap-3 transition-opacity duration-200"
                         :class="showActions ? 'opacity-100' : 'opacity-0'">
                        <!-- Quick Action Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('staff.vehicles.show', $vehicle->plate_no) }}" 
                               class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition"
                               title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('staff.vehicles.edit', $vehicle->plate_no) }}" 
                               class="w-10 h-10 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                               title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('staff.vehicles.destroy', $vehicle->plate_no) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                                        title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-16 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="car" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No vehicles found</h3>
                <p class="text-gray-600 mb-6">Add your first vehicle to get started</p>
                <a href="{{ route('staff.vehicles.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Add Vehicle
                </a>
            </div>
        @endforelse
    </div>

    @if($vehicles->hasPages())
        <div class="mt-6">
            {{ $vehicles->withQueryString()->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function toggleFilters() {
        document.getElementById('filters-panel').classList.toggle('hidden');
    }
    
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush
@endsection