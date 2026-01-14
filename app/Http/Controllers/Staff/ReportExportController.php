<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportExportController extends Controller
{
    /**
     * Export PDF report
     */
    public function exportPdf(Request $request)
    {
        try {
            $period = $request->input('period', 'month');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $type = $request->input('type', 'summary');
            
            // Generate sample data for testing
            $reportData = $this->getSampleReportData($period, $startDate, $endDate);
            
            // Prepare data for PDF
            $pdfData = [
                'title' => $this->getReportTitle($type, $period),
                'periodDisplay' => $this->generatePeriodDisplay($period, $startDate, $endDate),
                'summary' => $reportData['summary'],
                'tables' => $reportData['tables'],
                'companyLogo' => null, // Add your logo path here
                'generatedBy' => Auth::guard('staff')->user()->name ?? 'Staff',
            ];
            
            // Load the PDF view
            $pdf = Pdf::loadView('staff.reports.export.pdf', $pdfData);
            
            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);
            
            // Set filename
            $filename = $this->generateFilename($type, $period);
            
            // Return PDF for download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Export Excel report
     */
    public function exportExcel(Request $request)
    {
        // For now, return a simple response
        return response()->json([
            'message' => 'Excel export will be implemented soon',
            'status' => 'info'
        ]);
    }
    
    /**
     * Export customer invoice
     */
    public function exportCustomerInvoice(Request $request, $customerId)
    {
        try {
            // Generate sample invoice data
            $invoiceData = $this->getSampleInvoiceData($customerId);
            
            $pdfData = [
                'title' => "Invoice - Customer {$customerId}",
                'customer' => $invoiceData['customer'],
                'bookings' => $invoiceData['bookings'],
                'totalAmount' => $invoiceData['totalAmount'],
                'totalPaid' => $invoiceData['totalPaid'],
                'totalDue' => $invoiceData['totalDue'],
                'invoiceNumber' => 'INV-' . date('Ymd') . '-' . str_pad($customerId, 6, '0', STR_PAD_LEFT),
                'issueDate' => Carbon::now()->format('d F Y'),
                'dueDate' => Carbon::now()->addDays(30)->format('d F Y'),
                'companyLogo' => null,
                'companyAddress' => $this->getCompanyAddress(),
                'paymentInstructions' => $this->getPaymentInstructions(),
            ];
            
            $pdf = Pdf::loadView('staff.reports.export.invoice', $pdfData);
            $filename = "Invoice_Customer_{$customerId}_" . date('Ymd') . ".pdf";
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }
    
    /**
     * Export monthly report
     */
    public function exportMonthlyReport(Request $request, $year, $month)
    {
        try {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            
            // Generate sample monthly data
            $reportData = $this->getSampleMonthlyReport($year, $month);
            
            $pdfData = [
                'title' => "Monthly Report - {$startDate->format('F Y')}",
                'periodDisplay' => $startDate->format('F Y'),
                'summary' => $reportData['summary'],
                'tables' => $reportData['tables'],
                'companyLogo' => null,
                'generatedBy' => Auth::guard('staff')->user()->name ?? 'Staff',
            ];
            
            $pdf = Pdf::loadView('staff.reports.export.pdf', $pdfData);
            $filename = "Monthly_Report_{$startDate->format('F_Y')}.pdf";
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate monthly report: ' . $e->getMessage());
        }
    }
    
    /**
     * Helper methods
     */
    private function getSampleReportData($period, $startDate, $endDate)
{
    $revenue = rand(50000, 200000) + (rand(0, 99) / 100);
    $bookings = rand(100, 500);
    $avgBookingValue = $revenue / max(1, $bookings);
    
    return [
        'summary' => [
            'total_bookings' => $bookings,
            'total_revenue' => $revenue,
            'active_rentals' => rand(10, 50),
            'new_customers' => rand(20, 100),
            'booking_change' => rand(-20, 30),
            'revenue_change' => rand(-15, 40),
            'utilization_rate' => rand(65, 95),
            'avg_daily_revenue' => $revenue / 30,
            'avg_booking_value' => $avgBookingValue,
        ],
        'tables' => [
            'recent_bookings' => [
                [
                    'booking_id' => 'BK-' . date('Ymd') . '-001',
                    'customer_name' => 'Johnathan Smith',
                    'vehicle_name' => 'Toyota Camry 2023',
                    'period' => '3 days (15-18 Nov)',
                    'status' => 'completed',
                    'amount' => 450.00
                ],
                [
                    'booking_id' => 'BK-' . date('Ymd') . '-002',
                    'customer_name' => 'Sarah Johnson',
                    'vehicle_name' => 'Honda Civic Turbo',
                    'period' => '5 days (10-15 Nov)',
                    'status' => 'active',
                    'amount' => 625.00
                ],
                [
                    'booking_id' => 'BK-' . date('Ymd') . '-003',
                    'customer_name' => 'Michael Chen',
                    'vehicle_name' => 'Ford Ranger Raptor',
                    'period' => '7 days (12-19 Nov)',
                    'status' => 'confirmed',
                    'amount' => 980.00
                ],
                [
                    'booking_id' => 'BK-' . date('Ymd') . '-004',
                    'customer_name' => 'Emma Wilson',
                    'vehicle_name' => 'BMW 3 Series',
                    'period' => '2 days (20-22 Nov)',
                    'status' => 'pending',
                    'amount' => 320.00
                ],
                [
                    'booking_id' => 'BK-' . date('Ymd') . '-005',
                    'customer_name' => 'David Brown',
                    'vehicle_name' => 'Mercedes C-Class',
                    'period' => '4 days (18-22 Nov)',
                    'status' => 'active',
                    'amount' => 720.00
                ],
            ],
            'popular_vehicles' => [
                [
                    'name' => 'Toyota Camry 2023',
                    'category' => 'Premium Sedan',
                    'bookings' => 45,
                    'revenue' => 20250.00,
                    'utilization' => 92
                ],
                [
                    'name' => 'Honda Civic Turbo',
                    'category' => 'Sport Sedan',
                    'bookings' => 38,
                    'revenue' => 17100.00,
                    'utilization' => 88
                ],
                [
                    'name' => 'Ford Ranger Raptor',
                    'category' => 'Performance Pickup',
                    'bookings' => 32,
                    'revenue' => 22400.00,
                    'utilization' => 95
                ],
                [
                    'name' => 'BMW 3 Series',
                    'category' => 'Luxury Sedan',
                    'bookings' => 28,
                    'revenue' => 19600.00,
                    'utilization' => 85
                ],
                [
                    'name' => 'Mercedes C-Class',
                    'category' => 'Executive Sedan',
                    'bookings' => 25,
                    'revenue' => 18750.00,
                    'utilization' => 82
                ],
            ]
        ],
        'charts' => [
            'revenue_trend' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => [8500, 9200, 10200, 9800, 11500, 12500, 13200, 12800, 14200, 15800, 16500, 18000]
            ],
            'utilization' => [
                'labels' => ['Camry', 'Civic', 'Ranger', 'BMW', 'Mercedes'],
                'data' => [92, 88, 95, 85, 82]
            ]
        ]
    ];
    }
    
    private function getSampleInvoiceData($customerId)
    {
        return [
            'customer' => [
                'name' => 'Customer ' . $customerId,
                'address' => '123 Sample Street, Sample City',
                'phone' => '+6012-345-6789',
                'email' => 'customer' . $customerId . '@example.com',
            ],
            'bookings' => collect([
                (object)[
                    'id' => 1,
                    'booking_reference' => 'BK-' . date('Ymd') . '-001',
                    'vehicle' => (object)['name' => 'Toyota Camry', 'plate_number' => 'ABC123'],
                    'start_date' => Carbon::now()->subDays(10),
                    'end_date' => Carbon::now()->subDays(7),
                    'total_days' => 3,
                    'daily_rate' => 150.00,
                    'subtotal' => 450.00,
                    'tax_amount' => 27.00,
                    'total_amount' => 477.00,
                    'status' => 'completed',
                ],
                (object)[
                    'id' => 2,
                    'booking_reference' => 'BK-' . date('Ymd') . '-002',
                    'vehicle' => (object)['name' => 'Honda Civic', 'plate_number' => 'XYZ789'],
                    'start_date' => Carbon::now()->subDays(5),
                    'end_date' => Carbon::now(),
                    'total_days' => 5,
                    'daily_rate' => 125.00,
                    'subtotal' => 625.00,
                    'tax_amount' => 37.50,
                    'total_amount' => 662.50,
                    'status' => 'active',
                ],
            ]),
            'totalAmount' => 1139.50,
            'totalPaid' => 477.00,
            'totalDue' => 662.50,
        ];
    }
    
    private function getSampleMonthlyReport($year, $month)
    {
        return [
            'summary' => [
                'total_bookings' => rand(200, 800),
                'total_revenue' => rand(100000, 500000) + (rand(0, 99) / 100),
                'active_rentals' => rand(15, 60),
                'new_customers' => rand(30, 150),
            ],
            'tables' => [
                'recent_bookings' => [
                    [
                        'booking_id' => 'BK-' . date('Ymd', mktime(0, 0, 0, $month, 1, $year)) . '-001',
                        'customer_name' => 'Monthly Customer 1',
                        'vehicle_name' => 'Vehicle A',
                        'status' => 'completed',
                        'amount' => 500.00
                    ],
                    [
                        'booking_id' => 'BK-' . date('Ymd', mktime(0, 0, 0, $month, 15, $year)) . '-002',
                        'customer_name' => 'Monthly Customer 2',
                        'vehicle_name' => 'Vehicle B',
                        'status' => 'active',
                        'amount' => 750.00
                    ],
                ],
                'popular_vehicles' => [
                    [
                        'name' => 'Monthly Best Vehicle 1',
                        'category' => 'SUV',
                        'bookings' => 55,
                        'revenue' => 27500.00,
                        'utilization' => 88
                    ],
                    [
                        'name' => 'Monthly Best Vehicle 2',
                        'category' => 'Sedan',
                        'bookings' => 42,
                        'revenue' => 18900.00,
                        'utilization' => 75
                    ],
                ]
            ]
        ];
    }
    
    private function generatePeriodDisplay($period, $startDate, $endDate)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return "Today ({$now->format('d M Y')})";
            case 'yesterday':
                $yesterday = $now->copy()->subDay();
                return "Yesterday ({$yesterday->format('d M Y')})";
            case 'week':
                $weekStart = $now->copy()->startOfWeek();
                return "This Week ({$weekStart->format('d M')} - {$now->format('d M Y')})";
            case 'month':
                return "This Month ({$now->format('F Y')})";
            case 'quarter':
                $quarter = ceil($now->month / 3);
                return "Q{$quarter} {$now->year}";
            case 'year':
                return "This Year ({$now->year})";
            case 'custom':
                if ($startDate && $endDate) {
                    $start = Carbon::parse($startDate);
                    $end = Carbon::parse($endDate);
                    return "Custom Period ({$start->format('d M Y')} - {$end->format('d M Y')})";
                }
                return "Custom Period";
            default:
                return "Unknown Period";
        }
    }
    
    private function getReportTitle($type, $period)
    {
        $titles = [
            'summary' => 'Rental Performance Summary',
            'detailed' => 'Detailed Rental Report',
            'full' => 'Comprehensive Rental Analysis',
        ];
        
        $periodText = $this->generatePeriodDisplay($period, null, null);
        return ($titles[$type] ?? 'Rental Report') . " - {$periodText}";
    }
    
    private function generateFilename($type, $period)
    {
        $prefix = strtoupper($type);
        $date = date('Ymd_His');
        return "{$prefix}_Report_{$period}_{$date}.pdf";
    }
    
    private function getCompanyAddress()
    {
        return [
            'name' => config('app.name', 'Rental Management System'),
            'address' => '123 Business Street, City, State 12345',
            'phone' => '+1 (234) 567-8900',
            'email' => 'accounts@rental.example.com',
        ];
    }
    
    private function getPaymentInstructions()
    {
        return [
            'Bank Transfer' => 'Account: 1234567890, Bank: Example Bank',
            'Online Payment' => 'Pay via our portal with booking ID',
            'Cash' => 'Pay at our office during business hours',
        ];
    }
}