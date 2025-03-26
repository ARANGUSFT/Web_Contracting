@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-center"><i class="bi bi-person-circle"></i> Lead Details</h2>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif


    <div class="card shadow-lg p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="text-primary">{{ $lead->first_name }} {{ $lead->last_name }}</h4>
            <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <p><strong>📞 Phone:</strong> <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></p>
        <p><strong>📧 Email:</strong> <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
        <p><strong>📅 Created At:</strong> {{ $lead->created_at->format('d M, Y') }}</p>

        @php
            $status = $statusMap[$lead->estado] ?? ['name' => 'Unknown', 'color' => 'bg-secondary'];
        @endphp

        <p><strong>📌 Status:</strong> 
            <span class="badge {{ $status['color'] }}">{{ $status['name'] }}</span>
        </p>
        <p class="small text-muted mb-2">
            <i class="bi bi-geo-alt text-warning"></i>
            {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
        </p>
    </div>

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
            <a class="nav-link" data-bs-toggle="tab" href="#Financial Worksheet">Financial Worksheet</a>
        </li>
    </ul>

    <div class="tab-content p-4 bg-white shadow-lg rounded">
        
        <!-- Chat Tab -->
        <div class="tab-pane fade show active" id="chat">
            <h4><i class="bi bi-chat"></i> Chat</h4>
            <div id="chat-box" class="border p-3 mb-3 rounded" style="height: 350px; overflow-y: auto; background: #bfd9ff;">
        
                @foreach($messages as $msg)
                    @php
                        // Determinar si el mensaje es de un vendedor o un usuario
                        $isSeller = isset($msg->team); // Si el mensaje viene de un vendedor
                        $senderName = $isSeller ? $msg->team->name : ($msg->user->name ?? 'Usuario');
        
                        // Definir el color y alineación de los mensajes
                        $isMine = $msg->user_id == auth()->id();
                        $messageClass = $isMine ? 'bg-primary text-white' : 'bg-light';
                        $alignment = $isMine ? 'justify-content-end' : 'justify-content-start';
                        $textAlign = $isMine ? 'text-end' : 'text-start';
                    @endphp
        
                    <div class="d-flex {{ $alignment }} mb-2">
                        <div class="p-2 rounded shadow-sm" style="max-width: 75%; background: {{ $isMine ? '#007bff' : '#ffffff' }}; color: {{ $isMine ? '#ffffff' : '#000' }};">
                            <strong class="d-block text-muted small">{{ $senderName }}</strong>
                            <p class="mb-0">{{ $msg->message }}</p>
                            <small class="d-block text-muted text-end mt-1" style="font-size: 0.8rem;">{{ $msg->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        
            <form method="POST" action="{{ route('lead.messages.store') }}">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                <div class="input-group">
                    <input type="text" name="message" class="form-control rounded-pill" placeholder="Write a message.." required>
                    <button class="btn btn-success rounded-pill ms-2 px-4" type="submit">
                        <i class="bi bi-send"></i> Send
                    </button>
                </div>
            </form>
        </div>

        <!-- Photos Tab -->
        <div class="tab-pane fade" id="photos">
            <h4><i class="bi bi-images"></i> Photos</h4>

            <!-- Formulario de subida -->
            <form id="uploadForm">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                <div class="mb-3">
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Upload Imagen</button>
            </form>

            <hr>

            <!-- Galería de imágenes -->
            <div class="row g-3" id="gallery-box">
                @foreach ($images as $image)
                    <div class="col-md-4" id="image-{{ $image->id }}">
                        <div class="card shadow">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="gallery-item img-fluid" onclick="showPreview('{{ asset('storage/' . $image->image_path) }}')">
                            <div class="card-body text-center">
        
                                <a href="{{ asset('storage/' . $image->image_path) }}" download class="btn btn-outline-primary btn-sm mt-2">
                                    Download 📥
                                </a>
                                <form action="{{ route('lead.images.destroy', $image->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm mt-2">
                                        Delete ❌
                                    </button>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

      
       <!-- Documents Section -->
        <div class="tab-pane fade" id="documents">
            <h4><i class="bi bi-folder"></i>Documents</h4>

            <form action="{{ route('seller.leads.updateDocuments', $lead->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="accordion" id="documentsAccordion">
                    @php
                        $documentTypes = [
                            ['title' => 'Lead Documents', 'field' => 'files', 'input' => 'files_documento'],
                            ['title' => 'Finance Documents', 'field' => 'finanzas', 'input' => 'files_finanzas'],
                            ['title' => 'Annex Documents', 'field' => 'anexos', 'input' => 'files_anexos'],
                            ['title' => 'Contract Documents', 'field' => 'contratos', 'input' => 'files_contratos'],
                        ];

                        $iconsByExtension = [
                            'pdf' => 'bi-file-earmark-pdf text-danger',
                            'xls' => 'bi-file-earmark-spreadsheet text-success',
                            'xlsx' => 'bi-file-earmark-spreadsheet text-success',
                            'doc' => 'bi-file-earmark-word text-primary',
                            'docx' => 'bi-file-earmark-word text-primary',
                            'default' => 'bi-file-earmark text-secondary',
                        ];
                    @endphp

                    @foreach($documentTypes as $index => $docType)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                    <i class="bi bi-folder-fill me-2"></i> {{ $docType['title'] }} ({{ count($lead->{$docType['field']} ?? []) }})
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#documentsAccordion">
                                <div class="accordion-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lead->{$docType['field']} ?? [] as $file)
                                                @php
                                                    if (is_array($file)) {
                                                        $path = $file['path'];
                                                        $original_name = $file['original_name'];
                                                    } else {
                                                        $path = $file;
                                                        $original_name = basename($file);
                                                    }
                                                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                                                    $iconClass = $iconsByExtension[$extension] ?? $iconsByExtension['default'];
                                                @endphp
                                                <tr>
                                                    <td>{{ $original_name }}</td>
                                                    <td>
                                                        <i class="bi {{ $iconClass }}"></i> {{ strtoupper($extension) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $path) }}" download class="btn btn-sm btn-outline-primary">
                                                            Download <i class="bi bi-download"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument('{{ $path }}', '{{ $docType['field'] }}')">
                                                            Delete <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        
                                        </tbody>
                                    </table>
                                    <input type="file" name="{{ $docType['input'] }}[]" multiple class="form-control">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-success mt-3">
                    <i class="bi bi-upload"></i> Save Document
                </button>
            </form>

            <!-- Formulario oculto para eliminar -->
            <form id="deleteDocumentForm" action="{{ route('seller.leads.delete-file', $lead->id) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="type" id="deleteFileType">
                <input type="hidden" name="file_path" id="deleteFilePath">
            </form>
        </div>
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
        

        <!-- Financial Worksheet -->
        <div class="tab-pane fade" id="invoice">
            <h4><i class="bi bi-receipt"></i> Financial Worksheet</h4>
            <p>Review lead invoices and billing details.</p>
            <button class="btn btn-warning"><i class="bi bi-file-earmark-text"></i> Generate</button>
        </div>

    </div>

</div>

<!-- Modal para vista previa de imágenes -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewLabel">Vista Previa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="previewImage" class="img-fluid rounded shadow" alt="Vista previa de la imagen">
            </div>
        </div>
    </div>
</div>





 <!-- Script Imagenes-->
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
            .catch(error => console.error("Error enviando imagen:", error));
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
        if (!confirm("¿Estás seguro de que quieres eliminar esta imagen?")) return;

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
        .catch(error => console.error("Error al eliminar imagen:", error));
    }
</script>

 <!-- Script Eliminar documento-->
<script>
            function deleteDocument(filePath, fileType) {
                if (confirm('¿Estás seguro de que deseas eliminar este archivo?')) {
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
