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
                            <input type="text" name="folder_name" class="form-control form-control-lg"
                                placeholder="Folder name" required>
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
        @foreach ($lead->folders as $index => $folder)
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
                    <div class="folder-info" data-bs-toggle="collapse"
                        data-bs-target="#folderContent{{ $index }}">
                        <i class="bi bi-folder-fill text-warning me-3"></i>
                        <div>
                            <h6 class="mb-0">{{ $folder->name }}</h6>
                            <small class="text-muted">{{ $files->count() }} files</small>
                        </div>
                    </div>

                    <div class="folder-actions">
                        <button type="button"
                            class="btn btn-sm btn-outline-primary border-0"
                            data-bs-toggle="modal"
                            data-bs-target="#editFolderModal{{ $index }}"
                            title="Rename folder">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('leads.folders.destroy', $folder->id) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                onclick="return confirm('Are you sure you want to delete this folder and all its files?');"
                                title="Delete folder">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                        <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="collapse"
                            data-bs-target="#folderContent{{ $index }}">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                </div>

                <!-- Folder Content -->
                <div class="collapse" id="folderContent{{ $index }}">
                    <div class="folder-content">
                        @if ($files->isEmpty())
                            <div class="empty-folder text-center py-4">
                                <i class="bi bi-file-earmark-plus text-muted display-6 mb-3"></i>
                                <p class="text-muted mb-3">No files in this folder</p>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#uploadModal{{ $index }}">
                                    <i class="bi bi-cloud-upload me-1"></i> Upload Files
                                </button>
                            </div>
                        @else
                            <!-- Files List -->
                            <div class="files-list">
                                @foreach ($files as $file)
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
                                                <small class="text-muted">
                                                    {{ strtoupper($extension) }} • {{ $file->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="file-actions">
                                            @if ($isPreviewable)
                                                <button class="btn btn-sm btn-outline-secondary preview-file"
                                                    data-file-url="{{ asset('storage/' . $path) }}"
                                                    data-file-name="{{ $original_name }}"
                                                    data-file-extension="{{ $extension }}"
                                                    title="Preview">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @endif

                                            <a href="{{ asset('storage/' . $path) }}" download
                                                class="btn btn-sm btn-outline-secondary" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>

                                            <form action="{{ route('leads.files.destroy', $file->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Delete" onclick="return confirm('Delete this file?');">
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
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                data-bs-target="#uploadModal{{ $index }}">
                                <i class="bi bi-cloud-upload me-1"></i> Add Files to {{ $folder->name }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rename Folder Modal -->
            <div class="modal fade" id="editFolderModal{{ $index }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title">Rename Folder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('leads.folders.update', $folder->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Folder Name</label>
                                    <input type="text"
                                        name="folder_name"
                                        class="form-control form-control-lg"
                                        value="{{ $folder->name }}"
                                        required>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2 me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Upload Modal -->
            <div class="modal fade" id="uploadModal{{ $index }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Upload to {{ $folder->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('leads.files.store', $lead->id) }}" method="POST"
                            enctype="multipart/form-data" id="uploadForm{{ $index }}">
                            @csrf
                            <input type="hidden" name="folder_id" value="{{ $folder->id }}">

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="files{{ $index }}" class="form-label">Select Files</label>
                                    <input type="file" name="files[]" class="form-control"
                                        id="files{{ $index }}" multiple required>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple files</div>
                                </div>

                                <div class="selected-files mt-3" id="selectedFiles{{ $index }}"
                                    style="display: none;">
                                    <h6 class="mb-2">Selected Files:</h6>
                                    <div class="selected-files-list" id="selectedFilesList{{ $index }}"></div>
                                    <small class="text-muted" id="fileCount{{ $index }}">0 files selected</small>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">
                                    Cancel
                                </button>
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
    @if ($lead->folders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-folder-x text-muted display-1 mb-3"></i>
            <h5 class="text-muted mb-2">No folders yet</h5>
            <p class="text-muted mb-3">Create your first folder to organize documents</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#createFolderModal">
                <i class="bi bi-folder-plus me-1"></i> Create Folder
            </button>
        </div>
    @endif
</div>

{{-- Blade comment 

<script>
    // =============================================
    // DOCUMENTOS Y ARCHIVOS (CARPETAS, VISTA PREVIA)
    // =============================================

    /**
     * Inicializa la vista previa de archivos (PDF, imágenes, texto) en modal.
     */
    function initializeFilePreviews() {
        const previewModalEl = document.getElementById('filePreviewModal');
        if (!previewModalEl) return;
        const previewModal = new bootstrap.Modal(previewModalEl);

        document.querySelectorAll('.preview-file').forEach(button => {
            button.addEventListener('click', function() {
                const fileUrl = this.getAttribute('data-file-url');
                const fileName = this.getAttribute('data-file-name');
                const fileExtension = this.getAttribute('data-file-extension').toLowerCase();

                document.getElementById('filePreviewModalLabel').textContent =
                    `Vista previa: ${fileName}`;
                document.getElementById('filePreviewDownload').href = fileUrl;

                // Ocultar secciones y mostrar loading
                document.getElementById('filePreviewContent').classList.add('d-none');
                document.getElementById('filePreviewUnsupported').classList.add('d-none');
                document.getElementById('filePreviewLoading').classList.remove('d-none');

                previewModal.show();

                // Simular carga y luego mostrar el contenido
                setTimeout(() => {
                    document.getElementById('filePreviewLoading').classList.add('d-none');
                    const previewContent = document.getElementById('filePreviewContent');

                    switch (fileExtension) {
                        case 'pdf':
                            previewContent.innerHTML =
                                `<embed src="${fileUrl}#toolbar=1&navpanes=0" type="application/pdf">`;
                            previewContent.classList.remove('d-none');
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            previewContent.innerHTML =
                                `<img src="${fileUrl}" alt="${fileName}" class="img-fluid">`;
                            previewContent.classList.remove('d-none');
                            break;
                        case 'txt':
                            fetch(fileUrl)
                                .then(response => response.text())
                                .then(text => {
                                    previewContent.innerHTML =
                                        `<pre>${escapeHtml(text)}</pre>`;
                                    previewContent.classList.remove('d-none');
                                })
                                .catch(() => {
                                    document.getElementById('filePreviewUnsupported')
                                        .classList.remove('d-none');
                                });
                            break;
                        default:
                            document.getElementById('filePreviewUnsupported').classList.remove(
                                'd-none');
                    }
                }, 500);
            });
        });

        // Limpiar contenido al cerrar el modal
        previewModalEl.addEventListener('hidden.bs.modal', function() {
            document.getElementById('filePreviewContent').innerHTML = '';
            document.getElementById('filePreviewContent').classList.add('d-none');
            document.getElementById('filePreviewUnsupported').classList.add('d-none');
        });

        // Helper para escapar HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    /**
     * Inicializa la subida de archivos en carpetas (muestra lista de archivos seleccionados).
     */
    function initializeFolderUploads() {
        @foreach ($lead->folders as $index => $folder)
            (function(index) {
                const fileInput = document.getElementById(`files${index}`);
                const selectedFiles = document.getElementById(`selectedFiles${index}`);
                const selectedFilesList = document.getElementById(`selectedFilesList${index}`);
                const fileCount = document.getElementById(`fileCount${index}`);

                if (fileInput) {
                    fileInput.addEventListener('change', function(e) {
                        const files = e.target.files;
                        updateSelectedFilesList(files, index);
                    });
                }

                function updateSelectedFilesList(files, idx) {
                    if (!selectedFilesList) return;
                    selectedFilesList.innerHTML = '';

                    if (files.length > 0) {
                        selectedFiles.style.display = 'block';
                        fileCount.textContent = `${files.length} archivo(s) seleccionado(s)`;

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
                        fileCount.textContent = '0 archivos seleccionados';
                    }
                }
            })({{ $index }});
        @endforeach

        // Función auxiliar para ícono según extensión
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

        // Formatear tamaño de archivo
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Autoexpandir carpeta al hacer clic en subir
        document.querySelectorAll('[data-bs-target^="#uploadModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target');
                const folderIndex = target.replace('#uploadModal', '');
                const collapseElement = document.getElementById('folderContent' + folderIndex);
                if (collapseElement) {
                    new bootstrap.Collapse(collapseElement, {
                        toggle: true
                    }).show();
                }
            });
        });

        // Rotar ícono chevron al expandir/colapsar
        document.querySelectorAll('.folder-header .btn[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('.bi-chevron-down');
                if (icon) {
                    icon.style.transform = this.getAttribute('aria-expanded') === 'true' ?
                        'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        });
    }

    // =============================================
    // ELIMINACIÓN DE DOCUMENTOS
    // =============================================

    /**
     * Prepara el formulario oculto para eliminar un documento y lo envía.
     * @param {string} filePath - Ruta del archivo.
     * @param {string} fileType - Tipo de archivo.
     */
    function deleteDocument(filePath, fileType) {
        if (confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
            document.getElementById('deleteFileType').value = fileType;
            document.getElementById('deleteFilePath').value = filePath;
            document.getElementById('deleteDocumentForm').submit();
        }
    }
</script>

--}}