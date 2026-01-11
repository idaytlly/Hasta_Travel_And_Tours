@extends('layouts.app')

@section('title', 'Rewards')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <!-- Header Section -->
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold mb-2" style="color: #dc3545;">Stamp Rewards Program</h1>
                <p class="mb-0" style="color: #8A8584;">Collect stamps with every 7-hour booking and earn exclusive discounts!</p>
            </div>

            <!-- Current Stamp Card -->
            <div class="stamp-card-section mb-4 p-4" style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold" style="color: #dc3545;">Your Stamp Card</h5>
                    <span class="badge px-3 py-2" style="background: #dc3545; font-size: 1rem;">
                        {{ $currentStamps ?? 0 }} / 12 Stamps
                    </span>
                </div>

                <!-- Stamp Grid -->
                <div class="row g-3 mb-3">
                    @for ($i = 1; $i <= 12; $i++)
                        <div class="col-4 col-sm-3 col-md-2">
                            <div class="stamp-wrapper text-center">
                                @if ($i <= ($currentStamps ?? 0))
                                    <!-- Filled Stamp -->
                                    <div class="stamp-circle stamped">
                                        <svg width="65" height="65" viewBox="0 0 100 100">
                                            <!-- Outer circle -->
                                            <circle cx="50" cy="50" r="45" fill="none" stroke="#dc3545" stroke-width="4" opacity="0.8"/>
                                            <!-- Inner circle -->
                                            <circle cx="50" cy="50" r="35" fill="none" stroke="#dc3545" stroke-width="2" opacity="0.6"/>
                                            
                                            <!-- Rotated rectangle background -->
                                            <rect x="15" y="40" width="70" height="20" fill="#dc3545" transform="rotate(-15 50 50)" opacity="0.9"/>
                                            
                                            <!-- HASTA text -->
                                            <text x="50" y="55" text-anchor="middle" font-size="16" fill="white" font-weight="bold" font-family="Arial, sans-serif" transform="rotate(-15 50 50)" letter-spacing="2">HASTA</text>
                                        </svg>
                                    </div>
                                    <div class="stamp-number mt-1">{{ $i }}</div>
                                @else
                                    <!-- Empty Stamp -->
                                    <div class="stamp-circle unstamped">
                                        <svg width="65" height="65" viewBox="0 0 100 100">
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
                <div class="progress mb-3" style="height: 25px; background: #ffe0e6; border-radius: 15px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         style="background: linear-gradient(90deg, #dc3545 0%, #c82333 100%); width: {{ (($currentStamps ?? 0) / 12) * 100 }}%; border-radius: 15px;"
                         role="progressbar" 
                         aria-valuenow="{{ $currentStamps ?? 0 }}" 
                         aria-valuemin="0" 
                         aria-valuemax="12">
                        <span class="fw-bold">{{ round((($currentStamps ?? 0) / 12) * 100) }}%</span>
                    </div>
                </div>

                @if (($currentStamps ?? 0) >= 12)
                    <div class="text-center">
                        <p class="mb-1" style="color: #28a745; font-weight: 600; font-size: 1.1rem;">
                            🎉 Congratulations!
                        </p>
                        <p class="mb-0" style="color: #28a745;">
                            You've completed your stamp card! Redeem your 50% discount on your next booking.
                        </p>
                    </div>
                @elseif (($currentStamps ?? 0) >= 9)
                    <div class="text-center">
                        <p class="mb-1" style="color: #ffc107; font-weight: 600; font-size: 1.1rem;">
                            🌟 Almost There!
                        </p>
                        <p class="mb-0" style="color: #856404;">
                            You've unlocked 30% discount! {{ 12 - ($currentStamps ?? 0) }} more stamps to get 50% discount.
                        </p>
                    </div>
                @elseif (($currentStamps ?? 0) >= 3)
                    <div class="text-center">
                        <p class="mb-1" style="color: #17a2b8; font-weight: 600; font-size: 1.1rem;">
                            ✨ Congratulations!
                        </p>
                        <p class="mb-0" style="color: #0c5460;">
                            You've unlocked 20% discount! {{ 9 - ($currentStamps ?? 0) }} more stamps to get 30% discount.
                        </p>
                    </div>
                @else
                    <p class="text-center mb-0" style="color: #dc3545; font-weight: 600; font-size: 1rem;">
                        {{ 3 - ($currentStamps ?? 0) }} more stamps to unlock your first discount!
                    </p>
                @endif
            </div>

            <!-- Rewards Tiers -->
            <div class="rewards-section mb-4">
                <h5 class="mb-3 fw-bold" style="color: #dc3545;">Discount Rewards</h5>
                <div class="row g-3">
                    <!-- Tier 1: 3 Stamps = 20% -->
                    <div class="col-md-4">
                        <div class="reward-item {{ ($currentStamps ?? 0) >= 3 ? 'unlocked' : 'locked' }}">
                            <div class="reward-icon">
                                <svg width="55" height="55" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="35" fill="{{ ($currentStamps ?? 0) >= 3 ? '#dc3545' : '#e0e0e0' }}"/>
                                    <text x="50" y="62" text-anchor="middle" font-size="32" fill="white" font-weight="bold">3</text>
                                </svg>
                            </div>
                            <h5 class="reward-title">20% Discount</h5>
                            <p class="reward-desc">First 3 stamps</p>
                            <div class="reward-requirement">Each 7-hour booking = 1 stamp</div>
                            @if (($currentStamps ?? 0) >= 3)
                                <span class="mt-2" style="background: rgba(40, 167, 69, 0.1); color: #28a745; font-weight: 600; padding: 6px 16px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">✓ Unlocked</span>
                            @else
                                <span class="mt-2" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; font-weight: 600; padding: 6px 16px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">🔒 Locked</span>
                            @endif
                        </div>
                    </div>

                    <!-- Tier 2: 9 Stamps = 30% -->
                    <div class="col-md-4">
                        <div class="reward-item {{ ($currentStamps ?? 0) >= 9 ? 'unlocked' : 'locked' }}">
                            <div class="reward-icon">
                                <svg width="55" height="55" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="35" fill="{{ ($currentStamps ?? 0) >= 9 ? '#dc3545' : '#e0e0e0' }}"/>
                                    <text x="50" y="62" text-anchor="middle" font-size="32" fill="white" font-weight="bold">9</text>
                                </svg>
                            </div>
                            <h5 class="reward-title">30% Discount</h5>
                            <p class="reward-desc">Collected 9 stamps</p>
                            <div class="reward-requirement">Each 7-hour booking = 1 stamp</div>
                            @if (($currentStamps ?? 0) >= 9)
                                <span class="mt-2" style="background: rgba(40, 167, 69, 0.1); color: #28a745; font-weight: 600; padding: 6px 16px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">✓ Unlocked</span>
                            @else
                                <span class="mt-2" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; font-weight: 600; padding: 6px 16px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">🔒 Locked</span>
                            @endif
                        </div>
                    </div>

                    <!-- Tier 3: 12 Stamps = 50% -->
                    <div class="col-md-4">
                        <div class="reward-item premium {{ ($currentStamps ?? 0) >= 12 ? 'unlocked' : 'locked' }}">
                            <div class="reward-icon">
                                <svg width="55" height="55" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="35" fill="{{ ($currentStamps ?? 0) >= 12 ? '#dc3545' : '#e0e0e0' }}"/>
                                    <text x="50" y="62" text-anchor="middle" font-size="28" fill="white" font-weight="bold">12</text>
                                </svg>
                            </div>
                            <h5 class="reward-title">50% Discount</h5>
                            <p class="reward-desc">Complete stamp card! 🎉</p>
                            <div class="reward-requirement">Each 7-hour booking = 1 stamp</div>
                            @if (($currentStamps ?? 0) >= 12)
                                <span class="mt-2" style="background: rgba(40, 167, 69, 0.1); color: #28a745; font-weight: 600; padding: 6px 16px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">✓ Unlocked</span>
                            @else
                                <span class="mt-2" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; font-weight: 600; padding: 6px 16px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">🔒 Locked</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stamp History -->
            <div class="history-section mb-4">
                <h5 class="mb-3 fw-bold" style="color: #dc3545;">Stamp History</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fff5f5;">
                            <tr>
                                <th>Date</th>
                                <th>Booking ID</th>
                                <th>Hours Booked</th>
                                <th>Stamps Earned</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stampHistory ?? [] as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d/m/Y') }}</td>
                                    <td><strong>#{{ $history->order_id }}</strong></td>
                                    <td>{{ $history->hours }} hours</td>
                                    <td>
                                        <span style="background: rgba(220, 53, 69, 0.1); color: #dc3545; font-weight: 600; padding: 4px 12px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">
                                            +{{ $history->stamps_earned }} stamp{{ $history->stamps_earned > 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="background: {{ strtolower($history->status) == 'completed' ? 'rgba(40, 167, 69, 0.1)' : 'rgba(255, 193, 7, 0.1)' }}; color: {{ strtolower($history->status) == 'completed' ? '#28a745' : '#ffc107' }}; font-weight: 600; padding: 4px 12px; border-radius: 6px; display: inline-block; font-size: 0.875rem;">{{ ucfirst($history->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No stamp history yet. Make a 7-hour booking to start collecting stamps!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="how-it-works-section p-4" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe0e6 100%); border-radius: 15px;">
                <h5 class="mb-4 text-center fw-bold" style="color: #dc3545;">How It Works</h5>
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="how-icon">⏰</div>
                        <h6 class="fw-bold">Book 7 Hours</h6>
                        <p class="small text-muted mb-0">Each 7-hour booking counts</p>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="how-icon">🔴</div>
                        <h6 class="fw-bold">Earn 1 Stamp</h6>
                        <p class="small text-muted mb-0">A red stamp is added</p>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="how-icon">📊</div>
                        <h6 class="fw-bold">Collect Stamps</h6>
                        <p class="small text-muted mb-0">3, 9, or 12 stamps</p>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="how-icon">🎁</div>
                        <h6 class="fw-bold">Get Discounts</h6>
                        <p class="small text-muted mb-0">20%, 30%, or 50%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stamp Card Section */
.stamp-card-section {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

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
    font-size: 0.85rem;
}

.stamp-number-empty {
    font-weight: 600;
    color: #ffccd5;
    font-size: 0.85rem;
}

/* Milestone Badges */
.milestone-badge {
    position: absolute;
    top: -6px;
    right: 3px;
    padding: 3px 7px;
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

/* Status Messages - removed, now just colored text */

/* Reward Items */
.reward-item {
    background: white;
    border: 3px solid #e0e0e0;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
}

.reward-item.unlocked {
    border-color: #dc3545;
    background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
}

.reward-item.premium {
    background: linear-gradient(135deg, #fffbf0 0%, #fff5f5 100%);
}

.reward-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(220, 53, 69, 0.2);
}

.reward-icon {
    margin-bottom: 12px;
}

.reward-title {
    color: #dc3545;
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 1.2rem;
}

.reward-desc {
    color: #666;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.reward-requirement {
    font-size: 0.8rem;
    color: #999;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 8px;
}

/* How It Works Icons */
.how-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.how-it-works-section {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

/* Table Styles */
.table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.table-hover tbody tr:hover {
    background-color: #fff5f5;
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
</style>
@endsection