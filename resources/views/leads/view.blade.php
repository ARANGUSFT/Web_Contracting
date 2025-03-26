@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-center"><i class="bi bi-person-circle"></i> Lead Details</h2>

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
    ];

        $currentIndex = array_search($lead->estado, array_keys($statusList));
        $statusKeys = array_keys($statusList);
    @endphp

 

    <div class="card shadow-lg p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="text-primary">{{ $lead->first_name }} {{ $lead->last_name }}</h4>
            <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <p><strong>📞 Phone:</strong> <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></p>
        <p><strong>📧 Email:</strong> <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
        <p class="small text-muted mb-2">
            <i class="bi bi-geo-alt text-warning"></i>
            {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
        </p>
        <p><strong>📅 Created At:</strong> {{ $lead->created_at->format('d M, Y') }}</p>


        <form id="statusForm" action="{{ route('leads.assignstatus', $lead->id) }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="status" id="selectedStatus">

            <label class="form-label fw-semibold text-muted">📌 Status:</label>
            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                {{-- Botón Retroceder --}}
                @if ($currentIndex > 0)
                    <button type="button" class="btn btn-outline-secondary" onclick="changeStatus({{ $statusKeys[$currentIndex - 1] }})">
                        &#8592; Back
                    </button>
                @endif

                {{-- Estados en fila --}}
                @foreach ($statusList as $key => $status)
                    <div class="status-box {{ $status['color'] }} {{ $lead->estado == $key ? 'status-active' : 'status-inactive' }}">
                        {{ $status['label'] }}
                    </div>
                @endforeach

                {{-- Botón Avanzar --}}
                @if ($currentIndex < count($statusList) - 1)
                    <button type="button" class="btn btn-outline-primary" onclick="changeStatus({{ $statusKeys[$currentIndex + 1] }})">
                        Next &#8594;
                    </button>
                @endif
            </div>
        </form>

    
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

        <!-- Documents -->
        <div class="tab-pane fade" id="invoice">
        <h4><i class="bi bi-receipt"></i> Documents</h4>
        <p>Review lead invoices and billing details.</p>
        <button class="btn btn-warning"><i class="bi bi-file-earmark-text"></i> Generate</button>
        </div>

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


<script>
    function changeStatus(newStatus) {
        if (confirm('Are you sure you want to change the status?')) {
            document.getElementById('selectedStatus').value = newStatus;
            document.getElementById('statusForm').submit();
        }
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
