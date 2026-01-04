@extends('layouts.admin')

@section('title', 'Commission Management')
@section('page-title', 'Commission Management')

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
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: white;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .filter-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 2px solid var(--border);
        background: white;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .filter-tab:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .filter-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .commission-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.25rem;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }

    .commission-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .commission-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--border);
    }

    .staff-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .staff-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), #b91c1c);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
    }

    .commission-body {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 2rem;
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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border);
    }

    .btn-action {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 2px solid;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-approve {
        background: var(--success);
        color: white;
        border-color: var(--success);
    }

    .btn-approve:hover {
        background: #16a34a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-reject {
        background: white;
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-reject:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary), #b91c1c);
        color: white;
        border-radius: 16px 16px 0 0;
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="mb-2" style="font-size: 1.75rem; font-weight: 700;">
            <i class="fas fa-hand-holding-usd me-2"></i>Commission Management
        </h1>
        <p class="mb-0" style="opacity: 0.9;">Review and approve staff commission requests</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending Review</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
            <div class="stat-label">Approved</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(220, 38, 38, 0.1); color: var(--primary);">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">RM {{ number_format($stats['total_pending_amount'], 0) }}</div>
            <div class="stat-label">Pending Amount</div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('admin.commissions.index') }}" 
           class="filter-tab {{ !request('status') || request('status') == 'pending' ? 'active' : '' }}">
            <i class="fas fa-clock me-2"></i>Pending
        </a>
        <a href="{{ route('admin.commissions.index', ['status' => 'approved']) }}" 
           class="filter-tab {{ request('status') == 'approved' ? 'active' : '' }}">
            <i class="fas fa-check-circle me-2"></i>Approved
        </a>
        <a href="{{ route('admin.commissions.index', ['status' => 'rejected']) }}" 
           class="filter-tab {{ request('status') == 'rejected' ? 'active' : '' }}">
            <i class="fas fa-times-circle me-2"></i>Rejected
        </a>
        <a href="{{ route('admin.commissions.index', ['status' => 'all']) }}" 
           class="filter-tab {{ request('status') == 'all' ? 'active' : '' }}">
            <i class="fas fa-list me-2"></i>All
        </a>
    </div>

    <!-- Commissions List -->
    @forelse($commissions as $commission)
        <div class="commission-card">
            <div class="commission-header">
                <div class="staff-info">
                    <div class="staff-avatar">
                        {{ strtoupper(substr($commission->staff->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1" style="font-weight: 600; color: var(--dark);">
                            {{ $commission->staff->name }}
                        </h5>
                            <small style="color: var(--gray); font-size: 0.75rem;">
                                by {{ $commission->approver->name }} • 
                                <span data-timestamp="{{ $commission->approved_at->timestamp }}">
                                    {{ $commission->approved_at->diffForHumans() }}
                                </span>
                            </small>
                    </div>
                </div>
                <span class="status-badge status-{{ $commission->status }}">
                    {{ ucfirst($commission->status) }}
                </span>
            </div>

            <div class="commission-body">
                <div>
                    <h6 style="font-weight: 600; color: var(--dark); margin-bottom: 0.75rem;">
                        <i class="fas fa-{{ 
                            $commission->service_type === 'car_wash' ? 'soap' : 
                            ($commission->service_type === 'detailing' ? 'sparkles' : 
                            ($commission->service_type === 'fuel_top_up' ? 'gas-pump' : 'tools'))
                        }} me-2 text-primary"></i>
                        {{ ucwords(str_replace('_', ' ', $commission->service_type)) }}
                    </h6>
                    <p style="color: var(--gray); font-size: 0.9375rem; margin-bottom: 0.5rem;">
                        {{ $commission->description }}
                    </p>
                    @if($commission->booking)
                        <small style="color: var(--info);">
                            <i class="fas fa-link me-1"></i>
                            Related to #{{ $commission->booking->booking_reference }}
                        </small>
                    @endif
                </div>

                <div class="text-center">
                    <div style="font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                        Amount
                    </div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: var(--success);">
                        RM {{ number_format($commission->amount, 2) }}
                    </div>
                </div>

                <div class="text-center">
                    <div style="font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                        Submitted
                    </div>
                    <div style="font-weight: 600; color: var(--dark);">
                        {{ $commission->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="text-center">
                    @if($commission->status !== 'pending')
                        <div style="font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                            Reviewed By
                        </div>
                        <div style="font-weight: 600; color: var(--dark); font-size: 0.875rem;">
                            {{ $commission->approver->name ?? 'N/A' }}
                        </div>
                        <small style="color: var(--gray);">
                            {{ $commission->approved_at ? $commission->approved_at->format('d M Y') : 'N/A' }}
                        </small>
                    @endif
                </div>
            </div>

            @if($commission->admin_notes)
                <div style="margin-top: 1.25rem; padding: 1rem; background: var(--light); border-left: 4px solid var(--info); border-radius: 8px;">
                    <strong style="font-size: 0.875rem; color: var(--dark);">
                        <i class="fas fa-comment me-1"></i>Admin Notes:
                    </strong>
                    <p class="mb-0 mt-1" style="font-size: 0.875rem; color: var(--gray);">
                        {{ $commission->admin_notes }}
                    </p>
                </div>
            @endif

            @if($commission->status === 'pending')
                <div class="action-buttons">
                    <button class="btn btn-action btn-approve" onclick="approveCommission({{ $commission->id }})">
                        <i class="fas fa-check me-2"></i>Approve
                    </button>
                    <button class="btn btn-action btn-reject" onclick="openRejectModal({{ $commission->id }})">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                </div>
            @endif
        </div>
    @empty
        <div style="text-align: center; padding: 4rem 2rem; background: var(--light); border-radius: 16px;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: var(--gray); opacity: 0.3; margin-bottom: 1.5rem;"></i>
            <h3 style="font-weight: 700; color: var(--dark);">No Commissions Found</h3>
            <p style="color: var(--gray);">There are no {{ request('status') ?? 'pending' }} commissions at the moment.</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($commissions->hasPages())
        <div class="mt-4">
            {{ $commissions->links() }}
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Reject Commission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label fw-semibold">
                            <i class="fas fa-comment me-1"></i>Rejection Reason
                        </label>
                        <textarea 
                            name="admin_notes" 
                            id="admin_notes" 
                            rows="4" 
                            class="form-control" 
                            placeholder="Explain why this commission is being rejected..."
                            required></textarea>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            This will be visible to the staff member
                        </small>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-2"></i>Reject Commission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function approveCommission(id) {
    if(confirm('Are you sure you want to approve this commission?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/commissions/${id}/approve`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

function openRejectModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = `/admin/commissions/${id}/reject`;
    modal.show();
}
</script>
@endsection