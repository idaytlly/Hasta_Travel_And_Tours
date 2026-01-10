<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Login - Car Rental Portal</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        .login-bg {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            min-height: 100vh;
        }
        
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #DC2626;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #DC2626;
            border-radius: 4px;
        }
        
        .role-btn[data-selected="true"] {
            background-color: #DC2626;
            color: white;
            border-color: #DC2626;
        }
        
        .role-btn[data-selected="true"] svg {
            color: white;
        }
        
        .role-btn:not([data-selected="true"]) svg {
            color: #6B7280;
        }
    </style>
</head>
<body class="login-bg">
    
    <div class="min-h-screen flex items-center justify-center p-4 md:p-8">
        <div class="flex flex-col md:flex-row items-center justify-center w-full max-w-6xl gap-8 md:gap-12">
            
            <!-- Left Side - Brand & Info -->
            <div class="md:w-1/2 text-white text-center md:text-left">
                <div class="mb-8 md:mb-12">
                    <div class="flex items-center gap-3 justify-center md:justify-start">
                        <i data-lucide="car" class="w-10 h-10 md:w-12 md:h-12"></i>
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold">CarRental Pro</h1>
                            <p class="text-lg md:text-xl text-red-100 mt-1">Staff Portal</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6 max-w-md mx-auto md:mx-0">
                    <div class="bg-white bg-opacity-10 p-4 md:p-6 rounded-xl backdrop-blur-sm">
                        <h2 class="text-xl md:text-2xl font-semibold mb-3 md:mb-4">Staff Benefits</h2>
                        <ul class="space-y-2 md:space-y-3">
                            <li class="flex items-center gap-2 md:gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 md:w-5 md:h-5 text-green-300 flex-shrink-0"></i>
                                <span class="text-sm md:text-base">Real-time booking management</span>
                            </li>
                            <li class="flex items-center gap-2 md:gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 md:w-5 md:h-5 text-green-300 flex-shrink-0"></i>
                                <span class="text-sm md:text-base">Commission tracking for runners</span>
                            </li>
                            <li class="flex items-center gap-2 md:gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 md:w-5 md:h-5 text-green-300 flex-shrink-0"></i>
                                <span class="text-sm md:text-base">Advanced analytics & reporting</span>
                            </li>
                            <li class="flex items-center gap-2 md:gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 md:w-5 md:h-5 text-green-300 flex-shrink-0"></i>
                                <span class="text-sm md:text-base">Delivery & pickup task management</span>
                            </li>
                            <li class="flex items-center gap-2 md:gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 md:w-5 md:h-5 text-green-300 flex-shrink-0"></i>
                                <span class="text-sm md:text-base">Customer management tools</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="text-red-100 text-xs md:text-sm">
                        <p>For demo purposes, you can login with:</p>
                        <p class="font-mono mt-1 text-xs md:text-sm bg-white bg-opacity-10 p-2 rounded inline-block">Staff ID: any • Password: demo123</p>
                    </div>
                    
                    <div class="hidden md:block">
                        <div class="flex items-center gap-3 text-red-100">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                            <span>Secure & encrypted login</span>
                        </div>
                        <div class="flex items-center gap-3 text-red-100 mt-2">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                            <span>24/7 Support Available</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="md:w-1/2 max-w-md w-full">
                <div class="bg-white rounded-2xl card-shadow p-6 md:p-8">
                    <div class="text-center mb-6 md:mb-8">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Staff Login</h2>
                        <p class="text-gray-600 text-sm md:text-base mt-1 md:mt-2">Enter your credentials to access the portal</p>
                    </div>
                    
                    <form id="loginForm" class="space-y-4 md:space-y-6">
                        <!-- Staff ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
                                Staff ID
                            </label>
                            <div class="relative">
                                <i data-lucide="user" class="w-4 h-4 md:w-5 md:h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" 
                                       id="staffId" 
                                       placeholder="Enter your staff ID"
                                       class="w-full pl-9 md:pl-10 pr-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition text-sm md:text-base"
                                       required
                                       autocomplete="username">
                            </div>
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <i data-lucide="lock" class="w-4 h-4 md:w-5 md:h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="password" 
                                       id="password" 
                                       placeholder="Enter your password"
                                       class="w-full pl-9 md:pl-10 pr-10 md:pr-12 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition text-sm md:text-base"
                                       required
                                       autocomplete="current-password">
                                <button type="button" 
                                        onclick="togglePassword()" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i data-lucide="eye" class="w-4 h-4 md:w-5 md:h-5" id="password-icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">
                                Login As
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" 
                                        onclick="selectRole('staff')" 
                                        id="role-staff"
                                        class="role-btn px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs md:text-sm flex flex-col items-center justify-center"
                                        data-selected="true">
                                    <i data-lucide="user" class="w-4 h-4 md:w-5 md:h-5 mb-1"></i>
                                    <span>Staff</span>
                                </button>
                                <button type="button" 
                                        onclick="selectRole('runner')" 
                                        id="role-runner"
                                        class="role-btn px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs md:text-sm flex flex-col items-center justify-center">
                                    <i data-lucide="truck" class="w-4 h-4 md:w-5 md:h-5 mb-1"></i>
                                    <span>Runner</span>
                                </button>
                                <button type="button" 
                                        onclick="selectRole('admin')" 
                                        id="role-admin"
                                        class="role-btn px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-xs md:text-sm flex flex-col items-center justify-center">
                                    <i data-lucide="shield" class="w-4 h-4 md:w-5 md:h-5 mb-1"></i>
                                    <span>Admin</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="rememberMe" class="w-4 h-4 text-red-600 rounded focus:ring-red-500">
                                <span class="text-xs md:text-sm text-gray-700">Remember me</span>
                            </label>
                            <button type="button" onclick="showForgotPassword()" class="text-xs md:text-sm text-red-600 hover:text-red-700">
                                Forgot password?
                            </button>
                        </div>
                        
                        <!-- Login Button -->
                        <button type="submit" 
                                id="loginButton"
                                class="w-full bg-red-600 text-white py-2 md:py-3 px-4 rounded-lg hover:bg-red-700 transition font-semibold text-sm md:text-base flex items-center justify-center gap-2 mt-4 md:mt-6">
                            <span id="loginButtonText">Sign In to Portal</span>
                            <i data-lucide="log-in" class="w-4 h-4 md:w-5 md:h-5"></i>
                        </button>
                        
                        <!-- Demo Login Buttons -->
                        <div class="pt-4 md:pt-6 border-t border-gray-200">
                            <p class="text-xs md:text-sm text-gray-600 text-center mb-2 md:mb-3">Quick Demo Login</p>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        onclick="demoLogin('staff')" 
                                        class="px-3 md:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-xs md:text-sm flex items-center justify-center gap-1 md:gap-2">
                                    <i data-lucide="user" class="w-3 h-3 md:w-4 md:h-4"></i>
                                    <span>As Staff</span>
                                </button>
                                <button type="button" 
                                        onclick="demoLogin('runner')" 
                                        class="px-3 md:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-xs md:text-sm flex items-center justify-center gap-1 md:gap-2">
                                    <i data-lucide="truck" class="w-3 h-3 md:w-4 md:h-4"></i>
                                    <span>As Runner</span>
                                </button>
                                <button type="button" 
                                        onclick="demoLogin('admin')" 
                                        class="px-3 md:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-xs md:text-sm col-span-2 flex items-center justify-center gap-1 md:gap-2">
                                    <i data-lucide="shield" class="w-3 h-3 md:w-4 md:h-4"></i>
                                    <span>As Admin</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="hidden text-center mt-4">
                        <div class="spinner mx-auto"></div>
                        <p class="text-gray-500 text-xs md:text-sm mt-2">Authenticating...</p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-4 md:mt-6 text-red-100 text-xs md:text-sm">
                    <p>© 2026 CarRental Pro. All rights reserved.</p>
                    <p class="mt-1">For assistance, contact: <a href="mailto:support@carrental.com" class="underline">support@carrental.com</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>
    
    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl p-4 md:p-6 max-w-md w-full card-shadow">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-lg md:text-xl font-bold text-gray-800">Reset Password</h3>
                <button onclick="hideForgotPassword()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5 md:w-6 md:h-6"></i>
                </button>
            </div>
            <p class="text-gray-600 text-sm md:text-base mb-3 md:mb-4">Enter your staff email to receive password reset instructions.</p>
            <div class="space-y-3 md:space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Staff Email</label>
                    <input type="email" 
                           id="resetEmail"
                           placeholder="you@carrental.com"
                           class="w-full px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm md:text-base">
                </div>
                <button onclick="sendResetLink()" class="w-full bg-red-600 text-white py-2 md:py-3 px-4 rounded-lg hover:bg-red-700 transition text-sm md:text-base">
                    Send Reset Link
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Selected role (default: staff)
        let selectedRole = 'staff';
        
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                passwordIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
        
        // Select role
        function selectRole(role) {
            selectedRole = role;
            
            // Update button styles
            ['staff', 'runner', 'admin'].forEach(r => {
                const btn = document.getElementById(`role-${r}`);
                if (r === role) {
                    btn.setAttribute('data-selected', 'true');
                } else {
                    btn.removeAttribute('data-selected');
                }
            });
        }
        
        // Show forgot password modal
        function showForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
        }
        
        // Hide forgot password modal
        function hideForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        }
        
        // Send reset link
        function sendResetLink() {
            const email = document.getElementById('resetEmail').value;
            if (!email) {
                showToast('Please enter your email address', 'error');
                return;
            }
            
            showToast('Password reset link sent to ' + email, 'success');
            hideForgotPassword();
        }
        
        // Demo login
        function demoLogin(role) {
            document.getElementById('staffId').value = 'STAFF' + (role === 'runner' ? 'RUN001' : role === 'admin' ? 'ADM001' : '001');
            document.getElementById('password').value = 'demo123';
            selectRole(role);
            
            setTimeout(() => {
                submitLogin();
            }, 500);
        }
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `px-4 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.innerHTML = `
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            
            document.getElementById('toastContainer').appendChild(toast);
            lucide.createIcons();
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
        
        // Format currency
        function formatCurrency(amount) {
            return 'RM ' + parseFloat(amount).toFixed(2);
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-MY', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }
        
        // Submit login
        async function submitLogin() {
            const staffId = document.getElementById('staffId').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('rememberMe').checked;
            
            if (!staffId || !password) {
                showToast('Please enter both staff ID and password', 'error');
                return;
            }
            
            // Show loading
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            loginButton.disabled = true;
            loginButtonText.textContent = 'Authenticating...';
            loadingSpinner.classList.remove('hidden');
            
            try {
                // Simulate API call delay
                await new Promise(resolve => setTimeout(resolve, 1500));
                
                // Mock authentication - in real app, this would call your API
                const mockStaffData = {
                    staff_id: staffId,
                    staff_name: selectedRole === 'runner' ? 'John Runner' : 
                               selectedRole === 'admin' ? 'Admin User' : 'Staff Member',
                    staff_role: selectedRole,
                    email: `${selectedRole}@carrental.com`,
                    join_date: '2024-01-01',
                    commission_this_month: selectedRole === 'runner' ? 380.00 : 0
                };
                
                // Store in localStorage (in real app, use secure tokens)
                localStorage.setItem('staff_id', mockStaffData.staff_id);
                localStorage.setItem('staff_name', mockStaffData.staff_name);
                localStorage.setItem('staff_role', mockStaffData.staff_role);
                localStorage.setItem('staff_email', mockStaffData.email);
                localStorage.setItem('staff_join_date', mockStaffData.join_date);
                localStorage.setItem('staff_commission', mockStaffData.commission_this_month);
                localStorage.setItem('is_authenticated', 'true');
                
                if (rememberMe) {
                    localStorage.setItem('remember_me', 'true');
                }
                
                showToast(`Welcome back, ${mockStaffData.staff_name}!`, 'success');
                
                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = '/staff/dashboard';
                }, 1000);
                
            } catch (error) {
                showToast('Login failed. Please check your credentials.', 'error');
            } finally {
                loginButton.disabled = false;
                loginButtonText.textContent = 'Sign In to Portal';
                loadingSpinner.classList.add('hidden');
            }
        }
        
        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitLogin();
        });
        
        // Auto-login from localStorage if remembered
        document.addEventListener('DOMContentLoaded', () => {
            // Check if already logged in
            if (localStorage.getItem('is_authenticated') === 'true') {
                // If remember me is checked, auto-redirect to dashboard
                if (localStorage.getItem('remember_me') === 'true') {
                    window.location.href = '/staff/dashboard';
                }
            }
            
            // Enter key to submit
            document.getElementById('password').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    submitLogin();
                }
            });
            
            // Focus on staff ID input
            document.getElementById('staffId').focus();
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                // Ctrl+1 for staff demo
                if (e.ctrlKey && e.key === '1') {
                    e.preventDefault();
                    demoLogin('staff');
                }
                // Ctrl+2 for runner demo
                if (e.ctrlKey && e.key === '2') {
                    e.preventDefault();
                    demoLogin('runner');
                }
                // Ctrl+3 for admin demo
                if (e.ctrlKey && e.key === '3') {
                    e.preventDefault();
                    demoLogin('admin');
                }
            });
        });
    </script>
</body>
</html>