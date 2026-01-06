<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - Car Details</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary: #e53935;
            --primary-dark: #c62828;
            --primary-light: #ffebee;
            --dark: #1a1a2e;
            --text-dark: #333;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --white: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
            padding-top: 100px;
        }

        /* NAVBAR */
        .navbar-hasta {
            background: var(--white);
            min-height: 70px;
            max-height: 80px;
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .logo-image {
            height: 120px;
            width: 120px;
            max-width: 150px;
            object-fit: contain;
        }

        .nav-link-hasta {
            color: var(--text-dark) !important;
            font-weight: 500;
            padding: 10px 20px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin: 0 5px;
            position: relative;
            overflow: hidden;
        }

        .nav-link-hasta::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: var(--primary);
            border-radius: 25px;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: -1;
        }

        .nav-link-hasta:hover::after {
            opacity: 0.1;
        }

        .nav-link-hasta:hover {
            color: var(--primary) !important;
        }

        .nav-link-hasta.active {
            color: var(--white) !important;
        }

        .nav-link-hasta.active::after {
            opacity: 1;
        }

       
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

                /* FOOTER */
        .footer-hasta {
            background: var(--dark);
            color: var(--white);
            padding: 80px 0 30px;
            margin-top: 80px;
        }

        .footer-brand {
            font-size: 2rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 20px;
        }

        .footer-text {
            color: rgba(255,255,255,0.7);
            margin-bottom: 25px;
            max-width: 300px;
        }

        .footer-contact {
            margin-bottom: 25px;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            color: rgba(255,255,255,0.8);
        }

        .footer-contact-item i {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-links h5 {
            color: var(--white);
            font-weight: 600;
            margin-bottom: 25px;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links ul li {
            margin-bottom: 12px;
        }

        .footer-links ul li a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links ul li a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 50px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
        }

        @media (max-width: 768px) {
            .car-title {
                font-size: 1.5rem;
            }
            
            .car-price {
                font-size: 2rem;
            }
            
            .specs-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-hasta">
    <div class="container">
        <a class="logo-text" href="{{ route('home') }}">
            <img src="{{ asset('images/hasta logo.png') }}" alt="HASTA Logo" class="logo-image">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta active" href="{{ route('cars.index') }}">Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('aboutus') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('contactus') }}">Contact</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('profile.show') }}">Profile</a>
                </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-danger" style="padding: 10px 25px; border-radius: 30px;">Login</a>
                <a href="{{ route('register') }}" class="btn" style="background: #e53935; color: white; padding: 10px 25px; border-radius: 30px;">Register</a>
            @else
                <span class="me-2">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            @endguest
            </div>
        </div>
    </div>
</nav>

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

    <!-- FOOTER -->
<footer id="footer-hasta" class="footer-hasta">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="footer-brand">HASTA</div>
                <p class="footer-text">
                    Your trusted partner for car rental services in Malaysia. Quality vehicles, affordable prices.
                </p>
                
                <div class="footer-contact">
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Student Mall UTM, Skudai, 81300, Johor Bahru</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>hastatraveltours@gmail.com</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <span>011-1090 0700</span>
                    </div>
                </div>

                <div class="social-links">
                    <a href="http://wasap.my/601110900700/nakkeretasewa"><i class="fab fa-whatsapp"></i></a>
                    <a href="http://t.me/infoHastaCarRentalUTM"><i class="fab fa-telegram"></i></a>
                    <a href="http://youtube.com/watch?v=41Vedbjxn_s"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.instagram.com/hastatraveltours?igsh=MXR0ZjYyM3c3Znpsdg=="><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2 offset-lg-1 footer-links">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('cars.index') }}">Vehicles</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="{{ route('contactus') }}">Contact</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 footer-links">
                <h5>Vehicles</h5>
                <ul>
                    <li><a href="#">Sedan</a></li>
                    <li><a href="#">Hatchback</a></li>
                    <li><a href="#">MPV</a></li>
                    <li><a href="#">SUV</a></li>
                    <li><a href="#">Luxury</a></li>
                </ul>
            </div>

            <div class="col-lg-3 footer-links">
                <h5>Newsletter</h5>
                <p class="text-white-50 mb-3">Subscribe for updates and special offers</p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Your email" style="border-radius: 10px;">
                    <button type="submit" class="btn" style="background: var(--primary); color: white; border-radius: 10px;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-hasta');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.car-card, .feature-box, .testimonial-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    
</script>

</body>
</html>