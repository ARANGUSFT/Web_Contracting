@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Encabezado Mejorado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('calendar.view') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Calendar
        </a>
        <div class="text-center">
          
            <h4 class="mb-0 text-primary">CONTRACTING ALLIANCE</h4>
            <small class="text-muted">YOUR BUSINESS PARTNER FOR SUCCESS</small>
            <h5 class="mt-2">Supplement Request Form</h5>
        </div>
        <div style="width: 100px;"></div> <!-- Espacio para alinear -->
    </div>

    <!-- Tarjeta del formulario -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data" id="emergencyForm" class="needs-validation" novalidate>
                @csrf

                <!-- Sección 1: Información del Trabajo -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-file-earmark-text me-2"></i> Job Information
                    </h6>
                    
                    <div class="row g-3">
                        <!-- Fila 1 -->
                        <div class="col-md-6">
                            <label for="date_submitted" class="form-label">Date Submitted *</label>
                            <input type="date" class="form-control" id="date_submitted" name="date_submitted" required>
                            <div class="invalid-feedback">Please select a submission date.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="type_of_supplement" class="form-label">Type of Supplement *</label>
                            <select class="form-select" id="type_of_supplement" name="type_of_supplement" required>
                                <option value="" disabled selected>Select option</option>
                                <option value="Initial supplement">Initial supplement</option>
                                <option value="Final Supplement">Final Supplement</option>
                            </select>
                            <div class="invalid-feedback">Please select a supplement type.</div>
                        </div>

                        <!-- Fila 2 -->
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name *</label>
                            <input readonly type="text" class="form-control bg-light" id="company_name" 
                                   name="company_name" value="{{ $user->company_name ?? '' }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="company_contact_email" class="form-label">Company Contact Email *</label>
                            <input type="email" class="form-control" id="company_contact_email"
                                name="company_contact_email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <!-- Fila 3 -->
                        <div class="col-md-6">
                            <label for="job_number_name" class="form-label">Job Number / Name *</label>
                            <input type="text" class="form-control" id="job_number_name" 
                                   name="job_number_name" required>
                            <div class="invalid-feedback">Please enter a job number/name.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="job_address" class="form-label">Job Address *</label>
                            <input type="text" class="form-control" id="job_address" 
                                   name="job_address" required>
                            <div class="invalid-feedback">Please enter a job address.</div>
                        </div>

                        <!-- Fila 4 -->
                        <div class="col-md-6">
                            <label for="job_address_line2" class="form-label">Street Address Line 2</label>
                            <input type="text" class="form-control" id="job_address_line2" 
                                   name="job_address_line2">
                        </div>

                        <div class="col-md-3">
                            <label for="job_city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="job_city" 
                                   name="job_city" required>
                            <div class="invalid-feedback">Please enter a city.</div>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="job_state" class="form-label">State *</label>
                            <select class="form-select" id="job_state" name="job_state" required>
                                <option value="" disabled selected>Select</option>
                                <option value="TX">TX</option>
                                <option value="FL">FL</option>
                                <option value="CA">CA</option>
                            </select>
                            <div class="invalid-feedback">Please select a state.</div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="job_zip_code" class="form-label">Zip Code *</label>
                            <input type="text" class="form-control" id="job_zip_code" 
                                   name="job_zip_code" required>
                            <div class="invalid-feedback">Please enter a valid zip code.</div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Términos y Condiciones -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-file-earmark-check me-2"></i> Terms & Conditions
                    </h6>
                    
                    <div class="alert alert-light border">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms_conditions"
                                name="terms_conditions" required>
                            <label class="form-check-label fw-semibold" for="terms_conditions">
                                <i class="bi bi-exclamation-circle text-primary me-1"></i> Supplement Submission Responsibility
                            </label>
                            <p class="small text-muted mb-0 ps-4">
                                I understand it is my company's responsibility to submit the supplement.
                                Contracting Alliance will create document on your behalf.
                            </p>
                            <div class="invalid-feedback">You must accept this condition.</div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="requirements" 
                                   name="requirements" required>
                            <label class="form-check-label fw-semibold" for="requirements">
                                <i class="bi bi-exclamation-circle text-primary me-1"></i> Supplement Processing
                            </label>
                            <p class="small text-muted mb-0 ps-4">
                                I understand that the speed and accuracy of the supplement is based on the information provided,
                                failure to provide pictures and/or contracts.
                            </p>
                            <div class="invalid-feedback">You must accept this condition.</div>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Documentos Adjuntos -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-paperclip me-2"></i> Required Documents
                    </h6>
                    
                    <div class="row g-3">
                        <!-- Aerial Measurement -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <label class="form-label fw-semibold">Aerial Measurement *</label>
                                    <small class="d-block text-muted mb-2">(PDF, JPG, PNG)</small>
                                    <input type="file" class="form-control" name="aerial_measurement[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png" required
                                        onchange="previewFiles(event, 'aerialPreview')">
                                    <div id="aerialPreview" class="mt-3"></div>
                                    <div class="invalid-feedback">Please upload at least one file.</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contract Upload -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <label class="form-label fw-semibold">Contract Upload *</label>
                                    <small class="d-block text-muted mb-2">(PDF, JPG, PNG)</small>
                                    <input type="file" class="form-control" name="contract_upload[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png" required
                                        onchange="previewFiles(event, 'contractPreview')">
                                    <div id="contractPreview" class="mt-3"></div>
                                    <div class="invalid-feedback">Please upload at least one file.</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Pictures -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <label class="form-label fw-semibold">Additional Pictures (Optional)</label>
                                    <small class="d-block text-muted mb-2">(PDF, JPG, PNG)</small>
                                    <input type="file" class="form-control" name="file_picture_upload[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        onchange="previewFiles(event, 'picturePreview')">
                                    <div id="picturePreview" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-send-check me-2"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
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
    
    /* Checkboxes */
    .form-check-input {
        margin-top: 0.25rem;
    }
    
    /* Previsualización de archivos */
    .file-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .file-preview-item {
        display: flex;
        align-items: center;
        background-color: #f1f3f5;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .file-preview-item button {
        background: none;
        border: none;
        color: #dc3545;
        margin-left: 0.5rem;
        cursor: pointer;
    }
    
    /* Botones */
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    
    /* Tarjetas */
    .card {
        border-radius: 0.5rem;
    }
    
    /* Secciones */
    h6.border-bottom {
        border-color: #dee2e6 !important;
    }
