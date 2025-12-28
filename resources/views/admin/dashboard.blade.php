<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-20 bg-gradient-to-b from-red-500 to-red-600 flex flex-col items-center py-6 space-y-8">
            <!-- Logo -->
            <div class="bg-white px-3 py-1 rounded-md">
                <span class="text-red-500 font-bold text-sm">HASTA</span>
            </div>
            
            <!-- Navigation Icons -->
            <nav class="flex flex-col space-y-6 flex-1">
                <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                    <i class="fas fa-home text-xl"></i>
                </a>
                <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                    <i class="fas fa-bell text-xl"></i>
                </a>
                <a href="#" class="bg-red-700 text-white p-3 rounded-lg">
                    <i class="fas fa-th text-xl"></i>
                </a>
                <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                    <i class="fas fa-car text-xl"></i>
                </a>
                <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                    <i class="fas fa-file-alt text-xl"></i>
                </a>
                <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                    <i class="fas fa-clock text-xl"></i>
                </a>
            </nav>
            
            <!-- Settings Icon -->
            <a href="#" class="text-white hover:bg-red-700 p-3 rounded-lg transition">
                <i class="fas fa-cog text-xl"></i>
            </a>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" placeholder="Search" class="w-full bg-gray-100 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <img src="https://ui-avatars.com/api/?name=Profile&background=3b82f6&color=fff" alt="Profile" class="w-10 h-10 rounded-full">
                        <span class="text-sm font-medium text-red-500">Profile</span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
                <!-- Top Buttons -->
                <div class="flex justify-end space-x-3 mb-6">
                    <button class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition text-sm">
                        Export Data
                    </button>
                    <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                        Create Report
                    </button>
                </div>

                <!-- Top Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Revenue Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Revenue Generated</p>
                                <h3 class="text-2xl font-bold text-gray-800">RM 45,780.00</h3>
                                <p class="text-green-500 text-sm mt-1">+42%</p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-money-bill-wave text-2xl text-gray-700"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Users Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Today's Users</p>
                                <h3 class="text-2xl font-bold text-gray-800">18</h3>
                                <p class="text-green-500 text-sm mt-1">+6%</p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-users text-2xl text-gray-700"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Total Sales</p>
                                <h3 class="text-2xl font-bold text-gray-800">214</h3>
                                <p class="text-green-500 text-sm mt-1">+8%</p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-shopping-cart text-2xl text-gray-700"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart - Full Width -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <i class="far fa-calendar text-blue-500"></i>
                                <span class="text-sm text-gray-500">This month</span>
                            </div>
                            <h3 class="text-2xl font-bold text-red-500">RM45 780.00</h3>
                            <p class="text-sm text-gray-500 mt-1">Total Increase in Revenue</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-green-600">On track</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-red-500 text-sm font-medium">-24.8%</span>
                            <i class="fas fa-chart-line text-red-500 ml-2"></i>
                        </div>
                    </div>
                    <div style="height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Donut Chart - Left aligned with legend on right -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-800">Book vs Cancel</h4>
                        <span class="text-sm text-gray-500">Today</span>
                    </div>
                    <div class="flex items-center justify-start gap-12">
                        <div style="width: 200px; height: 200px;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 w-32">Total Booked</span>
                                <span class="text-sm font-semibold flex items-center">
                                    54%
                                    <span class="text-green-500 text-xs ml-2">↑</span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 w-32">Total Canceled</span>
                                <span class="text-sm font-semibold flex items-center">
                                    20%
                                    <span class="text-green-500 text-xs ml-2">↑</span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 w-32">Total Pending</span>
                                <span class="text-sm font-semibold flex items-center">
                                    26%
                                    <span class="text-red-500 text-xs ml-2">↓</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Overview -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-xl font-bold text-gray-800">Reports Overview</h2>
                            <button class="px-4 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition text-sm">
                                Export Data
                            </button>
                            <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                Create Report
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Vehicle Utilization Table -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-800">Vehicle Utilization</h3>
                                <div class="flex items-center space-x-2">
                                    <button class="px-3 py-1 border border-gray-300 text-gray-600 rounded text-sm hover:bg-gray-50">
                                        Export
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500 border-b">
                                            <th class="pb-3 font-medium">NAME</th>
                                            <th class="pb-3 font-medium">PLAT NUMBER</th>
                                            <th class="pb-3 font-medium">CONDITION</th>
                                            <th class="pb-3 font-medium">DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3">
                                                <input type="checkbox" class="mr-3 rounded">
                                                <span>Perodua Axia 2018</span>
                                            </td>
                                            <td class="py-3 text-gray-600">MCP 6113</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs font-medium">GOOD</span>
                                            </td>
                                            <td class="py-3 text-gray-600">24.Jan.2021</td>
                                        </tr>
                                        <tr class="border-b hover:bg-gray-50 bg-red-50">
                                            <td class="py-3">
                                                <input type="checkbox" checked class="mr-3 rounded accent-red-500">
                                                <span>Perodua Myvi 2015</span>
                                            </td>
                                            <td class="py-3 text-gray-600">NDE 5566</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs font-medium">GOOD</span>
                                            </td>
                                            <td class="py-3 text-gray-600">12.Jun.2021</td>
                                        </tr>
                                        <tr class="border-b hover:bg-gray-50 bg-red-50">
                                            <td class="py-3">
                                                <input type="checkbox" checked class="mr-3 rounded accent-red-500">
                                                <span>Perodua Myvi 2020</span>
                                            </td>
                                            <td class="py-3 text-gray-600">WYP 8789</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">BAD</span>
                                            </td>
                                            <td class="py-3 text-gray-600">5.Jan.2021</td>
                                        </tr>
                                        <tr class="border-b hover:bg-gray-50 bg-red-50">
                                            <td class="py-3">
                                                <input type="checkbox" checked class="mr-3 rounded accent-red-500">
                                                <span>Perodua Axia 2024</span>
                                            </td>
                                            <td class="py-3 text-gray-600">JLV 7862</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs font-medium">GOOD</span>
                                            </td>
                                            <td class="py-3 text-gray-600">7.Mar.2021</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3">
                                                <input type="checkbox" class="mr-3 rounded">
                                                <span>Proton Saga 2017</span>
                                            </td>
                                            <td class="py-3 text-gray-600">MCP 1174</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs font-medium">GOOD</span>
                                            </td>
                                            <td class="py-3 text-gray-600">17.Dec.2021</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Users by States -->
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-4">Users by States</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-700">Johor Bahru</span>
                                        <span class="text-sm font-semibold text-gray-700">74%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-orange-400 to-red-500 h-2 rounded-full" style="width: 74%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-700">Melaka</span>
                                        <span class="text-sm font-semibold text-gray-700">52%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-orange-400 to-orange-300 h-2 rounded-full" style="width: 52%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-700">Kuala Lumpur</span>
                                        <span class="text-sm font-semibold text-gray-700">36%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-orange-400 to-red-300 h-2 rounded-full" style="width: 36%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Revenue Chart - Optimized
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['SEP', 'OCT', 'NOV', 'DEC', 'JAN', 'FEB'],
                datasets: [{
                    label: 'Revenue',
                    data: [35000, 32000, 38000, 45780, 42000, 44000],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 2
                }, {
                    label: 'Comparison',
                    data: [30000, 28000, 33000, 35000, 37000, 39000],
                    borderColor: '#fb923c',
                    backgroundColor: 'rgba(251, 146, 60, 0.1)',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 0,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 750
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'RM' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Donut Chart - Optimized
        const donutCtx = document.getElementById('donutChart').getContext('2d');
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Booked', 'Canceled', 'Pending'],
                datasets: [{
                    data: [54, 20, 26],
                    backgroundColor: ['#3b82f6', '#22c55e', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                animation: {
                    duration: 750
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>