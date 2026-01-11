@extends('layouts.app')

@section('title', 'Rewards')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <!-- Header Section -->
            <div class="text-center mb-4">
                <h1 class="h2 fw-bold mb-2" style="color: #dc3545;">Stamp Rewards Program</h1>
                <p style="color: #8A8584;">Collect stamps with every 7-hour booking and earn exclusive discounts!</p>
            </div>

            <!-- Current Stamp Card -->
            <div class="card shadow border-0 mb-3" style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0" style="color: #dc3545;">Your Stamp Card</h5>
                        <span class="badge px-3 py-1" style="background: #dc3545; font-size: 0.9rem;">
                            {{ $currentStamps ?? 0 }} / 12 Stamps
                        </span>
                    </div>

                    <!-- Stamp Grid -->
                    <div class="row g-2 mb-3">
                        @for ($i = 1; $i <= 12; $i++)
                            <div class="col-4 col-sm-3 col-md-2">
                                <div class="stamp-wrapper text-center">
                                    @if ($i <= ($currentStamps ?? 0))
                                        <!-- Filled Stamp -->
                                        <div class="stamp-circle stamped">
                                            <svg width="60" height="60" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="45" fill="#dc3545" opacity="0.1"/>
                                                <circle cx="50" cy="50" r="40" fill="#dc3545"/>
                                                <path d="M30 50 L45 65 L70 35" stroke="white" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="stamp-number mt-1">{{ $i }}</div>
                                    @else
                                        <!-- Empty Stamp -->
                                        <div class="stamp-circle unstamped">
                                            <svg width="60" height="60" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="40" fill="none" stroke="#ffccd5" stroke-width="3" stroke-dasharray="5,5"/>
                                                <text x="50" y="58" text-anchor="middle" font-size="28" fill="#ffccd5" font-weight="bold">{{ $i }}</text>
                                            </svg>
                                        </div>
                                        <div class="stamp-number-empty mt-1">{{ $i }}</div>
                                    @endif
                                    
                                    <!-- Milestone Badges -->
                                    @if ($i == 3)
                                        <div class="milestone-badge badge-20">20%</div>
                                    @elseif ($i == 9)
                                        <div class="milestone-badge badge-30">30%</div>
                                    @elseif ($i == 12)
                                        <div class="milestone-badge badge-50">50%</div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-2" style="height: 20px; background: #ffe0e6;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); width: {{ (($currentStamps ?? 0) / 12) * 100 }}%"
                             role="progressbar" 
                             aria-valuenow="{{ $currentStamps ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="12">
                            <span class="fw-bold small">{{ round((($currentStamps ?? 0) / 12) * 100) }}%</span>
                        </div>
                    </div>

                    @if (($currentStamps ?? 0) >= 12)
                        <div class="alert text-center py-2 mb-0" style="background: #d4edda; border: 2px solid #28a745; color: #155724;" role="alert">
                            <h6 class="alert-heading mb-1">🎉 Congratulations!</h6>
                            <p class="mb-0 small">You've completed your stamp card! Redeem your 50% discount on your next booking.</p>
                        </div>
                    @elseif (($currentStamps ?? 0) >= 9)
                        <div class="alert text-center py-2 mb-0" style="background: #fff3cd; border: 2px solid #ffc107; color: #856404;" role="alert">
                            <h6 class="alert-heading mb-1">🌟 Almost There!</h6>
                            <p class="mb-0 small">You've unlocked 30% discount! {{ 12 - ($currentStamps ?? 0) }} more stamps to get 50% discount.</p>
                        </div>
                    @elseif (($currentStamps ?? 0) >= 3)
                        <div class="alert text-center py-2 mb-0" style="background: #d1ecf1; border: 2px solid #17a2b8; color: #0c5460;" role="alert">
                            <h6 class="alert-heading mb-1">✨ Congratulations!</h6>
                            <p class="mb-0 small">You've unlocked 20% discount! {{ 9 - ($currentStamps ?? 0) }} more stamps to get 30% discount.</p>
                        </div>
                    @else
                        <p class="text-center mb-0 small" style="color: #dc3545; font-weight: 500;">
                            {{ 3 - ($currentStamps ?? 0) }} more stamps to unlock your first discount!
                        </p>
                    @endif
                </div>
            </div>

            <!-- Rewards Tiers -->
            <div class="card shadow border-0 mb-3">
                <div class="card-body p-3">
                    <h5 class="mb-3" style="color: #dc3545;">Discount Rewards</h5>
                    <div class="row g-2">
                        <!-- Tier 1: 3 Stamps = 20% -->
                        <div class="col-md-4">
                            <div class="reward-card {{ ($currentStamps ?? 0) >= 3 ? 'unlocked' : 'locked' }}">
                                <div class="reward-icon">
                                    <svg width="50" height="50" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="35" fill="{{ ($currentStamps ?? 0) >= 3 ? '#dc3545' : '#e0e0e0' }}"/>
                                        <text x="50" y="62" text-anchor="middle" font-size="32" fill="white" font-weight="bold">3</text>
                                    </svg>
                                </div>
                                <h5 class="reward-title">20% Discount</h5>
                                <p class="reward-desc small">First 3 stamps</p>
                                <div class="reward-requirement">Each 7-hour booking = 1 stamp</div>
                                @if (($currentStamps ?? 0) >= 3)
                                    <span class="badge bg-success mt-2 px-2 py-1 small">✓ Unlocked</span>
                                @else
                                    <span class="badge bg-secondary mt-2 px-2 py-1 small">🔒 Locked</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tier 2: 9 Stamps = 30% -->
                        <div class="col-md-4">
                            <div class="reward-card {{ ($currentStamps ?? 0) >= 9 ? 'unlocked' : 'locked' }}">
                                <div class="reward-icon">
                                    <svg width="50" height="50" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="35" fill="{{ ($currentStamps ?? 0) >= 9 ? '#dc3545' : '#e0e0e0' }}"/>
                                        <text x="50" y="62" text-anchor="middle" font-size="32" fill="white" font-weight="bold">9</text>
                                    </svg>
                                </div>
                                <h5 class="reward-title">30% Discount</h5>
                                <p class="reward-desc small">Collected 9 stamps</p>
                                <div class="reward-requirement">Each 7-hour booking = 1 stamp</div>
                                @if (($currentStamps ?? 0) >= 9)
                                    <span class="badge bg-success mt-2 px-2 py-1 small">✓ Unlocked</span>
                                @else
                                    <span class="badge bg-secondary mt-2 px-2 py-1 small">🔒 Locked</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tier 3: 12 Stamps = 50% -->
                        <div class="col-md-4">
                            <div class="reward-card {{ ($currentStamps ?? 0) >= 12 ? 'unlocked' : 'locked' }} premium">
                                <div class="reward-icon">
                                    <svg width="50" height="50" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="35" fill="{{ ($currentStamps ?? 0) >= 12 ? '#dc3545' : '#e0e0e0' }}"/>
                                        <text x="50" y="62" text-anchor="middle" font-size="28" fill="white" font-weight="bold">12</text>
                                    </svg>
                                </div>
                                <h5 class="reward-title">50% Discount</h5>
                                <p class="reward-desc small">Complete stamp card! 🎉</p>
                                <div class="reward-requirement">Each 7-hour booking = 1 stamp</div>
                                @if (($currentStamps ?? 0) >= 12)
                                    <span class="badge bg-success mt-2 px-2 py-1 small">✓ Unlocked</span>
                                @else
                                    <span class="badge bg-secondary mt-2 px-2 py-1 small">🔒 Locked</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stamp History -->
            <div class="card shadow border-0 mb-3">
                <div class="card-body p-3">
                    <h5 class="mb-3" style="color: #dc3545;">Stamp History</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead style="background: #fff5f5;">
                                <tr>
                                    <th class="small">Date</th>
                                    <th class="small">Booking ID</th>
                                    <th class="small">Hours Booked</th>
                                    <th class="small">Stamps Earned</th>
                                    <th class="small">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stampHistory ?? [] as $history)
                                    <tr>
                                        <td class="small">{{ $history->created_at->format('d/m/Y') }}</td>
                                        <td class="small"><strong>#{{ $history->order_id }}</strong></td>
                                        <td class="small">{{ $history->hours }} hours</td>
                                        <td>
                                            <span class="badge px-2 py-1 small" style="background: #dc3545;">
                                                +{{ $history->stamps_earned }} stamps
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success px-2 py-1 small">{{ ucfirst($history->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3 small">
                                            No stamp history yet. Make a 7-hour booking to start collecting stamps!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="card shadow border-0" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe0e6 100%);">
                <div class="card-body p-3">
                    <h5 class="mb-3 text-center" style="color: #dc3545;">How It Works</h5>
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-2">
                            <div class="how-icon">⏰</div>
                            <h6 class="small fw-bold">Book 7 Hours</h6>
                            <p class="small text-muted mb-0">Each 7-hour booking counts</p>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="how-icon">🔴</div>
                            <h6 class="small fw-bold">Earn 1 Stamp</h6>
                            <p class="small text-muted mb-0">A red stamp is added</p>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="how-icon">📊</div>
                            <h6 class="small fw-bold">Collect Stamps</h6>
                            <p class="small text-muted mb-0">3, 9, or 12 stamps</p>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="how-icon">🎁</div>
                            <h6 class="small fw-bold">Get Discounts</h6>
                            <p class="small text-muted mb-0">20%, 30%, or 50%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stamp Wrapper */
