{{-- resources/views/staff/bookings/late-returns.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Late Returns')
@section('page-title', 'Late Returns')
@section('page-subtitle', 'Manage overdue vehicle returns')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.bookings.index') }}">Bookings</a></li>
            <li class="breadcrumb-item active" aria-current="page">Late Returns</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Overdue Vehicle Returns</h5>
    </div>
    <div class="card-body">
        @if($lateBookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Return Date</th>
                            <th>Overdue Days</th>
                            <th>Estimated Charge</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lateBookings as $booking)
                            @php
                                $returnDate = \Carbon\Carbon::parse($booking->return_date);
                                $overdueDays = max(0, $returnDate->diffInDays(now(), false));
                                $hourlyRate = $booking->vehicle->price_perHour;
                                $estimatedCharge = $overdueDays * 24 * $hourlyRate;
                            @endphp
                            <tr>
                                <td>{{ $booking->booking_id }}</td>
                                <td>
                                    <div>{{ $booking->customer->name }}</div>
                                    <small class="text-muted">{{ $booking->customer->phone_no }}</small>
                                </td>
                                <td>
                                    <div>{{ $booking->vehicle->name }}</div>
                                    <small class="text-muted">{{ $booking->vehicle->plate_no }}</small>
                                </td>
                                <td>
                                    {{ $booking->return_date->format('M d, Y') }}
                                    <div class="text-muted">{{ $booking->return_time }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $overdueDays }} day(s)</span>
                                </td>
                                <td>
                                    RM {{ number_format($estimatedCharge, 2) }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" data-bs-target="#chargeModal{{ $booking->id }}"
                                                title="Add Late Charge">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </button>
                                        <a href="tel:{{ $booking->customer->phone_no }}" 
                                           class="btn btn-sm btn-outline-success" title="Call Customer">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Charge Modal -->
                            <div class="modal fade" id="chargeModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Late Return Charge</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('staff.bookings.add-late-charge', $booking->booking_id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Booking ID</label>
                                                    <input type="text" class="form-control" value="{{ $booking->booking_id }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Customer</label>
                                                    <input type="text" class="form-control" value="{{ $booking->customer->name }}" readonly>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Overdue Days</label>
                                                            <input type="number" class="form-control" value="{{ $overdueDays }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Hourly Rate</label>
                                                            <input type="text" class="form-control" value="RM {{ number_format($hourlyRate, 2) }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Late Charge Amount (RM)</label>
                                                    <input type="number" name="late_charge" class="form-control" 
                                                           value="{{ $estimatedCharge }}" step="0.01" min="0" required>
                                                    <div class="form-text">Based on {{ $overdueDays }} days overdue</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Notes</label>
                                                    <textarea name="notes" class="form-control" rows="2" 
                                                              placeholder="Reason for late charge..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Add Charge</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Total Overdue</h6>
                            <h3>{{ $lateBookings->count() }}</h3>
                            <small class="text-muted">Bookings</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Total Estimated Charges</h6>
                            @php
                                $totalEstimated = 0;
                                foreach($lateBookings as $booking) {
                                    $returnDate = \Carbon\Carbon::parse($booking->return_date);
                                    $overdueDays = max(0, $returnDate->diffInDays(now(), false));
                                    $hourlyRate = $booking->vehicle->price_perHour;
                                    $totalEstimated += $overdueDays * 24 * $hourlyRate;
                                }
                            @endphp
                            <h3>RM {{ number_format($totalEstimated, 2) }}</h3>
                            <small class="text-muted">Potential revenue</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Average Overdue</h6>
                            @php
                                $totalDays = 0;
                                foreach($lateBookings as $booking) {
                                    $returnDate = \Carbon\Carbon::parse($booking->return_date);
                                    $totalDays += max(0, $returnDate->diffInDays(now(), false));
                                }
                                $averageDays = $lateBookings->count() > 0 ? $totalDays / $lateBookings->count() : 0;
                            @endphp
                            <h3>{{ number_format($averageDays, 1) }}</h3>
                            <small class="text-muted">Days per booking</small>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5>No Late Returns</h5>
                <p class="text-muted">All vehicles have been returned on time.</p>
            </div>
        @endif
    </div>
</div>
@endsection