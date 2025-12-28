<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - Car Listings</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Red gradient header */
        .header-gradient {
            background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%);
        }

        /* Brand filter buttons */
        .brand-btn {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .brand-btn:hover, .brand-btn.active {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .brand-btn img {
            max-width: 60%;
            max-height: 60%;
            object-fit: contain;
        }

        /* Car card styles */
        .car-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .car-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f5f5f5;
        }
        
        /* Orange button */
        .btn-orange {
            background: #FF6B35;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-orange:hover {
            background: #E85A2A;
            transform: scale(1.02);
        }

        /* Category filter pills */
        .filter-pill {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .filter-pill.active {
            background: #FF6B35;
            color: white;
        }
        .filter-pill:not(.active) {
            background: white;
            color: #666;
            border-color: #e0e0e0;
        }
        .filter-pill:not(.active):hover {
            border-color: #FF6B35;
            color: #FF6B35;
        }

        /* Footer */
        .footer-gradient {
            background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%);
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Header -->
<header class="header-gradient text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <h1 class="text-2xl font-bold tracking-wider">HASTA</h1>
            </a>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="hover:text-gray-200 transition">
                    <i class="fas fa-home text-xl"></i>
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-bell text-xl"></i>
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-th text-xl"></i>
                </a>
                <a href="{{ route('cars.index') }}" class="bg-red-400 bg-opacity-50 px-4 py-2 rounded-lg hover:bg-opacity-70 transition">
                    <i class="fas fa-car"></i> Vehicles
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-clock text-xl"></i>
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-cog text-xl"></i>
                </a>
            </nav>

            <!-- Login/Profile -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                                 alt="Profile" 
                                 class="w-10 h-10 rounded-full border-2 border-white">
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 px-5 py-2 rounded-lg text-white font-semibold transition">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-lg text-white font-semibold transition">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <!-- Brand Filter -->
    <div class="flex justify-center gap-4 mb-8 flex-wrap">
        <button class="brand-btn {{ !request('brand') ? 'active' : '' }}" onclick="filterByBrand('')">
            <span class="text-2xl">🚗</span>
        </button>
        @if($brands && $brands->count() > 0)
            @foreach($brands as $brand)
                <button class="brand-btn {{ request('brand') == strtolower($brand) ? 'active' : '' }}" 
                        onclick="filterByBrand('{{ strtolower($brand) }}')">
                    <span class="font-bold text-sm">{{ strtoupper(substr($brand, 0, 3)) }}</span>
                </button>
            @endforeach
        @endif
    </div>

    <!-- Category Filters -->
    <div class="flex justify-between items-center mb-8 flex-wrap gap-3">
        <div class="flex gap-2 flex-wrap">
            <button class="filter-pill {{ !request('type') ? 'active' : '' }}" onclick="filterByType('')">
                All vehicles
            </button>
            <button class="filter-pill {{ request('type') == 'sedan' ? 'active' : '' }}" onclick="filterByType('sedan')">
                🚗 Sedan
            </button>
            <button class="filter-pill {{ request('type') == 'hatchback' ? 'active' : '' }}" onclick="filterByType('hatchback')">
                🚙 Hatchback
            </button>
            <button class="filter-pill {{ request('type') == 'mpv' ? 'active' : '' }}" onclick="filterByType('mpv')">
                🚐 MPV
            </button>
            <button class="filter-pill {{ request('type') == 'suv' ? 'active' : '' }}" onclick="filterByType('suv')">
                🚙 SUV
            </button>
        </div>
        <button class="filter-pill" onclick="toggleFilters()">
            🔽 Filter
        </button>
    </div>

    <!-- Car Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($cars as $car)
            <div class="car-card">
                <!-- Car Image -->
                <div class="relative">
                    @if($car->image)
                        <img src="{{ asset('car_images/' . $car->image) }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="car-image">
                    @else
                        <div class="car-image flex items-center justify-center bg-gray-200">
                            <i class="fas fa-car text-6xl text-gray-400"></i>
                        </div>
                    @endif
                </div>

                <!-- Car Details -->
                <div class="p-4">
                    <!-- Title and Price -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-lg">{{ $car->brand }} {{ $car->model }} {{ $car->year }}</h3>
                            <p class="text-sm text-gray-600 capitalize">{{ $car->carType ?? 'Hatchback' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-orange-500 font-bold text-xl">RM{{ number_format($car->daily_rate, 0) }}</p>
                            <p class="text-xs text-gray-500">per day</p>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-cog"></i>
                            <span>{{ ucfirst($car->transmission ?? 'Automat') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-gas-pump"></i>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-snowflake"></i>
                            <span>AC</span>
                        </div>
                    </div>

                    <!-- Availability Badge -->
                    @if($car->is_available)
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full mb-3">
                            Available
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full mb-3">
                            Rented
                        </span>
                    @endif

                    <!-- View Details Button -->
                    @if($car->is_available)
                        <a href="{{ route('booking.create', $car->id) }}" class="btn-orange w-full block text-center">
                            View Details
                        </a>
                    @else
                        <button class="btn-orange w-full opacity-50 cursor-not-allowed" disabled>
                            Currently Rented
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-car text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No cars available at the moment.</p>
                <a href="{{ route('cars.index') }}" class="btn-orange inline-block mt-4">View All Cars</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($cars->hasPages())
    <div class="flex justify-center mt-8">
        {{ $cars->links() }}
    </div>
    @endif
</div>

<!-- Footer -->
<footer class="footer-gradient text-white mt-16">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Logo and Contact -->
            <div>
                <h2 class="text-2xl font-bold mb-4">HASTA</h2>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1"></i>
                        <div>
                            <p class="font-semibold">Address</p>
                            <p class="text-sm opacity-90">Student Mall UTM</p>
                            <p class="text-sm opacity-90">Skudai, 81300, Johor Bahru</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <p class="font-semibold">Email</p>
                            <p class="text-sm opacity-90">hastatravel@gmail.com</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-phone"></i>
                        <div>
                            <p class="font-semibold">Phone</p>
                            <p class="text-sm opacity-90">011-1090 0700</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Useful Links -->
            <div>
                <h3 class="font-bold text-lg mb-4">Useful Links</h3>
                <ul class="space-y-2 text-sm opacity-90">
                    <li><a href="#" class="hover:opacity-100 transition">About us</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">Contact us</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">Gallery</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">Blog</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">F.A.Q</a></li>
                </ul>
            </div>

            <!-- Vehicles -->
            <div>
                <h3 class="font-bold text-lg mb-4">Vehicles</h3>
                <ul class="space-y-2 text-sm opacity-90">
                    <li><a href="{{ route('cars.index', ['type' => 'sedan']) }}" class="hover:opacity-100 transition">Sedan</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'hatchback']) }}" class="hover:opacity-100 transition">Hatchback</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'mpv']) }}" class="hover:opacity-100 transition">MPV</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'minivan']) }}" class="hover:opacity-100 transition">Minivan</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'suv']) }}" class="hover:opacity-100 transition">SUV</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h3 class="font-bold text-lg mb-4">Follow Us</h3>
                <div class="flex gap-3">
                    <a href="#" class="bg-white text-red-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="bg-white text-red-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="bg-white text-red-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="bg-white text-red-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-red-400 border-opacity-50 mt-8 pt-8 text-center text-sm opacity-90">
            <p>&copy; {{ date('Y') }} Hasta Travel & Tours. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    function filterByType(type) {
        const url = new URL(window.location.href);
        if (type) {
            url.searchParams.set('type', type);
        } else {
            url.searchParams.delete('type');
        }
        window.location.href = url.toString();
    }

    function filterByBrand(brand) {
        const url = new URL(window.location.href);
        if (brand) {
            url.searchParams.set('brand', brand);
        } else {
            url.searchParams.delete('brand');
        }
        window.location.href = url.toString();
    }

    function toggleFilters() {
        // Add advanced filters modal/dropdown functionality here
        alert('Advanced filters coming soon!');
    }
</script>

</body>
</html>