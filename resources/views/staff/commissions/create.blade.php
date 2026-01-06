@extends('layouts.staff')

@section('title', 'Submit Commission')
@section('page-title', 'Submit Commission')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --success: #22c55e;
        --warning: #f59e0b;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f1f5f9;
        --border: #e2e8f0;
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, #b91c1c 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(220, 38, 38, 0.2);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .service-option {
        position: relative;
        cursor: pointer;
        padding: 1.5rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        transition: all 0.2s;
        text-align: center;
    }

    .service-option:hover {
        border-color: var(--primary);
        background: rgba(220, 38, 38, 0.05);
    }

    .service-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .service-option input[type="radio"]:checked + .service-content {
        color: var(--primary);
    }

    .service-option input[type="radio"]:checked ~ * {
        border-color: var(--primary);
    }

    .service-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: all 0.2s;
    }

    .service-option input[type="radio"]:checked ~ .service-icon {
        background: var(--primary) !important;
        color: white !important;
        transform: scale(1.1);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.75rem;
        font-size: 0.9375rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 0.875rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .info-box {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--info);
        padding: 1.25rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .btn-submit {
        background: var(--primary);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.3);
    }
</style>

<div class="container-fluid py-4">
    <div class="form-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2" style="font-size: 1.75rem; font-weight: 700;">
                        <i class="fas fa-plus-circle me-2"></i>Submit Commission
                    </h1>
                    <p class="mb-0" style="opacity: 0.9;">Submit your extra service commission for admin approval</p>
                </div>
                <a href="{{ route('staff.commissions.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="info-box">
                <div class="d-flex align-items-start gap-3">
                    <i class="fas fa-info-circle" style="color: var(--info); font-size: 1.25rem; margin-top: 2px;"></i>
                    <div style="color: #1e40af;">
                        <strong>Commission Guidelines</strong>
                        <ul class="mb-0 mt-2" style="font-size: 0.875rem;">
                            <li>Car washing: RM 10 - 30</li>
                            <li>Detailing service: RM 50 - 150</li>
                            <li>Fuel top-up service: RM 5 - 20</li>
                            <li>Other services: RM 5 - 100</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('staff.commissions.store') }}" method="POST">
                @csrf

                <!-- Service Type -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-cog me-1"></i>Service Type
                    </label>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <label class="service-option">
                                <input type="radio" name="service_type" value="car_wash" required>
                                <div class="service-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                                    <i class="fas fa-soap"></i>
                                </div>
                                <div class="service-content">
                                    <strong style="font-size: 0.875rem;">Car Wash</strong>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="service-option">
                                <input type="radio" name="service_type" value="detailing" required>
                                <div class="service-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                    <i class="fas fa-sparkles"></i>
                                </div>
                                <div class="service-content">
                                    <strong style="font-size: 0.875rem;">Detailing</strong>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="service-option">
                                <input type="radio" name="service_type" value="fuel_top_up" required>
                                <div class="service-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                                    <i class="fas fa-gas-pump"></i>
                                </div>
                                <div class="service-content">
                                    <strong style="font-size: 0.875rem;">Fuel Top-Up</strong>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="service-option">
                                <input type="radio" name="service_type" value="other" required>
                                <div class="service-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="service-content">
                                    <strong style="font-size: 0.875rem;">Other</strong>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('service_type')
                        <div class="text-danger mt-2" style="font-size: 0.875rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Related Booking (Optional) -->
                <div class="mb-4">
                    <label for="booking_id" class="form-label">
                        <i class="fas fa-link me-1"></i>Related Booking (Optional)
                    </label>
                    <select name="booking_id" id="booking_id" class="form-select @error('booking_id') is-invalid @enderror">
                        <option value="">-- Select if related to a booking --</option>
                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                #{{ $booking->booking_reference }} - 
                                {{ $booking->car->brand }} {{ $booking->car->model }} - 
                                {{ $booking->user->name ?? 'Guest' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Select a booking if this service is related to a specific rental
                    </small>
                    @error('booking_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-1"></i>Service Description
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4" 
                        class="form-control @error('description') is-invalid @enderror" 
                        placeholder="Describe the service you provided in detail..."
                        required>{{ old('description') }}</textarea>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Minimum 10 characters. Be specific about what you did.
                    </small>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label for="amount" class="form-label">
                        <i class="fas fa-dollar-sign me-1"></i>Commission Amount (RM)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--light); border: 2px solid var(--border); border-right: none;">
                            <i class="fas fa-money-bill-wave text-success"></i>
                        </span>
                        <input 
                            type="number" 
                            name="amount" 
                            id="amount" 
                            class="form-control @error('amount') is-invalid @enderror" 
                            placeholder="0.00" 
                            step="0.01" 
                            min="1" 
                            max="1000"
                            value="{{ old('amount') }}"
                            required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Enter amount between RM 1.00 and RM 1000.00
                    </small>
                </div>

                <!-- Submit Button -->
                <div class="d-flex gap-3 justify-content-end mt-5">
                    <a href="{{ route('staff.commissions.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-submit btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Submit for Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Highlight selected service type
document.querySelectorAll('input[name="service_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.service-option').forEach(option => {
            option.style.borderColor = 'var(--border)';
            option.style.background = 'white';
        });
        if(this.checked) {
            this.closest('.service-option').style.borderColor = 'var(--primary)';
            this.closest('.service-option').style.background = 'rgba(220, 38, 38, 0.05)';
        }
    });
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const description = document.getElementById('description').value.trim();
    const amount = parseFloat(document.getElementById('amount').value);
    
    if(description.length < 10) {
        e.preventDefault();
        alert('Description must be at least 10 characters long.');
        return false;
    }
    
    if(amount < 1 || amount > 1000) {
        e.preventDefault();
        alert('Amount must be between RM 1.00 and RM 1000.00');
        return false;
    }
});
</script>
@endsection