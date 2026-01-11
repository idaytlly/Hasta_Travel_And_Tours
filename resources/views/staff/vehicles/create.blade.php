{{-- resources/views/staff/vehicles/create.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Add New Vehicle')
@section('page-title', 'Add New Vehicle')
@section('page-subtitle', 'Add a new vehicle to the fleet')

@section('page-header')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-0">Add New Vehicle</h1>
            <p class="text-muted mb-0">Fill in the details to add a new vehicle</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.vehicles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('staff.vehicles.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h5 class="mb-3">Basic Information</h5>
                    
                    <div class="mb-3">
                        <label for="plate_no" class="form-label">Plate Number *</label>
                        <input type="text" class="form-control @error('plate_no') is-invalid @enderror" 
                               id="plate_no" name="plate_no" value="{{ old('plate_no') }}" required>
                        @error('plate_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Vehicle Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="model" class="form-label">Model *</label>
                        <input type="text" class="form-control @error('model') is-invalid @enderror" 
                               id="model" name="model" value="{{ old('model') }}" required>
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
                                <option value="{{ $type }}" {{ old('vehicle_type') == $type ? 'selected' : '' }}>
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
                                <option value="{{ $type }}" {{ old('transmission') == $type ? 'selected' : '' }}>
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
                                <option value="{{ $type }}" {{ old('fuel_type') == $type ? 'selected' : '' }}>
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
                               value="{{ old('seating_capacity', 4) }}" min="1" max="100" required>
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
                                   value="{{ old('price_perHour', 10.00) }}" min="0" required>
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
                                   value="{{ old('price_perDay', 80.00) }}" min="0" required>
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
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Air Conditioning" id="feature_ac">
                            <label class="form-check-label" for="feature_ac">
                                Air Conditioning
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Power Steering" id="feature_steering">
                            <label class="form-check-label" for="feature_steering">
                                Power Steering
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Bluetooth" id="feature_bluetooth">
                            <label class="form-check-label" for="feature_bluetooth">
                                Bluetooth
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="USB Port" id="feature_usb">
                            <label class="form-check-label" for="feature_usb">
                                USB Port
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="Reverse Camera" id="feature_camera">
                            <label class="form-check-label" for="feature_camera">
                                Reverse Camera
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="GPS Navigation" id="feature_gps">
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
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Images -->
            <div class="mb-3">
                <label for="images" class="form-label">Vehicle Images</label>
                <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                       id="images" name="images[]" multiple accept="image/*">
                <div class="form-text">You can upload multiple images. Maximum size: 5MB per image.</div>
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Status -->
            <div class="mb-3">
                <label for="availability_status" class="form-label">Availability Status *</label>
                <select class="form-select @error('availability_status') is-invalid @enderror" 
                        id="availability_status" name="availability_status" required>
                    <option value="available" {{ old('availability_status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="maintenance" {{ old('availability_status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
                @error('availability_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Maintenance Notes (if status is maintenance) -->
            <div class="mb-3" id="maintenanceNotes" style="{{ old('availability_status') == 'maintenance' ? '' : 'display: none;' }}">
                <label for="maintenance_notes" class="form-label">Maintenance Notes</label>
                <textarea class="form-control @error('maintenance_notes') is-invalid @enderror" 
                          id="maintenance_notes" name="maintenance_notes" rows="2">{{ old('maintenance_notes') }}</textarea>
                @error('maintenance_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <button type="reset" class="btn btn-outline-secondary me-3">Reset</button>
                <button type="submit" class="btn btn-primary">Add Vehicle</button>
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