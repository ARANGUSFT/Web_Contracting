@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i> Back to Leads
        </a>
        <h2 class="mb-0 text-primary">
            <i class="bi bi-pencil-square me-2"></i> Edit Lead
        </h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="card shadow-lg border-0">
        <form action="{{ route('leads.update', $lead->id) }}" method="POST" enctype="multipart/form-data" id="leadForm">
            @csrf
            @method('PUT')

            <div class="card-body p-0">
                <!-- Lead Information Section -->
                <div class="card border-0 rounded-0">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 text-dark">
                            <i class="bi bi-person-badge me-2"></i> Lead Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="estado" value="1">
                        
                        <div class="row g-3">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-semibold">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="{{ old('first_name', $lead->first_name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-semibold">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="{{ old('last_name', $lead->last_name) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label for="company_name" class="form-label fw-semibold">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       value="{{ old('company_name', $lead->company_name) }}">
                            </div>

                            <div class="col-md-12">
                                <label for="cross_reference" class="form-label fw-semibold">Cross Reference</label>
                                <input type="text" class="form-control" id="cross_reference" name="cross_reference" 
                                       value="{{ old('cross_reference', $lead->cross_reference) }}">
                            </div>

                            <!-- Job Information -->
                            <div class="col-md-4">
                                <label for="job_category" class="form-label fw-semibold">Job Category</label>
                                <select class="form-select" id="job_category" name="job_category">
                                    <option value="">Select Category</option>
                                    <option value="Commercial" {{ old('job_category', $lead->job_category) == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="Property Management" {{ old('job_category', $lead->job_category) == 'Property Management' ? 'selected' : '' }}>Property Management</option>
                                    <option value="Residential" {{ old('job_category', $lead->job_category) == 'Residential' ? 'selected' : '' }}>Residential</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="work_type" class="form-label fw-semibold">Work Type</label>
                                <select class="form-select" id="work_type" name="work_type">
                                    <option value="">Select Work Type</option>
                                    <option value="Inspection" {{ old('work_type', $lead->work_type) == 'Inspection' ? 'selected' : '' }}>Inspection</option>
                                    <option value="Insurance" {{ old('work_type', $lead->work_type) == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                                    <option value="New" {{ old('work_type', $lead->work_type) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Repair" {{ old('work_type', $lead->work_type) == 'Repair' ? 'selected' : '' }}>Repair</option>
                                    <option value="Retail" {{ old('work_type', $lead->work_type) == 'Retail' ? 'selected' : '' }}>Retail</option>
                                    <option value="Service" {{ old('work_type', $lead->work_type) == 'Service' ? 'selected' : '' }}>Service</option>
                                    <option value="Warranty" {{ old('work_type', $lead->work_type) == 'Warranty' ? 'selected' : '' }}>Warranty</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="job_trades" class="form-label fw-semibold">Trade Type</label>
                                <select class="form-select" id="job_trades" name="job_trades">
                                    <option value="">Select Trade Type</option>
                                    <option value="Gutters" {{ old('job_trades', $lead->job_trades ?? '') == 'Gutters' ? 'selected' : '' }}>Gutters</option>
                                    <option value="Roofing" {{ old('job_trades', $lead->job_trades ?? '') == 'Roofing' ? 'selected' : '' }}>Roofing</option>
                                    <option value="Siding" {{ old('job_trades', $lead->job_trades ?? '') == 'Siding' ? 'selected' : '' }}>Siding</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Address Information -->
                <div class="card border-0 rounded-0 border-top">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 text-dark">
                            <i class="bi bi-geo-alt me-2"></i> Contact & Address Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Contact Information -->
                            <div class="col-md-6">
                                <label for="lead_source" class="form-label fw-semibold">Lead Source</label>
                                <select class="form-select" id="lead_source" name="lead_source">
                                    <option value="" hidden>Select Lead Source</option>
                                    @foreach([
                                        '0' => 'Canvasser',
                                        '1' => 'Direct Mailings',
                                        '2' => 'Door hanger',
                                        '3' => 'Door Knocking',
                                        '4' => 'Internet',
                                        '5' => 'K104',
                                        '6' => 'Newspaper',
                                        '7' => 'Other',
                                        '8' => 'Phonebook',
                                        '9' => 'Previous Customer',
                                        '10' => 'Radio',
                                        '11' => 'Referral',
                                        '12' => 'Telemarketing',
                                        '13' => 'Truck',
                                        '14' => 'Yard Sign'
                                    ] as $value => $label)
                                        <option value="{{ $value }}" {{ old('lead_source', $lead->lead_source) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Phone Information -->
                            <div class="col-md-3">
                                <label for="phone" class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="(555) 123-4567" value="{{ old('phone', $lead->phone) }}" required>
                            </div>

                            <div class="col-md-2">
                                <label for="phone_ext" class="form-label fw-semibold">Ext</label>
                                <input type="text" class="form-control" id="phone_ext" name="phone_ext" 
                                       value="{{ old('phone_ext', $lead->phone_ext) }}">
                            </div>

                            <div class="col-md-3">
                                <label for="phone_type" class="form-label fw-semibold">Type</label>
                                <select id="phone_type" name="phone_type" class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="home" {{ old('phone_type', $lead->phone_type) == "home" ? 'selected' : '' }}>Home</option>
                                    <option value="mobile" {{ old('phone_type', $lead->phone_type) == "mobile" ? 'selected' : '' }}>Mobile</option>
                                    <option value="work" {{ old('phone_type', $lead->phone_type) == "work" ? 'selected' : '' }}>Work</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="{{ old('email', $lead->email) }}">
                            </div>

                            <!-- Location Address -->
                            <div class="col-12 mt-4">
                                <h6 class="border-bottom pb-2 text-dark">
                                    <i class="bi bi-house me-2"></i> Location Address
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label for="street" class="form-label fw-semibold">
                                    Street <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="street" name="street" class="form-control" 
                                       value="{{ old('street', $lead->street) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="suite" class="form-label fw-semibold">Suite/Apt/Unit</label>
                                <input type="text" id="suite" name="suite" class="form-control" 
                                       value="{{ old('suite', $lead->suite) }}">
                            </div>

                            <div class="col-md-3">
                                <label for="city" class="form-label fw-semibold">
                                    City <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="city" name="city" class="form-control" 
                                       value="{{ old('city', $lead->city) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label for="state" class="form-label fw-semibold">
                                    State <span class="text-danger">*</span>
                                </label>
                                <select id="state" name="state" class="form-select" required>
                                    <option value="">Choose State</option>
                                    @foreach(['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'DC', 'WV', 'WI', 'WY'] as $state)
                                        <option value="{{ $state }}" {{ old('state', $lead->state) == $state ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="zip" class="form-label fw-semibold">
                                    Zip <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="zip" name="zip" class="form-control" 
                                       value="{{ old('zip', $lead->zip) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label for="country" class="form-label fw-semibold">Country</label>
                                <input type="text" id="country" name="country" class="form-control" 
                                       value="{{ old('country', $lead->country ?? 'US') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

         <div class="mb-4">
    <label class="form-label fw-semibold">Location Photo</label>
    <div class="drop-zone" id="drop-location" onclick="document.getElementById('location_photo').click();">
        <div class="drop-zone-content">
            <i class="bi bi-cloud-arrow-up display-4 text-muted mb-3"></i>
            <p class="drop-zone-text mb-2">Drop photo here or click to select</p>
            <small class="text-muted">Supports JPG, PNG, WEBP • Max 10MB</small>
        </div>
        <input type="file" id="location_photo" name="location_photo" class="d-none" 
               accept="image/*" onchange="previewLocationImage(event)">
    </div>

    {{-- 🔹 Campo hidden para marcar eliminación --}}
    <input type="hidden" name="remove_location_photo" id="remove_location_photo" value="0">

    @php
        // Siempre trabajar como array
        $locationPhotos = $lead->location_photo ?? [];
        if (!is_array($locationPhotos)) {
            $locationPhotos = [$locationPhotos];
        }
        $lastPhoto = count($locationPhotos) ? end($locationPhotos) : null;
    @endphp
    
    <!-- Preview Container -->
    <div class="preview-container mt-3">
        @if($lastPhoto)
            <div class="preview-card">
                <img src="{{ asset('storage/' . $lastPhoto) }}?t={{ time() }}" 
                     alt="Location Photo" class="preview-img">
                <div class="preview-actions">
                    <a href="{{ asset('storage/' . $lastPhoto) }}" 
                       download class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i> Download
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeLocationPhoto()">
                        <i class="bi bi-trash me-1"></i> Remove
                    </button>
                </div>
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-image display-4 opacity-25"></i>
                <p class="mt-2 mb-0">No location photo uploaded</p>
            </div>
        @endif
    </div>
</div>


                <!-- Insurance Information -->
                <div class="card border-0 rounded-0 border-top">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 text-dark">
                            <i class="bi bi-shield-check me-2"></i> Insurance Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="insurance_company" class="form-label fw-semibold">Insurance Company</label>
                                <select class="form-select" id="insurance_company" name="insurance_company">
                                    <option value="">Choose Insurance Company</option>
                                    @foreach([
                                        'Allstate', 'American Family', 'Farm Bureau', 'Farmers', 
                                        'Liberty Mutual', 'Nationwide', 'Other', 'Safeco', 
                                        'State Farm', 'Travelers', 'USAA'
                                    ] as $company)
                                        <option value="{{ $company }}" {{ old('insurance_company', $lead->insurance_company) == $company ? 'selected' : '' }}>
                                            {{ $company }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="damage_location" class="form-label fw-semibold">Damage Location</label>
                                <input type="text" class="form-control" id="damage_location" name="damage_location" 
                                       value="{{ old('damage_location', $lead->damage_location) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="date_of_loss" class="form-label fw-semibold">Date of Loss</label>
                                <input type="date" class="form-control" id="date_of_loss" name="date_loss" 
                                       value="{{ old('date_loss', !empty($lead->date_loss) ? \Carbon\Carbon::parse($lead->date_loss)->format('Y-m-d') : '') }}">
                            </div>

                            <div class="col-md-4">
                                <label for="claim_number" class="form-label fw-semibold">Claim Number</label>
                                <input type="text" class="form-control" id="claim_number" name="claim_number" 
                                       value="{{ old('claim_number', $lead->claim_number) }}">
                            </div>

                            <!-- Adjuster Information -->
                            <div class="col-12 mt-4">
                                <h6 class="border-bottom pb-2 text-dark">
                                    <i class="bi bi-person-badge me-2"></i> Adjuster Information
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label for="adjuster_phone" class="form-label fw-semibold">Adjuster Phone</label>
                                <input type="tel" class="form-control" id="adjuster_phone" name="adjuster_phone" 
                                       placeholder="(555) 123-4567" value="{{ old('adjuster_phone', $lead->adjuster_phone) }}">
                            </div>

                            <div class="col-md-2">
                                <label for="adjuster_ext" class="form-label fw-semibold">Ext</label>
                                <input type="text" class="form-control" id="adjuster_ext" name="adjuster_ext" 
                                       value="{{ old('adjuster_ext', $lead->adjuster_ext) }}">
                            </div>

                            <div class="col-md-3">
                                <label for="adjuster_phone_type" class="form-label fw-semibold">Phone Type</label>
                                <select class="form-select" id="adjuster_phone_type" name="adjuster_phone_type">
                                    <option value="">Choose Type</option>
                                    <option value="home" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'home' ? 'selected' : '' }}>Home</option>
                                    <option value="mobile" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                    <option value="work" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'work' ? 'selected' : '' }}>Work</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="adjuster_fax" class="form-label fw-semibold">Adjuster Fax</label>
                                <input type="tel" class="form-control" id="adjuster_fax" name="adjuster_fax" 
                                       placeholder="(555) 123-4567" value="{{ old('adjuster_fax', $lead->adjuster_fax) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="adjuster_email" class="form-label fw-semibold">Adjuster Email</label>
                                <input type="email" class="form-control" id="adjuster_email" name="adjuster_email" 
                                       value="{{ old('adjuster_email', $lead->adjuster_email) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="card border-0 rounded-0 border-top">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 text-dark">
                            <i class="bi bi-chat-text me-2"></i> Additional Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea id="notas" name="notas" class="form-control" rows="4" 
                                      placeholder="Enter any additional notes, comments, or special instructions here...">{{ old('notas', $lead->notas) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card-footer bg-transparent border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i> Update Lead
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.drop-zone:hover {
    border-color: #0d6efd;
    background: #e7f1ff;
}

.drop-zone-content {
    pointer-events: none;
}

.preview-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    background: white;
}

.preview-img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 4px;
}

.preview-actions {
    margin-top: 1rem;
}

.address-section {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    background: #f8f9fa;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.card {
    margin-bottom: 0;
}

.card:not(:first-child) {
    border-top: 1px solid rgba(0,0,0,.125) !important;
}

@media (max-width: 768px) {
    .drop-zone {
        padding: 1rem;
    }
    
    .preview-actions .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
function toggleAddressForm(select, targetId) {
    const target = document.getElementById(targetId);
    if (!target) return;

    if (select.value === 'new') {
        target.style.display = 'block';
    } else {
        target.style.display = 'none';
        // Clear fields cuando se oculta
        target.querySelectorAll('input, select').forEach(field => {
            if (field.type !== 'hidden') {
                field.value = '';
            }
        });
    }
}

function previewLocationImage(event) {
    const input = event.target;
    const previewContainer = document.querySelector('.preview-container');
    if (!previewContainer) return;

    // Si selecciona una nueva foto, NO queremos borrar las existentes
    const removeInput = document.getElementById('remove_location_photo');
    if (removeInput) {
        removeInput.value = '0';
    }

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validar tamaño (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            // Si solo hay el placeholder (sin .preview-card), lo limpiamos primero
            const hasCard = previewContainer.querySelector('.preview-card') !== null;
            if (!hasCard) {
                previewContainer.innerHTML = '';
            }

            // Creamos una tarjeta NUEVA para esta imagen
            const card = document.createElement('div');
            card.className = 'preview-card temp-photo';
            card.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="preview-img">
                <div class="preview-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearImagePreview(this)">
                        <i class="bi bi-x-circle me-1"></i> Remove this
                    </button>
                </div>
            `;
            previewContainer.appendChild(card);
        };
        reader.readAsDataURL(file);
    }
}

// Elimina SOLO la tarjeta de la foto nueva (no las anteriores)
function clearImagePreview(button) {
    const card = button.closest('.preview-card');
    if (card) {
        card.remove();
    }

    // Limpiar input file (para poder elegir otra)
    const input = document.getElementById('location_photo');
    if (input) {
        input.value = '';
    }
}

function removeLocationPhoto() {
    if (!confirm('Are you sure you want to remove the location photo?')) {
        return;
    }

    const previewContainer = document.querySelector('.preview-container');
    if (previewContainer) {
        previewContainer.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-image display-4 opacity-25"></i>
                <p class="mt-2 mb-0">No location photo uploaded</p>
            </div>
        `;
    }

    const input = document.getElementById('location_photo');
    if (input) {
        input.value = '';
    }

    // Marcar para que el backend borre TODAS las location_photo de este lead
    let removeInput = document.getElementById('remove_location_photo');
    if (removeInput) {
        removeInput.value = '1';
    } else {
        removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_location_photo';
        removeInput.id = 'remove_location_photo';
        removeInput.value = '1';

        const form = document.querySelector('form');
        if (form) {
            form.appendChild(removeInput);
        }
    }
}

// Initialize address forms and drag&drop on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize mailing address
    const mailingSelect = document.getElementById('mailing_address');
    if (mailingSelect) {
        toggleAddressForm(mailingSelect, 'newAddressFieldsMailing');
    }
    
    // Initialize billing address
    const billingSelect = document.getElementById('billing_address');
    if (billingSelect) {
        toggleAddressForm(billingSelect, 'newAddressFieldsBilling');
    }
    
    // Add drag and drop functionality
    const dropZone = document.getElementById('drop-location');
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        dropZone.addEventListener('drop', handleDrop, false);
    }

    // Phone masks
    document.getElementById('phone')?.addEventListener('input', function(e) {
        formatPhoneNumber(e.target);
    });

    document.getElementById('adjuster_phone')?.addEventListener('input', function(e) {
        formatPhoneNumber(e.target);
    });

    document.getElementById('adjuster_fax')?.addEventListener('input', function(e) {
        formatPhoneNumber(e.target);
    });
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    const dz = document.getElementById('drop-location');
    if (dz) dz.classList.add('bg-primary', 'text-white');
}

function unhighlight(e) {
    const dz = document.getElementById('drop-location');
    if (dz) dz.classList.remove('bg-primary', 'text-white');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    const input = document.getElementById('location_photo');
    
    if (input && files.length > 0) {
        input.files = files;
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    }
}

// Phone number formatting
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        value = '(' + value;
    }
    if (value.length > 4) {
        value = value.slice(0, 4) + ') ' + value.slice(4);
    }
    if (value.length > 9) {
        value = value.slice(0, 9) + '-' + value.slice(9, 13);
    }
    
    input.value = value;
}
</script>
@endsection
