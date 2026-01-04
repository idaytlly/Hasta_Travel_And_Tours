<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-20 bg-gradient-to-b from-red-500 to-red-600 flex flex-col items-center py-6 space-y-8 shadow-xl">
            <div class="bg-white px-3 py-1 rounded-md">
                <span class="text-red-500 font-bold text-sm">HASTA</span>
            </div>
            
            <nav class="flex flex-col space-y-6 flex-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-700' : '' }} text-white p-3 rounded-lg transition"
                   title="Dashboard">
                    <i class="fas fa-th text-xl"></i>
                </a>

                <a href="{{ route('admin.bookings.index') }}" 
                   class="{{ request()->routeIs('admin.bookings*') ? 'bg-red-700' : '' }} text-white p-3 rounded-lg transition"
                   title="Booking Management">
                    <i class="fas fa-edit text-xl"></i>
                </a>

                <a href="{{ route('admin.cars.index') }}" 
                   class="{{ request()->routeIs('admin.cars*') ? 'bg-red-700' : '' }} text-white p-3 rounded-lg transition" 
                   title="Vehicles">
                    <i class="fas fa-car text-xl"></i>
                </a>

                <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition" title="Users">
                    <i class="fas fa-users text-xl"></i>
                </a>
            </nav>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </button>
            </form>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" placeholder="Search" class="w-full bg-gray-100 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-red-500 capitalize">{{ Auth::user()->usertype }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ef4444&color=fff" alt="Profile" class="w-10 h-10 rounded-full border-2 border-red-100">
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-gray-500 text-sm mb-2">Revenue Generated</p>
                        <h3 class="text-2xl font-bold text-gray-800">RM {{ number_format($revenue ?? 0, 2) }}</h3>
                    </div>
                    <a href="{{ route('admin.bookings.index', ['status' => 'Pending']) }}" class="bg-white rounded-xl shadow-sm p-6 hover:ring-2 hover:ring-red-500 transition">
                        <p class="text-gray-500 text-sm mb-2">Pending Orders</p>
                        <h3 class="text-2xl font-bold text-red-500">{{ $pendingCount ?? 0 }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Click to review</p>
                    </a>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-gray-500 text-sm mb-2">Total Sales</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalSales ?? 0 }}</h3>
                    </div>
                </div>

                <a href="{{ route('admin.commissions.index') }}" class="nav-link">
                    <i class="fas fa-hand-holding-usd"></i> Commission Management
                </a>

                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-xl font-bold text-red-500 mb-4">Revenue Overview</h3>
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Recent Vehicle Status</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-gray-500">
                                <th class="pb-3 uppercase">Vehicle</th>
                                <th class="pb-3 uppercase">Plate</th>
                                <th class="pb-3 uppercase text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs ?? [] as $log)
                            <tr class="border-b">
                                <td class="py-3 font-medium">{{ $log->model_name }}</td>
                                <td class="py-3 text-gray-600">{{ $log->plate_number }}</td>
                                <td class="py-3 text-right">
                                    <span class="px-2 py-1 rounded text-xs {{ $log->status == 'available' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ strtoupper($log->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-400">No recent logs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['SEP', 'OCT', 'NOV', 'DEC', 'JAN', 'FEB'],
                datasets: [{
                    label: 'Revenue (RM)',
                    data: [35000, 32000, 38000, 45780, 42000, 44000],
                    borderColor: '#ef4444',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(239, 68, 68, 0.05)'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</body>
</html>