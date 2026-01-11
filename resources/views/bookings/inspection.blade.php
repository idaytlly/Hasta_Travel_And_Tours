@extends('layouts.app')

@section('title', 'Vehicle Inspection - ' . $booking->vehicle->name)

@section('noFooter', true)

@section('content')
<style>
    body {
        padding-top: 70px;
        background: #f9fafb;
    }
    
    .inspection-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .inspection-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }
    
    .header-content h1 {
        font-size: 24px;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    
    .inspection-type-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .type-pickup {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #10b981;
    }
    
    .type-dropoff {
        background: #fef3c7;
        color: #92400e;
        border: 2px solid #fbbf24;
    }
    
    .vehicle-info-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .vehicle-info-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    
    .vehicle-info-card p {
        font-size: 14px;
        color: #6b7280;
    }
    
    .inspection-form {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #111;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .inspection-section {
        margin-bottom: 32px;
    }
    
    .required-label {
        color: #ef4444;
        font-weight: 700;
        margin-left: 4px;
    }
    
    /* Car Photos Grid */
    .car-photos-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .photo-upload-box {
        border: 3px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .photo-upload-box:hover {
        border-color: #d93025;
        background: #fef2f2;
    }
    
    .photo-upload-box.has-image {
        border-color: #10b981;
        background: #f0fdf4;
        border-style: solid;
    }
    
    .photo-upload-box input[type="file"] {
        display: none;
    }
    
    .upload-icon {
        font-size: 48px;
        margin-bottom: 12px;
        color: #9ca3af;
    }
    
    .upload-label {
        font-size: 15px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    
    .upload-hint {
        font-size: 12px;
        color: #9ca3af;
    }
    
    .photo-preview-container {
        position: relative;
        width: 100%;
    }
    
    .photo-preview-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    
    .remove-photo-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    
    .photo-label-display {
        font-size: 13px;
        font-weight: 600;
        color: #10b981;
    }
    
    /* Fuel Reading Section */
    .fuel-reading-upload {
        border: 3px dashed #d1d5db;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .fuel-reading-upload:hover {
        border-color: #d93025;
        background: #fef2f2;
    }
    
    .fuel-reading-upload.has-image {
        border-color: #10b981;
        background: #f0fdf4;
        border-style: solid;
    }
    
    .fuel-reading-upload input[type="file"] {
        display: none;
    }
    
    .fuel-preview-img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        margin: 12px 0;
    }
    
    /* Remarks Section */
    .remarks-field {
        margin-top: 16px;
    }
    
    .remarks-field textarea {
        width: 100%;
        padding: 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        resize: vertical;
        min-height: 120px;
        transition: all 0.2s ease;
    }
    
    .remarks-field textarea:focus {
        outline: none;
        border-color: #d93025;
        box-shadow: 0 0 0 3px rgba(217, 48, 37, 0.1);
    }
    
    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
    }
    
    .signature-section {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f3f4f6;
    }
    
    .signature-pad {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: crosshair;
        margin-top: 12px;
        background: white;
    }
    
    .signature-controls {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }
    
    .btn-clear-signature {
        padding: 8px 16px;
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }
    
    .btn-clear-signature:hover {
        background: #4b5563;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f3f4f6;
    }
    
    .btn-submit {
        flex: 1;
        padding: 16px;
        background: #d93025;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-submit:hover {
        background: #b71c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(217, 48, 37, 0.3);
    }
    
    .btn-submit:disabled {
        background: #d1d5db;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-cancel {
        padding: 16px 32px;
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cancel:hover {
        background: #4b5563;
    }
    
    .validation-message {
        background: #fee2e2;
        border: 2px solid #ef4444;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 12px;
        }
        
        .car-photos-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>

<div class="inspection-container">
    <div class="inspection-header">
        <div class="header-content">
            <div>
                <h1>Vehicle Inspection</h1>
                <p style="color: #6b7280; font-size: 14px;">Complete thorough inspection before {{ $inspectionType === 'pickup' ? 'picking up' : 'returning' }} the vehicle</p>
            </div>
            <span class="inspection-type-badge type-{{ $inspectionType }}">
                {{ $inspectionType === 'pickup' ? 'Pick Up' : 'Drop Off' }} Inspection
            </span>
        </div>
        
        <div class="vehicle-info-card">
            <div>
                <h3>{{ $booking->vehicle->name }}</h3>
                <p>{{ $booking->plate_no }} • Booking #{{ $booking->booking_id }}</p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('bookings.inspection.store', ['id' => $booking->booking_id, 'type' => $inspectionType]) }}" method="POST" enctype="multipart/form-data" class="inspection-form" id="inspection-form">
        @csrf
        
        <div id="validation-errors"></div>
        
        <!-- Car Photos - 4 Sides -->
        <div class="inspection-section">
            <h2 class="section-title">
                Vehicle Photos (4 Sides) <span class="required-label">*</span>
            </h2>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                Please upload clear photos of all 4 sides of the vehicle
            </p>
            
            <div class="car-photos-grid">
                <!-- Front Photo -->
                <label class="photo-upload-box" id="front-box">
                    <input type="file" name="photo_front" accept="image/*" data-side="front">
                    <div class="upload-content">
                        <div class="upload-icon">+</div>
                        <div class="upload-label">Front View</div>
                        <div class="upload-hint">Click to upload</div>
                    </div>
                </label>
                
                <!-- Back Photo -->
                <label class="photo-upload-box" id="back-box">
                    <input type="file" name="photo_back" accept="image/*" data-side="back">
                    <div class="upload-content">
                        <div class="upload-icon">+</div>
                        <div class="upload-label">Back View</div>
                        <div class="upload-hint">Click to upload</div>
                    </div>
                </label>
                
                <!-- Left Photo -->
                <label class="photo-upload-box" id="left-box">
                    <input type="file" name="photo_left" accept="image/*" data-side="left">
                    <div class="upload-content">
                        <div class="upload-icon">+</div>
                        <div class="upload-label">Left Side View</div>
                        <div class="upload-hint">Click to upload</div>
                    </div>
                </label>
                
                <!-- Right Photo -->
                <label class="photo-upload-box" id="right-box">
                    <input type="file" name="photo_right" accept="image/*" data-side="right">
                    <div class="upload-content">
                        <div class="upload-icon">+</div>
                        <div class="upload-label">Right Side View</div>
                        <div class="upload-hint">Click to upload</div>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- Fuel Bar Reading Photo -->
        <div class="inspection-section">
            <h2 class="section-title">
                Fuel Bar Reading <span class="required-label">*</span>
            </h2>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
                Upload a clear photo of the fuel gauge showing the current fuel level
            </p>
            
            <label class="fuel-reading-upload" id="fuel-box">
                <input type="file" name="photo_fuel" accept="image/*" id="fuel-input">
                <div class="upload-content">
                    <div class="upload-icon">+</div>
                    <div class="upload-label">Fuel Gauge Photo</div>
                    <div class="upload-hint">Click to upload fuel gauge reading</div>
                </div>
            </label>
        </div>
        
        <!-- Remarks -->
        <div class="inspection-section">
            <h2 class="section-title">
                Inspection Remarks <span class="required-label">*</span>
            </h2>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 12px;">
                Please provide detailed remarks about the vehicle condition, any damages, scratches, or issues observed
            </p>
            
            <div class="remarks-field">
                <textarea 
                    name="remarks" 
                    id="remarks" 
                    placeholder="Describe the vehicle condition in detail. Include any observations about exterior, interior, cleanliness, damages, scratches, dents, or any other relevant details..."
                    maxlength="1000"></textarea>
                <div class="char-counter">
                    <span id="char-count">0</span> / 1000 characters
                </div>
            </div>
        </div>
        
        <!-- Signature -->
        <div class="signature-section">
            <h2 class="section-title">
                Signature <span class="required-label">*</span>
            </h2>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 12px;">
                I confirm that the above inspection is accurate and complete.
            </p>
            <canvas id="signature-pad" class="signature-pad" width="800" height="200"></canvas>
            <div class="signature-controls">
                <button type="button" class="btn-clear-signature" id="clear-signature">Clear Signature</button>
            </div>
            <input type="hidden" name="signature" id="signature-data">
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('bookings.show', $booking->booking_id) }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit" id="submit-btn">Submit Inspection</button>
        </div>
    </form>
</div>

const fuelInput = document.getElementById('fuel-input');

<script>
// Photo Upload Handlers
document.querySelectorAll('.photo-upload-box input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const box = this.closest('.photo-upload-box');
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                box.classList.add('has-image');
                box.innerHTML = `
                    <div class="photo-preview-container">
                        <img src="${e.target.result}" alt="Preview" class="photo-preview-img">
                        <button type="button" class="remove-photo-btn">×</button>
                        <div class="photo-label-display">${input.dataset.side ? input.dataset.side.charAt(0).toUpperCase() + input.dataset.side.slice(1) + ' view' : 'Fuel gauge'} uploaded</div>
                    </div>
                `;
                
                // Re-attach input
                const newInput = input.cloneNode(true);
                newInput.style.display = 'none';
                box.appendChild(newInput);
                
                // Add remove handler
                box.querySelector('.remove-photo-btn').addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    resetPhotoBox(box, input.name, input.dataset.side);
                });
                
                // Re-attach change event
                newInput.addEventListener('change', input.onchange);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Fuel photo upload
