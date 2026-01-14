@extends('layouts.app')

@section('title', 'Edit Profile')

@section('noFooter', true)

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

    .form-control::placeholder {
        color: #6c757d;
        opacity: 0.8;
        font-weight: 500;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #6c757d;
        font-weight: 600;
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

    /* File Upload Styles */
    .file-upload-wrapper {
        position: relative;
        margin-top: 8px;
    }

    .file-upload-inline {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .file-upload-label-compact {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(229, 57, 53, 0.3);
    }

    .file-upload-label-compact:hover {
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        box-shadow: 0 4px 12px rgba(229, 57, 53, 0.4);
        transform: translateY(-1px);
    }

    .file-upload-label-compact i {
        font-size: 1rem;
    }

    .current-file-inline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #e3f2fd;
        border: 1px solid #64b5f6;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .current-file-inline i {
        color: #1976d2;
        font-size: 0.95rem;
    }

    .current-file-inline a {
        color: #1976d2;
        text-decoration: none;
        font-weight: 600;
    }

    .current-file-inline a:hover {
        text-decoration: underline;
    }

    .file-input-hidden {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
    }

    .file-preview {
        margin-top: 8px;
        padding: 8px 12px;
        background: #e8f5e9;
        border: 1px solid #81c784;
        border-radius: 8px;
        display: none;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .file-preview.show {
        display: flex;
    }

    .file-preview i {
        color: #2e7d32;
        font-size: 1rem;
    }

    .file-preview-text {
        flex: 1;
        color: #2e7d32;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .file-preview-remove {
        background: transparent;
        border: none;
        color: #d32f2f;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .file-preview-remove:hover {
        background: #ffebee;
    }

    .current-file {
        margin-top: 8px;
        padding: 8px 12px;
        background: #e3f2fd;
        border: 1px solid #64b5f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
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
                <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Identification Card / Passport
                                    <span class="required">*</span>
                                </label>
                                
                                <!-- IC File Upload -->
                                <div class="file-upload-inline">
                                    <label for="ic_file" class="file-upload-label-compact">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Upload Copy</span>
                                    </label>
                                    <input type="file" id="ic_file" name="ic_file" class="file-input-hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    @if(isset($customer->ic_file_path))
                                    <div class="current-file-inline">
                                        <i class="fas fa-file-check"></i>
                                        <a href="{{ asset('storage/' . $customer->ic_file_path) }}" target="_blank">View Current</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="file-preview" id="ic_preview">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="file-preview-text" id="ic_filename"></span>
                                    <button type="button" class="file-preview-remove" onclick="removeFile('ic_file')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="info-helper">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Upload your MyKad (PDF, JPG, PNG - Max 2MB)</span>
                                </div>
                                @error('ic_file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-graduation-cap"></i>
                                    Matric Card
                                </label>
                                
                                <!-- Matric File Upload -->
                                <div class="file-upload-inline">
                                    <label for="matric_file" class="file-upload-label-compact">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Upload Copy</span>
                                    </label>
                                    <input type="file" id="matric_file" name="matric_file" class="file-input-hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    @if(isset($customer->matric_file_path))
                                    <div class="current-file-inline">
                                        <i class="fas fa-file-check"></i>
                                        <a href="{{ asset('storage/' . $customer->matric_file_path) }}" target="_blank">View Current</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="file-preview" id="matric_preview">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="file-preview-text" id="matric_filename"></span>
                                    <button type="button" class="file-preview-remove" onclick="removeFile('matric_file')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="info-helper">
                                    <i class="fas fa-info-circle"></i>
                                    <span>For students only (PDF, JPG, PNG - Max 2MB)</span>
                                </div>
                                @error('matric_file')
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
                                    <input type="text" class="form-control" name="phone_no" value="{{ old('phone_no', $customer->phone_no ?? '') }}" placeholder="Enter your number without +60" required>
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
                                    <input type="text" class="form-control" name="emergency_phoneNo" value="{{ old('emergency_phoneNo', $customer->emergency_phoneNo ?? '') }}" placeholder="Enter number without +60" required>
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
                        <a href="{{ route('customer.profile') }}" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Discard Changes
                        </a>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>
</div>

<script>
    // File upload preview functionality
    function setupFileUpload(inputId, previewId, filenameId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const filename = document.getElementById(filenameId);
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                filename.textContent = file.name;
                preview.classList.add('show');
            }
        });
    }

    function removeFile(inputId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(inputId.replace('_file', '_preview'));
        
        input.value = '';
        preview.classList.remove('show');
    }

    // Initialize file uploads
    setupFileUpload('ic_file', 'ic_preview', 'ic_filename');
    setupFileUpload('matric_file', 'matric_preview', 'matric_filename');
</script>
@endsection