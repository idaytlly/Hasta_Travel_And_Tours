@extends('layouts.staff')

@section('title', 'Create Booking')
@section('page-title', 'Create Booking')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create New Booking</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.bookings.store') }}" method="POST">
                        @csrf
                        
                        <!-- Customer Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Customer</label>
                            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Vehicle Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Vehicle</label>
                            <select name="car_id" class="form-select @error('car_id') is-invalid @enderror" required>
                                <option value="">-- Select Vehicle --</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" data-rate="{{ $car->daily_rate }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }} ({{ $car->year }}) - 
                                        RM {{ number_format($car->daily_rate, 2) }}/day
                                        @if($car->category == 'motorcycle')
                                            <i class="fas fa-motorcycle ms-1"></i>
                                        @else
                                            <i class="fas fa-car ms-1"></i>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pickup Date</label>
                                <input type="date" name="pickup_date" 
                                       class="form-control @error('pickup_date') is-invalid @enderror"
                                       value="{{ old('pickup_date') }}"
                                       min="{{ date('Y-m-d') }}" 
                                       required>
                                @error('pickup_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Return Date</label>
                                <input type="date" name="return_date" 
                                       class="form-control @error('return_date') is-invalid @enderror"
                                       value="{{ old('return_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                       required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Price Preview -->
                        <div class="alert alert-info mb-4" id="pricePreview">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Estimated Total:</strong>
                                    <span id="totalPrice">RM 0.00</span>
                                </div>
                                <div>
                                    <span id="duration">0 days</span>
                                    <span class="ms-2">×</span>
                                    <span id="dailyRate" class="ms-2">RM 0.00/day</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Additional Notes (Optional)</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Any special requests or notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('staff.bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Create Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carSelect = document.querySelector('select[name="car_id"]');
    const pickupDate = document.querySelector('input[name="pickup_date"]');
    const returnDate = document.querySelector('input[name="return_date"]');
    const totalPriceEl = document.getElementById('totalPrice');
    const durationEl = document.getElementById('duration');
    const dailyRateEl = document.getElementById('dailyRate');
    
    function calculatePrice() {
        const selectedOption = carSelect.options[carSelect.selectedIndex];
        const dailyRate = selectedOption ? parseFloat(selectedOption.getAttribute('data-rate') || 0) : 0;
        const pickup = new Date(pickupDate.value);
        const returnD = new Date(returnDate.value);
        
        let duration = 0;
        if (pickupDate.value && returnDate.value && returnD > pickup) {
            const diffTime = Math.abs(returnD - pickup);
            duration = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }
        
        const total = duration * dailyRate;
        
        dailyRateEl.textContent = `RM ${dailyRate.toFixed(2)}/day`;
        durationEl.textContent = `${duration} days`;
        totalPriceEl.textContent = `RM ${total.toFixed(2)}`;
    }
    
    carSelect.addEventListener('change', calculatePrice);
    pickupDate.addEventListener('change', function() {
        if (this.value) {
            const minReturnDate = new Date(this.value);
            minReturnDate.setDate(minReturnDate.getDate() + 1);
            returnDate.min = minReturnDate.toISOString().split('T')[0];
            
            if (returnDate.value && new Date(returnDate.value) <= new Date(this.value)) {
                returnDate.value = '';
            }
        }
        calculatePrice();
    });
    returnDate.addEventListener('change', calculatePrice);
    
    // Initial calculation
    calculatePrice();
});
</script>
@endpush
@endsection