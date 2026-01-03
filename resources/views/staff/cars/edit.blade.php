{{-- resources/views/staff/cars/edit.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - Hasta Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .header-gradient {
            background: linear-gradient(135deg, #f4130fff 0%, #D32F2F 100%);
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="header-gradient text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('staff.dashboard') }}" class="flex items-center">
                <h1 class="text-2xl font-bold tracking-wider">HASTA</h1>
            </a>

            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('staff.dashboard') }}" class="hover:text-gray-200 transition">
                    <i class="fas fa-home text-xl"></i>
                </a>
                <a href="{{ route('staff.notifications.index') }}" class="hover:text-gray-200 transition">
                    <i class="fas fa-bell text-xl"></i>
                </a>
                <a href="{{ route('staff.cars.index') }}" class="bg-red-400 bg-opacity-50 px-4 py-2 rounded-lg hover:bg-opacity-70 transition">
                    <i class="fas fa-car"></i> Vehicle Management
                </a>
                <a href="{{ route('staff.bookings.index') }}" class="hover:text-gray-200 transition">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </a>
                <a href="{{ route('staff.settings.profile') }}" class="hover:text-gray-200 transition">
                    <i class="fas fa-cog text-xl"></i>
                </a>
            </nav>

            <div class="flex items-center gap-4">
                <span class="text-sm">{{ Auth::user()->name }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF6B35&color=fff" 
                     alt="Profile" 
                     class="w-12 h-12 rounded-full border-2 border-white">
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <!-- Back Button -->
    <a href="{{ route('staff.cars.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <i class="fas fa-chevron-left text-3xl"></i>
    </a>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Side - Image Preview -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="bg-gray-50 rounded-2xl p-8 mb-6 flex items-center justify-center" style="min-height: 350px;">
                <img id="carImagePreview" src="{{ $car->image }}" alt="Car Preview" class="max-w-full max-h-80 object-contain mx-auto">
            </div>
            
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2">Image URL</label>
                <input type="url" 
                       id="imageUrlInput"
                       value="{{ $car->image }}"
                       placeholder="https://example.com/car-image.jpg"
                       class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500"
                       onchange="previewImageUrl(event)">
            </div>
            <p class="text-sm text-gray-500 text-center mt-2">Enter image URL from web</p>
        </div>

        <!-- Right Side - Form -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-3xl font-bold mb-6">Edit Car</h2>

            <form action="{{ route('staff.cars.update', $car->id) }}" method="POST" id="editCarForm">
                @csrf
                @method('PATCH')

                <!-- Plate Number -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Plate Number</label>
                    <input type="text" 
                           name="plate_number" 
                           value="{{ old('plate_number', $car->license_plate) }}"
                           placeholder="e.g., ABC1234"
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Brand -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Brand <span class="text-red-500">*</span></label>
                    <select name="brand" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Brand</option>
                        <option value="Perodua" {{ old('brand', $car->brand) == 'Perodua' ? 'selected' : '' }}>Perodua</option>
                        <option value="Proton" {{ old('brand', $car->brand) == 'Proton' ? 'selected' : '' }}>Proton</option>
                        <option value="Toyota" {{ old('brand', $car->brand) == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                        <option value="Honda" {{ old('brand', $car->brand) == 'Honda' ? 'selected' : '' }}>Honda</option>
                        <option value="Nissan" {{ old('brand', $car->brand) == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                        <option value="Mazda" {{ old('brand', $car->brand) == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                        <option value="Hyundai" {{ old('brand', $car->brand) == 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                    </select>
                </div>

                <!-- Model -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Model <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="model" 
                           value="{{ old('model', $car->model) }}"
                           placeholder="e.g., Axia"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Year -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Year <span class="text-red-500">*</span></label>
                    <input type="number" 
                           name="year" 
                           value="{{ old('year', $car->year) }}"
                           min="2000" 
                           max="{{ date('Y') + 1 }}"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Category</option>
                        <option value="Sedan" {{ old('category', $car->category) == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                        <option value="Hatchback" {{ old('category', $car->category) == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                        <option value="MPV" {{ old('category', $car->category) == 'MPV' ? 'selected' : '' }}>MPV</option>
                        <option value="SUV" {{ old('category', $car->category) == 'SUV' ? 'selected' : '' }}>SUV</option>
                        <option value="Motorcycle" {{ old('category', $car->category) == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                    </select>
                </div>

                <!-- Transmission -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Transmission <span class="text-red-500">*</span></label>
                    <select name="transmission" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="Automatic" {{ old('transmission', $car->transmission) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="Manual" {{ old('transmission', $car->transmission) == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <!-- Fuel Type -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Fuel Type <span class="text-red-500">*</span></label>
                    <select name="fuel_type" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="Petrol" {{ old('fuel_type', $car->fuel_type) == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="Diesel" {{ old('fuel_type', $car->fuel_type) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Hybrid" {{ old('fuel_type', $car->fuel_type) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="Electric" {{ old('fuel_type', $car->fuel_type) == 'Electric' ? 'selected' : '' }}>Electric</option>
                    </select>
                </div>

                <!-- Seats -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Seats <span class="text-red-500">*</span></label>
                    <input type="number" 
                           name="seats" 
                           value="{{ old('seats', $car->seats) }}"
                           min="1" 
                           max="12"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Air Conditioner -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Air Conditioner <span class="text-red-500">*</span></label>
                    <select name="air_conditioner" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="1" {{ old('air_conditioner', $car->air_conditioner) == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('air_conditioner', $car->air_conditioner) == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Daily Rate -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Daily Rate (RM) <span class="text-red-500">*</span></label>
                    <input type="number" 
                           name="daily_rate" 
                           value="{{ old('daily_rate', $car->daily_rate) }}"
                           step="0.01"
                           min="0"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented" {{ old('status', $car->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                        <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <!-- Hidden Image URL -->
                <input type="hidden" name="image" id="hiddenImageInput" value="{{ old('image', $car->image) }}">

                <!-- Buttons -->
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('staff.cars.index') }}" 
                       class="flex-1 px-6 py-3 rounded-lg border-2 border-orange-500 text-orange-500 text-center font-semibold hover:bg-orange-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 rounded-lg bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                        <i class="fas fa-save mr-2"></i> Update Car
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImageUrl(event) {
        const url = event.target.value;
        if (url) {
            const preview = document.getElementById('carImagePreview');
            const hiddenInput = document.getElementById('hiddenImageInput');
            
            // Update preview
            preview.src = url;
            
            // Update hidden input
            hiddenInput.value = url;
            
            // Handle image load error
            preview.onerror = function() {
                alert('Failed to load image. Please check the URL.');
                hiddenInput.value = '{{ $car->image }}';
                event.target.value = '{{ $car->image }}';
                preview.src = '{{ $car->image }}';
            };
        }
    }
</script>

</body>
</html>