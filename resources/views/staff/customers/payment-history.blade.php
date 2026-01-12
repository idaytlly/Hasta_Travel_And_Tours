{{-- resources/views/staff/customers/payment-history.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Payment History - ' . $customer->name)
@section('page-title', 'Payment History')
@section('page-subtitle', $customer->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.customers.index') }}">Customers</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.customers.show', $customer->id) }}">{{ $customer->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payment History</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Payments for {{ $customer->name }}</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <a href="{{ route('staff.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        <h4 class="text-primary">{{ $payments->total() }}</h4>
                        <p class="text-muted mb-0">Total Payments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        @php
                            $paid = $payments->where('payment_status', 'paid')->count();
                        @endphp
                        <h4 class="text-success">{{ $paid }}</h4>
                        <p class="text-muted mb-0">Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        @php
                            $pending = $payments->where('payment_status', 'pending')->count();
                        @endphp
                        <h4 class="text-warning">{{ $pending }}</h4>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        @php
                            $totalAmount = $payments->where('payment_status', 'paid')->sum('amount');
                        @endphp
                        <h4 class="text-info">RM {{ number_format($totalAmount, 2) }}</h4>
                        <p class="text-muted mb-0">Total Paid</p>
                    </div>
                </div>
            </div>
        </div>

        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Verified At</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $payment->payment_id }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('staff.bookings.show', $payment->booking_id) }}" class="text-decoration-none">
                                        {{ $payment->booking->booking_id ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    {{ $payment->payment_date->format('M d, Y') }}
                                    <div class="text-muted">{{ $payment->payment_date->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                </td>
                                <td>
                                    <strong class="{{ $payment->payment_status == 'paid' ? 'text-success' : 'text-warning' }}">
                                        RM {{ number_format($payment->amount, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @if($payment->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($payment->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($payment->payment_status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->payment_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->verified_at)
                                        {{ $payment->verified_at->format('M d, Y') }}
                                        <div class="text-muted">{{ $payment->verified_at->format('h:i A') }}</div>
                                    @else
                                        <span class="text-muted">Not verified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->payment_notes)
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="popover" 
                                                data-bs-content="{{ $payment->payment_notes }}"
                                                title="Payment Notes">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">No notes</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Payment Methods Summary</h6>
                            @php
                                $methodSummary = $payments->groupBy('payment_method')->map(function($group) {
                                    return [
                                        'count' => $group->count(),
                                        'total' => $group->where('payment_status', 'paid')->sum('amount')
                                    ];
                                });
                            @endphp
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Count</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($methodSummary as $method => $data)
                                        <tr>
                                            <td>{{ ucfirst($method) }}</td>
                                            <td>{{ $data['count'] }}</td>
                                            <td>RM {{ number_format($data['total'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Monthly Summary</h6>
                            @php
                                $monthlySummary = $payments->where('payment_status', 'paid')
                                    ->groupBy(function($payment) {
                                        return $payment->payment_date->format('Y-m');
                                    })
                                    ->map(function($group) {
                                        return $group->sum('amount');
                                    })
                                    ->sortDesc()
                                    ->take(6);
                            @endphp
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlySummary as $month => $amount)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</td>
                                            <td class="text-success">RM {{ number_format($amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                <h5>No Payment History</h5>
                <p class="text-muted">No payments recorded for this customer.</p>
                <a href="{{ route('staff.customers.show', $customer->id) }}" class="btn btn-outline-primary mt-2">
                    <i class="fas fa-arrow-left me-2"></i> Back to Customer
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Payments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('staff.customers.payment-history', $customer->id) }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Min Amount</label>
                        <input type="number" name="min_amount" class="form-control" value="{{ request('min_amount') }}" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Amount</label>
                        <input type="number" name="max_amount" class="form-control" value="{{ request('max_amount') }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('staff.customers.payment-history', $customer->id) }}" class="btn btn-outline-secondary">Clear Filters</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>
@endpush