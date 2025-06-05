@extends('layouts.app')

@section('content')

<a href="{{ route('calendar.view') }}" class="btn btn-light btn-sm position-absolute top-3 end-3">
    <i class="bi bi-arrow-left"></i> Back
</a>

<div class="container-lg py-5">
    <div class="max-w-6xl mx-auto bg-white shadow-xl rounded-3xl overflow-hidden">
        <!-- Encabezado Mejorado -->
        
        <div class="bg-primary text-white text-center position-relative py-4">
            
            <div class="container">
               
                <div class="d-flex flex-column align-items-center">
                    <img src="https://www.jotform.com/uploads/fredysanchezc1980/form_files/IMG_7040.663336b07e6656.75204432.jpeg" 
                         alt="Contracting Alliance Logo" 
                         class="img-fluid mb-3" 
                         style="width: 80px; height: 80px; object-fit: contain;">
                    <div>
                        <h4 class="mb-0 fw-bold">CONTRACTING ALLIANCE</h4>
                        <p class="mb-0 small opacity-75">YOUR BUSINESS PARTNER FOR SUCCESS</p>
                        <h5 class="mt-2 fw-semibold">Job Request Form</h5>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 mb-0">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf

                            <!-- Progress Steps -->
                            <div class="px-4 pt-4">
                                <div class="form-steps">
                                    <div class="step active" data-step="1">
                                        <div class="step-number">1</div>
                                        <div class="step-label">General Info</div>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-number">2</div>
                                        <div class="step-label">Customer</div>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-number">3</div>
                                        <div class="step-label">Job Location</div>
                                    </div>
                                    <div class="step" data-step="4">
                                        <div class="step-number">4</div>
                                        <div class="step-label">Materials</div>
                                    </div>
                                    <div class="step" data-step="5">
                                        <div class="step-number">5</div>
                                        <div class="step-label">Inspections</div>
                                    </div>
                                    <div class="step" data-step="6">
                                        <div class="step-number">6</div>
                                        <div class="step-label">Attachments</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 1: General Information -->
                            <div class="step-content active" data-step="1">
                                <div class="card mb-4 border-0 rounded-0">
                                    <div class="card-header bg-primary bg-opacity-10 text-primary fw-semibold border-0 py-3">
                                        <i class="bi bi-building me-2"></i> General Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="install_date_requested" class="form-label">Install Date Requested *</label>
                                                <input type="date" class="form-control" id="install_date_requested" 
                                                    name="install_date_requested" min="{{ date('Y-m-d') }}" required>
                                                <div class="invalid-feedback">Please select a valid install date.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="company_name" class="form-label">Company Name *</label>
                                                <input readonly type="text" class="form-control bg-light" 
                                                    id="company_name" name="company_name" value="{{ $user->company_name }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="company_rep" class="form-label">Company Representative *</label>
                                                <input type="text" class="form-control" id="company_rep" 
                                                    name="company_rep" placeholder="Representative name" required>
                                                <div class="invalid-feedback">Please enter the company representative name.</div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="company_rep_phone" class="form-label">Representative Phone *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                    <input type="tel" class="form-control" id="company_rep_phone" 
                                                        name="company_rep_phone" value="{{ $user->phone }}" required>
                                                </div>
                                                <div class="invalid-feedback">Please enter a valid phone number.</div>
                                            </div>

                                            <input type="hidden" name="company_rep_email" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between pt-0">
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="bi bi-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step">
                                            Next <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Customer Information -->
                            <div class="step-content" data-step="2">
                                <div class="card mb-4 border-0 rounded-0">
                                    <div class="card-header bg-primary bg-opacity-10 text-primary fw-semibold border-0 py-3">
                                        <i class="bi bi-person me-2"></i> Customer Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="customer_first_name" class="form-label">First Name *</label>
                                                <input type="text" class="form-control" id="customer_first_name" 
                                                    name="customer_first_name" required>
                                                <div class="invalid-feedback">Please enter the customer's first name.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="customer_last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="customer_last_name" 
                                                    name="customer_last_name">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="customer_phone_number" class="form-label">Phone Number *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                    <input type="tel" class="form-control" id="customer_phone_number" 
                                                        name="customer_phone_number" required>
                                                </div>
                                                <div class="invalid-feedback">Please enter a valid phone number.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between pt-0">
                                        <button type="button" class="btn btn-secondary prev-step">
                                            <i class="bi bi-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step">
                                            Next <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Job Location -->
                            <div class="step-content" data-step="3">
                                <div class="card mb-4 border-0 rounded-0">
                                    <div class="card-header bg-primary bg-opacity-10 text-primary fw-semibold border-0 py-3">
                                        <i class="bi bi-geo-alt me-2"></i> Job Location
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="job_number_name" class="form-label">Job Number / Name *</label>
                                                <input type="text" class="form-control" id="job_number_name" 
                                                    name="job_number_name" required>
                                                <div class="invalid-feedback">Please enter a job number/name.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="job_address_street_address" class="form-label">Street Address *</label>
                                                <input type="text" class="form-control" id="job_address_street_address" 
                                                    name="job_address_street_address" required>
                                                <div class="invalid-feedback">Please enter a street address.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="job_address_street_address_line_2" class="form-label">Address Line 2</label>
                                                <input type="text" class="form-control" id="job_address_street_address_line_2" 
                                                    name="job_address_street_address_line_2">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="job_address_city" class="form-label">City *</label>
                                                <input type="text" class="form-control" id="job_address_city" 
                                                    name="job_address_city" required>
                                                <div class="invalid-feedback">Please enter a city.</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="job_address_state" class="form-label">State *</label>
                                                <select class="form-select" id="job_address_state" name="job_address_state" required>
                                                    <option value="" disabled selected>Select state</option>
                                                    <option value="TX">Texas</option>
                                                    <option value="FL">Florida</option>
                                                    <option value="CA">California</option>
                                                    <!-- Más estados según sea necesario -->
                                                </select>
                                                <div class="invalid-feedback">Please select a state.</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="job_address_zip_code" class="form-label">Zip Code *</label>
                                                <input type="text" class="form-control" id="job_address_zip_code" 
                                                    name="job_address_zip_code" required>
                                                <div class="invalid-feedback">Please enter a valid zip code.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="assigned_team_members" class="form-label fw-semibold">Asignar a miembros del equipo</label>
                                                <select name="assigned_team_members[]" id="assigned_team_members" class="form-select" multiple size="6">
                                                    @php
                                                        $grouped = $teamMembers->groupBy('role');
                                                    @endphp
                                            
                                                    @foreach($grouped as $role => $members)
                                                        <optgroup label="{{ ucfirst(str_replace('_', ' ', $role)) }}">
                                                            @foreach($members as $member)
                                                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Usa Ctrl (Windows) o Cmd (Mac) para seleccionar varios.</small>
                                            </div>
                                                                                  
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between pt-0">
                                        <button type="button" class="btn btn-secondary prev-step">
                                            <i class="bi bi-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step">
                                            Next <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Materials Details -->
                            <div class="step-content" data-step="4">
                                <div class="card mb-4 border-0 rounded-0">
                                    <div class="card-header bg-primary bg-opacity-10 text-primary fw-semibold border-0 py-3">
                                        <i class="bi bi-box-seam me-2"></i> Materials Details
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="material_roof_loaded" class="form-label">Material Roof Loaded *</label>
                                                <select class="form-select" id="material_roof_loaded" name="material_roof_loaded" required>
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                                <div class="invalid-feedback">Please select an option.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                                <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="starter_bundles_ordered" class="form-label">Starter Bundles Ordered</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="starter_bundles_ordered" 
                                                        name="starter_bundles_ordered" min="0">
                                                    <span class="input-group-text">bundles</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="hip_and_ridge_ordered" class="form-label">Hip and Ridge Ordered</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="hip_and_ridge_ordered" 
                                                        name="hip_and_ridge_ordered" min="0">
                                                    <span class="input-group-text">bundles</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="field_shingle_bundles_ordered" class="form-label">Field Shingle Bundles Ordered</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="field_shingle_bundles_ordered" 
                                                        name="field_shingle_bundles_ordered" min="0">
                                                    <span class="input-group-text">bundles</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="modified_bitumen_cap_rolls_ordered" class="form-label">Modified Bitumen Cap Rolls Ordered</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="modified_bitumen_cap_rolls_ordered" 
                                                        name="modified_bitumen_cap_rolls_ordered" min="0">
                                                    <span class="input-group-text">rolls</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between pt-0">
                                        <button type="button" class="btn btn-secondary prev-step">
                                            <i class="bi bi-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step">
                                            Next <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5: Inspections and Substitutions -->
                            <div class="step-content" data-step="5">
                                <div class="card mb-4 border-0 rounded-0">
                                    <div class="card-header bg-primary bg-opacity-10 text-primary fw-semibold border-0 py-3">
                                        <i class="bi bi-clipboard-check me-2"></i> Inspections and Substitutions
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="mid_roof_inspection" class="form-label">Mid Roof Inspection</label>
                                                <select class="form-select" id="mid_roof_inspection" name="mid_roof_inspection">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="siding_being_replaced" class="form-label">Siding Being Replaced</label>
                                                <select class="form-select" id="siding_being_replaced" name="siding_being_replaced">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="asphalt_shingle_layers_to_remove" class="form-label">Asphalt Shingle Layers to Remove</label>
                                                <select class="form-select" id="asphalt_shingle_layers_to_remove" name="asphalt_shingle_layers_to_remove">
                                                    <option value="" disabled selected>Select</option>
                                                    @for ($i = 1; $i <= 6; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            
                                            <!-- Dynamic Yes/No Fields -->
                                            @foreach ([
                                                're_deck' => 'Re Deck',
                                                'skylights_replace' => 'Skylights Replace',
                                                'gutter_remove' => 'Gutter Remove',
                                                'gutter_detached_and_reset' => 'Gutter Detached and Reset',
                                                'satellite_remove' => 'Satellite Remove',
                                                'satellite_goes_in_the_trash' => 'Satellite Goes in the Trash',
                                                'open_soffit_ceiling' => 'Open Soffit Ceiling',
                                                'detached_garage_roof' => 'Detached Garage Roof',
                                                'detached_shed_roof' => 'Detached Shed Roof'
                                            ] as $field => $label)
                                                <div class="col-md-6">
                                                    <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                                    <select class="form-select" id="{{ $field }}" name="{{ $field }}">
                                                        <option value="" disabled selected>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            @endforeach

                                            <!-- Special Instructions -->
                                            <div class="col-12 mt-3">
                                                <label for="special_instructions" class="form-label">Special Instructions</label>
                                                <textarea class="form-control" id="special_instructions" 
                                                        name="special_instructions" rows="3" 
                                                        placeholder="Any specific notes or expectations..."></textarea>
                                            </div>

                                            <!-- Important Checkboxes -->
                                            <div class="col-12 mt-3">
                                                <div class="alert alert-light border">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" 
                                                            id="material_verification" name="material_verification" value="1">
                                                        <label class="form-check-label fw-semibold" for="material_verification">
                                                            <i class="bi bi-exclamation-triangle text-warning me-1"></i> Material Verification
                                                        </label>
                                                        <p class="small text-muted mb-0 ps-4">
                                                            I understand it is my company's responsibility to alert Contracting Alliance the night before construction if materials are not on site.
                                                        </p>
                                                    </div>

                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" 
                                                            id="stop_work_request" name="stop_work_request" value="1">
                                                        <label class="form-check-label fw-semibold" for="stop_work_request">
                                                            <i class="bi bi-exclamation-triangle text-warning me-1"></i> Stop Work Request
                                                        </label>
                                                        <p class="small text-muted mb-0 ps-4">
                                                            Our company is obligated to notify Contracting Alliance by 4:00 PM Central Time on the day prior to any scheduled construction if the project is to be put on hold.
                                                        </p>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                            id="documentationattachment" name="documentationattachment" value="1">
                                                        <label class="form-check-label fw-semibold" for="documentationattachment">
                                                            <i class="bi bi-exclamation-triangle text-warning me-1"></i> Required Documentation
                                                        </label>
                                                        <p class="small text-muted mb-0 ps-4">
                                                            Aerial measurement, material order, and photos are required. If not included, this can delay your build.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between pt-0">
                                        <button type="button" class="btn btn-secondary prev-step">
                                            <i class="bi bi-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step">
                                            Next <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 6: Attachments -->
                <div class="step-content" data-step="6">
                    <div class="card mb-4 border-0 rounded-0">
                        <div class="card-header bg-primary bg-opacity-10 text-primary fw-semibold border-0 py-3">
                            <i class="bi bi-paperclip me-2"></i> Attachments
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach (['aerial_measurement' => 'Aerial Measurement', 'material_order' => 'Material Order', 'file_upload' => 'Other Files (Permit / SOL / etc)'] as $field => $label)
                                    <div class="col-md-6">
                                        <div class="file-group mb-2">
                                            <label class="form-label">{{ $label }}</label>
                                            <div id="{{ $field }}-container">
                                                <div class="input-group mb-2">
                                                    <input type="file" name="{{ $field }}[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                                    <button type="button" class="btn btn-outline-secondary add-file" data-target="{{ $field }}-container">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mb-2">Accepted formats: PDF, JPG, PNG, WEBP</small>
                                        </div>

                                        @php
                                            $files = $job->$field ?? [];
                                        @endphp

                                        @if (!empty($files))
                                            <div class="existing-files mt-2">
                                                @foreach ($files as $file)
                                                    @php
                                                        $fileUrl = asset('storage/' . $file);
                                                        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                    @endphp

                                                    <div class="mb-2 border p-2 rounded d-flex align-items-center justify-content-between">
                                                        <div>
                                                            @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp']))
                                                                <img src="{{ $fileUrl }}" alt="Preview" style="max-height: 60px;" class="me-2">
                                                            @elseif ($fileExtension === 'pdf')
                                                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-4 me-2"></i>
                                                                <a href="{{ $fileUrl }}" target="_blank">View PDF</a>
                                                            @endif
                                                        </div>

                                                        <div>
                                                            <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-primary me-2">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                            <form action="{{ route('jobs.files.delete', ['job' => $job->id, 'field' => $field, 'file' => urlencode($file)]) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-between pt-0">
                            <button type="button" class="btn btn-secondary prev-step">
                                <i class="bi bi-arrow-left me-1"></i> Previous
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Submit Job Request
                            </button>
                        </div>
                    </div>
                </div>


        </form>

        
    </div>
</div>

<style>
    /* Estilos generales */
    body {
        background-color: #f8f9fa;
    }
    
    /* Encabezado */
    .card-header.bg-primary {
        background-color: #0d6efd !important;
    }
    
    /* Barra de progreso */
    .form-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-steps::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #dee2e6;
        z-index: 0;
        transform: translateY(-50%);
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        cursor: pointer;
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 0.5rem;
        border: 3px solid white;
        transition: all 0.3s ease;
    }
    
    .step-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
    }
    
    .step.active .step-number {
        background-color: #0d6efd;
        color: white;
    }
    
    .step.active .step-label {
        color: #0d6efd;
        font-weight: 600;
    }
    
    /* Contenido de pasos */
    .step-content {
        display: none;
    }
    
    .step-content.active {
        display: block;
    }
    
    /* Formulario */
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.4rem;
        color: #495057;
    }
    
    .invalid-feedback {
        font-size: 0.8rem;
    }
    
    /* Cards */
    .card {
        border-radius: 0 !important;
        border: none;
        box-shadow: none;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    /* Checkboxes importantes */
    .form-check-input {
        margin-top: 0.25rem;
    }
    
    /* Botones */
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-steps {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .step {
            width: 25%;
            margin-bottom: 1rem;
        }
        
        .step-number {
            width: 28px;
            height: 28px;
            font-size: 0.9rem;
        }
        
        .step-label {
            font-size: 0.7rem;
        }
    }
</style>


<script>
    document.querySelectorAll('.add-file').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = `
                <input type="file" name="${targetId.replace('-container', '')}[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
                <button type="button" class="btn btn-outline-danger remove-file"><i class="bi bi-dash"></i></button>
            `;
            container.appendChild(inputGroup);
    
            inputGroup.querySelector('.remove-file').addEventListener('click', function() {
                inputGroup.remove();
            });
        });
    });
    </script>
    


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navegación por pasos
    const steps = document.querySelectorAll('.step-content');
    const stepButtons = document.querySelectorAll('.step');
    let currentStep = 1;

    // Mostrar el primer paso
    document.querySelector('.step-content[data-step="1"]').classList.add('active');

    // Botón Siguiente
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentStepElement = document.querySelector(`.step-content[data-step="${currentStep}"]`);
            const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            // Validar campos requeridos
            inputs.forEach(input => {
                if (!input.value) {
                    input.classList.add('is-invalid');
                    isValid = false;
                    
                    // Scroll al primer campo inválido
                    if (isValid === false) {
                        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        isValid = true; // Prevenir múltiples scrolls
                    }
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) return;

            // Ocultar paso actual
            currentStepElement.classList.remove('active');
            
            // Actualizar paso actual
            currentStep++;
            
            // Mostrar siguiente paso
            document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.add('active');
            
            // Actualizar indicador de progreso
            updateProgressIndicator();
        });
    });

    // Botón Anterior
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            // Ocultar paso actual
            document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.remove('active');
            
            // Actualizar paso actual
            currentStep--;
            
            // Mostrar paso anterior
            document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.add('active');
            
            // Actualizar indicador de progreso
            updateProgressIndicator();
        });
    });

    // Actualizar indicador de progreso
    function updateProgressIndicator() {
        stepButtons.forEach(step => {
            const stepNumber = parseInt(step.dataset.step);
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.classList.add('active');
                step.querySelector('.step-number').innerHTML = '<i class="bi bi-check"></i>';
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
                step.querySelector('.step-number').textContent = stepNumber;
            } else {
                step.classList.remove('active', 'completed');
                step.querySelector('.step-number').textContent = stepNumber;
            }
        });
    }

    // Validación de formulario al enviar
    const form = document.querySelector('form.needs-validation');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Encontrar el primer campo inválido y hacer scroll
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            form.classList.add('was-validated');
        }, false);
    }

    // Mostrar nombre de archivo al seleccionar
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const container = this.closest('.file-upload-container');
            const fileName = this.files.length > 0 ? this.files[0].name : 'No file selected';
            
            // Eliminar nombre de archivo anterior si existe
            const existingFileName = container.querySelector('.file-name');
            if (existingFileName) {
                existingFileName.remove();
            }
            
            // Agregar nuevo nombre de archivo
            const fileNameElement = document.createElement('small');
            fileNameElement.className = 'file-name d-block text-primary mt-1 fw-semibold';
            fileNameElement.textContent = fileName;
            container.appendChild(fileNameElement);
        });
    });
});
</script>
@endsection