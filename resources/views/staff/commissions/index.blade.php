@extends('layouts.staff')

@section('title', 'My Commissions')
@section('page-title', 'My Commissions')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --success: #22c55e;
        --warning: #f59e0b;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f1f5f9;
        --border: #e2e8f0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, #b91c1c 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(220, 38, 38, 0.2);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    .commission-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }

    .commission-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .commission-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .commission-id {
        font-family: 'Monaco', 'Courier New', monospace;
        font-weight: 700;
        color: var(--primary);
        font-size: 0.875rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-approved {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }

    .status-rejected {
        background: rgba(220, 38, 38, 0.1);
        color: #b91c1c;
    }

    .commission-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 1.5rem;
    }

    .service-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .service-info p {
        font-size: 0.875rem;
        color: var(--gray);
        margin: 0;
    }

    .commission-amount {
        text-align: center;
    }

    .amount-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--success);
    }

    .amount-label {
        font-size: 0.75rem;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .commission-date {
        text-align: center;
    }

    .date-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--dark);
    }

    .date-label {
        font-size: 0.75rem;
        color: var(--gray);
    }

    .admin-notes {
        margin-top: 1rem;
        padding: 1rem;
        background: var(--light);
        border-left: 4px solid var(--info);
        border-radius: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--light);
        border-radius: 16px;
    }

    @media (max-width: 768px) {
        .commission-content {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2" style="font-size: 1.75rem; font-weight: 700;">
                    <i class="fas fa-hand-holding-usd me-2"></i>My Commissions
                </h1>
                <p class="mb-0" style="opacity: 0.9;">Track your earnings from extra services</p>
            </div>
            <a href="{{ route('staff.commissions.create') }}" class="btn btn-light btn-lg" 
            style="font-family: 'Inter', 'Helvetica Neue', sans-serif; font-weight: 600; font-size: 1rem; letter-spacing: 0.025em;">
                <i class="fas fa-plus me-2"></i>New Commission
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" 
             style="border-radius: 12px; border-left: 4px solid var(--success); background: rgba(34, 197, 94, 0.1);">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3" style="color: var(--success); font-size: 1.5rem;"></i>
                <div class="flex-grow-1">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">RM {{ number_format($totalPending, 2) }}</div>
            <div class="stat-label">Pending Approval</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">RM {{ number_format($totalApproved, 2) }}</div>
            <div class="stat-label">Total Approved</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-value">RM {{ number_format($totalEarned, 2) }}</div>
            <div class="stat-label">This Month</div>
        </div>
    </div>

    <!-- Commissions List -->
    <div class="mb-4">
        <h5 style="font-weight: 600; color: var(--dark); margin-bottom: 1rem;">
            <i class="fas fa-list me-2"></i>Commission History
        </h5>
    </div>

    @forelse($commissions as $commission)
        <div class="commission-card">
            <div class="commission-header">
                <div class="commission-id">
                    <i class="fas fa-hashtag"></i>COM-{{ str_pad($commission->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                <span class="status-badge status-{{ $commission->status }}">
                    @if($commission->status === 'pending')
                        <i class="fas fa-clock"></i> Pending
                    @elseif($commission->status === 'approved')
                        <i class="fas fa-check-circle"></i> Approved
                    @else
                        <i class="fas fa-times-circle"></i> Rejected
                    @endif
                </span>
            </div>

            <div class="commission-content">
                <div class="service-info">
                    <h4>
                        <i class="fas fa-{{ 
                            $commission->service_type === 'car_wash' ? 'soap' : 
                            ($commission->service_type === 'detailing' ? 'sparkles' : 
                            ($commission->service_type === 'fuel_top_up' ? 'gas-pump' : 'tools'))
                        }} me-2 text-primary"></i>
                        {{ ucwords(str_replace('_', ' ', $commission->service_type)) }}
                    </h4>
                    <p>{{ $commission->description }}</p>
                    @if($commission->booking)
                        <p class="mt-2">
                            <i class="fas fa-link me-1"></i>
                            <small>Related to Booking #{{ $commission->booking->booking_reference }}</small>
                        </p>
                    @endif
                </div>

                <div class="commission-amount">
                    <div class="amount-label">Amount</div>
                    <div class="amount-value">RM {{ number_format($commission->amount, 2) }}</div>
                </div>

                <div class="commission-date">
                    <div class="commission-date">
                    <div class="date-label">Submitted</div>
                    <div class="date-value" data-timestamp-date="{{ $commission->created_at->timestamp }}"></div>
                    <div class="date-label" data-timestamp="{{ $commission->created_at->timestamp }}">
                        {{ $commission->created_at->diffForHumans() }}
                    </div>
                </div>
             </div>
            </div>

            @if($commission->status !== 'pending' && $commission->admin_notes)
                <div class="admin-notes">
                    <strong style="font-size: 0.875rem; color: var(--dark);">
                        <i class="fas fa-user-shield me-1"></i>Admin Notes:
                    </strong>
                    <p class="mb-0 mt-1" style="font-size: 0.875rem; color: var(--gray);">
                        {{ $commission->admin_notes }}
                    </p>
                    @if($commission->approver)
                        <small style="color: var(--gray); font-size: 0.75rem;">
                            by {{ $commission->approver->name }} • {{ $commission->approved_at->diffForHumans() }}
                        </small>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-hand-holding-usd" style="font-size: 4rem; color: var(--gray); opacity: 0.3; margin-bottom: 1.5rem;"></i>
            <h3 style="font-weight: 700; color: var(--dark);">No Commissions Yet</h3>
            <p style="color: var(--gray); margin-bottom: 2rem;">Start earning by submitting your first commission!</p>
            <a href="{{ route('staff.commissions.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Submit Commission
            </a>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($commissions->hasPages())
        <div class="mt-4">
            {{ $commissions->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
// Function to format date in local timezone
function formatLocalDate(timestamp) {
    const date = new Date(timestamp * 1000);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
}

// Function to update relative times
function updateRelativeTimes() {
    // Update dates
    document.querySelectorAll('[data-timestamp-date]').forEach(element => {
        const timestamp = parseInt(element.getAttribute('data-timestamp-date'));
        element.textContent = formatLocalDate(timestamp);
    });
    
    // Update relative times
    document.querySelectorAll('[data-timestamp]').forEach(element => {
        const timestamp = parseInt(element.getAttribute('data-timestamp'));
        const now = Math.floor(Date.now() / 1000);
        const diff = now - timestamp;
        
        let timeAgo = '';
        
        if (diff < 0) {
            timeAgo = 'Just now';
        } else if (diff < 60) {
            timeAgo = 'Just now';
        } else if (diff < 3600) {
            const minutes = Math.floor(diff / 60);
            timeAgo = minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
        } else if (diff < 86400) {
            const hours = Math.floor(diff / 3600);
            timeAgo = hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
        } else if (diff < 604800) {
            const days = Math.floor(diff / 86400);
            timeAgo = days + ' day' + (days > 1 ? 's' : '') + ' ago';
        } else if (diff < 2592000) {
            const weeks = Math.floor(diff / 604800);
            timeAgo = weeks + ' week' + (weeks > 1 ? 's' : '') + ' ago';
        } else if (diff < 31536000) {
            const months = Math.floor(diff / 2592000);
            timeAgo = months + ' month' + (months > 1 ? 's' : '') + ' ago';
        } else {
            const years = Math.floor(diff / 31536000);
            timeAgo = years + ' year' + (years > 1 ? 's' : '') + ' ago';
        }
        
        element.textContent = timeAgo;
    });
}

// Update times immediately and then every minute
updateRelativeTimes();
setInterval(updateRelativeTimes, 60000);
</script>
@endpush
@endsection