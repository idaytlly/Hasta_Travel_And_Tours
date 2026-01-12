{{-- resources/views/staff/vehicles/create.blade.php --}}
@extends('staff.layouts.app')

@section('title', 'Add New Vehicle')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Add New Vehicle</h1>
                <p class="text-gray-600">Fill in the vehicle details to add to your fleet</p>
            </div>
            <a href="{{ route('staff.vehicles.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Fleet
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('staff.vehicles.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Main Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            
            <!-- Vehicle Type Selection -->
            <div class="border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50 px-8 py-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="car" class="w-5 h-5 text-red-600"></i>
                    Vehicle Type
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="vehicle_type" value="car" class="peer sr-only" checked required>
                        <div class="flex items-center gap-4 p-5 border-2 border-gray-200 rounded-xl bg-white transition-all peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:shadow-md hover:border-gray-300">
                            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center peer-checked:bg-red-100 transition">
                                <i data-lucide="car" class="w-7 h-7 text-gray-600 peer-checked:text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900">Car</p>
                                <p class="text-sm text-gray-600">Sedan, SUV, MPV, Hatchback</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="vehicle_type" value="motorcycle" class="peer sr-only" required>
                        <div class="flex items-center gap-4 p-5 border-2 border-gray-200 rounded-xl bg-white transition-all peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:shadow-md hover:border-gray-300">
                            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center peer-checked:bg-red-100 transition">
                                <i data-lucide="bike" class="w-7 h-7 text-gray-600 peer-checked:text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900">Motorcycle</p>
                                <p class="text-sm text-gray-600">Scooter, Sport, Cruiser</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="p-8 space-y-8">
                
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-red-600"></i>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Plate Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Plate Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="hash" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" name="plate_no" required value="{{ old('plate_no') }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                       placeholder="e.g., ABC1234">
                            </div>
                            @error('plate_no')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Vehicle Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Vehicle Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="tag" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" name="name" required value="{{ old('name') }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                       placeholder="e.g., Perodua Axia 2024">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Color -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="palette" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" name="color" required value="{{ old('color') }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                       placeholder="e.g., Pearl White">
                            </div>
                            @error('color')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Year -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Year <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="number" name="year" required min="2000" max="{{ date('Y') + 1 }}" value="{{ old('year', date('Y')) }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                            </div>
                            @error('year')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Distance Travelled -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Distance Travelled (km) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="gauge" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="number" name="distance_travelled" required min="0" step="0.1" value="{{ old('distance_travelled', 0) }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                            </div>
                            <p class="mt-2 text-xs text-gray-600">Current mileage in kilometers</p>
                            @error('distance_travelled')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Road Tax Expiry -->
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Road Tax Expiry Date <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="file-text" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="date" name="roadtax_expiry" required value="{{ old('roadtax_expiry') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                            </div>
                            <p class="mt-2 text-xs text-gray-600 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3"></i>
                                System will alert you 30 days before expiry
                            </p>
                            @error('roadtax_expiry')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <i data-lucide="settings" class="w-5 h-5 text-red-600"></i>
                        Specifications
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Transmission -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Transmission <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="settings" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none z-10"></i>
                                <select name="transmission" required 
                                        class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition appearance-none bg-white">
                                    <option value="">Select Transmission</option>
                                    <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                    <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"></i>
                            </div>
                            @error('transmission')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Fuel Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Fuel Type <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="fuel" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none z-10"></i>
                                <select name="fuel_type" required 
                                        class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition appearance-none bg-white">
                                    <option value="">Select Fuel Type</option>
                                    <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                                    <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"></i>
                            </div>
                            @error('fuel_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Seating Capacity -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Seating Capacity <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="users" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="number" name="seating_capacity" required min="1" max="50" value="{{ old('seating_capacity', 4) }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                            </div>
                            @error('seating_capacity')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Price per Hour -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Price per Hour (RM) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 font-semibold">RM</span>
                                <input type="number" name="price_perHour" required step="0.01" min="0" value="{{ old('price_perHour', 10) }}"
                                       class="pl-14 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                            </div>
                            <p class="mt-2 text-xs text-gray-600">Daily rate will be calculated automatically (× 24 hours)</p>
                            @error('price_perHour')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Vehicle Images -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <i data-lucide="image" class="w-5 h-5 text-red-600"></i>
                        Vehicle Images
                    </h3>
                    <p class="text-sm text-gray-600 mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Upload 6 images (4 exterior views + 2 interior views) - Max: 5MB each, JPG/PNG
                    </p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Front View -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Front View <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="front_image" accept="image/*" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="previewImage(this, 'preview-front')">
                                <div id="preview-front" 
                                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Front View</p>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                            </div>
                            @error('front_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Right Side View -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Right Side View <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="right_image" accept="image/*" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="previewImage(this, 'preview-right')">
                                <div id="preview-right" 
                                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Right Side</p>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                            </div>
                            @error('right_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Back View -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Back View <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="back_image" accept="image/*" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="previewImage(this, 'preview-back')">
                                <div id="preview-back" 
                                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Back View</p>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                            </div>
                            @error('back_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Left Side View -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Left Side View <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="left_image" accept="image/*" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="previewImage(this, 'preview-left')">
                                <div id="preview-left" 
                                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Left Side</p>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                            </div>
                            @error('left_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Interior Front View -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Interior Front <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="interior_front_image" accept="image/*" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="previewImage(this, 'preview-interior-front')">
                                <div id="preview-interior-front" 
                                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Interior Front</p>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                            </div>
                            @error('interior_front_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Interior Back View -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Interior Back <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="interior_back_image" accept="image/*" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       onchange="previewImage(this, 'preview-interior-back')">
                                <div id="preview-interior-back" 
                                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Interior Back</p>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                            </div>
                            @error('interior_back_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Display Image (for vehicle listing) -->
<div>
    <h3 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
        <i data-lucide="image" class="w-5 h-5 text-red-600"></i>
        Display Image
    </h3>
    <p class="text-sm text-gray-600 mb-6 flex items-center gap-2">
        <i data-lucide="info" class="w-4 h-4"></i>
        This image will be shown in the vehicle listing page. Recommended: 400×400px
    </p>
    
    <div class="max-w-md">
        <div class="relative group">
            <input type="file" name="display_image" accept="image/*"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                   onchange="previewImage(this, 'preview-display')">
            <div id="preview-display" 
                 class="border-2 border-dashed border-gray-300 rounded-xl p-8 bg-gray-50 group-hover:border-red-400 group-hover:bg-red-50 transition-all cursor-pointer">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="image" class="w-8 h-8 text-red-600"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Display Image</p>
                    <p class="text-xs text-gray-500">Best size: 400×400px</p>
                </div>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-600">
            This is the main thumbnail image that appears in the vehicle list. Different from the detailed images above.
        </p>
        @error('display_image')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                {{ $message }}
            </p>
        @enderror
    </div>
    </div>

                <!-- Description & Status -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description (Optional)
                        </label>
                        <textarea name="description" rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                  placeholder="Additional vehicle information, special notes, or remarks...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Status & Maintenance Notes -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Initial Status <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-emerald-300 hover:bg-emerald-50 transition cursor-pointer has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="availability_status" value="available" 
                                           class="w-5 h-5 text-emerald-600" checked required>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                        <span class="font-semibold text-gray-700">Available</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="availability_status" value="booked" 
                                           class="w-5 h-5 text-blue-600" required>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <span class="font-semibold text-gray-700">Booked</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-red-300 hover:bg-red-50 transition cursor-pointer has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                    <input type="radio" name="availability_status" value="maintenance" 
                                           class="w-5 h-5 text-red-600" required>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        <span class="font-semibold text-gray-700">Maintenance</span>
                                    </div>
                                </label>
                            </div>
                            @error('availability_status')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Maintenance Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Maintenance Notes (Optional)
                            </label>
                            <textarea name="maintenance_notes" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                      placeholder="Initial maintenance notes, if any">{{ old('maintenance_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-8 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('staff.vehicles.index') }}" 
                           class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Add Vehicle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="relative">
                    <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                    <button type="button" onclick="removeImage(this, '${input.name}')" 
                            class="absolute top-2 right-2 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    }
}

function removeImage(button, inputName) {
    const preview = button.closest('.relative').parentElement;
    const input = document.querySelector(`input[name="${inputName}"]`);
    
    // Reset file input
    input.value = '';
    
    // Get label text based on input name
    let labelText = 'Upload';
    if (inputName.includes('front')) labelText = 'Front View';
    else if (inputName.includes('right')) labelText = 'Right Side';
    else if (inputName.includes('back')) labelText = 'Back View';
    else if (inputName.includes('left')) labelText = 'Left Side';
    else if (inputName.includes('interior_front')) labelText = 'Interior Front';
    else if (inputName.includes('interior_back')) labelText = 'Interior Back';
    else if (inputName.includes('display')) labelText = 'Display Image';
    
    // Reset preview
    preview.innerHTML = `
        <div class="text-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i data-lucide="camera" class="w-6 h-6 text-red-600"></i>
            </div>
            <p class="text-sm font-semibold text-gray-700 mb-1">${labelText}</p>
            <p class="text-xs text-gray-500">Click to upload</p>
        </div>
    `;
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