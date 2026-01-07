@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        
        <!-- Back Button -->
        <a href="{{ route('cars.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Back to Cars
        </a>

        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Book Your Car</h1>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Side - Car Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                    <!-- Car Image -->
                    <div class="mb-4">
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}" 
                                 alt="{{ $car->brand }} {{ $car->model }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-6xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Car Info -->
                    <h2 class="text-xl font-bold mb-2">{{ $car->brand }} {{ $car->model }}</h2>
                    <p class="text-gray-600 mb-4">{{ $car->year }} • {{ ucfirst($car->carType ?? 'Car') }}</p>

                    <!-- Features -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-cog w-5"></i>
                            <span>{{ ucfirst($car->transmission ?? 'Automatic') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-id-card w-5"></i>
                            <span>{{ $car->plateNo }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-snowflake w-5"></i>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Daily Rate:</span>
                            <span class="text-2xl font-bold text-orange-500">RM{{ number_format($car->daily_rate, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Booking Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('bookings.store') }}" method="POST" class="bg-white rounded-lg shadow-lg p-6">
                    @csrf

                    <input type="hidden" name="plate_no" value="{{ $car->plateNo }}">

                    <!-- Booking Details -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-calendar-alt text-orange-500"></i> Booking Details
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Pickup Date -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Pickup Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="pickup_date" 
                                       id="pickup_date"
                                       value="{{ old('pickup_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>

                            <!-- Return Date -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Return Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="return_date" 
                                       id="return_date"
                                       value="{{ old('return_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-user text-orange-500"></i> Your Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name (Read-only) -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                                <input type="text" 
                                       value="{{ Auth::user()->name }}"
                                       readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100">
                            </div>

                            <!-- Email (Read-only) -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                                <input type="email" 
                                       value="{{ Auth::user()->email }}"
                                       readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                                <input type="tel" 
                                       value="{{ Auth::user()->phone }}"
                                       readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100">
                            </div>

                            <!-- Matric Number -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Matric Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="matricNum" 
                                       value="{{ old('matricNum', Auth::user()->matricNum ?? '') }}"
                                       required
                                       placeholder="e.g., A12345"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>

                    <!-- Voucher Code (Optional) -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-ticket-alt text-orange-500"></i> Voucher Code (Optional)
                        </h3>
                        <input type="text" 
                               name="voucher_id" 
                               value="{{ old('voucher_id') }}"
                               placeholder="Enter voucher code if you have one"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Price Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Price Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Daily Rate:</span>
                                <span class="font-semibold">RM{{ number_format($car->daily_rate, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Number of Days:</span>
                                <span class="font-semibold" id="days_count">0 days</span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between text-lg">
                                    <span class="font-bold">Total Price:</span>
                                    <span class="font-bold text-orange-500" id="total_price">RM0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="terms" 
                                   required
                                   class="mt-1 mr-2">
                            <span class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-orange-500 hover:underline">terms and conditions</a> 
                                and understand that I must have a valid driver's license to rent this vehicle.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4">
                        <a href="{{ route('cars.index') }}" 
                           class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 text-center py-3 rounded-lg font-semibold transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-check mr-2"></i> Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const dailyRate = {{ $car->daily_rate }};
    const pickupInput = document.getElementById('pickup_date');
    const returnInput = document.getElementById('return_date');
    const daysCount = document.getElementById('days_count');
    const totalPrice = document.getElementById('total_price');

    function calculatePrice() {
        const pickup = new Date(pickupInput.value);
        const returnDate = new Date(returnInput.value);

        if (pickup && returnDate && returnDate > pickup) {
            const days = Math.ceil((returnDate - pickup) / (1000 * 60 * 60 * 24));
            const total = days * dailyRate;

            daysCount.textContent = `${days} day${days > 1 ? 's' : ''}`;
            totalPrice.textContent = `RM${total.toFixed(2)}`;
        } else {
            daysCount.textContent = '0 days';
            totalPrice.textContent = 'RM0.00';
        }
    }

    pickupInput.addEventListener('change', calculatePrice);
    returnInput.addEventListener('change', calculatePrice);

    // Set return date minimum to pickup date
    pickupInput.addEventListener('change', function() {
        returnInput.min = this.value;
    });
</script>

</body>
</html>