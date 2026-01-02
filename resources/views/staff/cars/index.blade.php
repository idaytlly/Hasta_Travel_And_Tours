{{-- resources/views/staff/cars/index.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management - Hasta Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .header-gradient {
            background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%);
        }
        .footer-gradient {
            background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%);
        }
        .floating-add-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #E0E0E0 0%, #BDBDBD 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.3s;
            z-index: 100;
        }
        .floating-add-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
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
        .brand-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Header -->
<header class="header-gradient text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <h1 class="text-2xl font-bold tracking-wider">HASTA</h1>
            </a>

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
                <a href="{{ route('staff.cars.index') }}" class="bg-red-400 bg-opacity-50 px-4 py-2 rounded-lg hover:bg-opacity-70 transition">
                    <i class="fas fa-car"></i> Vehicle Management
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-clock text-xl"></i>
                </a>
                <a href="#" class="hover:text-gray-200 transition">
                    <i class="fas fa-cog text-xl"></i>
                </a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-lg text-white font-semibold transition">
                    Login
                </a>
                <img src="https://ui-avatars.com/api/?name=Staff&background=FF6B35&color=fff" 
                     alt="Profile" 
                     class="w-12 h-12 rounded-full border-2 border-white">
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif



    <!-- Category Filters -->
    <div class="flex justify-center gap-3 mb-8 flex-wrap">
        <a href="{{ route('staff.cars.index') }}" 
           class="px-6 py-2 rounded-full {{ !request('type') ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border border-gray-200' }} font-semibold hover:border-orange-500 transition">
            All vehicles
        </a>
        <a href="{{ route('staff.cars.index', ['type' => 'sedan']) }}" 
           class="px-6 py-2 rounded-full {{ request('type') == 'sedan' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border border-gray-200' }} font-semibold hover:border-orange-500 transition">
            🚗 Sedan
        </a>
        <a href="{{ route('staff.cars.index', ['type' => 'hatchback']) }}" 
           class="px-6 py-2 rounded-full {{ request('type') == 'hatchback' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border border-gray-200' }} font-semibold hover:border-orange-500 transition">
            🚙 Hatchback
        </a>
        <a href="{{ route('staff.cars.index', ['type' => 'mpv']) }}" 
           class="px-6 py-2 rounded-full {{ request('type') == 'mpv' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border border-gray-200' }} font-semibold hover:border-orange-500 transition">
            🚐 MPV
        </a>
        <a href="{{ route('staff.cars.index', ['type' => 'suv']) }}" 
           class="px-6 py-2 rounded-full {{ request('type') == 'suv' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border border-gray-200' }} font-semibold hover:border-orange-500 transition">
            🚙 SUV
        </a>
    </div>

    <!-- Car Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cars as $car)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                <!-- Car Image -->
                <div class="relative h-56 bg-gray-100">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}"
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="w-full h-full object-contain p-4">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-car text-gray-300 text-6xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Car Details -->
                <div class="p-5">
                    <!-- Title and Type -->
                    <div class="mb-3">
                        <h3 class="font-bold text-lg">{{ $car->brand }} {{ $car->model }} {{ $car->year }}</h3>
                        <p class="text-sm text-gray-500 capitalize">{{ $car->carType ?? 'Hatchback' }}</p>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <p class="text-orange-500 font-bold text-2xl">
                            RM{{ number_format($car->daily_rate, 0) }}
                            <span class="text-sm text-gray-500 font-normal">per day</span>
                        </p>
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
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('staff.cars.edit', $car->id) }}" 
                           class="flex-1 bg-orange-500 hover:bg-orange-600 text-white text-center py-3 rounded-lg font-semibold transition">
                            Edit Car
                        </a>
                        <form action="{{ route('staff.cars.destroy', $car->id) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete {{ $car->brand }} {{ $car->model }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-100 hover:bg-red-200 text-red-600 px-4 py-3 rounded-lg transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-car text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No cars available</h3>
                <p class="text-gray-500 mb-4">Start by adding your first vehicle</p>
                <a href="{{ route('staff.cars.create') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Add New Car
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Floating Add Button -->
<a href="{{ route('staff.cars.create') }}" class="floating-add-btn" title="Add New Car">
    <i class="fas fa-plus"></i>
</a>

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
                    <li><a href="#" class="hover:opacity-100 transition">Sedan</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">Hatchback</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">MPV</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">Minivan</a></li>
                    <li><a href="#" class="hover:opacity-100 transition">SUV</a></li>
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

</body>
</html>