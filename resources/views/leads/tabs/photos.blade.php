<!-- ================= PHOTOS TAB ================= -->
<div class="tab-pane fade" id="photos">

    <!-- ================= HEADER ================= -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-primary-subtle">
                <i class="bi bi-images text-primary"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-semibold">Photo Gallery</h4>
                <p class="text-muted small mb-0">Manage and organize your lead photos</p>
            </div>
        </div>
        <div>
            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2" id="totalImagesCounter">
                <i class="bi bi-image me-1"></i>
                {{ $images->count() }}
            </span>
        </div>
    </div>

    <!-- ================= UPLOAD CARD ================= -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-cloud-arrow-up me-2 text-success"></i>
                Upload New Photos
            </h6>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <!-- UPLOAD ZONE -->
                <div class="upload-zone text-center mb-4">
                    <i class="bi bi-cloud-upload fs-1 text-primary mb-3"></i>
                    <h5 class="fw-semibold mb-1">Drop images here</h5>
                    <p class="text-muted small">or click to browse files</p>
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('images').click()">
                        <i class="bi bi-folder2-open me-1"></i> Choose Files
                    </button>
                    <p class="text-muted small mt-3 mb-0">JPG • PNG • WEBP</p>
                    <input type="file" name="images[]" id="images" multiple class="d-none" accept="image/*">
                </div>

                <!-- UPLOAD PROGRESS -->
                <div id="uploadProgress" class="mb-4 d-none">
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Uploading...</span>
                        <span id="progressPercent">0%</span>
                    </div>
                    <div class="progress">
                        <div id="progressFill" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
                    </div>
                </div>

                <!-- PREVIEW IMAGES -->
                <div id="previewContainer" class="row g-3 mb-4"></div>

                <!-- ACTIONS -->
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <div id="fileInfo" class="text-muted small"></div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="leadImagesManager.resetUploadForm()">Cancel</button>
                        <button type="submit" class="btn btn-success" id="uploadBtn" disabled>
                            <i class="bi bi-cloud-upload me-1"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= BULK ACTIONS ================= -->
    <div class="card shadow-sm border-0 mb-4 d-none" id="bulkActionsCard">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label class="form-check-label small">Select All</label>
                    </div>
                    <span class="badge bg-primary-subtle text-primary" id="selectedCount">
                        <span id="selectedCountText">0</span> selected
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <button id="downloadSelectedBtn" class="btn btn-outline-primary btn-sm" disabled>
                        <i class="bi bi-download"></i>
                    </button>
                    <button id="deleteSelectedBtn" class="btn btn-outline-danger btn-sm" disabled>
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= GALLERY ================= -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>
                Photo Collection
            </h6>
        </div>
        <div class="card-body">
            <div id="galleryContainer">
                <div id="gallery" class="row g-3"></div>
                <!-- EMPTY STATE -->
                <div id="emptyState" class="text-center py-5" style="display: {{ $images->count() > 0 ? 'none' : 'block' }};">
                    <i class="bi bi-images display-5 text-muted mb-3"></i>
                    <h5 class="fw-semibold">No photos yet</h5>
                    <p class="text-muted small">Upload your first images to start the gallery</p>
                    <button class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('images').click()">
                        Upload Photos
                    </button>
                </div>
            </div>
        </div>
        <!-- PAGINATION -->
        <div class="card-footer bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <button id="prevPage" class="btn btn-outline-primary btn-sm" disabled>
                    <i class="bi bi-chevron-left"></i> Previous
                </button>
                <span class="text-muted small" id="pageInfoDetailed">Showing 0 images</span>
                <button id="nextPage" class="btn btn-outline-primary btn-sm" disabled>
                    Next <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= IMAGE MODAL ================= -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1060;"></button>
                <img src="" id="modalImage" class="img-fluid w-100 rounded" alt="Preview">
            </div>
        </div>
    </div>
</div>

<!-- ================= TOAST CONTAINER ================= -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999"></div>

