@extends('admin.layouts.superadmin')

@section('title', 'Edit Crew Manager')

@section('content')
<div class="container-fluid px-4">
    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit text-primary me-2"></i>Edit Crew Manager
        </h1>
        <a href="{{ route('superadmin.subcontractors.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-body p-5">
            <form method="POST" action="{{ route('superadmin.subcontractors.update', $subcontractor->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Personal Information Section -->
                    <div class="col-12">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-user-circle me-2"></i>Personal Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('company_name', $subcontractor->company_name) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">State <span class="text-danger">*</span></label>
                        <input type="text" name="state" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('state', $subcontractor->state) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('name', $subcontractor->name) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Name</label>
                        <input type="text" name="last_name" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('last_name', $subcontractor->last_name) }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('email', $subcontractor->email) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control border-2 border-gray-300 rounded-3" 
                               value="{{ old('phone', $subcontractor->phone) }}">
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
                                @php
                                    $residential = old('residential_roof_types', $subcontractor->residential_roof_types ?? []);
                                @endphp
                                @foreach(['TPO', 'Low Slope', 'Tile', 'Wood Shakes', 'Asphalt Shingle', 'Metal'] as $roof)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="residential_roof_types[]" 
                                                   value="{{ $roof }}" id="res_{{ $roof }}" 
                                                   {{ in_array($roof, $residential) ? 'checked' : '' }}>
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
                                @php
                                    $commercial = old('commercial_roof_types', $subcontractor->commercial_roof_types ?? []);
                                @endphp
                                @foreach(['EPDM', 'Asphalt Shingle', 'Low Slope', 'TPO', 'Tar & Gravel', 'Metal'] as $roof)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="commercial_roof_types[]" 
                                                   value="{{ $roof }}" id="com_{{ $roof }}"
                                                   {{ in_array($roof, $commercial) ? 'checked' : '' }}>
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
                            $statesAvailable = ['Texas', 'Florida', 'California', 'New York', 'Illinois', 'Arizona', 'Nevada', 'Colorado', 'Georgia', 'North Carolina'];
                            $statesSelected = old('states_you_can_work', $subcontractor->states_you_can_work ?? []);
                        @endphp
                        <select name="states_you_can_work[]" class="form-select select2-multiple" multiple="multiple" data-placeholder="Select states...">
                            @foreach($statesAvailable as $state)
                                <option value="{{ $state }}" {{ in_array($state, $statesSelected) ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="form-check mt-3">
                            <input type="checkbox" name="all_states" id="all_states" class="form-check-input" value="1"
                                   {{ old('all_states', $subcontractor->all_states ?? false) ? 'checked' : '' }}>
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
                        <label class="form-label fw-bold">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control border-2 border-gray-300 rounded-3" 
                                   placeholder="Leave blank to keep current password">
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>
                    
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                                   {{ old('is_active', $subcontractor->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active account</label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-5 pt-4 border-top">
                    <button type="reset" class="btn btn-light me-3">
                        <i class="fas fa-undo me-1"></i> Reset Changes
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Update Crew Manager
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection