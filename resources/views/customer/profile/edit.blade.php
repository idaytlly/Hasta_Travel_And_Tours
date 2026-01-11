@extends('layouts.app')

@section('content')
<style>
    .profile-edit-container {
        background: linear-gradient(135deg, #eaeaeaff 0%, #f3f3f3ff 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .edit-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        overflow: hidden;
        margin-bottom: 40px;
    }

    .edit-header {
        background: linear-gradient(135deg, #eea1a0ff 0%, #fd5c5cff 100%);
        color: white;
        padding: 20px 30px;
        text-align: center;
        position: relative;
    }

    .edit-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
    }

    .edit-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .edit-header p {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .edit-body {
        padding: 40px;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 3px solid #e53935;
        display: inline-block;
    }

    .section-divider {
        margin: 40px 0;
        border: none;
        height: 2px;
        background: linear-gradient(to right, #e53935, transparent);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: #e53935;
        font-size: 1rem;
    }

    .form-label .required {
        color: #e53935;
        margin-left: 3px;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        border-color: #e53935;
        box-shadow: 0 0 0 0.2rem rgba(229, 57, 53, 0.15);
        background-color: white;
    }

    .form-control:hover, .form-select:hover {
        border-color: #ddd;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #6c757d;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .input-group:focus-within .input-group-text {
        border-color: #e53935;
        background-color: white;
    }

    .input-group:focus-within .form-control {
        border-color: #e53935;
    }

    .btn-save {
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        color: white;
        padding: 14px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(229, 57, 53, 0.4);
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
    }

    .btn-cancel {
        background: white;
        color: #6c757d;
        padding: 14px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        border-color: #ddd;
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px dashed #e9ecef;
    }

    .info-helper {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .info-helper i {
        color: #17a2b8;
    }

    @media (max-width: 768px) {
        .edit-header h1 {
            font-size: 1.5rem;
        }

        .edit-body {
            padding: 25px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-save, .btn-cancel {
            width: 100%;
        }
    }
</style>

<div class="profile-edit-container">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="edit-card">

            <!-- Header -->
            <div class="edit-header">
                <div class="profile-avatar">
                    <i class="fas fa-customer-edit"></i>
                </div>
                <h1>Edit Your Profile</h1>
                <p>Keep your information up to date for a better experience</p>
            </div>

            <!-- Form Body -->
            <div class="edit-body">
                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div class="section-title">
                        <i class="fas fa-customer-circle"></i> Personal Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-signature"></i>
                                    Full Name
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $customer->name ?? '') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Identification Card (IC/NRIC)
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="ic_number" value="{{ old('ic_number', $customer->ic_number ?? '') }}" placeholder="e.g., 990101-01-1234" required>
                                <div class="info-helper">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Enter your MyKad number</span>
                                </div>
                                @error('ic_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-graduation-cap"></i>
                                    Matric Number
                                </label>
                                <input type="text" class="form-control" name="matricNum" value="{{ old('matricNum', $customer->matricNum ?? '') }}" placeholder="e.g., A21EC0001">
                                <div class="info-helper">
                                    <i class="fas fa-info-circle"></i>
                                    <span>For students only</span>
                                </div>
                                @error('matricNum')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    License Expiry Date
                                    <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control" name="license_expiry" value="{{ old('license_expiry', $customer->license_expiry ?? '') }}" required>
                                @error('license_expiry')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Contact Information Section -->
                    <div class="section-title">
                        <i class="fas fa-address-book"></i> Contact Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Phone Number
                                    <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">+60</span>
                                    <input type="text" class="form-control" name="phone_no" value="{{ old('phone_no', $customer->phone_no ?? '') }}" placeholder="123456789" required>
                                </div>
                                @error('phone_no')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" class="form-control" value="{{ $customer->email ?? '' }}" readonly style="background-color: #e9ecef;">
                                <div class="info-helper">
                                    <i class="fas fa-lock"></i>
                                    <span>Email cannot be changed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Emergency Contact Section -->
                    <div class="section-title">
                        <i class="fas fa-exclamation-triangle"></i> Emergency Contact
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-customer-shield"></i>
                                    Emergency Contact Name
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="emergency_name" value="{{ old('emergency_name', $customer->emergency_name ?? '') }}" required>
                                @error('emergency_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone-alt"></i>
                                    Emergency Contact Number
                                    <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">+60</span>
                                    <input type="text" class="form-control" name="emergency_phoneNo" value="{{ old('emergency_phoneNo', $customer->emergency_phoneNo ?? '') }}" placeholder="123456789" required>
                                </div>
                                @error('emergency_phoneNo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-customers"></i>
                                    Relationship
                                    <span class="required">*</span>
                                </label>
                                <select class="form-select" name="emergency_relationship" required>
                                    <option value="Father" {{ old('emergency_relationship', $customer->emergency_relationship ?? '') == 'Father' ? 'selected' : '' }}>Father</option>
                                    <option value="Mother" {{ old('emergency_relationship', $customer->emergency_relationship ?? '') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="Sibling" {{ old('emergency_relationship', $customer->emergency_relationship ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                </select>
                                @error('emergency_relationship')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ route('customer.profile.edit') }}" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>
@endsection