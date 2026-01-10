<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hasta Travel And Tours</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f3c3a3ff 0%, #FF9792 50%, #FF5850 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-text {
            background: #ff5722;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
            display: inline-block;
            letter-spacing: 2px;
        }

        .profile-icon {
            width: 120px;
            height: 120px;
            margin: 32px auto;
            background: white;
            border-radius: 50%;
            border: 6px solid #000;
            position: relative;
            overflow: hidden;
        }

        .profile-icon::before {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            background: #000;
            border-radius: 50%;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .profile-icon::after {
            content: '';
            position: absolute;
            width: 85px;
            height: 100px;
            background: #000;
            border-radius: 50%;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 8px;
        }

        .welcome-text h1 {
            font-size: 32px;
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
        }

        .welcome-text p {
            font-size: 14px;
            color: #666;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #000;
            margin-bottom: 8px;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff5722;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            font-size: 14px;
            color: #333;
            cursor: pointer;
        }

        .forgot-password {
            font-size: 14px;
            color: #2563eb;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #1d4ed8;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: #EC592B;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-bottom: 24px;
        }

        .login-button:hover {
            background: #f4511e;
        }

        .signup-link {
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .signup-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: #1d4ed8;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
            }

            .welcome-text h1 {
                font-size: 28px;
            }

            .profile-icon {
                width: 100px;
                height: 100px;
            }
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