.stamp-wrapper {
    position: relative;
}

/* Stamp Circle Styles */
.stamp-circle {
    display: inline-block;
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.stamp-circle.stamped {
    animation: stampPop 0.6s ease;
}

.stamp-circle.unstamped:hover svg circle {
    stroke: #ff8fa3;
    stroke-width: 4;
}

/* Stamp Numbers */
.stamp-number {
    font-weight: bold;
    color: #dc3545;
    font-size: 0.8rem;
}

.stamp-number-empty {
    font-weight: 600;
    color: #ffccd5;
    font-size: 0.8rem;
}

/* Milestone Badges */
.milestone-badge {
    position: absolute;
    top: -6px;
    right: 3px;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.65rem;
    font-weight: bold;
    color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.badge-20 {
    background: linear-gradient(135deg, #17a2b8, #138496);
}

.badge-30 {
    background: linear-gradient(135deg, #ffc107, #e0a800);
}

.badge-50 {
    background: linear-gradient(135deg, #28a745, #218838);
}

/* Reward Cards */
.reward-card {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
}

.reward-card.unlocked {
    border-color: #dc3545;
    background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
}

.reward-card.premium {
    background: linear-gradient(135deg, #fffbf0 0%, #fff5f5 100%);
}

.reward-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.2);
}

.reward-icon {
    margin-bottom: 10px;
}

.reward-title {
    color: #dc3545;
    font-weight: bold;
    margin-bottom: 8px;
    font-size: 1.1rem;
}

.reward-desc {
    color: #666;
    margin-bottom: 8px;
}

.reward-requirement {
    font-size: 0.75rem;
    color: #999;
    padding: 6px;
    background: #f8f9fa;
    border-radius: 6px;
}

/* How It Works Icons */
.how-icon {
    font-size: 2rem;
    margin-bottom: 8px;
}

/* Animations */
@keyframes stampPop {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    60% {
        transform: scale(1.2) rotate(10deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}

/* Progress Bar Animation */
.progress-bar {
    transition: width 1s ease-in-out;
}

/* Table Hover Effect */
.table-hover tbody tr:hover {
    background-color: #fff5f5;
}
</style>
@endsection