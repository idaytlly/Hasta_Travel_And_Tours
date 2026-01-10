@extends('layouts.app')

@section('title', 'Vehicles')

@section('noFooter', true)

@section('content')
<style>
    /* Page-specific styles copied from previous standalone file */
    body {
        padding-top: 70px; /* match layout spacing */
    }

    .main-content {
        padding: 24px 0;
        min-height: calc(100vh - 90px - 300px);
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
    .category-btn { padding:8px 18px; border-radius:20px; font-weight:600; background:#e53935; color:#fff; border:none; font-size:0.95rem }
    .category-btn.active { background:#c62828; }

    .right-controls { display:flex; gap:15px; align-items:center; }
    .total-display { padding:8px 18px; border:2px solid #e53935; border-radius:20px; color:#e53935; font-weight:600; background:#fff; font-size:0.95rem }
    .filter-btn { padding:8px 14px; border-radius:20px; background:#e53935; color:#fff; border:none; font-size:0.95rem }

    /* Make cards a bit more compact and let image sit outside the card (not cropped) */
    /* Keep card sizes compact and ensure all images use the same fixed container so they're visually consistent */
    .vehicle-card { background:#fff; border-radius:12px; padding:14px; margin-bottom:14px; box-shadow:0 1px 8px rgba(0,0,0,0.06); }
    .vehicle-info { display:flex; gap:18px; align-items:center; }
    /* Fixed image container — same size for every vehicle */
    .vehicle-image { width:140px; height:100px; border-radius:8px; display:flex; align-items:center; justify-content:center; background:transparent; overflow:visible; }
    .vehicle-image img { width:140px; height:100px; object-fit:contain; display:block; margin:0; }

    /* Pagination (previous/next) sizing - make arrows smaller and balanced */
    .pagination .page-link,
    .pagination li a,
    .pagination li span {
        font-size: 0.9rem;
        padding: 6px 10px;
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    /* Reduce the chevron/icon size if present */
    .pagination .page-link svg,
    .pagination .page-link i {
        width: 14px;
        height: 14px;
    }
    /* Ensure any icon inside pagination stays small */
    .pagination i, .pagination .fa, .pagination svg { font-size: 14px !important; width: auto !important; height: auto !important; }
    .vehicle-details h3 { font-size:18px; margin-bottom:6px; }
    .vehicle-plate { color:#6c757d; font-size:0.92rem }
    .price { color:#e53935; font-size:18px; font-weight:700; }
    .price-unit { color:#e53935; font-size:12px; font-weight:700; }
    .view-btn { padding:8px 18px; border-radius:20px; background:#e53935; color:#fff; text-decoration:none; font-size:0.95rem }

    .no-vehicles { text-align:center; padding:60px 20px; background:#fff; border-radius:15px; }

    /* Pagination / arrows — make previous/next smaller and less dominant */
    .pagination .page-link {
        padding: .25rem .5rem;
        font-size: 0.9rem;
    }

    

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
                <div class="total-display">Total : {{ $vehicles->total() }}</div>
                    <div class="dropdown">
                    <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter"></i> Filter
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ request('status') == 'available' ? 'active' : '' }}"
                            href="{{ route('vehicles.index', array_merge(request()->query(), ['status' => 'available'])) }}">
                                Available
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item {{ request('status') == 'unavailable' ? 'active' : '' }}"
                            href="{{ route('vehicles.index', array_merge(request()->query(), ['status' => 'unavailable'])) }}">
                                Unavailable
                            </a>
                        </li>

                        @if(request('status'))
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger"
                                href="{{ route('vehicles.index', request()->except('status')) }}">
                                    Clear Status Filter
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
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
                    {{ $vehicles->appends(request()->query())->links() }}
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