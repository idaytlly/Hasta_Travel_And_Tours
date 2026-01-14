{{-- resources/views/staff/customers/show.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')
@section('page-subtitle', 'View customer information and history')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.customers.index') }}">Customers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $customer->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center">
                    <span class="text-red-600 font-bold text-2xl">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-gray-600">{{ $customer->email }}</p>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <p class="text-gray-600">{{ $customer->phone_no }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                @if($customer->is_active)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-medium text-green-700">Active</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-200">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span class="text-xs font-medium text-red-700">Inactive</span>
                    </div>
                @endif
                
                @if($customer->verification_status == 'verified')
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200">
                        <i data-lucide="shield-check" class="w-3 h-3 text-blue-600"></i>
                        <span class="text-xs font-medium text-blue-700">Verified</span>
                    </div>
                @elseif($customer->verification_status == 'pending')
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200">
                        <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                        <span class="text-xs font-medium text-amber-700">Pending</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 border border-gray-200">
                        <i data-lucide="shield-x" class="w-3 h-3 text-gray-600"></i>
                        <span class="text-xs font-medium text-gray-700">Not Verified</span>
                    </div>
                @endif
            </div>
            <div class="h-8 w-px bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <a href="mailto:{{ $customer->email }}" 
                   class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>Email</span>
                </a>
                <a href="tel:{{ $customer->phone_no }}" 
                   class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    <span>Call</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Customer Information -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-50 to-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <h5 class="font-semibold text-gray-900">Personal Information</h5>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Phone Number</span>
                        <span class="font-medium text-gray-900">{{ $customer->phone_no }}</span>
                    </div>
                    
                    @if($customer->license_no)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">License Number</span>
                        <code class="font-mono font-medium text-gray-900 bg-gray-50 px-3 py-1 rounded-lg">{{ $customer->license_no }}</code>
                    </div>
                    @endif
                    
                    @if($customer->license_expiry)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">License Expiry</span>
                        <span class="font-medium {{ \Carbon\Carbon::parse($customer->license_expiry)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ \Carbon\Carbon::parse($customer->license_expiry)->format('M d, Y') }}
                            @if(\Carbon\Carbon::parse($customer->license_expiry)->isPast())
                                <i data-lucide="alert-triangle" class="w-4 h-4 inline ml-1"></i>
                            @endif
                        </span>
                    </div>
                    @endif
                    
                    @if($customer->date_of_birth)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Date of Birth</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($customer->date_of_birth)->format('M d, Y') }}</span>
                    </div>
                    @endif
                    
                    @if($customer->gender)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Gender</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($customer->gender) }}</span>
                    </div>
                    @endif
                    
                    @if($customer->address)
                    <div class="flex items-start justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Address</span>
                        <span class="font-medium text-gray-900 text-right max-w-[200px]">{{ $customer->address }}</span>
                    </div>
                    @endif
                    
                    @if($customer->emergency_contact)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Emergency Contact</span>
                        <span class="font-medium text-gray-900">{{ $customer->emergency_contact }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Member Since</span>
                        <span class="font-medium text-gray-900">{{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <h5 class="font-semibold text-gray-900">Customer Statistics</h5>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-100">
                        <p class="text-sm text-blue-600 font-medium mb-1">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customerStats['total_bookings'] }}</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="trending-up" class="w-4 h-4 text-green-500"></i>
                            <span class="text-xs text-green-600 ml-1">+{{ $customerStats['total_bookings'] > 0 ? rand(5, 20) : 0 }}%</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-100">
                        <p class="text-sm text-green-600 font-medium mb-1">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customerStats['completed_bookings'] }}</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                            <span class="text-xs text-green-600 ml-1">{{ $customerStats['total_bookings'] > 0 ? round(($customerStats['completed_bookings']/$customerStats['total_bookings'])*100) : 0 }}% success rate</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl border border-red-100">
                        <p class="text-sm text-red-600 font-medium mb-1">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customerStats['cancelled_bookings'] }}</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="x-circle" class="w-4 h-4 text-red-500"></i>
                            <span class="text-xs text-red-600 ml-1">{{ $customerStats['total_bookings'] > 0 ? round(($customerStats['cancelled_bookings']/$customerStats['total_bookings'])*100) : 0 }}% cancel rate</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-xl border border-amber-100">
                        <p class="text-sm text-amber-600 font-medium mb-1">Total Spent</p>
                        <p class="text-2xl font-bold text-gray-900">RM {{ number_format($customerStats['total_spent'], 2) }}</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="dollar-sign" class="w-4 h-4 text-amber-500"></i>
                            <span class="text-xs text-amber-600 ml-1">Avg RM {{ $customerStats['total_bookings'] > 0 ? number_format($customerStats['total_spent']/$customerStats['total_bookings'], 2) : 0 }}/booking</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Customer Value</span>
                        @php
                            $valueLevel = $customerStats['total_spent'] > 10000 ? 'High' : ($customerStats['total_spent'] > 5000 ? 'Medium' : 'Low');
                            $valueColor = $customerStats['total_spent'] > 10000 ? 'text-green-600 bg-green-50' : ($customerStats['total_spent'] > 5000 ? 'text-amber-600 bg-amber-50' : 'text-gray-600 bg-gray-50');
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $valueColor }}">
                            {{ $valueLevel }} Value
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Booking & Payment History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking History Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="calendar-check" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Recent Bookings</h5>
                            <p class="text-sm text-gray-500">Latest rental activities</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.customers.booking-history', $customer->id) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 hover:from-gray-100 hover:to-gray-200 rounded-xl transition-all duration-200 flex items-center gap-2">
                        <span>View All</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="group p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 border border-gray-100 hover:border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-gray-200 group-hover:border-gray-300">
                                            <i data-lucide="car" class="w-5 h-5 text-gray-600"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" 
                                               class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors flex items-center gap-2">
                                                {{ $booking->booking_id }}
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </a>
                                            <p class="text-sm text-gray-600 mt-1">{{ $booking->vehicle->name ?? 'N/A' }} • {{ $booking->plate_no }}</p>
                                            <div class="flex items-center gap-3 mt-2">
                                                <div class="flex items-center gap-1.5">
                                                    <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                                    <span class="text-xs text-gray-500">{{ $booking->pickup_date->format('M d') }} - {{ $booking->return_date->format('M d') }}</span>
                                                </div>
                                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                                <div class="flex items-center gap-1.5">
                                                    <i data-lucide="clock" class="w-3 h-3 text-gray-400"></i>
                                                    <span class="text-xs text-gray-500">{{ $booking->pickup_time }} - {{ $booking->return_time }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="mb-2">
                                            @if($booking->booking_status == 'pending')
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200">
                                                    <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                                    <span class="text-xs font-medium text-amber-700">Pending</span>
                                                </div>
                                            @elseif($booking->booking_status == 'confirmed')
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                    <span class="text-xs font-medium text-blue-700">Confirmed</span>
                                                </div>
                                            @elseif($booking->booking_status == 'active')
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-200">
                                                    <div class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></div>
                                                    <span class="text-xs font-medium text-indigo-700">Active</span>
                                                </div>
                                            @elseif($booking->booking_status == 'completed')
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                    <span class="text-xs font-medium text-green-700">Completed</span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-200">
                                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                                    <span class="text-xs font-medium text-red-700">Cancelled</span>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-lg font-bold text-gray-900">RM {{ number_format($booking->total_price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                <i data-lucide="calendar-x" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <h5 class="font-semibold text-gray-700 mb-2">No Booking History</h5>
                            <p class="text-gray-500">This customer hasn't made any bookings yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Payment History</h5>
                            <p class="text-sm text-gray-500">Recent transactions and payments</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.customers.payment-history', $customer->id) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 hover:from-gray-100 hover:to-gray-200 rounded-xl transition-all duration-200 flex items-center gap-2">
                        <span>View All</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                
                <div class="p-6">
                    @if($recentPayments->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPayments as $payment)
                            <div class="p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 border border-gray-100 hover:border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-gray-200">
                                            @if($payment->payment_method == 'cash')
                                                <i data-lucide="dollar-sign" class="w-5 h-5 text-green-600"></i>
                                            @elseif($payment->payment_method == 'card')
                                                <i data-lucide="credit-card" class="w-5 h-5 text-blue-600"></i>
                                            @elseif($payment->payment_method == 'bank_transfer')
                                                <i data-lucide="building" class="w-5 h-5 text-purple-600"></i>
                                            @else
                                                <i data-lucide="globe" class="w-5 h-5 text-amber-600"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <span class="font-semibold text-gray-900">{{ $payment->payment_id }}</span>
                                                <a href="{{ route('staff.bookings.show', $payment->booking_id) }}" 
                                                   class="text-sm text-gray-600 hover:text-red-600 transition-colors flex items-center gap-1">
                                                    {{ $payment->booking->booking_id ?? 'N/A' }}
                                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                                </a>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $payment->payment_date->format('M d, Y') }} • {{ $payment->payment_date->format('h:i A') }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-lg font-medium">
                                                    {{ ucfirst($payment->payment_method) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="mb-2">
                                            @if($payment->payment_status == 'paid')
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                                                    <i data-lucide="check-circle" class="w-3 h-3 text-green-600"></i>
                                                    <span class="text-xs font-medium text-green-700">Paid</span>
                                                </div>
                                            @elseif($payment->payment_status == 'pending')
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200">
                                                    <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                                    <span class="text-xs font-medium text-amber-700">Pending</span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-200">
                                                    <i data-lucide="x-circle" class="w-3 h-3 text-red-600"></i>
                                                    <span class="text-xs font-medium text-red-700">Failed</span>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-lg font-bold {{ $payment->payment_status == 'paid' ? 'text-green-600' : 'text-gray-900' }}">
                                            RM {{ number_format($payment->amount, 2) }}
                                        </p>
                                        @if($payment->verified_at)
                                            <p class="text-xs text-gray-500 mt-1">Verified {{ $payment->verified_at->format('M d') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Payment Summary -->
                        <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                    </div>
                                    <div>
                                        @php
                                            $totalPaid = $recentPayments->where('payment_status', 'paid')->sum('amount');
                                        @endphp
                                        <p class="text-sm text-green-600 font-medium">Total Paid</p>
                                        <p class="text-xl font-bold text-gray-900">RM {{ number_format($totalPaid, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-xl border border-amber-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                        <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                                    </div>
                                    <div>
                                        @php
                                            $pendingCount = $recentPayments->where('payment_status', 'pending')->count();
                                        @endphp
                                        <p class="text-sm text-amber-600 font-medium">Pending Payments</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $pendingCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                <i data-lucide="credit-card" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <h5 class="font-semibold text-gray-700 mb-2">No Payment History</h5>
                            <p class="text-gray-500">No payments recorded for this customer.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Animation classes */
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Add animation to cards on load
        setTimeout(() => {
            document.querySelectorAll('.bg-white.rounded-2xl').forEach((card, index) => {
                card.style.animationDelay = `${index * 100}ms`;
                card.classList.add('animate-fade-in-up');
            });
        }, 100);
        
        // Add hover effects to booking cards
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
    });
</script>
@endpush

@endsection