document.getElementById('fuel-input').addEventListener('change', function(e) {
    const box = document.getElementById('fuel-box');
    const file = this.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            box.classList.add('has-image');
            box.innerHTML = `
                <div class="photo-preview-container" style="width: 100%; text-align: center;">
                    <img src="${e.target.result}" alt="Fuel Gauge" class="fuel-preview-img">
                    <button type="button" class="remove-photo-btn">×</button>
                    <div class="photo-label-display">Fuel gauge photo uploaded</div>
                </div>
            `;
            
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = 'photo_fuel';
            newInput.accept = 'image/*';
            newInput.id = 'fuel-input';
            newInput.style.display = 'none';
            box.appendChild(newInput);
            
            box.querySelector('.remove-photo-btn').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                resetFuelBox();
            });
            
            newInput.addEventListener('change', document.getElementById('fuel-input').onchange);
        };
        reader.readAsDataURL(file);
    }
});

function resetPhotoBox(box, inputName, side) {
    const sideLabels = {
        'front': 'Front View',
        'back': 'Back View',
        'left': 'Left Side View',
        'right': 'Right Side View'
    };
    
    box.classList.remove('has-image');
    box.innerHTML = `
        <input type="file" name="${inputName}" accept="image/*" data-side="${side}">
        <div class="upload-content">
            <div class="upload-icon">+</div>
            <div class="upload-label">${sideLabels[side]}</div>
            <div class="upload-hint">Click to upload</div>
        </div>
    `;
    
    box.querySelector('input').addEventListener('change', function(e) {
        document.querySelector(`input[name="${inputName}"]`).dispatchEvent(new Event('change'));
    });
}

