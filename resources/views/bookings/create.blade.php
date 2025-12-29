@extends('layouts.app')
@section('title', 'Book ' . $car->full_name . ' - HASTA')

@push('styles')
<style>
    .booking-container {
        background: white;
        border-radius: 20px;
        margin: 40px auto;
        max-width: 1400px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .booking-header {
        background: #f8f8f8;
        padding: 40px;
        display: flex;
        align-items: center;
        gap: 40px;
    }
    .booking-car-image {
        width: 400px;
        height: 300px;
        object-fit: contain;
    }
    .booking-car-info {
        flex: 1;
    }
    .booking-car-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .booking-car-specs {
        display: flex;
        gap: 20px;
    }
    .booking-spec-box {
        background: white;
        padding: 15px 25px;
        border-radius: 10px;
        text-align: center;
    }
    .booking-spec-icon {
        font-size: 24px;
        margin-bottom: 5px;
    }
    .booking-spec-label {
        font-size: 12px;
        color: #888;
    }
    .booking-spec-value {
        font-size: 16px;
        font-weight: 600;
    }
    .booking-form {
        padding: 40px;
    }
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px;
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #d84444;
        box-shadow: 0 0 0 0.2rem rgba(216, 68, 68, 0.25);
    }
    .deposit-box {
        background: #f0f7ff;
        border: 1px solid #b3d9ff;
        border-radius: 10px;
        padding: 15px;
        margin: 20px 0;
    }
    .final-calculations {
        background: #f8f8f8;
        border-radius: 15px;
        padding: 30px;
        margin-top: 30px;
    }
    .final-price {
        font-size: 48px;
        color: #ff4444;
        font-weight: 700;
    }
    .btn-pay-now {
        background: #ff6b3d;
        color: white;
        border: none;
        padding: 15px 80px;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 700;
        float: right;
    }
    .btn-pay-now:hover {
        background: #ff5722;
    }
    .terms-checkbox {
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="booking-container">
        <!-- Booking Header with Car Info -->
        <div class="booking-header">
            <img src="{{ $car->image }}" class="booking-car-image" alt="{{ $car->full_name }}">
            <div class="booking-car-info">
                <div class="booking-car-title">⚡ {{ $car->full_name }}</div>
                <div class="booking-car-specs">
                    <div class="booking-spec-box">
                        <div class="booking-spec-icon">❄️</div>
                        <div class="booking-spec-label">Comfort</div>
                        <div class="booking-spec-value">Air Conditioner</div>
                    </div>
                    <div class="booking-spec-box">
                        <div class="booking-spec-icon">👥</div>
                        <div class="booking-spec-label">Capacity</div>
                        <div class="booking-spec-value">{{ $car->passengers }} Passengers</div>
                    </div>
                    <div class="booking-spec-box">
                        <div class="booking-spec-icon">⛽</div>
                        <div class="booking-spec-label">Fuel Type</div>
                        <div class="booking-spec-value">{{ $car->fuel_type }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <form action="{{ route('bookings.store') }}" method="POST" class="booking-form">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">

            <div class="row g-3">
                <!-- Pick-up Location -->
                <div class="col-md-12">
                    <label class="form-label">Pick-Up Location</label>
                    <input type="text" name="pickup_location" class="form-control" 
                           placeholder="Pick-Up Location" value="{{ old('pickup_location') }}" required>
                    @error('pickup_location')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Drop-Off Location -->
                <div class="col-md-12">
                    <label class="form-label">Drop-Off Location</label>
                    <input type="text" name="dropoff_location" class="form-control" 
                           placeholder="Drop-Off Location" value="{{ old('dropoff_location') }}">
                </div>

                <!-- Destination -->
                <div class="col-md-12">
                    <label class="form-label">Destination</label>
                    <input type="text" name="destination" class="form-control" 
                           placeholder="Destination" value="{{ old('destination') }}">
                </div>

                <!-- Pickup Date & Time -->
                <div class="col-md-6">
                    <label class="form-label">Pickup Date</label>
                    <input type="date" name="pickup_date" class="form-control" 
                           value="{{ old('pickup_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('pickup_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pickup Time</label>
                    <input type="time" name="pickup_time" class="form-control" 
                           value="{{ old('pickup_time', '09:00') }}">
                </div>

                <!-- Return Date & Time -->
                <div class="col-md-6">
                    <label class="form-label">Return Date</label>
                    <input type="date" name="return_date" class="form-control" 
                           value="{{ old('return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    @error('return_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Return Time</label>
                    <input type="time" name="return_time" class="form-control" 
                           value="{{ old('return_time', '09:00') }}">
                </div>

                <!-- Duration & Voucher -->
                <div class="col-md-6">
                    <label class="form-label">Duration (Days)</label>
                    <input type="text" class="form-control" value="-" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Voucher</label>
                    <select name="voucher" class="form-select">
                        <option value="">Select voucher</option>
                        @foreach($vouchers as $voucher)
                            <option value="{{ $voucher->code }}">{{ $voucher->code }} - {{ $voucher->description }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Remarks -->
                <div class="col-md-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="4" 
                              placeholder="For Additional Information">{{ old('remarks') }}</textarea>
                    <small class="text-muted">100 characters remaining</small>
                </div>
            </div>

            <!-- Deposit Info -->
            <div class="deposit-box">
                <strong>Deposit (RM): 100</strong>
            </div>

            <!-- Final Calculations -->
            <div class="final-calculations">
                <h4 style="font-weight: 700; margin-bottom: 20px;">Final Calculations</h4>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="final-price">RM340</div>
                    <button type="submit" class="btn btn-pay-now">Pay Now</button>
                </div>
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        I've read and accept the <a href="#" style="color: #007bff;">Terms and Conditions</a>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate duration when dates change
document.addEventListener('DOMContentLoaded', function() {
    const pickupDate = document.querySelector('[name="pickup_date"]');
    const returnDate = document.querySelector('[name="return_date"]');
    const durationField = document.querySelector('[type="text"][readonly]');
    const priceDisplay = document.querySelector('.final-price');
    const dailyRate = {{ $car->daily_rate }};

    function calculateDuration() {
        if (pickupDate.value && returnDate.value) {
            const start = new Date(pickupDate.value);
            const end = new Date(returnDate.value);
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            
            if (days > 0) {
                durationField.value = days;
                const total = days * dailyRate;
                priceDisplay.textContent = 'RM' + total;
            } else {
                durationField.value = '-';
            }
        }
    }

    pickupDate.addEventListener('change', calculateDuration);
    returnDate.addEventListener('change', calculateDuration);
});
</script>
@endpush