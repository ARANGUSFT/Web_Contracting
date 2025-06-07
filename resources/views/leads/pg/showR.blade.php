@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    /* Estilos mejorados */
    .job-header {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-weight: 600;
        font-size: 1.25rem;
        color: #2c3e50;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background-color: #4361ee;
    }
    
    .info-card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    
    .info-item {
        margin-bottom: 0.75rem;
        padding: 0.5rem 0;
    }
    
    .info-item strong {
        display: inline-block;
        width: 200px;
        color: #6c757d;
        font-weight: 500;
    }
    
    .document-card {
        transition: transform 0.2s;
        height: 100%;
    }
    
    .document-card:hover {
        transform: translateY(-5px);
    }
    
    .document-preview {
        height: 200px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
    
    .document-preview img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .badge-status {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
</style>

<div class="container py-4">

    {{-- Encabezado mejorado --}}
    <div class="job-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h3 class="mb-1">
                    <i class="bi bi-clipboard2-check-fill text-primary me-2"></i> 
                    Job Request #{{ $job->id }}
                </h3>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar3 me-1"></i> 
                    Created on {{ $job->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-2"></i> Edit Job
                </a>
                
                <a href="{{ route('calendar.view') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i> Back
                </a>
                <form id="delete-job-form-{{ $job->id }}" action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger" onclick="confirmJobDelete({{ $job->id }})">
                        <i class="bi bi-trash me-2"></i> Delete Job
                    </button>
                </form>
                
            </div>
            
        </div>
    </div>

    {{-- Información General --}}
    <div class="info-card card">
        <div class="card-body">
            <h5 class="section-title">
                <i class="bi bi-info-circle me-2"></i> General Information
            </h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <strong><i class="bi bi-calendar-event me-2"></i>Install Date:</strong> 
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $job->install_date_requested }}
                        </span>
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-building me-2"></i>Company:</strong> 
                        {{ $job->company_name }}
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-person me-2"></i>Representative:</strong> 
                        {{ $job->company_rep }}
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-telephone me-2"></i>Phone:</strong> 
                        {{ $job->company_rep_phone }}
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-envelope me-2"></i>Email:</strong> 
                        {{ $job->company_rep_email }}
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-item">
                        <strong><i class="bi bi-person-badge me-2"></i>Customer:</strong> 
                        {{ $job->customer_first_name }} {{ $job->customer_last_name }}
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-phone me-2"></i>Customer Phone:</strong> 
                        {{ $job->customer_phone_number }}
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-tag me-2"></i>Job Name:</strong> 
                        {{ $job->job_number_name }}
                    </div>
                    <div class="info-item">
                        <strong><i class="bi bi-geo-alt me-2"></i>Address:</strong> 
                        {{ $job->job_address_street_address }}
                        @if ($job->job_address_street_address_line_2)
                            <br>{{ $job->job_address_street_address_line_2 }}
                        @endif
                        <br>{{ $job->job_address_city }}, {{ $job->job_address_state }} {{ $job->job_address_zip_code }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalles del Proyecto --}}
    <div class="info-card card">
        <div class="card-body">
            <h5 class="section-title">
                <i class="bi bi-clipboard2-data me-2"></i> Project Details
            </h5>
            
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Materials</h6>
                    <div class="info-item"><strong>Starter Bundles:</strong> {{ $job->starter_bundles_ordered }}</div>
                    <div class="info-item"><strong>Hip & Ridge:</strong> {{ $job->hip_and_ridge_ordered }}</div>
                    <div class="info-item"><strong>Field Shingles:</strong> {{ $job->field_shingle_bundles_ordered }}</div>
                    <div class="info-item"><strong>Cap Rolls:</strong> {{ $job->modified_bitumen_cap_rolls_ordered }}</div>
                    
                    <h6 class="text-muted mt-4 mb-3">Dates</h6>
                    <div class="info-item"><strong>Delivery Date:</strong> {{ $job->delivery_date }}</div>
                    <div class="info-item"><strong>Mid Roof Inspection:</strong> {{ $job->mid_roof_inspection }}</div>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Work Specifications</h6>
                    <div class="info-item">
                        <strong>Siding Replacement:</strong> 
                        <span class="badge bg-light text-dark">{{ $job->siding_being_replaced }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Shingle Layers:</strong> 
                        <span class="badge bg-light text-dark">{{ $job->asphalt_shingle_layers_to_remove }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Re-deck:</strong> 
                        <span class="badge bg-light text-dark">{{ $job->re_deck }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Skylights:</strong> 
                        <span class="badge bg-light text-dark">{{ $job->skylights_replace }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Gutters:</strong> 
                        <span class="badge bg-light text-dark">Remove: {{ $job->gutter_remove }}</span>
                        <span class="badge bg-light text-dark ms-1">Reset: {{ $job->gutter_detached_and_reset }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Documentación --}}
    <div class="info-card card">
        <div class="card-body">
            <h5 class="section-title">
                <i class="bi bi-files me-2"></i> Documentation
            </h5>
            
            <div class="mb-4">
                <div class="info-item">
                    <strong><i class="bi bi-chat-square-text me-2"></i>Special Instructions:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $job->special_instructions }}</div>
                </div>
                <div class="info-item">
                    <strong><i class="bi bi-check-circle me-2"></i>Material Verification:</strong> 
                    <span class="badge-status bg-{{ $job->material_verification ? 'success' : 'secondary' }}">
                        {{ $job->material_verification ? 'Verified' : 'Pending' }}
                    </span>
                </div>
                <div class="info-item">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Stop Work Request:</strong> 
                    <span class="badge-status bg-{{ $job->stop_work_request ? 'danger' : 'secondary' }}">
                        {{ $job->stop_work_request ? 'Active' : 'None' }}
                    </span>
                </div>
            </div>
            

 {{-- Documentation --}}
<div class="info-card card">
    <div class="card-body">
        <h5 class="section-title">
            <i class="bi bi-files me-2"></i> Documentation
        </h5>
        
        <div class="mb-4">
            <div class="info-item">
                <strong><i class="bi bi-chat-square-text me-2"></i>Special Instructions:</strong>
                <div class="mt-2 p-3 bg-light rounded">{{ $job->special_instructions }}</div>
            </div>
            <div class="info-item">
                <strong><i class="bi bi-check-circle me-2"></i>Material Verification:</strong> 
                <span class="badge-status bg-{{ $job->material_verification ? 'success' : 'secondary' }}">
                    {{ $job->material_verification ? 'Verified' : 'Pending' }}
                </span>
            </div>
            <div class="info-item">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Stop Work Request:</strong> 
                <span class="badge-status bg-{{ $job->stop_work_request ? 'danger' : 'secondary' }}">
                    {{ $job->stop_work_request ? 'Active' : 'None' }}
                </span>
            </div>
        </div>

        {{-- Assigned Team Members --}}
        <div class="info-item mt-4">
                    <strong><i class="bi bi-people-fill me-2"></i>Assigned Team Members:</strong>
                    @if($job->teamMembers->isEmpty())
                        <div class="mt-2 text-muted">No team members assigned.</div>
                    @else
                        <div class="mt-3">
                            @foreach($job->teamMembers as $member)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle text-primary me-2 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">{{ $member->name }}</div>
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

         
            
            <h5 class="text-primary fw-bold mb-4">
                <i class="bi bi-paperclip me-2"></i> Attachments
            </h5>
            
            <div class="row g-4">
                @php
                    $categories = [
                        ['title' => 'Aerial Measurements', 'field' => $job->aerial_measurement, 'icon' => 'bi-map'],
                        ['title' => 'Material Orders', 'field' => $job->material_order, 'icon' => 'bi-cart-check'],
                        ['title' => 'Other Files', 'field' => $job->file_upload, 'icon' => 'bi-upload'],
                    ];
                @endphp
            
                @foreach ($categories as $category)
                    @if (!empty($category['field']))
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-light fw-semibold">
                                    <i class="{{ $category['icon'] }} me-2 text-primary"></i> {{ $category['title'] }}
                                </div>
                                <div class="card-body">
                                    @php
                                        $files = is_array($category['field']) ? $category['field'] : [$category['field']];
                                    @endphp
            
                                    @foreach ($files as $file)
                                        @php
                                            $path = trim($file, '[]"');
                                            $filename = basename($path);
                                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                            $url = asset('storage/' . $path);
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp
            
                                        <div class="d-flex align-items-center border p-2 rounded mb-3">
                                            @if ($isImage)
                                                <img src="{{ $url }}" alt="{{ $filename }}" class="me-3" style="width: 64px; height: 64px; object-fit: cover; border-radius: 6px;">
                                            @elseif ($extension === 'pdf')
                                                <i class="bi bi-file-pdf-fill text-danger fs-1 me-3"></i>
                                            @else
                                                <i class="bi bi-file-earmark-fill text-secondary fs-1 me-3"></i>
                                            @endif
            
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $filename }}</div>
                                                <small class="text-muted text-uppercase">{{ $extension }}</small>
                                            </div>
                                            <div class="ms-3">
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ $url }}" download class="btn btn-sm btn-outline-success" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            
        </div>
    </div>

</div>
<script>
    function confirmJobDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the job and all related data.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-job-form-' + id).submit();
            }
        });
    }
    </script>

@endsection