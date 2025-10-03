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
            --warning: #ffc107;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .brand-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .brand-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.3;
        }
        
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .form-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            padding: 1.5rem 2rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-steps::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 2rem;
            right: 2rem;
            height: 2px;
            background-color: var(--border-color);
            z-index: 0;
            transform: translateY(-50%);
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .step-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
            text-align: center;
        }
        
        .step.active .step-number {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        .step.completed .step-number {
            background-color: var(--success);
            color: white;
            border-color: var(--success);
        }
        
        .step.completed .step-number::before {
            content: "✓";
        }
        
        .step-content {
            display: none;
            padding: 0;
        }
        
        .step-content.active {
            display: block;
        }
        
        .section-header {
            background: linear-gradient(to right, rgba(0, 51, 102, 0.1), transparent);
            color: var(--primary);
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }
        
        .section-header i {
            background-color: var(--primary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .required-field::after {
            content: "*";
            color: #dc3545;
            margin-left: 0.25rem;
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
        
        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 8px 0 0 8px;
        }
        
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.25);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #0f6848 100%);
            border: none;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.25);
        }
        
        .terms-card {
            background-color: #f8f9fa;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.25);
        }
        
        .file-group {
            margin-bottom: 1.5rem;
        }
        
        .file-group .input-group {
            margin-bottom: 0.5rem;
        }
        
        .add-file, .remove-file {
            border-radius: 0 8px 8px 0;
        }
        
        .existing-files {
            margin-top: 1rem;
        }
        
        .file-preview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
        }
        
        .file-preview img {
            max-height: 50px;
            border-radius: 4px;
        }
        
        .back-button {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
        }
        
        @media (max-width: 768px) {
            .form-steps {
                flex-wrap: wrap;
                justify-content: center;
                padding: 1rem;
            }
            
            .step {
                width: 33%;
                margin-bottom: 1rem;
            }
            
            .step-number {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
            
            .step-label {
                font-size: 0.7rem;
            }
            
            .brand-header {
                padding: 1.5rem 1rem;
            }
            
            .section-header {
                padding: 0.75rem 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-lg py-4">
        <!-- Botón de retroceso -->
        <a href="{{ route('calendar.view') }}" class="btn btn-light back-button">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>

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
                <h4 class="mt-2 fw-semibold">Job Request Form</h4>
            </div>
        </div>
    </div>
</div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-auto mb-4" style="max-width: 1000px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="form-container mx-auto" style="max-width: 1000px;">
            <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <!-- Progress Steps -->
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

                <!-- Step 1: General Information -->
                <div class="step-content active" data-step="1">
                    <div class="card mb-0 border-0 rounded-0">
                        <div class="section-header">
                            <i class="bi bi-building"></i> General Information
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="install_date_requested" class="form-label required-field">Install Date Requested</label>
                                    <input type="date" class="form-control" id="install_date_requested" 
                                        name="install_date_requested" min="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback">Please select a valid install date.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label required-field">Company Name</label>
                                    <input readonly type="text" class="form-control bg-light" 
                                        id="company_name" name="company_name" value="{{ $user->company_name }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="company_rep" class="form-label required-field">Company Representative</label>
                                    <input type="text" class="form-control" id="company_rep" 
                                        name="company_rep" placeholder="Representative name" required>
                                    <div class="invalid-feedback">Please enter the company representative name.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="company_rep_phone" class="form-label required-field">Representative Phone</label>
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
                        <div class="card-footer d-flex justify-content-between">
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
                    <div class="card mb-0 border-0 rounded-0">
                        <div class="section-header">
                            <i class="bi bi-person"></i> Customer Information
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_first_name" class="form-label required-field">First Name</label>
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
                                    <label for="customer_phone_number" class="form-label required-field">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control" id="customer_phone_number" 
                                            name="customer_phone_number" required>
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid phone number.</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
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
                    <div class="card mb-0 border-0 rounded-0">
                        <div class="section-header">
                            <i class="bi bi-geo-alt"></i> Job Location
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="job_number_name" class="form-label required-field">Job Number / Name</label>
                                    <input type="text" class="form-control" id="job_number_name" 
                                        name="job_number_name" required>
                                    <div class="invalid-feedback">Please enter a job number/name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="job_address_street_address" class="form-label required-field">Street Address</label>
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
                                    <label for="job_address_city" class="form-label required-field">City</label>
                                    <input type="text" class="form-control" id="job_address_city" 
                                        name="job_address_city" required>
                                    <div class="invalid-feedback">Please enter a city.</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="job_address_state" class="form-label required-field">State</label>
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
                                    <label for="job_address_zip_code" class="form-label required-field">Zip Code</label>
                                    <input type="text" class="form-control" id="job_address_zip_code" 
                                        name="job_address_zip_code" required>
                                    <div class="invalid-feedback">Please enter a valid zip code.</div>
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <label for="assigned_team_members" class="form-label fw-semibold">Assign Team Members</label>
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
                                    <small class="form-text text-muted">Use Ctrl (Windows) or Cmd (Mac) to select multiple members.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
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
                    <div class="card mb-0 border-0 rounded-0">
                        <div class="section-header">
                            <i class="bi bi-box-seam"></i> Materials Details
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="material_roof_loaded" class="form-label required-field">Material Roof Loaded</label>
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
                        <div class="card-footer d-flex justify-content-between">
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
                    <div class="card mb-0 border-0 rounded-0">
                        <div class="section-header">
                            <i class="bi bi-clipboard-check"></i> Inspections and Substitutions
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
                                    <div class="terms-card">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                id="material_verification" name="material_verification" value="1">
                                            <label class="form-check-label fw-semibold" for="material_verification">
                                                Material Verification
                                            </label>
                                            <p class="text-muted mb-0 mt-1 ps-4">
                                                I understand it is my company's responsibility to alert Contracting Alliance the night before construction if materials are not on site.
                                            </p>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                id="stop_work_request" name="stop_work_request" value="1">
                                            <label class="form-check-label fw-semibold" for="stop_work_request">
                                                Stop Work Request
                                            </label>
                                            <p class="text-muted mb-0 mt-1 ps-4">
                                                Our company is obligated to notify Contracting Alliance by 4:00 PM Central Time on the day prior to any scheduled construction if the project is to be put on hold.
                                            </p>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                id="documentationattachment" name="documentationattachment" value="1">
                                            <label class="form-check-label fw-semibold" for="documentationattachment">
                                                Required Documentation
                                            </label>
                                            <p class="text-muted mb-0 mt-1 ps-4">
                                                Aerial measurement, material order, and photos are required. If not included, this can delay your build.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
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
                    <div class="card mb-0 border-0 rounded-0">
                        <div class="section-header">
                            <i class="bi bi-paperclip"></i> Attachments
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach (['aerial_measurement' => 'Aerial Measurement', 'material_order' => 'Material Order', 'file_upload' => 'Other Files (Permit / SOL / etc)'] as $field => $label)
                                    <div class="col-md-6">
                                        <div class="file-group">
                                            <label class="form-label">{{ $label }}</label>
                                            <div id="{{ $field }}-container">
                                                <div class="input-group mb-2">
                                                    <input type="file" name="{{ $field }}[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                                    <button type="button" class="btn btn-outline-secondary add-file" data-target="{{ $field }}-container">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block">Accepted formats: PDF, JPG, PNG, WEBP</small>
                                        </div>

                                        @php
                                            $files = $job->$field ?? [];
                                        @endphp

                                        @if (!empty($files))
                                            <div class="existing-files mt-3">
                                                <h6 class="fw-semibold mb-2">Uploaded Files:</h6>
                                                @foreach ($files as $file)
                                                    @php
                                                        $fileUrl = asset('storage/' . $file);
                                                        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                    @endphp

                                                    <div class="file-preview">
                                                        <div class="d-flex align-items-center">
                                                            @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp']))
                                                                <img src="{{ $fileUrl }}" alt="Preview" class="me-2">
                                                            @elseif ($fileExtension === 'pdf')
                                                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-4 me-2"></i>
                                                            @endif
                                                            <span class="text-truncate" style="max-width: 150px;">
                                                                {{ basename($file) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-primary me-1">
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
                        <div class="card-footer d-flex justify-content-between">
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

            // Agregar campos de archivo dinámicamente
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

            // Establecer la fecha mínima para el campo de fecha de instalación
            const installDateField = document.getElementById('install_date_requested');
            if (installDateField) {
                const today = new Date().toISOString().split('T')[0];
                installDateField.min = today;
            }
        });
    </script>
</body>
</html>
@endsection