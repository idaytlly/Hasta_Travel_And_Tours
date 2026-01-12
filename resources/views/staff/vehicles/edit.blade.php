{{-- resources/views/staff/vehicles/edit.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Edit Vehicle')
@section('page-title', 'Edit Vehicle')
@section('page-subtitle', 'Update vehicle information')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.vehicles.index') }}">Vehicles</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.vehicles.show', $vehicle->plate_no) }}">{{ $vehicle->plate_no }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Vehicle: {{ $vehicle->name }} ({{ $vehicle->plate_no }})</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('staff.vehicles.update', $vehicle->plate_no) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h5 class="mb-3">Basic Information</h5>
                    
                    <div class="mb-3">
                        <label for="plate_no" class="form-label">Plate Number *</label>
                        <input type="text" class="form-control @error('plate_no') is-invalid @enderror" 
                               id="plate_no" name="plate_no" value="{{ old('plate_no', $vehicle->plate_no) }}" required readonly>
                        <div class="form-text">Plate number cannot be changed</div>
                        @error('plate_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Vehicle Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $vehicle->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="model" class="form-label">Model *</label>
                        <input type="text" class="form-control @error('model') is-invalid @enderror" 
                               id="model" name="model" value="{{ old('model', $vehicle->model) }}" required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="vehicle_type" class="form-label">Vehicle Type *</label>
                        <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                id="vehicle_type" name="vehicle_type" required>
                            <option value="">Select Type</option>
                            @foreach($vehicleTypes as $type)
                                <option value="{{ $type }}" {{ old('vehicle_type', $vehicle->vehicle_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Specifications -->
                <div class="col-md-6">
                    <h5 class="mb-3">Specifications</h5>
                    
                    <div class="mb-3">
                        <label for="transmission" class="form-label">Transmission *</label>
                        <select class="form-select @error('transmission') is-invalid @enderror" 
                                id="transmission" name="transmission" required>
                            <option value="">Select Transmission</option>
                            @foreach($transmissionTypes as $type)
                                <option value="{{ $type }}" {{ old('transmission', $vehicle->transmission) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('transmission')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="fuel_type" class="form-label">Fuel Type *</label>
                        <select class="form-select @error('fuel_type') is-invalid @enderror" 
                                id="fuel_type" name="fuel_type" required>
                            <option value="">Select Fuel Type</option>
                            @foreach($fuelTypes as $type)
                                <option value="{{ $type }}" {{ old('fuel_type', $vehicle->fuel_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('fuel_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="seating_capacity" class="form-label">Seating Capacity *</label>
                        <input type="number" class="form-control @error('seating_capacity') is-invalid @enderror" 
                               id="seating_capacity" name="seating_capacity" 
                               value="{{ old('seating_capacity', $vehicle->seating_capacity) }}" min="1" max="100" required>
                        @error('seating_capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <!-- Pricing -->
            <h5 class="mb-3">Pricing</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price_perHour" class="form-label">Price per Hour (RM) *</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" step="0.01" class="form-control @error('price_perHour') is-invalid @enderror" 
                                   id="price_perHour" name="price_perHour" 
                                   value="{{ old('price_perHour', $vehicle->price_perHour) }}" min="0" required>
                            @error('price_perHour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price_perDay" class="form-label">Price per Day (RM) *</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" step="0.01" class="form-control @error('price_perDay') is-invalid @enderror" 
                                   id="price_perDay" name="price_perDay" 
                                   value="{{ old('price_perDay', $vehicle->price_perDay) }}" min="0" required>
                            @error('price_perDay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features -->
            <div class="mb-3">
                <label class="form-label">Features</label>
                <div class="row">
                    @php
                        $currentFeatures = json_decode($vehicle->features ?? '[]', true);
                    @endphp
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Air Conditioning" id="feature_ac"
                                   {{ in_array('Air Conditioning', $currentFeatures) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_ac">
                                Air Conditioning
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Power Steering" id="feature_steering"
                                   {{ in_array('Power Steering', $currentFeatures) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_steering">
                                Power Steering
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Bluetooth" id="feature_bluetooth"
                                   {{ in_array('Bluetooth', $currentFeatures) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_bluetooth">
                                Bluetooth
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="USB Port" id="feature_usb"
                                   {{ in_array('USB Port', $currentFeatures) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_usb">
                                USB Port
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Reverse Camera" id="feature_camera"
                                   {{ in_array('Reverse Camera', $currentFeatures) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_camera">
                                Reverse Camera
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="GPS Navigation" id="feature_gps"
                                   {{ in_array('GPS Navigation', $currentFeatures) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_gps">
                                GPS Navigation
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $vehicle->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Current Images -->
            @if($vehicle->images)
                @php
                    $images = json_decode($vehicle->images, true);
                @endphp
                <div class="mb-3">
                    <label class="form-label">Current Images</label>
                    <div class="row">
                        @foreach($images as $index => $image)
                            <div class="col-md-3 mb-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" style="height: 150px; object-fit: cover;" alt="Vehicle Image">
                                    <div class="form-check position-absolute top-0 start-0 m-2">
                                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image }}" id="delete_{{ $index }}">
                                        <label class="form-check-label text-white" for="delete_{{ $index }}" style="text-shadow: 1px 1px 2px black;">Delete</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- New Images -->
            <div class="mb-3">
                <label for="images" class="form-label">Add New Images</label>
                <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                       id="images" name="images[]" multiple accept="image/*">
                <div class="form-text">You can upload multiple images. Maximum size: 5MB per image. Leave empty to keep current images.</div>
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Status -->
            <div class="mb-3">
                <label for="availability_status" class="form-label">Availability Status *</label>
                <select class="form-select @error('availability_status') is-invalid @enderror" 
                        id="availability_status" name="availability_status" required>
                    <option value="available" {{ old('availability_status', $vehicle->availability_status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="maintenance" {{ old('availability_status', $vehicle->availability_status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="booked" {{ old('availability_status', $vehicle->availability_status) == 'booked' ? 'selected' : '' }}>Booked</option>
                </select>
                @error('availability_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Maintenance Notes (if status is maintenance) -->
            <div class="mb-3" id="maintenanceNotes" style="{{ old('availability_status', $vehicle->availability_status) == 'maintenance' ? '' : 'display: none;' }}">
                <label for="maintenance_notes" class="form-label">Maintenance Notes</label>
                <textarea class="form-control @error('maintenance_notes') is-invalid @enderror" 
                          id="maintenance_notes" name="maintenance_notes" rows="2">{{ old('maintenance_notes', $vehicle->maintenance_notes) }}</textarea>
                @error('maintenance_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('staff.vehicles.show', $vehicle->plate_no) }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Update Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('availability_status');
        const maintenanceNotes = document.getElementById('maintenanceNotes');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'maintenance') {
                maintenanceNotes.style.display = 'block';
            } else {
                maintenanceNotes.style.display = 'none';
            }
        });
    });
</script>
@endpush