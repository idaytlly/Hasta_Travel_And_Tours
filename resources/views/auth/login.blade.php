<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Car Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 600;
        }
        .user-type-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .user-type-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #ddd;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        .user-type-btn.active {
            border-color: #0d6efd;
            background-color: #e7f1ff;
            color: #0d6efd;
        }
        .staff-login-info {
            display: none;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h2>Login to Car Rental</h2>
                <p class="text-muted">Choose your login type</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="user-type-selector">
                <button type="button" class="user-type-btn active" id="btn-customer" onclick="selectUserType('customer')">
                    Customer Login
                </button>
                <button type="button" class="user-type-btn" id="btn-staff" onclick="selectUserType('staff')">
                    Staff Login
                </button>
            </div>
            
            <div class="staff-login-info" id="staff-info">
                <p class="mb-1"><strong>Staff Test Credentials:</strong></p>
                <p class="mb-0">Username: <code>staff</code> | Password: <code>password123</code></p>
            </div>
            
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf
                
                <!-- Hidden field to identify user type -->
                <input type="hidden" name="user_type" id="user_type" value="customer">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email/Username</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100" id="submit-btn">Login as Customer</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="{{ route('register') }}">Create Account</a> | 
                <a href="{{ route('guest.home') }}">Back to Home</a>
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
            document.getElementById('submit-btn').textContent = 'Login as ' + 
                (type === 'staff' ? 'Staff' : 'Customer');
            
            // Show/hide staff info
            document.getElementById('staff-info').style.display = 
                (type === 'staff') ? 'block' : 'none';
            
            // Update form action for staff (we'll handle this in controller)
            // For now, same form action for both
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>