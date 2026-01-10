<!DOCTYPE html>
<html>
<head>
    <title>Staff Login</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        .login-box { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #007bff; color: white; padding: 10px; border: none; width: 100%; }
        .error { color: red; background: #ffeaea; padding: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Staff Login</h2>
        
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        
        <form method="POST" action="{{ route('staff.login.submit') }}">
            @csrf
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login as Staff</button>
        </form>
        
        <p style="margin-top: 20px;">
            <strong>Test credentials:</strong><br>
            Username: <code>staff</code><br>
            Password: <code>password123</code>
        </p>
        
        <p><a href="{{ route('guest.home') }}">Back to Home</a></p>
    </div>
</body>
</html>