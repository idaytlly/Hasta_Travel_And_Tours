@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        background: linear-gradient(135deg, #eaeaeaff 0%, #f3f3f3ff 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        overflow: hidden;
        margin: 0 auto 40px; /* center card */
        max-width: 750px;     /* sama size dengan edit.blade.php */
        width: 100%;           /* responsive */
    }

    .profile-header {
        background: linear-gradient(135deg, #f36060ff 0%, #f99a9aff 100%);
        color: white;
        padding: 30px 120px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .profile-email {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .profile-body {
        padding: 20px 30px; /* adjust ikut form */
        margin-top: -60px;   /* adjust header overlap */
        position: relative;
        z-index: 2;
    }

    .info-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 2px solid #f8f9fa;
        transition: all 0.3s ease;
    }

    .info-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        border-color: #e53935;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #e53935;
    }

    .section-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .info-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #f1f3f5;
        align-items: center;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        flex: 0 0 200px;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label i {
        color: #e53935;
        width: 20px;
    }

    .info-value {
        flex: 1;
        color: #333;
        font-size: 1rem;
        font-weight: 500;
    }

    .info-value.empty {
        color: #adb5bd;
        font-style: italic;
    }

    .badge-status {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-verified {
        background: #d4edda;
        color: #155724;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-expired {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .btn-edit-profile {
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        color: white;
        padding: 14px 35px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
        transition: all 0.3s ease;
    }

    .btn-edit-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(229, 57, 53, 0.4);
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        color: white;
    }

    .btn-back {
        background: white;
        color: #6c757d;
        padding: 14px 35px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: 2px solid #e9ecef;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #f8f9fa;
        border-color: #ddd;
        color: #333;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px 20px;
        border-radius: 12px;
        border-left: 4px solid #28a745;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success i {
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 40px 20px 100px;
        }

        .profile-name {
            font-size: 1.5rem;
        }

        .profile-body {
            padding: 0 20px 30px;
        }

        .info-section {
            padding: 20px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .info-label {
            flex: none;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-edit-profile,
        .btn-back {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="profile-container">
    <div class="container">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="profile-card">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar-large">
                    <i class="fas fa-customer"></i>
                </div>
                <h1 class="profile-name">{{ $customer->name ?? 'User Name' }}</h1>
                <p class="profile-email">{{ $customer->email ?? 'customer@example.com' }}</p>
            </div>

            <!-- Profile Body -->
            <div class="profile-body">
                
                <!-- Personal Information Section -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3 class="section-title">Personal Information</h3>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-signature"></i>
                            Full Name
                        </div>
                        <div class="info-value">
                            {{ $customer->name ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i>
                            IC/NRIC Number
                        </div>
                        <div class="info-value {{ !isset($customer->ic_number) ? 'empty' : '' }}">
                            {{ $customer->ic_number ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-graduation-cap"></i>
                            Matric Number
                        </div>
                        <div class="info-value {{ !isset($customer->matricNum) ? 'empty' : '' }}">
                            {{ $customer->matricNum ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            License Expiry
                        </div>
                        <div class="info-value">
                            @if(isset($customer->license_expiry))
                                {{ \Carbon\Carbon::parse($customer->license_expiry)->format('d M Y') }}
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($customer->license_expiry);
                                    $today = \Carbon\Carbon::today();
                                @endphp
                                @if($expiryDate->isPast())
                                    <span class="badge-status badge-expired">Expired</span>
                                @elseif($expiryDate->diffInDays($today) <= 30)
                                    <span class="badge-status badge-pending">Expiring Soon</span>
                                @else
                                    <span class="badge-status badge-verified">Valid</span>
                                @endif
                            @else
                                <span class="empty">Not provided</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <h3 class="section-title">Contact Information</h3>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-phone"></i>
                            Phone Number
                        </div>
                        <div class="info-value {{ !isset($customer->phone_no) ? 'empty' : '' }}">
                            {{ isset($customer->phone_no) ? '+60 ' . $customer->phone_no : 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </div>
                        <div class="info-value">
                            {{ $customer->email ?? 'Not provided' }}
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="section-title">Emergency Contact</h3>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-user-shield"></i>
                            Contact Name
                        </div>
                        <div class="info-value {{ !isset($customer->emergency_name) ? 'empty' : '' }}">
                            {{ $customer->emergency_name ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-phone-alt"></i>
                            Contact Number
                        </div>
                        <div class="info-value {{ !isset($customer->emergency_phoneNo) ? 'empty' : '' }}">
                            {{ isset($customer->emergency_phoneNo) ? '+60 ' . $customer->emergency_phoneNo : 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-users"></i>
                            Relationship
                        </div>
                        <div class="info-value {{ !isset($customer->emergency_relationship) ? 'empty' : '' }}">
                            {{ $customer->emergency_relationship ?? 'Not provided' }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('customer.profile.edit') }}" class="btn-edit-profile">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </a>
                    <a href="{{ route('customer.home') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection