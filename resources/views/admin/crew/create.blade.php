@extends('admin.layouts.superadmin')

@section('title', 'Create Crew')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Block -->
    <div class="header-block bg-white p-4 rounded-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 text-primary">
                    <i class="fas fa-users me-2"></i>Create New Crew
                </h1>
                <p class="mb-0 text-muted">Fill in the details to register a new work team</p>
            </div>
        </div>
    </div>

    <!-- Form Block -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('superadmin.crew.store') }}">
                @csrf

                <div class="row g-4 mb-4">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Crew Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="Enter crew name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   placeholder="crew@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Company <span class="text-danger">*</span></label>
                            <input type="text" name="company" value="{{ old('company') }}" 
                                   class="form-control form-control-lg @error('company') is-invalid @enderror" 
                                   placeholder="Company name" required>
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" 
                                   class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                   placeholder="(123) 456-7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Toggle -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0"> {{-- fallback si no se marca --}}
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>



                    <!-- States Information -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label fw-bold">Operating States</label>

                            @php
                                $availableStates = [
                                    'AL' => 'Alabama',     'AK' => 'Alaska',       'AZ' => 'Arizona',      'AR' => 'Arkansas',
                                    'CA' => 'California',  'CO' => 'Colorado',     'CT' => 'Connecticut',  'DE' => 'Delaware',
                                    'FL' => 'Florida',     'GA' => 'Georgia',      'HI' => 'Hawaii',       'ID' => 'Idaho',
                                    'IL' => 'Illinois',    'IN' => 'Indiana',      'IA' => 'Iowa',         'KS' => 'Kansas',
                                    'KY' => 'Kentucky',    'LA' => 'Louisiana',    'ME' => 'Maine',        'MD' => 'Maryland',
                                    'MA' => 'Massachusetts','MI' => 'Michigan',    'MN' => 'Minnesota',    'MS' => 'Mississippi',
                                    'MO' => 'Missouri',    'MT' => 'Montana',      'NE' => 'Nebraska',     'NV' => 'Nevada',
                                    'NH' => 'New Hampshire','NJ' => 'New Jersey',  'NM' => 'New Mexico',   'NY' => 'New York',
                                    'NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio',       'OK' => 'Oklahoma',
                                    'OR' => 'Oregon',      'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                                    'SD' => 'South Dakota','TN' => 'Tennessee',    'TX' => 'Texas',        'UT' => 'Utah',
                                    'VT' => 'Vermont',     'VA' => 'Virginia',     'WA' => 'Washington',   'WV' => 'West Virginia',
                                    'WI' => 'Wisconsin',   'WY' => 'Wyoming'
                                ];

                                $selectedStates = old('states', $crew->states ?? []);
                            @endphp

                            <div class="row">
                                @foreach($availableStates as $code => $name)
                                    <div class="col-md-3 col-sm-4 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="states[]" value="{{ $code }}"
                                                id="state_{{ $code }}"
                                                {{ in_array($code, $selectedStates) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="state_{{ $code }}">{{ $name }} ({{ $code }})</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('states')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                    <a href="{{ route('superadmin.crew.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i> Save Crew
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .header-block {
        background-color: #f8fafc;
        border-left: 4px solid #3b7ddd;
    }
    
    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .card {
        border-radius: 0.5rem;
    }
    
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1.5rem;
    }
    
    .invalid-feedback {
        font-size: 0.875rem;
    }
</style>

<script>
    // Puedes agregar scripts adicionales aquí si necesitas validación en el cliente
    document.addEventListener('DOMContentLoaded', function() {
        // Ejemplo: Máscara para el teléfono
        if (document.querySelector('input[name="phone"]')) {
            IMask(document.querySelector('input[name="phone"]'), {
                mask: '(000) 000-0000'
            });
        }
    });
</script>
@endsection