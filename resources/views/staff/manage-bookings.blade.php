<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA Booking Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-32 bg-gradient-to-b from-red-500 to-red-600 flex flex-col items-center py-6">
            <!-- Logo -->
            <div class="bg-white px-3 py-1 rounded-md mb-4 flex-shrink-0">
                <span class="text-red-500 font-bold text-sm">HASTA</span>
            </div>
            
            <!-- Navigation Icons -->
            <nav class="flex flex-col space-y-3 w-full">
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-home text-xl mb-1"></i>
                    <span class="text-xs">Home</span>
                </a>
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-bell text-xl mb-1"></i>
                    <span class="text-xs">Notifications</span>
                </a>
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-th text-xl mb-1"></i>
                    <span class="text-xs">Dashboard</span>
                </a>
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-car text-xl mb-1"></i>
                    <span class="text-xs text-center leading-tight">Vehicle<br>Management</span>
                </a>
                <a href="#" class="flex flex-col items-center bg-red-700 text-white py-2">
                    <i class="fas fa-file-alt text-xl mb-1"></i>
                    <span class="text-xs text-center leading-tight">Booking<br>Management</span>
                </a>
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-clock text-xl mb-1"></i>
                    <span class="text-xs">History</span>
                </a>
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-user text-xl mb-1"></i>
                    <span class="text-xs">Profile</span>
                </a>
                <a href="#" class="flex flex-col items-center text-white hover:bg-red-700 py-2 transition">
                    <i class="fas fa-cog text-xl mb-1"></i>
                    <span class="text-xs">Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header with Search -->
            <header class="bg-gradient-to-r from-red-500 to-red-600 px-8 py-4">
                <div class="max-w-md">
                    <div class="relative">
                        <input type="text" placeholder="Search" class="w-full bg-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-red-300">
                        <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Booking Management</h1>

                <!-- Tabs and Filter -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex space-x-8">
                        <button class="text-red-500 font-semibold border-b-2 border-red-500 pb-2">
                            All Order
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 font-semibold pb-2 transition">
                            Pending
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 font-semibold pb-2 transition">
                            Approved
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 font-semibold pb-2 transition">
                            Rejected
                        </button>
                    </div>
                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg transition flex items-center space-x-2">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                    <div class="flex items-center space-x-1">
                                        <span>Id</span>
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- ROW TEMPLATE -->
                            <!-- Copy-paste siap semua row -->

                            <!-- C001 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C001</td>
                                <td class="px-6 py-4">14-03-2025</td>
                                <td class="px-6 py-4">Ali Bin Abu</td>
                                <td class="px-6 py-4 text-green-500 font-semibold">Approved</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C002 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C002</td>
                                <td class="px-6 py-4">20-03-2025</td>
                                <td class="px-6 py-4">Clarence Wilson</td>
                                <td class="px-6 py-4 text-green-500 font-semibold">Approved</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C003 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C003</td>
                                <td class="px-6 py-4">24-06-2025</td>
                                <td class="px-6 py-4">Alex Rose</td>
                                <td class="px-6 py-4 text-red-500 font-semibold">Rejected</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C004 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C004</td>
                                <td class="px-6 py-4">20-07-2025</td>
                                <td class="px-6 py-4">Yang Jungwon</td>
                                <td class="px-6 py-4 text-green-500 font-semibold">Approved</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C005 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C005</td>
                                <td class="px-6 py-4">25-08-2025</td>
                                <td class="px-6 py-4">Iman Nadhirah</td>
                                <td class="px-6 py-4 text-yellow-500 font-semibold">Pending</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C006 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C006</td>
                                <td class="px-6 py-4">01-09-2025</td>
                                <td class="px-6 py-4">Muhammad Omar</td>
                                <td class="px-6 py-4 text-yellow-500 font-semibold">Pending</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C007 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C007</td>
                                <td class="px-6 py-4">03-09-2025</td>
                                <td class="px-6 py-4">Lee Heeseung</td>
                                <td class="px-6 py-4 text-yellow-500 font-semibold">Pending</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C008 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C008</td>
                                <td class="px-6 py-4">24-09-2025</td>
                                <td class="px-6 py-4">Jennifer Anniston</td>
                                <td class="px-6 py-4 text-yellow-500 font-semibold">Pending</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- C009 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">C009</td>
                                <td class="px-6 py-4">25-10-2025</td>
                                <td class="px-6 py-4">Ben Thomas</td>
                                <td class="px-6 py-4 text-red-500 font-semibold">Rejected</td>
                                <td class="px-6 py-4 relative">
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-blue-500">View</a>
                                        <div class="relative">
                                            <button onclick="toggleDropdown(this)" class="font-bold px-2">:</button>
                                            <div class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow z-50">
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Update Status</a>
                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Request Reupload</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <script>
    function toggleDropdown(btn) {
        const menu = btn.nextElementSibling;
        document.querySelectorAll('td .absolute').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
        });
        menu.classList.toggle('hidden');
    }
    </script>
</body>
</html>