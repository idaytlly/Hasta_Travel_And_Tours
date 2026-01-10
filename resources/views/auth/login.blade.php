@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* Beige Section */
.beige-section {
    background-color: #F2ECEB;
    padding: 3rem 0 5rem 0;
}

.beige-section h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.beige-section p {
    font-size: 1rem;
    color: #6b7280;
}

/* Login Card */
.login-card {
    border: none;
    border-radius: 12px;
    background: #ffffff;
    padding: 30px;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

/* Form Labels & Inputs */
.form-label-custom {
    color: #6b7280;
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 8px;
}

.form-control-custom {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 0.9rem;
}

.form-control-custom:focus {
    border-color: #F0785B;
    box-shadow: 0 0 0 3px rgba(240, 120, 91, 0.1);
}

/* User Type Selector */
.user-type-selector {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    background: #f8f9fa;
    padding: 8px;
    border-radius: 8px;
}

.user-type-btn {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid transparent;
    background: white;
    color: #6b7280;
    font-weight: 500;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
}

.user-type-btn:hover {
    border-color: #e5e7eb;
    color: #1f2937;
}

.user-type-btn.active {
    border-color: #F0785B;
    background-color: rgba(240, 120, 91, 0.1);
    color: #F0785B;
    font-weight: 600;
}

/* Login Button */
.btn-login {
    background: #CB3737;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 32px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.2s;
    width: 100%;
}

.btn-login:hover {
    background: #a52c2c;
    color: white;
}

/* Checkbox */
.form-check-input:checked {
    background-color: #F0785B;
    border-color: #F0785B;
}

/* Staff Info Box */
.staff-info-box {
    background-color: #fff3e0;
    border: 1px solid #ffe0b2;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: none;
}

.staff-info-box h6 {
    color: #f57c00;
    font-weight: 600;
    margin-bottom: 8px;
}

.staff-info-box p {
    margin-bottom: 5px;
    font-size: 0.9rem;
    color: #5d4037;
}

.staff-info-box code {
    background: rgba(240, 120, 91, 0.1);
    color: #F0785B;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.85rem;
}

/* Links */
.auth-links {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.auth-links a {
    color: #F0785B;
    text-decoration: none;
    font-weight: 500;
    margin: 0 10px;
}

.auth-links a:hover {
    color: #CB3737;
    text-decoration: underline;
}

/* Alert Styling */
.alert-custom {
    border-radius: 8px;
    border: none;
    margin-bottom: 25px;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-left: 4px solid #dc3545;
}

.alert-success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
    border-left: 4px solid #198754;
}
</style>

<!-- Beige Section -->
<div class="beige-section">
    <div class="container">
        <!-- Heading -->
        <div class="text-center mb-4">
            <h1>Welcome Back!</h1>
            <p>Login to access your account</p>
        </div>

        <!-- Login Card -->
        <div class="d-flex justify-content-center">
            <div class="login-card">
                @if(session('error'))
                    <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="user-type-selector">
                    <button type="button" class="user-type-btn active" id="btn-customer" onclick="selectUserType('customer')">
                        <i class="bi bi-person me-1"></i> Customer
                    </button>
                    <button type="button" class="user-type-btn" id="btn-staff" onclick="selectUserType('staff')">
                        <i class="bi bi-person-badge me-1"></i> Staff
                    </button>
                </div>
                
                <div class="staff-info-box" id="staff-info">
                    <h6><i class="bi bi-info-circle me-1"></i> Staff Access</h6>
                    <p>Use your staff credentials to access the management dashboard.</p>
                    <p class="mb-0">
                        <strong>Test Account:</strong> 
                        <code>staff@example.com</code> / <code>password123</code>
                    </p>
                </div>
                
                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf
                    
                    <input type="hidden" name="user_type" id="user_type" value="customer">
                    
                    <div class="mb-4">
                        <label for="email" class="form-label-custom">Email Address</label>
                        <input type="text" class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" 
                                   id="password" name="password" required
                                   placeholder="Enter your password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember" style="color: #6b7280; font-size: 0.9rem;">
                                Remember me
                            </label>
                        </div>
                        <a href="#" style="color: #F0785B; font-size: 0.9rem; text-decoration: none;">
                            Forgot password?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-login" id="submit-btn">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login as Customer
                    </button>
                </form>
                
                <div class="auth-links">
                    <a href="{{ route('register') }}">Create Account</a>
                    <span style="color: #d1d5db;">|</span>
                    <a href="{{ route('home') }}">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectUserType(type) {
    // Update buttons
    document.getElementById('btn-customer').classList.remove('active');
    document.getElementById('btn-staff').classList.remove('active');
    document.getElementById('btn-' + type).classList.add('active');
    
    // Update hidden field
    document.getElementById('user_type').value = type;
    
    // Update submit button text
    const submitBtn = document.getElementById('submit-btn');
    const btnIcon = submitBtn.querySelector('i');
    const btnText = type === 'staff' ? 'Login as Staff' : 'Login as Customer';
    submitBtn.innerHTML = btnIcon.outerHTML + ' ' + btnText;
    
    // Show/hide staff info
    document.getElementById('staff-info').style.display = 
        (type === 'staff') ? 'block' : 'none';
}

// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

// Auto-hide staff info after 5 seconds for customers
setTimeout(() => {
    if (document.getElementById('user_type').value === 'customer') {
        document.getElementById('staff-info').style.display = 'none';
    }
}, 5000);
</script>
@endsection