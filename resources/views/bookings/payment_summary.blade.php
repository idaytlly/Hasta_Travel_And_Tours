<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Summary - HASTA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; padding: 50px 20px; color: #333; }
        .summary-card { max-width: 700px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .summary-header { background: #d84444; color: white; padding: 25px; text-align: center; }
        .summary-body { padding: 40px; }
        .detail-group { margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .label { color: #777; font-weight: 500; }
        .value { font-weight: 600; text-align: right; }
        .total-box { background: #fff5f5; border: 2px dashed #d84444; padding: 20px; border-radius: 10px; text-align: center; margin-top: 30px; }
        .total-amount { font-size: 36px; color: #d84444; font-weight: 800; margin-top: 5px; }
        .btn-confirm { display: block; width: 100%; background: #28a745; color: white; border: none; padding: 18px; border-radius: 8px; font-size: 18px; font-weight: 700; cursor: pointer; transition: 0.3s; text-decoration: none; margin-top: 20px; }
        .btn-confirm:hover { background: #218838; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="summary-card">
    <div class="summary-header">
        <h1><i class="fas fa-file-invoice-dollar"></i> Payment Summary</h1>
    </div>
    
    <div class="summary-body">
        <div class="detail-group">
            <div class="detail-row">
                <span class="label">Car Selected:</span>
                <span class="value">{{ $car->brand }} {{ $car->model }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Rental Duration:</span>
                <span class="value">{{ $bookingData['duration'] }} Day(s)</span>
            </div>
        </div>

        <div class="detail-group">
            <div class="detail-row">
                <span class="label">Pick-up:</span>
                <span class="value">{{ $bookingData['pickup_location'] }} ({{ $bookingData['pickup_date'] }})</span>
            </div>
            <div class="detail-row">
                <span class="label">Drop-off:</span>
                <span class="value">{{ $bookingData['dropoff_location'] }} ({{ $bookingData['return_date'] }})</span>
            </div>
        </div>

        @if($bookingData['voucher'])
        <div class="detail-row" style="color: #28a745;">
            <span class="label">Voucher Applied:</span>
            <span class="value">{{ $bookingData['voucher'] }}</span>
        </div>
        @endif

        <div class="detail-group">
        <div class="detail-row">
            <span class="label">Original Price:</span>
            <span class="value">RM {{ number_format($bookingData['total_price'] + ($bookingData['discount_amount'] ?? 0), 2) }}</span>
        </div>

        @if(($bookingData['discount_amount'] ?? 0) > 0)
        <div class="detail-row" style="color: #28a745; font-weight: bold;">
            <span class="label">Discount ({{ $bookingData['voucher'] }}):</span>
            <span class="value">- RM {{ number_format($bookingData['discount_amount'], 2) }}</span>
        </div>
        @endif
        </div>

        <div class="total-box">
            <span class="label">GRAND TOTAL</span>
            <div class="total-amount">RM {{ number_format($bookingData['total_price'], 2) }}</div>
        </div>

        <form action="{{ route('payment.show') }}" method="POST">
        @csrf
        {{-- Carry all data forward to the payment page --}}
        @foreach($bookingData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        
        <button type="submit" class="btn-confirm">
            PROCEED TO PAYMENT <i class="fas fa-credit-card"></i>
        </button>
        </form>
    </div>
</div>

</body>
</html>