{{-- resources/views/staff/vehicles/show.blade.php --}}
@extends('staff.layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
        <a href="{{ route('staff.dashboard') }}" class="hover:text-gray-900 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <a href="{{ route('staff.vehicles.index') }}" class="hover:text-gray-900 transition">Fleet</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-900 font-medium">{{ $vehicle->plate_no }}</span>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">{{ $vehicle->name }}</h2>
            <p class="text-gray-600 mt-1">{{ $vehicle->plate_no }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.vehicles.index') }}" 
               class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back
            </a>
            <a href="{{ route('staff.vehicles.edit', $vehicle->plate_no) }}" 
               class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Image & Quick Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                @if($vehicle->images)
                    @php 
                        $images = is_array($vehicle->images) ? $vehicle->images : json_decode($vehicle->images, true);
                    @endphp
                    @if(!empty($images))
                        <div class="relative">
                            @php $firstImage = reset($images); @endphp
                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $vehicle->name }}" 
                                 class="w-full h-64 object-cover" id="mainImage">
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1.5 rounded-full text-sm font-bold shadow-lg
                                    {{ $vehicle->availability_status == 'available' ? 'bg-green-500 text-white' : '' }}
                                    {{ $vehicle->availability_status == 'booked' ? 'bg-blue-500 text-white' : '' }}
                                    {{ $vehicle->availability_status == 'maintenance' ? 'bg-orange-500 text-white' : '' }}">
                                    {{ ucfirst($vehicle->availability_status) }}
                                </span>
                            </div>
                        </div>
                        @if(count($images) > 1)
                            <div class="p-3 grid grid-cols-4 gap-2 bg-gray-50">
                                @foreach($images as $key => $image)
                                    <button onclick="changeImage('{{ asset('storage/' . $image) }}')" 
                                            class="aspect-square rounded-lg overflow-hidden border-2 border-gray-200 hover:border-red-500 transition">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                            <i data-lucide="car" class="w-16 h-16 text-gray-300"></i>
                        </div>
                    @endif
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                        <i data-lucide="car" class="w-16 h-16 text-gray-300"></i>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-red-600"></i>
                    Quick Stats
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Type</span>
                        <span class="font-semibold capitalize">{{ $vehicle->vehicle_type }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Year</span>
                        <span class="font-semibold">{{ $vehicle->year }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Transmission</span>
                        <span class="font-semibold capitalize">{{ $vehicle->transmission }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Fuel</span>
                        <span class="font-semibold capitalize">{{ $vehicle->fuel_type }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Seats</span>
                        <span class="font-semibold">{{ $vehicle->seating_capacity }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Distance Travelled</span>
                        <span class="font-semibold">{{ number_format($vehicle->distance_travelled ?? 0) }} km</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-5 text-white">
                <p class="text-sm text-red-100 mb-1">Hourly Rate</p>
                <p class="text-3xl font-bold mb-2">RM {{ number_format($vehicle->price_perHour, 2) }}</p>
                <p class="text-sm text-red-100">Daily: RM {{ number_format($vehicle->price_perHour * 24, 2) }}</p>
            </div>
        </div>

        <!-- Right: Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-red-600"></i>
                    Vehicle Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 mb-1">Plate Number</p>
                        <p class="font-semibold text-lg">{{ $vehicle->plate_no }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Color</p>
                        <p class="font-semibold text-lg">{{ $vehicle->color }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Vehicle Name</p>
                        <p class="font-semibold text-lg">{{ $vehicle->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Roadtax Expiry</p>
                        <p class="font-semibold text-lg">{{ \Carbon\Carbon::parse($vehicle->roadtax_expiry)->format('d M Y') }}</p>
                        @if(\Carbon\Carbon::parse($vehicle->roadtax_expiry)->isPast())
                            <span class="text-xs text-red-600 font-medium mt-1">⚠️ Expired</span>
                        @elseif(\Carbon\Carbon::parse($vehicle->roadtax_expiry)->diffInDays(now()) < 30)
                            <span class="text-xs text-orange-600 font-medium mt-1">⚠️ Expiring soon</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($vehicle->features && !empty(json_decode($vehicle->features, true)))
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-red-600"></i>
                        Features
                    </h3>
                    @php $features = json_decode($vehicle->features, true); @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($features as $feature)
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="check" class="w-3 h-3 text-red-600"></i>
                                </div>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($vehicle->description)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $vehicle->description }}</p>
                </div>
            @endif

            @if($vehicle->maintenance_notes)
                <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-6">
                    <h3 class="font-bold text-orange-900 mb-3 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        Maintenance Notes
                    </h3>
                    <p class="text-orange-800">{{ $vehicle->maintenance_notes }}</p>
                </div>
            @endif

            <!-- Maintenance History (Optional - You can add this later) -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-red-600"></i>
                    Vehicle History
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            <span>Added to fleet</span>
                        </div>
                        <span class="text-gray-600">{{ $vehicle->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            <span>Last updated</span>
                        </div>
                        <span class="text-gray-600">{{ $vehicle->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function changeImage(src) { 
        document.getElementById('mainImage').src = src; 
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