@extends('layouts.app')

@section('title', 'Create Booking')

@section('noFooter', true)

@section('content')

@include('components.booking-timeline', ['currentStep' => 1])


<style>
    body { padding-top: 70px; }
    .booking-container { 
        max-width: 1000px; 
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
        box-shadow: 0 6px 14px rgba(217,48,37,0.35);
    }
    
    .alert-warning-profile {
        margin-bottom: 20px;
        padding: 20px;
        background: #fef3c7;
        border: 2px solid #f59e0b;
        border-radius: 8px;
        display: flex;
        align-items: start;
        gap: 12px;
    }
    
    .alert-warning-profile .icon {
        font-size: 24px;
        line-height: 1;
    }
    
    .alert-warning-profile h4 {
        margin: 0 0 8px 0;
        color: #92400e;
        font-weight: 700;
        font-size: 18px;
    }
    
    .alert-warning-profile p {
        margin: 0 0 12px 0;
        color: #92400e;
        font-size: 14px;
    }
    
    .alert-warning-profile .btn-complete-profile {
        display: inline-block;
        background: #d97706;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .alert-warning-profile .btn-complete-profile:hover {
        background: #b45309;
        transform: translateY(-1px);
    }
    
    .booking-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    
    .section-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #111;
    }
    
    .car-info-box {
        background: #f7f7f7;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 20px;
        align-items: center;
    }
    
    .car-info-box img {
        width: 180px;
        height: auto;
        border-radius: 8px;
    }
    
    .car-details h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .car-meta {
        color: #666;
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .car-price {
        color: #d93025;
        font-size: 20px;
        font-weight: 800;
        margin-top: 8px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        background: #f7f7f7;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #d93025;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(217,48,37,0.1);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    
    .nested-field {
        margin-left: 16px;
        padding-left: 16px;
        border-left: 4px solid #4299e1;
        background: #eff6ff;
        padding: 16px;
        border-radius: 8px;
        margin-top: 12px;
    }
    
    .delivery-notice {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .delivery-notice p {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
    }
    
    .voucher-section {
        border-top: 2px solid #e0e0e0;
        padding-top: 20px;
        margin-top: 20px;
    }
    
    .voucher-row {
        display: flex;
        gap: 12px;
    }
    
    .voucher-row .form-control {
        flex: 1;
    }
    
    .btn-apply {
        background: #4b5563;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-apply:hover {
        background: #374151;
    }
    
    .voucher-success {
        color: #059669;
        font-size: 14px;
        margin-top: 8px;
    }
    
    .price-summary {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .price-summary h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 16px;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        color: #4b5563;
    }
    
    .price-row.total {
        border-top: 2px solid #93c5fd;
        padding-top: 12px;
        margin-top: 12px;
        font-size: 18px;
        font-weight: 700;
        color: #111;
    }
    
    .price-row.total .amount {
        color: #d93025;
    }
    
    .price-row.discount {
        color: #059669;
    }
    
    .agreement-section {
        border-top: 2px solid #d1d5db;
        padding-top: 20px;
        margin-top: 24px;
    }
    
    .terms-box {
        background: #f7f7f7;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 16px;
        max-height: 250px;
        overflow-y: auto;
    }
    
    .terms-box h4 {
        font-weight: 600;
        margin-bottom: 12px;
    }
    
    .terms-box p {
        font-size: 14px;
        color: #4b5563;
        margin-bottom: 8px;
    }
    
    .checkbox-label {
        display: flex;
        align-items: start;
        cursor: pointer;
        margin-bottom: 16px;
    }
    
    .checkbox-label input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        margin-top: 2px;
        cursor: pointer;
    }
    
    .signature-box {
        margin-top: 16px;
    }
    
    .signature-canvas {
        border: 2px solid #d1d5db;
        border-radius: 8px;
        cursor: crosshair;
        background: #fff;
        width: 100%;
        height: 150px;
    }
    
    .signature-actions {
        margin-top: 8px;
        display: flex;
        gap: 12px;
    }
    
    .btn-clear {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }
    
    .btn-clear:hover {
        background: #dc2626;
    }
    
    .btn-submit {
        width: 100%;
        background: #d93025;
        color: #fff;
        border: none;
        padding: 16px;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 24px;
        transition: all 0.2s ease;
    }
    
    .btn-submit:hover {
        background: #b71c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(217,48,37,0.3);
    }
    
    .btn-submit:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }
    
    * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .agreement-section {
            margin-bottom: 30px;
        }
        
        h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .terms-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .terms-box h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }
        
        .terms-box p {
            margin-bottom: 12px;
            font-size: 14px;
            color: #374151;
            line-height: 1.5;
            text-align: justify; 
        }
        
        .terms-box strong {
            color: #111827;
            font-weight: 600;
        }
        
        .header-text {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .terms-box p.header-text {
            text-align: center !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 13px;
        }
        
        table.narrow {
            width: 60%;
        }
        
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background: #f3f4f6;
            font-weight: 600;
        }
        
        .checkbox-label {
            display: flex;
            align-items: start;
            gap: 10px;
            margin-bottom: 24px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .checkbox-label input[type="checkbox"] {
            margin-top: 3px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .checkbox-label span {
            flex: 1;
            color: #374151;
        }
        
        .signature-box {
            margin-top: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
        }
        
        .form-label::after {
            content: " *";
            color: #ef4444;
        }
        
        .signature-box > p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        
        .signature-canvas {
            width: 100%;
            height: 200px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            cursor: crosshair;
            background: #ffffff;
            touch-action: none;
        }
        
        .signature-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
        }
        
        .btn-clear {
            padding: 8px 16px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-clear:hover {
            background: #e5e7eb;
        }
        
        .btn-clear:active {
            transform: scale(0.98);
        }
        
        .preview-note {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 12px;
        }
        
        /* Scrollbar styling */
        .terms-box::-webkit-scrollbar {
            width: 8px;
        }
        
        .terms-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .terms-box::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        
        .terms-box::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 20px;
            }
            
            .signature-canvas {
                height: 150px;
            }
            
            table {
                font-size: 11px;
            }
            
            th, td {
                padding: 6px;
            }
        }
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .car-info-box {
            flex-direction: column;
            text-align: center;
        }
        
        .car-info-box img {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

<div class="booking-container">
    <a href="{{ route('vehicles.show', $vehicle->plate_no) }}" class="back-arrow" title="Back">←</a>
    
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; border-radius: 8px;">
            {{ session('error') }}
        </div>
    @endif
    
    @if(empty(Auth::guard('customer')->user()->name) || empty(Auth::guard('customer')->user()->phone_no))
        <div class="alert-warning-profile">
            <span class="icon">⚠️</span>
            <div>
                <h4>Incomplete Profile</h4>
                <p>Your profile is incomplete. Please update your full name and phone number before making a booking.</p>
                <a href="{{ route('customer.profile.edit') }}" class="btn-complete-profile">
                    Complete Profile Now →
                </a>
            </div>
        </div>
    @endif

    <div class="booking-card">
        <h2 class="section-title">Fill in Booking Details</h2>
        
        <!-- Car Information -->
        <div class="car-info-box">
            <img src="{{ $vehicle->image_url ?? asset('car_images/axia.jpg') }}" alt="{{ $vehicle->name }}">
            <div class="car-details">
                <h3>{{ $vehicle->name }}</h3>
                <p class="car-meta">License Plate: <strong style="color: #d93025;">{{ $vehicle->plate_no }}</strong></p>
                <p class="car-meta">Year: {{ $vehicle->year }} | Color: {{ $vehicle->color }}</p>
                <p class="car-meta">Passengers: {{ $vehicle->passengers }}</p>
                <p class="car-price">RM {{ number_format($vehicle->price_perHour, 2) }}/hour</p>
            </div>
        </div>
        
        <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}">
            @csrf
            <input type="hidden" name="plate_no" value="{{ $vehicle->plate_no }}">
            <input type="hidden" name="signature" id="signatureInput">

            <!-- Customer Information (Read-only from profile) -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="customer_name" class="form-control" value="{{ Auth::guard('customer')->user()->name }}" readonly required style="background: #e5e7eb; cursor: not-allowed;">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="customer_phone" class="form-control" value="{{ Auth::guard('customer')->user()->phone_no }}" readonly required style="background: #e5e7eb; cursor: not-allowed;">
                </div>
            </div>

            <!-- Pickup Date & Time -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">📅 Pickup Date</label>
                    <input type="date" name="pickup_date" id="pickupDate" class="form-control" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">🕐 Pickup Time</label>
                    <input type="time" name="pickup_time" id="pickupTime" class="form-control" required>
                </div>
            </div>
            
            <!-- Return Date & Time -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">📅 Return Date</label>
                    <input type="date" name="return_date" id="returnDate" class="form-control" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">🕐 Return Time</label>
                    <input type="time" name="return_time" id="returnTime" class="form-control" required>
                </div>
            </div>
            
            <!-- Pickup Location -->
            <div class="form-group">
                <label class="form-label">📍 Pickup Location</label>
                <select name="pickup_location" id="pickupLocation" class="form-control" required>
                    <option value="">Select a location</option>
                    <option value="HASTA office">HASTA office</option>
                    <option value="Residential College">Residential College</option>
                    <option value="Faculty">Faculty</option>
                </select>
            </div>
            
            <div id="pickupCollegeField" class="nested-field" style="display: none;">
                <label class="form-label">Which Residential College?</label>
                <input type="text" name="pickup_college" class="form-control" placeholder="e.g., Kolej Tun Dr. Ismail">
            </div>
            
            <div id="pickupFacultyField" class="nested-field" style="display: none;">
                <label class="form-label">Which Faculty?</label>
                <input type="text" name="pickup_faculty" class="form-control" placeholder="e.g., Faculty of Engineering">
            </div>
            
            <!-- Drop-off Location -->
            <div class="form-group">
                <label class="form-label">📍 Drop-off Location</label>
                <select name="dropoff_location" id="dropoffLocation" class="form-control" required>
                    <option value="">Select a location</option>
                    <option value="HASTA office">HASTA office</option>
                    <option value="Residential College">Residential College</option>
                    <option value="Faculty">Faculty</option>
                </select>
            </div>
            
            <div id="dropoffCollegeField" class="nested-field" style="display: none;">
                <label class="form-label">Which Residential College?</label>
                <input type="text" name="dropoff_college" class="form-control" placeholder="e.g., Kolej Tun Dr. Ismail">
            </div>
            
            <div id="dropoffFacultyField" class="nested-field" style="display: none;">
                <label class="form-label">Which Faculty?</label>
                <input type="text" name="dropoff_faculty" class="form-control" placeholder="e.g., Faculty of Engineering">
            </div>
            
            <!-- Delivery Notice -->
            <div id="deliveryNotice" class="delivery-notice" style="display: none;">
                <p>⚠️ RM 15 delivery fee applies (shown at payment).</p>
            </div>
            
            <!-- Voucher Section -->
            <div class="voucher-section">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">Voucher Code (Optional)</h3>
                <div class="voucher-row">
                    <input type="text" name="voucher_code" id="voucherCode" class="form-control" placeholder="Enter voucher code">
                    <button type="button" class="btn-apply" onclick="applyVoucher()">Apply</button>
                </div>
                <p id="voucherMessage" class="voucher-success" style="display: none;"></p>
            </div>
            
            <!-- Price Summary -->
            <div id="priceSummary" class="price-summary" style="display: none;">
                <h3>Price Summary</h3>
                <div class="price-row">
                    <span>Rental Duration:</span>
                    <span id="durationDisplay">0 hours</span>
                </div>
                <div class="price-row">
                    <span>Price per Hour:</span>
                    <span>RM {{ number_format($vehicle->price_perHour, 2) }}</span>
                </div>
                <div class="price-row">
                    <span>Subtotal:</span>
                    <span id="subtotalDisplay">RM 0.00</span>
                </div>
                <div class="price-row discount" id="discountRow" style="display: none;">
                    <span>Discount (<span id="discountPercent">0</span>%):</span>
                    <span id="discountDisplay">- RM 0.00</span>
                </div>
                <div class="price-row total">
                    <span>Total Amount:</span>
                    <span class="amount" id="totalDisplay">RM 0.00</span>
                </div>
            </div>
            
        <div class="container">
        <div class="agreement-section">
            <h3>Rental Agreement</h3>
            
            <div class="terms-box">
                
                <p class="header-text">
                    HASTA TRAVEL & TOURS SDN. BHD. 202001003057 (1359376T)<br>
                    KPK/LN 10181
                </p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Left Column -->
                    <div>
                        <h4>Rates</h4>
                        <p>Rental rates are charged for minimum of 1-hour RM30. Rental with more than 12 hours will be considered as 1-day rental. Extend hours will be calculated at fix rate based on Table 1. Rates include maximum mileage of 300 km per day and replace car breakdown (if car got problem on road because of car maintenance only). Rates are in Ringgit Malaysia (RM).</p>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>HOUR</th>
                                    <th>1</th>
                                    <th>3</th>
                                    <th>5</th>
                                    <th>7</th>
                                    <th>9</th>
                                    <th>12</th>
                                    <th>24</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>RATE AXIA (RM)</td>
                                    <td>30</td>
                                    <td>50</td>
                                    <td>60</td>
                                    <td>65</td>
                                    <td>70</td>
                                    <td>80</td>
                                    <td>110</td>
                                </tr>
                                <tr>
                                    <td>RATE MYVI/BEZZA/SAGA (RM)</td>
                                    <td>35</td>
                                    <td>55</td>
                                    <td>65</td>
                                    <td>70</td>
                                    <td>75</td>
                                    <td>85</td>
                                    <td>130</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="text-align: center; font-size: 12px; font-style: italic; margin-bottom: 16px;">Table 1: Price List Hasta</p>
                        
                        <h4>Driver's Age and License Requirements</h4>
                        <p>The driver must be between 19 to 55 years old for all car category vehicles and in possession of a valid national or International Driving License. Probational license holders will not be accepted.</p>
                        
                        <h4>Terms of Payment & Deposit</h4>
                        <p>All rentals are subjected to a compulsory deposit of RM50.00 per car with maximum rental of 5 days. For weekly rental deposit will be RM150 and for one month is equal to one month rental. Our company only accepts the online payment for deposits and rental. Cash is accepted as mode of payment at the counter. Refundable deposit depends on return car condition (fuel, late return, extend and accident).</p>
                        
                        <h4>Cancellation Policy</h4>
                        <p>All paid rental and deposit cannot be cancelled, and payment made are non-refundable.</p>

                        <h4>Excess Fee</h4>
                        <p>The renter shall be held responsible for accidental damage to third party property and bodily injuries. However, the renter is always responsible for an amount equivalent to the excess fee based on Table 2. A full responsible will be on the renter for damage as a result of illegal, negligence, careless actions, tyre punctures, bust tyre, scratches and dent, lack of battery power because of forgotten turned off car electrical devices, loss or damage to the vehicle and vehicle accessories and damages of windows, mirror and undercarriage. In the event of any accident, the renter must agree to accept the Excess Fee and inform our company first before taking any action and make a police report within 24 hours from the time of the accident or theft. Our company shall be entitled to charge the renter an excess fee which is in accordance with the following Table 2. Upon the renter's acceptance and subject to the terms and conditions stipulated in the Rental Agreement, the renter's liability is limited to the Excess Fee. Excess Fee is used to cover loss of company sales for that particular car while repairing. Any extra charge of the repairing cost will be added if needed by the company. Receipt of any additional cost will be given to the customer.</p>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>TYPES OF CAR</th>
                                    <th>EXCESS FEE (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PERODUA MYVI</td>
                                    <td>2,500</td>
                                </tr>
                                <tr>
                                    <td>PROTON SAGA</td>
                                    <td>2,500</td>
                                </tr>
                                <tr>
                                    <td>PERODUA AXIA</td>
                                    <td>2,000</td>
                                </tr>
                                <tr>
                                    <td>PERODUA BEZZA</td>
                                    <td>2,500</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h4>Fuel</h4>
                        <p>Our company does not provide full tank unless requested by the renter and must be returned the same fuel level. Otherwise the renter will be charged based on 1 bar RM10.</p>
                        
                        <h4>Parking Fees and Traffic Fines</h4>
                        <p>The renter is liable for all parking and traffic fines incurred for the duration of the rental. An additional RM20 administration fee will be charged to the renter over and above any fine and penalty cost for any violation arising from the renter's use of vehicle. Our company retains the right to charge against the renter's charge if and when payment is due for traffic fines committed by the renter, upon receiving notification from the government authorities.</p>
                        
                        <h4>Vehicle Condition & Cleanliness</h4>
                        <p>Upon return, the car must be in the same condition as when it was rented. Failing which, the renter will be liable for the cost of restoring the vehicle to its original condition and loss of company sales for that particular car.</p>
                        
                        <h4>Surcharge</h4>
                        <p>If customer want to meet reservations after operating hours, a fee of RM10 will be charged.</p>
                        
                        <h4>Restricted Entry</h4>
                        <p>The vehicles cannot be driven into Singapore, Thailand, Brunei and Indonesia. Subsequently the vehicles are prohibited from being loaded onto other modes of transportation via sea, river and air for usage from mainland to Langkawi, Tioman, Redang, Pangkor island etc.</p>
                        
                        <h4>Prohibited Odours</h4>
                        <p>All items and goods discharging unpleasant odours are strictly forbidden from being carried in the vehicle (e.g. Durians, salted fish etc). The renter will be liable to reimburse on demand for all costs of eliminating such odours, including the servicing of the whole air conditioner system and the loss of rental days. Please be advised that smoking in vehicles is strictly prohibited.</p>
                        
                        <h4>Limitation Destination</h4>
                        <p>All customer are limit to Johor state area only for 1 day rental. Minimum rental for 2 days for rental area outside Johor state area. If the customer fails to comply, penalty will be charge 1 day rental extra and deposit will be burn.</p>
                    </div>
                </div>
            </div>
            
            <label class="checkbox-label">
                <input type="checkbox" id="agreeTerms" required>
                <span>I have read and agree to the rental terms and conditions stated above.</span>
            </label>
                
                <div class="signature-box">
                    <label class="form-label">Digital Signature</label>
                    <p style="font-size: 13px; color: #6b7280; margin-bottom: 12px;">
                        Please sign in the box below. Your signature will be added to the rental agreement PDF.
                    </p>
                    <canvas id="signatureCanvas" class="signature-canvas"></canvas>
                    <div class="signature-actions">
                        <button type="button" class="btn-clear" onclick="clearSignature()">Clear Signature</button>
                    </div>
                </form>
                </div>
            
            <button type="submit" class="btn-submit">Confirm Booking</button>
        </form>
    </div>
</div>
<script>
    const pricePerHour = {{ $vehicle->price_perHour }};
    let voucherDiscount = 0;
    
    // Signature Canvas
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let hasSignature = false;
    
    // Set canvas size
    canvas.width = canvas.offsetWidth;
    canvas.height = 150;
    
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Touch support
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });
    
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });
    
    canvas.addEventListener('touchend', (e) => {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup', {});
        canvas.dispatchEvent(mouseEvent);
    });
    
    function startDrawing(e) {
        isDrawing = true;
        hasSignature = true;
        const rect = canvas.getBoundingClientRect();
        ctx.beginPath();
        ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    }
    
    function draw(e) {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.stroke();
    }
    
    function stopDrawing() {
        isDrawing = false;
    }
    
    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSignature = false;
        document.getElementById('signatureInput').value = '';
    }
    
    // Location handlers
    document.getElementById('pickupLocation').addEventListener('change', function() {
        const college = document.getElementById('pickupCollegeField');
        const faculty = document.getElementById('pickupFacultyField');
        
        college.style.display = 'none';
        faculty.style.display = 'none';
        
        if (this.value === 'Residential College') {
            college.style.display = 'block';
            college.querySelector('input').required = true;
            faculty.querySelector('input').required = false;
        } else if (this.value === 'Faculty') {
            faculty.style.display = 'block';
            faculty.querySelector('input').required = true;
            college.querySelector('input').required = false;
        } else {
            college.querySelector('input').required = false;
            faculty.querySelector('input').required = false;
        }
        
        checkDelivery();
    });
    
    document.getElementById('dropoffLocation').addEventListener('change', function() {
        const college = document.getElementById('dropoffCollegeField');
        const faculty = document.getElementById('dropoffFacultyField');
        
        college.style.display = 'none';
        faculty.style.display = 'none';
        
        if (this.value === 'Residential College') {
            college.style.display = 'block';
            college.querySelector('input').required = true;
            faculty.querySelector('input').required = false;
        } else if (this.value === 'Faculty') {
            faculty.style.display = 'block';
            faculty.querySelector('input').required = true;
            college.querySelector('input').required = false;
        } else {
            college.querySelector('input').required = false;
            faculty.querySelector('input').required = false;
        }
        
        checkDelivery();
    });
    
    function checkDelivery() {
        const pickup = document.getElementById('pickupLocation').value;
        const dropoff = document.getElementById('dropoffLocation').value;
        const notice = document.getElementById('deliveryNotice');
        
        if ((pickup && pickup !== 'HASTA office') || (dropoff && dropoff !== 'HASTA office')) {
            notice.style.display = 'block';
        } else {
            notice.style.display = 'none';
        }
    }
    
    // Calculate price
    function calculatePrice() {
        const pickupDate = document.getElementById('pickupDate').value;
        const pickupTime = document.getElementById('pickupTime').value;
        const returnDate = document.getElementById('returnDate').value;
        const returnTime = document.getElementById('returnTime').value;
        
        if (!pickupDate || !pickupTime || !returnDate || !returnTime) {
            document.getElementById('priceSummary').style.display = 'none';
            return;
        }
        
        const pickup = new Date(`${pickupDate}T${pickupTime}`);
        const returnDt = new Date(`${returnDate}T${returnTime}`);
        
        const diffMs = returnDt - pickup;
        const hours = Math.max(0, Math.ceil(diffMs / (1000 * 60 * 60)));
        
        const subtotal = hours * pricePerHour;
        const discount = (subtotal * voucherDiscount) / 100;
        const total = subtotal - discount;
        
        document.getElementById('durationDisplay').textContent = `${hours} hour${hours !== 1 ? 's' : ''}`;
        document.getElementById('subtotalDisplay').textContent = `RM ${subtotal.toFixed(2)}`;
        document.getElementById('totalDisplay').textContent = `RM ${total.toFixed(2)}`;
        
        if (voucherDiscount > 0) {
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountPercent').textContent = voucherDiscount;
            document.getElementById('discountDisplay').textContent = `- RM ${discount.toFixed(2)}`;
        } else {
            document.getElementById('discountRow').style.display = 'none';
        }
        
        document.getElementById('priceSummary').style.display = 'block';
    }
    
    ['pickupDate', 'pickupTime', 'returnDate', 'returnTime'].forEach(id => {
        document.getElementById(id).addEventListener('change', calculatePrice);
    });
    
    // Voucher - NEW AJAX VALIDATION
    function applyVoucher() {
        const code = document.getElementById('voucherCode').value.trim().toUpperCase();
        const msg = document.getElementById('voucherMessage');
        const applyBtn = document.querySelector('.btn-apply');
        
        if (!code) {
            msg.textContent = '⚠️ Please enter a voucher code';
            msg.style.color = '#dc2626';
            msg.style.display = 'block';
            return;
        }
        
        // Disable button and show loading
        applyBtn.disabled = true;
        applyBtn.textContent = 'Checking...';
        msg.textContent = '⏳ Validating voucher...';
        msg.style.color = '#4b5563';
        msg.style.display = 'block';
        
        // AJAX call to validate voucher
        fetch('{{ route("vouchers.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                voucherDiscount = data.discount;
                msg.textContent = `Voucher applied successfully: ${voucherDiscount}% discount!`;
                msg.style.color = '#059669';
                msg.style.display = 'block';
                applyBtn.textContent = 'Applied ✓';
                applyBtn.style.background = '#059669';
                
                // Update the voucher input to show the validated code
                document.getElementById('voucherCode').value = data.code;
                
                calculatePrice();
            } else {
                msg.textContent = (data.message || 'Invalid voucher code');
                msg.style.color = '#dc2626';
                msg.style.display = 'block';
                voucherDiscount = 0;
                applyBtn.disabled = false;
                applyBtn.textContent = 'Apply';
                applyBtn.style.background = '#4b5563';
                calculatePrice();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            msg.textContent = 'Error validating voucher. Please try again.';
            msg.style.color = '#dc2626';
            msg.style.display = 'block';
            voucherDiscount = 0;
            applyBtn.disabled = false;
            applyBtn.textContent = 'Apply';
            applyBtn.style.background = '#4b5563';
        });
    }
    
    // Form submission
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        // Check customer info
        const customerName = document.querySelector('input[name="customer_name"]').value.trim();
        const customerPhone = document.querySelector('input[name="customer_phone"]').value.trim();
        
        if (!customerName || !customerPhone) {
            e.preventDefault();
            alert('⚠️ Please complete your profile with your full name and phone number before booking.');
            window.location.href = '{{ route("customer.profile.edit") }}';
            return;
        }
        
        if (!hasSignature) {
            e.preventDefault();
            alert('Please provide your signature before submitting.');
            return;
        }
        
        // Validate rental duration
        const pickupDate = document.getElementById('pickupDate').value;
        const pickupTime = document.getElementById('pickupTime').value;
        const returnDate = document.getElementById('returnDate').value;
        const returnTime = document.getElementById('returnTime').value;
        
        if (pickupDate && pickupTime && returnDate && returnTime) {
            const pickup = new Date(`${pickupDate}T${pickupTime}`);
            const returnDt = new Date(`${returnDate}T${returnTime}`);
            
            if (returnDt <= pickup) {
                e.preventDefault();
                alert('Return date and time must be after pickup date and time.');
                return;
            }
            
            const diffMs = returnDt - pickup;
            const hours = diffMs / (1000 * 60 * 60);
            
            if (hours < 1) {
                e.preventDefault();
                alert('Minimum rental duration is 1 hour.');
                return;
            }
        }
        
        // Save signature as base64
        document.getElementById('signatureInput').value = canvas.toDataURL();
    });
</script>
@endsection