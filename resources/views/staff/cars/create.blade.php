@extends('layouts.staff')

@section('title', 'Add New Vehicle')
@section('page-title', 'Add New Vehicle')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-light: rgba(220, 38, 38, 0.1);
        --success: #22c55e;
        --info: #3b82f6;
        --purple: #8b5cf6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f1f5f9;
        --white: #ffffff;
        --border: #e2e8f0;
    }

    body {
        background: var(--light) !important;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray);
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        color: var(--primary);
        gap: 0.75rem;
    }

    .content-card {
        background: var(--white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .vehicle-type-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--light);
        border-radius: 12px;
    }

    .type-option {
        flex: 1;
        padding: 1.25rem;
        border-radius: 8px;
        border: 2px solid var(--border);
        background: var(--white);
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .type-option:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .type-option.active {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .type-option.active.motorcycle {
        border-color: var(--purple);
        background: rgba(139, 92, 246, 0.1);
    }

    .type-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .type-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 1rem;
    }

    .image-upload-section {
        background: var(--light);
        border-radius: 12px;
        padding: 3rem;
        text-align: center;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed var(--border);
        transition: all 0.3s;
    }

    .image-upload-section:hover {
        border-color: var(--primary);
        background: rgba(220, 38, 38, 0.02);
    }

    .image-preview {
        max-width: 100%;
        max-height: 350px;
        object-fit: contain;
        border-radius: 8px;
    }

    .upload-placeholder {
        font-size: 6rem;
        color: var(--border);
        margin-bottom: 1.5rem;
    }

    .upload-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        margin-top: 1rem;
    }

    .upload-button:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .required {
        color: var(--primary);
    }

    .form-input, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--dark);
        background: var(--white);
        transition: all 0.2s;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .button-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        color: white;
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
        background: var(--primary-light);
        color: var(--primary);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .alert-error {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.2);
        color: #991b1b;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
        
        .button-group {
            grid-template-columns: 1fr;
        }

        .vehicle-type-selector {
            flex-direction: column;
        }
    }
</style>

