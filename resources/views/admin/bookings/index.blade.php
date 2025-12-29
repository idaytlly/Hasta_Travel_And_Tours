<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management - HASTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom scrollbar for a cleaner look */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ef4444; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#fdfdfd] font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-24 bg-[#db3c39] flex flex-col items-center py-8 space-y-10 shadow-2xl z-10">
            <div class="bg-white px-2 py-1 rounded shadow-md mb-4">
                <span class="text-[#db3c39] font-black text-xs tracking-tighter">HASTA</span>
            </div>
            
            <nav class="flex flex-col space-y-8 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fas fa-home text-xl"></i>
                    <span class="text-[10px] font-bold">Home</span>
                </a>
                <a href="#" class="text-white/70 hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="text-[10px] font-bold">Notifications</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fas fa-th-large text-xl"></i>
                    <span class="text-[10px] font-bold">Dashboard</span>
                </a>
                <a href="{{ route('admin.cars.index') }}" class="text-white/70 hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fas fa-car text-xl"></i>
                    <span class="text-[10px] font-bold text-center leading-tight">Vehicle Management</span>
                </a>
                <div class="bg-white/20 p-3 rounded-2xl flex flex-col items-center gap-1 border border-white/10">
                    <i class="fas fa-file-alt text-xl text-white"></i>
                    <span class="text-[10px] font-black text-white text-center leading-tight">Booking Management</span>
                </div>
                <a href="#" class="text-white/70 hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fas fa-history text-xl"></i>
                    <span class="text-[10px] font-bold">History</span>
                </a>
                <a href="#" class="text-white/70 hover:text-white transition-colors flex flex-col items-center gap-1">
                    <i class="fas fa-cog text-xl"></i>
                    <span class="text-[10px] font-bold">Settings</span>
                </a>
            </nav>
            
            <div class="flex flex-col items-center gap-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fecaca&color=7f1d1d" alt="Profile" class="w-12 h-12 rounded-full border-2 border-white/50 object-cover">
                <span class="text-white text-[10px] font-bold">Profile</span>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="bg-[#db3c39] px-10 py-5">
                <div class="relative max-w-sm">
                    <input type="text" placeholder="Search" class="w-full bg-[#f1f5f9]/20 text-white placeholder-white/80 rounded-2xl py-2.5 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-white/30 transition-all">
                    <i class="fas fa-search absolute left-4 top-3.5 text-white/80"></i>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#db3c39] px-6 pb-6">
                <div class="bg-white rounded-[40px] shadow-sm p-12 min-h-full">
                    
                    <div class="flex justify-between items-start mb-10">
                        <h1 class="text-4xl font-black text-gray-900 tracking-tight">Booking Management</h1>
                        <button class="bg-[#f27041] text-white px-6 py-2.5 rounded-2xl font-black flex items-center gap-2 hover:bg-[#e66030] shadow-lg shadow-orange-200 transition-all">
                            <i class="fas fa-chevron-left text-xs"></i> Filter
                        </button>
                    </div>

                    <div class="flex space-x-12 border-b border-gray-100 mb-10">
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="pb-5 font-black text-xl {{ !request('status') ? 'text-[#f27041] border-b-[5px] border-[#f27041]' : 'text-gray-300 hover:text-gray-500' }}">
                            All Order
                        </a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'Pending']) }}" 
                           class="pb-5 font-black text-xl {{ request('status') == 'Pending' ? 'text-[#f27041] border-b-[5px] border-[#f27041]' : 'text-gray-300 hover:text-gray-500' }}">
                            Pending
                        </a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'Approved']) }}" 
                           class="pb-5 font-black text-xl {{ request('status') == 'Approved' ? 'text-[#f27041] border-b-[5px] border-[#f27041]' : 'text-gray-300 hover:text-gray-500' }}">
                            Approved
                        </a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'Rejected']) }}" 
                           class="pb-5 font-black text-xl {{ request('status') == 'Rejected' ? 'text-[#f27041] border-b-[5px] border-[#f27041]' : 'text-gray-300 hover:text-gray-500' }}">
                            Rejected
                        </a>
                    </div>

                    <div class="w-full">
                        <table class="w-full border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-left text-gray-900">
                                    <th class="px-8 pb-4 font-black text-xl">Id <i class="fas fa-chevron-down text-sm ml-1 text-gray-400"></i></th>
                                    <th class="px-8 pb-4 font-black text-xl">Date</th>
                                    <th class="px-8 pb-4 font-black text-xl">Name</th>
                                    <th class="px-8 pb-4 font-black text-xl">Status</th>
                                    <th class="px-8 pb-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                                    <td class="px-8 py-6 rounded-l-3xl text-gray-700 font-bold text-lg">C{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-8 py-6 text-gray-700 font-bold text-lg">{{ $booking->created_at->format('d-m-Y') }}</td>
                                    <td class="px-8 py-6 text-gray-700 font-bold text-lg">{{ $booking->user->name ?? 'Guest User' }}</td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusColor = match($booking->status) {
                                                'Approved' => 'text-green-500',
                                                'Rejected' => 'text-red-500',
                                                'Pending' => 'text-[#f27041]',
                                                default => 'text-gray-500'
                                            };
                                        @endphp
                                        <span class="font-bold text-lg {{ $statusColor }}">{{ $booking->status }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right rounded-r-3xl">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-500 font-black text-lg hover:underline decoration-2 underline-offset-4">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-32 text-center text-gray-300 font-black text-2xl italic">No Records Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>