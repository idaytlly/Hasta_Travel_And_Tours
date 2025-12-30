<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book {{ $car->full_name }} - HASTA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #d84444; padding: 15px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header-container { max-width: 1400px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { background: white; color: #d84444; padding: 8px 20px; font-weight: 700; font-size: 1.5rem; border-radius: 4px; letter-spacing: 2px; }
        .main-container { max-width: 1400px; margin: 0 auto; padding: 0 20px; background: white; min-height: calc(100vh - 180px); }
        .page-header { display: flex; align-items: center; padding: 30px 0 20px; gap: 15px; }
        .back-btn { width: 50px; height: 50px; background: black; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; }
        .back-btn i { color: white; font-size: 20px; }
        .page-title { font-size: 32px; font-weight: 700; }
        .form-layout { display: grid; grid-template-columns: 400px 1fr; gap: 40px; padding: 20px 0; }
        .car-image { width: 100%; height: auto; margin-bottom: 30px; border-radius: 10px; }
        .car-specs { display: flex; flex-direction: column; gap: 15px; }
        .spec-item { display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #333; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; }
        .form-control:focus { outline: none; border-color: #d84444; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        /* Voucher Section */
        .voucher-section { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .voucher-section h3 { margin-bottom: 15px; color: #333; }
        .voucher-input-group { display: flex; gap: 10px; margin-bottom: 10px; }
        .voucher-input-group input { flex: 1; }
        .btn-apply-voucher { padding: 12px 24px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .btn-apply-voucher:hover { background: #218838; }
        .btn-apply-voucher:disabled { background: #6c757d; cursor: not-allowed; }
        .voucher-message { margin-top: 10px; padding: 12px; border-radius: 8px; display: none; font-size: 14px; }
        .voucher-message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .voucher-message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Price Summary */
        .price-summary { background: #fff9e6; border: 2px solid #ffeeba; border-radius: 10px; padding: 20px; margin: 20px 0; }
        .price-summary h3 { margin-bottom: 15px; color: #856404; }
        .price-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ffeeba; font-size: 16px; }
        .price-row:last-child { border-bottom: none; }
        .price-row.discount { color: #28a745; font-weight: 600; }
        .price-row.total { font-size: 24px; font-weight: 700; color: #d84444; margin-top: 10px; padding-top: 15px; border-top: 2px solid #856404; }
        
        .deposit-box { background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 15px 20px; margin: 20px 0; font-size: 14px; color: #004085; }
        
        .final-section { margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0; }
        .terms-checkbox { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .terms-checkbox input { width: 20px; height: 20px; cursor: pointer; }
        .terms-link { color: #d84444; text-decoration: none; }
        .terms-link:hover { text-decoration: underline; }
        
        .btn-submit { width: 100%; background: #d84444; color: white; border: none; padding: 18px; border-radius: 10px; font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
        .btn-submit:hover { background: #c13838; transform: translateY(-2px); }
        .btn-submit:disabled { background: #ccc; cursor: not-allowed; transform: none; }
        
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .footer { background: #d84444; color: white; padding: 40px 0; margin-top: 60px; }
        .footer-container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        .footer-top { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 40px; }
        .footer-item { display: flex; gap: 15px; }
        .footer-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .footer-title { font-weight: 700; margin-bottom: 8px; }
        .footer-text { font-size: 14px; line-height: 1.6; }
        .footer-bottom { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.2); }
        .footer-logo { font-size: 32px; font-weight: 700; margin-bottom: 20px; }
        .social-icons { display: flex; gap: 15px; }
        .social-icon { width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; }
        .social-icon:hover { background: rgba(255,255,255,0.2); }
        .footer-section-title { font-weight: 700; margin-bottom: 15px; font-size: 18px; }
        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-link { color: white; text-decoration: none; font-size: 14px; transition: all 0.3s; }
        .footer-link:hover { padding-left: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-container">
            <div class="logo">HASTA</div>
        </div>
    </div>

    <div class="main-container">
        <div class="page-header">
            <a href="{{ route('cars.show', $car->id) }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="page-title">{{ $car->full_name }}</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
            @csrf
            
            <!-- Hidden Fields -->
            <input type="hidden" name="car_id" value="{{ $car->id }}">
            <input type="hidden" id="hidden_duration" name="duration" value="">
            <input type="hidden" id="hidden_base_price" name="base_price" value="">
            <input type="hidden" id="hidden_discount_amount" name="discount_amount" value="0">
            
            <div class="form-layout">
                <!-- Car Info Sidebar -->
                <div class="car-info">
                    <img src="{{ $car->image }}" alt="{{ $car->full_name }}" class="car-image">
                    <div class="car-specs">
                        <div class="spec-item">
                            <i class="fas fa-snowflake" style="color: #00bcd4;"></i>
                            <strong>AC Included</strong>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-users" style="color: #4caf50;"></i>
                            <strong>{{ $car->passengers }} Passengers</strong>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-gas-pump" style="color: #ff9800;"></i>
                            <strong>{{ $car->fuel_type }}</strong>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-dollar-sign" style="color: #d84444;"></i>
                            <strong>RM {{ number_format($car->daily_rate, 2) }}/day</strong>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="booking-form">
                    <div class="form-group">
                        <label class="form-label">Pick-Up Location *</label>
                        <input type="text" name="pickup_location" class="form-control" 
                               placeholder="Where are you picking up the car?" 
                               value="{{ old('pickup_location') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Drop-Off Location *</label>
                        <input type="text" name="dropoff_location" class="form-control" 
                               placeholder="Where will you return the car?" 
                               value="{{ old('dropoff_location') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Destination *</label>
                        <input type="text" name="destination" class="form-control" 
                               placeholder="Where are you traveling to?" 
                               value="{{ old('destination') }}">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pickup Date *</label>
                            <input type="date" name="pickup_date" id="pickup_date" 
                                   class="form-control" value="{{ old('pickup_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pickup Time *</label>
                            <input type="time" name="pickup_time" class="form-control" 
                                   value="{{ old('pickup_time') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Return Date *</label>
                            <input type="date" name="return_date" id="return_date" 
                                   class="form-control" value="{{ old('return_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Return Time *</label>
                            <input type="time" name="return_time" class="form-control" 
                                   value="{{ old('return_time') }}" required>
                        </div>
                    </div>

                    <!-- Voucher Section -->
                    <div class="voucher-section">
                        <h3>🎟️ Have a Voucher Code?</h3>
                        <div class="voucher-input-group">
                            <input type="text" id="voucher_input" name="voucher" 
                                   class="form-control" placeholder="Enter voucher code" 
                                   value="{{ old('voucher') }}">
                            <button type="button" class="btn-apply-voucher" id="apply_voucher_btn">
                                Apply
                            </button>
                        </div>
                        <div class="voucher-message" id="voucher_message"></div>
                    </div>

                    <!-- Price Summary -->
                    <div class="price-summary">
                        <h3>💰 Price Breakdown</h3>
                        <div class="price-row">
                            <span>Duration:</span>
                            <span id="duration_display">-- days</span>
                        </div>
                        <div class="price-row">
                            <span>Base Price ({{ $car->daily_rate }}/day):</span>
                            <span>RM <span id="base_price_display">0.00</span></span>
                        </div>
                        <div class="price-row discount" id="discount_row" style="display: none;">
                            <span>Discount:</span>
                            <span>- RM <span id="discount_display">0.00</span></span>
                        </div>
                        <div class="price-row total">
                            <span>Total:</span>
                            <span>RM <span id="total_price_display">0.00</span></span>
                        </div>
                    </div>

                    <div class="deposit-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> A 10% deposit (RM <span id="deposit_display">0.00</span>) will be calculated and must be paid during pickup.
                    </div>

                    <!-- Terms & Submit -->
                    <div class="final-section">
                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">
                                I accept the <a href="#" class="terms-link">Terms and Conditions</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn-submit" id="submit_btn">
                            <i class="fas fa-check-circle"></i> Confirm Booking
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const dailyRate = {{ $car->daily_rate }};
        let currentDiscount = 0;
        let discountPercentage = 0;
        let voucherApplied = false;

        // Get form elements
        const pickupDateInput = document.getElementById('pickup_date');
        const returnDateInput = document.getElementById('return_date');
        const voucherInput = document.getElementById('voucher_input');
        const applyVoucherBtn = document.getElementById('apply_voucher_btn');
        const voucherMessage = document.getElementById('voucher_message');
        const submitBtn = document.getElementById('submit_btn');
        
        // Display elements
        const durationDisplay = document.getElementById('duration_display');
        const basePriceDisplay = document.getElementById('base_price_display');
        const discountDisplay = document.getElementById('discount_display');
        const totalPriceDisplay = document.getElementById('total_price_display');
        const depositDisplay = document.getElementById('deposit_display');
        const discountRow = document.getElementById('discount_row');
        
        // Hidden fields
        const hiddenDuration = document.getElementById('hidden_duration');
        const hiddenBasePrice = document.getElementById('hidden_base_price');
        const hiddenDiscountAmount = document.getElementById('hidden_discount_amount');

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        pickupDateInput.setAttribute('min', today);
        returnDateInput.setAttribute('min', today);

        // Calculate prices
        function calculatePrices() {
            const pickupDate = pickupDateInput.value;
            const returnDate = returnDateInput.value;

            if (!pickupDate || !returnDate) {
                return;
            }

            const pickup = new Date(pickupDate);
            const returnD = new Date(returnDate);
            const diffTime = returnD - pickup;
            const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (days < 1) {
                durationDisplay.textContent = '-- days';
                basePriceDisplay.textContent = '0.00';
                totalPriceDisplay.textContent = '0.00';
                depositDisplay.textContent = '0.00';
                return;
            }

            const basePrice = days * dailyRate;
            const discountAmount = (basePrice * discountPercentage) / 100;
            const totalPrice = basePrice - discountAmount;
            const depositAmount = totalPrice * 0.1;

            // Update displays
            durationDisplay.textContent = `${days} day${days > 1 ? 's' : ''}`;
            basePriceDisplay.textContent = basePrice.toFixed(2);
            discountDisplay.textContent = discountAmount.toFixed(2);
            totalPriceDisplay.textContent = totalPrice.toFixed(2);
            depositDisplay.textContent = depositAmount.toFixed(2);

            // Update hidden fields
            hiddenDuration.value = days;
            hiddenBasePrice.value = basePrice.toFixed(2);
            hiddenDiscountAmount.value = discountAmount.toFixed(2);

            // Show/hide discount row
            if (discountAmount > 0) {
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        }

        // Apply voucher
        applyVoucherBtn.addEventListener('click', async function() {
            const voucherCode = voucherInput.value.trim();
            const basePrice = parseFloat(hiddenBasePrice.value);

            if (!voucherCode) {
                showVoucherMessage('Please enter a voucher code', 'error');
                return;
            }

            if (!basePrice || basePrice === 0) {
                showVoucherMessage('Please select pickup and return dates first', 'error');
                return;
            }

            // Show loading
            applyVoucherBtn.textContent = 'Checking...';
            applyVoucherBtn.disabled = true;

            try {
                const response = await fetch('{{ route('voucher.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        code: voucherCode,
                        base_price: basePrice
                    })
                });

                const data = await response.json();

                if (response.ok && data.valid) {
                    discountPercentage = data.discount_percentage;
                    voucherApplied = true;
                    calculatePrices();
                    showVoucherMessage(data.message, 'success');
                    applyVoucherBtn.textContent = 'Applied ✓';
                    applyVoucherBtn.style.background = '#28a745';
                    voucherInput.disabled = true;
                } else {
                    showVoucherMessage(data.message || 'Invalid voucher code', 'error');
                    applyVoucherBtn.textContent = 'Apply';
                    applyVoucherBtn.disabled = false;
                    discountPercentage = 0;
                    calculatePrices();
                }
            } catch (error) {
                showVoucherMessage('Error validating voucher. Please try again.', 'error');
                applyVoucherBtn.textContent = 'Apply';
                applyVoucherBtn.disabled = false;
            }
        });

        function showVoucherMessage(message, type) {
            voucherMessage.textContent = message;
            voucherMessage.className = `voucher-message ${type}`;
            voucherMessage.style.display = 'block';
        }

        // Event listeners
        pickupDateInput.addEventListener('change', calculatePrices);
        returnDateInput.addEventListener('change', calculatePrices);

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        });

        // Initial calculation
        calculatePrices();
    </script>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-item">
                    <div class="footer-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div class="footer-title">Address</div>
                        <div class="footer-text">
                            Student Mall UTM<br>
                            Skudai, 81300, Johor Bahru
                        </div>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="footer-title">Email</div>
                        <div class="footer-text">hastatravel@gmail.com</div>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div class="footer-title">Phone</div>
                        <div class="footer-text">011-1090 0700</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    <div class="footer-logo">HASTA</div>
                    <div class="social-icons">
                        <div class="social-icon"><i class="fab fa-facebook-f"></i></div>
                        <div class="social-icon"><i class="fab fa-instagram"></i></div>
                        <div class="social-icon"><i class="fab fa-twitter"></i></div>
                        <div class="social-icon"><i class="fab fa-youtube"></i></div>
                    </div>
                </div>
                <div>
                    <div class="footer-section-title">Useful Links</div>
                    <div class="footer-links">
                        <a href="#" class="footer-link">About us</a>
                        <a href="#" class="footer-link">Contact us</a>
                        <a href="#" class="footer-link">Gallery</a>
                        <a href="#" class="footer-link">Blog</a>
                        <a href="#" class="footer-link">F.A.Q</a>
                    </div>
                </div>
                <div>
                    <div class="footer-section-title">Vehicles</div>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Sedan</a>
                        <a href="#" class="footer-link">Hatchback</a>
                        <a href="#" class="footer-link">MPV</a>
                        <a href="#" class="footer-link">Minivan</a>
                        <a href="#" class="footer-link">SUV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
=======
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; }
        
        /* Header */
        .header { background: #d84444; padding: 15px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header-container { max-width: 1400px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { background: white; color: #d84444; padding: 8px 20px; font-weight: 700; font-size: 1.5rem; border-radius: 4px; letter-spacing: 2px; }
        .nav-icons { display: flex; gap: 8px; align-items: center; }
        .nav-icon { background: rgba(255,255,255,0.15); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.3s; }
        .nav-icon.active { background: rgba(255,255,255,0.3); }
        .nav-icon i { color: white; font-size: 18px; }
        
        /* Main Content */
        .main-container { max-width: 1400px; margin: 20px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); min-height: calc(100vh - 200px); }
        .page-header { display: flex; align-items: center; padding-bottom: 30px; gap: 20px; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .back-btn { width: 40px; height: 40px; background: #333; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s; }
        .back-btn:hover { background: #d84444; transform: translateX(-3px); }
        .back-btn i { color: white; }
        .page-title { font-size: 28px; font-weight: 700; }

        /* Form Layout */
        .form-layout { display: grid; grid-template-columns: 450px 1fr; gap: 60px; }
        .car-info { position: sticky; top: 20px; }
        .car-image { width: 100%; border-radius: 12px; margin-bottom: 25px; object-fit: cover; }
        .car-specs { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .spec-item { display: flex; align-items: center; gap: 10px; font-size: 14px; background: #f9f9f9; padding: 12px; border-radius: 8px; }
        
        /* Form Styling */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #555; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .form-control:focus { outline: none; border-color: #d84444; box-shadow: 0 0 0 3px rgba(216, 68, 68, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .deposit-box { background: #fff4f4; border: 1px dashed #d84444; border-radius: 8px; padding: 15px; margin: 25px 0; color: #d84444; font-weight: 600; }

        /* Final section */
        .final-section { margin-top: 30px; padding-top: 30px; border-top: 2px solid #f5f5f5; }
        .final-price-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .price-label { font-size: 16px; color: #777; }
        .final-price { font-size: 40px; font-weight: 800; color: #d84444; }
        .btn-pay { background: #ff6b3d; color: white; border: none; padding: 18px 60px; border-radius: 10px; font-size: 18px; font-weight: 700; cursor: pointer; transition: 0.3s; width: 100%; }
        .btn-pay:hover:not(:disabled) { background: #e65a2b; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,107,61,0.3); }
        .btn-pay:disabled { background: #ccc; cursor: not-allowed; }

        @media (max-width: 1024px) {
            .form-layout { grid-template-columns: 1fr; }
            .car-info { position: static; }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-container">
            <div class="logo">HASTA</div>
            <div class="nav-icons">
                <div class="nav-icon"><i class="fas fa-home"></i></div>
                <div class="nav-icon"><i class="fas fa-bell"></i></div>
                <div class="nav-icon active"><i class="fas fa-car"></i></div>
                <div class="nav-icon"><i class="fas fa-history"></i></div>
                <div class="nav-icon"><i class="fas fa-cog"></i></div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="page-header">
            <a href="{{ route('cars.show', $car->id) }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="page-title">{{ $car->full_name }}</h1>
        </div>

        <form action="{{ route('bookings.payment-summary') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">
            <input type="hidden" name="total_price" id="hiddenTotalPrice" value="0">
            <input type="hidden" name="duration" id="hiddenDuration" value="0">

            <div class="form-layout">
                <div class="car-info">
                    <img src="{{ asset($car->image) }}" alt="{{ $car->full_name }}" class="car-image">
                    <div class="car-specs">
                        <div class="spec-item"><i class="fas fa-snowflake" style="color: #00bcd4;"></i> <span>A/C</span></div>
                        <div class="spec-item"><i class="fas fa-users" style="color: #4caf50;"></i> <span>{{ $car->passengers }} Seats</span></div>
                        <div class="spec-item"><i class="fas fa-gas-pump" style="color: #ff9800;"></i> <span>{{ $car->fuel_type }}</span></div>
                        <div class="spec-item"><i class="fas fa-cog" style="color: #607d8b;"></i> <span>{{ $car->transmission }}</span></div>
                    </div>
                    
                    <div class="deposit-box">
                        <i class="fas fa-info-circle"></i> Security Deposit: RM 100 (Refundable)
                    </div>
                </div>

                <div class="booking-form">
                    <div class="form-group">
                        <label class="form-label">Pick-Up Location</label>
                        <input type="text" name="pickup_location" class="form-control" placeholder="Enter location" value="{{ old('pickup_location') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Drop-Off Location</label>
                        <input type="text" name="dropoff_location" class="form-control" placeholder="Enter location" value="{{ old('dropoff_location') }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pickup Date</label>
                            <input type="date" name="pickup_date" id="pickup_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pickup Time</label>
                            <input type="time" name="pickup_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Return Date</label>
                            <input type="date" name="return_date" id="return_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Return Time</label>
                            <input type="time" name="return_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Duration (Days)</label>
                            <input type="text" id="duration" class="form-control" placeholder="---" readonly style="background: #eee;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voucher Code</label>
                            <select name="voucher" class="form-control">
                                <option value="">No Voucher</option>
                                @foreach($vouchers as $voucher)
                                    <option value="{{ $voucher->code }}">{{ $voucher->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="final-section">
                        <div class="final-price-wrapper">
                            <span class="price-label">Total Estimated Price:</span>
                            <div class="final-price" id="totalPrice">RM 0</div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" id="terms" required style="width: 18px; height: 18px;">
                                <span style="font-size: 14px;">I accept the <a href="#" style="color: #d84444;">Terms and Conditions</a></span>
                            </label>
                        </div>

                        <button type="submit" class="btn-pay" id="payBtn" disabled>Proceed to Payment</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const dailyRate = {{ $car->daily_rate }};
        const pickupInput = document.getElementById('pickup_date');
        const returnInput = document.getElementById('return_date');
        const durationDisplay = document.getElementById('duration');
        const priceDisplay = document.getElementById('totalPrice');
        const payBtn = document.getElementById('payBtn');
        
        const hiddenTotal = document.getElementById('hiddenTotalPrice');
        const hiddenDur = document.getElementById('hiddenDuration');

        function updateSummary() {
            if (pickupInput.value && returnInput.value) {
                const start = new Date(pickupInput.value);
                const end = new Date(returnInput.value);
                
                // Set min for return date to be at least pickup date
                returnInput.min = pickupInput.value;

                const diffTime = end - start;
                const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (days > 0) {
                    const total = days * dailyRate;
                    durationDisplay.value = days;
                    priceDisplay.textContent = 'RM ' + total.toLocaleString();
                    
                    // Update hidden inputs
                    hiddenTotal.value = total;
                    hiddenDur.value = days;
                    payBtn.disabled = false;
                } else {
                    resetFields();
                }
            } else {
                resetFields();
            }
        }

        function resetFields() {
            durationDisplay.value = '---';
            priceDisplay.textContent = 'RM 0';
            hiddenTotal.value = 0;
            hiddenDur.value = 0;
            payBtn.disabled = true;
        }

        pickupInput.addEventListener('change', updateSummary);
        returnInput.addEventListener('change', updateSummary);
    </script>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-item">
                    <div class="footer-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="footer-title">Call Us</div>
                        <div class="footer-text">+60 12-345 6789</div>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="footer-title">Email Us</div>
                        <div class="footer-text">support@hasta.com</div>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="footer-title">Visit Us</div>
                        <div class="footer-text">Kuala Lumpur, Malaysia</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    <div class="footer-logo">HASTA</div>
                    <div class="social-icons">
                        <div class="social-icon"><i class="fab fa-facebook-f"></i></div>
                        <div class="social-icon"><i class="fab fa-instagram"></i></div>
                        <div class="social-icon"><i class="fab fa-twitter"></i></div>
                    </div>
                </div>
                
                <div class="footer-links">
                    <div class="footer-section-title">Quick Links</div>
                    <a href="#" class="footer-link">How it Works</a>
                    <a href="#" class="footer-link">Our Fleet</a>
                    <a href="#" class="footer-link">Insurance Policy</a>
                </div>

                <div class="footer-links">
                    <div class="footer-section-title">Support</div>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms & Conditions</a>
                    <a href="#" class="footer-link">Contact Support</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Footer Styles */
        .footer {
            background: #d84444;
            color: white;
            padding: 60px 0 30px;
            margin-top: 60px;
        }
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .footer-top {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .footer-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .footer-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .footer-title { font-weight: 700; font-size: 16px; margin-bottom: 4px; }
        .footer-text { font-size: 14px; color: rgba(255,255,255,0.8); }

        .footer-bottom {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 60px;
        }
        .footer-logo {
            display: inline-block;
            background: white;
            color: #d84444;
            padding: 8px 25px;
            font-weight: 800;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .social-icons { display: flex; gap: 10px; }
        .social-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            cursor: pointer;
        }
        .social-icon:hover { background: white; color: #d84444; }

        .footer-section-title { font-weight: 700; margin-bottom: 20px; }
        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }
        .footer-link:hover { color: white; transform: translateX(5px); }

        @media (max-width: 768px) {
            .footer-top, .footer-bottom { grid-template-columns: 1fr; gap: 30px; }
        }
    </style>
>>>>>>> Stashed changes
=======
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; }
        
        /* Header */
        .header { background: #d84444; padding: 15px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header-container { max-width: 1400px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { background: white; color: #d84444; padding: 8px 20px; font-weight: 700; font-size: 1.5rem; border-radius: 4px; letter-spacing: 2px; }
        .nav-icons { display: flex; gap: 8px; align-items: center; }
        .nav-icon { background: rgba(255,255,255,0.15); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.3s; }
        .nav-icon.active { background: rgba(255,255,255,0.3); }
        .nav-icon i { color: white; font-size: 18px; }
        
        /* Main Content */
        .main-container { max-width: 1400px; margin: 20px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); min-height: calc(100vh - 200px); }
        .page-header { display: flex; align-items: center; padding-bottom: 30px; gap: 20px; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .back-btn { width: 40px; height: 40px; background: #333; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s; }
        .back-btn:hover { background: #d84444; transform: translateX(-3px); }
        .back-btn i { color: white; }
        .page-title { font-size: 28px; font-weight: 700; }

        /* Form Layout */
        .form-layout { display: grid; grid-template-columns: 450px 1fr; gap: 60px; }
        .car-info { position: sticky; top: 20px; }
        .car-image { width: 100%; border-radius: 12px; margin-bottom: 25px; object-fit: cover; }
        .car-specs { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .spec-item { display: flex; align-items: center; gap: 10px; font-size: 14px; background: #f9f9f9; padding: 12px; border-radius: 8px; }
        
        /* Form Styling */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #555; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .form-control:focus { outline: none; border-color: #d84444; box-shadow: 0 0 0 3px rgba(216, 68, 68, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .deposit-box { background: #fff4f4; border: 1px dashed #d84444; border-radius: 8px; padding: 15px; margin: 25px 0; color: #d84444; font-weight: 600; }

        /* Final section */
        .final-section { margin-top: 30px; padding-top: 30px; border-top: 2px solid #f5f5f5; }
        .final-price-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .price-label { font-size: 16px; color: #777; }
        .final-price { font-size: 40px; font-weight: 800; color: #d84444; }
        .btn-pay { background: #ff6b3d; color: white; border: none; padding: 18px 60px; border-radius: 10px; font-size: 18px; font-weight: 700; cursor: pointer; transition: 0.3s; width: 100%; }
        .btn-pay:hover:not(:disabled) { background: #e65a2b; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,107,61,0.3); }
        .btn-pay:disabled { background: #ccc; cursor: not-allowed; }

        @media (max-width: 1024px) {
            .form-layout { grid-template-columns: 1fr; }
            .car-info { position: static; }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-container">
            <div class="logo">HASTA</div>
            <div class="nav-icons">
                <div class="nav-icon"><i class="fas fa-home"></i></div>
                <div class="nav-icon"><i class="fas fa-bell"></i></div>
                <div class="nav-icon active"><i class="fas fa-car"></i></div>
                <div class="nav-icon"><i class="fas fa-history"></i></div>
                <div class="nav-icon"><i class="fas fa-cog"></i></div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="page-header">
            <a href="{{ route('cars.show', $car->id) }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="page-title">{{ $car->full_name }}</h1>
        </div>

        <form action="{{ route('bookings.payment-summary') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">
            <input type="hidden" name="total_price" id="hiddenTotalPrice" value="0">
            <input type="hidden" name="duration" id="hiddenDuration" value="0">

            <div class="form-layout">
                <div class="car-info">
                    <img src="{{ asset($car->image) }}" alt="{{ $car->full_name }}" class="car-image">
                    <div class="car-specs">
                        <div class="spec-item"><i class="fas fa-snowflake" style="color: #00bcd4;"></i> <span>A/C</span></div>
                        <div class="spec-item"><i class="fas fa-users" style="color: #4caf50;"></i> <span>{{ $car->passengers }} Seats</span></div>
                        <div class="spec-item"><i class="fas fa-gas-pump" style="color: #ff9800;"></i> <span>{{ $car->fuel_type }}</span></div>
                        <div class="spec-item"><i class="fas fa-cog" style="color: #607d8b;"></i> <span>{{ $car->transmission }}</span></div>
                    </div>
                    
                    <div class="deposit-box">
                        <i class="fas fa-info-circle"></i> Security Deposit: RM 100 (Refundable)
                    </div>
                </div>

                <div class="booking-form">
                    <div class="form-group">
                        <label class="form-label">Pick-Up Location</label>
                        <input type="text" name="pickup_location" class="form-control" placeholder="Enter location" value="{{ old('pickup_location') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Drop-Off Location</label>
                        <input type="text" name="dropoff_location" class="form-control" placeholder="Enter location" value="{{ old('dropoff_location') }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pickup Date</label>
                            <input type="date" name="pickup_date" id="pickup_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pickup Time</label>
                            <input type="time" name="pickup_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Return Date</label>
                            <input type="date" name="return_date" id="return_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Return Time</label>
                            <input type="time" name="return_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Duration (Days)</label>
                            <input type="text" id="duration" class="form-control" placeholder="---" readonly style="background: #eee;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voucher Code</label>
                            <select name="voucher" class="form-control">
                                <option value="">No Voucher</option>
                                @foreach($vouchers as $voucher)
                                    <option value="{{ $voucher->code }}">{{ $voucher->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="final-section">
                        <div class="final-price-wrapper">
                            <span class="price-label">Total Estimated Price:</span>
                            <div class="final-price" id="totalPrice">RM 0</div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" id="terms" required style="width: 18px; height: 18px;">
                                <span style="font-size: 14px;">I accept the <a href="#" style="color: #d84444;">Terms and Conditions</a></span>
                            </label>
                        </div>

                        <button type="submit" class="btn-pay" id="payBtn" disabled>Proceed to Payment</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const dailyRate = {{ $car->daily_rate }};
        const pickupInput = document.getElementById('pickup_date');
        const returnInput = document.getElementById('return_date');
        const durationDisplay = document.getElementById('duration');
        const priceDisplay = document.getElementById('totalPrice');
        const payBtn = document.getElementById('payBtn');
        
        const hiddenTotal = document.getElementById('hiddenTotalPrice');
        const hiddenDur = document.getElementById('hiddenDuration');

        function updateSummary() {
            if (pickupInput.value && returnInput.value) {
                const start = new Date(pickupInput.value);
                const end = new Date(returnInput.value);
                
                // Set min for return date to be at least pickup date
                returnInput.min = pickupInput.value;

                const diffTime = end - start;
                const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (days > 0) {
                    const total = days * dailyRate;
                    durationDisplay.value = days;
                    priceDisplay.textContent = 'RM ' + total.toLocaleString();
                    
                    // Update hidden inputs
                    hiddenTotal.value = total;
                    hiddenDur.value = days;
                    payBtn.disabled = false;
                } else {
                    resetFields();
                }
            } else {
                resetFields();
            }
        }

        function resetFields() {
            durationDisplay.value = '---';
            priceDisplay.textContent = 'RM 0';
            hiddenTotal.value = 0;
            hiddenDur.value = 0;
            payBtn.disabled = true;
        }

        pickupInput.addEventListener('change', updateSummary);
        returnInput.addEventListener('change', updateSummary);
    </script>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-item">
                    <div class="footer-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="footer-title">Call Us</div>
                        <div class="footer-text">+60 12-345 6789</div>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="footer-title">Email Us</div>
                        <div class="footer-text">support@hasta.com</div>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="footer-title">Visit Us</div>
                        <div class="footer-text">Kuala Lumpur, Malaysia</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    <div class="footer-logo">HASTA</div>
                    <div class="social-icons">
                        <div class="social-icon"><i class="fab fa-facebook-f"></i></div>
                        <div class="social-icon"><i class="fab fa-instagram"></i></div>
                        <div class="social-icon"><i class="fab fa-twitter"></i></div>
                    </div>
                </div>
                
                <div class="footer-links">
                    <div class="footer-section-title">Quick Links</div>
                    <a href="#" class="footer-link">How it Works</a>
                    <a href="#" class="footer-link">Our Fleet</a>
                    <a href="#" class="footer-link">Insurance Policy</a>
                </div>

                <div class="footer-links">
                    <div class="footer-section-title">Support</div>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms & Conditions</a>
                    <a href="#" class="footer-link">Contact Support</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Footer Styles */
        .footer {
            background: #d84444;
            color: white;
            padding: 60px 0 30px;
            margin-top: 60px;
        }
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .footer-top {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .footer-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .footer-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .footer-title { font-weight: 700; font-size: 16px; margin-bottom: 4px; }
        .footer-text { font-size: 14px; color: rgba(255,255,255,0.8); }

        .footer-bottom {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 60px;
        }
        .footer-logo {
            display: inline-block;
            background: white;
            color: #d84444;
            padding: 8px 25px;
            font-weight: 800;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .social-icons { display: flex; gap: 10px; }
        .social-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            cursor: pointer;
        }
        .social-icon:hover { background: white; color: #d84444; }

        .footer-section-title { font-weight: 700; margin-bottom: 20px; }
        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }
        .footer-link:hover { color: white; transform: translateX(5px); }

        @media (max-width: 768px) {
            .footer-top, .footer-bottom { grid-template-columns: 1fr; gap: 30px; }
        }
    </style>
>>>>>>> Stashed changes
</body>
</html>