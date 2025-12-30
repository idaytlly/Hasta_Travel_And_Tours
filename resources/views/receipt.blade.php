<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - {{ $booking->booking_reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .receipt-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .receipt-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .receipt-header .checkmark {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .booking-reference {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
        }
        .booking-reference h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .booking-reference p {
            color: #667eea;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .receipt-section {
            padding: 30px;
        }
        .section-title {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .info-item label {
            display: block;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .info-item value {
            display: block;
            color: #333;
            font-size: 16px;
            font-weight: 600;
        }
        .price-breakdown {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .price-row:last-child {
            border-bottom: none;
        }
        .price-row.total {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #667eea;
        }
        .price-row.discount {
            color: #28a745;
            font-weight: 600;
        }
        .voucher-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-print {
            background: #17a2b8;
            color: white;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
            }
            .buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="checkmark">✓</div>
            <h1>Booking Confirmed!</h1>
            <p>Thank you for your reservation</p>
        </div>

        <div class="booking-reference">
            <h2>Booking Reference</h2>
            <p>{{ $booking->booking_reference }}</p>
        </div>

        <div class="receipt-section">
            <h3 class="section-title">🚗 Car Details</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Car</label>
                    <value>{{ $booking->car->brand }} {{ $booking->car->model }}</value>
                </div>
                <div class="info-item">
                    <label>Daily Rate</label>
                    <value>RM {{ number_format($booking->car->daily_rate, 2) }}</value>
                </div>
            </div>

            <h3 class="section-title">👤 Customer Details</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Name</label>
                    <value>{{ $booking->user->name }}</value>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <value>{{ $booking->user->email }}</value>
                </div>
            </div>

            <h3 class="section-title">📅 Booking Details</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Pickup Location</label>
                    <value>{{ $booking->pickup_location }}</value>
                </div>
                <div class="info-item">
                    <label>Dropoff Location</label>
                    <value>{{ $booking->dropoff_location }}</value>
                </div>
                <div class="info-item">
                    <label>Pickup Date & Time</label>
                    <value>{{ date('d M Y', strtotime($booking->pickup_date)) }} at {{ date('h:i A', strtotime($booking->pickup_time)) }}</value>
                </div>
                <div class="info-item">
                    <label>Return Date & Time</label>
                    <value>{{ date('d M Y', strtotime($booking->return_date)) }} at {{ date('h:i A', strtotime($booking->return_time)) }}</value>
                </div>
                @if($booking->destination)
                <div class="info-item">
                    <label>Destination</label>
                    <value>{{ $booking->destination }}</value>
                </div>
                @endif
                <div class="info-item">
                    <label>Duration</label>
                    <value>{{ $booking->duration }} day(s)</value>
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <value>
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </value>
                </div>
                <div class="info-item">
                    <label>Payment Status</label>
                    <value>
                        <span class="status-badge status-{{ $booking->payment_status }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </value>
                </div>
            </div>

            <h3 class="section-title">💰 Price Breakdown</h3>
            <div class="price-breakdown">
                <div class="price-row">
                    <span>Base Price ({{ $booking->duration }} days × RM {{ number_format($booking->car->daily_rate, 2) }})</span>
                    <span>RM {{ number_format($booking->base_price, 2) }}</span>
                </div>
                
                @if($booking->voucher && $booking->discount_amount > 0)
                <div class="price-row discount">
                    <span>
                        Discount 
                        <span class="voucher-badge">{{ $booking->voucher }}</span>
                    </span>
                    <span>- RM {{ number_format($booking->discount_amount, 2) }}</span>
                </div>
                @endif

                <div class="price-row">
                    <span>Deposit Required (10%)</span>
                    <span>RM {{ number_format($booking->deposit_amount, 2) }}</span>
                </div>

                <div class="price-row total">
                    <span>Total Amount</span>
                    <span>RM {{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>

            <div class="buttons">
                <button class="btn btn-print" onclick="window.print()">🖨️ Print Receipt</button>
                <a href="{{ route('booking.create', ['car_id' => $booking->car_id]) }}" class="btn btn-secondary">← Back to Booking</a>
                <a href="#" class="btn btn-primary">Proceed to Payment →</a>
            </div>
        </div>
    </div>
</body>
</html>