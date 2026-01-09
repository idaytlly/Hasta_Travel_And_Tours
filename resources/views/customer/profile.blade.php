@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        background: #f3f3f3;
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        max-width: 900px; /* samakan size edit.blade.php */
        margin: 0 auto 40px;
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        color: white;
        padding: 50px 40px 120px;
        text-align: center;
        position: relative;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .profile-email {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .profile-body {
        padding: 0 40px 40px;
        margin-top: -80px;
    }

    .info-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
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
    }

    .info-value {
        flex: 1;
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
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .btn-edit-profile,
    .btn-back {
        padding: 14px 35px;
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
    }

    .btn-edit-profile:hover {
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
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
                <h1 class="profile-name">{{ $customer->name ?? 'User Name' }}</h1>
                <p class="profile-email">{{ $customer->email ?? 'email@example.com' }}</p>
            </div>

            <div class="profile-body">
                <!-- Personal Info -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-user-circle"></i></div>
                        <h3 class="section-title">Personal Information</h3>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $customer->name ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">IC/NRIC Number</div>
                        <div class="info-value {{ !isset($customer->ic) ? 'empty' : '' }}">{{ $customer->ic ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Matric Number</div>
                        <div class="info-value {{ !isset($customer->matricNum) ? 'empty' : '' }}">{{ $customer->matricNum ?? 'Not provided' }}</div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-address-book"></i></div>
                        <h3 class="section-title">Contact Information</h3>
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

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('customer.profile.edit') }}" class="btn-edit-profile"><i class="fas fa-edit"></i>Edit Profile</a>
                    <a href="{{ route('customer.home') }}" class="btn-back"><i class="fas fa-arrow-left"></i>Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
