// Real-time updates for Staff Dashboard
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Staff Real-Time Updates Initialized');

    // Listen for new bookings
    window.Echo.channel('staff-dashboard')
        .listen('.booking.created', (data) => {
            console.log('📢 New Booking Created:', data);
            
            // Show notification
            showNotification('New Booking!', `${data.customer_name} booked ${data.vehicle}`, 'success');
            
            // Update dashboard stats
            updateBookingStats();
            
            // Add to recent bookings table
            addBookingToTable(data);
            
            // Play notification sound
            playNotificationSound();
        })
        .listen('.booking.updated', (data) => {
            console.log('📢 Booking Updated:', data);
            
            showNotification('Booking Updated', `Booking ${data.booking_id} - ${data.action}`, 'info');
            
            // Update specific booking row
            updateBookingRow(data.booking_id, data);
            
            // Update stats
            updateBookingStats();
        })
        .listen('.payment.received', (data) => {
            console.log('💰 Payment Received:', data);
            
            showNotification('Payment Received', `RM${data.amount} from ${data.customer_name}`, 'success');
            
            // Update payment pending count
            updatePaymentStats();
            
            // Highlight booking row
            highlightBookingRow(data.booking_id);
        });
});

// Show notification
function showNotification(title, message, type = 'info') {
    // Check if browser supports notifications
    if (!("Notification" in window)) {
        console.log("This browser does not support notifications");
        return;
    }

    // Check permission
    if (Notification.permission === "granted") {
        new Notification(title, {
            body: message,
            icon: '/images/logo.png',
            badge: '/images/badge.png'
        });
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                new Notification(title, { body: message });
            }
        });
    }

    // Also show in-page toast notification
    showToast(title, message, type);
}

// Toast notification
function showToast(title, message, type) {
    const colors = {
        success: 'bg-green-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500',
        error: 'bg-red-500'
    };

    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <div>
                <div class="font-bold">${title}</div>
                <div class="text-sm">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                ✕
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Update booking stats
async function updateBookingStats() {
    try {
        const response = await fetch('/api/staff/dashboard/stats');
        const data = await response.json();

        if (data.success) {
            document.getElementById('total-bookings').textContent = data.stats.total_bookings;
            document.getElementById('pending-bookings').textContent = data.stats.pending_bookings;
            document.getElementById('confirmed-bookings').textContent = data.stats.confirmed_bookings;
            document.getElementById('active-bookings').textContent = data.stats.active_bookings;
        }
    } catch (error) {
        console.error('Error updating stats:', error);
    }
}

// Add booking to table
function addBookingToTable(booking) {
    const tableBody = document.getElementById('recent-bookings-table');
    if (!tableBody) return;

    const row = document.createElement('tr');
    row.className = 'bg-yellow-50 animate-pulse';
    row.id = `booking-row-${booking.booking_id}`;
    
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                NEW
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${booking.booking_id}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${booking.customer_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${booking.vehicle}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${booking.pickup_date}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RM${booking.total_price}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                ${booking.booking_status}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="/staff/bookings/${booking.booking_id}" class="text-blue-600 hover:text-blue-900">View</a>
        </td>
    `;

    tableBody.insertBefore(row, tableBody.firstChild);

    // Remove highlight after 3 seconds
    setTimeout(() => {
        row.classList.remove('bg-yellow-50', 'animate-pulse');
    }, 3000);
}

// Update booking row
function updateBookingRow(bookingId, data) {
    const row = document.getElementById(`booking-row-${bookingId}`);
    if (!row) return;

    // Flash the row
    row.classList.add('bg-blue-50');
    setTimeout(() => row.classList.remove('bg-blue-50'), 2000);

    // Update status badge
    const statusBadge = row.querySelector('.rounded-full');
    if (statusBadge) {
        statusBadge.textContent = data.booking_status;
        statusBadge.className = getStatusBadgeClass(data.booking_status);
    }
}

// Highlight booking row
function highlightBookingRow(bookingId) {
    const row = document.getElementById(`booking-row-${bookingId}`);
    if (!row) return;

    row.classList.add('bg-green-50');
    setTimeout(() => row.classList.remove('bg-green-50'), 3000);
}

// Get status badge class
function getStatusBadgeClass(status) {
    const classes = {
        pending: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800',
        confirmed: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800',
        active: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800',
        completed: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800',
        cancelled: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'
    };
    return classes[status] || classes.pending;
}

// Play notification sound
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.volume = 0.5;
    audio.play().catch(e => console.log('Sound play failed:', e));
}

// Update payment stats
async function updatePaymentStats() {
    try {
        const response = await fetch('/api/staff/dashboard/stats');
        const data = await response.json();

        if (data.success) {
            document.getElementById('pending-payments').textContent = data.stats.pending_payments;
            document.getElementById('today-revenue').textContent = `RM${data.stats.today_revenue.toFixed(2)}`;
        }
    } catch (error) {
        console.error('Error updating payment stats:', error);
    }
}