<style>
    /* ================= ICON CIRCLE ================= */
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* ================= UPLOAD ZONE ================= */
    .upload-zone {
        border: 2px dashed #d9e2ec;
        border-radius: 16px;
        background: #f8fafc;
        padding: 40px;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .upload-zone:hover {
        border-color: #0d6efd;
        background: #f1f6ff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    }
    .upload-zone i {
        transition: 0.2s;
    }
    .upload-zone:hover i {
        transform: scale(1.08);
    }

    /* ================= UPLOAD BUTTON ================= */
    .upload-zone .btn {
        font-weight: 500;
        padding: 8px 22px;
    }

    /* ================= PROGRESS BAR ================= */
    .progress {
        height: 10px;
        border-radius: 20px;
        background: #eef2f7;
        overflow: hidden;
    }
    .progress-bar {
        border-radius: 20px;
    }

    /* ================= PREVIEW IMAGES ================= */
    #previewContainer .preview-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    #previewContainer img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
    }

    /* ================= BULK ACTION CARD ================= */
    #bulkActionsCard {
        border-radius: 12px;
        transition: 0.2s;
    }
    #bulkActionsCard .btn {
        border-radius: 8px;
    }

    /* ================= GALLERY ================= */
    #gallery .gallery-item {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        cursor: pointer;
        transition: 0.25s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    #gallery .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    /* ================= IMAGE ================= */
    .gallery-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    /* ================= HOVER OVERLAY ================= */
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        opacity: 0;
        transition: 0.2s;
    }
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    /* ================= ACTION BUTTONS ================= */
    .gallery-overlay button {
        border: none;
        background: white;
        padding: 8px 10px;
        border-radius: 8px;
        transition: 0.15s;
    }
    .gallery-overlay button:hover {
        transform: scale(1.1);
    }

    /* ================= EMPTY STATE ================= */
    #emptyState {
        opacity: 0.85;
    }
    #emptyState i {
        opacity: 0.35;
    }

    /* ================= PAGINATION ================= */
    .card-footer .btn {
        border-radius: 8px;
    }

    /* ================= SCROLLBAR ================= */
    #galleryContainer::-webkit-scrollbar {
        width: 6px;
    }
    #galleryContainer::-webkit-scrollbar-thumb {
        background: #cfd4da;
        border-radius: 10px;
    }

    /* Additional styles for checkboxes and overlays */
    .select-image {
        z-index: 2;
        cursor: pointer;
        transform: scale(1.2);
    }
    .image-counter {
        z-index: 2;
        font-size: 0.8rem;
    }
    .image-overlay {
        background: rgba(0,0,0,0.3);
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 1;
    }
    .gallery-card:hover .image-overlay {
        opacity: 1;
    }
    .gallery-card {
        transition: all 0.2s;
    }
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* ================= MODAL ================= */
    #imageModal .modal-content {
        background: transparent;
        box-shadow: none;
    }
    #imageModal .btn-close {
        background-color: rgba(0,0,0,0.5);
        border-radius: 50%;
        padding: 0.75rem;
        opacity: 0.8;
    }
    #imageModal .btn-close:hover {
        opacity: 1;
    }
    #imageModal .modal-body {
        padding: 0;
    }
</style>

