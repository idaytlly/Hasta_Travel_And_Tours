<form action="{{ route('staff.bookings.inspection.return', $booking->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="vehicle_type" value="{{ $booking->car->category }}">

    <!-- Exterior Inspection -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-car me-2"></i>Exterior Inspection</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Exterior Condition</label>
            <select class="form-select" name="exterior_condition" required>
                <option value="">Select...</option>
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Exterior Cleanliness</label>
            <select class="form-select" name="exterior_cleanliness" required>
                <option value="">Select...</option>
                <option value="Clean">Clean</option>
                <option value="Slightly Dirty">Slightly Dirty</option>
                <option value="Very Dirty">Very Dirty</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">New Damages (if any)</label>
            <textarea class="form-control" name="exterior_damages" rows="2" placeholder="Describe any new scratches, dents, or damages..."></textarea>
        </div>
    </div>

    <!-- Interior Inspection -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-chair me-2"></i>Interior Inspection</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Interior Condition</label>
            <select class="form-select" name="interior_condition" required>
                <option value="">Select...</option>
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Interior Cleanliness</label>
            <select class="form-select" name="interior_cleanliness" required>
                <option value="">Select...</option>
                <option value="Clean">Clean</option>
                <option value="Slightly Dirty">Slightly Dirty</option>
                <option value="Very Dirty">Very Dirty</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">New Damages (if any)</label>
            <textarea class="form-control" name="interior_damages" rows="2" placeholder="Describe any new tears, stains, or damages..."></textarea>
        </div>
    </div>

    <!-- Mechanical Inspection -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-cog me-2"></i>Mechanical Inspection</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Engine</label>
            <select class="form-select" name="engine_condition" required>
                <option value="">Select...</option>
                <option value="Working">Working</option>
                <option value="Issues">Issues</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Brakes</label>
            <select class="form-select" name="brake_condition" required>
                <option value="">Select...</option>
                <option value="Working">Working</option>
                <option value="Issues">Issues</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tires</label>
            <select class="form-select" name="tire_condition" required>
                <option value="">Select...</option>
                <option value="Good">Good</option>
                <option value="Worn">Worn</option>
                <option value="Damaged">Damaged</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Lights</label>
            <select class="form-select" name="lights_condition" required>
                <option value="">Select...</option>
                <option value="Working">All Working</option>
                <option value="Some Issues">Some Issues</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Wipers</label>
            <select class="form-select" name="wipers_condition" required>
                <option value="">Select...</option>
                <option value="Working">Working</option>
                <option value="Issues">Issues</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Horn</label>
            <select class="form-select" name="horn_condition" required>
                <option value="">Select...</option>
                <option value="Working">Working</option>
                <option value="Not Working">Not Working</option>
            </select>
        </div>
    </div>

    <!-- Fluids & Levels -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-gas-pump me-2"></i>Fluids & Levels</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Fuel Level</label>
            <select class="form-select" name="fuel_level" required>
                <option value="">Select...</option>
                <option value="Full">Full</option>
                <option value="3/4">3/4</option>
                <option value="1/2">1/2</option>
                <option value="1/4">1/4</option>
                <option value="Empty">Empty</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Oil Level</label>
            <select class="form-select" name="oil_level" required>
                <option value="">Select...</option>
                <option value="Good">Good</option>
                <option value="Low">Low</option>
                <option value="Very Low">Very Low</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Coolant Level</label>
            <select class="form-select" name="coolant_level" required>
                <option value="">Select...</option>
                <option value="Good">Good</option>
                <option value="Low">Low</option>
                <option value="Very Low">Very Low</option>
            </select>
        </div>
    </div>

    <!-- Documents & Accessories -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-tools me-2"></i>Documents & Accessories</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="spare_tire" value="1">
                <label class="form-check-label">Spare Tire</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="jack" value="1">
                <label class="form-check-label">Jack</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="vehicle_manual" value="1">
                <label class="form-check-label">Vehicle Manual</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="first_aid_kit" value="1">
                <label class="form-check-label">First Aid Kit</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="fire_extinguisher" value="1">
                <label class="form-check-label">Fire Extinguisher</label>
            </div>
        </div>
    </div>

    <!-- For Motorcycles Only -->
    @if(in_array(strtolower($booking->car->category), ['motorcycle', 'bike']))
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-motorcycle me-2"></i>Motorcycle Specific</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Helmet Condition</label>
            <select class="form-select" name="helmet_condition">
                <option value="">Select...</option>
                <option value="Good">Good</option>
                <option value="Damaged">Damaged</option>
                <option value="Missing">Missing</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="side_mirrors" value="1">
                <label class="form-check-label">Side Mirrors (Both Working)</label>
            </div>
        </div>
    </div>
    @endif

    <!-- Mileage -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-tachometer-alt me-2"></i>Mileage</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Current Mileage Reading (km)</label>
            <input type="number" class="form-control" name="mileage_reading" required placeholder="e.g., 45350">
            @if($booking->pickupInspection)
            <small class="text-muted">Pickup mileage: {{ number_format($booking->pickupInspection->mileage_reading) }} km</small>
            @endif
        </div>
    </div>

    <!-- Upload Photos -->
    <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-camera me-2"></i>Inspection Photos</h6>
    <div class="mb-4">
        <label class="form-label">Upload Photos (Multiple allowed)</label>
        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
        <small class="text-muted">Take photos of vehicle exterior, interior, any new damages, and mileage reading</small>
    </div>

    <!-- Additional Notes -->
    <div class="mb-4">
        <label class="form-label">Additional Notes</label>
        <textarea class="form-control" name="notes" rows="3" placeholder="Any additional observations, damages, or issues found..."></textarea>
    </div>

    <button type="submit" class="btn btn-danger w-100 py-3">
        <i class="fas fa-check-circle me-2"></i>Complete Return Inspection & Close Booking
    </button>
</form>