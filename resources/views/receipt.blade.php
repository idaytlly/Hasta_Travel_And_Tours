<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA Payment Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="bg-white px-4 py-2 rounded-md">
                <span class="text-red-500 font-bold text-lg">HASTA</span>
            </div>

            <!-- Navigation Icons -->
            <div class="flex items-center space-x-6">
                <a href="#" class="text-red-300 hover:text-white transition">
                    <i class="fas fa-home text-2xl"></i>
                </a>
                <a href="#" class="text-red-300 hover:text-white transition">
                    <i class="fas fa-bell text-2xl"></i>
                </a>
                <a href="#" class="text-red-300 hover:text-white transition">
                    <i class="fas fa-th text-2xl"></i>
                </a>
                <a href="#" class="bg-red-400 bg-opacity-40 text-white px-6 py-3 rounded-lg">
                    <i class="fas fa-car text-xl"></i>
                </a>
                <a href="#" class="text-red-300 hover:text-white transition">
                    <i class="fas fa-history text-2xl"></i>
                </a>
                <a href="#" class="text-red-300 hover:text-white transition">
                    <i class="fas fa-cog text-2xl"></i>
                </a>
            </div>

            <!-- Login and Profile -->
            <div class="flex items-center space-x-4">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Login
                </button>
                <img src="https://ui-avatars.com/api/?name=Profile&background=3b82f6&color=fff" alt="Profile" class="w-12 h-12 rounded-full border-2 border-white">
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 px-6 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <button class="w-12 h-12 bg-black rounded-full flex items-center justify-center hover:bg-gray-800 transition mb-6">
                <i class="fas fa-chevron-left text-white text-lg"></i>
            </button>

            <!-- Receipt Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-8">
                    Thank you for the payment!
                </h1>

                <!-- Receipt ID and Date -->
                <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-200">
                    <div>
                        <span class="text-gray-500">Receipt ID:</span>
                        <span class="ml-2 font-semibold text-gray-800">BK001</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Date:</span>
                        <span class="ml-2 font-semibold text-gray-800">14-03-2025</span>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="space-y-4 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Full Name</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">Ali Bin Abu</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Identity Card Number</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">010203-04-5678</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Phone Number</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">+6012 - 3456789</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Email Address</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">aliabu@graduate.utm.my</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start md:col-span-2">
                            <span class="text-gray-500 md:w-48">Address</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">MA1, Kolej Tun Dr. Ismail, UTM</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Destination</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">TERMINAL BUS LARKIN</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Pickup Location</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">STUDENT MALL UTM</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Return Location</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">STUDENT MALL UTM</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Pickup Date & Time</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">20 MAY 2025, 10:00 AM</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Return Date & Time</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">22 MAY 2025, 10:00 AM</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Rental Duration (Days)</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">2</span>
                        </div>
                        
                        <div class="flex justify-between md:justify-start">
                            <span class="text-gray-500 md:w-48">Car</span>
                            <span class="font-semibold text-gray-800 text-right md:text-left">PERODUA AXIA 2018 (HATCHBACK)</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Price (RM)</span>
                            <span class="font-semibold text-gray-800">240</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Deposit</span>
                            <span class="font-semibold text-gray-800">100</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Voucher</span>
                            <span class="font-semibold text-gray-800">0</span>
                        </div>
                    </div>
                </div>

                <!-- Total Payment -->
                <div class="border-t-2 border-gray-300 pt-6 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total Payment (RM) :</span>
                        <span class="text-3xl font-bold text-gray-800">340</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button class="px-12 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold rounded-lg hover:from-red-600 hover:to-orange-600 transition shadow-lg">
                        Share
                    </button>
                    <button class="px-12 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold rounded-lg hover:from-red-600 hover:to-orange-600 transition shadow-lg">
                        Download
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-red-500 to-red-600 text-white py-12 mt-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Logo -->
                <div>
                    <div class="bg-white px-4 py-2 rounded-md inline-block mb-6">
                        <span class="text-red-500 font-bold text-xl">HASTA</span>
                    </div>
                    <!-- Social Media Icons -->
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-black bg-opacity-30 rounded-full flex items-center justify-center hover:bg-opacity-50 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-black bg-opacity-30 rounded-full flex items-center justify-center hover:bg-opacity-50 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-black bg-opacity-30 rounded-full flex items-center justify-center hover:bg-opacity-50 transition">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-black bg-opacity-30 rounded-full flex items-center justify-center hover:bg-opacity-50 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <div class="flex items-start space-x-3 mb-3">
                        <div class="w-10 h-10 bg-orange-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Address</h4>
                            <p class="text-sm">Student Mall UTM</p>
                            <p class="text-sm">Skudai, 81300, Johor Bahru</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 mb-3">
                        <div class="w-10 h-10 bg-orange-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Email</h4>
                            <p class="text-sm">hastatravel@gmail.com</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-orange-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">Phone</h4>
                            <p class="text-sm">011-1090 0700</p>
                        </div>
                    </div>
                </div>

                <!-- Useful Links -->
                <div>
                    <h4 class="font-bold mb-4">Useful links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:underline">About us</a></li>
                        <li><a href="#" class="hover:underline">Contact us</a></li>
                        <li><a href="#" class="hover:underline">Gallery</a></li>
                        <li><a href="#" class="hover:underline">Blog</a></li>
                        <li><a href="#" class="hover:underline">F.A.Q</a></li>
                    </ul>
                </div>

                <!-- Vehicles -->
                <div>
                    <h4 class="font-bold mb-4">Vehicles</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:underline">Sedan</a></li>
                        <li><a href="#" class="hover:underline">Hatchback</a></li>
                        <li><a href="#" class="hover:underline">MPV</a></li>
                        <li><a href="#" class="hover:underline">Minivan</a></li>
                        <li><a href="#" class="hover:underline">SUV</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>