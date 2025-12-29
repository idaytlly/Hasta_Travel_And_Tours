<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA Payment</title>
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
            <div class="flex items-center space-x-8">
                <a href="#" class="text-white text-center hover:opacity-80 transition">
                    <i class="fas fa-home text-xl block mb-1"></i>
                    <span class="text-xs">Home</span>
                </a>
                <a href="#" class="text-white text-center hover:opacity-80 transition">
                    <i class="fas fa-bell text-xl block mb-1"></i>
                    <span class="text-xs">Notifications</span>
                </a>
                <a href="#" class="text-white text-center hover:opacity-80 transition">
                    <i class="fas fa-th text-xl block mb-1"></i>
                    <span class="text-xs">Dashboard</span>
                </a>
                <a href="#" class="bg-red-400 bg-opacity-40 text-white text-center px-6 py-2 rounded-lg">
                    <i class="fas fa-car text-xl block mb-1"></i>
                    <span class="text-xs">Vehicle Listing</span>
                </a>
                <a href="#" class="text-white text-center hover:opacity-80 transition">
                    <i class="fas fa-history text-xl block mb-1"></i>
                    <span class="text-xs">History</span>
                </a>
                <a href="#" class="text-white text-center hover:opacity-80 transition">
                    <i class="fas fa-cog text-xl block mb-1"></i>
                    <span class="text-xs">Settings</span>
                </a>
            </div>

            <!-- Login and Profile -->
            <div class="flex items-center space-x-4">
                <button class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Login
                </button>
                <img src="https://ui-avatars.com/api/?name=Profile&background=3b82f6&color=fff" alt="Profile" class="w-12 h-12 rounded-full border-2 border-white">
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 px-6 py-8">
        <!-- Back Button and Title -->
        <div class="flex items-center space-x-3 mb-8">
            <button class="w-12 h-12 bg-black rounded-full flex items-center justify-center hover:bg-gray-800 transition">
                <i class="fas fa-chevron-left text-white text-lg"></i>
            </button>
            <h1 class="text-3xl font-bold text-gray-800">Payment</h1>
        </div>

        <!-- Payment Card -->
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-12">
            <form method="POST" action="/receipt" enctype="multipart/form-data">
                @csrf
            <!-- Company Name -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">HASTA TRAVEL & TOURS SDN BHD</h2>

            <div class="flex justify-center mb-8">
                <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-6 rounded-3xl shadow-xl">
                    <div class="bg-white p-4 rounded-2xl">
                        <img 
                            src="/images/hasta-qr.png" 
                            alt="HASTA Payment QR Code"
                            class="w-[300px] h-[300px] object-contain"
                        />
                    </div>
                    <div class="text-center mt-4">
                        <span class="text-white font-bold text-sm">DuitNow QR</span>
                    </div>
                </div>
            </div>            
            <!-- Total Payment -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Total Payment : <span class="text-red-500">RM340</span></h3>
            </div>

            <!-- File Upload -->
            <div class="flex flex-col items-center mb-6">
                <label class="relative">
                    <input 
                        type="file" 
                        name="payment_proof" 
                        id="fileInput" 
                        class="hidden" 
                        accept="image/*,.pdf"
                        required
                    >

                    <button 
                        type="button"
                        id="uploadBtn"
                        onclick="document.getElementById('fileInput').click()"
                        class="px-6 py-3 bg-gray-100 border-2 border-gray-300 rounded-lg 
                            text-gray-700 font-medium hover:bg-gray-200 transition
                            flex items-center gap-2"
                    >
                        <i class="fas fa-upload"></i>
                        <span>Choose File</span>
                    </button>
                </label>

                
                <p class="text-xs italic text-red-400 mt-1">
                    Accepted formats: JPG, PNG, and PDF
                </p>
            </div>


            <p id="fileName" class="text-center text-sm text-gray-500 mb-8">
                No File Chosen
            </p>

            <!-- Submit Button -->
             <div class="flex justify-center">
                <button type="submit"
                    class="px-16 py-4 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold text-lg rounded-xl hover:from-red-600 hover:to-orange-600 transition shadow-lg">
                    Submit
                </button>
            </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-red-500 to-red-600 text-white py-12 mt-auto">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Logo -->
                <div>
                    <div class="bg-white px-4 py-2 rounded-md inline-block mb-6">
                        <span class="text-red-500 font-bold text-xl">HASTA</span>
                    </div>
                    <!-- Social Media Icons -->
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
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

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-white border-opacity-20 pt-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 class="font-bold">Email</h4>
                        <p class="text-sm">hastatravel@gmail.com</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h4 class="font-bold">Phone</h4>
                        <p class="text-sm">011-1090 0700</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const fileInput = document.getElementById('fileInput');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadText = document.getElementById('uploadText');
        const fileNameText = document.getElementById('fileName');

        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                const fileName = fileInput.files[0].name;

                // Update file name
                fileNameText.textContent = fileName;

                // Change button to Uploaded
                uploadBtn.classList.remove('bg-gray-100', 'border-gray-300', 'text-gray-700');
                uploadBtn.classList.add('bg-green-500', 'border-green-600', 'text-white');

                uploadBtn.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <span>Uploaded</span>
                `;
            }
        });
    </script>
</body>
</html>