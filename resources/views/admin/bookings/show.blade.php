<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - HASTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .detail-box {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            width: 100%;
            color: #374151;
            font-weight: 500;
            min-height: 40px;
            display: flex;
            align-items: center;
        }
        .label-text {
            font-weight: 700;
            color: #1f2937;
            width: 250px;
            flex-shrink: 0;
            display: inline-block;
        }
    </style>
</head>
<body class="bg-[#db3c39] font-sans overflow-x-hidden">

    <header class="flex items-center justify-between px-10 py-4 text-white">
        <div class="bg-white px-3 py-1 rounded shadow-md">
            <span class="text-[#db3c39] font-black text-sm">HASTA</span>
        </div>
        <div class="flex space-x-8 items-center font-bold text-sm">
            <a href="{{ route('admin.dashboard') }}" class="opacity-70 hover:opacity-100">Home</a>
            <a href="#" class="opacity-70">Notifications</a>
            <a href="{{ route('admin.dashboard') }}" class="opacity-70">Dashboard</a>
            <a href="{{ route('admin.bookings.index') }}" class="border-b-2 border-white pb-1">Booking Management</a>
        </div>
        <div class="flex items-center gap-3">
             <span class="font-bold text-sm">{{ Auth::user()->name }}</span>
             <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fff&color=db3c39" class="w-10 h-10 rounded-full border-2 border-white">
        </div>
    </header>

    <main class="px-10 pb-10">
        <div class="bg-white rounded-[40px] shadow-2xl p-10 max-w-5xl mx-auto">
            
            <a href="{{ route('admin.bookings.index') }}" class="text-gray-900 mb-6 inline-block">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>

            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-gray-900 mb-6">{{ $booking->car->full_name ?? $booking->car->model_name }}</h1>
                <div class="bg-white rounded-[30px] shadow-lg p-4 inline-block border border-gray-100">
                    @if($booking->car && $booking->car->image)
                        <img src="{{ $booking->car->image }}" 
                             alt="{{ $booking->car->full_name ?? $booking->car->model_name }}" 
                             class="w-[500px] h-[300px] object-contain"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-[500px] h-[250px] bg-gray-200 flex items-center justify-center rounded-xl\'><span class=\'text-gray-500\'>Image not found</span></div>'">
                    @else
                        <div class="w-[500px] h-[250px] bg-gray-200 flex items-center justify-center rounded-xl">
                            <span class="text-gray-500">No Image Available</span>
                        </div>
                    @endif
                </div>
            </div>

            <h2 class="text-3xl font-black text-gray-900 text-center mb-12">Booking Details</h2>

            <div class="space-y-4 px-10">
                <div class="flex items-center">
                    <span class="label-text">Full Name</span>
                    <div class="detail-box">{{ $booking->user->name ?? 'N/A' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Identity Card Number</span>
                    <div class="detail-box">{{ $booking->user->ic ?? 'N/A' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Phone Number</span>
                    <div class="detail-box">{{ $booking->user->phone ?? 'N/A' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Email Address</span>
                    <div class="detail-box">{{ $booking->user->email ?? 'N/A' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Destination</span>
                    <div class="detail-box">{{ $booking->destination ?? 'N/A' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Pickup Location</span>
                    <div class="detail-box">{{ $booking->pickup_location }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Return Location</span>
                    <div class="detail-box">{{ $booking->dropoff_location }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Pickup Date & Time</span>
                    <div class="detail-box">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}, {{ $booking->pickup_time }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Return Date & Time</span>
                    <div class="detail-box">{{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}, {{ $booking->return_time }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Rental Duration (Days)</span>
                    <div class="detail-box">{{ $booking->duration }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Deposit (RM)</span>
                    <div class="detail-box">RM {{ number_format($booking->deposit_amount ?? 0, 2) }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Price (RM)</span>
                    <div class="detail-box">RM {{ number_format($booking->base_price ?? 0, 2) }}</div>
                </div>
                <div class="flex items-center">
                    <span class="label-text">Total Payment (RM)</span>
                    <div class="detail-box font-bold text-gray-900">RM {{ number_format($booking->total_price, 2) }}</div>
                </div>
            </div>

            @if(strtolower($booking->status) === 'pending')
            <div class="flex justify-center gap-8 mt-16">
                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                    @csrf 
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="border-2 border-[#f27041] text-[#f27041] px-16 py-3 rounded-2xl font-black text-xl hover:bg-orange-50 transition-all">
                        Reject
                    </button>
                </form>

                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                    @csrf 
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="bg-[#f27041] text-white px-16 py-3 rounded-2xl font-black text-xl hover:bg-[#e66030] shadow-xl shadow-orange-200 transition-all">
                        Approve
                    </button>
                </form>
            </div>
            @else
            <div class="text-center mt-12">
                @php
                    $statusColors = [
                        'confirmed' => 'text-green-500',
                        'cancelled' => 'text-red-500',
                        'completed' => 'text-blue-500',
                    ];
                    $statusColor = $statusColors[strtolower($booking->status)] ?? 'text-gray-500';
                @endphp
                <span class="text-2xl font-black uppercase {{ $statusColor }}">
                    STATUS: {{ strtoupper($booking->status) }}
                </span>
            </div>
            @endif
        </div>
    </main>

    <footer class="bg-[#db3c39] text-white py-12 px-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div>
                <div class="bg-white inline-block px-3 py-1 rounded mb-6">
                    <span class="text-[#db3c39] font-black">HASTA</span>
                </div>
                <div class="flex space-x-4 mt-4">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="space-y-4 text-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt mt-1"></i>
                    <p>Address<br>Student Mall UTM<br>Skudai, 81300, Johor Bahru</p>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-envelope"></i>
                    <p>hastatravel@gmail.com</p>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-phone"></i>
                    <p>011-1090 0700</p>
                </div>
            </div>

            <div>
                <h4 class="font-bold mb-4">Useful links</h4>
                <ul class="text-xs space-y-2 opacity-80">
                    <li>About us</li><li>Contact us</li><li>Gallery</li><li>Blog</li><li>F.A.Q</li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-4">Vehicles</h4>
                <ul class="text-xs space-y-2 opacity-80">
                    <li>Sedan</li><li>Hatchback</li><li>MPV</li><li>Minivan</li><li>SUV</li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>