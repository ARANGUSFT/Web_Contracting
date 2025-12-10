@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="text-primary m-0">
            <i class="bi bi-person-circle"></i> Customer Details
        </h2>
        <div class="d-flex align-items-center gap-3">
            <!-- Financial Summary -->
            <div class="text-end">
                <h5 class="fw-bold mb-0" id="totalAmountText">$0.00</h5>
                <div class="text-danger fw-bold small">Balance Due</div>
                <div class="text-danger small" id="balanceDueText">$0.00</div>
            </div>
            
            <!-- Progress Chart -->
            <div class="position-relative" style="width: 70px; height: 70px;">
                <canvas id="balanceChart" class="balance-chart"></canvas>
                <div class="position-absolute top-50 start-50 translate-middle fw-bold small" id="chartPercentageText">0%</div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $statusList = [
            1 => ['label' => 'Lead', 'color' => 'bg-warning', 'icon' => 'bi-person'],
            2 => ['label' => 'Prospect', 'color' => 'bg-orange', 'icon' => 'bi-search'],
            3 => ['label' => 'Approved', 'color' => 'bg-success', 'icon' => 'bi-check-circle'],
            4 => ['label' => 'Completed', 'color' => 'bg-primary', 'icon' => 'bi-check-all'],
            5 => ['label' => 'Invoiced', 'color' => 'bg-info', 'icon' => 'bi-receipt'],
            6 => ['label' => 'Finish', 'color' => 'bg-secondary', 'icon' => 'bi-flag-fill'],
            7 => ['label' => 'Cancelled', 'color' => 'bg-danger', 'icon' => 'bi-x-circle'],
        ];

        $currentIndex = array_search($lead->estado, array_keys($statusList));
        $statusKeys = array_keys($statusList);
    @endphp

    <!-- Main Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('guest.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Leads
                </a>
                
                <h4 class="text-primary mb-0">
                    {{ $lead->first_name }} {{ $lead->last_name }}
                </h4>
                
                <span class="badge {{ $statusList[$lead->estado]['color'] }} fs-6">
                    <i class="bi {{ $statusList[$lead->estado]['icon'] }} me-1"></i>
                    {{ $statusList[$lead->estado]['label'] }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Contact Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-telephone text-primary me-2"></i>
                        <strong class="me-2">Phone:</strong>
                        <a href="tel:{{ $lead->phone }}" class="text-decoration-none">{{ $lead->phone }}</a>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-envelope text-primary me-2"></i>
                        <strong class="me-2">Email:</strong>
                        <a href="mailto:{{ $lead->email }}" class="text-decoration-none">{{ $lead->email }}</a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar text-primary me-2"></i>
                        <strong class="me-2">Created:</strong>
                        <span>{{ $lead->created_at->format('d M, Y') }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-clock text-primary me-2"></i>
                        <strong class="me-2">Last Touched:</strong>
                        <span>{{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}</span>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="mb-4 p-3 bg-light rounded">
                <div class="d-flex align-items-start">
                    <i class="bi bi-geo-alt text-warning mt-1 me-2"></i>
                    <div>
                        <strong class="d-block mb-1">Address</strong>
                        {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
                    </div>
                </div>
            </div>


            <!-- Financial Summary -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-md-4 mb-3">
                    <div class="card border-danger h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-up-circle text-danger h4"></i>
                            <h6 class="card-title text-muted">Total Expenses</h6>
                            <div id="totalExpensesDisplayBelow" class="h4 text-danger">
                                ${{ number_format($lead->total_expenses, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-coin text-primary h4"></i>
                            <h6 class="card-title text-muted">Total Paid</h6>
                            <div id="totalPaidDisplayBelow" class="h4 text-primary">
                                ${{ number_format($lead->total_paid, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card {{ $lead->net_profit >= 0 ? 'border-success' : 'border-warning' }} h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up {{ $lead->net_profit >= 0 ? 'text-success' : 'text-warning' }} h4"></i>
                            <h6 class="card-title text-muted">Net Profit</h6>
                            <div id="netProfitDisplayBelow" class="h4 fw-bold {{ $lead->net_profit >= 0 ? 'text-success' : 'text-warning' }}">
                                ${{ number_format($lead->net_profit, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-box {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.status-active {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.status-inactive {
    opacity: 0.6;
}

.status-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.bg-orange {
    background-color: #fd7e14 !important;
}

.balance-chart {
    border-radius: 50%;
}
</style>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

function changeStatus(status) {
    document.getElementById('selectedStatus').value = status;
    document.getElementById('statusForm').submit();
}

function handleNextClick(nextStatus) {
    if (nextStatus === 3) {
        // Scroll to the approved form section
        document.querySelector('.card-border-success')?.scrollIntoView({ 
            behavior: 'smooth' 
        });
    } else {
        changeStatus(nextStatus);
    }
}

// Initialize chart (you'll need to implement this based on your data)
function initializeBalanceChart() {
    const ctx = document.getElementById('balanceChart').getContext('2d');
    // Add your chart initialization logic here
}
</script>




    

     <!-- Pestañas -->
    <ul class="nav nav-tabs mt-4" id="leadTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#photos">Photos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a>
        </li>
    
    </ul>


    <!-- Toast de éxito -->
    @if(session('success'))
         <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
             <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
                 <div class="d-flex">
                     <div class="toast-body">
                         {{ session('success') }}
                     </div>
                     <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                 </div>
             </div>
         </div>
    @endif


    <div class="tab-content p-4 bg-white shadow-lg rounded">
        
        <!-- Chat Tab -->
        <div class="tab-pane fade show active" id="chat">
                <h4 class="mb-3"><i class="bi bi-chat-dots me-2"></i> Conversation</h4>
    
                <div id="chat-box" class="border rounded shadow-sm p-3 mb-4" style="height: 350px; overflow-y: auto; background-color: #f2f6fb;">
                    @foreach($messages as $msg)
                        @php
                            $isSeller = isset($msg->team);
                            $senderName = $isSeller ? $msg->team->name : ($msg->user->company_name ?? 'Usuario');
                            $isMine = $msg->user_id == auth()->id();
    
                            $alignment = $isMine ? 'justify-content-end' : 'justify-content-start';
                            $bubbleClass = $isMine ? 'bg-primary text-white' : 'bg-white text-dark';
                            $nameColor = $isMine ? 'text-light' : 'text-muted';
                            $timeAlign = $isMine ? 'text-end' : 'text-start';
                        @endphp
    
                        <div class="d-flex {{ $alignment }} mb-3">
                            <div class="p-3 rounded shadow-sm {{ $bubbleClass }}" style="max-width: 80%;">
                                <div class="fw-bold small {{ $nameColor }}">{{ $senderName }}</div>
                                <div class="small">{{ $msg->message }}</div>
                                <div class="small text-muted {{ $timeAlign }} mt-1" style="font-size: 0.75rem;">
                                    {{ $msg->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
    
                <form id="chatForm" method="POST" action="{{ route('lead.messages.store') }}">
                    @csrf
                    <input type="hidden" id="lead_id" name="lead_id" value="{{ $lead->id }}">
                    <div class="input-group">
                        <input type="text" id="message" name="message" class="form-control rounded-start-pill" placeholder="Write a message..." required>
                        <button class="btn btn-success rounded-end-pill px-4" type="submit">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
        </div>



        <!-- Photos Tab -->
        <div class="tab-pane fade" id="photos">
            
            <!-- Header Section - Rediseñado -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-images text-primary fs-4"></i>
                    </div>
                    <div>
                        <h4 class="text-dark mb-1 fw-bold">Photo Gallery</h4>
                        <p class="text-muted small mb-0">Manage and organize your lead photos</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6" id="totalImagesCounter">
                        <i class="bi bi-image me-1"></i>{{ $images->count() }}
                    </span>
                </div>
            </div>

            <!-- Upload Card - Diseño Moderno -->
            <div class="card mb-4 border-0 shadow-lg rounded-3">
                <div class="card-header bg-transparent border-0 py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-cloud-arrow-up text-success fs-5"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">Upload New Photos</h5>
                    </div>
                </div>
                
                <div class="card-body pt-0">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                        <!-- Modern Upload Zone -->
                        <div class="mb-4">
                            <div class="upload-zone border-dashed rounded-4 p-5 text-center bg-light bg-gradient position-relative overflow-hidden">
                                <div class="upload-zone-content">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                                        <i class="bi bi-cloud-arrow-up text-primary fs-1"></i>
                                    </div>
                                    <h5 class="text-dark mb-2 fw-semibold">Drop your images here</h5>
                                    <p class="text-muted mb-3">or click to browse your files</p>
                                    
                                    <button type="button" 
                                            class="btn btn-primary btn-lg px-4 rounded-pill fw-semibold"
                                            onclick="document.getElementById('images').click()">
                                        <i class="bi bi-folder2-open me-2"></i>Choose Files
                                    </button>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            JPG, PNG, WEBP • Max 50MB each • Up to 200 files
                                        </small>
                                    </div>
                                </div>
                                
                                <!-- Hidden File Input -->
                                <input type="file" 
                                    name="images[]" 
                                    id="images" 
                                    multiple 
                                    class="form-control d-none" 
                                    accept="image/*"
                                    aria-label="Select images to upload">
                            </div>
                        </div>

                        <!-- Progress Bar - Mejorado -->
                        <div id="uploadProgress" class="mb-4 d-none" aria-live="polite">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-dark small fw-semibold">
                                    <i class="bi bi-arrow-up-circle me-1"></i>
                                    Uploading...
                                </span>
                                <span class="text-primary fw-bold" id="progressPercent">0%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px;">
                                <div id="progressFill" 
                                    class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                    role="progressbar" 
                                    style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <!-- Image Previews - Grid Moderno -->
                        <div id="previewContainer" class="row g-3 mb-4" aria-live="polite">
                            <!-- Preview cards will be dynamically inserted here -->
                        </div>

                        <!-- Action Bar - Rediseñada -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div id="fileInfo" class="text-muted small fw-medium" aria-live="polite">
                                <!-- File info will be displayed here -->
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" 
                                        class="btn btn-outline-secondary rounded-pill px-4"
                                        onclick="leadImagesManager.resetUploadForm()">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </button>
                                <button type="submit" 
                                        class="btn btn-success rounded-pill px-4 fw-semibold" 
                                        id="uploadBtn" 
                                        disabled>
                                    <i class="bi bi-cloud-arrow-up me-2"></i>Upload All
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions - Toolbar Flotante -->
            <div class="card mb-4 border-0 shadow-lg rounded-3 sticky-top" id="bulkActionsCard" style="display: none; top: 20px; z-index: 1000;">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                    id="selectAll" 
                                    class="form-check-input"
                                    style="width: 18px; height: 18px;"
                                    aria-label="Select all images">
                                <label for="selectAll" class="form-check-label text-dark fw-medium ms-2">
                                    Select All
                                </label>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary border-0 px-3 py-2" id="selectedCount">
                                <i class="bi bi-check2-circle me-1"></i><span id="selectedCountText">0</span> selected
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <button id="downloadSelectedBtn" 
                                    class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                    disabled>
                                <i class="bi bi-download me-1"></i>Download
                            </button>
                            <button id="deleteSelectedBtn" 
                                    class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                    disabled>
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                            <button id="deleteAllBtn" 
                                    class="btn btn-danger btn-sm rounded-pill px-3">
                                <i class="bi bi-trash-fill me-1"></i>Delete All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Section - Diseño Moderno -->
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-header bg-transparent border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Photo Collection
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small" id="pageInfo">Page 1 of 1</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- Gallery Container -->
                    <div id="galleryContainer" class="p-4">
                        <!-- Dynamic Gallery -->
                        <div id="dynamicGallery">
                            <div id="gallery" class="row g-4"></div>
                        </div>

                        <!-- Empty State - Rediseñado -->
                        <div id="emptyState" class="text-center py-5" style="display: {{ $images->count() > 0 ? 'none' : 'block' }};">
                            <div class="empty-state">
                                <div class="bg-light bg-gradient rounded-4 p-5 mx-auto" style="max-width: 400px;">
                                    <i class="bi bi-images display-1 text-muted opacity-25 mb-4"></i>
                                    <h4 class="text-dark mb-3">No photos yet</h4>
                                    <p class="text-muted mb-4">Upload your first images to get started with your photo gallery.</p>
                                    <button class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('images').click()">
                                        <i class="bi bi-cloud-arrow-up me-2"></i>Upload Photos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination - Mejorado -->
                    <div class="card-footer bg-transparent border-0 py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <button id="prevPage" class="btn btn-outline-primary rounded-pill px-4" disabled>
                                <i class="bi bi-chevron-left me-2"></i>Previous
                            </button>
                            <div class="text-center">
                                <span class="text-muted small" id="pageInfoDetailed">Showing 0 images</span>
                            </div>
                            <button id="nextPage" class="btn btn-outline-primary rounded-pill px-4" disabled>
                                Next<i class="bi bi-chevron-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<!-- Toast Container for Notifications -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<!-- Custom Styles -->
<style>
:root {
    --primary-color: #0d6efd;
    --primary-light: rgba(13, 110, 253, 0.1);
    --success-color: #198754;
    --danger-color: #dc3545;
    --border-radius: 1rem;
    --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Upload Zone Styles */
.upload-zone {
    border: 2px dashed #dee2e6;
    transition: var(--transition);
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.upload-zone::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: var(--transition);
}

.upload-zone:hover::before {
    left: 100%;
}

.upload-zone:hover {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.upload-zone.dragover {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    transform: scale(1.02);
}

/* Gallery Cards */
.gallery-card {
    border: none;
    border-radius: var(--border-radius);
    transition: var(--transition);
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
}

.gallery-card .card-img-top {
    transition: var(--transition);
}

.gallery-card:hover .card-img-top {
    transform: scale(1.05);
}

/* Image Overlay */
.image-overlay {
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.8) 100%);
    opacity: 0;
    transition: var(--transition);
}

.gallery-card:hover .image-overlay {
    opacity: 1;
}

.image-counter {
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(10px);
    font-weight: 600;
    z-index: 10;
}

/* Preview Images */
.preview-image {
    border-radius: var(--border-radius);
    transition: var(--transition);
    overflow: hidden;
}

.preview-image:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.remove-preview {
    background: rgba(220, 53, 69, 0.9);
    border: none;
    transition: var(--transition);
    z-index: 20;
}

.remove-preview:hover {
    background: var(--danger-color);
    transform: scale(1.1);
}

/* Progress Bar */
.progress {
    background: #e9ecef;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, var(--primary-color), #4dabf7);
    transition: width 0.6s ease;
}

/* Bulk Actions Sticky */
.sticky-top {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

/* Button Styles */
.btn {
    transition: var(--transition);
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), #4dabf7);
    border: none;
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color), #20c997);
    border: none;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color), #e35d6a);
    border: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .upload-zone {
        padding: 2rem 1rem !important;
    }
    
    .upload-zone-content h5 {
        font-size: 1.1rem;
    }
    
    .gallery-card .btn-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .bulk-actions-card .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .upload-zone {
        padding: 1.5rem 0.75rem !important;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
}

/* Animation for new items */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.gallery-card, .preview-image {
    animation: fadeInUp 0.5s ease-out;
}

/* Loading states */
.uploading {
    opacity: 0.7;
    pointer-events: none;
}

.gallery-loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Selection states */
.select-image:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.gallery-card.selected {
    border: 2px solid var(--primary-color);
    box-shadow: 0 0 0 3px var(--primary-light);
}
</style>

<script>
// =============================================
// LEAD IMAGES GALLERY MANAGER - VERSION MEJORADA
// =============================================

class LeadImagesManager {
    constructor() {
        this.config = {
            MAX_FILES_PER_UPLOAD: 200,
            MAX_FILE_SIZE: 50 * 1024 * 1024, // 50MB
            MAX_TOTAL_SIZE: 100 * 1024 * 1024, // 100MB
            ALLOWED_FILE_TYPES: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            IMAGES_PER_PAGE: 12,
            TOAST_DELAY: 5000
        };

        this.state = {
            isUploading: false,
            currentPage: 1,
            lastPage: 1,
            totalImages: 0,
            imagesPerPage: 12,
            selectedImages: new Set(),
            currentPagination: null
        };

        this.leadId = {{ $lead->id }};
        this.elements = {};
        this.init();
    }

    // =============================================
    // INITIALIZATION
    // =============================================

    init() {
        console.log('🔄 Initializing LeadImagesManager...');
        this.initializeElements();
        this.initializeUploadFunctionality();
        this.initializeEventListeners();
        this.loadInitialImages();
        this.initializeToastSystem();
    }

    initializeElements() {
        // Galería
        this.elements.gallery = document.getElementById('gallery');
        this.elements.dynamicGallery = document.getElementById('dynamicGallery');
        this.elements.emptyState = document.getElementById('emptyState');
        
        // Controles
        this.elements.counter = document.getElementById('totalImagesCounter');
        this.elements.prevBtn = document.getElementById('prevPage');
        this.elements.nextBtn = document.getElementById('nextPage');
        this.elements.pageInfo = document.getElementById('pageInfo');
        this.elements.pageInfoDetailed = document.getElementById('pageInfoDetailed');
        
        // Upload
        this.elements.uploadForm = document.getElementById('uploadForm');
        this.elements.fileInput = document.getElementById('images');
        this.elements.uploadZone = document.querySelector('.upload-zone');
        
        // Bulk actions
        this.elements.selectAllCheckbox = document.getElementById('selectAll');
        this.elements.deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        this.elements.downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
        this.elements.deleteAllBtn = document.getElementById('deleteAllBtn');
        this.elements.selectedCount = document.getElementById('selectedCount');
        this.elements.selectedCountText = document.getElementById('selectedCountText');
        
        // Progress
        this.elements.uploadBtn = document.getElementById('uploadBtn');
        this.elements.uploadProgress = document.getElementById('uploadProgress');
        this.elements.progressFill = document.getElementById('progressFill');
        this.elements.progressPercent = document.getElementById('progressPercent');
        
        // Info
        this.elements.fileInfo = document.getElementById('fileInfo');
        this.elements.previewContainer = document.getElementById('previewContainer');
    }

    initializeUploadFunctionality() {
        if (!this.elements.uploadForm || !this.elements.fileInput) return;

        this.elements.fileInput.addEventListener('change', (e) => this.handleFileSelection(e));
        this.elements.uploadForm.addEventListener('submit', (e) => this.handleUpload(e));
        this.initializeDragAndDrop();
    }

    initializeEventListeners() {
        // Bulk actions
        if (this.elements.selectAllCheckbox) {
            this.elements.selectAllCheckbox.addEventListener('change', (e) => this.handleSelectAll(e));
        }
        if (this.elements.deleteSelectedBtn) {
            this.elements.deleteSelectedBtn.addEventListener('click', () => this.deleteSelectedImages());
        }
        if (this.elements.downloadSelectedBtn) {
            this.elements.downloadSelectedBtn.addEventListener('click', () => this.downloadSelectedImages());
        }
        if (this.elements.deleteAllBtn) {
            this.elements.deleteAllBtn.addEventListener('click', () => this.deleteAllImages());
        }
        
        // Pagination
        if (this.elements.prevBtn) {
            this.elements.prevBtn.addEventListener('click', () => this.loadImages(this.state.currentPage - 1));
        }
        if (this.elements.nextBtn) {
            this.elements.nextBtn.addEventListener('click', () => this.loadImages(this.state.currentPage + 1));
        }
        
        // Image selection
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('select-image')) {
                this.toggleImageSelection(e.target.value, e.target.checked);
            }
        });
    }

    initializeDragAndDrop() {
        const dropZone = this.elements.uploadZone;
        if (!dropZone) return;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.setDropZoneState(true), false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.setDropZoneState(false), false);
        });
        
        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.elements.fileInput.files = files;
                this.handleFileSelection();
            }
        }, false);
    }

    initializeToastSystem() {
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
    }

    // =============================================
    // UPLOAD MANAGEMENT
    // =============================================

    handleFileSelection() {
        const files = this.elements.fileInput.files;
        
        if (files.length === 0) {
            this.resetFileInfo();
            return;
        }

        console.log(`📁 ${files.length} files selected`);

        // Validar cantidad de archivos
        if (files.length > this.config.MAX_FILES_PER_UPLOAD) {
            this.showAlert('error', `Maximum ${this.config.MAX_FILES_PER_UPLOAD} files allowed per upload`);
            this.resetFileInput();
            return;
        }

        const validationResult = this.validateFiles(files);
        if (!validationResult.valid) {
            this.showAlert('error', validationResult.message);
            this.resetFileInput();
            return;
        }

        this.updateFileInfo(files);
        this.createPreviews(files);
    }

    async handleUpload(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (this.state.isUploading) {
            this.showAlert('warning', 'Upload already in progress. Please wait...');
            return;
        }
        
        const files = this.elements.fileInput.files;
        
        if (files.length === 0) {
            this.showAlert('warning', 'Please select at least one image');
            return;
        }

        console.log(`🚀 Starting upload of ${files.length} files`);

        this.state.isUploading = true;
        this.setUploadState(true);

        try {
            await this.uploadAllFiles(files);
            this.showAlert('success', `✅ Successfully uploaded ${files.length} images`);
            this.resetUploadForm();
            
            setTimeout(() => {
                this.loadImages(1);
            }, 1000);
            
        } catch (error) {
            console.error('❌ Upload error:', error);
            this.showAlert('error', error.message || 'Error uploading images');
        } finally {
            this.state.isUploading = false;
            this.setUploadState(false);
        }
    }

    async uploadAllFiles(files) {
        const formData = new FormData();
        formData.append("lead_id", this.leadId);
        
        Array.from(files).forEach((file, index) => {
            console.log(`📎 Adding file ${index + 1}:`, file.name, `(${this.formatFileSize(file.size)})`);
            formData.append("images[]", file);
        });

        try {
            const response = await fetch("{{ route('lead.images.store') }}", {
                method: "POST",
                headers: { 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData,
            });

            console.log('📨 Upload response status:', response.status);

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('❌ Server returned non-JSON response:', text.substring(0, 200));
                throw new Error('Server error: Please check the console for details');
            }

            const result = await response.json();
            console.log('📨 Server response:', result);

            if (!response.ok) {
                throw new Error(result.message || `Upload failed with status ${response.status}`);
            }

            if (!result.success) {
                throw new Error(result.message || 'Upload failed');
            }

            this.updateProgress(100);
            console.log('✅ Upload successful, uploaded:', result.uploaded_count);
            return result;

        } catch (error) {
            console.error('❌ Upload error:', error);
            throw error;
        }
    }

    validateFiles(files) {
        const oversizedFiles = [];
        const invalidTypeFiles = [];
        let totalSize = 0;
        
        Array.from(files).forEach(file => {
            totalSize += file.size;
            
            if (file.size > this.config.MAX_FILE_SIZE) {
                oversizedFiles.push({
                    name: file.name,
                    size: this.formatFileSize(file.size)
                });
            }
            
            if (!this.config.ALLOWED_FILE_TYPES.includes(file.type)) {
                invalidTypeFiles.push(file.name);
            }
        });

        if (totalSize > this.config.MAX_TOTAL_SIZE) {
            return {
                valid: false,
                message: `Total files size exceeds ${this.formatFileSize(this.config.MAX_TOTAL_SIZE)}. Please select fewer files.`
            };
        }
        
        if (oversizedFiles.length > 0) {
            const fileList = oversizedFiles.map(f => `${f.name} (${f.size})`).join(', ');
            return {
                valid: false,
                message: `The following files exceed ${this.formatFileSize(this.config.MAX_FILE_SIZE)}: ${fileList}`
            };
        }
        
        if (invalidTypeFiles.length > 0) {
            return {
                valid: false,
                message: `The following files are not supported: ${invalidTypeFiles.join(', ')}. Supported formats: JPG, PNG, WEBP, GIF`
            };
        }
        
        return { valid: true };
    }

    createPreviews(files) {
        if (!this.elements.previewContainer) return;
        
        this.elements.previewContainer.innerHTML = '';
        
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-4 col-lg-3';
                col.innerHTML = this.createPreviewCard(e.target.result, file, index);
                this.elements.previewContainer.appendChild(col);
                
                const removeBtn = col.querySelector('.remove-preview');
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        this.removeFileFromInput(index);
                        col.remove();
                        this.updateFileInfo();
                    });
                }
            };
            reader.onerror = () => {
                console.error('Error reading file:', file.name);
            };
            reader.readAsDataURL(file);
        });
    }

    createPreviewCard(imageSrc, file, index) {
        return `
            <div class="card preview-image border-0 shadow-sm">
                <span class="position-absolute top-0 start-0 m-2 badge bg-secondary bg-opacity-75">
                    #${index + 1}
                </span>
                <img src="${imageSrc}" 
                     class="card-img-top rounded" 
                     style="height:120px;object-fit:cover;" 
                     alt="Preview of ${file.name}"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD48L3N2Zz4='">
                <button type="button" class="remove-preview" data-index="${index}" aria-label="Remove ${file.name}">
                    <i class="bi bi-x"></i>
                </button>
                <div class="card-body text-center p-2">
                    <small class="text-muted text-truncate d-block" title="${file.name}">${this.truncateFileName(file.name)}</small>
                    <small class="text-muted">${this.formatFileSize(file.size)}</small>
                </div>
            </div>`;
    }

    removeFileFromInput(index) {
        const dt = new DataTransfer();
        const files = Array.from(this.elements.fileInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) dt.items.add(file);
        });
        
        this.elements.fileInput.files = dt.files;
        this.updateFileInfo();
        this.createPreviews(this.elements.fileInput.files);
    }

    // =============================================
    // GALLERY MANAGEMENT
    // =============================================

    async loadInitialImages() {
        await this.loadImages(1);
    }

    async loadImages(page = 1) {
        if (!this.elements.gallery) return;

        this.showGalleryLoading();

        try {
            const response = await fetch(`{{ route('lead.images.index', $lead->id) }}?page=${page}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                this.renderGallery(data.images.data, data.pagination);
                this.updatePagination(data.pagination);
                this.updateUIState(data.images.data.length > 0);
            } else {
                this.showGalleryError('Error loading images from server');
            }
        } catch (error) {
            console.error('Error fetching images:', error);
            this.showGalleryError('Error loading images. Please try again.');
        }
    }

    renderGallery(images, pagination = null) {
        if (!this.elements.gallery) return;

        if (!images || images.length === 0) {
            this.showEmptyGallery();
            return;
        }

        if (pagination) {
            this.state.currentPagination = pagination;
        }

        const galleryHTML = images.map((img, index) => this.createImageCard(img, index)).join('');
        this.elements.gallery.innerHTML = galleryHTML;
        this.updateImageCounter();
    }

    createImageCard(image, index) {
        const imageUrl = `/storage/${image.image_path}`;
        const uploadDate = new Date(image.created_at).toLocaleDateString();
        const fileSize = image.file_size ? this.formatFileSize(image.file_size) : 'Unknown size';
        
        let imageNumber;
        
        if (this.state.currentPagination && this.state.currentPagination.from !== undefined) {
            imageNumber = this.state.currentPagination.from + index;
        } else if (this.state.currentPage && this.state.imagesPerPage) {
            imageNumber = ((this.state.currentPage - 1) * this.state.imagesPerPage) + index + 1;
        } else {
            imageNumber = index + 1;
        }
        
        return `
            <div class="col-xl-3 col-lg-4 col-md-6" id="image-${image.id}">
                <div class="card h-100 shadow-sm border-0 gallery-card">
                    <div class="card-img-top position-relative overflow-hidden">
                        <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 image-counter">
                            #${imageNumber}
                        </span>
                        
                        <input type="checkbox" 
                            class="form-check-input position-absolute top-0 end-0 m-2 select-image" 
                            value="${image.id}"
                            aria-label="Select image ${imageNumber}">
                        
                        <img src="${imageUrl}" 
                            class="img-fluid w-100" 
                            style="height: 220px; object-fit: cover;" 
                            alt="Image ${imageNumber}"
                            loading="lazy"
                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD48L3N2Zz4='">
                        
                        <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <div class="btn-group">
                                <a href="${imageUrl}" 
                                download="lead-${this.leadId}-image-${imageNumber}.jpg" 
                                class="btn btn-light btn-sm"
                                title="Download image ${imageNumber}">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="${imageUrl}" 
                                target="_blank" 
                                class="btn btn-light btn-sm"
                                title="View full size">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-light btn-sm" 
                                        onclick="leadImagesManager.deleteSingleImage(${image.id})"
                                        title="Delete image ${imageNumber}">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            ${uploadDate}
                        </small>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-file-earmark me-1"></i>
                            ${fileSize}
                        </small>
                    </div>
                </div>
            </div>`;
    }

    updatePagination(pagination) {
        this.state.currentPage = pagination.current_page;
        this.state.lastPage = pagination.last_page;
        this.state.currentPagination = pagination;
        this.state.totalImages = pagination.total;
        this.state.imagesPerPage = pagination.per_page;
        
        if (this.elements.counter) {
            this.elements.counter.textContent = `${this.state.totalImages} image${this.state.totalImages !== 1 ? 's' : ''}`;
        }
        if (this.elements.pageInfo) {
            this.elements.pageInfo.textContent = `Page ${this.state.currentPage} of ${this.state.lastPage}`;
        }
        if (this.elements.pageInfoDetailed) {
            const from = pagination.from || ((this.state.currentPage - 1) * this.state.imagesPerPage + 1);
            const to = pagination.to || Math.min(this.state.currentPage * this.state.imagesPerPage, this.state.totalImages);
            this.elements.pageInfoDetailed.textContent = `Showing ${from} to ${to} of ${this.state.totalImages} images`;
        }
        
        if (this.elements.prevBtn) this.elements.prevBtn.disabled = this.state.currentPage <= 1;
        if (this.elements.nextBtn) this.elements.nextBtn.disabled = this.state.currentPage >= this.state.lastPage;
    }

    // =============================================
    // IMAGE ACTIONS
    // =============================================

    async deleteSingleImage(id) {
        if (!await this.showConfirmation('Are you sure you want to delete this image?')) return;

        try {
            const response = await fetch(`{{ route('lead.images.destroy', '') }}/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            });

            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', 'Image deleted successfully');
                this.removeImageFromDOM(id);
                this.state.selectedImages.delete(id.toString());
                this.updateSelectionState();
            } else {
                throw new Error(data.message || 'Error deleting image');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showAlert('error', error.message || 'Error deleting image');
        }
    }

    async deleteSelectedImages() {
        const selected = Array.from(this.state.selectedImages);
        if (selected.length === 0) {
            this.showAlert('warning', 'Please select at least one image');
            return;
        }
        
        if (!await this.showConfirmation(`Are you sure you want to delete ${selected.length} selected image(s)?`)) return;

        try {
            const response = await fetch('{{ route("lead.images.bulkDelete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: selected })
            });

            const data = await response.json();
            
            if (data.success) {
                this.state.selectedImages.clear();
                this.showAlert('success', `${data.deleted_count || selected.length} images deleted successfully`);
                setTimeout(() => {
                    this.loadImages(this.state.currentPage);
                }, 500);
            } else {
                throw new Error(data.message || 'Error deleting selected images');
            }
        } catch (error) {
            console.error('Bulk delete error:', error);
            this.showAlert('error', error.message || 'Error deleting selected images');
        }
    }

    async deleteAllImages() {
        if (!await this.showConfirmation('Are you sure you want to delete ALL images? This action cannot be undone.', true)) return;

        try {
            const response = await fetch('{{ route("lead.images.deleteAll", $lead->id) }}', {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            });

            const data = await response.json();
            
            if (data.success) {
                this.state.selectedImages.clear();
                this.showAlert('success', 'All images deleted successfully');
                setTimeout(() => {
                    this.loadImages(1);
                }, 500);
            } else {
                throw new Error(data.message || 'Error deleting all images');
            }
        } catch (error) {
            console.error('Delete all error:', error);
            this.showAlert('error', error.message || 'Error deleting all images');
        }
    }

    downloadSelectedImages() {
        const selected = Array.from(this.state.selectedImages);
        if (selected.length === 0) {
            this.showAlert('warning', 'Please select at least one image');
            return;
        }
        
        if (selected.length > 1) {
            this.showAlert('info', `Starting download of ${selected.length} images...`);
        }
        
        selected.forEach(id => {
            const imageElement = document.querySelector(`#image-${id} img`);
            if (imageElement) {
                const link = document.createElement('a');
                link.href = imageElement.src;
                link.download = `lead-${this.leadId}-image-${id}.jpg`;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
        
        if (selected.length === 1) {
            this.showAlert('info', 'Image download started');
        }
    }

    // =============================================
    // ALERT & NOTIFICATION SYSTEM - MEJORADO
    // =============================================

    showAlert(type, message, options = {}) {
        const {
            title = this.getAlertTitle(type),
            duration = this.config.TOAST_DELAY,
            position = 'top-end',
            showConfirmButton = false,
            timer = duration
        } = options;

        if (typeof Swal !== 'undefined') {
            // Usar SweetAlert2 si está disponible
            const Toast = Swal.mixin({
                toast: true,
                position: position,
                showConfirmButton: showConfirmButton,
                timer: timer,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type,
                title: message,
                background: this.getAlertColor(type),
                color: '#fff'
            });
        } else {
            // Fallback a toasts de Bootstrap
            this.showToast(message, type);
        }
    }

    async showConfirmation(message, isDangerous = false) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: isDangerous ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: isDangerous ? '#d33' : '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
            return result.isConfirmed;
        } else {
            return confirm(message);
        }
    }

    showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toast-container');
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        
        toast.id = toastId;
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="bi ${this.getToastIcon(type)} me-2 fs-5"></i>
                    <span class="fs-6">${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>`;
        
        toastContainer.appendChild(toast);
        
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            const bsToast = new bootstrap.Toast(toast, { delay: this.config.TOAST_DELAY });
            bsToast.show();
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        } else {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, this.config.TOAST_DELAY);
        }
    }

    getAlertTitle(type) {
        const titles = {
            success: 'Success!',
            error: 'Error!',
            warning: 'Warning!',
            info: 'Information',
            question: 'Confirm'
        };
        return titles[type] || 'Notification';
    }

    getAlertColor(type) {
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8',
            question: '#6c757d'
        };
        return colors[type] || '#6c757d';
    }

    getToastIcon(type) {
        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-triangle-fill',
            warning: 'bi-exclamation-circle-fill',
            info: 'bi-info-circle-fill'
        };
        return icons[type] || 'bi-info-circle-fill';
    }

    // =============================================
    // UTILITY FUNCTIONS
    // =============================================

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    setDropZoneState(isActive) {
        if (!this.elements.uploadZone) return;
        
        if (isActive) {
            this.elements.uploadZone.classList.add('dragover');
            this.elements.uploadZone.style.borderColor = '#0d6efd';
            this.elements.uploadZone.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
        } else {
            this.elements.uploadZone.classList.remove('dragover');
            this.elements.uploadZone.style.borderColor = '';
            this.elements.uploadZone.style.backgroundColor = '';
        }
    }

    updateProgress(percent) {
        if (this.elements.progressFill) this.elements.progressFill.style.width = percent + '%';
        if (this.elements.progressPercent) this.elements.progressPercent.textContent = Math.round(percent) + '%';
    }

    setUploadState(isUploading) {
        if (this.elements.uploadBtn) {
            this.elements.uploadBtn.disabled = isUploading;
            this.elements.uploadBtn.innerHTML = isUploading 
                ? '<i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-2"></i> Uploading...' 
                : '<i class="bi bi-cloud-arrow-up me-2"></i> Upload All';
        }
        
        if (this.elements.uploadProgress) {
            this.elements.uploadProgress.classList.toggle('d-none', !isUploading);
        }
        
        if (!isUploading) {
            this.updateProgress(0);
        }
    }

    resetUploadForm() {
        console.log('🔄 Resetting upload form...');
        
        if (this.elements.fileInput) this.elements.fileInput.value = '';
        if (this.elements.previewContainer) this.elements.previewContainer.innerHTML = '';
        if (this.elements.fileInfo) this.elements.fileInfo.textContent = '';
        if (this.elements.uploadBtn) this.elements.uploadBtn.disabled = true;
    }

    resetFileInput() {
        if (this.elements.fileInput) this.elements.fileInput.value = '';
        this.resetFileInfo();
    }

    resetFileInfo() {
        if (this.elements.fileInfo) this.elements.fileInfo.textContent = '';
        if (this.elements.uploadBtn) this.elements.uploadBtn.disabled = true;
        if (this.elements.previewContainer) this.elements.previewContainer.innerHTML = '';
    }

    updateFileInfo() {
        const files = this.elements.fileInput?.files;
        
        if (!files || files.length === 0) {
            this.resetFileInfo();
        } else {
            const totalSize = Array.from(files).reduce((acc, file) => acc + file.size, 0);
            if (this.elements.fileInfo) {
                this.elements.fileInfo.innerHTML = `
                    <i class="bi bi-folder2 me-1"></i>
                    <strong>${files.length}</strong> file${files.length !== 1 ? 's' : ''} selected • 
                    <strong>${this.formatFileSize(totalSize)}</strong>
                `;
            }
            if (this.elements.uploadBtn) this.elements.uploadBtn.disabled = false;
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    truncateFileName(filename, maxLength = 20) {
        if (filename.length <= maxLength) return filename;
        return filename.substring(0, maxLength - 3) + '...';
    }

    // =============================================
    // SELECTION MANAGEMENT
    // =============================================

    toggleImageSelection(imageId, isSelected) {
        if (isSelected) {
            this.state.selectedImages.add(imageId);
        } else {
            this.state.selectedImages.delete(imageId);
        }
        this.updateSelectionState();
    }

    updateSelectionState() {
        const selected = this.state.selectedImages.size;
        
        if (this.elements.deleteSelectedBtn) this.elements.deleteSelectedBtn.disabled = selected === 0;
        if (this.elements.downloadSelectedBtn) this.elements.downloadSelectedBtn.disabled = selected === 0;
        if (this.elements.selectedCount) this.elements.selectedCount.style.display = selected > 0 ? 'inline-block' : 'none';
        if (this.elements.selectedCountText) this.elements.selectedCountText.textContent = selected;
        
        const totalCheckboxes = document.querySelectorAll('.select-image').length;
        if (this.elements.selectAllCheckbox) {
            this.elements.selectAllCheckbox.checked = selected > 0 && selected === totalCheckboxes;
            this.elements.selectAllCheckbox.indeterminate = selected > 0 && selected < totalCheckboxes;
        }
    }

    handleSelectAll(e) {
        const checkboxes = document.querySelectorAll('.select-image');
        const isChecked = e.target.checked;
        
        checkboxes.forEach(cb => {
            cb.checked = isChecked;
            this.toggleImageSelection(cb.value, isChecked);
        });
    }

    updateImageCounter() {
        const total = document.querySelectorAll('#galleryContainer .col-xl-3').length;
        if (this.elements.counter) this.elements.counter.textContent = total + ' image' + (total !== 1 ? 's' : '');
        if (this.elements.deleteAllBtn) this.elements.deleteAllBtn.disabled = total === 0;
    }

    // =============================================
    // UI STATE MANAGEMENT
    // =============================================

    updateUIState(hasImages) {
        if (this.elements.dynamicGallery) this.elements.dynamicGallery.style.display = hasImages ? 'block' : 'none';
        if (this.elements.emptyState) this.elements.emptyState.style.display = hasImages ? 'none' : 'block';
        
        const bulkActionsCard = document.getElementById('bulkActionsCard');
        if (bulkActionsCard) {
            bulkActionsCard.style.display = hasImages ? 'block' : 'none';
        }
    }

    showGalleryLoading() {
        if (this.elements.gallery) {
            this.elements.gallery.innerHTML = `
                <div class='col-12 text-center text-muted py-5'>
                    <div class='spinner-border text-primary mb-3' style='width: 3rem; height: 3rem;'></div>
                    <p class='mt-2 fs-5'>Loading images...</p>
                </div>`;
        }
    }

    showGalleryError(message) {
        if (this.elements.gallery) {
            this.elements.gallery.innerHTML = `
                <div class='col-12 text-center text-danger py-5'>
                    <i class='bi bi-exclamation-triangle display-4 d-block mb-3'></i>
                    <p class='fs-5'>${message}</p>
                    <button class='btn btn-primary mt-2 rounded-pill px-4' onclick='leadImagesManager.loadImages(${this.state.currentPage})'>
                        <i class='bi bi-arrow-clockwise me-2'></i>Try Again
                    </button>
                </div>`;
        }
    }

    showEmptyGallery() {
        if (this.elements.gallery) {
            this.elements.gallery.innerHTML = `
                <div class='col-12 text-center text-muted py-5'>
                    <i class='bi bi-images display-1 d-block mb-3 opacity-25'></i>
                    <h4 class='text-dark mb-3'>No images uploaded yet</h4>
                    <p class='text-muted mb-4'>Start by uploading some images to the gallery.</p>
                    <button class='btn btn-primary rounded-pill px-4' onclick="document.getElementById('images').click()">
                        <i class='bi bi-cloud-arrow-up me-2'></i>Upload Photos
                    </button>
                </div>`;
        }
    }

    removeImageFromDOM(id) {
        const imageElement = document.getElementById(`image-${id}`);
        if (imageElement) {
            imageElement.style.opacity = '0';
            imageElement.style.transform = 'scale(0.8)';
            setTimeout(() => {
                imageElement.remove();
                this.updateUIState(document.querySelectorAll('.gallery-card').length > 0);
                this.updateImageCounter();
            }, 300);
        }
    }
}

// =============================================
// INITIALIZATION
// =============================================

let leadImagesManager;

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM Content Loaded - Initializing LeadImagesManager');
    leadImagesManager = new LeadImagesManager();
});

window.leadImagesManager = leadImagesManager;
</script>











        <!-- Documents Section - Minimalist Design -->
        <div class="tab-pane fade" id="documents">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="text-dark mb-1">Documents</h4>
                    <p class="text-muted small mb-0">Manage your files and folders</p>
                </div>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                    <i class="bi bi-folder-plus me-1"></i> New Folder
                </button>
            </div>

            <!-- Create Folder Modal -->
            <div class="modal fade" id="createFolderModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title">Create New Folder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('leads.folders.store', $lead->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="text" name="folder_name" class="form-control form-control-lg" placeholder="Folder name" required>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-folder-plus me-1"></i> Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- File Preview Modal -->
            <div class="modal fade" id="filePreviewModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center p-0">
                            <div id="filePreviewContent"></div>
                            <div id="filePreviewUnsupported" class="d-none p-5">
                                <i class="bi bi-file-earmark-x text-muted display-4 mb-3"></i>
                                <p class="text-muted">Preview not available for this file type</p>
                            </div>
                            <div id="filePreviewLoading" class="d-none p-5">
                                <div class="spinner-border text-primary mb-3"></div>
                                <p class="text-muted">Loading preview...</p>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <a id="filePreviewDownload" href="#" class="btn btn-primary" download>
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Folders List -->
            <div class="folders-list">
                @foreach($lead->folders as $index => $folder)
                    @php
                        $files = $folder->files;
                        $iconsByExtension = [
                            'pdf' => 'bi-file-earmark-pdf text-danger',
                            'xls' => 'bi-file-earmark-spreadsheet text-success',
                            'xlsx' => 'bi-file-earmark-spreadsheet text-success',
                            'doc' => 'bi-file-earmark-word text-primary',
                            'docx' => 'bi-file-earmark-word text-primary',
                            'txt' => 'bi-file-earmark-text text-info',
                            'jpg' => 'bi-file-image text-warning',
                            'jpeg' => 'bi-file-image text-warning',
                            'png' => 'bi-file-image text-warning',
                            'gif' => 'bi-file-image text-warning',
                            'default' => 'bi-file-earmark text-muted',
                        ];
                        $previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt'];
                    @endphp

                    <div class="folder-item">
                        <!-- Folder Header -->
                        <div class="folder-header">
                            <div class="folder-info" data-bs-toggle="collapse" data-bs-target="#folderContent{{ $index }}">
                                <i class="bi bi-folder-fill text-warning me-3"></i>
                                <div>
                                    <h6 class="mb-0">{{ $folder->name }}</h6>
                                    <small class="text-muted">{{ $files->count() }} files</small>
                                </div>
                            </div>
                            <div class="folder-actions">
                                <form action="{{ route('leads.folders.destroy', $folder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" 
                                            onclick="return confirm('Are you sure you want to delete this folder and all its files?');"
                                            title="Delete folder">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="collapse" data-bs-target="#folderContent{{ $index }}">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Folder Content -->
                        <div class="collapse" id="folderContent{{ $index }}">
                            <div class="folder-content">
                                @if($files->isEmpty())
                                    <div class="empty-folder text-center py-4">
                                        <i class="bi bi-file-earmark-plus text-muted display-6 mb-3"></i>
                                        <p class="text-muted mb-3">No files in this folder</p>
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $index }}">
                                            <i class="bi bi-cloud-upload me-1"></i> Upload Files
                                        </button>
                                    </div>
                                @else
                                    <!-- Files List -->
                                    <div class="files-list">
                                        @foreach($files as $file)
                                            @php
                                                $path = $file->file_path;
                                                $original_name = basename($path);
                                                $extension = pathinfo($path, PATHINFO_EXTENSION);
                                                $iconClass = $iconsByExtension[$extension] ?? $iconsByExtension['default'];
                                                $isPreviewable = in_array(strtolower($extension), $previewableExtensions);
                                            @endphp
                                            <div class="file-item">
                                                <div class="file-info">
                                                    <i class="bi {{ $iconClass }} me-3"></i>
                                                    <div class="file-details">
                                                        <span class="file-name">{{ $original_name }}</span>
                                                        <small class="text-muted">{{ strtoupper($extension) }} • {{ $file->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                </div>
                                                <div class="file-actions">
                                                    @if($isPreviewable)
                                                    <button class="btn btn-sm btn-outline-secondary preview-file" 
                                                            data-file-url="{{ asset('storage/' . $path) }}"
                                                            data-file-name="{{ $original_name }}"
                                                            data-file-extension="{{ $extension }}"
                                                            title="Preview">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    @endif
                                                    <a href="{{ asset('storage/' . $path) }}" download class="btn btn-sm btn-outline-secondary" title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <form action="{{ route('leads.files.destroy', $file->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this file?');">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Upload Section -->
                                <div class="upload-section mt-3 pt-3 border-top">
                                    <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $index }}">
                                        <i class="bi bi-cloud-upload me-1"></i> Add Files to {{ $folder->name }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Modal - MODIFICADO PARA MÚLTIPLES ARCHIVOS -->
                    <div class="modal fade" id="uploadModal{{ $index }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Upload to {{ $folder->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('leads.files.store', $lead->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm{{ $index }}">
                                        @csrf
                                        <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="files{{ $index }}" class="form-label">Select Files</label>
                                                <input type="file" name="files[]" class="form-control" id="files{{ $index }}" multiple required>
                                                <div class="form-text">Hold Ctrl/Cmd to select multiple files</div>
                                            </div>
                                            
                                            <!-- Selected Files Preview -->
                                            <div class="selected-files mt-3" id="selectedFiles{{ $index }}" style="display: none;">
                                                <h6 class="mb-2">Selected Files:</h6>
                                                <div class="selected-files-list" id="selectedFilesList{{ $index }}"></div>
                                                <small class="text-muted" id="fileCount{{ $index }}">0 files selected</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary" id="uploadButton{{ $index }}">
                                                <i class="bi bi-cloud-upload me-1"></i> Upload Files
                                            </button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if($lead->folders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-folder-x text-muted display-1 mb-3"></i>
                <h5 class="text-muted mb-2">No folders yet</h5>
                <p class="text-muted mb-3">Create your first folder to organize documents</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                    <i class="bi bi-folder-plus me-1"></i> Create Folder
                </button>
            </div>
            @endif
        </div>

        <style>
            /* Minimalist Variables */
            :root {
                --border-color: #e9ecef;
                --hover-color: #f8f9fa;
                --radius: 8px;
                --transition: all 0.2s ease;
            }

            /* Selected Files Styles */
            .selected-files-list {
                max-height: 150px;
                overflow-y: auto;
                border: 1px solid var(--border-color);
                border-radius: var(--radius);
                padding: 0.75rem;
                background: white;
                margin-bottom: 0.5rem;
            }

            .selected-file-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.5rem;
                border-bottom: 1px solid var(--border-color);
                transition: var(--transition);
            }

            .selected-file-item:last-child {
                border-bottom: none;
            }

            .selected-file-item:hover {
                background: var(--hover-color);
            }

            .file-info-small {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                flex: 1;
            }

            .file-icon-small {
                font-size: 0.875rem;
                width: 16px;
            }

            .file-name-small {
                font-size: 0.875rem;
                color: #374151;
                flex: 1;
            }

            .file-size {
                font-size: 0.75rem;
                color: #6b7280;
                margin-left: 0.5rem;
            }

            /* Folders List */
            .folders-list {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            /* Folder Item */
            .folder-item {
                border: 1px solid var(--border-color);
                border-radius: var(--radius);
                background: white;
                overflow: hidden;
            }

            .folder-header {
                padding: 1rem 1.25rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                transition: var(--transition);
                background: none;
            }

            .folder-header:hover {
                background: var(--hover-color);
            }

            .folder-info {
                display: flex;
                align-items: center;
                flex: 1;
                cursor: pointer;
            }

            .folder-info i {
                font-size: 1.25rem;
            }

            .folder-info h6 {
                font-weight: 500;
            }

            .folder-actions {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .folder-actions .btn {
                padding: 0.375rem;
                border-radius: 4px;
                transition: var(--transition);
            }

            .folder-actions .bi-chevron-down {
                transition: var(--transition);
            }

            .folder-header[aria-expanded="true"] .bi-chevron-down {
                transform: rotate(180deg);
            }

            /* Folder Content */
            .folder-content {
                padding: 1.25rem;
                border-top: 1px solid var(--border-color);
            }

            /* Files List */
            .files-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .file-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem;
                border-radius: var(--radius);
                transition: var(--transition);
            }

            .file-item:hover {
                background: var(--hover-color);
            }

            .file-info {
                display: flex;
                align-items: center;
                flex: 1;
            }

            .file-info i {
                font-size: 1.125rem;
                width: 24px;
            }

            .file-details {
                display: flex;
                flex-direction: column;
            }

            .file-name {
                font-weight: 400;
                font-size: 0.9rem;
                color: #212529;
            }

            .file-actions {
                display: flex;
                gap: 0.25rem;
                opacity: 0;
                transition: var(--transition);
            }

            .file-item:hover .file-actions {
                opacity: 1;
            }

            .file-actions .btn {
                padding: 0.375rem;
                border-radius: 4px;
            }

            /* Empty Folder */
            .empty-folder {
                padding: 2rem 1rem;
            }

            /* Upload Section */
            .upload-section {
                border-top: 1px solid var(--border-color);
            }

            /* Modal Styles */
            .modal-content {
                border: none;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .modal-header {
                padding: 1.5rem 1.5rem 0.5rem;
            }

            .modal-body {
                padding: 0.5rem 1.5rem;
            }

            .modal-footer {
                padding: 0.5rem 1.5rem 1.5rem;
            }

            /* Form Controls */
            .form-control {
                border: 1px solid var(--border-color);
                border-radius: 6px;
                padding: 0.75rem;
            }

            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
            }

            .form-control-lg {
                padding: 1rem;
            }

            /* Buttons */
            .btn {
                border-radius: 6px;
                font-weight: 400;
                transition: var(--transition);
            }

            .btn-outline-primary {
                border-color: var(--border-color);
                color: #6c757d;
            }

            .btn-outline-primary:hover {
                border-color: #0d6efd;
                background-color: #0d6efd;
                color: white;
            }

            .btn-outline-secondary {
                border-color: var(--border-color);
                color: #6c757d;
            }

            .btn-outline-secondary:hover {
                border-color: #6c757d;
                background-color: #6c757d;
                color: white;
            }

            .btn-outline-danger {
                border-color: var(--border-color);
                color: #6c757d;
            }

            .btn-outline-danger:hover {
                border-color: #dc3545;
                background-color: #dc3545;
                color: white;
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            /* File Preview */
            #filePreviewContent embed,
            #filePreviewContent iframe {
                width: 100%;
                height: 70vh;
                border: none;
            }

            #filePreviewContent img {
                max-width: 100%;
                max-height: 70vh;
                object-fit: contain;
            }

            #filePreviewContent pre {
                width: 100%;
                height: 70vh;
                overflow: auto;
                background: #f8f9fa;
                padding: 1rem;
                margin: 0;
                border: none;
                font-family: 'Courier New', monospace;
                font-size: 0.875rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .folder-header {
                    padding: 0.875rem 1rem;
                }
                
                .folder-content {
                    padding: 1rem;
                }
                
                .file-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.75rem;
                }
                
                .file-actions {
                    opacity: 1;
                    align-self: flex-end;
                }
                
                .modal-dialog {
                    margin: 0.5rem;
                }
                
                .folder-actions {
                    gap: 0.25rem;
                }
                
                .selected-files-list {
                    max-height: 120px;
                }
            }

            /* Animation */
            .collapsing {
                transition: height 0.2s ease;
            }

            /* Text Colors */
            .text-muted {
                color: #6c757d !important;
            }

            .text-dark {
                color: #212529 !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // File preview functionality
                const previewButtons = document.querySelectorAll('.preview-file');
                const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
                
                previewButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const fileUrl = this.getAttribute('data-file-url');
                        const fileName = this.getAttribute('data-file-name');
                        const fileExtension = this.getAttribute('data-file-extension').toLowerCase();
                        
                        // Set modal title and download link
                        document.getElementById('filePreviewModalLabel').textContent = `Preview: ${fileName}`;
                        document.getElementById('filePreviewDownload').href = fileUrl;
                        
                        // Show loading state
                        document.getElementById('filePreviewContent').classList.add('d-none');
                        document.getElementById('filePreviewUnsupported').classList.add('d-none');
                        document.getElementById('filePreviewLoading').classList.remove('d-none');
                        
                        // Show modal
                        previewModal.show();
                        
                        // Load preview based on file type
                        setTimeout(() => {
                            document.getElementById('filePreviewLoading').classList.add('d-none');
                            const previewContent = document.getElementById('filePreviewContent');
                            
                            switch(fileExtension) {
                                case 'pdf':
                                    previewContent.innerHTML = `<embed src="${fileUrl}#toolbar=1&navpanes=0" type="application/pdf">`;
                                    previewContent.classList.remove('d-none');
                                    break;
                                case 'jpg':
                                case 'jpeg':
                                case 'png':
                                case 'gif':
                                    previewContent.innerHTML = `<img src="${fileUrl}" alt="${fileName}" class="img-fluid">`;
                                    previewContent.classList.remove('d-none');
                                    break;
                                case 'txt':
                                    fetch(fileUrl)
                                        .then(response => response.text())
                                        .then(text => {
                                            previewContent.innerHTML = `<pre>${escapeHtml(text)}</pre>`;
                                            previewContent.classList.remove('d-none');
                                        })
                                        .catch(error => {
                                            console.error('Error loading text file:', error);
                                            document.getElementById('filePreviewUnsupported').classList.remove('d-none');
                                        });
                                    break;
                                default:
                                    document.getElementById('filePreviewUnsupported').classList.remove('d-none');
                                    break;
                            }
                        }, 500);
                    });
                });
                
                // Clean up when modal is hidden
                document.getElementById('filePreviewModal').addEventListener('hidden.bs.modal', function() {
                    document.getElementById('filePreviewContent').innerHTML = '';
                    document.getElementById('filePreviewContent').classList.add('d-none');
                    document.getElementById('filePreviewUnsupported').classList.add('d-none');
                });
                
                // Helper function to escape HTML
                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
                
                // Auto-expand folder when uploading to empty folder
                document.querySelectorAll('[data-bs-target^="#uploadModal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const target = this.getAttribute('data-bs-target');
                        const folderIndex = target.replace('#uploadModal', '');
                        const collapseElement = document.getElementById('folderContent' + folderIndex);
                        if (collapseElement) {
                            const bsCollapse = new bootstrap.Collapse(collapseElement, {
                                toggle: false
                            });
                            bsCollapse.show();
                        }
                    });
                });
                
                // Rotate chevron icon when folder is expanded/collapsed
                document.querySelectorAll('.folder-header .btn[data-bs-toggle="collapse"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const icon = this.querySelector('.bi-chevron-down');
                        if (icon) {
                            icon.style.transform = this.getAttribute('aria-expanded') === 'true' ? 'rotate(180deg)' : 'rotate(0deg)';
                        }
                    });
                });

                // Initialize file upload for each folder
                @foreach($lead->folders as $index => $folder)
                    initializeFileUpload({{ $index }});
                @endforeach
            });

            // File upload functionality for multiple files
            function initializeFileUpload(index) {
                const fileInput = document.getElementById(`files${index}`);
                const selectedFiles = document.getElementById(`selectedFiles${index}`);
                const selectedFilesList = document.getElementById(`selectedFilesList${index}`);
                const fileCount = document.getElementById(`fileCount${index}`);

                fileInput.addEventListener('change', function(e) {
                    const files = e.target.files;
                    updateSelectedFilesList(files, index);
                });

                function updateSelectedFilesList(files, index) {
                    selectedFilesList.innerHTML = '';
                    
                    if (files.length > 0) {
                        selectedFiles.style.display = 'block';
                        fileCount.textContent = `${files.length} file(s) selected`;
                        
                        Array.from(files).forEach((file, fileIndex) => {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'selected-file-item';
                            
                            const fileExtension = file.name.split('.').pop().toLowerCase();
                            const iconClass = getFileIconClass(fileExtension);
                            
                            fileItem.innerHTML = `
                                <div class="file-info-small">
                                    <i class="bi ${iconClass} file-icon-small"></i>
                                    <span class="file-name-small" title="${file.name}">${file.name}</span>
                                    <span class="file-size">(${formatFileSize(file.size)})</span>
                                </div>
                            `;
                            selectedFilesList.appendChild(fileItem);
                        });
                    } else {
                        selectedFiles.style.display = 'none';
                        fileCount.textContent = '0 files selected';
                    }
                }

                function getFileIconClass(extension) {
                    const icons = {
                        'pdf': 'bi-file-earmark-pdf text-danger',
                        'xls': 'bi-file-earmark-spreadsheet text-success',
                        'xlsx': 'bi-file-earmark-spreadsheet text-success',
                        'doc': 'bi-file-earmark-word text-primary',
                        'docx': 'bi-file-earmark-word text-primary',
                        'txt': 'bi-file-earmark-text text-info',
                        'jpg': 'bi-file-image text-warning',
                        'jpeg': 'bi-file-image text-warning',
                        'png': 'bi-file-image text-warning',
                        'gif': 'bi-file-image text-warning'
                    };
                    return icons[extension] || 'bi-file-earmark text-muted';
                }

                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }
            }
        </script>


        

    </div>

</div>

<!-- Modal para vista previa de imágenes -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewLabel">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="previewImage" class="img-fluid rounded shadow" alt="Vista previa de la imagen">
            </div>
        </div>
    </div>
</div>





<style>
    #chat-box::-webkit-scrollbar {
        width: 6px;
    }
    #chat-box::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.2);
        border-radius: 3px;
    }
</style>

{{-- Alerta approved --}}
<script>
    const approvedStatus = 2;
    const completedStatus = 3;
    const currentStatus = {{ $lead->estado }};
    const approvedDataSubmitted = {{ $lead->approved_data_submitted ? 'true' : 'false' }};

    function handleNextClick(nextStatus) {
        // Evita avanzar a Completed si no se ha enviado la data de aprobación
        if (currentStatus === approvedStatus && !approvedDataSubmitted && nextStatus === completedStatus) {
            Swal.fire({
                icon: 'warning',
                title: 'Action Required',
                text: 'Please complete and submit the additional information form before proceeding to Completed status.',
                confirmButtonText: 'Got it',
            });
            return;
        }

        // Bloquea retroceder desde Completed
        if (currentStatus >= completedStatus && nextStatus < currentStatus) {
            Swal.fire({
                icon: 'error',
                title: 'Action Denied',
                text: 'You cannot return to a previous status after reaching Completed.',
                confirmButtonText: 'Understood',
            });
            return;
        }

        // Confirmación antes de avanzar
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to change the status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('selectedStatus').value = nextStatus;
                document.getElementById('statusForm').submit();
            }
        });
    }
</script>




<!--Actulizar pestana sin refrescar pagina-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Restaurar la última pestaña activa
        const lastTab = localStorage.getItem('activeLeadTab');
        if (lastTab) {
            const trigger = document.querySelector(`a[data-bs-toggle="tab"][href="${lastTab}"]`);
            if (trigger) {
                const tab = new bootstrap.Tab(trigger);
                tab.show();
            }
        }
    
        // Guardar pestaña activa al cambiar
        const tabLinks = document.querySelectorAll('#leadTabs a[data-bs-toggle="tab"]');
        tabLinks.forEach(link => {
            link.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('activeLeadTab', e.target.getAttribute('href'));
            });
        });
    });
</script>

{{-- Grafica del balance --}}
<script>
    let chart;
    
    const renderChart = (paid, remaining) => {
        const ctx = document.getElementById('balanceChart').getContext('2d');
        if (chart) chart.destroy();
    
        chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Remaining'],
                datasets: [{
                    data: [paid, remaining],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    };
    
    const updateBalance = () => {
        const aporteInputs = document.querySelectorAll('.aporte-value');
        let total = 0;
        aporteInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
    
        const contractValue = parseFloat(document.getElementById('contractValue').value) || 0;
        const balance = contractValue - total;
        const percentage = contractValue > 0 ? (total / contractValue) * 100 : 0;
    
        // Actualizar textos
        document.getElementById('balanceDisplay').textContent = `$${balance.toFixed(2)}`;
        document.getElementById('totalAmountText').textContent = `$${contractValue.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('balanceDueText').textContent = `$${balance.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('chartPercentageText').textContent = `${percentage.toFixed(0)}%`;
    
        // Cambiar color del porcentaje según el progreso
        const percentEl = document.getElementById('chartPercentageText');
        if (percentage >= 100) {
            percentEl.classList.add('text-success');
            percentEl.classList.remove('text-danger');
        } else {
            percentEl.classList.add('text-danger');
            percentEl.classList.remove('text-success');
        }
    
        renderChart(total, Math.max(0, balance));
    };
    
    document.addEventListener('DOMContentLoaded', function () {
        updateBalance();
    
        document.getElementById('contractValue').addEventListener('input', updateBalance);
        document.querySelectorAll('.aporte-value').forEach(input => {
            input.addEventListener('input', updateBalance);
        });
    });
</script>

{{-- Script Contribution --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        const updateBalance = () => {
            const contractValue = parseFloat(document.getElementById('contractValue')?.value) || 0;
            let total = 0;
    
            document.querySelectorAll('.aporte-value').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
    
            document.getElementById('balanceDisplay').textContent = `$${total.toFixed(2)}`;
        };
    
        const toggleCheckNumber = (select) => {
            const tr = select.closest('tr');
            const checkInput = tr.querySelector('.check-number-input');
            if (!checkInput) return;
    
            if (select.value === 'Check') {
                checkInput.removeAttribute('disabled');
            } else {
                checkInput.value = '';
                checkInput.setAttribute('disabled', true);
            }
        };
    
        const bindEventsToRow = (row) => {
            row.querySelector('.aporte-value')?.addEventListener('input', updateBalance);
            const methodSelect = row.querySelector('.method-select');
            if (methodSelect) {
                toggleCheckNumber(methodSelect);
                methodSelect.addEventListener('change', () => toggleCheckNumber(methodSelect));
            }
        };
    
        const addContributionRow = () => {
            const tableBody = document.querySelector('#aportTable');
            const rowIndex = tableBody.querySelectorAll('tr').length;
    
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="date" name="finanzas[${rowIndex}][date]" class="form-control" required /></td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="finanzas[${rowIndex}][amount]" class="form-control aporte-value" required />
                    </div>
                </td>
                <td>
                    <select name="finanzas[${rowIndex}][method]" class="form-select method-select">
                        <option value="">Select</option>
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </td>
                <td><input type="text" name="finanzas[${rowIndex}][check_number]" class="form-control check-number-input" disabled /></td>
                <td><input type="text" name="finanzas[${rowIndex}][notes]" class="form-control" /></td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
    
            tableBody.appendChild(newRow);
            bindEventsToRow(newRow);
            updateBalance();
        };
    
        // Inicialización
        updateBalance();
        document.getElementById('contractValue')?.addEventListener('input', updateBalance);
    
        document.querySelectorAll('#aportTable tr').forEach(row => bindEventsToRow(row));
    
        document.getElementById('addRow')?.addEventListener('click', addContributionRow);
    
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr')?.remove();
                updateBalance();
            }
        });
    
    });

 
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('aportTable');

        // Delegación de evento para remover filas
        tableBody.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('tr');
                row.remove();
                updateBalance(); // Si estás usando el balance, actualízalo
            }
        });
    });


</script>
    

{{-- Expenses --}}
<script>
        document.addEventListener('DOMContentLoaded', function () {

            // Toggle amount field visibility
            function toggleAmountField() {
                const select = document.querySelector('select[name="expenses[0][type]"]');
                const input = document.querySelector('input[name="expenses[0][amount]"]');
                const percentLabel = document.querySelector('.commission-label');
                if (!select || !input) return;

                if (select.value === '') {
                    input.classList.add('d-none');
                    input.value = '';
                    percentLabel?.classList.add('d-none');
                } else {
                    input.classList.remove('d-none');
                    if (select.value === 'commission') {
                        percentLabel?.classList.remove('d-none');
                        input.placeholder = "%";
                    } else {
                        percentLabel?.classList.add('d-none');
                        input.placeholder = "$";
                    }
                }
            }

            // Bind toggle to each selector
            document.querySelectorAll('.expense-type').forEach(function (select) {
                select.addEventListener('change', function () {
                    toggleAmountField(this);
                });
                toggleAmountField(select); // initialize
            });

            // SweetAlert delete confirmation
            document.querySelectorAll('.delete-expense-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Delete expense?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: new URLSearchParams(new FormData(form))
                            })
                            .then(response => {
                                if (response.ok) {
                                    form.closest('tr').remove();
                                    Swal.fire('Deleted!', 'Expense has been removed.', 'success');
                                } else {
                                    Swal.fire('Error!', 'Could not delete expense.', 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error!', 'Network error.', 'error');
                            });
                        }
                    });
                });
            });

        });
</script>

{{-- Quote --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-quote-form-${id}`).submit();
            }
        });
    }
</script>







{{-- Total expenses / paid / nex profit --}}
<script>
   
        const updateExpenseSummary = () => {
        const contractValue = parseFloat(document.getElementById('contractValue')?.value) || 0;
        const baseExpenses = parseFloat('{{ $lead->total_expenses }}') || 0;
        const basePaid = parseFloat('{{ $lead->total_paid }}') || 0;

        let dynamicExpenses = 0;

        document.querySelectorAll('form[action*="lead-expenses"] tbody tr').forEach(row => {
            const type = row.querySelector('.expense-type')?.value;
            if (!type) return;

            const input = row.querySelector('input[name*="[amount]"]');
            if (!input || input.classList.contains('d-none')) return;

            const val = parseFloat(input.value) || 0;
            if (type === 'commission') {
                dynamicExpenses += contractValue * (val / 100);
            } else {
                dynamicExpenses += val;
            }
        });

        let dynamicPaid = 0;
        document.querySelectorAll('.aporte-value:not([data-existing="1"])').forEach(input => {
            dynamicPaid += parseFloat(input.value) || 0;
        });


        const totalExpenses = baseExpenses + dynamicExpenses;
        const totalPaid = basePaid + dynamicPaid;
        const netProfit = totalPaid - totalExpenses;

        document.getElementById('totalExpensesDisplayBelow').textContent = `$${totalExpenses.toFixed(2)}`;
        document.getElementById('totalPaidDisplayBelow').textContent = `$${totalPaid.toFixed(2)}`;

        const netEl = document.getElementById('netProfitDisplayBelow');
        netEl.textContent = `$${netProfit.toFixed(2)}`;
        netEl.className = 'h5 fw-bold ' + (netProfit >= 0 ? 'text-success' : 'text-danger');

        renderExpensesChart(totalExpenses, netProfit);
    };

        
        document.addEventListener('input', function (e) {
            if (
                e.target.closest('form[action*="lead-expenses"]') ||
                e.target.classList.contains('aporte-value') ||
                e.target.id === 'contractValue'
            ) {
                updateExpenseSummary();
            }
        });

        
        document.addEventListener('DOMContentLoaded', () => {
            updateExpenseSummary(); // al cargar
        });
