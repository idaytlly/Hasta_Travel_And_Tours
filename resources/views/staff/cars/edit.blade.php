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
            background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%);
        }
    </style>
</head>
<body class="bg-gray-100">

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
                <a href="{{ route('staff.cars') }}" class="bg-red-400 bg-opacity-50 px-4 py-2 rounded-lg hover:bg-opacity-70 transition">
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
                <button class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-lg text-white font-semibold transition">
                    Login
                </button>
                <img src="https://ui-avatars.com/api/?name=Staff&background=FF6B35&color=fff" 
                     alt="Profile" 
                     class="w-12 h-12 rounded-full border-2 border-white">
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <!-- Back Button -->
    <a href="{{ route('staff.cars') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <i class="fas fa-chevron-left text-3xl"></i>
    </a>

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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Side - Image -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="bg-gray-50 rounded-2xl p-8 mb-6 flex items-center justify-center" style="min-height: 350px;">
                @if($car->image)
                    <img src="{{ asset('car_images/' . $car->image) }}" 
                         alt="{{ $car->brand }} {{ $car->model }}" 
                         class="max-w-full max-h-80 object-contain"
                         id="carImagePreview">
                @else
                    <i class="fas fa-car text-gray-300 text-9xl" id="carImagePreview"></i>
                @endif
            </div>
            
            <form id="imageUploadForm" enctype="multipart/form-data">
                <label for="imageUpload" class="block w-full">
                    <div class="border-2 border-orange-500 text-orange-500 px-6 py-3 rounded-lg text-center cursor-pointer hover:bg-orange-50 transition">
                        <i class="fas fa-camera mr-2"></i> Edit Picture
                    </div>
                    <input type="file" 
                           id="imageUpload" 
                           name="image" 
                           accept="image/*" 
                           class="hidden"
                           onchange="previewImage(event)">
                </label>
            </form>
        </div>

        <!-- Right Side - Form -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-3xl font-bold mb-6">Car Information</h2>

            <form action="{{ route('staff.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Hidden image input -->
                <input type="file" name="image" id="actualImageInput" class="hidden">

                <!-- Car ID -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Car Id</label>
                    <input type="text" 
                           value="{{ $car->id }}" 
                           disabled
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200">
                </div>

                <!-- Brand -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Brand</label>
                    <select name="brand" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Brand</option>
                        <option value="PERODUA" {{ strtoupper($car->brand) == 'PERODUA' ? 'selected' : '' }}>PERODUA</option>
                        <option value="PROTON" {{ strtoupper($car->brand) == 'PROTON' ? 'selected' : '' }}>PROTON</option>
                        <option value="TOYOTA" {{ strtoupper($car->brand) == 'TOYOTA' ? 'selected' : '' }}>TOYOTA</option>
                        <option value="HONDA" {{ strtoupper($car->brand) == 'HONDA' ? 'selected' : '' }}>HONDA</option>
                        <option value="NISSAN" {{ strtoupper($car->brand) == 'NISSAN' ? 'selected' : '' }}>NISSAN</option>
                        <option value="MAZDA" {{ strtoupper($car->brand) == 'MAZDA' ? 'selected' : '' }}>MAZDA</option>
                    </select>
                </div>

                <!-- Model -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Model</label>
                    <input type="text" 
                           name="model" 
                           value="{{ $car->model }}"
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Year -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Year</label>
                    <input type="number" 
                           name="year" 
                           value="{{ $car->year }}"
                           min="2000" 
                           max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Plate Number -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Plate Number</label>
                    <input type="text" 
                           name="plateNo" 
                           value="{{ $car->plateNo }}"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Car Type -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Car Type</label>
                    <select name="carType" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Type</option>
                        <option value="sedan" {{ $car->carType == 'sedan' ? 'selected' : '' }}>Sedan</option>
                        <option value="hatchback" {{ $car->carType == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                        <option value="mpv" {{ $car->carType == 'mpv' ? 'selected' : '' }}>MPV</option>
                        <option value="suv" {{ $car->carType == 'suv' ? 'selected' : '' }}>SUV</option>
                        <option value="minivan" {{ $car->carType == 'minivan' ? 'selected' : '' }}>Minivan</option>
                    </select>
                </div>

                <!-- Transmission -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Transmission</label>
                    <select name="transmission" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="automatic" {{ $car->transmission == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ $car->transmission == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <!-- Daily Rate -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Daily Rate (RM)</label>
                    <input type="number" 
                           name="daily_rate" 
                           value="{{ $car->daily_rate }}"
                           step="0.01"
                           min="0"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Availability -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Availability</label>
                    <select name="is_available" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="1" {{ $car->is_available == 1 ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ $car->is_available == 0 ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('staff.cars') }}" 
                       class="flex-1 px-6 py-3 rounded-lg border-2 border-orange-500 text-orange-500 text-center font-semibold hover:bg-orange-50 transition">
                        Discard Changes
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 rounded-lg bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                        Save Changes
                    </button>
                </div>

                <!-- Delete Button -->
                <button type="button" 
                        onclick="confirmDelete()"
                        class="w-full mt-3 px-6 py-3 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 transition">
                    <i class="fas fa-trash mr-2"></i> Delete Car
                </button>
            </form>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('staff.cars.destroy', $car->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('carImagePreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    preview.outerHTML = `<img src="${e.target.result}" alt="Car Preview" class="max-w-full max-h-80 object-contain" id="carImagePreview">`;
                }
                
                // Copy file to actual form input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('actualImageInput').files = dataTransfer.files;
            };
            reader.readAsDataURL(file);
        }
    }

    function confirmDelete() {
        if (confirm('Are you sure you want to delete this car? This action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>

</body>
</html>