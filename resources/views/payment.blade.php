@extends('layouts.guest')

@section('content')
    <main class="flex-1 px-6 py-8">
        <div class="flex items-center space-x-3 mb-8">
            <button onclick="window.history.back()" class="w-12 h-12 bg-black rounded-full flex items-center justify-center hover:bg-gray-800 transition">
                <i class="fas fa-chevron-left text-white text-lg"></i>
            </button>
            <h1 class="text-3xl font-bold text-gray-800">Payment</h1>
        </div>

        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-12">
        <form 
            method="POST" 
            action="{{ route('payment.process', $booking->booking_reference) }}" 
            enctype="multipart/form-data"
            onsubmit="handleSubmit(event)"
        >
            @csrf

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
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-800"> Total Payment : RM {{ number_format($booking->total_price, 2) }}</h3>
            </div>

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
                    <span id="uploadText">Choose File</span>
                </button>
            </label>

            <p class="text-xs text-gray-400 mt-1" id="fileHelpText">
                Accepted formats: JPG, PNG, and PDF. Max size: 5MB *
            </p>

            <p id="fileName" class="text-center text-sm text-gray-500 mb-8">
                No File Chosen
            </p>

            <div class="flex justify-center">
                <button type="submit"
                    class="px-16 py-4 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold text-lg rounded-xl hover:from-red-600 hover:to-orange-600 transition shadow-lg">
                    Submit
                </button>
            </div>
            </form>
        </div>
    </main>

    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-sm text-center shadow-lg flex flex-col items-center">
            <div class="w-20 h-20 flex items-center justify-center bg-green-100 rounded-full mb-4">
                <i class="fas fa-check text-green-600 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Upload Successful!</h2>
            <p class="text-gray-500 mb-6">Your receipt has been uploaded successfully.</p>
            <button onclick="closeModal()" class="px-6 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                Close
            </button>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const uploadBtn = document.getElementById('uploadBtn');
        const fileNameText = document.getElementById('fileName');
        const helpText = document.getElementById('fileHelpText');
        const modal = document.getElementById('successModal');

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        fileInput.addEventListener('change', function () {
            uploadBtn.classList.remove('bg-green-500', 'border-green-600', 'text-white', 'border-red-500');
            uploadBtn.classList.add('bg-gray-100', 'border-gray-300', 'text-gray-700');
            helpText.classList.remove('text-red-500');
            helpText.classList.add('text-gray-400');

            if (fileInput.files.length === 0) {
                fileNameText.textContent = 'No File Chosen';
                return;
            }

            const file = fileInput.files[0];

            if (!allowedTypes.includes(file.type)) {
                helpText.textContent = 'Invalid file type! *';
                helpText.classList.add('text-red-500');
                fileInput.value = '';
                return;
            }

            if (file.size > maxSize) {
                helpText.textContent = 'File too large! *';
                helpText.classList.add('text-red-500');
                fileInput.value = '';
                return;
            }

            fileNameText.textContent = file.name;
            uploadBtn.classList.add('bg-green-500', 'text-white');
        });

        function handleSubmit(e) {
            e.preventDefault();
            if (!fileInput.files.length) {
                alert('Please upload proof.');
                return;
            }

            // Show success modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Wait 2 seconds then submit the actual form
            setTimeout(() => {
                e.target.submit();
            }, 2000);
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
