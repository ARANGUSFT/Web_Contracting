@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #7f8c8d;
            --success-color: #27ae60;
            --info-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-shadow: 0 0.125rem 0.625rem rgba(0, 0, 0, 0.08);
            --border-color: #e0e0e0;
        }
        
        body {
            background-color: #f9fafb;
            padding-top: 20px;
            padding-bottom: 40px;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
            margin-bottom: 20px;
            background-color: #fff;
        }
        
        .card:hover {
            box-shadow: var(--card-shadow);
        }
        
        .card-header {
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            background-color: #f8f9fa;
            color: var(--primary-color);
            padding: 15px 20px;
        }
        
        .btn {
            border-radius: 4px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1a2530;
            border-color: #1a2530;
            transform: none;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: none;
        }
        
        .btn-outline-secondary {
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: none;
        }
        
        .detail-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .badge {
            border-radius: 4px;
            padding: 6px 10px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .file-item {
            background-color: var(--light-bg);
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
            border: 1px solid var(--border-color);
        }
        
        .file-item:hover {
            background-color: #f1f3f5;
        }
        
        .team-member {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            margin-bottom: 8px;
            background-color: var(--light-bg);
            border-radius: 4px;
            transition: all 0.2s;
            border: 1px solid var(--border-color);
        }
        
        .team-member:hover {
            background-color: #f1f3f5;
        }
        
        .header-section {
            background: #fff;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid var(--border-color);
        }
        
        .icon-container {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
            background-color: #f1f3f5;
            color: var(--primary-color);
        }
        
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #f1f3f5;
            color: var(--secondary-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .bg-success {
            background-color: var(--success-color) !important;
        }
        
        .bg-info {
            background-color: var(--info-color) !important;
        }
        
        .bg-danger {
            background-color: var(--danger-color) !important;
        }
        
        .text-muted {
            color: #95a5a6 !important;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .section-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-color);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-item strong {
            display: block;
            color: var(--secondary-color);
            font-weight: 500;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="bi bi-clipboard2-check me-2"></i> Job Request #{{ $job->id }}
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar3 me-1"></i> 
                        Created on {{ $job->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="btn-group">
                        <a href="{{ route('calendar.view') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil me-2"></i> Edit Job
                        </a>
                        <form id="delete-job-form-{{ $job->id }}" action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmJobDelete({{ $job->id }})">
                                <i class="bi bi-trash me-1"></i> Delete Job
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Job Information -->
            <div class="col-lg-8">
                <!-- General Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> General Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <strong><i class="bi bi-calendar-event me-2"></i>Install Date</strong>
                                        {{ $job->install_date_requested }}
                                 
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-building me-2"></i>Company</strong>
                                    {{ $job->company_name }}
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-person me-2"></i>Representative</strong>
                                    {{ $job->company_rep }}
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-telephone me-2"></i>Phone</strong>
                                    {{ $job->company_rep_phone }}
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-envelope me-2"></i>Email</strong>
                                    {{ $job->company_rep_email }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <strong><i class="bi bi-person-badge me-2"></i>Customer</strong>
                                    {{ $job->customer_first_name }} {{ $job->customer_last_name }}
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-phone me-2"></i>Customer Phone</strong>
                                    {{ $job->customer_phone_number }}
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-tag me-2"></i>Job Name</strong>
                                    {{ $job->job_number_name }}
                                </div>
                                
                                <div class="info-item">
                                    <strong><i class="bi bi-geo-alt me-2"></i>Address</strong>
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
                
                <!-- Project Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clipboard2-data me-2"></i> Project Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Materials</h6>
                                <div class="info-item"><strong>Starter Bundles</strong> {{ $job->starter_bundles_ordered }}</div>
                                <div class="info-item"><strong>Hip & Ridge</strong> {{ $job->hip_and_ridge_ordered }}</div>
                                <div class="info-item"><strong>Field Shingles</strong> {{ $job->field_shingle_bundles_ordered }}</div>
                                <div class="info-item"><strong>Cap Rolls</strong> {{ $job->modified_bitumen_cap_rolls_ordered }}</div>
                                
                                <h6 class="text-muted mt-4 mb-3">Dates</h6>
                                <div class="info-item"><strong>Delivery Date</strong> {{ $job->delivery_date }}</div>
                                <div class="info-item"><strong>Mid Roof Inspection</strong> {{ $job->mid_roof_inspection }}</div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Work Specifications</h6>
                                <div class="info-item">
                                    <strong>Siding Replacement</strong> 
                                    <span class="badge bg-light text-dark">{{ $job->siding_being_replaced }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Shingle Layers</strong> 
                                    <span class="badge bg-light text-dark">{{ $job->asphalt_shingle_layers_to_remove }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Re-deck</strong> 
                                    <span class="badge bg-light text-dark">{{ $job->re_deck }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Skylights</strong> 
                                    <span class="badge bg-light text-dark">{{ $job->skylights_replace }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Gutters</strong> 
                                    <span class="badge bg-light text-dark">Remove: {{ $job->gutter_remove }}</span>
                                    <span class="badge bg-light text-dark ms-1">Reset: {{ $job->gutter_detached_and_reset }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Documentation and Team -->
            <div class="col-lg-4">
                <!-- Documentation Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-files me-2"></i> Documentation</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <strong><i class="bi bi-chat-square-text me-2"></i>Special Instructions</strong>
                            <div class="mt-2 p-3 bg-light rounded">{{ $job->special_instructions }}</div>
                        </div>
                        
                        <div class="info-item">
                            <strong><i class="bi bi-check-circle me-2"></i>Material Verification</strong> 
                            <span class="badge bg-{{ $job->material_verification ? 'success' : 'secondary' }}">
                                {{ $job->material_verification ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Stop Work Request</strong> 
                            <span class="badge bg-{{ $job->stop_work_request ? 'danger' : 'secondary' }}">
                                {{ $job->stop_work_request ? 'Active' : 'None' }}
                            </span>
                        </div>
                        
                        <!-- Assigned Team Members -->
                        <div class="info-item">
                            <strong><i class="bi bi-people-fill me-2"></i>Assigned Team Members</strong>
                            @if($job->teamMembers->isEmpty())
                                <div class="mt-2 text-muted">No team members assigned.</div>
                            @else
                                <div class="mt-3">
                                    @foreach($job->teamMembers as $member)
                                        <div class="team-member">
                                            <div class="icon-container">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $member->name }}</div>
                                                <span class="text-muted small">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</span>
                                            </div>
                                            <span class="badge bg-secondary text-uppercase">{{ $member->role }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Attachments Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-paperclip me-2"></i> Attachments</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $categories = [
                                ['title' => 'Aerial Measurements', 'field' => $job->aerial_measurement, 'icon' => 'bi-map'],
                                ['title' => 'Material Orders', 'field' => $job->material_order, 'icon' => 'bi-cart-check'],
                                ['title' => 'Other Files', 'field' => $job->file_upload, 'icon' => 'bi-file-earmark'],
                            ];
                        @endphp
                    
                        @foreach ($categories as $category)
                            @if (!empty($category['field']))
                                <h6 class="fw-semibold mt-3 mb-2"><i class="{{ $category['icon'] }} me-2"></i> {{ $category['title'] }}</h6>
                                
                                @php
                                    // Manejar tanto arrays como strings
                                    $files = is_array($category['field']) ? $category['field'] : [$category['field']];
                                @endphp
                    
                                @foreach ($files as $file)
                                    @php
                                        // Manejar diferentes formatos de archivo (string o array)
                                        if (is_array($file)) {
                                            $path = $file['path'] ?? '';
                                            $filename = $file['original_name'] ?? basename($path);
                                        } else {
                                            $path = $file;
                                            $filename = basename($path);
                                        }
                                        
                                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        $url = asset('storage/' . $path);
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                    
                                    <div class="file-item">
                                        <div class="d-flex align-items-center">
                                            @if ($isImage)
                                                <i class="bi bi-file-image-fill text-primary me-2"></i>
                                            @elseif ($extension === 'pdf')
                                                <i class="bi bi-file-pdf-fill text-danger me-2"></i>
                                            @elseif (in_array($extension, ['xlsx', 'xls', 'csv']))
                                                <i class="bi bi-file-spreadsheet-fill text-success me-2"></i>
                                            @elseif (in_array($extension, ['doc', 'docx']))
                                                <i class="bi bi-file-word-fill text-info me-2"></i>
                                            @elseif ($extension === 'zip')
                                                <i class="bi bi-file-zip-fill text-warning me-2"></i>
                                            @else
                                                <i class="bi bi-file-earmark-fill text-secondary me-2"></i>
                                            @endif
                    
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $filename }}</div>
                                                <small class="text-muted text-uppercase">{{ $extension }}</small>
                                            </div>
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
                            @endif
                        @endforeach
                    
                        @if (empty($job->aerial_measurement) && empty($job->material_order) && empty($job->file_upload))
                            <p class="text-muted">No attachments available for this job.</p>
                        @endif
                    </div>
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
    
    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection