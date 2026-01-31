@extends('staff.layouts.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')
@section('page-subtitle', 'View and manage booking information')

@section('page-header')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-0">Booking: {{ $booking->booking_id }}</h1>
            <p class="text-muted mb-0">Created: {{ $booking->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.bookings') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $booking->booking_id }}</h2>
                <p class="text-sm text-gray-600 mt-1">Created: {{ $booking->created_at->format('M d, Y H:i') }}</p>
            </div>
            <a href="{{ route('staff.bookings') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Side -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status & Price Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Information</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <div class="mt-2">
                            @if($booking->booking_status == 'pending')
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-full font-medium">Pending</span>
                            @elseif($booking->booking_status == 'confirmed')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded-full font-medium">Confirmed</span>
                            @elseif($booking->booking_status == 'active')
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full font-medium">Active</span>
                            @elseif($booking->booking_status == 'completed')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full font-medium">Completed</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full font-medium">Cancelled</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Total Price</label>
                        <p class="text-2xl font-bold text-gray-800 mt-2">RM {{ number_format($booking->total_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Approved By</label>
                        <div class="mt-2">
                            @if($booking->approvedByStaff ?? false)
                                <p class="font-medium text-gray-800">{{ $booking->approvedByStaff->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->approved_at->format('M d, Y') }}</p>
                            @else
                                <p class="text-gray-500">Not approved yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    Customer Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Name</label>
                        <p class="font-medium text-gray-800 mt-1">{{ $booking->customer->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="font-medium text-gray-800 mt-1">{{ $booking->customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phone</label>
                        <p class="font-medium text-gray-800 mt-1">{{ $booking->customer->phone_no }}</p>
                    </div>
                    @if($booking->customer->license_no ?? false)
                    <div>
                        <label class="text-sm text-gray-600">License No</label>
                        <p class="font-medium text-gray-800 mt-1">{{ $booking->customer->license_no }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="car" class="w-5 h-5"></i>
                    Vehicle Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Vehicle</label>
                        <p class="font-medium text-gray-800 mt-1">{{ $booking->vehicle->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Plate Number</label>
                        <p class="font-medium text-gray-800 mt-1">{{ $booking->vehicle->plate_no }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Rate</label>
                        <p class="font-medium text-gray-800 mt-1">RM {{ number_format($booking->vehicle->price_perHour, 2) }}/hour</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Details</label>
                        <p class="font-medium text-gray-800 mt-1">{{ ucfirst($booking->vehicle->fuel_type) }} • {{ ucfirst($booking->vehicle->transmission) }}</p>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Rental Period
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Pickup</label>
                        <p class="font-medium text-gray-800 mt-1">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('l, M d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->pickup_time }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Return</label>
                        <p class="font-medium text-gray-800 mt-1">{{ \Carbon\Carbon::parse($booking->return_date)->format('l, M d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->return_time }}</p>
                    </div>
                </div>
            </div>

            @if($booking->special_requests)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Special Requests</h3>
                <p class="text-gray-700">{{ $booking->special_requests }}</p>
            </div>
            @endif
        </div>

        <!-- Right Sidebar - Actions -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Actions</h3>
                <div class="space-y-3">
                    @if($booking->booking_status == 'pending')
                    <form method="POST" action="{{ route('staff.bookings.approve', $booking->booking_id) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Approve Booking
                        </button>
                    </form>
                    @endif

                    @if($booking->booking_status == 'confirmed')
                    <form method="POST" action="{{ route('staff.bookings.mark-active', $booking->booking_id) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="play" class="w-4 h-4"></i>
                            Mark as Active
                        </button>
                    </form>
                    @endif

                    @if($booking->booking_status == 'active')
                    <form method="POST" action="{{ route('staff.bookings.mark-completed', $booking->booking_id) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Mark as Completed
                        </button>
                    </form>
                    @endif

                    @if(in_array($booking->booking_status, ['pending', 'confirmed']))
                    <button onclick="alert('Edit functionality coming soon')" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit Booking
                    </button>
                    
                    <form method="POST" action="{{ route('staff.bookings.cancel', $booking->booking_id) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Cancel Booking
                        </button>
                    </form>
                    @endif

                    @if($booking->booking_status == 'pending')
                    <form method="POST" action="{{ route('staff.bookings.destroy', $booking->booking_id) }}" onsubmit="return confirm('Are you sure you want to delete this booking?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-gray-100 text-red-600 rounded-lg hover:bg-gray-200 transition flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Delete Booking
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Payments Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payments</h3>
                @if($booking->payments->count() > 0)
                <div class="space-y-3">
                    @foreach($booking->payments as $payment)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">RM {{ number_format($payment->amount, 2) }}</p>
                                <p class="text-sm text-gray-600">{{ ucfirst($payment->payment_method) }}</p>
                                @if($payment->verified_at)
                                <p class="text-xs text-gray-500 mt-1">Verified {{ $payment->verified_at->format('M d') }}</p>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $payment->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </div>
                        @if($payment->payment_notes)
                        <p class="text-sm text-gray-500 mt-2">{{ $payment->payment_notes }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No payments recorded</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
@endpush
@endsection