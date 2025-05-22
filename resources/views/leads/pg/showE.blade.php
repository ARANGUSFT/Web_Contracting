@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header with back button and refresh -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('calendar.view') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('emergency.edit', $emergency->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-2"></i> Edit Job
            </a>
            
        </div>
        <h2 class="text-primary mb-0">
            <i class="bi bi-clipboard-data me-2"></i> Emergency Request Details
        </h2>
    </div>

    <!-- Job Information Card -->
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-info-circle me-2"></i> Job Information
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="bi bi-calendar me-2"></i>Date Submitted:</strong></p>
                    <p class="text-muted">{{ $emergency->date_submitted }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="bi bi-tag me-2"></i>Type of Supplement:</strong></p>
                    <p class="text-muted">{{ $emergency->type_of_supplement }}</p>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="bi bi-building me-2"></i>Company Name:</strong></p>
                    <p class="text-muted">{{ $emergency->company_name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="bi bi-envelope me-2"></i>Contact Email:</strong></p>
                    <p class="text-muted">{{ $emergency->company_contact_email }}</p>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="bi bi-file-earmark-text me-2"></i>Job Number/Name:</strong></p>
                    <p class="text-muted">{{ $emergency->job_number_name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="bi bi-geo-alt me-2"></i>Job Address:</strong></p>
                    <p class="text-muted">
                        {{ $emergency->job_address }}<br>
                        {{ $emergency->job_address_line2 }}
                    </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1"><strong><i class="bi bi-building me-2"></i>City:</strong></p>
                    <p class="text-muted">{{ $emergency->job_city }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong><i class="bi bi-map me-2"></i>State:</strong></p>
                    <p class="text-muted">{{ $emergency->job_state }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong><i class="bi bi-postcard me-2"></i>Zip Code:</strong></p>
                    <p class="text-muted">{{ $emergency->job_zip_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Card -->
    <div class="card shadow-sm mb-4 border-success">
        <div class="card-header bg-success text-white fw-bold">
            <i class="bi bi-file-earmark-check me-2"></i> Terms & Conditions
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <p class="mb-1"><strong><i class="bi bi-check-circle me-2"></i>Submission Responsibility:</strong></p>
                    {!! $emergency->terms_conditions ? 
                        '<span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Accepted</span>' : 
                        '<span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i> Not Accepted</span>' !!}
                </div>
                <div class="col-md-6 mb-3">
                    <p class="mb-1"><strong><i class="bi bi-check-circle me-2"></i>Supplement Processing:</strong></p>
                    {!! $emergency->requirements ? 
                        '<span class="badge bg-success"><i class="bi bi-check-lg me-1"></i> Accepted</span>' : 
                        '<span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i> Not Accepted</span>' !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Attached Documents Card -->
    <div class="card shadow-sm mb-4 border-info">
        <div class="card-header bg-info text-white fw-bold">
            <i class="bi bi-paperclip me-2"></i> Attached Documents
        </div>
        <div class="card-body">
            @if (is_array($emergency->aerial_measurement_path) && count($emergency->aerial_measurement_path))
                <div class="mb-4">
                    <h5 class="text-info mb-3">
                        <i class="bi bi-cloud-arrow-down me-2"></i> Aerial Measurements
                    </h5>
                    <div class="list-group">
                        @foreach ($emergency->aerial_measurement_path as $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark-arrow-down me-2"></i> 
                                    {{ basename($file) }}
                                </div>
                                <span class="badge bg-primary rounded-pill">Download</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (is_array($emergency->contract_upload_path) && count($emergency->contract_upload_path))
                <div class="mb-4">
                    <h5 class="text-info mb-3">
                        <i class="bi bi-journal-text me-2"></i> Contracts
                    </h5>
                    <div class="list-group">
                        @foreach ($emergency->contract_upload_path as $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark-pdf me-2"></i> 
                                    {{ basename($file) }}
                                </div>
                                <span class="badge bg-primary rounded-pill">Download</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (is_array($emergency->file_picture_upload_path) && count($emergency->file_picture_upload_path))
                <div class="mb-4">
                    <h5 class="text-info mb-3">
                        <i class="bi bi-images me-2"></i> Additional Pictures
                    </h5>
                    <div class="row">
                        @foreach ($emergency->file_picture_upload_path as $file)
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="card h-100">
                                    @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png']))
                                        <img src="{{ asset('storage/' . $file) }}" 
                                             class="card-img-top img-thumbnail" 
                                             alt="Image" 
                                             style="height: 150px; object-fit: cover;">
                                    @else
                                        <div class="card-body text-center py-4">
                                            <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-footer bg-white border-top-0 text-center">
                                        <a href="{{ asset('storage/' . $file) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-info w-100">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!is_array($emergency->aerial_measurement_path) && !is_array($emergency->contract_upload_path) && !is_array($emergency->file_picture_upload_path))
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i> No documents attached to this request.
                </div>
            @endif
        </div>
    </div>
    
</div>
@endsection