</script>






<!-- Script Eliminar documento-->
<script>
    function deleteDocument(filePath, fileType) {
        if (confirm('Are you sure you want to delete this file?')) {
            document.getElementById('deleteFileType').value = fileType;
            document.getElementById('deleteFilePath').value = filePath;
            document.getElementById('deleteDocumentForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'))
        toastElList.map(function (toastEl) {
            new bootstrap.Toast(toastEl).show()
        })
    });
</script>

{{-- Imagenes--}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let leadId = document.querySelector('input[name="lead_id"]').value;

        // Manejo de subida de imágenes
        document.getElementById("uploadForm").addEventListener("submit", function (event) {
            event.preventDefault();
            
            let formData = new FormData(this);

            fetch("{{ route('lead.images.store') }}", {
                method: "POST",
                body: formData,
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Image uploaded correctly");
                    location.reload(); // Recargar la galería
                } else {
                    alert("Error uploading image.");
                }
            })
            .catch(error => console.error("Error sending image:", error));
        });
    });

    // modal view
    function showPreview(imageSrc) {
            if (!imageSrc) return;
            const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            document.getElementById("previewImage").src = imageSrc;
            modal.show();
        }

    // Eliminar imagen con AJAX
    function deleteImage(imageId) {
        if (!confirm("Are you sure you want to remove this image?")) return;

        fetch(`/lead-images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`image-${imageId}`).remove();
                alert("Image removed successfully");
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error("Image removed successfully", error));
    }
</script>
    
    

    
    
    

<style>
    .balance-chart {
        width: 70px !important;
        height: 70px !important;
    }
    #chartPercentageText {
        font-size: 0.9rem;
        font-weight: bold;
        color: #dc3545; /* default red */
    }
</style>

<script>
    function changeStatus(newStatus) {
        const currentStatus = document.getElementById('selectedStatus').value;

        if (currentStatus == newStatus) {
            alert('You are already on this status.');
            return;
        }

        Swal.fire({
            title: 'Change Status',
            text: 'Are you sure you want to change the status?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('selectedStatus').value = newStatus;
                document.getElementById('statusForm').submit();
            }
        });
    }
</script>


<style>
    .status-box {
        padding: 8px 16px;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        min-width: 100px;
        text-align: center;
        transition: all 0.3s ease-in-out;
    }
    .bg-orange {
        background-color: #f79646 !important;
    }
    .status-active {
        border: 3px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        transform: scale(1.05);
        z-index: 1;
    }
    .status-inactive {
        opacity: 0.5;
    }
</style>

<style>
    body { background: #2270be; }
    .card { border-radius: 10px; background: white; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }

    /* Estilo para la galería */
    .gallery-item {
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .gallery-item:hover {
        transform: scale(1.05);
    }

    /* Asegurar que la imagen en el modal se centre y se ajuste */
    #previewImage {
        max-width: 100%;
        max-height: 90vh;
        display: block;
        margin: auto;
    }
</style>

@endsection