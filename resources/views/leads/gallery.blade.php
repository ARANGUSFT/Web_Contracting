@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg">
        <!-- Encabezado de la tarjeta -->
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📸 Galería de Imágenes - Lead #{{ $lead_id }}</h5>
            <button id="downloadAll" class="btn btn-primary btn-sm">
                <i class="bi bi-download"></i> Descargar Todas
            </button>
        </div>

        <!-- Cuerpo de la tarjeta -->
        <div class="card-body">
            <!-- Mensajes de éxito/error -->
            <div id="messages"></div>

            <!-- Formulario para subir imágenes -->
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label">📤 Seleccionar o tomar una foto</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-upload"></i> Subir Imagen
                </button>
            </form>

            <hr>

            <!-- Galería de imágenes -->
            <div id="gallery-box" class="row g-3">
                @forelse ($images as $image)
                    <div class="col-md-4 image-card">
                        <div class="card shadow">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 class="card-img-top img-fluid rounded gallery-item"
                                 onclick="showPreview('{{ asset('storage/' . $image->image_path) }}')">
                            <div class="card-body text-center">
                                <small class="text-muted d-block">📅 {{ \Carbon\Carbon::parse($image->created_at)->format('d/m/Y H:i') }}</small>
                                <a href="{{ asset('storage/' . $image->image_path) }}" download class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center" id="no-images-message">
                        🚫 No hay imágenes disponibles. ¡Sube una ahora!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal para Vista Previa -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewLabel">Vista Previa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="previewImage" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let leadId = {{ $lead_id }};

    // Función para mostrar mensajes
    function showMessage(type, message) {
        let messagesDiv = document.getElementById('messages');
        messagesDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => messagesDiv.innerHTML = '', 5000);
    }

    // Función para cargar imágenes dinámicamente
    function loadImages() {
        fetch(`/leads/${leadId}/images`)
            .then(response => response.json())
            .then(images => {
                let galleryBox = document.getElementById('gallery-box');
                let noImagesMessage = document.getElementById('no-images-message');

                galleryBox.innerHTML = '';

                if (images.length === 0) {
                    noImagesMessage.style.display = 'block';
                } else {
                    noImagesMessage.style.display = 'none';
                    images.forEach(img => {
                        galleryBox.innerHTML += `
                            <div class="col-md-4 image-card">
                                <div class="card shadow">
                                    <img src="/storage/${img.image_path}" class="card-img-top img-fluid rounded gallery-item"
                                         onclick="showPreview('/storage/${img.image_path}')">
                                    <div class="card-body text-center">
                                        <small class="text-muted d-block">📅 ${new Date(img.created_at).toLocaleString()}</small>
                                        <a href="/storage/${img.image_path}" download class="btn btn-outline-primary btn-sm mt-2">
                                            <i class="bi bi-download"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
            })
            .catch(error => {
                console.error("Error al cargar imágenes:", error);
                showMessage('danger', 'Error al cargar las imágenes.');
            });
    }

    // Subida de imágenes con AJAX
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let formData = new FormData();
        formData.append('lead_id', leadId);
        formData.append('image', document.getElementById('image').files[0]);

        fetch('/leads/images', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('image').value = '';
                showMessage('success', 'Imagen subida correctamente.');
                loadImages();
            } else {
                showMessage('danger', data.error || 'Error al subir la imagen.');
            }
        })
        .catch(error => {
            console.error("Error al subir imagen:", error);
            showMessage('danger', 'Error al subir la imagen.');
        });
    });

    // Descargar todas las imágenes como ZIP
    document.getElementById('downloadAll').addEventListener('click', function () {
        fetch(`/leads/${leadId}/images/download-all`)
            .then(response => {
                if (response.status === 404) {
                    showMessage('warning', 'No hay imágenes disponibles para descargar.');
                } else {
                    window.location.href = `/leads/${leadId}/images/download-all`;
                }
            })
            .catch(error => {
                console.error("Error al descargar imágenes:", error);
                showMessage('danger', 'Error al descargar las imágenes.');
            });
    });

    // Vista previa de imágenes
    function showPreview(imageSrc) {
        document.getElementById("previewImage").src = imageSrc;
        var modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        modal.show();
    }

    // Cargar imágenes al inicio
    document.addEventListener("DOMContentLoaded", function () {
        loadImages();
    });
</script>
@endpush
