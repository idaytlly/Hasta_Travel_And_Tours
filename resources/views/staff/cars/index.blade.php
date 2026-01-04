@extends('layouts.staff')

@section('title', 'Vehicle Management')
@section('page-title', 'Vehicle Management')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-light: rgba(220, 38, 38, 0.1);
        --success: #22c55e;
        --warning: #f59e0b;
        --info: #3b82f6;
        --purple: #8b5cf6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f1f5f9;
        --white: #ffffff;
        --border: #e2e8f0;
    }

    body {
        background: var(--light) !important;
    }

    /* Header Section */
    .page-header-card {
        background: var(--white);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        color: var(--gray);
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-add-car {
        background: var(--primary);
        color: white;
    }

    .btn-add-car:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        color: white;
    }

    .btn-add-motorcycle {
        background: white;
        color: var(--purple);
        border: 2px solid var(--purple);
    }

    .btn-add-motorcycle:hover {
        background: var(--purple);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
    }

    /* Category Stats */
    .category-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .category-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .category-icon {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .category-icon.car {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .category-icon.motorcycle {
        background: rgba(139, 92, 246, 0.1);
        color: var(--purple);
    }

    .category-info {
        flex: 1;
    }

    .category-count {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .category-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    /* Search and Filter Card */
    .filter-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .search-box {
        position: relative;
        margin-bottom: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .filter-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-section {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dark);
        white-space: nowrap;
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid var(--border);
        background: white;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .filter-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .filter-btn.type-filter:hover {
        border-color: var(--info);
        color: var(--info);
    }

    .filter-btn.type-filter.active {
        background: var(--info);
        color: white;
        border-color: var(--info);
    }

    /* Vehicles Grid */
    .vehicles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .vehicle-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .vehicle-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .vehicle-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: var(--light);
    }

    .vehicle-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .vehicle-card:hover .vehicle-image {
        transform: scale(1.05);
    }

    .vehicle-badge {
        position: absolute;
        top: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-status {
        right: 12px;
    }

    .badge-type {
        left: 12px;
    }

    .status-available {
        background: rgba(34, 197, 94, 0.9);
        color: white;
    }

    .status-unavailable {
        background: rgba(220, 38, 38, 0.9);
        color: white;
    }

    .type-car {
        background: rgba(59, 130, 246, 0.9);
        color: white;
    }

    .type-motorcycle {
        background: rgba(139, 92, 246, 0.9);
        color: white;
    }

    .vehicle-content {
        padding: 1.5rem;
    }

    .vehicle-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .vehicle-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--gray);
        font-size: 0.875rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .vehicle-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--gray);
    }

    .detail-icon {
        color: var(--primary);
        width: 16px;
    }

    .vehicle-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .vehicle-price small {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    .vehicle-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .btn-action {
        padding: 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-edit {
        background: var(--info);
        color: white;
    }

    .btn-edit:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        color: white;
    }

    .btn-delete {
        background: var(--primary);
        color: white;
    }

    .btn-delete:hover {
        background: #991b1b;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        background: var(--white);
        border-radius: 12px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--gray);
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: var(--gray);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .vehicles-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .category-stats {
            grid-template-columns: 1fr;
        }

        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-section {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .vehicle-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-4">
    
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <h1 class="header-title">
                    <i class="fas fa-car text-primary"></i> Vehicle Management
                </h1>
                <p class="header-subtitle">Manage your rental fleet with cars and motorcycles</p>
            </div>
            <div class="col-lg-6">
                <div class="header-actions justify-content-lg-end">
                    <a href="{{ route('staff.cars.create') }}?type=car" class="btn-add btn-add-car">
                        <i class="fas fa-car"></i>
                        Add New Car
                    </a>
                    <a href="{{ route('staff.cars.create') }}?type=motorcycle" class="btn-add btn-add-motorcycle">
                        <i class="fas fa-motorcycle"></i>
                        Add Motorcycle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="category-stats">
        @php
            $totalCars = $cars->where('category', '!=', 'motorcycle')->count();
            $totalMotorcycles = $cars->where('category', 'motorcycle')->count();
        @endphp

        <div class="category-card">
            <div class="category-icon car">
                <i class="fas fa-car"></i>
            </div>
            <div class="category-info">
                <div class="category-count">{{ $totalCars }}</div>
                <div class="category-label">Total Cars</div>
            </div>
        </div>

        <div class="category-card">
            <div class="category-icon motorcycle">
                <i class="fas fa-motorcycle"></i>
            </div>
            <div class="category-info">
                <div class="category-count">{{ $totalMotorcycles }}</div>
                <div class="category-label">Total Motorcycles</div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        @php
            $totalVehicles = $cars->count();
            $availableVehicles = $cars->where('is_available', 1)->count();
            $unavailableVehicles = $cars->where('is_available', 0)->count();
            $avgDailyRate = $cars->avg('daily_rate') ?? 0;
        @endphp

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                    <i class="fas fa-car"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalVehicles }}</div>
            <div class="stat-label">Total Vehicles</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-value">{{ $availableVehicles }}</div>
            <div class="stat-label">Available</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: rgba(220, 38, 38, 0.1); color: var(--primary);">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-value">{{ $unavailableVehicles }}</div>
            <div class="stat-label">Unavailable</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-value">RM {{ number_format($avgDailyRate, 0) }}</div>
            <div class="stat-label">Avg Daily Rate</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filter-card">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" 
                   class="search-input" 
                   id="searchInput"
                   placeholder="Search by brand, model, or license plate...">
        </div>

        <div class="filter-row">
            <div class="filter-section">
                <span class="filter-label">Type:</span>
                <div class="filter-buttons" id="typeFilter">
                    <button class="filter-btn type-filter active" data-filter="all">
                        <i class="fas fa-list"></i> All
                    </button>
                    <button class="filter-btn type-filter" data-filter="car">
                        <i class="fas fa-car"></i> Cars
                    </button>
                    <button class="filter-btn type-filter" data-filter="motorcycle">
                        <i class="fas fa-motorcycle"></i> Motorcycles
                    </button>
                </div>
            </div>

            <div class="filter-section">
                <span class="filter-label">Status:</span>
                <div class="filter-buttons" id="statusFilter">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="available">Available</button>
                    <button class="filter-btn" data-filter="unavailable">Unavailable</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles Grid -->
    <div class="vehicles-grid" id="vehiclesGrid">
        @forelse($cars as $car)
            @php
                $vehicleType = ($car->category == 'motorcycle') ? 'motorcycle' : 'car';
            @endphp

            <div class="vehicle-card" 
                 data-type="{{ $vehicleType }}"
                 data-available="{{ $car->is_available ? 'available' : 'unavailable' }}"
                 data-search="{{ strtolower($car->brand . ' ' . $car->model . ' ' . ($car->license_plate ?? '')) }}">
                
                <!-- Vehicle Image -->
                <div class="vehicle-image-container">
                    @if($car->image && !filter_var($car->image, FILTER_VALIDATE_URL))
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="vehicle-image">
                    @elseif($car->image)
                        <img src="{{ $car->image }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="vehicle-image">
                    @else
                        <div class="vehicle-image" style="display: flex; align-items: center; justify-content: center; background: var(--light);">
                            <i class="fas fa-{{ $vehicleType == 'motorcycle' ? 'motorcycle' : 'car' }}" style="font-size: 4rem; color: var(--border);"></i>
                        </div>
                    @endif

                    <!-- Type Badge -->
                    <div class="vehicle-badge badge-type type-{{ $vehicleType }}">
                        <i class="fas fa-{{ $vehicleType == 'motorcycle' ? 'motorcycle' : 'car' }}"></i>
                        {{ ucfirst($vehicleType) }}
                    </div>

                    <!-- Status Badge -->
                    <div class="vehicle-badge badge-status {{ $car->is_available ? 'status-available' : 'status-unavailable' }}">
                        {{ $car->is_available ? 'Available' : 'Unavailable' }}
                    </div>
                </div>

                <!-- Vehicle Content -->
                <div class="vehicle-content">
                    <h3 class="vehicle-name">{{ $car->brand }} {{ $car->model }}</h3>
                    
                    <div class="vehicle-meta">
                        <span><i class="fas fa-calendar"></i> {{ $car->year }}</span>
                        @if($car->license_plate)
                            <span><i class="fas fa-id-card"></i> {{ $car->license_plate }}</span>
                        @endif
                    </div>

                    <div class="vehicle-details">
                        <div class="detail-item">
                            <i class="fas fa-cog detail-icon"></i>
                            <span>{{ ucfirst($car->transmission ?? 'N/A') }}</span>
                        </div>
                        @if($car->fuel_type)
                        <div class="detail-item">
                            <i class="fas fa-gas-pump detail-icon"></i>
                            <span>{{ ucfirst($car->fuel_type) }}</span>
                        </div>
                        @endif
                        @if($vehicleType == 'car' && $car->seats)
                        <div class="detail-item">
                            <i class="fas fa-users detail-icon"></i>
                            <span>{{ $car->seats }} Seats</span>
                        </div>
                        @endif
                        @if($car->air_conditioner)
                        <div class="detail-item">
                            <i class="fas fa-snowflake detail-icon"></i>
                            <span>A/C</span>
                        </div>
                        @endif
                    </div>

                    <div class="vehicle-price">
                        RM {{ number_format($car->daily_rate, 2) }}
                        <small>/day</small>
                    </div>

                    <div class="vehicle-actions">
                        <a href="{{ route('staff.cars.edit', $car->id) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        
                        <form action="{{ route('staff.cars.destroy', $car->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this {{ $vehicleType }}? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete w-100">
                                <i class="fas fa-trash-alt"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3 class="empty-title">No Vehicles Found</h3>
                    <p class="empty-text">
                        Start building your rental fleet by adding your first vehicle. Choose between cars and motorcycles.
                    </p>
                    <div class="empty-actions">
                        <a href="{{ route('staff.cars.create') }}?type=car" class="btn-add btn-add-car">
                            <i class="fas fa-car"></i>
                            Add Your First Car
                        </a>
                        <a href="{{ route('staff.cars.create') }}?type=motorcycle" class="btn-add btn-add-motorcycle">
                            <i class="fas fa-motorcycle"></i>
                            Add Motorcycle
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeButtons = document.querySelectorAll('#typeFilter .filter-btn');
    const statusButtons = document.querySelectorAll('#statusFilter .filter-btn');
    const vehicleCards = document.querySelectorAll('.vehicle-card');

    // Search functionality
    searchInput.addEventListener('input', function() {
        filterVehicles();
    });

    // Type filter
    typeButtons.forEach(button => {
        button.addEventListener('click', function() {
            typeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterVehicles();
        });
    });

    // Status filter
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            statusButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterVehicles();
        });
    });

    function filterVehicles() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const activeType = document.querySelector('#typeFilter .filter-btn.active').dataset.filter;
        const activeStatus = document.querySelector('#statusFilter .filter-btn.active').dataset.filter;
        
        let visibleCount = 0;

        vehicleCards.forEach(card => {
            const searchData = card.dataset.search;
            const vehicleType = card.dataset.type;
            const availableStatus = card.dataset.available;
            
            const matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
            const matchesType = activeType === 'all' || vehicleType === activeType;
            const matchesStatus = activeStatus === 'all' || availableStatus === activeStatus;
            
            if (matchesSearch && matchesType && matchesStatus) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show "no results" message if needed
        updateEmptyState(visibleCount);
    }

    function updateEmptyState(visibleCount) {
        const grid = document.getElementById('vehiclesGrid');
        let noResults = grid.querySelector('.no-results');

        if (visibleCount === 0 && vehicleCards.length > 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'col-12 no-results';
                noResults.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="empty-title">No Vehicles Match</h3>
                        <p class="empty-text">
                            Try adjusting your search or filters to find what you're looking for.
                        </p>
                        <button class="btn-add btn-add-car" onclick="resetFilters()">
                            <i class="fas fa-redo"></i>
                            Reset Filters
                        </button>
                    </div>
                `;
                grid.appendChild(noResults);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }

    window.resetFilters = function() {
        searchInput.value = '';
        typeButtons.forEach(btn => btn.classList.remove('active'));
        typeButtons[0].classList.add('active');
        statusButtons.forEach(btn => btn.classList.remove('active'));
        statusButtons[0].classList.add('active');
        filterVehicles();
    };
});
</script>
@endpush
@endsection