<div class="container-fluid py-4">
    
    <!-- Back Button -->
    <a href="{{ route('staff.cars.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Vehicles
    </a>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-error">
            <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
            <ul class="mt-2 ml-4 list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        
        <!-- Left Side - Image Upload -->
        <div class="col-lg-5">
            <div class="content-card">
                <h3 class="form-section-title">Vehicle Image</h3>
                
                <div class="image-upload-section">
                    <div id="imagePlaceholder">
                        <i class="fas fa-car upload-placeholder" id="placeholderIcon"></i>
                        <p class="text-gray-500 text-sm">Upload vehicle image</p>
                    </div>
                    <img id="imagePreview" class="image-preview" style="display: none;">
                </div>

                <label for="imageUpload" class="upload-button" style="width: 100%; margin-top: 1.5rem; justify-content: center;">
                    <i class="fas fa-camera"></i>
                    Choose Image
                </label>
                <input type="file" 
                       id="imageUpload" 
                       accept="image/*" 
                       class="d-none"
                       onchange="previewImage(event)">

                <p class="text-center text-gray-500" style="font-size: 0.75rem; margin-top: 0.75rem;">
                    Accepted formats: JPG, PNG, JPEG (Max 5MB)
                </p>
            </div>
        </div>

        <!-- Right Side - Vehicle Form -->
        <div class="col-lg-7">
            <div class="content-card">
                <h3 class="form-section-title mb-3">Add New Vehicle</h3>

                <!-- Vehicle Type Selector -->
                <div class="vehicle-type-selector">
                    <div class="type-option active" id="carOption" onclick="selectType('car')">
                        <div class="type-icon"><i class="fas fa-car" style="color: var(--info);"></i></div>
                        <div class="type-label">Car</div>
                    </div>
                    <div class="type-option motorcycle" id="motorcycleOption" onclick="selectType('motorcycle')">
                        <div class="type-icon"><i class="fas fa-motorcycle" style="color: var(--purple);"></i></div>
                        <div class="type-label">Motorcycle</div>
                    </div>
                </div>

                <form action="{{ route('staff.cars.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
                    @csrf

                    <!-- Hidden inputs -->
                    <input type="file" name="image" id="actualImageInput" class="d-none">
                    <input type="hidden" name="vehicle_type" id="vehicleType" value="{{ request('type', 'car') }}">

                    <!-- Brand & Model -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building text-primary"></i> Brand <span class="required">*</span>
                            </label>
                            <select name="brand" required class="form-select" id="brandSelect">
                                <option value="">Select Brand</option>
                                <!-- Car Brands -->
                                <optgroup label="Car Brands" id="carBrands">
                                    <option value="Perodua">Perodua</option>
                                    <option value="Proton">Proton</option>
                                    <option value="Toyota">Toyota</option>
                                    <option value="Honda">Honda</option>
                                    <option value="Nissan">Nissan</option>
                                    <option value="Mazda">Mazda</option>
                                    <option value="Mitsubishi">Mitsubishi</option>
                                </optgroup>
                                <!-- Motorcycle Brands -->
                                <optgroup label="Motorcycle Brands" id="motorcycleBrands" style="display: none;">
                                    <option value="Honda">Honda</option>
                                    <option value="Yamaha">Yamaha</option>
                                    <option value="SYM">SYM</option>
                                    <option value="Modenas">Modenas</option>
                                    <option value="Kawasaki">Kawasaki</option>
                                    <option value="Suzuki">Suzuki</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-car text-primary"></i> Model <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="model" 
                                   value="{{ old('model') }}"
                                   required
                                   placeholder="e.g., Myvi, Axia"
                                   class="form-input">
                        </div>
                    </div>

                    <!-- Year & License Plate -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar text-primary"></i> Year <span class="required">*</span>
                            </label>
                            <input type="number" 
                                   name="year" 
                                   value="{{ old('year', date('Y')) }}"
                                   min="2000" 
                                   max="{{ date('Y') + 1 }}"
                                   required
                                   placeholder="{{ date('Y') }}"
                                   class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-id-card text-primary"></i> License Plate <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="license_plate" 
                                   value="{{ old('license_plate') }}"
                                   required
                                   placeholder="e.g., ABC 1234"
                                   class="form-input"
                                   style="text-transform: uppercase;">
                        </div>
                    </div>

                    <!-- Category & Transmission -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-shapes text-primary"></i> Category
                            </label>
                            <select name="category" class="form-select" id="categorySelect">
                                <option value="">Select Category</option>
                                <option value="Sedan">Sedan</option>
                                <option value="Hatchback">Hatchback</option>
                                <option value="MPV">MPV</option>
                                <option value="SUV">SUV</option>
                                <option value="Minivan">Minivan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-cog text-primary"></i> Transmission <span class="required">*</span>
                            </label>
                            <select name="transmission" required class="form-select">
                                <option value="Automatic" selected>Automatic</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fuel Type & Engine/Passengers -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-gas-pump text-primary"></i> Fuel Type
                            </label>
                            <select name="fuel_type" class="form-select">
                                <option value="">Select Fuel Type</option>
                                <option value="Petrol" selected>Petrol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Electric">Electric</option>
                            </select>
                        </div>

                        <div class="form-group" id="passengersField">
                            <label class="form-label">
                                <i class="fas fa-users text-primary"></i> Passengers
                            </label>
                            <input type="number" 
                                   name="passengers" 
                                   value="{{ old('passengers', 5) }}"
                                   min="1" 
                                   max="15"
                                   placeholder="5"
                                   class="form-input">
                        </div>

                        <div class="form-group" id="engineField" style="display: none;">
                            <label class="form-label">
                                <i class="fas fa-tachometer-alt text-primary"></i> Engine Capacity (cc)
                            </label>
                            <input type="number" 
                                   name="engine_capacity" 
                                   value="{{ old('engine_capacity', 150) }}"
                                   min="50" 
                                   max="1000"
                                   placeholder="150"
                                   class="form-input">
                        </div>
                    </div>

                    <!-- Seats & Daily Rate -->
                    <div class="grid-2">
                        <div class="form-group" id="seatsField">
                            <label class="form-label">
                                <i class="fas fa-chair text-primary"></i> Seats
                            </label>
                            <input type="number" 
                                   name="seats" 
                                   value="{{ old('seats', 5) }}"
                                   min="1" 
                                   max="15"
                                   placeholder="5"
                                   class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign text-primary"></i> Daily Rate (RM) <span class="required">*</span>
                            </label>
                            <input type="number" 
                                   name="daily_rate" 
                                   value="{{ old('daily_rate') }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   placeholder="120.00"
                                   class="form-input">
                        </div>
                    </div>

                    <!-- Air Conditioner & Availability -->
                    <div class="grid-2">
                        <div class="form-group" id="airConditionerField">
                            <label class="form-label">
                                <i class="fas fa-snowflake text-primary"></i> Air Conditioner
                            </label>
                            <select name="air_conditioner" class="form-select">
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on text-primary"></i> Availability <span class="required">*</span>
                            </label>
                            <select name="is_available" required class="form-select">
                                <option value="1" selected>Available</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="button-group">
                        <a href="{{ route('staff.cars.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Get URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const initialType = urlParams.get('type') || 'car';

    // Initialize with URL parameter
    if (initialType === 'motorcycle') {
        selectType('motorcycle');
    }

    function selectType(type) {
        const carOption = document.getElementById('carOption');
        const motorcycleOption = document.getElementById('motorcycleOption');
        const vehicleType = document.getElementById('vehicleType');
        const placeholderIcon = document.getElementById('placeholderIcon');
        const categorySelect = document.getElementById('categorySelect');
        const passengersField = document.getElementById('passengersField');
        const seatsField = document.getElementById('seatsField');
        const engineField = document.getElementById('engineField');
        const airConditionerField = document.getElementById('airConditionerField');
        const carBrands = document.getElementById('carBrands');
        const motorcycleBrands = document.getElementById('motorcycleBrands');
        
        if (type === 'car') {
            carOption.classList.add('active');
            motorcycleOption.classList.remove('active');
            placeholderIcon.className = 'fas fa-car upload-placeholder';
            categorySelect.disabled = false;
            passengersField.style.display = 'block';
            seatsField.style.display = 'block';
            engineField.style.display = 'none';
            airConditionerField.style.display = 'block';
            carBrands.style.display = 'block';
            motorcycleBrands.style.display = 'none';
            vehicleType.value = 'car';
        } else {
            carOption.classList.remove('active');
            motorcycleOption.classList.add('active');
            placeholderIcon.className = 'fas fa-motorcycle upload-placeholder';
            categorySelect.value = 'motorcycle';
            categorySelect.disabled = true;
            passengersField.style.display = 'none';
            seatsField.style.display = 'none';
            engineField.style.display = 'block';
            airConditionerField.style.display = 'none';
            carBrands.style.display = 'none';
            motorcycleBrands.style.display = 'block';
            vehicleType.value = 'motorcycle';
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                event.target.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select a valid image file');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById('imagePlaceholder');
                const preview = document.getElementById('imagePreview');
                
                placeholder.style.display = 'none';
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                // Copy file to actual form input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('actualImageInput').files = dataTransfer.files;
            };
            reader.readAsDataURL(file);
        }
    }

    // Auto-uppercase license plate
    document.querySelector('input[name="license_plate"]').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });

    // Form validation
    document.getElementById('createForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'var(--primary)';
            } else {
                field.style.borderColor = 'var(--border)';
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
</script>
@endpush
@endsection