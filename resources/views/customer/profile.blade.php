@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        background: linear-gradient(135deg, #eaeaea 0%, #f3f3f3 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        overflow: hidden;
        margin-bottom: 40px;
        max-width: 750px;
        margin: 0 auto;
    }

    .profile-header {
        background: linear-gradient(135deg, #eea1a0 0%, #fd5c5c 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
        position: relative;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
    }

    .profile-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 1px;
        position: relative;
        z-index: 1;
    }

    .profile-header p {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .profile-body {
        padding: 40px;
        margin-top: -20px;
    }

    .info-section {
        margin-bottom: 30px;
        border: 2px solid #f1f3f5;
        border-radius: 15px;
        padding: 25px 30px;
        background: #fff;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 3px solid #e53935;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #e53935;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f5;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        width: 200px;
    }

    .info-value {
        color: #333;
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
        justify-content: center;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px dashed #e9ecef;
    }

    .btn-edit-profile, .btn-back {
        padding: 14px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-edit-profile {
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
        border: none;
    }

    .btn-edit-profile:hover {
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(229, 57, 53, 0.4);
    }

    .btn-back {
        background: white;
        border: 2px solid #e9ecef;
        color: #6c757d;
    }

    .btn-back:hover {
        background: #f8f9fa;
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

    @media (max-width: 768px) {
        .profile-body {
            padding: 25px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-label {
            margin-bottom: 6px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-edit-profile, .btn-back {
            width: 100%;
        }
    }
</style>

<div class="profile-container">
    <div class="container">
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-header">
                <h1>{{ $customer->name ?? 'User Name' }}</h1>
                <p>{{ $customer->email ?? 'email@example.com' }}</p>
            </div>

            <div class="profile-body">
                <!-- Personal Info -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i> Personal Information
                    </div>

                    <div class="info-row">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $customer->name ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">IC/NRIC Number</div>
                        <div class="info-value {{ !isset($customer->ic_number) ? 'empty' : '' }}">{{ $customer->ic_number ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Matric Number</div>
                        <div class="info-value {{ !isset($customer->matricNum) ? 'empty' : '' }}">{{ $customer->matricNum ?? 'Not provided' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
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

                <!-- Contact Info -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-address-book"></i> Contact Information
                    </div>

                    <div class="info-row">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value {{ !isset($customer->phone_no) ? 'empty' : '' }}">
                            {{ isset($customer->phone_no) ? '+60 '.$customer->phone_no : 'Not provided' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $customer->email ?? 'Not provided' }}</div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-exclamation-triangle"></i> Emergency Contact
                    </div>

                    <div class="info-row">
                        <div class="info-label">Contact Name</div>
                        <div class="info-value {{ !isset($customer->emergency_name) ? 'empty' : '' }}">
                            {{ $customer->emergency_name ?? 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Contact Phone</div>
                        <div class="info-value {{ !isset($customer->emergency_phoneNo) ? 'empty' : '' }}">
                            {{ isset($customer->emergency_phoneNo) ? '+60 '.$customer->emergency_phoneNo : 'Not provided' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Relationship</div>
                        <div class="info-value {{ !isset($customer->emergency_relationship) ? 'empty' : '' }}">
                            {{ $customer->emergency_relationship ?? 'Not provided' }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('customer.profile.edit') }}" class="btn-edit-profile">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <a href="{{ route('customer.home') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection