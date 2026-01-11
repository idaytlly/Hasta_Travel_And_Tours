@extends('staff.layouts.app')

@section('title', 'Vehicle Management')
@section('page-title', 'Vehicle Management')
@section('page-subtitle', 'Manage all rental vehicles')

@section('page-header')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-0">All Vehicles</h1>
            <p class="text-muted mb-0">View and manage rental vehicles</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.vehicles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Add Vehicle
            </a>
        </div>
    </div>
@endsection

@section('page-actions')
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-filter me-2"></i> Filter
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('staff.vehicles.index') }}">All Vehicles</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('staff.vehicles.index', ['status' => 'available']) }}">Available</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.vehicles.index', ['status' => 'booked']) }}">Booked</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.vehicles.index', ['status' => 'maintenance']) }}">Maintenance</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.vehicles.index', ['status' => 'unavailable']) }}">Unavailable</a></li>
        </ul>
    </div>
@endsection

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $stats['total'] }}</h5>
                <p class="card-text text-muted">Total Vehicles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $stats['available'] }}</h5>
                <p class="card-text text-muted">Available</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $stats['booked'] }}</h5>
                <p class="card-text text-muted">Booked</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">{{ $stats['maintenance'] }}</h5>
                <p class="card-text text-muted">Maintenance</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Vehicle List</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('staff.vehicles.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search vehicles..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Plate No</th>
                        <th>Type</th>
                        <th>Transmission</th>
                        <th>Fuel Type</th>
                        <th>Price/Hour</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($vehicle->image_url)
                                <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" 
                                     class="rounded me-3" style="width: 60px; height: 40px; object-fit: cover;">
                                @endif
                                <div>
                                    <strong>{{ $vehicle->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $vehicle->model }} • {{ $vehicle->year }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $vehicle->plate_no }}</strong>
                        </td>
                        <td>{{ ucfirst($vehicle->vehicle_type) }}</td>
                        <td>{{ ucfirst($vehicle->transmission) }}</td>
                        <td>{{ ucfirst($vehicle->fuel_type) }}</td>
                        <td>
                            <strong>RM {{ number_format($vehicle->price_perHour, 2) }}</strong>
                        </td>
                        <td>
                            @if($vehicle->availability_status == 'available')
                                <span class="badge bg-success">Available</span>
                            @elseif($vehicle->availability_status == 'booked')
                                <span class="badge bg-primary">Booked</span>
                            @elseif($vehicle->availability_status == 'maintenance')
                                <span class="badge bg-warning">Maintenance</span>
                            @else
                                <span class="badge bg-danger">Unavailable</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('staff.vehicles.show', $vehicle->plate_no) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.vehicles.edit', $vehicle->plate_no) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No vehicles found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
@endsection