@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('noFooter', true)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Page layout */
    body { padding-top: 70px; }
    .page-wrap { padding: 20px 12px; max-width:880px; margin:0 auto; }

    /* Main card */
    .vehicle-card-main {
        position: relative;   
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        max-width: 820px;
        margin: 0 auto;
    }
    /* Back link (top-left) */
.back-arrow {
    position: absolute;
    top: 12px;
    left: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ffd6d6;
    color: #d93025;
    font-weight: 900;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 10px rgba(217,48,37,0.25);
    transition: all 0.2s ease;
    text-decoration: none;
    z-index: 10;
}

.back-arrow:hover {
    transform: translateX(-2px) scale(1.1);
    background: #ffcaca;
    box-shadow: 0 6px 14px rgba(217,48,37,0.35);
}
.back-arrow:active {
    transform: scale(0.95);
}
    .back-link .arrow {
        transition: transform 0.25s ease;
    }

    .back-link:hover .arrow {
        transform: translateX(-2px);
    }
    .vehicle-top {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 18px;
        align-items: start; /* align header to the top of the photo */
    }

    .vehicle-photo {
        width: 100%;
        max-width: 320px;
        aspect-ratio: 4 / 3;
        background: #f7f7f7;
        border-radius: 10px;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        padding: 8px;
    }

    .vehicle-photo img { width:100%; height:100%; object-fit:contain; }

    .vehicle-header {
        display:flex;
        flex-direction:column;
        gap:8px;
        align-items: flex-start; /* left-align header text */
        text-align: left;
    }

    .vehicle-title {
        font-size:28px; /* larger title */
        font-weight:700;
        color:#111;
        line-height:1.05;
    }

    .avail-badge {
        display: inline-flex;         
        align-items: center;
        width: fit-content;           
        padding: 0.45em 0.9em;         /* slightly larger padding */
        font-size: 1rem;              /* larger font */
        font-weight: 700;
        border-radius: 999px;       
        line-height: 1;
    }

    /* AVAILABLE */
    .avail-badge.available {
        background: #e8f7ee;
        color: #1e8e3e;
    }

    /* UNAVAILABLE */
    .avail-badge.unavailable {
        background: #fdecea;
        color: #d93025;
    }    
    
    .vehicle-meta {
        display:flex;
        gap:28px;
        margin-top:10px;
        color:#666;
        font-size:15px; /* larger meta text */
        align-items:flex-start;
    }

    .meta-item { display:flex; flex-direction:column; }
    .meta-item span:first-child { font-weight:600; color:#333; }
    .meta-item medium { color:#999; }

    .vehicle-stats { margin-top:12px; display:flex; gap:16px; color:#666; align-items:center; }
    .stat { display:flex; gap:8px; align-items:center; font-size:14px; }

    /* Gallery */
    .gallery-row { display:flex; gap:14px; align-items:center; justify-content:center; margin:18px 0; }
    .gallery-thumbs {
        display: flex;
        gap: 20px;
        transition: transform 0.3s ease;
    }   
    .gallery-thumb {
    width: 150px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    background: #fafafa;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; /* Prevents thumbnails from shrinking */
}
    .gallery-thumb img { width:100%; height:100%; object-fit:cover; }
    .gallery-window {
        width: calc(150px * 2 + 20px); /* Shows exactly 2 thumbnails */
        overflow: hidden;
    }    /* Place price beside the Book button and align them to the right side */
    .booking-row { display:flex; justify-content:flex-end; align-items:center; gap:12px; margin-top:16px; width:100%; }
    .booking-actions { display:flex; align-items:center; gap:12px; }
    .booking-price { font-size:22px; color:#d14545; font-weight:800; margin:0; }

    .gallery-nav {
        background: #ffd6d6;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        font-weight: 900;
        font-size: 16px;
        color: #d93025;
        border:none;
        transition: all 0.2s ease;
    }
    .gallery-nav:hover {
        transform: scale(1.1);
        background: #ffcaca;
    }

    .gallery-nav:active {
        transform: scale(0.92);
    }

    .view-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;          /* pill shape */
    background: #fff;
    color: #d93025;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    border: 1.5px solid #ffd6d6;
    transition: all 0.25s ease;
}

.view-all::after {
    transition: transform 0.25s ease;
}

.view-all:hover {
    background: #ffd6d6;
    box-shadow: 0 4px 12px rgba(217,48,37,0.25);
}

.view-all:hover::after {
    transform: translateX(4px);
}

.view-all:active {
    transform: scale(0.95);
}



    /* Responsive: on narrow screens stack price and button and align to right */
    @media (max-width:480px) {
        .booking-row { justify-content:flex-end; }
        .booking-row { flex-direction:column; align-items:flex-end; gap:8px; }
    }
    .btn-book { background:#d93025; color:#fff; border:none; padding:10px 18px; border-radius:8px; font-weight:700; cursor:pointer; }
    .btn-book:hover { background: #CB3737; }
    .btn-view-details {
    background: #d93025;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
    width: 100%;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-view-details:hover {
    background: #b71c1c;
    transform: translateY(-1px);
}

.btn-view-details:active {
    transform: scale(0.96);
}

    /* Other cars grid (match index look) */
    .other-section { margin-top:34px; }
    .other-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .other-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
    .other-card { background:#fff; border-radius:12px; padding:12px; box-shadow:0 1px 8px rgba(0,0,0,0.06); }
    .other-card img { width:100%; height:140px; object-fit:contain; border-radius:8px; }
    .other-card h4 { font-size:16px; margin:10px 0 6px; }
    .other-card .price { color:#e53935; font-weight:700; }
    .other-card .view { 
            background: #CB3737;
            color: #CB3737;
            border: none;
            padding: 6px;
            justify-content: right;
            font-size: 0.8rem;
            width: 100%;
            transition: all 0.2s; }
    .other-card .view-details:hover {
        background: #CB3737;
        color: #CB3737;
    }


    /* Responsive */
    @media (max-width:1000px) {
        .vehicle-top { grid-template-columns: 1fr; }
        .vehicle-photo { max-width:100%; aspect-ratio:16/9 }
        .other-grid { grid-template-columns: 1fr; }
    
    }
</style>

<div class="page-wrap">
    <div class="vehicle-card-main">
        <a href="{{ route('vehicles.index') }}" class="gallery-nav back-arrow" title="Back">
            ←
        </a>
        <div class="vehicle-top">
            <div class="vehicle-photo">
                {{-- image from DB if available otherwise placeholder --}}
                <img src="{{ asset('storage/' . $vehicle->display_image) }}" alt="{{ $vehicle->name }}">
            </div>

            <div class="vehicle-header">
                {{-- header uses same compact style as index: name, plate, price --}}
                <div class="vehicle-title">{{ $vehicle->name }}</div>
                <div class="avail-badge {{ $vehicle->availability_status === 'available' ? 'available' : 'unavailable' }}">
                    {{ ucfirst($vehicle->availability_status) }}
                </div>

                <div class="vehicle-meta">
                    <div class="meta-item">
                        <medium>PLATE NUMBER</medium>
                        <span>{{ $vehicle->plate_no }}</span>
                    </div>
                    <div class="meta-item">
                        <medium>COLOR</medium>
                        <span>{{ $vehicle->color }}</span>
                    </div>
                    <div class="meta-item">
                        <medium>YEAR</medium>
                        <span>{{ $vehicle->year }}</span>
                    </div>
                </div>

                <div class="vehicle-stats">
                    <div class="stat">Passengers: <strong style="margin-left:6px">{{ $vehicle->seating_capacity }}</strong></div>
                    <div class="stat">Distance Travelled (km):  <strong style="margin-left:6px">{{ $vehicle->distance_travelled }}</strong></div>
                </div>

                {{-- gallery thumbnails (placeholders) --}}
                <div class="gallery-row">
                    <button class="gallery-nav prev" onclick="prevThumbs()">❮</button>
                    <div class="gallery-window">
                        <div class="gallery-thumbs">
                            @foreach ($images as $image)
                                <div class="gallery-thumb">
                                    <img src="{{ asset('storage/' . $image) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="gallery-nav next" onclick="nextThumbs()">❯</button>
                </div>                
                <div class="booking-row">
                    <div class="booking-price">RM{{ number_format($vehicle->price_perHour) }} <medium style="font-size:12px; color:#666; font-weight:600">/hour</medium></div>
                    @if($vehicle->availability_status === 'available')
                        <a href="{{ route('bookings.create', $vehicle->plate_no) }}" class="btn-book" style="text-decoration: none; display: inline-block;">Book Now</a>
                    @else
                        <button class="btn-book" disabled style="opacity: 0.5; cursor: not-allowed;">Unavailable</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Other vehicles section: show DB items if available otherwise static placeholders --}}
    <div class="other-section">
        <div class="other-header">
            <h2 style="font-size:22px; margin:0;">Other cars</h2>
            <a href="{{ route('vehicles.index') }}" class="view-all">View All →</a>
        </div>

        <div class="other-grid">
            @php $others = collect($otherVehicles ?? [])->take(3); @endphp
            @if($others->isNotEmpty())
                @foreach($others as $other)
                    <div class="other-card">
                        <img src="{{ $other->image_url ?? asset('car_images/axia.jpg') }}" alt="{{ $other->name }}">
                        <h4>{{ $other->name ?? 'Vehicle' }}</h4>
                        <div class="price">RM{{ number_format($other->price_perHour) }} <medium style="font-size:11px;color:#888">/hour</medium></div>
                        <a href="{{ route('vehicles.show', $other->plate_no ?? '#') }}" class="btn-view-details">View Details</a>
                    </div>
                @endforeach
            @else
                {{-- three static placeholders matching index look --}}
                @for($i=0;$i<3;$i++)
                    <div class="other-card">
                        <img src="{{ asset('car_images/axia.jpg') }}" alt="Placeholder">
                        <h4>Perodua Bezza 2018</h4>
                        <div class="price">RM260 <medium style="font-size:11px;color:#888">per hour</medium></div>
                        <a href="{{ route('vehicles.show', $other->plate_no ?? '#') }}" class="view">View Details</a>
                    </div>
                @endfor
            @endif
        </div>
    </div>
</div>
<script>
let currentIndex = 0;
const visibleCount = 2; // Number of visible thumbnails

function nextThumbs() {
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const maxIndex = thumbs.length - visibleCount;

    if (currentIndex < maxIndex) {
        currentIndex += 1; // Move by 1 to show next set smoothly
        updateGallery();
    }
}

function prevThumbs() {
    if (currentIndex > 0) {
        currentIndex -= 1; // Move by 1 to show previous set smoothly
        updateGallery();
    }
}

function updateGallery() {
    const thumbWidth = 150 + 20; // thumb width + gap
    const gallery = document.querySelector('.gallery-thumbs');
    gallery.style.transform = `translateX(-${currentIndex * thumbWidth}px)`;
}
</script>
@endsection
