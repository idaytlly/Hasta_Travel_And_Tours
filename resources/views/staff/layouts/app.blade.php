<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Staff Dashboard') | Hasta Travel & Tours</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3a57e8;
            --primary-dark: #2a46d0;
            --secondary-color: #6c757d;
            --success-color: #1aa053;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
            --transition-speed: 0.3s;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fb;
            color: #333;
            overflow-x: hidden;
        }
        
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Content Area */
        .content-area {
            padding: 25px;
            min-height: calc(100vh - var(--header-height));
            flex: 1;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-title-section {
            display: flex;
            flex-direction: column;
        }
        
        .page-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 5px;
        }
        
        .page-subtitle {
            color: var(--secondary-color);
            font-size: 0.95rem;
        }
        
        .page-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        /* Footer */
        .footer {
            padding: 20px 25px;
            background-color: white;
            border-top: 1px solid #eee;
            text-align: center;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                margin-left: -100%;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            
            .overlay.show {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 15px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
        
        /* Custom Utilities */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            transition: transform 0.3s;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-footer {
            background-color: white;
            border-top: 1px solid #eee;
            padding: 15px 20px;
            border-radius: 0 0 10px 10px !important;
        }
        
        .badge {
            padding: 5px 10px;
            font-weight: 500;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .badge-sm {
            padding: 3px 8px;
            font-size: 0.75rem;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-sm {
            padding: 5px 15px;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(58, 87, 232, 0.2);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: #212529;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: rgba(26, 160, 83, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #856404;
            border-left: 4px solid var(--warning-color);
        }
        
        .alert-info {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
            border-left: 4px solid var(--info-color);
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: var(--secondary-color);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--light-bg);
            border-bottom: none;
            font-weight: 600;
            color: #555;
            padding: 15px;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(58, 87, 232, 0.05);
        }
        
        .stats-card {
            text-align: center;
            padding: 20px;
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
        }
        
        .stats-icon.primary {
            background-color: rgba(58, 87, 232, 0.1);
            color: var(--primary-color);
        }
        
        .stats-icon.success {
            background-color: rgba(26, 160, 83, 0.1);
            color: var(--success-color);
        }
        
        .stats-icon.warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }
        
        .stats-icon.danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        .stats-icon.info {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
        }
        
        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .stats-label {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(58, 87, 232, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }
        
        .required-field::after {
            content: " *";
            color: var(--danger-color);
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--secondary-color);
        }
        
        .empty-state-icon {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 15px;
        }
        
        .empty-state-text {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: rgba(26, 160, 83, 0.1);
            color: var(--success-color);
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #856404;
        }
        
        .status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: var(--secondary-color);
        }
        
        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #ddd;
            background-color: white;
            color: #666;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }
        
        /* Print Styles */
        @media print {
            .sidebar, .top-header, .page-actions, .btn, .footer {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    
    <!-- Additional Styles for Specific Pages -->
    @stack('styles')
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Include Sidebar -->
        @include('staff.partials.sidebar')
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Include Header -->
            @include('staff.partials.header')
            
            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header with Breadcrumb -->
                <div class="page-header">
                    <div class="page-title-section">
                        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                        <p class="page-subtitle">@yield('page-subtitle', 'Welcome to your dashboard')</p>
                    </div>
                    
                    <div class="page-actions">
                        @yield('page-actions')
                    </div>
                </div>
                
                <!-- Breadcrumb -->
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Main Content -->
                <main>
                    @yield('content')
                </main>
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            &copy; {{ date('Y') }} Hasta Travel & Tours. All rights reserved.
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="text-muted">v1.0.0</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Mobile Overlay (for sidebar on mobile) -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Chart.js (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('overlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            
            // Toggle sidebar on desktop
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }
            
            // Toggle sidebar on mobile
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                });
            }
            
            // Close sidebar when clicking overlay on mobile
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    if (!sidebar.contains(event.target) && !mobileSidebarToggle.contains(event.target) && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        if (overlay) overlay.classList.remove('show');
                    }
                }
            });
            
            // Initialize submenu toggles
            document.querySelectorAll('.has-submenu > .nav-link').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    if (window.innerWidth > 992 || !sidebar.classList.contains('collapsed')) {
                        e.preventDefault();
                        const submenu = this.nextElementSibling;
                        const parent = this.parentElement;
                        
                        // Close other open submenus in the same level
                        const siblings = parent.parentElement.querySelectorAll('.has-submenu');
                        siblings.forEach(function(sibling) {
                            if (sibling !== parent) {
                                sibling.classList.remove('active');
                                sibling.querySelector('.submenu').classList.remove('show');
                            }
                        });
                        
                        // Toggle current submenu
                        parent.classList.toggle('active');
                        submenu.classList.toggle('show');
                    }
                });
            });
            
            // Auto-close submenus when sidebar is collapsed
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (sidebar.classList.contains('collapsed')) {
                            document.querySelectorAll('.has-submenu').forEach(function(item) {
                                item.classList.remove('active');
                                item.querySelector('.submenu').classList.remove('show');
                            });
                        }
                    }
                });
            });
            
            observer.observe(sidebar, { attributes: true });
            
            // Initialize DataTables if table has datatable class
            if ($('.datatable').length) {
                $('.datatable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        zeroRecords: "No matching records found",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                });
            }
            
            // Initialize Select2 if select has select2 class
            if ($('.select2').length) {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
            }
            
            // Initialize Flatpickr if input has datepicker class
            if ($('.datepicker').length) {
                $('.datepicker').flatpickr({
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
            }
            
            if ($('.datetimepicker').length) {
                $('.datetimepicker').flatpickr({
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    allowInput: true
                });
            }
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert:not(.alert-permanent)').alert('close');
            }, 5000);
            
            // Handle form submissions with loading state
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                if (submitBtn.length) {
                    submitBtn.prop('disabled', true);
                    if (!submitBtn.find('.loading-spinner').length) {
                        submitBtn.prepend('<span class="loading-spinner me-2"></span>');
                    }
                }
            });
        });
        
        // Window resize handling
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
            }
        });
    </script>
    
    <!-- Additional Scripts for Specific Pages -->
    @stack('scripts')
</body>
</html>