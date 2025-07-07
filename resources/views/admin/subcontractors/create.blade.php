@extends('admin.layouts.superadmin')
@section('title', 'Create Subcontractor')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus text-primary me-2"></i>Create New Crew Manager
        </h1>
        <a href="{{ route('superadmin.subcontractors.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-body p-5">
            <form method="POST" action="{{ route('superadmin.subcontractors.store') }}">
                @csrf

                <div class="row g-4">
                    <!-- Personal Information Section -->
                    <div class="col-12">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-user-circle me-2"></i>Personal Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="form-control border-2 border-gray-300 rounded-3" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" 
                               class="form-control border-2 border-gray-300 rounded-3">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" 
                               class="form-control border-2 border-gray-300 rounded-3" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="form-control border-2 border-gray-300 rounded-3" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="form-control border-2 border-gray-300 rounded-3">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">State <span class="text-danger">*</span></label>
                        <input type="text" name="state" value="{{ old('state') }}" 
                               class="form-control border-2 border-gray-300 rounded-3" required>
                    </div>

                    <!-- Roofing Specialties Section -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-home me-2"></i>Roofing Specialties
                        </h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Residential Roof Types</label>
                        <div class="card border-0 bg-light p-3">
                            <div class="row g-2">
                                @foreach(['TPO', 'Low Slope', 'Tile', 'Wood Shakes', 'Asphalt Shingle', 'Metal'] as $roof)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="residential_roof_types[]" 
                                                   value="{{ $roof }}" id="res_{{ $roof }}" 
                                                   {{ in_array($roof, old('residential_roof_types', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="res_{{ $roof }}">{{ $roof }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Commercial Roof Types</label>
                        <div class="card border-0 bg-light p-3">
                            <div class="row g-2">
                                @foreach(['EPDM', 'Asphalt Shingle', 'Low Slope', 'TPO', 'Tar & Gravel', 'Metal'] as $roof)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="commercial_roof_types[]" 
                                                   value="{{ $roof }}" id="com_{{ $roof }}"
                                                   {{ in_array($roof, old('commercial_roof_types', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="com_{{ $roof }}">{{ $roof }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Work Locations Section -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-map-marked-alt me-2"></i>Work Locations
                        </h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">States You Can Work</label>
                        @php
                            $selectedStates = old('states_you_can_work', []);
                            $allStates = ['Texas', 'Florida', 'California', 'New York', 'Illinois', 'Arizona', 'Nevada', 'Colorado', 'Georgia', 'North Carolina'];
                        @endphp
                        <select name="states_you_can_work[]" class="form-select select2-multiple" multiple="multiple" data-placeholder="Select states...">
                            @foreach($allStates as $state)
                                <option value="{{ $state }}" {{ in_array($state, $selectedStates) ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="form-check mt-3">
                            <input type="checkbox" name="all_states" id="all_states" class="form-check-input" value="1"
                                   {{ old('all_states') ? 'checked' : '' }}>
                            <label for="all_states" class="form-check-label">I can work in all states</label>
                        </div>
                    </div>

                    <!-- Account Settings Section -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-lock me-2"></i>Account Settings
                        </h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control border-2 border-gray-300 rounded-3">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control border-2 border-gray-300 rounded-3">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                            <label for="is_active" class="form-check-label">Active account</label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-5 pt-4 border-top">
                    <button type="reset" class="btn btn-light me-3">
                        <i class="fas fa-undo me-1"></i> Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Save Crew Manager
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for state selection
        $('.select2-multiple').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false
        });

        // Toggle password visibility
        $('.toggle-password').click(function() {
            const input = $(this).siblings('input');
            const icon = $(this).find('i');
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });

        // Handle "all states" checkbox
        $('#all_states').change(function() {
            const select = $('select[name="states_you_can_work[]"]');
            if ($(this).is(':checked')) {
                select.val(null).trigger('change').prop('disabled', true);
            } else {
                select.prop('disabled', false);
            }
        }).trigger('change');
    });
</script>
@endsection

<style>
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        transition: border-color 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-check-input:checked {
        background-color: #3b7ddd;
        border-color: #3b7ddd;
    }
    
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
    }
    
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 0.375rem 0.75rem;
    }
    
    .toggle-password {
        border-left: none;
    }
    
    .toggle-password:hover {
        background-color: #e9ecef;
    }
</style>
@endsection