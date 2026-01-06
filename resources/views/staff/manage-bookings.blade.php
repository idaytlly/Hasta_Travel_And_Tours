<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f9fafb;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 100px;
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
            color: white;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 1rem 0.5rem;
            margin-bottom: 0.5rem;
        }

        .logo-box {
            background: white;
            color: #dc2626;
            font-weight: bold;
            font-size: 0.875rem;
            padding: 0.375rem;
            border-radius: 0.375rem;
            text-align: center;
        }

        .nav {
            flex: 1;
            padding: 0 0.5rem;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .nav-item:hover {
            background: rgba(248, 113, 113, 0.3);
        }

        .nav-item.active {
            background: rgba(248, 113, 113, 0.5);
        }

        .nav-item i {
            margin-bottom: 0.125rem;
        }

        .nav-item span {
            font-size: 0.625rem;
            text-align: center;
            line-height: 1.1;
            padding: 0 0.25rem;
        }

        .profile {
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-avatar {
            width: 2.5rem;
            height: 2.5rem;
            background: #60a5fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.125rem;
        }

        .profile span {
            font-size: 0.625rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
            padding: 1rem 1.5rem;
        }

        .search-container {
            max-width: 28rem;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border-radius: 9999px;
            border: none;
            outline: none;
            font-size: 0.875rem;
        }

        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(252, 165, 165, 0.5);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 1.5rem;
            overflow: auto;
        }

        .content-inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        h1 {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .tabs-filter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .tabs {
            display: flex;
            gap: 2rem;
        }

        .tab {
            padding-bottom: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background: none;
            color: #9ca3af;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
        }

        .tab:hover {
            color: #4b5563;
        }

        .tab.active {
            color: #ef4444;
            border-bottom-color: #ef4444;
        }

        .filter-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f97316;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
        }

        .filter-btn:hover {
            background: #ea580c;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            text-align: left;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #374151;
        }

        tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody td {
            padding: 1rem 1.5rem;
        }

        .empty-state {
            text-align: center;
            color: #9ca3af;
            padding: 3rem !important;
        }

        /* Status badges */
        .status-approved {
            color: #22c55e;
            font-weight: 500;
        }

        .status-pending {
            color: #f59e0b;
            font-weight: 500;
        }

        .status-rejected {
            color: #ef4444;
            font-weight: 500;
        }

        .view-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .view-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-box">HASTA</div>
            </div>

            <nav class="nav">
                <div class="nav-item">
                    <i class="fas fa-home" style="font-size: 1.125rem;"></i>
                    <span>Home</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-bell" style="font-size: 1.125rem;"></i>
                    <span>Notifications</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-th-large" style="font-size: 1.125rem;"></i>
                    <span>Dashboard</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-car" style="font-size: 1.125rem;"></i>
                    <span>Vehicle Management</span>
                </div>
                <div class="nav-item active">
                    <i class="fas fa-file-alt" style="font-size: 1.125rem;"></i>
                    <span>Booking Management</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-clock" style="font-size: 1.125rem;"></i>
                    <span>History</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-cog" style="font-size: 1.125rem;"></i>
                    <span>Settings</span>
                </div>
            </nav>

            <div class="profile">
                <div class="profile-avatar">
                    <i class="fas fa-user" style="font-size: 1.25rem;"></i>
                </div>
                <span>Profile</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search">
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="content-inner">
                    <h1>Booking Management</h1>

                    <!-- Tabs and Filter -->
                    <div class="tabs-filter">
                        <div class="tabs">
                            <button class="tab active" data-tab="all">All Order</button>
                            <button class="tab" data-tab="pending">Pending</button>
                            <button class="tab" data-tab="approved">Approved</button>
                            <button class="tab" data-tab="rejected">Rejected</button>
                        </div>

                        <button class="filter-btn">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Id </th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Your Laravel data loop will go here -->
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        No bookings to display. Data will appear here when added.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // You can add AJAX call here to filter data based on tab
                const tabType = this.dataset.tab;
                console.log('Tab clicked:', tabType);
            });
        });

        // Filter button functionality
        document.querySelector('.filter-btn').addEventListener('click', function() {
            console.log('Filter clicked');
            // Add your filter modal/dropdown logic here
        });

        // Navigation items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                if (!this.classList.contains('active')) {
                    console.log('Navigate to:', this.querySelector('span').textContent);
                }
            });
        });
    </script>
</body>
</html>