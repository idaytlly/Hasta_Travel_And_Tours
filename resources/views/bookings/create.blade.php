<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $car->full_name }} - HASTA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        /* Header */
        .header {
            background: #d84444;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            background: white;
            color: #d84444;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 1.5rem;
            border-radius: 4px;
            letter-spacing: 2px;
        }
        .nav-icons {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .nav-icon {
            background: rgba(255,255,255,0.15);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .nav-icon.active {
            background: rgba(255,255,255,0.3);
        }
        .nav-icon i {
            color: white;
            font-size: 20px;
        }
        .nav-icon-label {
            position: absolute;
            bottom: -20px;
            font-size: 10px;
            color: white;
            white-space: nowrap;
        }
        .btn-login {
            background: #c73030;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 10px;
        }
        
        /* Main Content */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            background: white;
            min-height: calc(100vh - 180px);
        }
        
        /* Back Button & Title */
        .page-header {
            display: flex;
            align-items: center;
            padding: 30px 0 20px;
            gap: 15px;
        }
        .back-btn {
            width: 50px;
            height: 50px;
            background: black;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .back-btn:hover {
            transform: scale(1.1);
        }
        .back-btn i {
            color: white;
            font-size: 20px;
        }
        .page-title {
            font-size: 32px;
            font-weight: 700;
        }
        
        /* Form Layout */
        .form-layout {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 40px;
            padding: 20px 0;
        }
        
        /* Left: Car Info */
        .car-info {
            position: sticky;
            top: 20px;
            align-self: start;
        }
        .car-image {
            width: 100%;
            height: auto;
            margin-bottom: 30px;
        }
        .car-specs {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .spec-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }
        .spec-item i {
            font-size: 24px;
            width: 30px;
        }
        
        /* Right: Form */
        .booking-form {
            padding-right: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #d84444;
            box-shadow: 0 0 0 3px rgba(216, 68, 68, 0.1);
        }
        .form-control::placeholder {
            color: #999;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        textarea.form-control {
            resize: none;
            height: 100px;
        }
        .form-note {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        /* Deposit Box */
        .deposit-box {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 20px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        /* Final Calculations */
        .final-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        .final-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
        }
        .final-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .final-price {
            font-size: 48px;
            font-weight: 700;
            color: #d84444;
        }
        .btn-pay {
            background: #ff6b3d;
            color: white;
            border: none;
            padding: 18px 80px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-pay:hover {
            background: #ff5722;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,61,0.3);
        }
        .terms-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        .terms-checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .terms-link {
            color: #007bff;
            text-decoration: none;
        }
        
        /* Footer */
        .footer {
            background: #d84444;
            color: white;
            padding: 40px 0 20px;
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
        }
        .footer-item {
            display: flex;
            align-items: start;
            gap: 15px;
        }
        .footer-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .footer-icon i {
            font-size: 18px;
        }
        .footer-title {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .footer-text {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255,255,255,0.9);
        }
        .footer-bottom {
            display: grid;
            grid-template-columns: auto 1fr 1fr;
            gap: 60px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .footer-logo {
            background: white;
            color: #d84444;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 1.3rem;
            border-radius: 4px;
            letter-spacing: 2px;
            align-self: start;
        }
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        .social-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .social-icon:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-3px);
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .footer-section-title {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .footer-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .footer-link:hover {
            color: white;
            padding-left: 5px;
        }
        
        @media (max-width: 1024px) {
            .form-layout {
                grid-template-columns: 1fr;
            }
            .car-info {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-container">
            <div class="logo">HASTA</div>
            <div class="nav-icons">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="nav-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="nav-icon">
                    <i class="fas fa-th"></i>
                </div>
                <div class="nav-icon active">
                    <i class="fas fa-car"></i>
                </div>
                <div class="nav-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="nav-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <button class="btn-login">Login</button>
                <img src="https://ui-avatars.com/api/?name=User&background=d84444&color=fff" 
                     style="width: 50px; height: 50px; border-radius: 50%; margin-left: 10px;" alt="Profile">
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <a href="{{ route('cars.show', $car->id) }}" class="back-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="page-title">{{ $car->full_name }}</h1>
        </div>

        <!-- Form Layout -->
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">

            <div class="form-layout">
                <!-- Left: Car Info -->
                <div class="car-info">
                    <img src="{{ $car->image }}" alt="{{ $car->full_name }}" class="car-image">
                    
                    <div class="car-specs">
                        <div class="spec-item">
                            <i class="fas fa-snowflake" style="color: #00bcd4;"></i>
                            <span><strong>Air Conditioner</strong></span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-users" style="color: #4caf50;"></i>
                            <span><strong>{{ $car->passengers }} Passengers</strong></span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-gas-pump" style="color: #ff9800;"></i>
                            <span><strong>{{ $car->fuel_type }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Right: Booking Form -->
                <div class="booking-form">
                    <!-- Pick-Up Location -->
                    <div class="form-group">
                        <label class="form-label">Pick-Up Location</label>
                        <input type="text" name="pickup_location" class="form-control" 
                               placeholder="Pick-Up Location" value="{{ old('pickup_location') }}" required>
                        @error('pickup_location')
                            <small style="color: #f44336;">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Drop-Off Location -->
                    <div class="form-group">
                        <label class="form-label">Drop-Off Location</label>
                        <input type="text" name="dropoff_location" class="form-control" 
                               placeholder="Drop-Off Location" value="{{ old('dropoff_location') }}">
                    </div>

                    <!-- Destination -->
                    <div class="form-group">
                        <label class="form-label">Destination</label>
                        <input type="text" name="destination" class="form-control" 
                               placeholder="Destination" value="{{ old('destination') }}">
                    </div>

                    <!-- Pickup Date & Time -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pickup Date</label>
                            <input type="text" name="pickup_date" class="form-control" 
                                   placeholder="DD/MM/YY" value="{{ old('pickup_date') }}" 
                                   onfocus="(this.type='date')" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pickup Time</label>
                            <input type="text" name="pickup_time" class="form-control" 
                                   placeholder="---" value="{{ old('pickup_time') }}"
                                   onfocus="(this.type='time')">
                        </div>
                    </div>

                    <!-- Return Date & Time -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Return Date</label>
                            <input type="text" name="return_date" class="form-control" 
                                   placeholder="DD/MM/YY" value="{{ old('return_date') }}"
                                   onfocus="(this.type='date')" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Return Time</label>
                            <input type="text" name="return_time" class="form-control" 
                                   placeholder="---" value="{{ old('return_time') }}"
                                   onfocus="(this.type='time')">
                        </div>
                    </div>

                    <!-- Duration & Voucher -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Duration (Days)</label>
                            <input type="text" id="duration" class="form-control" 
                                   placeholder="---" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voucher</label>
                            <select name="voucher" class="form-control">
                                <option value="">---</option>
                                @foreach($vouchers as $voucher)
                                    <option value="{{ $voucher->code }}">{{ $voucher->code }} - {{ $voucher->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" 
                                  placeholder="for additional request">{{ old('remarks') }}</textarea>
                        <div class="form-note">100 characters remaining</div>
                    </div>

                    <!-- Deposit -->
                    <div class="deposit-box">
                        <strong>Deposit (RM): 100</strong>
                    </div>

                    <!-- Final Calculations -->
                    <div class="final-section">
                        <h2 class="final-title">Final Calculations</h2>
                        <div class="final-content">
                            <div class="final-price" id="totalPrice">RM340</div>
                            <button type="submit" class="btn-pay">Pay Now</button>
                        </div>
                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">
                                I've read and accept the <a href="#" class="terms-link">Terms and Conditions</a>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-container">
            <!-- Top Section -->
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

            <!-- Bottom Section -->
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

    <!-- Auto Calculate Duration & Price -->
    <script>
        const dailyRate = {{ $car->daily_rate }};
        const pickupDateInput = document.querySelector('[name="pickup_date"]');
        const returnDateInput = document.querySelector('[name="return_date"]');
        const durationField = document.getElementById('duration');
        const totalPriceField = document.getElementById('totalPrice');

        function calculateDuration() {
            if (pickupDateInput.value && returnDateInput.value) {
                const pickup = new Date(pickupDateInput.value);
                const returnD = new Date(returnDateInput.value);
                const days = Math.ceil((returnD - pickup) / (1000 * 60 * 60 * 24));
                
                if (days > 0) {
                    durationField.value = days;
                    const total = days * dailyRate;
                    totalPriceField.textContent = 'RM' + total;
                } else {
                    durationField.value = '---';
                    totalPriceField.textContent = 'RM0';
                }
            }
        }

        pickupDateInput.addEventListener('change', calculateDuration);
        returnDateInput.addEventListener('change', calculateDuration);
    </script>
</body>
</html>