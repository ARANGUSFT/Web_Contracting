@extends('layouts.app')

@section('content')
<!-- En el head de tu layout -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="text-primary m-0">
            <i class="bi bi-person-circle"></i> Customer Details
        </h2>
    </div>
    
   
    @if(session('success'))
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif


    @php
        $statusList = [
            1 => ['label' => 'Lead', 'color' => 'bg-warning'],
            2 => ['label' => 'Prospect', 'color' => 'bg-orange'],
            3 => ['label' => 'Approved', 'color' => 'bg-success'],
            4 => ['label' => 'Completed', 'color' => 'bg-primary'],
            5 => ['label' => 'Invoiced', 'color' => 'bg-danger'],
            6 => ['label' => 'Closed', 'color' => 'bg-secondary'], // ✅ NUEVO ESTADO 6
        ];

        $currentIndex = array_search($lead->estado, array_keys($statusList));
        $statusKeys = array_keys($statusList);
    @endphp

 
    {{-- Tarjeta --}}
    <div class="card shadow-lg p-4">
        <div class="d-flex justify-content-between align-items-center">

            <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <h4 class="text-primary">{{ $lead->first_name }} {{ $lead->last_name }}</h4>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Textual Info -->
                <div class="text-end me-2">
                    <h5 class="fw-bold mb-0" id="totalAmountText">$0.00</h5>
                    <div class="text-danger fw-bold">Balance Due</div>
                    <div class="text-danger small" id="balanceDueText">$0.00</div>
                </div>
            
                <!-- Chart with percentage -->
                <div class="position-relative" style="width: 70px; height: 70px;">
                    <canvas id="balanceChart" class="balance-chart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle fw-bold small" id="chartPercentageText">0%</div>
                </div>
            </div>
            

        </div>

        <p><strong>📞 Phone:</strong> <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></p>
        <p><strong>📧 Email:</strong> <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
        <p class="small text-muted mb-2">
            <i class="bi bi-geo-alt text-warning"></i>
            {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
        </p>
        <p><strong>📅 Created At:</strong> {{ $lead->created_at->format('d M, Y') }}</p>
        <p><strong>🕒 Last Touched:</strong> 
            {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
        </p>
        
        


        <form id="statusForm" action="{{ route('leads.assignstatus', $lead->id) }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="status" id="selectedStatus">
        
            <label class="form-label fw-semibold text-muted">📌 Status:</label>
            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                @if ($currentIndex > 0 && $lead->estado < 3)
                    <button type="button" class="btn btn-outline-secondary" onclick="changeStatus({{ $statusKeys[$currentIndex - 1] }})">
                        &#8592; Back
                    </button>
                @endif
        
                @foreach ($statusList as $key => $status)
                    <div class="status-box {{ $status['color'] }} {{ $lead->estado == $key ? 'status-active' : 'status-inactive' }}">
                        {{ $status['label'] }}
                    </div>
                @endforeach
        
                @if ($currentIndex < count($statusList) - 1)
                    <button type="button" class="btn btn-outline-primary" onclick="handleNextClick({{ $statusKeys[$currentIndex + 1] }})">
                        Next &#8594;
                    </button>
                @endif
            </div>
        </form>
        
        @if ($lead->estado == 2)
            <div class="card border-success mt-4 shadow-sm w-75 mx-auto">
                <div class="card-header bg-success text-white py-2 px-3">
                    <h6 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Approved Lead - Submit Information</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('leads.submitApprovedData', $lead->id) }}" method="POST">
                        @csrf
        
                        <div class="row g-2">
                            <!-- Company Info -->
                            <div class="col-md-6">
                                <p class="text-primary fw-bold small mb-1">🏢 Company Information</p>
                            
                                <div class="mb-2">
                                    <input type="text" name="company_name" 
                                           value="{{ $lead->user->company_name ?? '' }}" 
                                           placeholder="Company Name" 
                                           class="form-control form-control-sm" 
                                           readonly>
                                </div>
                            
                                <div class="mb-2">
                                    <input type="text" name="company_representative" 
                                           value="{{ $lead->user->name ?? '' }} {{ $lead->user->last_name ?? '' }}" 
                                           placeholder="Representative" 
                                           class="form-control form-control-sm" 
                                           readonly>
                                </div>
                            
                                <div class="mb-2">
                                    <input type="text" name="company_phone" 
                                           value="{{ $lead->user->phone ?? '' }}" 
                                           placeholder="Phone" 
                                           class="form-control form-control-sm" 
                                           readonly>
                                </div>
                            </div>
                            
        
                            <!-- Lead Info -->
                            <div class="col-md-6">
                                <p class="text-success fw-bold small mb-1">🙍‍♂️ Lead Information</p>
                                <div class="mb-2">
                                    <input type="text" name="lead_name" value="{{ $lead->first_name }}" placeholder="Lead Name" class="form-control form-control-sm" required>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="lead_address" value="{{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}" placeholder="Address" class="form-control form-control-sm" required>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="lead_phone" value="{{ $lead->phone }}" placeholder="Lead Phone" class="form-control form-control-sm" required>
                                </div>
                                <div class="mb-2">
                                    <input type="date" name="installation_date" value="{{ $lead->installation_date }}" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
        
                        <!-- Extra Info -->
                        <div class="mb-2 mt-2">
                            <textarea name="extra_info" class="form-control form-control-sm" rows="2" placeholder="Additional Notes"></textarea>
                        </div>
        
                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-send-fill me-1"></i>Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
        
    
    
    

       
            
            
        

        <hr>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="border p-3 rounded bg-light">
                    <strong>Total Expenses:</strong>
                    <div id="totalExpensesDisplayBelow" class="h5 text-danger">${{ number_format($lead->total_expenses, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border p-3 rounded bg-light">
                    <strong>Total Paid:</strong>
                    <div id="totalPaidDisplayBelow" class="h5 text-primary">${{ number_format($lead->total_paid, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border p-3 rounded bg-light">
                    <strong>Net Profit:</strong>
                    <div id="netProfitDisplayBelow" class="h5 fw-bold {{ $lead->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($lead->net_profit, 2) }}
                    </div>
                </div>
            </div>
        </div>




    
    </div>
    {{-- Fin --}}



    

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
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#contribution">Contribution</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#expenses">Expenses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#quote">Quote</a>
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
        

<!-- Photos Tab - Enhanced Design -->
<div class="tab-pane fade" id="photos">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-dark mb-1">
                <i class="bi bi-images me-2 text-primary"></i>Photo Manager
            </h4>
            <p class="text-muted small mb-0">Upload, organize and manage your photo collection</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                <i class="bi bi-images me-1"></i>
                <span id="totalImagesCounter">{{ $images->count() }}</span> images
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="bi bi-question-circle"></i>
            </button>
        </div>
    </div>

    <!-- Upload Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-0 pb-0">
            <h5 class="card-title mb-0">
                <i class="bi bi-cloud-arrow-up text-primary me-2"></i>Upload Images
            </h5>
        </div>
        <div class="card-body">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <!-- Enhanced Drop Area -->
                <div id="dropArea" class="border border-3 border-dashed rounded-4 p-5 text-center bg-light bg-opacity-50 position-relative transition-all">
                    <div class="drop-content">
                        <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3"></i>
                        <h5 class="fw-semibold text-dark mb-2">Drag & Drop your images here</h5>
                        <p class="text-muted mb-3">Supports JPG, PNG, GIF, WEBP • Max 10MB per image</p>
                        <input type="file" id="imagesInput" name="images[]" accept="image/*" multiple class="d-none">
                        <button type="button" class="btn btn-primary px-4" onclick="document.getElementById('imagesInput').click()">
                            <i class="bi bi-folder2-open me-2"></i> Select Files
                        </button>
                    </div>
                    <div id="dropOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10 rounded-4 d-none transition-all"></div>
                </div>

                <!-- File Selection Info -->
                <div id="fileSelectionInfo" class="alert alert-info mt-3 d-none">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-info-circle me-2"></i>
                            <span id="selectedFilesCount">0</span> files selected • 
                            <span id="totalFilesSize">0 MB</span>
                        </div>
                        <button type="button" class="btn-close" onclick="clearSelection()"></button>
                    </div>
                </div>

                <!-- Preview Section -->
                <div id="previewSection" class="d-none">
                    <h6 class="text-dark mb-3">
                        <i class="bi bi-eye me-2"></i>Preview
                        <small class="text-muted">(<span id="previewCount">0</span> images)</small>
                    </h6>
                    <div id="previewContainer" class="row g-3"></div>
                </div>

                <!-- Upload Progress -->
                <div id="uploadProgress" class="mt-4 d-none">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-dark fw-medium">Uploading...</span>
                        <span id="uploadPercent" class="text-primary fw-bold">0%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%;"></div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted" id="uploadStatus">Preparing upload...</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-4">
                    <button type="button" id="clearSelectionBtn" class="btn btn-outline-secondary flex-fill d-none" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-2"></i>Clear Selection
                    </button>
                    <button type="submit" id="uploadButton" class="btn btn-success flex-fill" disabled>
                        <i class="bi bi-cloud-upload me-2"></i> Upload Images
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-collection text-primary me-2"></i>Photo Gallery
                    <small class="text-muted ms-2">(<span id="galleryCounter">0</span> images)</small>
                </h5>
                <div class="d-flex gap-2">
                    <!-- Bulk Actions -->
                    <div id="bulkActions" class="d-none">
                        <button type="button" class="btn btn-outline-success btn-sm" id="downloadSelectedBtn">
                            <i class="bi bi-download me-1"></i> Download (<span id="selectedCount">0</span>)
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="deleteSelectedBtn">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="cancelSelectionBtn">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-sort-down"></i> Sort
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="newest">Newest First</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="oldest">Oldest First</a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="selectImagesBtn">
                        <i class="bi bi-check-square me-1"></i> Select
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Gallery Grid -->
            <div class="row g-3" id="gallery-box"></div>

            <!-- Empty State -->
            <div id="emptyGallery" class="text-center py-5 {{ $images->count() ? 'd-none' : '' }}">
                <i class="bi bi-images display-1 text-muted opacity-25"></i>
                <h5 class="text-muted mt-3">No images uploaded yet</h5>
                <p class="text-muted">Start by uploading some images using the uploader above.</p>
            </div>

            <!-- Load More -->
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="btn btn-outline-primary px-4 d-none">
                    <i class="bi bi-arrow-down-circle me-2"></i> Load More Images
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Viewer Modal -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-secondary border-bottom">
                <div class="d-flex align-items-center text-white">
                    <i class="bi bi-image me-2"></i>
                    <h6 class="modal-title mb-0" id="imageModalTitle">Image Viewer</h6>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-light btn-sm" id="downloadImageBtn">
                        <i class="bi bi-download me-1"></i> Download
                    </button>
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0 position-relative">
                <!-- Navigation Arrows -->
                <button class="btn btn-light btn-navigation position-absolute top-50 start-0 translate-middle-y ms-3" id="prevImageBtn" style="z-index: 10;">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-light btn-navigation position-absolute top-50 end-0 translate-middle-y me-3" id="nextImageBtn" style="z-index: 10;">
                    <i class="bi bi-chevron-right"></i>
                </button>
                
                <!-- Image Container -->
                <div class="d-flex justify-content-center align-items-center min-vh-50 p-4">
                    <img id="modalImage" src="" class="img-fluid modal-image" alt="Full size image" style="max-height: 70vh; object-fit: contain;">
                </div>
            </div>
            <div class="modal-footer border-secondary border-top justify-content-center">
                <div class="text-center text-white">
                    <small id="imageCounter" class="text-light opacity-75">Image 1 of 10</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle text-primary me-2"></i>Photo Manager Help
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Supported Formats:</h6>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check text-success me-2"></i>JPG, PNG, GIF, WEBP</li>
                    <li><i class="bi bi-check text-success me-2"></i>Maximum 10MB per image</li>
                </ul>
                <h6>Features:</h6>
                <ul class="list-unstyled">
                    <li><i class="bi bi-mouse me-2"></i>Drag & Drop upload</li>
                    <li><i class="bi bi-eye me-2"></i>Image preview before upload</li>
                    <li><i class="bi bi-arrows-fullscreen me-2"></i>Full-screen modal viewer</li>
                    <li><i class="bi bi-download me-2"></i>Download images (single & multiple)</li>
                    <li><i class="bi bi-arrow-left-right me-2"></i>Navigation between images</li>
                    <li><i class="bi bi-trash me-2"></i>One-click deletion</li>
                    <li><i class="bi bi-check-square me-2"></i>Multiple selection</li>
                    <li><i class="bi bi-hash me-2"></i>Photo numbering</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ========== GLOBAL VARIABLES ==========
    const galleryBox = document.getElementById('gallery-box');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const emptyGallery = document.getElementById('emptyGallery');
    const galleryCounter = document.getElementById('galleryCounter');
    const selectImagesBtn = document.getElementById('selectImagesBtn');
    const bulkActions = document.getElementById('bulkActions');
    const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const cancelSelectionBtn = document.getElementById('cancelSelectionBtn');
    const selectedCount = document.getElementById('selectedCount');
    
    let currentPage = 1;
    const leadId = {{ $lead->id }};
    const perPage = 20;
    let totalImagesLoaded = 0;
    let allGalleryImages = [];
    let selectedImages = new Set();
    let selectionMode = false;
    let isLoading = false; // ✅ NUEVO: Prevenir carga duplicada
    let isUploading = false; // ✅ NUEVO: Prevenir upload duplicado

    // Modal elements
    const imageViewerModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
    const modalImage = document.getElementById('modalImage');
    const imageModalTitle = document.getElementById('imageModalTitle');
    const imageCounter = document.getElementById('imageCounter');
    const prevImageBtn = document.getElementById('prevImageBtn');
    const nextImageBtn = document.getElementById('nextImageBtn');
    const downloadImageBtn = document.getElementById('downloadImageBtn');
    
    let currentImageIndex = 0;
    let currentModalImages = [];

    // Upload functionality elements
    const dropArea = document.getElementById('dropArea');
    const overlay = document.getElementById('dropOverlay');
    const input = document.getElementById('imagesInput');
    const previewContainer = document.getElementById('previewContainer');
    const uploadButton = document.getElementById('uploadButton');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadProgressBar = document.getElementById('uploadProgressBar');
    const uploadPercent = document.getElementById('uploadPercent');
    const uploadStatus = document.getElementById('uploadStatus');
    const fileSelectionInfo = document.getElementById('fileSelectionInfo');
    const previewSection = document.getElementById('previewSection');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    
    let selectedFiles = [];

    // ========== UPLOAD FUNCTIONS MEJORADAS ==========
    function handleFiles(files) {
        // ✅ MEJORADO: Limitar a 100 archivos máximo
        const maxFiles = 100;
        if (files.length > maxFiles) {
            showToast(`You can only upload up to ${maxFiles} files at once. The first ${maxFiles} files will be selected.`, 'warning');
            files = files.slice(0, maxFiles);
        }

        const validFiles = files.filter(file => {
            if (!file.type.startsWith('image/')) {
                showToast(`"${file.name}" is not a valid image file`, 'error');
                return false;
            }
            
            // ✅ MEJORADO: Verificar tipos específicos
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showToast(`"${file.name}" has unsupported format`, 'error');
                return false;
            }
            
            // ✅ MEJORADO: Aumentar límite a 50MB
            if (file.size > 50 * 1024 * 1024) {
                showToast(`"${file.name}" is too large (${(file.size / (1024 * 1024)).toFixed(2)}MB). Max 50MB`, 'error');
                return false;
            }
            
            if (file.size === 0) {
                showToast(`"${file.name}" is empty`, 'error');
                return false;
            }
            
            return true;
        });

        if (validFiles.length > 0) {
            selectedFiles = [...selectedFiles, ...validFiles];
            
            // ✅ NUEVO: Limitar el total a 100 archivos
            if (selectedFiles.length > maxFiles) {
                selectedFiles = selectedFiles.slice(0, maxFiles);
                showToast(`Maximum ${maxFiles} files allowed. Some files were removed.`, 'warning');
            }
            
            updateFileSelection();
            renderPreviews();
            showToast(`Added ${validFiles.length} file(s). Total: ${selectedFiles.length}/${maxFiles}`, 'success');
        }
    }

    function updateFileSelection() {
        const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);
        const sizeMB = (totalSize / (1024 * 1024)).toFixed(2);
        
        document.getElementById('selectedFilesCount').textContent = selectedFiles.length;
        document.getElementById('totalFilesSize').textContent = sizeMB + ' MB';
        document.getElementById('previewCount').textContent = selectedFiles.length;
        
        if (selectedFiles.length > 0) {
            fileSelectionInfo.classList.remove('d-none');
            previewSection.classList.remove('d-none');
            clearSelectionBtn.classList.remove('d-none');
            uploadButton.disabled = false;
        } else {
            fileSelectionInfo.classList.add('d-none');
            previewSection.classList.add('d-none');
            clearSelectionBtn.classList.add('d-none');
            uploadButton.disabled = true;
        }
    }

    function renderPreviews() {
        previewContainer.innerHTML = '';
        selectedFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                previewContainer.insertAdjacentHTML('beforeend', `
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="position-relative">
                                <img src="${e.target.result}" class="card-img-top" style="height:120px;object-fit:cover;" alt="Preview ${i + 1}">
                                <div class="position-absolute top-0 start-0 m-1">
                                    <span class="badge bg-primary bg-opacity-90 text-white px-2 py-1" style="font-size:0.7rem;">
                                        #${i + 1}
                                    </span>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" onclick="removeFile(${i})" style="width:30px;height:30px;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div class="card-body p-2">
                                <small class="text-muted d-block text-truncate" title="${file.name}">${file.name}</small>
                                <small class="text-muted">${(file.size / (1024 * 1024)).toFixed(2)} MB</small>
                            </div>
                        </div>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        });
    }

    // ✅ MEJORADO: Upload con mejor manejo
    function handleUploadSubmit(e) {
        e.preventDefault();
        
        if (isUploading) {
            showToast('Upload already in progress', 'warning');
            return;
        }

        if (!selectedFiles.length) {
            showToast('Please select files to upload', 'warning');
            return;
        }

        const formData = new FormData(e.target);
        
        // ✅ MEJORADO: Agregar archivos con verificación
        selectedFiles.forEach((file, index) => {
            if (file && file instanceof File) {
                formData.append('images[]', file);
            }
        });

        // ✅ MEJORADO: Agregar información adicional
        formData.append('total_files', selectedFiles.length);
        formData.append('chunk_index', 0);

        isUploading = true;
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="bi bi-cloud-upload me-2"></i> Uploading...';
        uploadProgress.classList.remove('d-none');
        uploadStatus.textContent = 'Preparing upload...';

        // ✅ MEJORADO: Usar fetch con timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 300000); // 5 minutos

        fetch('{{ route("lead.images.store") }}', {
            method: 'POST',
            body: formData,
            signal: controller.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || `Successfully uploaded ${selectedFiles.length} image(s)!`, 'success');
                
                // Limpiar selección
                selectedFiles = [];
                updateFileSelection();
                renderPreviews();
                input.value = '';
                
                // ✅ MEJORADO: Recargar galería completa
                reloadGallery();
            } else {
                throw new Error(data.error || 'Upload failed on server');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            
            let errorMessage = 'Upload failed. Please try again.';
            if (error.name === 'AbortError') {
                errorMessage = 'Upload timeout. Please try again with fewer files.';
            } else if (error.message.includes('HTTP error')) {
                errorMessage = `Server error: ${error.message}`;
            }
            
            showToast(errorMessage, 'error');
        })
        .finally(() => {
            isUploading = false;
            uploadButton.disabled = false;
            uploadButton.innerHTML = '<i class="bi bi-cloud-upload me-2"></i> Upload Images';
            uploadProgress.classList.add('d-none');
            clearTimeout(timeoutId);
        });
    }

    // ✅ NUEVO: Función para recargar galería
    function reloadGallery() {
        galleryBox.innerHTML = '';
        allGalleryImages = [];
        totalImagesLoaded = 0;
        currentPage = 1;
        loadImages(currentPage, false);
    }

    // ========== GALLERY FUNCTIONS MEJORADAS ==========
    function renderImages(images, append = false) {
        // ✅ MEJORADO: Manejar append vs replace
        if (!append) {
            galleryBox.innerHTML = '';
            allGalleryImages = [];
            totalImagesLoaded = 0;
        }

        if (images.length > 0) {
            emptyGallery.classList.add('d-none');
        }
        
        images.forEach((img, index) => {
            // ✅ NUEVO: Verificar duplicados
            const existingIndex = allGalleryImages.findIndex(existingImg => existingImg.id === img.id);
            if (existingIndex !== -1) {
                console.log('Duplicate image skipped:', img.id);
                return;
            }

            const imageNumber = totalImagesLoaded + index + 1;
            const imageIndex = allGalleryImages.length;
            allGalleryImages.push(img);
            
            galleryBox.insertAdjacentHTML('beforeend', `
                <div class="col-md-4 col-lg-3" id="image-${img.id}">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition-all image-card" data-image-id="${img.id}">
                        <div class="position-relative">
                            <!-- Checkbox for selection -->
                            <div class="position-absolute top-0 start-0 m-2 image-checkbox" style="display: none; z-index: 10;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check-${img.id}" 
                                           onchange="toggleImageSelection(${img.id}, this.closest('.image-card'))"
                                           style="width: 1.2em; height: 1.2em;">
                                </div>
                            </div>
                            
                            <img src="${img.url}" class="card-img-top gallery-image" 
                                 style="height:200px;object-fit:cover;cursor:pointer;" 
                                 alt="Uploaded image ${imageNumber}"
                                 data-image-index="${imageIndex}"
                                 data-image-id="${img.id}"
                                 data-image-url="${img.url}"
                                 data-image-name="${img.name || 'image' + imageNumber}">
                            <!-- Photo Counter Badge -->
                            <div class="position-absolute top-0 start-0 m-2" style="margin-left: 2.5rem !important;">
                                <span class="badge bg-primary bg-opacity-90 text-white px-2 py-1">
                                    #${imageNumber}
                                </span>
                            </div>
                            <!-- Date Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">${formatDate(img.created_at)}</span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm view-image-btn" 
                                        data-image-index="${imageIndex}">
                                    <i class="bi bi-arrows-fullscreen me-1"></i> View
                                </button>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-success btn-sm flex-fill" 
                                            onclick="downloadSingleImage(${img.id})">
                                        <i class="bi bi-download"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill delete-single-btn" 
                                            data-image-id="${img.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });

        totalImagesLoaded = allGalleryImages.length; // ✅ CORREGIDO: Usar longitud real
        updateGalleryCounter();
        addImageClickListeners();
    }

    function loadImages(page = 1, append = false) {
        if (isLoading) {
            console.log('Load already in progress, skipping...');
            return;
        }

        isLoading = true;
        if (append) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = '<i class="bi bi-arrow-down-circle me-2"></i> Loading...';
        }

        fetch(`{{ url('/leads') }}/${leadId}/images/paginated?page=${page}&per_page=${perPage}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    renderImages(data.images, append);
                    
                    if (data.next_page) {
                        loadMoreBtn.classList.remove('d-none');
                        loadMoreBtn.dataset.page = data.next_page;
                        currentPage = data.next_page;
                    } else {
                        loadMoreBtn.classList.add('d-none');
                    }
                } else {
                    throw new Error(data.error || 'Failed to load images');
                }
            })
            .catch(err => {
                console.error('Error loading images:', err);
                showToast('Error loading images', 'error');
            })
            .finally(() => {
                isLoading = false;
                if (append) {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = '<i class="bi bi-arrow-down-circle me-2"></i> Load More Images';
                }
            });
    }

    // ========== SELECTION MANAGEMENT ==========
    selectImagesBtn.addEventListener('click', function() {
        selectionMode = !selectionMode;
        toggleSelectionMode();
    });

    cancelSelectionBtn.addEventListener('click', function() {
        selectionMode = false;
        selectedImages.clear();
        toggleSelectionMode();
        updateSelectionUI();
    });

    deleteSelectedBtn.addEventListener('click', function() {
        deleteSelectedImages();
    });

    downloadSelectedBtn.addEventListener('click', function() {
        downloadSelectedImages();
    });

    function toggleSelectionMode() {
        if (selectionMode) {
            selectImagesBtn.classList.add('btn-primary');
            selectImagesBtn.classList.remove('btn-outline-primary');
            selectImagesBtn.innerHTML = '<i class="bi bi-check-square-fill me-1"></i> Selecting';
            bulkActions.classList.remove('d-none');
        } else {
            selectImagesBtn.classList.remove('btn-primary');
            selectImagesBtn.classList.add('btn-outline-primary');
            selectImagesBtn.innerHTML = '<i class="bi bi-check-square me-1"></i> Select';
            bulkActions.classList.add('d-none');
            selectedImages.clear();
        }
        
        document.querySelectorAll('.image-checkbox').forEach(checkbox => {
            checkbox.style.display = selectionMode ? 'block' : 'none';
        });
        
        document.querySelectorAll('.image-card').forEach(card => {
            card.classList.remove('selected');
        });
    }

    function updateSelectionUI() {
        selectedCount.textContent = selectedImages.size;
        deleteSelectedBtn.disabled = selectedImages.size === 0;
        downloadSelectedBtn.disabled = selectedImages.size === 0;
    }

    window.toggleImageSelection = function(imageId, cardElement) {
        if (selectedImages.has(imageId)) {
            selectedImages.delete(imageId);
            cardElement.classList.remove('selected');
        } else {
            selectedImages.add(imageId);
            cardElement.classList.add('selected');
        }
        updateSelectionUI();
    };

    // ========== DOWNLOAD FUNCTIONS ==========
    function downloadSelectedImages() {
        if (selectedImages.size === 0) return;

        if (selectedImages.size === 1) {
            const imageId = Array.from(selectedImages)[0];
            downloadSingleImage(imageId);
            return;
        }

        showToast(`Preparing ${selectedImages.size} images for download...`, 'info');

        let downloaded = 0;
        selectedImages.forEach(imageId => {
            setTimeout(() => {
                downloadSingleImage(imageId);
                downloaded++;
                
                if (downloaded === selectedImages.size) {
                    showToast(`Successfully downloaded ${downloaded} images`, 'success');
                }
            }, downloaded * 300);
        });
    }

    function downloadSingleImage(imageId) {
        const image = allGalleryImages.find(img => img.id == imageId);
        if (image) {
            const link = document.createElement('a');
            link.href = image.url;
            link.download = image.name || `image-${imageId}.jpg`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // ========== RESTANTE DEL CÓDIGO ==========
    // ... (las funciones de delete, modal, etc. se mantienen igual)

    function addImageClickListeners() {
        document.querySelectorAll('.gallery-image').forEach(img => {
            img.addEventListener('click', function() {
                if (selectionMode) {
                    const imageId = parseInt(this.dataset.imageId);
                    const cardElement = this.closest('.image-card');
                    toggleImageSelection(imageId, cardElement);
                } else {
                    openImageViewer(parseInt(this.dataset.imageIndex));
                }
            });
        });
        
        document.querySelectorAll('.view-image-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                openImageViewer(parseInt(this.dataset.imageIndex));
            });
        });

        document.querySelectorAll('.delete-single-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const imageId = this.dataset.imageId;
                deleteSingleImage(imageId);
            });
        });
    }

    function deleteSingleImage(imageId) {
        if (!confirm('Are you sure you want to delete this image?')) return;

        showToast('Deleting image...', 'info');
        
        fetch(`{{ route('lead.images.destroy', '') }}/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                removeImageFromDOM(imageId);
                showToast('Image deleted successfully', 'success');
            } else {
                showToast(data.error || 'Failed to delete image', 'error');
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            showToast('Error deleting image', 'error');
        });
    }

    function deleteSelectedImages() {
        if (selectedImages.size === 0) return;
        
        if (!confirm(`Are you sure you want to delete ${selectedImages.size} selected image(s)?`)) {
            return;
        }

        showToast(`Deleting ${selectedImages.size} image(s)...`, 'info');
        
        const deletePromises = Array.from(selectedImages).map(imageId => 
            fetch(`{{ route('lead.images.destroy', '') }}/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res => res.json())
        );

        Promise.all(deletePromises)
            .then(results => {
                const successCount = results.filter(result => result.success).length;
                
                if (successCount > 0) {
                    selectedImages.forEach(imageId => {
                        removeImageFromDOM(imageId);
                    });
                    
                    totalImagesLoaded = allGalleryImages.length;
                    updateGalleryCounter();
                    
                    showToast(`Successfully deleted ${successCount} image(s)`, 'success');
                }
                
                selectedImages.clear();
                selectionMode = false;
                toggleSelectionMode();
                updateSelectionUI();
            })
            .catch(err => {
                console.error('Bulk delete error:', err);
                showToast('Error deleting some images', 'error');
            });
    }

    function removeImageFromDOM(imageId) {
        const imageElement = document.getElementById(`image-${imageId}`);
        if (imageElement) {
            imageElement.remove();
        }
        
        const imageIndex = allGalleryImages.findIndex(img => img.id == imageId);
        if (imageIndex !== -1) {
            allGalleryImages.splice(imageIndex, 1);
        }
        
        if (selectedImages.has(parseInt(imageId))) {
            selectedImages.delete(parseInt(imageId));
            updateSelectionUI();
        }
        
        if (totalImagesLoaded === 0) {
            emptyGallery.classList.remove('d-none');
        }
    }

    function updateGalleryCounter() {
        galleryCounter.textContent = totalImagesLoaded;
        const totalImagesCounter = document.getElementById('totalImagesCounter');
        if (totalImagesCounter) {
            totalImagesCounter.textContent = totalImagesLoaded;
        }
    }

    function openImageViewer(imageIndex) {
        if (selectionMode) return;
        
        currentModalImages = allGalleryImages;
        currentImageIndex = imageIndex;
        updateModalImage();
        imageViewerModal.show();
    }

    function updateModalImage() {
        if (currentModalImages.length === 0) return;
        
        const currentImage = currentModalImages[currentImageIndex];
        modalImage.src = currentImage.url;
        
        const imageNumber = currentImageIndex + 1;
        imageModalTitle.textContent = `Image ${imageNumber}`;
        imageCounter.textContent = `Image ${imageNumber} of ${currentModalImages.length}`;
        
        downloadImageBtn.onclick = () => downloadImage(currentImage.url, currentImage.name || `image-${currentImage.id}`);
        
        prevImageBtn.style.visibility = currentImageIndex > 0 ? 'visible' : 'hidden';
        nextImageBtn.style.visibility = currentImageIndex < currentModalImages.length - 1 ? 'visible' : 'hidden';
    }

    // ========== EVENT LISTENERS ==========
    ['dragenter', 'dragover'].forEach(ev => {
        dropArea.addEventListener(ev, e => { 
            e.preventDefault(); 
            dropArea.classList.add('border-primary');
            overlay.classList.remove('d-none');
        });
    });
    
    ['dragleave', 'drop'].forEach(ev => {
        dropArea.addEventListener(ev, e => { 
            e.preventDefault(); 
            dropArea.classList.remove('border-primary');
            overlay.classList.add('d-none');
        });
    });

    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        const files = [...e.dataTransfer.files];
        if (files.length > 0) {
            showToast(`Processing ${files.length} file(s)...`, 'info');
            handleFiles(files);
        }
    });

    input.addEventListener('change', e => {
        const files = [...e.target.files];
        if (files.length > 0) {
            showToast(`Processing ${files.length} file(s)...`, 'info');
            handleFiles(files);
        }
    });

    // ✅ CORREGIDO: Usar la nueva función de upload
    document.getElementById('uploadForm').addEventListener('submit', handleUploadSubmit);

    // Modal Navigation
    prevImageBtn.addEventListener('click', function() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            updateModalImage();
        }
    });

    nextImageBtn.addEventListener('click', function() {
        if (currentImageIndex < currentModalImages.length - 1) {
            currentImageIndex++;
            updateModalImage();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (imageViewerModal._element.classList.contains('show')) {
            switch(e.key) {
                case 'ArrowLeft':
                    if (currentImageIndex > 0) {
                        currentImageIndex--;
                        updateModalImage();
                    }
                    break;
                case 'ArrowRight':
                    if (currentImageIndex < currentModalImages.length - 1) {
                        currentImageIndex++;
                        updateModalImage();
                    }
                    break;
                case 'Escape':
                    imageViewerModal.hide();
                    break;
            }
        }
    });

    loadMoreBtn.addEventListener('click', function () {
        const nextPage = parseInt(this.dataset.page);
        if (nextPage) {
            loadImages(nextPage, true);
        }
    });

    // ========== INITIALIZATION ==========
    loadImages(currentPage, false);
});

// ========== GLOBAL FUNCTIONS ==========
window.removeFile = (index) => {
    if (window.selectedFiles && window.selectedFiles[index]) {
        const removedFile = window.selectedFiles[index];
        window.selectedFiles.splice(index, 1);
        
        if (window.updateFileSelection) window.updateFileSelection();
        if (window.renderPreviews) window.renderPreviews();
        
        showToast(`Removed "${removedFile.name}"`, 'info');
    }
};

window.clearSelection = () => {
    if (window.selectedFiles) {
        const fileCount = window.selectedFiles.length;
        window.selectedFiles = [];
        
        if (window.updateFileSelection) window.updateFileSelection();
        if (window.renderPreviews) window.renderPreviews();
        
        const input = document.getElementById('imagesInput');
        if (input) input.value = '';
        
        if (fileCount > 0) {
            showToast(`Cleared ${fileCount} file(s)`, 'info');
        }
    }
};

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function downloadImage(imageUrl, fileName) {
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = fileName || 'download';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <strong>${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️'}</strong>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

// Exponer funciones globales
window.downloadSingleImage = function(imageId) {
    // Esta función será manejada por el event listener interno
};
</script>

<style>
.image-card.selected {
    border: 3px solid #0d6efd !important;
    background-color: rgba(13, 110, 253, 0.05);
}

.image-card {
    transition: all 0.3s ease;
}

.image-checkbox .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}

.transition-all {
    transition: all 0.3s ease;
}

.border-dashed {
    border-style: dashed!important;
}

.modal-image {
    border-radius: 0.5rem;
}

.btn-navigation {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.btn-navigation:hover {
    transform: scale(1.1);
}

.preview-selected {
    border: 3px solid #0d6efd !important;
}

#imageViewerModal .modal-content {
    background: rgba(0,0,0,0.9);
}

#imageViewerModal .modal-header {
    background: rgba(0,0,0,0.8);
}

.gallery-image:hover {
    opacity: 0.8;
}
</style>

         
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


        <!-- Contributions Tab -->
        <div class="tab-pane fade show" id="contribution">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4 text-primary">
                        <i class="bi bi-receipt me-2"></i> Financial Contributions
                    </h4>

                    <form method="POST" action="{{ route('leads.finanzas.update', $lead->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Contract Value --}}
                        <div class="row mb-4 align-items-center">
                            <label for="contractValue" class="col-md-3 col-form-label fw-semibold text-md-end">Contract Value</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="contract_value"
                                        value="{{ old('contract_value', $lead->contract_value) }}"
                                        class="form-control" required id="contractValue">
                                </div>
                            </div>
                        </div>

                        {{-- Contribution Table --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-secondary mb-0">
                                    <i class="bi bi-piggy-bank me-1"></i> Contributions
                                </h6>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">
                                    <i class="bi bi-plus-circle me-1"></i> Add Contribution
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-calendar-date"></i> Date</th>
                                            <th><i class="bi bi-currency-dollar"></i> Amount</th>
                                            <th><i class="bi bi-credit-card"></i> Method</th>
                                            <th><i class="bi bi-hash"></i> Check #</th>
                                            <th><i class="bi bi-card-text"></i> Notes</th>
                                            <th><i class="bi bi-tools"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="aportTable">
                                        @foreach($lead->finanzas ?? [] as $index => $aporte)
                                            <tr>
                                                <td>
                                                    <input type="date" name="finanzas[{{ $index }}][date]"
                                                        class="form-control" value="{{ old("finanzas.$index.date", $aporte['date']) }}">
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" name="finanzas[{{ $index }}][amount]"
                                                            class="form-control aporte-value"
                                                            value="{{ old("finanzas.$index.amount", $aporte['amount']) }}" data-existing="1">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name="finanzas[{{ $index }}][method]" class="form-select method-select">
                                                        <option value="">Select</option>
                                                        <option value="Cash" {{ $aporte['method'] === 'Cash' ? 'selected' : '' }}>Cash</option>
                                                        <option value="Check" {{ $aporte['method'] === 'Check' ? 'selected' : '' }}>Check</option>
                                                        <option value="Transfer" {{ $aporte['method'] === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="finanzas[{{ $index }}][check_number]"
                                                        class="form-control check-number-input"
                                                        value="{{ $aporte['check_number'] ?? '' }}">
                                                </td>
                                                <td>
                                                    <textarea name="finanzas[{{ $index }}][notes]" rows="1"
                                                            class="form-control form-control-sm"
                                                            placeholder="Add notes...">{{ $aporte['notes'] }}</textarea>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Remove">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Balance Display --}}
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label fw-semibold text-md-end">Balance</label>
                            <div class="col-md-6">
                                <div id="balanceDisplay" class="h5 text-success mb-0">$0.00</div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i> Save Financials
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


                
        <!-- Expense -->
        <div class="tab-pane fade show" id="expenses">

            <form action="{{ route('lead-expenses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 1; $i++)
                        <tr>
                            <td>
                                <input type="date" name="expenses[{{ $i }}][expense_date]" class="form-control">
                            </td>
                            <td>
                                <select name="expenses[{{ $i }}][type]" class="form-select expense-type">
                                    <option value="">Select</option>
                                    <option value="material">Material</option>
                                    <option value="labor">Labor</option>
                                    <option value="commission">Commission</option>
                                    <option value="permit">Permit</option>
                                    <option value="supplement">Supplement</option>
                                    <option value="other">Other</option>
                                </select>
                                
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="expenses[{{ $i }}][amount]" class="form-control amount-field" placeholder="$">
                                    <span class="input-group-text commission-label d-none">%</span>
                                </div>
                            </td>
                            
                        </tr>
                        @endfor
                    </tbody>
                </table>

                <button type="submit" class="btn btn-success">Save Expenses</button>
            </form>

            <hr>

            <h5 class="mt-4">
                <i class="bi bi-cash-coin me-1 text-primary"></i> Registered Expenses
            </h5>
            
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th><i class="bi bi-calendar-event"></i> Date</th>
                        <th><i class="bi bi-tag"></i> Type</th>
                        <th><i class="bi bi-currency-dollar"></i> Amount</th>
                        <th class="text-end"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lead->expenses as $expense)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-secondary text-capitalize">
                                {{ str_replace('_', ' ', $expense->type) }}
                            </span>
                        </td>
                        <td>
                            @if($expense->type === 'commission')
                                {{ number_format($expense->amount, 2) }}%
                            @else
                                ${{ number_format($expense->amount, 2) }}
                            @endif
                        </td>
                        <td class="text-end">
                            <form action="{{ route('lead-expenses.destroy', $expense->id) }}" method="POST" class="delete-expense-form d-inline">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                
                                
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No expenses registered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            

        </div>


        <!-- Quote -->
        <div class="tab-pane fade show" id="quote">

            <!-- Form to create a quote -->
            <form method="POST" action="{{ route('quotes.store') }}">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <div class="row">
                    <div class="col-md-4">
                        <label>Sq</label>
                        <input type="number" name="sq" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Material Cost per Sq</label>
                        <input type="number" step="0.01" name="material_cost_per_sq" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Labor Cost per Sq</label>
                        <input type="number" step="0.01" name="labor_cost_per_sq" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Other Costs</label>
                        <input type="number" step="0.01" name="other_costs" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Profit Percentage (%)</label>
                        <input type="number" step="0.01" name="percentage" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Quote</button>
            </form>

            <!-- Quote table -->
            <h5 class="mt-4">Previous Quotes</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sq</th>
                        <th>Material Total</th>
                        <th>Labor Total</th>
                        <th>Other Costs</th>
                        <th>Profit</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->quotes as $quote)
                    <tr>
                        <td>{{ $quote->sq }}</td>
                        <td>{{ number_format($quote->material_total, 2) }}</td>
                        <td>{{ number_format($quote->labor_total, 2) }}</td>
                        <td>{{ number_format($quote->other_costs, 2) }}</td>
                        <td>{{ number_format($quote->profit, 2) }}</td>
                        <td>{{ number_format($quote->quote_total, 2) }}</td>
                        <td>
                            <form id="delete-quote-form-{{ $quote->id }}" action="{{ route('quotes.destroy', $quote->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $quote->id }})" title="Delete this quote">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>


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
                    alert("Imagen subida correctamente.");
                    location.reload(); // Recargar la galería
                } else {
                    alert("Error al subir la imagen.");
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
                alert("Imagen eliminada correctamente.");
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