function resetFuelBox() {
    const box = document.getElementById('fuel-box');
    box.classList.remove('has-image');
    box.innerHTML = `
        <input type="file" name="photo_fuel" accept="image/*" id="fuel-input">
        <div class="upload-content">
            <div class="upload-icon">+</div>
            <div class="upload-label">Fuel Gauge Photo</div>
            <div class="upload-hint">Click to upload fuel gauge reading</div>
        </div>
    `;
    
    document.getElementById('fuel-input').addEventListener('change', function(e) {
        // Re-trigger the original handler
        const event = new Event('change', { bubbles: true });
        this.dispatchEvent(event);
    });
}

// Character counter for remarks
const remarksField = document.getElementById('remarks');
const charCount = document.getElementById('char-count');

remarksField.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});

// Signature Pad
const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
const signatureData = document.getElementById('signature-data');
let isDrawing = false;

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseout', stopDrawing);

// Touch support
canvas.addEventListener('touchstart', function(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const mouseEvent = new MouseEvent('mousedown', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
});

canvas.addEventListener('touchmove', function(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent('mousemove', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
});

canvas.addEventListener('touchend', function(e) {
    e.preventDefault();
    const mouseEvent = new MouseEvent('mouseup', {});
    canvas.dispatchEvent(mouseEvent);
});

function startDrawing(e) {
    isDrawing = true;
    const rect = canvas.getBoundingClientRect();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = canvas.getBoundingClientRect();
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.strokeStyle = '#111';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.stroke();
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        signatureData.value = canvas.toDataURL();
    }
}

document.getElementById('clear-signature').addEventListener('click', function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    signatureData.value = '';
});

// Form Validation
document.getElementById('inspection-form').addEventListener('submit', function(e) {
    console.log('Form submit triggered');
    const errors = [];
    
    // Check photos
    const photoInputs = ['photo_front', 'photo_back', 'photo_left', 'photo_right', 'photo_fuel'];
    // Car photos
    ['photo_front', 'photo_back', 'photo_left', 'photo_right'].forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input || input.files.length === 0) {
            errors.push(`${name.replace('photo_', '')} photo is required`);
        }
    });

    // Fuel photo (SPECIAL CASE)
    if (!fuelInput || fuelInput.files.length === 0) {
        errors.push('fuel photo is required');
    }

    
    // Check remarks
    console.log('Remarks length:', remarksField.value.trim().length);
    if (remarksField.value.trim().length < 10) {
        errors.push('Remarks must be at least 10 characters long');
    }
    
    // Check signature
    console.log('Signature data exists:', !!signatureData.value);
    if (!signatureData.value) {
        errors.push('Signature is required');
    }
    
    console.log('Total errors:', errors.length);
    
    if (errors.length > 0) {
        e.preventDefault();
        const errorDiv = document.getElementById('validation-errors');
        errorDiv.innerHTML = `
            <div class="validation-message">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    ${errors.map(err => `<li>${err}</li>`).join('')}
                </ul>
            </div>
        `;
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return false;
    }
    
    console.log('Form validation passed, submitting...');
});
</script>
@endsection