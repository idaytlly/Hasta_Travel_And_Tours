{{-- resources/views/staff/bookings/export-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Report - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .info-box {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .summary-item {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 5px;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bookings Report</h1>
        <div class="subtitle">
            Generated on: {{ date('F d, Y h:i A') }}
        </div>
    </div>

    <div class="info-box">
        <strong>Report Period:</strong> 
        @if(request()->has('start_date') && request()->has('end_date'))
            {{ date('F d, Y', strtotime(request('start_date'))) }} to {{ date('F d, Y', strtotime(request('end_date'))) }}
        @else
            All Time
        @endif
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-value">{{ $bookings->count() }}</div>
            <div class="summary-label">Total Bookings</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">RM {{ number_format($bookings->sum('total_price'), 2) }}</div>
            <div class="summary-label">Total Revenue</div>
        </div>
        <div class="summary-item">
            @php
                $completed = $bookings->where('booking_status', 'completed')->count();
                $completionRate = $bookings->count() > 0 ? ($completed / $bookings->count()) * 100 : 0;
            @endphp
            <div class="summary-value">{{ number_format($completionRate, 1) }}%</div>
            <div class="summary-label">Completion Rate</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Pickup Date</th>
                <th>Return Date</th>
                <th>Duration</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                @php
                    $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
                    $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
                    $duration = $pickup->diffInHours($return);
                @endphp
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                    <td>{{ $booking->vehicle->model ?? 'N/A' }} ({{ $booking->plate_no }})</td>
                    <td>{{ $booking->pickup_date->format('M d, Y') }} {{ $booking->pickup_time }}</td>
                    <td>{{ $booking->return_date->format('M d, Y') }} {{ $booking->return_time }}</td>
                    <td>{{ $duration }} hours</td>
                    <td>RM {{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="status-badge status-{{ $booking->booking_status }}">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Revenue:</td>
                <td>RM {{ number_format($bookings->sum('total_price'), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div style="page-break-inside: avoid;">
        <h3>Status Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusGroups = $bookings->groupBy('booking_status');
                @endphp
                @foreach(['pending', 'confirmed', 'active', 'completed', 'cancelled'] as $status)
                    @php
                        $group = $statusGroups->get($status, collect());
                        $count = $group->count();
                        $percentage = $bookings->count() > 0 ? ($count / $bookings->count()) * 100 : 0;
                    @endphp
                    <tr>
                        <td>{{ ucfirst($status) }}</td>
                        <td>{{ $count }}</td>
                        <td>{{ number_format($percentage, 1) }}%</td>
                        <td>RM {{ number_format($group->sum('total_price'), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(request()->has('vehicle_type'))
        <div style="page-break-inside: avoid; margin-top: 30px;">
            <h3>Vehicle Type Analysis</h3>
            <table>
                <thead>
                    <tr>
                        <th>Vehicle Type</th>
                        <th>Bookings Count</th>
                        <th>Total Revenue</th>
                        <th>Average Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $vehicleGroups = $bookings->groupBy(function($booking) {
                            return $booking->vehicle->vehicle_type ?? 'Unknown';
                        });
                    @endphp
                    @foreach($vehicleGroups as $type => $group)
                        @php
                            $totalDuration = 0;
                            foreach($group as $booking) {
                                $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
                                $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
                                $totalDuration += $pickup->diffInHours($return);
                            }
                            $avgDuration = $group->count() > 0 ? $totalDuration / $group->count() : 0;
                        @endphp
                        <tr>
                            <td>{{ $type }}</td>
                            <td>{{ $group->count() }}</td>
                            <td>RM {{ number_format($group->sum('total_price'), 2) }}</td>
                            <td>{{ number_format($avgDuration, 1) }} hours</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <div>CarRental Management System</div>
        <div>Generated by: {{ Auth::guard('staff')->user()->name ?? 'System' }}</div>
        <div>Page {{ $pdf->getPage() }} of {{ $pdf->getPageCount() }}</div>
    </div>
</body>
</html>