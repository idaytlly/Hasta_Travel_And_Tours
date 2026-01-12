<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Rental Report' }} - {{ config('app.name') }}</title>
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
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .period {
            font-size: 14px;
            color: #666;
        }
        
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            flex: 1;
            min-width: 200px;
            margin: 5px;
            text-align: center;
        }
        
        .summary-card-title {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .summary-card-value {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
        }
        
        .summary-card-change {
            font-size: 10px;
            margin-top: 5px;
        }
        
        .change-positive {
            color: #059669;
        }
        
        .change-negative {
            color: #dc2626;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        th {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #374151;
        }
        
        td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            font-size: 11px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-confirmed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-active {
            background-color: #f3e8ff;
            color: #7c3aed;
        }
        
        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        .chart-placeholder {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            margin-bottom: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .logo {
            max-width: 150px;
            margin: 0 auto 10px;
        }
        
        .logo img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($companyLogo) && $companyLogo)
        <div class="logo">
            <img src="{{ $companyLogo }}" alt="Company Logo">
        </div>
        @endif
        
        <div class="company-name">{{ config('app.name', 'Rental Management System') }}</div>
        <div class="report-title">{{ $title ?? 'Rental Performance Report' }}</div>
        <div class="period">Period: {{ $periodDisplay ?? 'Not specified' }}</div>
        <div class="period">Generated on: {{ date('d F Y, h:i A') }}</div>
    </div>
    
    <div class="meta-info">
        <div>Report ID: {{ 'REP-' . date('Ymd-His') . '-' . strtoupper(Str::random(6)) }}</div>
        <div>Generated by: {{ Auth::user()->name ?? 'Staff' }}</div>
    </div>
    
    <!-- Summary Section -->
    <div class="section">
        <div class="section-title">Performance Summary</div>
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-title">Total Bookings</div>
                <div class="summary-card-value">{{ number_format($summary['total_bookings'] ?? 0) }}</div>
                @if(isset($summary['booking_change']))
                <div class="summary-card-change {{ ($summary['booking_change'] >= 0) ? 'change-positive' : 'change-negative' }}">
                    {{ ($summary['booking_change'] >= 0 ? '↑' : '↓') }} {{ abs($summary['booking_change']) }}% from previous period
                </div>
                @endif
            </div>
            
            <div class="summary-card">
                <div class="summary-card-title">Total Revenue</div>
                <div class="summary-card-value">RM {{ number_format($summary['total_revenue'] ?? 0, 2) }}</div>
                @if(isset($summary['revenue_change']))
                <div class="summary-card-change {{ ($summary['revenue_change'] >= 0) ? 'change-positive' : 'change-negative' }}">
                    {{ ($summary['revenue_change'] >= 0 ? '↑' : '↓') }} {{ abs($summary['revenue_change']) }}% from previous period
                </div>
                @endif
            </div>
            
            <div class="summary-card">
                <div class="summary-card-title">Active Rentals</div>
                <div class="summary-card-value">{{ number_format($summary['active_rentals'] ?? 0) }}</div>
                <div class="summary-card-change">Currently active rentals</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-card-title">New Customers</div>
                <div class="summary-card-value">{{ number_format($summary['new_customers'] ?? 0) }}</div>
                <div class="summary-card-change">This period</div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Chart Placeholder -->
    <div class="section">
        <div class="section-title">Revenue Trend</div>
        <div class="chart-placeholder">
            <div>📊 Revenue Chart</div>
            <div style="font-size: 10px; margin-top: 10px;">
                Monthly Revenue: RM {{ number_format($summary['total_revenue'] ?? 0, 2) }}
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings Table -->
    <div class="section">
        <div class="section-title">Recent Bookings</div>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th class="text-right">Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tables['recent_bookings'] ?? [] as $booking)
                <tr>
                    <td>{{ $booking['booking_id'] ?? 'N/A' }}</td>
                    <td>{{ $booking['customer_name'] ?? 'Unknown' }}</td>
                    <td>{{ $booking['vehicle_name'] ?? 'N/A' }}</td>
                    <td>{{ $booking['period'] ?? 'N/A' }}</td>
                    <td>
                        @php
                            $status = strtolower($booking['status'] ?? 'unknown');
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
                    <td class="text-right">{{ number_format($booking['amount'] ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No bookings found for this period</td>
                </tr>
                @endforelse
            </tbody>
            @if(isset($tables['recent_bookings']) && count($tables['recent_bookings']) > 0)
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Total:</td>
                    <td class="text-right" style="font-weight: bold;">
                        RM {{ number_format(collect($tables['recent_bookings'])->sum('amount') ?? 0, 2) }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    <!-- Popular Vehicles Table -->
    <div class="section">
        <div class="section-title">Popular Vehicles</div>
        <table>
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Category</th>
                    <th class="text-center">Bookings</th>
                    <th class="text-right">Revenue (RM)</th>
                    <th class="text-center">Utilization</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tables['popular_vehicles'] ?? [] as $vehicle)
                <tr>
                    <td>{{ $vehicle['name'] ?? 'Unknown' }}</td>
                    <td>{{ $vehicle['category'] ?? 'N/A' }}</td>
                    <td class="text-center">{{ $vehicle['bookings'] ?? 0 }}</td>
                    <td class="text-right">{{ number_format($vehicle['revenue'] ?? 0, 2) }}</td>
                    <td class="text-center">{{ $vehicle['utilization'] ?? 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No vehicle data available</td>
                </tr>
                @endforelse
            </tbody>
            @if(isset($tables['popular_vehicles']) && count($tables['popular_vehicles']) > 0)
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ number_format(collect($tables['popular_vehicles'])->sum('bookings') ?? 0) }}
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        RM {{ number_format(collect($tables['popular_vehicles'])->sum('revenue') ?? 0, 2) }}
                    </td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ number_format(collect($tables['popular_vehicles'])->avg('utilization') ?? 0, 1) }}%
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    <!-- Additional Sections for Different Report Types -->
    @if(isset($additionalSections) && !empty($additionalSections))
        @foreach($additionalSections as $section)
        <div class="section {{ $loop->iteration % 2 == 0 ? 'page-break' : '' }}">
            <div class="section-title">{{ $section['title'] }}</div>
            @if(isset($section['content']))
                {!! $section['content'] !!}
            @endif
        </div>
        @endforeach
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <div>This is a computer-generated report. No signature is required.</div>
        <div>Confidential - For internal use only</div>
        <div>Page <span class="page-number"></span> of <span class="page-count"></span></div>
    </div>
    
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 9;
            $font = $fontMetrics->getFont("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
            
            // Add watermark for draft reports
            if (isset($draft) && $draft) {
                $pdf->page_text(150, 400, "DRAFT COPY", $font, 60, array(0.9,0.9,0.9), 0, 45);
            }
        }
    </script>
</body>
</html>