</style>

<script>
    const fileMap = {
        aerial_measurement: [],
        contract_upload: [],
        file_picture_upload: []
    };

    function previewFiles(event, previewId) {
        const inputName = event.target.name.replace('[]', '');
        const files = Array.from(event.target.files);
        fileMap[inputName] = fileMap[inputName].concat(files);
        updatePreview(inputName, previewId, event.target);
    }

    function removeFile(event, index, inputName, previewId) {
        event.preventDefault();
        event.stopPropagation();
        fileMap[inputName].splice(index, 1);
        updatePreview(inputName, previewId);
    }

    function updatePreview(inputName, previewId, input = null) {
        const container = document.getElementById(previewId);
        container.innerHTML = '';

        if (fileMap[inputName].length > 0) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'file-preview-container';
            
            fileMap[inputName].forEach((file, i) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'file-preview-item';
                
                const icon = document.createElement('i');
                icon.className = file.type.includes('image') ? 'bi bi-image me-2' : 'bi bi-file-earmark-pdf me-2';
                
                const fileName = document.createElement('span');
                fileName.textContent = file.name.length > 20 ? 
                    file.name.substring(0, 15) + '...' + file.name.split('.').pop() : 
                    file.name;
                
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = (e) => removeFile(e, i, inputName, previewId);
                
                previewItem.appendChild(icon);
                previewItem.appendChild(fileName);
                previewItem.appendChild(removeBtn);
                previewContainer.appendChild(previewItem);
            });
            
            container.appendChild(previewContainer);
        }

        if (input) {
            const dt = new DataTransfer();
            fileMap[inputName].forEach(f => dt.items.add(f));
            input.files = dt.files;
            
            // Trigger validation
            if (input.required && fileMap[inputName].length === 0) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }
    }

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('emergencyForm');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Scroll to the first invalid element
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
            
            form.classList.add('was-validated');
        }, false);
        
        // Validate required file inputs
        const fileInputs = form.querySelectorAll('input[type="file"][required]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                }
            });
        });
    });
</script>
@endsection