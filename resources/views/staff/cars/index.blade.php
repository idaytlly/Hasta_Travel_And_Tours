@extends('layouts.staff')

@section('page-title')
    Vehicle Inventory
@endsection

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Fleet Inventory</h1>
            <p class="text-muted mb-0">Manage technical specifications and fleet availability.</p>
        </div>
        <a href="{{ route('staff.cars.create') }}" class="btn btn-dark px-4 py-2 rounded-3 shadow-sm">
            <i class="fas fa-plus me-2"></i>Register New Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle me-3 fs-4"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Search and Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('staff.cars.index') }}" class="row g-3">
                {{-- Search Input --}}
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Search by name, brand, model, or registration number..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-indigo flex-grow-1">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('staff.cars.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-success-soft">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-success mb-1 small fw-bold">AVAILABLE</p>
                            <h3 class="mb-0 fw-bold text-success">{{ $cars->where('status', 'available')->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-primary-soft">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-primary mb-1 small fw-bold">IN USE</p>
                            <h3 class="mb-0 fw-bold text-primary">{{ $cars->where('status', 'rented')->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-key text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-warning-soft">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-warning mb-1 small fw-bold">MAINTENANCE</p>
                            <h3 class="mb-0 fw-bold text-warning">{{ $cars->where('status', 'maintenance')->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-tools text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-info-soft">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-info mb-1 small fw-bold">TOTAL FLEET</p>
                            <h3 class="mb-0 fw-bold text-info">{{ $cars->total() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-car text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cars Grid --}}
    <div class="row g-4">
        @forelse($cars as $car)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 car-management-card">
                    <div class="position-relative p-3">
                        <div class="status-badge-overlay">
                            @if($car->status === 'available')
                                <span class="badge bg-success-soft text-success border border-success-subtle px-3 py-2 rounded-pill">
                                    <i class="fas fa-circle me-1 small"></i> Available
                                </span>
                            @elseif($car->status === 'rented')
                                <span class="badge bg-primary-soft text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                    <i class="fas fa-key me-1 small"></i> In Use
                                </span>
                            @elseif($car->status === 'booked')
                                <span class="badge bg-info-soft text-info border border-info-subtle px-3 py-2 rounded-pill">
                                    <i class="fas fa-calendar-check me-1 small"></i> Booked
                                </span>
                            @else
                                <span class="badge bg-warning-soft text-warning border border-warning-subtle px-3 py-2 rounded-pill">
                                    <i class="fas fa-tools me-1 small"></i> Workshop
                                </span>
                            @endif
                        </div>
                        
                        <div class="car-image-container rounded-4 d-flex align-items-center justify-content-center bg-light overflow-hidden" style="height: 160px;">
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" class="img-fluid p-2" alt="{{ $car->name }}" style="max-height: 140px; object-fit: contain;">
                            @else
                                <i class="fas fa-car-side text-light-emphasis display-4"></i>
                            @endif
                        </div>
                    </div>

                    <div class="card-body pt-0 px-4 pb-4">
                        <div class="mb-3">
                            <h5 class="fw-bold text-dark mb-1">{{ $car->name }}</h5>
                            <span class="text-muted small fw-medium">{{ $car->brand }} {{ $car->model }} • {{ $car->year }}</span>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
                                <span><i class="fas fa-id-card me-1"></i> {{ $car->registration_number }}</span>
                                <span><i class="fas fa-users me-1"></i> {{ $car->seats }} seats</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small text-muted">
                                <span><i class="fas fa-cogs me-1"></i> {{ ucfirst($car->transmission) }}</span>
                                <span><i class="fas fa-gas-pump me-1"></i> {{ ucfirst($car->fuel_type) }}</span>
                            </div>
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-3 text-center">
                            <span class="text-muted d-block small fw-bold text-uppercase mb-1">Daily Rate</span>
                            <span class="text-indigo fw-bold fs-4">RM{{ number_format($car->daily_rate, 0) }}</span>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('staff.cars.show', $car->id) }}" class="btn btn-outline-info flex-fill rounded-3 border-light-subtle py-2">
                                <i class="fas fa-eye me-1 small"></i>View
                            </a>
                            <a href="{{ route('staff.cars.edit', $car->id) }}" class="btn btn-outline-secondary flex-fill rounded-3 border-light-subtle py-2">
                                <i class="fas fa-pen me-1 small"></i>Edit
                            </a>
                            <form action="{{ route('staff.cars.destroy', $car->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete {{ $car->name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-3 border-light-subtle py-2 px-3">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white rounded-4 p-5 shadow-sm border">
                    <i class="fas fa-car-side display-1 text-muted mb-4"></i>
                    <h4 class="fw-bold text-dark">No Vehicles Found</h4>
                    <p class="text-muted mb-4">
                        @if(request('search') || request('status'))
                            No vehicles match your search criteria. Try adjusting your filters.
                        @else
                            You haven't added any vehicles yet. Click "Register New Vehicle" to get started.
                        @endif
                    </p>
                    @if(request('search') || request('status'))
                        <a href="{{ route('staff.cars.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($cars->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $cars->links() }}
        </div>
    @endif
</div>

<style>
    :root {
        --indigo: #4f46e5;
        --indigo-hover: #4338ca;
        --bg-body: #f8fafc;
    }

    body { background-color: var(--bg-body); }

    /* Custom Button Indigo */
    .btn-indigo {
        background-color: var(--indigo);
        color: white;
        border: none;
    }
    .btn-indigo:hover {
        background-color: var(--indigo-hover);
        color: white;
    }

    /* Soft Badges */
    .bg-success-soft { background-color: #f0fdf4 !important; }
    .bg-primary-soft { background-color: #eff6ff !important; }
    .bg-warning-soft { background-color: #fffbeb !important; }
    .bg-info-soft { background-color: #f0f9ff !important; }
    
    .text-indigo { color: var(--indigo); }

    /* Card Styling */
    .car-management-card {
        transition: all 0.25s cubic-bezier(.4,0,.2,1);
        border: 1px solid transparent !important;
    }

    .car-management-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: var(--indigo) !important;
    }

    .status-badge-overlay {
        position: absolute;
        top: 25px;
        right: 25px;
        z-index: 2;
    }

    .car-image-container {
        border: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .car-management-card:hover .car-image-container {
        background-color: #ffffff !important;
    }

    .btn-light {
        background-color: #fff;
        border: 1px solid #e2e8f0;
    }

    /* Fix form control focus */
    .form-control:focus, .form-select:focus {
        border-color: var(--indigo);
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
    }
</style>
@endsection