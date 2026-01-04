@extends('layouts.staff')

@section('title', 'Edit Vehicle')
@section('page-title', 'Edit Vehicle')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-light: rgba(220, 38, 38, 0.1);
        --orange: #f97316;
        --success: #22c55e;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f1f5f9;
        --white: #ffffff;
        --border: #e2e8f0;
    }

    body {
        background: var(--light) !important;
    }

    .page-header {
        margin-bottom: 2rem;
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
        position: relative;
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

    .form-input:disabled {
        background: var(--light);
        color: var(--gray);
        cursor: not-allowed;
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
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
        background: var(--primary-light);
    }

    .btn-danger {
        background: var(--primary);
        color: white;
        width: 100%;
        margin-top: 1rem;
    }

    .btn-danger:hover {
        background: #991b1b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
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

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #15803d;
    }

    .car-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--light);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-weight: 600;
        color: var(--gray);
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
        
        .button-group {
            grid-template-columns: 1fr;
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

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        
        <!-- Left Side - Image Upload -->
        <div class="col-lg-5">
            <div class="content-card">
                <h3 class="form-section-title">Vehicle Image</h3>
                
                <div class="image-upload-section">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="image-preview"
                             id="imagePreview">
                    @else
                        <div id="imagePlaceholder">
                            <i class="fas fa-car upload-placeholder"></i>
                            <p class="text-gray-500 text-sm">No image uploaded</p>
                        </div>
                    @endif
                </div>

                <label for="imageUpload" class="upload-button" style="width: 100%; margin-top: 1.5rem; justify-content: center;">
                    <i class="fas fa-camera"></i>
                    Change Image
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

        <!-- Right Side - Vehicle Information Form -->
        <div class="col-lg-7">
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="form-section-title mb-0" style="border: none; padding: 0;">Vehicle Information</h3>
                    <div class="car-id-badge">
                        <i class="fas fa-hashtag"></i>
                        ID: {{ $car->id }}
                    </div>
                </div>

                <form action="{{ route('staff.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data" id="carForm">
    @csrf
    @method('PUT')

    <!-- Hidden image input -->
    <input type="file" name="image" id="actualImageInput" class="d-none">

    <!-- Brand & Model -->
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-building text-primary"></i> Brand *
            </label>
            <select name="brand" required class="form-select">
                <option value="">Select Brand</option>
                <option value="Perodua" {{ $car->brand == 'Perodua' ? 'selected' : '' }}>Perodua</option>
                <option value="Proton" {{ $car->brand == 'Proton' ? 'selected' : '' }}>Proton</option>
                <option value="Toyota" {{ $car->brand == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                <option value="Honda" {{ $car->brand == 'Honda' ? 'selected' : '' }}>Honda</option>
                <option value="Nissan" {{ $car->brand == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                <option value="Mazda" {{ $car->brand == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                <option value="Mitsubishi" {{ $car->brand == 'Mitsubishi' ? 'selected' : '' }}>Mitsubishi</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-car text-primary"></i> Model *
            </label>
            <input type="text" 
                   name="model" 
                   value="{{ old('model', $car->model) }}"
                   required
                   placeholder="e.g., Myvi, Saga, Vios"
                   class="form-input">
        </div>
    </div>

    <!-- Year & License Plate -->
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-calendar text-primary"></i> Year *
            </label>
            <input type="number" 
                   name="year" 
                   value="{{ old('year', $car->year) }}"
                   min="2000" 
                   max="{{ date('Y') + 1 }}"
                   required
                   placeholder="{{ date('Y') }}"
                   class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-id-card text-primary"></i> License Plate *
            </label>
            <input type="text" 
                   name="license_plate" 
                   value="{{ old('license_plate', $car->license_plate) }}"
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
            <select name="category" class="form-select">
                <option value="">Select Category</option>
                <option value="Sedan" {{ $car->category == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                <option value="Hatchback" {{ $car->category == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                <option value="MPV" {{ $car->category == 'MPV' ? 'selected' : '' }}>MPV</option>
                <option value="SUV" {{ $car->category == 'SUV' ? 'selected' : '' }}>SUV</option>
                <option value="Minivan" {{ $car->category == 'Minivan' ? 'selected' : '' }}>Minivan</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-cog text-primary"></i> Transmission *
            </label>
            <select name="transmission" required class="form-select">
                <option value="Automatic" {{ ucfirst(strtolower($car->transmission)) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                <option value="Manual" {{ ucfirst(strtolower($car->transmission)) == 'Manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </div>
    </div>

    <!-- Fuel Type & Passengers -->
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-gas-pump text-primary"></i> Fuel Type
            </label>
            <select name="fuel_type" class="form-select">
                <option value="">Select Fuel Type</option>
                <option value="Petrol" {{ $car->fuel_type == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                <option value="Diesel" {{ $car->fuel_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                <option value="Hybrid" {{ $car->fuel_type == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                <option value="Electric" {{ $car->fuel_type == 'Electric' ? 'selected' : '' }}>Electric</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-users text-primary"></i> Passengers
            </label>
            <input type="number" 
                   name="passengers" 
                   value="{{ old('passengers', $car->passengers ?? 5) }}"
                   min="1" 
                   max="15"
                   placeholder="5"
                   class="form-input">
        </div>
    </div>

    <!-- Seats & Daily Rate -->
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-chair text-primary"></i> Seats
            </label>
            <input type="number" 
                   name="seats" 
                   value="{{ old('seats', $car->seats ?? 5) }}"
                   min="1" 
                   max="15"
                   placeholder="5"
                   class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-dollar-sign text-primary"></i> Daily Rate (RM) *
            </label>
            <input type="number" 
                   name="daily_rate" 
                   value="{{ old('daily_rate', $car->daily_rate) }}"
                   step="0.01"
                   min="0"
                   required
                   placeholder="0.00"
                   class="form-input">
        </div>
    </div>

    <!-- Air Conditioner & Availability -->
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-snowflake text-primary"></i> Air Conditioner
            </label>
            <select name="air_conditioner" class="form-select">
                <option value="1" {{ $car->air_conditioner == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $car->air_conditioner == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-toggle-on text-primary"></i> Availability Status *
            </label>
            <select name="is_available" required class="form-select">
                <option value="1" {{ $car->is_available == 1 ? 'selected' : '' }}>Available</option>
                <option value="0" {{ $car->is_available == 0 ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>
    </div>

    <!-- Buttons -->
    <div class="button-group">
        <a href="{{ route('staff.cars.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i>
            Discard Changes
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Save Changes
        </button>
    </div>

    <!-- Delete Button -->
    <button type="button" 
            onclick="confirmDelete()"
            class="btn btn-danger">
        <i class="fas fa-trash-alt"></i>
        Delete Vehicle
    </button>
</form>

                <!-- Hidden Delete Form -->
                <form id="deleteForm" action="{{ route('staff.cars.destroy', $car->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
                const uploadSection = document.querySelector('.image-upload-section');
                
                // Remove placeholder if exists
                const placeholder = document.getElementById('imagePlaceholder');
                if (placeholder) {
                    placeholder.remove();
                }

                // Update or create image preview
                let preview = document.getElementById('imagePreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    uploadSection.innerHTML = `<img src="${e.target.result}" alt="Car Preview" class="image-preview" id="imagePreview">`;
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
        if (confirm('⚠️ Are you sure you want to delete this vehicle?\n\nThis action cannot be undone and will remove:\n• Vehicle information\n• Associated bookings history\n• Vehicle image\n\nType "DELETE" to confirm.')) {
            const confirmation = prompt('Type "DELETE" to confirm deletion:');
            if (confirmation === 'DELETE') {
                document.getElementById('deleteForm').submit();
            } else {
                alert('Deletion cancelled. The confirmation text did not match.');
            }
        }
    }

    // Auto-uppercase plate number
    document.querySelector('input[name="plate_number"]').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });

    // Form validation
    document.getElementById('carForm').addEventListener('submit', function(e) {
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