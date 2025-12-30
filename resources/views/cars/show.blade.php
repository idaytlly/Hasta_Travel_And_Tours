
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $car->full_name }} - HASTA</title>
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
        }
        .nav-icon.active {
            background: rgba(255,255,255,0.3);
        }
        .nav-icon i {
            color: white;
            font-size: 20px;
        }
        .btn-login {
            background: #ff7c3e;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 10px;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Car Header Card */
        .car-header-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .car-title-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .back-icon {
            width: 50px;
            height: 50px;
            background: black;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .back-icon i {
            color: white;
            font-size: 20px;
        }
        .car-title {
            font-size: 32px;
            font-weight: 700;
        }
        .car-price {
            font-size: 32px;
            color: #d84444;
            font-weight: 700;
            margin-bottom: 30px;
        }
        .price-unit {
            font-size: 16px;
            color: #888;
            font-weight: 400;
        }
        .car-image-section {
            position: relative;
            text-align: center;
        }
        .car-main-image {
            width: 100%;
            max-width: 700px;
            height: auto;
            margin: 0 auto;
        }
        .car-thumbnails {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            align-items: center;
        }
        .nav-arrow {
            width: 45px;
            height: 45px;
            border: 2px solid black;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: white;
            transition: all 0.3s;
        }
        .nav-arrow:hover {
            background: black;
        }
        .nav-arrow:hover i {
            color: white;
        }
        .nav-arrow i {
            color: black;
        }
        .thumbnail {
            width: 120px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        .thumbnail:hover {
            border-color: #d84444;
        }
        
        /* Specifications Section */
        .specs-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
        }
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .spec-box {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
        }
        .spec-icon {
            font-size: 32px;
            margin-bottom: 15px;
        }
        .spec-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .spec-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        
        /* Equipment Section */
        .equipment-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .equipment-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }
        .check-icon {
            width: 24px;
            height: 24px;
            background: #d84444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .check-icon i {
            color: white;
            font-size: 12px;
        }
        
        /* Book Now Button */
        .btn-book {
            background: #ff6b3d;
            color: white;
            border: none;
            padding: 15px 60px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            float: right;
            transition: all 0.3s;
        }
        .btn-book:hover {
            background: #ff5722;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,61,0.3);
        }
        
        /* Other Cars Section */
        .other-cars {
            margin-top: 60px;
        }
        .other-cars-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .other-cars-title {
            font-size: 32px;
            font-weight: 700;
        }
        .view-all {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        .car-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .car-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .car-card-body {
            padding: 20px;
        }
        .car-card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .car-card-type {
            color: #888;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .car-card-price {
            color: #d84444;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .car-card-specs {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #666;
        }
        .car-card-spec {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn-view-details {
            width: 100%;
            background: #ff6b3d;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-view-details:hover {
            background: #ff5722;
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
        }
        .footer-section-title {
            font-weight: 700;
            margin-bottom: 15px;
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .footer-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 14px;
        }
        
        @media (max-width: 1024px) {
            .specs-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .cars-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .specs-grid {
                grid-template-columns: 1fr;
            }
            .cars-grid {
                grid-template-columns: 1fr;
            }
            .equipment-grid {
                grid-template-columns: 1fr;
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
                </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Car Header Card -->
        <div class="car-header-card">
            <div class="car-title-section">
                <a href="{{ route('cars.index') }}" class="back-icon">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <h1 class="car-title">{{ $car->full_name }}</h1>
            </div>
            
            <div class="car-price">
                RM{{ number_format($car->daily_rate, 0) }}
                <span class="price-unit">/ day</span>
            </div>

            <div class="car-image-section">
                <img src="{{ $car->image }}" alt="{{ $car->full_name }}" class="car-main-image">
                
                <div class="car-thumbnails">
                    <div class="nav-arrow">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <img src="{{ $car->image }}" alt="View 1" class="thumbnail">
                    <img src="{{ $car->image }}" alt="View 2" class="thumbnail">
                    <div class="nav-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Specifications Card -->
        <div class="specs-card">
            <h2 class="section-title">Specifications</h2>
            
            <div class="specs-grid">
                <div class="spec-box">
                    <div class="spec-icon">🔧</div>
                    <div class="spec-label">Gear Box</div>
                    <div class="spec-value">{{ $car->transmission }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">⛽</div>
                    <div class="spec-label">Fuel</div>
                    <div class="spec-value">{{ $car->fuel_type }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">🚪</div>
                    <div class="spec-label">Doors</div>
                    <div class="spec-value">4</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">❄️</div>
                    <div class="spec-label">Air Conditioner</div>
                    <div class="spec-value">{{ $car->air_conditioner ? 'Yes' : 'No' }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">👥</div>
                    <div class="spec-label">Seats</div>
                    <div class="spec-value">{{ $car->passengers }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">📏</div>
                    <div class="spec-label">Distance</div>
                    <div class="spec-value">500</div>
                </div>
            </div>

            <!-- Car Equipment -->
            <h3 class="equipment-title">Car Equipment</h3>
            <div class="equipment-grid">
                <div class="equipment-item">
                    <div class="check-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>ABS</span>
                </div>
                <div class="equipment-item">
                    <div class="check-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>ABS</span>
                </div>
                <div class="equipment-item">
                    <div class="check-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Air Bags</span>
                </div>
                <div class="equipment-item">
                    <div class="check-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Air Bags</span>
                </div>
                <div class="equipment-item">
                    <div class="check-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Cruise Control</span>
                </div>
                <div class="equipment-item">
                    <div class="check-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Air Conditioner</span>
                </div>
            </div>

            <a href="{{ route('bookings.create', $car->id) }}" class="btn-book">
                Book Now
            </a>
            <div style="clear: both;"></div>
        </div>

        <!-- Other Cars Section -->
        <div class="other-cars">
            <div class="other-cars-header">
                <h2 class="other-cars-title">Other cars</h2>
                <a href="{{ route('cars.index') }}" class="view-all">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="cars-grid">
                @foreach($otherCars as $otherCar)
                <div class="car-card">
                    <img src="{{ $otherCar->image }}" alt="{{ $otherCar->full_name }}" class="car-card-image">
                    <div class="car-card-body">
                        <div class="car-card-title">{{ $otherCar->full_name }}</div>
                        <div class="car-card-type">{{ $otherCar->transmission }}</div>
                        <div class="car-card-price">
                            RM{{ number_format($otherCar->daily_rate, 0) }}
                            <span style="font-size: 14px; color: #888;">per day</span>
                        </div>
                        <div class="car-card-specs">
                            <div class="car-card-spec">
                                <i class="fas fa-cog"></i> Automat
                            </div>
                            <div class="car-card-spec">
                                <i class="fas fa-gas-pump"></i> RON 95
                            </div>
                            <div class="car-card-spec">
                                <i class="fas fa-snowflake"></i> Air Conditioner
                            </div>
                        </div>
                        <a href="{{ route('cars.show', $otherCar->id) }}" class="btn-view-details">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

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
</body>
</html>