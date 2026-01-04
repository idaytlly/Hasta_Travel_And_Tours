@extends('layouts.staff')

@section('title', 'Profile Settings')

@section('content')
<style>
    :root {
        --primary-color: #eb2525ff;
        --primary-dark: #af1e1eff;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --border-color: #e2e8f0;
        --bg-light: #f8fafc;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }
    
    .settings-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }
    
    .page-title {
        color: var(--text-dark);
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    /* Success Alert */
    .success-alert {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .success-alert i {
        color: var(--success-color);
        font-size: 1.25rem;
    }
    
    /* Cards */
    .settings-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .card-header-custom {
        background: var(--bg-light);
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .card-title {
        color: var(--text-dark);
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: var(--primary-color);
    }
    
    .card-subtitle {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin: 0;
    }
    
    .card-body-custom {
        padding: 2rem;
    }
    
    /* Profile Overview */
    .profile-header-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        padding: 2rem;
        border-radius: 12px;
        color: white;
        margin-bottom: 1.5rem;
    }
    
    .profile-avatar-circle {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }
    
    .profile-avatar-circle i {
        font-size: 2rem;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .profile-email {
        opacity: 0.9;
        margin: 0;
    }
    
    /* Info Items */
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
    }
    
    .info-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        border: 1px solid var(--border-color);
    }
    
    .info-icon i {
        color: var(--primary-color);
        font-size: 1.125rem;
    }
    
    .info-content h6 {
        font-size: 0.813rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-content p {
        font-size: 0.938rem;
        color: var(--text-dark);
        margin: 0;
        font-weight: 500;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        background: #f0fdf4;
        color: var(--success-color);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid #86efac;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        background: var(--success-color);
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .user-id-badge {
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        margin-top: 1rem;
    }
    
    .user-id-badge h6 {
        font-size: 0.813rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .user-id-badge code {
        background: var(--bg-light);
        color: var(--text-dark);
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        border: 1px solid var(--border-color);
    }
    
    /* Form Styles */
    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-control {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.938rem;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    
    .form-control:disabled {
        background: var(--bg-light);
        color: var(--text-muted);
    }
    
    .input-group-text {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-right: none;
        color: var(--text-muted);
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
    }
    
    /* Password Toggle */
    .password-toggle {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-left: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0.625rem 0.875rem;
        border-radius: 0 8px 8px 0;
    }
    
    .password-toggle:hover {
        color: var(--primary-color);
    }
    
    /* Requirements Box */
    .requirements-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 1.25rem;
    }
    
    .requirements-box h6 {
        color: var(--primary-color);
        font-size: 0.938rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    
    .requirements-box ul {
        margin: 0;
        padding-left: 1.25rem;
    }
    
    .requirements-box li {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 0.375rem;
    }
    
    .requirements-box li:last-child {
        margin-bottom: 0;
    }
    
    /* Buttons */
    .btn-primary {
        background: var(--primary-color);
        border: 1px solid var(--primary-color);
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    
    .btn-outline-secondary {
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        background: white;
        transition: all 0.2s;
    }
    
    .btn-outline-secondary:hover {
        background: var(--bg-light);
        border-color: var(--secondary-color);
    }
    
    /* Badge */
    .role-badge {
        background: white;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .role-badge i {
        color: var(--primary-color);
    }
    
    /* Helper Text */
    .helper-text {
        color: var(--text-muted);
        font-size: 0.813rem;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    /* Invalid Feedback */
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.813rem;
        margin-top: 0.375rem;
    }
    
    /* Form Actions */
    .form-actions {
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 0.75rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-primary,
        .btn-outline-secondary {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4 settings-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="page-title">Profile Settings</h1>
                <p class="card-subtitle">Manage your account information and security preferences</p>
            </div>
            <div class="role-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Staff Account</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle"></i>
            <div class="flex-grow-1">
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Overview -->
        <div class="col-lg-4">
            <div class="settings-card">
                <div class="profile-header-section">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-avatar-circle">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h2 class="profile-name">{{ $user->name }}</h2>
                            <p class="profile-email">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body-custom">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="info-content">
                            <h6>Account Role</h6>
                            <p>{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="info-content">
                            <h6>Member Since</h6>
                            <p>{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h6>Last Updated</h6>
                            <p>{{ $user->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <span class="status-badge">
                            <span class="status-dot"></span>
                            Active & Verified
                        </span>
                    </div>
                    
                    <div class="user-id-badge">
                        <h6>User ID</h6>
                        <code>{{ $user->id }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information & Password -->
        <div class="col-lg-8">
            <!-- Profile Information Form -->
            <div class="settings-card">
                <div class="card-header-custom">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit"></i>
                        <span>Profile Information</span>
                    </h3>
                    <p class="card-subtitle">Update your personal details and contact information</p>
                </div>
                
                <div class="card-body-custom">
                    <form action="{{ route('staff.settings.updateProfile') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i> Full Name
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i> Email Address
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required
                                       placeholder="your.email@company.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i> Phone Number
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="+1 (555) 000-0000">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-briefcase me-1"></i> Role
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ ucfirst($user->role) }}" 
                                       disabled>
                                <small class="helper-text">
                                    <i class="fas fa-info-circle"></i>
                                    Contact administrator for role changes
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="settings-card">
                <div class="card-header-custom">
                    <h3 class="card-title">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </h3>
                    <p class="card-subtitle">Update your password to keep your account secure</p>
                </div>
                
                <div class="card-body-custom">
                    <form action="{{ route('staff.settings.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key me-1"></i> Current Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required
                                           placeholder="Enter your current password">
                                    <button class="password-toggle" type="button" data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> New Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Enter new password">
                                    <button class="password-toggle" type="button" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-check-circle me-1"></i> Confirm Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           placeholder="Confirm new password">
                                    <button class="password-toggle" type="button" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="requirements-box mb-4">
                            <h6>
                                <i class="fas fa-info-circle me-2"></i>
                                Password Requirements
                            </h6>
                            <ul>
                                <li>Minimum 8 characters in length</li>
                                <li>Include both uppercase and lowercase letters</li>
                                <li>Include at least one number (0-9)</li>
                                <li>Include special characters (!@#$%^&*) for enhanced security</li>
                            </ul>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Password visibility toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.password-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            }, 5000);
        }
    });
</script>
@endsection