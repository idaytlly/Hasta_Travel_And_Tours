{{-- 
    Booking Timeline Component
    Usage: @include('components.booking-timeline', ['currentStep' => 1, 'bookingStatus' => 'pending'])
    
    Steps:
    1 - Booking Details (creating booking)
    2 - Complete Payment (payment page)
    3 - Pending Approval (waiting for admin approval)
    4 - Pick Up Car (with inspection form)
    5 - Drop Off Car (with inspection form)
--}}

@php
    $steps = [
        1 => ['title' => 'Booking Details', 'desc' => 'Fill in rental information'],
        2 => ['title' => 'Complete Payment', 'desc' => 'Upload payment proof'],
        3 => ['title' => 'Pending Approval', 'desc' => 'Waiting for confirmation'],
        4 => ['title' => 'Pick Up Car', 'desc' => 'Vehicle inspection & handover'],
        5 => ['title' => 'Drop Off Car', 'desc' => 'Return & final inspection'],
    ];
    
    // Determine current step based on booking status if not explicitly set
    if (!isset($currentStep)) {
        $currentStep = match($bookingStatus ?? 'pending') {
            'pending' => $booking->payments->isEmpty() ? 2 : 3,
            'confirmed' => 4,
            'active' => 4,
            'completed' => 5,
            default => 1
        };
    }
@endphp

<style>
    .booking-timeline {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    
    .timeline-container {
        display: flex;
        justify-content: space-between;
        align-items: start;
        position: relative;
        padding: 0 20px;
    }
    
    .timeline-line {
        position: absolute;
        top: 20px;
        left: 50px;
        right: 50px;
        height: 3px;
        background: #e5e7eb;
        z-index: 0;
    }
    
    .timeline-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        transition: width 0.5s ease;
        border-radius: 2px;
    }
    
    .timeline-step {
        position: relative;
        z-index: 1;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        background: #f3f4f6;
        border: 3px solid #e5e7eb;
        transition: all 0.3s ease;
        margin-bottom: 8px;
        position: relative;
        color: #6b7280;
    }
    
    .timeline-step.completed .step-circle {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }
    
    .timeline-step.active .step-circle {
        background: linear-gradient(135deg, #d93025, #b71c1c);
        border-color: #d93025;
        box-shadow: 0 4px 12px rgba(217, 48, 37, 0.3);
        animation: pulse 2s infinite;
        color: white;
    }
    
    .timeline-step.pending .step-circle {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    
    .step-checkmark {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        font-weight: bold;
        border: 2px solid white;
    }
    
    .step-content {
        max-width: 140px;
    }
    
    .step-title {
        font-size: 14px;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 4px;
        transition: color 0.3s ease;
    }
    
    .timeline-step.completed .step-title,
    .timeline-step.active .step-title {
        color: #111;
    }
    
    .step-desc {
        font-size: 12px;
        color: #9ca3af;
        line-height: 1.4;
    }
    
    .timeline-step.active .step-desc {
        color: #6b7280;
        font-weight: 500;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 12px rgba(217, 48, 37, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(217, 48, 37, 0.5);
        }
    }
    
    @media (max-width: 1024px) {
        .timeline-container {
            flex-direction: column;
            align-items: stretch;
            padding: 0;
        }
        
        .timeline-line {
            display: none;
        }
        
        .timeline-step {
            flex-direction: row;
            text-align: left;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .timeline-step:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .step-circle {
            margin-right: 12px;
            margin-bottom: 0;
            width: 40px;
            height: 40px;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .step-content {
            max-width: none;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    }
</style>

<div class="booking-timeline">
    <div class="timeline-container">
        <div class="timeline-line">
            <div class="timeline-progress" style="width: {{ ($currentStep - 1) * 25 }}%"></div>
        </div>
        
        @foreach($steps as $stepNum => $step)
            <div class="timeline-step {{ $stepNum < $currentStep ? 'completed' : ($stepNum == $currentStep ? 'active' : 'pending') }}">
                <div class="step-circle">
                    <span>{{ $stepNum }}</span>
                    @if($stepNum < $currentStep)
                        <div class="step-checkmark">✓</div>
                    @endif
                </div>
                <div class="step-content">
                    <div class="step-title">{{ $step['title'] }}</div>
                    <div class="step-desc">{{ $step['desc'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>