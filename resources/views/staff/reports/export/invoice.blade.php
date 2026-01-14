<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }} - {{ config('app.name') }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 20px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 16px;
            color: #666;
        }
        
        .customer-info, .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-box {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin: 0 10px;
        }
        
        .info-box-title {
            font-weight: bold;
            color: #111827;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .info-box-content p {
            margin: 5px 0;
        }
        
        .invoice-items {
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #374151;
        }
        
        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals-table {
            width: 300px;
            margin-left: auto;
            border: 2px solid #111827;
        }
        
        .totals-table td {
            border: none;
        }
        
        .totals-table tr:last-child td {
            border-top: 2px solid #111827;
            font-weight: bold;
            font-size: 14px;
        }
        
        .payment-instructions {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .payment-instructions h3 {
            color: #111827;
            margin-bottom: 15px;
        }
        
        .payment-method {
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #fef3c7;
            border-left: 4px solid #d97706;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="company-info">
            @if(isset($companyLogo) && file_exists($companyLogo))
            <div style="margin-bottom: 15px;">
                <img src="{{ $companyLogo }}" alt="Company Logo" style="max-height: 60px;">
            </div>
            @endif
            <div class="company-name">{{ $companyAddress['name'] ?? config('app.name') }}</div>
            <p>{{ $companyAddress['address'] ?? '' }}</p>
            <p>Phone: {{ $companyAddress['phone'] ?? '' }}</p>
            <p>Email: {{ $companyAddress['email'] ?? '' }}</p>
        </div>
        
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <div class="invoice-number">{{ $invoiceNumber }}</div>
            <p>Issue Date: {{ $issueDate }}</p>
            <p>Due Date: {{ $dueDate }}</p>
        </div>
    </div>
    
    <div class="customer-info">
        <div class="info-box">
            <div class="info-box-title">Bill To</div>
            <div class="info-box-content">
                <p><strong>{{ $customer->name }}</strong></p>
                <p>{{ $customer->address }}</p>
                <p>Phone: {{ $customer->phone }}</p>
                <p>Email: {{ $customer->email }}</p>
                <p>Customer ID: {{ $customer->customer_id ?? $customer->id }}</p>
            </div>
        </div>
        
        <div class="info-box">
            <div class="info-box-title">Invoice Details</div>
            <div class="info-box-content">
                <p><strong>Period:</strong> {{ $periodDisplay }}</p>
                <p><strong>Total Bookings:</strong> {{ $bookings->count() }}</p>
                <p><strong>Invoice Status:</strong> 
                    @php
                        $statusClass = 'status-pending';
                        if ($totalDue <= 0) {
                            $statusClass = 'status-paid';
                            $statusText = 'PAID';
                        } elseif ($dueDate < now()) {
                            $statusClass = 'status-overdue';
                            $statusText = 'OVERDUE';
                        } else {
                            $statusText = 'PENDING';
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="invoice-items">
        <h2 style="color: #111827; margin-bottom: 15px;">Booking Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Vehicle</th>
                    <th>Rental Period</th>
                    <th>Days</th>
                    <th class="text-right">Daily Rate</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">Tax</th>
                    <th class="text-right">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_reference ?? 'BK-' . $booking->id }}</td>
                    <td>{{ $booking->vehicle->name ?? 'N/A' }} ({{ $booking->vehicle->plate_number ?? 'N/A' }})</td>
                    <td>{{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}</td>
                    <td class="text-center">{{ $booking->total_days }}</td>
                    <td class="text-right">RM {{ number_format($booking->daily_rate, 2) }}</td>
                    <td class="text-right">RM {{ number_format($booking->subtotal, 2) }}</td>
                    <td class="text-right">RM {{ number_format($booking->tax_amount, 2) }}</td>
                    <td class="text-right">RM {{ number_format($booking->total_amount, 2) }}</td>
                    <td>
                        @php
                            $status = strtolower($booking->status);
                            $statusClasses = [
                                'confirmed' => 'status-confirmed',
                                'active' => 'status-active',
                                'completed' => 'status-completed',
                                'cancelled' => 'status-cancelled',
                                'pending' => 'status-pending',
                            ];
                            $statusClass = $statusClasses[$status] ?? 'status-pending';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">RM {{ number_format($totalAmount - $bookings->sum('tax_amount'), 2) }}</td>
            </tr>
            <tr>
                <td>Tax ({{ config('rental.tax_rate', 6) }}%):</td>
                <td class="text-right">RM {{ number_format($bookings->sum('tax_amount'), 2) }}</td>
            </tr>
            <tr>
                <td>Total Amount:</td>
                <td class="text-right">RM {{ number_format($totalAmount, 2) }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td class="text-right">RM {{ number_format($totalPaid, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Balance Due:</strong></td>
                <td class="text-right"><strong>RM {{ number_format($totalDue, 2) }}</strong></td>
            </tr>
        </table>
    </div>
    
    @if(isset($paymentInstructions) && !empty($paymentInstructions))
    <div class="payment-instructions">
        <h3>Payment Instructions</h3>
        @foreach($paymentInstructions as $method => $instructions)
        <div class="payment-method">
            <strong>{{ $method }}:</strong> {{ $instructions }}
        </div>
        @endforeach
    </div>
    @endif
    
    <div class="notes">
        <p><strong>Notes:</strong></p>
        <p>1. Please make payment by the due date to avoid late fees.</p>
        <p>2. Late payments are subject to a 1.5% monthly interest charge.</p>
        <p>3. For payment inquiries, contact our accounts department at {{ $companyAddress['email'] ?? 'accounts@example.com' }}</p>
        <p>4. Please include the invoice number with all payments.</p>
    </div>
    
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice. No signature is required.</p>
        <p>If you have any questions, please contact us.</p>
    </div>
</body>
</html>