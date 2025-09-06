@extends('layouts.app')

@section('content')
 <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #7f8c8d;
            --success-color: #021949;
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
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="bi bi-clipboard-data me-2"></i> Emergency Request Details
                    </h1>
                </div>
                <div class="d-flex align-items-center">
                    <div class="btn-group">
                        <a href="{{ route('calendar.view') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                        <a href="{{ route('emergency.edit', $emergency->id) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil-square me-2"></i> Edit Job
                        </a>
                        <form id="delete-emergency-form-{{ $emergency->id }}" action="{{ route('emergency.destroy', $emergency->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $emergency->id }})">
                                <i class="bi bi-trash me-1"></i> Delete Emergency
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Job Information -->
            <div class="col-lg-8">
                <!-- Job Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Job Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-calendar me-2"></i> Date Submitted</p>
                                    <p class="fw-semibold">{{ $emergency->date_submitted }}</p>
                                </div>
                                
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-tag me-2"></i> Type of Supplement</p>
                                    <p class="fw-semibold">{{ $emergency->type_of_supplement }}</p>
                                </div>
                                
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-building me-2"></i> Company Name</p>
                                    <p class="fw-semibold">{{ $emergency->company_name }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-envelope me-2"></i> Contact Email</p>
                                    <p class="fw-semibold">{{ $emergency->company_contact_email }}</p>
                                </div>
                                
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-file-earmark-text me-2"></i> Job Number/Name</p>
                                    <p class="fw-semibold">{{ $emergency->job_number_name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <p class="mb-1 text-secondary"><i class="bi bi-geo-alt me-2"></i> Job Address</p>
                            <p class="fw-semibold">
                                {{ $emergency->job_address }}<br>
                                {{ $emergency->job_address_line2 }}
                            </p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-building me-2"></i> City</p>
                                    <p class="fw-semibold">{{ $emergency->job_city }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-map me-2"></i> State</p>
                                    <p class="fw-semibold">{{ $emergency->job_state }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-item">
                                    <p class="mb-1 text-secondary"><i class="bi bi-postcard me-2"></i> Zip Code</p>
                                    <p class="fw-semibold">{{ $emergency->job_zip_code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terms & Conditions Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-check me-2"></i> Terms & Conditions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-opacity-10 rounded {{ $emergency->terms_conditions ? 'bg-success' : 'bg-danger' }}">
                                    <div class="icon-container {{ $emergency->terms_conditions ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        <i class="bi {{ $emergency->terms_conditions ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Submission Responsibility</h6>
                                        <p class="mb-0 text-muted small">{{ $emergency->terms_conditions ? 'Accepted' : 'Not Accepted' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-opacity-10 rounded {{ $emergency->requirements ? 'bg-success' : 'bg-danger' }}">
                                    <div class="icon-container {{ $emergency->requirements ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        <i class="bi {{ $emergency->requirements ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Supplement Processing</h6>
                                        <p class="mb-0 text-muted small">{{ $emergency->requirements ? 'Accepted' : 'Not Accepted' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Team and Documents -->
            <div class="col-lg-4">
                <!-- Team Members Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Assigned Team Members</h5>
                    </div>
                    <div class="card-body">
                        @forelse ($emergency->teamMembers as $member)
                            <div class="team-member">
                                <div class="icon-container">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $member->name }}</h6>
                                    <p class="mb-0 text-muted small">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</p>
                                </div>
                                <span class="badge bg-secondary text-uppercase">{{ $member->role }}</span>
                            </div>
                        @empty
                            <div class="text-muted fst-italic">No team members assigned to this emergency.</div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Attached Documents Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-paperclip me-2"></i> Attached Documents</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $aerials = $emergency->aerial_measurement_path ?? [];
                            $contracts = $emergency->contract_upload_path ?? [];
                            $pictures = $emergency->file_picture_upload_path ?? [];
                        @endphp

                        {{-- Aerial Measurements --}}
                        @if (!empty($aerials))
                            <h6 class="fw-semibold mb-3"><i class="bi bi-map me-2"></i> Aerial Measurements</h6>
                            @foreach ($aerials as $file)
                                <div class="file-item">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-container">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $file['original_name'] ?? basename($file['path']) }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($file['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            @endforeach
                        @endif

                        {{-- Contract Files --}}
                        @if (!empty($contracts))
                            <h6 class="fw-semibold mt-4 mb-3"><i class="bi bi-file-earmark-richtext me-2"></i> Contract Uploads</h6>
                            @foreach ($contracts as $file)
                                <div class="file-item">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-container">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $file['original_name'] ?? basename($file['path']) }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($file['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            @endforeach
                        @endif

                        {{-- Additional Files --}}
                        @if (!empty($pictures))
                            <h6 class="fw-semibold mt-4 mb-3"><i class="bi bi-images me-2"></i> Additional Files</h6>
                            @foreach ($pictures as $file)
                                <div class="file-item">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-container">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $file['original_name'] ?? basename($file['path']) }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($file['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            @endforeach
                        @endif

                        @if (empty($aerials) && empty($contracts) && empty($pictures))
                            <p class="text-muted">No documents uploaded for this emergency case.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the emergency.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-emergency-form-' + id).submit();
                }
            });
        }
    </script>
    
    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection