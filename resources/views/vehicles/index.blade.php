@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<style>
    /* Page-specific styles copied from previous standalone file */
    body {
        padding-top: 70px; /* match layout spacing */
    }

    .main-content {
        padding: 40px 0;
        min-height: calc(100vh - 110px - 400px);
    }

    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .category-buttons { display:flex; gap:15px; flex-wrap:wrap; }
    .category-btn { padding:12px 30px; border-radius:25px; font-weight:600; background:#e53935; color:#fff; border:none; }
    .category-btn.active { background:#c62828; }

    .right-controls { display:flex; gap:15px; align-items:center; }
    .total-display { padding:12px 30px; border:2px solid #e53935; border-radius:25px; color:#e53935; font-weight:600; background:#fff; }
    .filter-btn { padding:12px 30px; border-radius:25px; background:#e53935; color:#fff; border:none; }

    .vehicle-card { background:#fff; border-radius:15px; padding:30px; margin-bottom:20px; box-shadow:0 2px 10px rgba(0,0,0,0.08); }
    .vehicle-info { display:flex; gap:30px; align-items:center; }
    .vehicle-image { width:150px; height:150px; border-radius:12px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#f0f0f0; }
    .vehicle-image img { width:100%; height:100%; object-fit:cover; }
    .vehicle-details h3 { font-size:24px; margin-bottom:8px; }
    .vehicle-plate { color:#6c757d; }
    .price { color:#e53935; font-size:28px; font-weight:bold; }
    .view-btn { padding:12px 35px; border-radius:25px; background:#e53935; color:#fff; text-decoration:none; }

    .no-vehicles { text-align:center; padding:60px 20px; background:#fff; border-radius:15px; }

    @media (max-width:768px){ .view-btn{ width:100%; margin-top:15px } }
</style>

<div class="main-content">
    <div class="container">
        <div class="filter-section">
            <div class="category-buttons">
                <button class="category-btn {{ !request('type') ? 'active' : '' }}" onclick="filterVehicles('all')">All</button>
                <button class="category-btn {{ request('type') == 'car' ? 'active' : '' }}" onclick="filterVehicles('car')">Car</button>
                <button class="category-btn {{ request('type') == 'bike' ? 'active' : '' }}" onclick="filterVehicles('bike')">Bike</button>
            </div>
            <div class="right-controls">
                <div class="total-display">Total : {{ $vehicles->count() }}</div>
                <button class="filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-chevron-left"></i> Filter
                </button>
            </div>
        </div>

        @if($vehicles->count() > 0)
            @foreach($vehicles as $vehicle)
                <div class="vehicle-card">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="vehicle-info">
                                <div class="vehicle-image">
                                    @if($vehicle->image_url)
                                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}">
                                    @else
                                        <i class="fas fa-car fa-3x"></i>
                                    @endif
                                </div>
                                <div class="vehicle-details">
                                    <h3>{{ $vehicle->name }}</h3>
                                    <div class="vehicle-plate">{{ $vehicle->plate_no }}</div>
                                    <div class="price-label">From</div>
                                    <div class="price">MYR {{ number_format($vehicle->price_perHour, 2) }}<span class="price-unit">/Hour</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end text-center">
                            <a href="{{ route('vehicles.show', $vehicle->plate_no) }}" class="view-btn">View</a>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($vehicles->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $vehicles->links() }}
                </div>
            @endif
        @else
            <div class="no-vehicles">
                <i class="fas fa-car-side"></i>
                <h3>No Vehicles Available</h3>
                <p>There are currently no vehicles matching your criteria. Please check back later.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function filterVehicles(type) {
        if (type === 'all') {
            window.location.href = "{{ route('vehicles.index') }}";
        } else {
            window.location.href = "{{ route('vehicles.index') }}?type=" + type;
        }
    }
</script>

@endsection