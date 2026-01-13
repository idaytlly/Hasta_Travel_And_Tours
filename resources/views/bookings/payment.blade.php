@extends('layouts.app')

@section('title', 'Payment')

@section('noFooter', true)

@section('content')

@include('components.booking-timeline', ['currentStep' => 2])

<style>
    body { padding-top: 70px; }
    .payment-container { 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 20px;
    }
    
    .back-arrow {
        display: inline-flex;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffd6d6;
        color: #d93025;
        font-weight: 900;
        font-size: 18px;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 10px rgba(217,48,37,0.25);
        transition: all 0.2s ease;
        text-decoration: none;
        margin-bottom: 20px;
    }
    
    .back-arrow:hover {
        transform: translateX(-2px) scale(1.1);
        background: #ffcaca;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #111;
    }
    
    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #111;
    }
    
    .info-group {
        margin-bottom: 20px;
    }
    
    .info-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 4px;
    }
    
    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #111;
    }
    
    .divider {
        border-top: 1px solid #e0e0e0;
        margin: 16px 0;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .price-row.total {
        border-top: 2px solid #e0e0e0;
        padding-top: 12px;
        margin-top: 12px;
        font-size: 18px;
        font-weight: 700;
    }
    
    .price-row.total .amount {
        color: #d93025;
    }
    
    .price-row.discount {
        color: #059669;
    }
    
    .qr-section {
        background: #f7f7f7;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .qr-code {
        background: #fff;
        padding: 16px;
        border-radius: 8px;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .qr-code img {
        width: 200px;
        height: 200px;
    }
    
    .qr-amount {
        font-size: 20px;
        font-weight: 700;
        color: #111;
        margin-top: 16px;
    }
    
    .instructions-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .instructions-box h4 {
        font-size: 14px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 12px;
    }
    
    .instructions-box ol {
        font-size: 13px;
        color: #1e40af;
        padding-left: 20px;
    }
    
    .instructions-box li {
        margin-bottom: 6px;
    }
    
    .upload-section {
        border-top: 2px solid #e0e0e0;
        padding-top: 20px;
    }
    
    .upload-label {
        font-size: 14px;
        font-weight: 600;
        color: #111;
        margin-bottom: 12px;
        display: block;
    }
    
    .upload-box {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 32px;
        text-align: center;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .upload-box:hover {
        border-color: #d93025;
        background: #fef2f2;
    }
    
    .upload-box.uploaded {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .upload-icon {
        font-size: 40px;
        color: #9ca3af;
        margin-bottom: 12px;
    }
    
    .upload-box.uploaded .upload-icon {
        color: #10b981;
    }
    
    .upload-text {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }
    
    .upload-subtext {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .file-input {
        display: none;
    }
    
    .btn-submit {
        width: 100%;
        background: #d93025;
        color: #fff;
        border: none;
        padding: 16px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.2s ease;
    }
    
    .btn-submit:hover:not(:disabled) {
        background: #b71c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(217,48,37,0.3);
    }
    
    .btn-submit:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }
    
    .notice-text {
        font-size: 12px;
        color: #6b7280;
        text-align: center;
        margin-top: 12px;
    }
    
    @media (max-width: 768px) {
        .payment-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="payment-container">
    <a href="{{ route('bookings.create', $booking->plate_no) }}" class="back-arrow" title="Back">←</a>
    
    <h1 class="page-title">Complete Your Payment</h1>
    
    <div class="payment-grid">
        <div class="card">
            <h2 class="card-title">Booking Summary</h2>
            
            <div class="info-group">
                <div class="info-label">Customer Name</div>
                <div class="info-value">{{ $booking->customer->name }}</div>
            </div>
            
            <div class="info-group">
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ $booking->customer->phone_no }}</div>
            </div>
            
            <div class="divider"></div>
            
            <div class="info-group">
                <div class="info-label">Vehicle</div>
                <div class="info-value">{{ $booking->vehicle->name }}</div>
                <div class="info-subtext" style="font-size: 13px; color: #666;">{{ $booking->plate_no }}</div>
            </div>
            
            <div class="divider"></div>
            
            <div class="info-group">
                <div class="info-label">Pickup</div>
                {{-- FIX: Robust parsing for Pickup --}}
                <div class="info-value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('F d, Y') }}</div>
                <div class="info-subtext" style="font-size: 13px; color: #666;">
                    {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }} • {{ $booking->pickup_details ?? $booking->pickup_location }}
                </div>
            </div>
            
            <div class="info-group">
                <div class="info-label">Return</div>
                {{-- FIX: Robust parsing for Return --}}
                <div class="info-value">{{ \Carbon\Carbon::parse($booking->return_date)->format('F d, Y') }}</div>
                <div class="info-subtext" style="font-size: 13px; color: #666;">
                    {{ \Carbon\Carbon::parse($booking->return_time)->format('g:i A') }} • {{ $booking->dropoff_details ?? $booking->dropoff_location }}
                </div>
            </div>
            
            <div class="divider"></div>
            
            @php
                // === DATE PARSING FIX ===
                // We ensure pickup_date is treated as a Y-m-d string before concatenation
                $pDate = \Carbon\Carbon::parse($booking->pickup_date)->format('Y-m-d');
                $pTime = $booking->pickup_time;
                $pickup = \Carbon\Carbon::parse($pDate . ' ' . $pTime);

                $rDate = \Carbon\Carbon::parse($booking->return_date)->format('Y-m-d');
                $rTime = $booking->return_time;
                $return = \Carbon\Carbon::parse($rDate . ' ' . $rTime);

                $hours = ceil($pickup->diffInHours($return));
                $subtotal = $hours * $booking->vehicle->price_perHour;
                $discount = 0;
                
                if($booking->voucher) {
                    $discount = ($subtotal * $booking->voucher->voucherAmount) / 100;
                }

                $deliveryFee = $booking->delivery_required ? 15 : 0;
            @endphp
            
            <div class="price-row">
                <span>Duration:</span>
                <span style="font-weight: 600;">{{ $hours }} hours</span>
            </div>
            
            <div class="price-row">
                <span>Subtotal:</span>
                <span style="font-weight: 600;">RM {{ number_format($subtotal, 2) }}</span>
            </div>
            
            @if($discount > 0)
            <div class="price-row discount">
                <span>Discount ({{ $booking->voucher->voucherAmount }}%):</span>
                <span style="font-weight: 600;">- RM {{ number_format($discount, 2) }}</span>
            </div>
            @endif
            @if($deliveryFee > 0)
            <div class="price-row">
                <span>Delivery Fee:</span>
                <span style="font-weight: 600;">RM {{ number_format($deliveryFee, 2) }}</span>
            </div>
            @endif
            <div class="price-row total">
                <span>Total:</span>
                <span class="amount">RM {{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>
        
        <div class="card">
            <h2 class="card-title">Payment via QR Code</h2>
            
            <form id="paymentForm" method="POST" action="{{ route('bookings.payment.store', $booking->booking_id) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="qr-section">
                    <p style="font-size: 13px; color: #666; margin-bottom: 16px;">Scan the QR code below to make payment</p>
                    
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=HASTA-PAYMENT-{{ $booking->booking_id }}-RM{{ number_format($booking->total_price, 2) }}" 
                             alt="Payment QR Code">
                    </div>
                    
                    <div class="qr-amount">Amount: RM {{ number_format($booking->total_price, 2) }}</div>
                    <p style="font-size: 13px; color: #666; margin-top: 8px;">HASTA Car Rental Service</p>
                </div>
                
                <div class="instructions-box">
                    <h4>Payment Instructions:</h4>
                    <ol>
                        <li>Scan the QR code using your banking app or e-wallet</li>
                        <li>Complete the payment of RM {{ number_format($booking->total_price, 2) }}</li>
                        <li>Take a screenshot or download the payment receipt</li>
                        <li>Upload the receipt below</li>
                    </ol>
                </div>
                
                <div class="upload-section">
                    <label class="upload-label">Upload Payment Receipt (PDF, PNG, or JPG) *</label>
                    
                    <input type="file" name="payment_proof" id="receiptUpload" class="file-input" accept=".pdf,.png,.jpg,.jpeg" required>
                    <input type="hidden" name="payment_method" value="online">
                    
                    <label for="receiptUpload" class="upload-box" id="uploadBox">
                        <div class="upload-icon" id="uploadIcon">📤</div>
                        <div class="upload-text" id="uploadText">Click to upload receipt</div>
                        <div class="upload-subtext">PDF, PNG, or JPG (max 5MB)</div>
                    </label>
                    
                    <div id="fileName" style="font-size: 13px; color: #059669; margin-top: 8px; display: none;"></div>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    Upload Receipt to Continue
                </button>
                
                <p class="notice-text">Your booking will be confirmed after payment verification</p>
            </form>
        </div>
    </div>
</div>

<script>
    const fileInput = document.getElementById('receiptUpload');
    const uploadBox = document.getElementById('uploadBox');
    const uploadIcon = document.getElementById('uploadIcon');
    const uploadText = document.getElementById('uploadText');
    const submitBtn = document.getElementById('submitBtn');
    const fileName = document.getElementById('fileName');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const validTypes = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a PDF, PNG, or JPG file only.');
                fileInput.value = '';
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB.');
                fileInput.value = '';
                return;
            }
            
            // Update UI
            uploadBox.classList.add('uploaded');
            uploadIcon.textContent = '✓';
            uploadText.textContent = 'Receipt uploaded successfully!';
            fileName.textContent = 'File: ' + file.name;
            fileName.style.display = 'block';
            
            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Confirm Payment';
        }
    });
</script>
@endsection