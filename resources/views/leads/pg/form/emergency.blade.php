@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #003366;
            --primary-light: #1a4d80;
            --primary-dark: #002244;
            --accent: #f8f9fa;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
            --success: #198754;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .brand-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .form-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .form-card .card-body {
            padding: 2rem;
        }
        
        .section-header {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
        }
        
        .section-header i {
            background-color: var(--primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.15);
        }
        
        .file-upload-card {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }
        
        .file-upload-card:hover {
            border-color: var(--primary);
            background-color: rgba(0, 51, 102, 0.03);
        }
        
        .file-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .file-preview-item {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .file-preview-item button {
            background: none;
            border: none;
            color: #dc3545;
            margin-left: 0.5rem;
            cursor: pointer;
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.25);
        }
        
        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
        
        .terms-card {
            background-color: #f8f9fa;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.25);
        }
        
        .required-field::after {
            content: "*";
            color: #dc3545;
            margin-left: 0.25rem;
        }
        
        @media (max-width: 768px) {
            .brand-header {
                text-align: center;
                padding: 1rem;
            }
            
            .form-card .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
      <!-- Encabezado de marca -->
        <div class="brand-header text-center rounded-3 position-relative">
            <!-- Botón de retroceso en la esquina superior izquierda -->
            <a href="{{ route('calendar.view') }}" class="btn btn-light position-absolute top-0 start-0 m-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Calendar
            </a>
            
            <div class="container py-3">
                <div class="d-flex flex-column align-items-center position-relative">
                    <img src="https://www.jotform.com/uploads/fredysanchezc1980/form_files/IMG_7040.663336b07e6656.75204432.jpeg" 
                        alt="Contracting Alliance Logo" 
                        class="img-fluid mb-3" 
                        style="width: 80px; height: 80px; object-fit: contain;">
                    <div>
                        <h3 class="mb-0 fw-bold">CONTRACTING ALLIANCE INC</h3>
                        <h4 class="mt-2 fw-semibold">Emergency Form</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta del formulario -->
        <div class="card form-card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i> 
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data" id="emergencyForm" class="needs-validation" novalidate>
                    @csrf

                    <!-- Sección 1: Información del Trabajo -->
                    <div class="mb-5">
                        <h5 class="section-header">
                            <i class="bi bi-file-earmark-text"></i> Job Information
                        </h5>
                        
                        <div class="row g-4">
                            <!-- Fila 1 -->
                            <div class="col-md-6">
                                <label for="date_submitted" class="form-label required-field">Date Submitted</label>
                                <input type="date" class="form-control" id="date_submitted" name="date_submitted" required>
                                <div class="invalid-feedback">Please select a submission date.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="type_of_supplement" class="form-label required-field">Type of Emergency:</label>
                                <select class="form-select" id="type_of_supplement" name="type_of_supplement" required>
                                    <option value="" disabled selected>Select option</option>
                                    <option value="New roof installed by a Contracting Alliance sub is leaking (warranty)" >New roof installed by a Contracting Alliance sub is leaking (warranty)</option>
                                    <option value="New job, please identify and Stop Leak (minimum $750 charge)">New job, please identify and Stop Leak (minimum $750 charge)</option>
                                    <option value="Emergency Hurricane Tarping Labor and Materials">Emergency Hurricane Tarping Labor and Materials</option>
                                    <option value="Emergency Hurricane Tarping Labor Only">Emergency Hurricane Tarping Labor Only</option>
                                </select>
                                <div class="invalid-feedback">Please select type.</div>
                            </div>

                            <!-- Fila 2 -->
                            <div class="col-md-6">
                                <label for="company_name" class="form-label required-field">Company Name</label>
                                <input readonly type="text" class="form-control bg-light" id="company_name" 
                                       name="company_name" value="{{ $user->company_name ?? '' }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="company_contact_email" class="form-label required-field">Company Contact Email</label>
                                <input type="email" class="form-control" id="company_contact_email"
                                    name="company_contact_email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>

                            <!-- Fila 3 -->
                            <div class="col-md-6">
                                <label for="job_number_name" class="form-label required-field">Job Number / Name</label>
                                <input type="text" class="form-control" id="job_number_name" 
                                       name="job_number_name" required>
                                <div class="invalid-feedback">Please enter a job number/name.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="job_address" class="form-label required-field">Job Address</label>
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
                                <label for="job_city" class="form-label required-field">City</label>
                                <input type="text" class="form-control" id="job_city" 
                                       name="job_city" required>
                                <div class="invalid-feedback">Please enter a city.</div>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="job_state" class="form-label required-field">State</label>
                                <select class="form-select" id="job_state" name="job_state" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="TX">TX</option>
                                    <option value="FL">FL</option>
                                    <option value="CA">CA</option>
                                </select>
                                <div class="invalid-feedback">Please select a state.</div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="job_zip_code" class="form-label required-field">Zip Code</label>
                                <input type="text" class="form-control" id="job_zip_code" 
                                       name="job_zip_code" required>
                                <div class="invalid-feedback">Please enter a valid zip code.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Members Section -->
                    @php
                    $teamMembers = \App\Models\Team::whereIn('role', ['manager', 'project_manager', 'crew'])->get();
                    @endphp
                    
                    <div class="mb-5">
                        <h5 class="section-header">
                            <i class="bi bi-people-fill"></i> Assign Team Members
                        </h5>
                        
                        <div class="row">
                            @foreach($teamMembers as $member)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check p-3 bg-light rounded">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="assigned_team_members[]"
                                            value="{{ $member->id }}"
                                            id="emergency_member_{{ $member->id }}">
                                        <label class="form-check-label d-flex align-items-center" for="emergency_member_{{ $member->id }}">
                                            <span class="fw-medium">{{ $member->name }}</span>
                                            <small class="text-muted ms-2">({{ ucfirst(str_replace('_', ' ', $member->role)) }})</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sección 2: Términos y Condiciones -->
                    <div class="mb-5">
                        <h5 class="section-header">
                            <i class="bi bi-file-earmark-check"></i> Terms & Conditions
                        </h5>
                        
                        <div class="terms-card p-4">
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms_conditions"
                                    name="terms_conditions" required>
                                <label class="form-check-label fw-semibold" for="terms_conditions">
                                    Supplement Submission Responsibility
                                </label>
                                <p class="text-muted mb-0 mt-1 ps-4">
                                    I understand it is my company's responsibility to submit the supplement.
                                    Contracting Alliance will create document on your behalf.
                                </p>
                                <div class="invalid-feedback">You must accept this condition.</div>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="requirements" 
                                       name="requirements" required>
                                <label class="form-check-label fw-semibold" for="requirements">
                                    Supplement Processing
                                </label>
                                <p class="text-muted mb-0 mt-1 ps-4">
                                    I understand that the speed and accuracy of the supplement is based on the information provided,
                                    failure to provide pictures and/or contracts.
                                </p>
                                <div class="invalid-feedback">You must accept this condition.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 3: Documentos Adjuntos -->
                    <div class="mb-5">
                        <h5 class="section-header">
                            <i class="bi bi-paperclip"></i> Required Documents
                        </h5>
                        
                        <div class="row g-4">
                            <!-- Aerial Measurement -->
                            <div class="col-md-6">
                                <div class="file-upload-card h-100 p-4">
                                    <label class="form-label fw-semibold required-field">Aerial Measurement</label>
                                    <small class="d-block text-muted mb-3">Accepted formats: PDF, JPG, PNG (Max 5MB each)</small>
                                    <input type="file" class="form-control" name="aerial_measurement[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png" required
                                        onchange="previewFiles(event, 'aerialPreview')">
                                    <div id="aerialPreview" class="mt-3"></div>
                                    <div class="invalid-feedback">Please upload at least one file.</div>
                                </div>
                            </div>
                            
                            <!-- Contract Upload -->
                            <div class="col-md-6">
                                <div class="file-upload-card h-100 p-4">
                                    <label class="form-label fw-semibold required-field">Contract Upload</label>
                                    <small class="d-block text-muted mb-3">Accepted formats: PDF, JPG, PNG (Max 5MB each)</small>
                                    <input type="file" class="form-control" name="contract_upload[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png" required
                                        onchange="previewFiles(event, 'contractPreview')">
                                    <div id="contractPreview" class="mt-3"></div>
                                    <div class="invalid-feedback">Please upload at least one file.</div>
                                </div>
                            </div>
                            
                            <!-- Additional Pictures -->
                            <div class="col-12">
                                <div class="file-upload-card p-4">
                                    <label class="form-label fw-semibold">Additional Pictures (Optional)</label>
                                    <small class="d-block text-muted mb-3">Accepted formats: PDF, JPG, PNG (Max 5MB each)</small>
                                    <input type="file" class="form-control" name="file_picture_upload[]" multiple
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        onchange="previewFiles(event, 'picturePreview')">
                                    <div id="picturePreview" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-3">
                            <i class="bi bi-send-check me-2"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            
            // Set today's date as default for date field
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_submitted').value = today;
            
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
</body>
</html>
@endsection