<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Our Vehicles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <header class="bg-[#db3c39] px-10 py-4 flex items-center justify-between shadow-lg">
        <div class="bg-white px-4 py-1 rounded">
            <span class="text-[#db3c39] font-black text-xl">HASTA</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('home') }}" class="p-2 bg-white/20 rounded-lg text-white"><i class="fas fa-home"></i></a>
            <a href="#" class="p-2 bg-white/20 rounded-lg text-white"><i class="fas fa-bell"></i></a>
            <a href="#" class="p-2 bg-white/20 rounded-lg text-white"><i class="fas fa-th-large"></i></a>
            <a href="{{ route('cars.index') }}" class="p-2 bg-white/20 rounded-lg text-white"><i class="fas fa-car"></i></a>
            <a href="{{ route('bookings.my-bookings') }}" class="p-2 bg-white/20 rounded-lg text-white"><i class="fas fa-history"></i></a>
            <a href="#" class="p-2 bg-white/20 rounded-lg text-white"><i class="fas fa-cog"></i></a>
        </div>
    </header>

    <div class="flex justify-center gap-6 py-10">
        @php
            $brandLogos = [
                // Using high-availability CDN links or direct public SVGs
               'toyota' => 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Toyota_EU.svg',
                'hyundai' => 'https://upload.wikimedia.org/wikipedia/commons/4/44/Hyundai_Motor_Company_logo.svg',
                'proton' => 'https://logos-world.net/wp-content/uploads/2022/12/Proton-Logo-500x281.png',
                'perodua' => 'https://cdn.worldvectorlogo.com/logos/perodua.svg'
            ];
        @endphp

        @foreach($brandLogos as $name => $url)
        <a href="{{ route('cars.index', ['brand' => $name]) }}" 
           class="bg-white border rounded-2xl p-4 w-28 h-28 flex items-center justify-center shadow-sm hover:border-[#db3c39] hover:shadow-md transition-all">
            <img src="{{ $url }}" alt="{{ ucfirst($name) }}" class="max-h-full w-auto object-contain">
        </a>
        @endforeach
    </div>

    <div class="max-w-7xl mx-auto px-10 mb-10 flex items-center justify-between">
        <div class="flex gap-4">
            <a href="{{ route('cars.index') }}" class="bg-[#f27041] text-white px-6 py-2 rounded-full font-bold flex items-center gap-2">
                <i class="fas fa-car"></i> All vehicles
            </a>
            <a href="{{ route('cars.index', ['category' => 'Sedan']) }}" class="bg-white border text-gray-700 px-6 py-2 rounded-full font-bold flex items-center gap-2 hover:bg-gray-50">
                <i class="fas fa-car-side"></i> Sedan
            </a>
            <a href="{{ route('cars.index', ['category' => 'Hatchback']) }}" class="bg-white border text-gray-700 px-6 py-2 rounded-full font-bold flex items-center gap-2 hover:bg-gray-50">
                <i class="fas fa-truck-pickup"></i> Hatchback
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-10 grid grid-cols-1 md:grid-cols-3 gap-10 pb-20">
        @forelse($cars as $car)
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col">
                    <div class="h-56 bg-gray-50 p-6 flex items-center justify-center">
            <img src="{{ $car->image }}" 
                alt="{{ $car->brand }} {{ $car->model }}" 
                class="max-w-full max-h-full object-contain">
        </div>
            
            <div class="p-8">
                <h3 class="text-2xl font-black text-gray-900 leading-tight">
                    {{ $car->brand }} {{ $car->model }} {{ $car->year }}
                </h3>
                <p class="text-gray-400 font-medium mb-4">{{ $car->transmission }}</p>
                
                <div class="flex items-baseline gap-1 mb-6">
                    <span class="text-[#db3c39] font-black text-3xl">RM{{ number_format($car->daily_rate, 0) }}</span>
                    <span class="text-gray-400 font-bold">per day</span>
                </div>

                <div class="flex items-center gap-4 text-[10px] font-bold text-gray-500 mb-8 uppercase tracking-wider">
                    <span class="flex items-center gap-1"><i class="fas fa-cog text-[#f27041]"></i> {{ $car->transmission }}</span>
                    <span class="flex items-center gap-1"><i class="fas fa-gas-pump text-[#f27041]"></i> {{ $car->fuel_type }}</span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-snowflake text-[#f27041]"></i> 
                        {{ $car->air_conditioner ? 'AC' : 'No AC' }}
                    </span>
                </div>

                <a href="{{ route('cars.show', $car->id) }}" 
                   class="block w-full text-center bg-[#f27041] text-white py-4 rounded-2xl font-black text-lg hover:bg-[#e66030] shadow-lg shadow-orange-100 transition-all">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-20">
            <p class="text-gray-500 text-xl font-bold">No vehicles found in this category.</p>
        </div>
        @endforelse
    </div>

</body>
</html>