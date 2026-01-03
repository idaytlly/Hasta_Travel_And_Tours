{{-- resources/views/staff/cars/create.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car - Hasta Staff</title>
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
    <a href="{{ route('staff.cars.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <i class="fas fa-chevron-left text-3xl"></i>
    </a>

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
        
        <!-- Left Side - Image Upload -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="bg-gray-50 rounded-2xl p-8 mb-6 flex items-center justify-center" style="min-height: 350px;">
                <div id="imagePreviewContainer">
                    <i class="fas fa-car text-gray-300 text-9xl" id="carImageIcon"></i>
                    <img id="carImagePreview" src="" alt="Car Preview" class="hidden max-w-full max-h-80 object-contain">
                </div>
            </div>
            
            <label for="imageUpload" class="block w-full">
                <div class="border-2 border-orange-500 text-orange-500 px-6 py-3 rounded-lg text-center cursor-pointer hover:bg-orange-50 transition">
                    <i class="fas fa-camera mr-2"></i> Upload Picture
                </div>
                <input type="file" 
                       id="imageUpload" 
                       name="image" 
                       accept="image/*" 
                       class="hidden"
                       form="createCarForm"
                       onchange="previewImage(event)">
            </label>
            <p class="text-sm text-gray-500 text-center mt-2">JPG, PNG (Max 2MB)</p>
        </div>

        <!-- Right Side - Form -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="text-3xl font-bold mb-6">Add New Car</h2>

            <form action="{{ route('staff.cars.store') }}" method="POST" enctype="multipart/form-data" id="createCarForm">
                @csrf

                <!-- Hidden image input -->
                <input type="file" name="image" id="actualImageInput" class="hidden">

                <!-- Plate Number -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Plate Number <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="plateNo" 
                           value="{{ old('plateNo') }}"
                           placeholder="e.g., ABC1234"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Brand -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Brand <span class="text-red-500">*</span></label>
                    <select name="brand" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Brand</option>
                        <option value="PERODUA" {{ old('brand') == 'PERODUA' ? 'selected' : '' }}>PERODUA</option>
                        <option value="PROTON" {{ old('brand') == 'PROTON' ? 'selected' : '' }}>PROTON</option>
                        <option value="TOYOTA" {{ old('brand') == 'TOYOTA' ? 'selected' : '' }}>TOYOTA</option>
                        <option value="HONDA" {{ old('brand') == 'HONDA' ? 'selected' : '' }}>HONDA</option>
                        <option value="NISSAN" {{ old('brand') == 'NISSAN' ? 'selected' : '' }}>NISSAN</option>
                        <option value="MAZDA" {{ old('brand') == 'MAZDA' ? 'selected' : '' }}>MAZDA</option>
                    </select>
                </div>

                <!-- Model -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Model</label>
                    <input type="text" 
                           name="model" 
                           value="{{ old('model') }}"
                           placeholder="e.g., AXIA"
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Year -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Year</label>
                    <input type="number" 
                           name="year" 
                           value="{{ old('year', date('Y')) }}"
                           min="2000" 
                           max="{{ date('Y') + 1 }}"
                           placeholder="2024"
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Car Type -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Car Type</label>
                    <select name="carType" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Type</option>
                        <option value="sedan" {{ old('carType') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                        <option value="hatchback" {{ old('carType') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                        <option value="mpv" {{ old('carType') == 'mpv' ? 'selected' : '' }}>MPV</option>
                        <option value="suv" {{ old('carType') == 'suv' ? 'selected' : '' }}>SUV</option>
                        <option value="minivan" {{ old('carType') == 'minivan' ? 'selected' : '' }}>Minivan</option>
                    </select>
                </div>

                <!-- Transmission -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Transmission</label>
                    <select name="transmission" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="automatic" {{ old('transmission', 'automatic') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <!-- Daily Rate -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Daily Rate (RM) <span class="text-red-500">*</span></label>
                    <input type="number" 
                           name="daily_rate" 
                           value="{{ old('daily_rate') }}"
                           step="0.01"
                           min="0"
                           placeholder="120.00"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Availability -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Availability</label>
                    <select name="is_available" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="1" {{ old('is_available', 1) == 1 ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ old('is_available') == 0 ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('staff.cars.index') }}" 
                       class="flex-1 px-6 py-3 rounded-lg border-2 border-orange-500 text-orange-500 text-center font-semibold hover:bg-orange-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 rounded-lg bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">
                        <i class="fas fa-plus mr-2"></i> Add Car
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const icon = document.getElementById('carImageIcon');
                const preview = document.getElementById('carImagePreview');
                
                icon.classList.add('hidden');
                preview.classList.remove('hidden');
                preview.src = e.target.result;
                
                // Copy file to actual form input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('actualImageInput').files = dataTransfer.files;
            };
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>