<script>
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
        this.elements.emptyState = document.getElementById('emptyState');
        
        // Controles
        this.elements.counter = document.getElementById('totalImagesCounter');
        this.elements.prevBtn = document.getElementById('prevPage');
        this.elements.nextBtn = document.getElementById('nextPage');
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
        this.elements.bulkActionsCard = document.getElementById('bulkActionsCard');
        
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
        
        // Delegación de eventos para checkboxes de selección (se añaden dinámicamente)
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

        this.updateFileInfo();
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
                <button type="button" class="remove-preview position-absolute top-0 end-0 m-2 btn btn-sm btn-danger rounded-circle p-0" style="width:24px;height:24px;" data-index="${index}" aria-label="Remove ${file.name}">
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
        // Resetear selección al cargar nuevas imágenes
        this.state.selectedImages.clear();
        this.updateSelectionState();
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
        
        // Escapamos la URL para evitar problemas con comillas
        const escapedUrl = imageUrl.replace(/'/g, "\\'");
        
        return `
            <div class="col-xl-3 col-lg-4 col-md-6" id="image-${image.id}">
                <div class="card h-100 shadow-sm border-0 gallery-card" style="cursor: pointer;" onclick="leadImagesManager.openImageModal('${escapedUrl}', ${image.id})">
                    <div class="card-img-top position-relative overflow-hidden">
                        <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 image-counter">
                            #${imageNumber}
                        </span>
                        
                        <input type="checkbox" 
                            class="form-check-input position-absolute top-0 end-0 m-2 select-image" 
                            value="${image.id}"
                            onclick="event.stopPropagation()"
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
                                   onclick="event.stopPropagation()"
                                   title="Download image ${imageNumber}">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="${imageUrl}" 
                                   target="_blank" 
                                   class="btn btn-light btn-sm"
                                   onclick="event.stopPropagation()"
                                   title="View full size">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-light btn-sm" 
                                        onclick="event.stopPropagation(); leadImagesManager.deleteSingleImage(${image.id})"
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

    // Método para abrir el modal con la imagen
    openImageModal(imageUrl, imageId) {
        console.log('🔵 Abriendo modal para imagen:', imageId, imageUrl);

        const modalImage = document.getElementById('modalImage');
        const modalEl = document.getElementById('imageModal');

        if (!modalImage || !modalEl) {
            console.error('❌ No se encontró el modal o la imagen del modal');
            return;
        }

        // Establecer la imagen en el modal
        modalImage.src = imageUrl;
        modalImage.alt = `Image ${imageId}`;

        // Intentar con Bootstrap (si está disponible)
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
                console.log('✅ Modal abierto con Bootstrap');
            } catch (e) {
                console.warn('⚠️ Error con Bootstrap, usando fallback manual:', e);
                this._showModalFallback(modalEl);
            }
        } else {
            console.warn('⚠️ Bootstrap no disponible, usando fallback manual');
            this._showModalFallback(modalEl);
        }
    }

    // Método auxiliar para mostrar el modal manualmente
    _showModalFallback(modalEl) {
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        document.body.classList.add('modal-open');

        let backdrop = document.querySelector('.modal-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }

        const closeModal = () => {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
            if (backdrop && backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        };

        backdrop.onclick = closeModal;

        const closeBtn = modalEl.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.onclick = closeModal;
        }
    }

    updatePagination(pagination) {
        this.state.currentPage = pagination.current_page;
        this.state.lastPage = pagination.last_page;
        this.state.currentPagination = pagination;
        this.state.totalImages = pagination.total;
        this.state.imagesPerPage = pagination.per_page;
        
        if (this.elements.counter) {
            this.elements.counter.innerHTML = `<i class="bi bi-image me-1"></i> ${this.state.totalImages}`;
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

        this.showAlert('info', `Preparing ZIP with ${selected.length} images...`);

        fetch('{{ route("lead.images.downloadZip") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/zip'
            },
            body: JSON.stringify({ ids: selected })
        })
        .then(async response => {
            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const error = await response.json();
                    throw new Error(error.message || 'Error downloading ZIP');
                } else {
                    throw new Error(`HTTP error ${response.status}`);
                }
            }
            
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `lead-${this.leadId}-images.zip`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            
            this.showAlert('success', 'ZIP downloaded successfully');
        })
        .catch(error => {
            console.error('Download error:', error);
            this.showAlert('error', error.message || 'Error downloading images');
        });
    }

    // =============================================
    // ALERT & NOTIFICATION SYSTEM
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
            this.state.selectedImages.add(imageId.toString());
        } else {
            this.state.selectedImages.delete(imageId.toString());
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
        const total = document.querySelectorAll('#gallery .col-xl-3').length;
        if (this.elements.counter) this.elements.counter.innerHTML = `<i class="bi bi-image me-1"></i> ${total}`;
        if (this.elements.deleteAllBtn) this.elements.deleteAllBtn.disabled = total === 0;
    }

    // =============================================
    // UI STATE MANAGEMENT
    // =============================================

    updateUIState(hasImages) {
        if (this.elements.emptyState) this.elements.emptyState.style.display = hasImages ? 'none' : 'block';
        if (this.elements.bulkActionsCard) {
            this.elements.bulkActionsCard.classList.toggle('d-none', !hasImages);
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
// INSTANCIACIÓN DEL MANAGER DE GALERÍA
// =============================================

let leadImagesManager;

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 DOM Content Loaded - Initializing LeadImagesManager');
    if (document.getElementById('gallery')) {
        leadImagesManager = new LeadImagesManager();
        window.leadImagesManager = leadImagesManager;
    }
});
</script>