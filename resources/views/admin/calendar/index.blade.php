@extends('admin.layouts.superadmin')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #4361ee;
        --primary-light: #e0e7ff;
        --secondary-color: #3f37c9;
        --accent-color: #4cc9f0;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --light-bg: #f8fafc;
        --light-border: #e2e8f0;
        --dark-text: #1e293b;
        --muted-text: #64748b;
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --box-shadow-sm: 0 2px 8px rgba(0,0,0,0.1);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    /* Contenedor principal mejorado */
    #calendar-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2rem;
        margin-top: 1.5rem;
        border: 1px solid var(--light-border);
    }
    
    #calendar {
        margin: 0 auto;
        min-height: 70vh;
        --fc-border-color: var(--light-border);
    }
    
    /* Barra de herramientas del calendario mejorada */
    .fc-header-toolbar {
        margin-bottom: 1.5rem !important;
        padding: 1rem;
        background: var(--light-bg);
        border-radius: var(--border-radius);
        border: 1px solid var(--light-border);
    }
    
    /* Estilos para la versión compacta */
    .compact-attachments-grid {
        display: grid;
        gap: 8px;
    }

    .compact-attachment-item {
        transition: all 0.2s ease;
    }

    .compact-attachment-item:hover {
        transform: translateY(-1px);
    }

    .file-icon {
        width: 24px;
        text-align: center;
        font-size: 1.1rem;
    }

    .file-info {
        min-width: 0; /* Permite que el text-truncate funcione */
    }

    .file-name {
        font-size: 0.78rem;
        font-weight: 500;
    }

    .file-meta {
        font-size: 0.68rem;
    }

    .fs-8 {
        font-size: 0.7rem !important;
    }

    .x-small {
        font-size: 0.65rem !important;
    }

    /* Ajustes para botones pequeños */
    .btn-sm.py-0.px-2 {
        padding-top: 0.15rem !important;
        padding-bottom: 0.15rem !important;
        font-size: 0.7rem;
    }
    
    .fc-toolbar-title {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 1.25rem;
    }
    
    .fc-button {
        background: white !important;
        border: 1px solid var(--light-border) !important;
        color: var(--dark-text) !important;
        transition: var(--transition);
        border-radius: var(--border-radius-sm) !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.875rem;
        box-shadow: none !important;
    }
    
    .fc-button:hover {
        background: var(--light-bg) !important;
        transform: translateY(-1px);
    }
    
    .fc-button-active {
        background: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }
    
    /* Eventos del calendario mejorados */
    .fc-event {
        border: none !important;
        border-radius: var(--border-radius-sm) !important;
        padding: 8px 12px !important;
        box-shadow: var(--box-shadow-sm);
        cursor: pointer;
        transition: var(--transition);
        margin-bottom: 4px;
        font-size: 0.8rem;
    }
    
    .fc-event:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .fc-event-title {
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .fc-event-crew {
        font-size: 0.7rem;
        opacity: 0.9;
        margin-top: 2px;
        display: flex;
        align-items: center;
    }
    
    .fc-event-crew i {
        margin-right: 4px;
        font-size: 0.6rem;
    }
    
    /* Botón de configuración mejorado */
    .settings-toggle {
        background: var(--primary-color);
        color: white;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        border: none;
        box-shadow: 0 2px 6px rgba(67, 97, 238, 0.2);
    }
    
    .settings-toggle:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }
    
    /* Modal mejorado */
    .modal-xl .modal-content {
        border-radius: var(--border-radius);
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .btn-close-white {
        filter: invert(1) brightness(100%);
    }
    
    /* Tarjetas mejoradas */
    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        overflow: hidden;
    }
    
    .card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background: var(--light-bg);
        border-bottom: 1px solid var(--light-border);
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-header i {
        color: var(--primary-color);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Offcanvas mejorado */
    .offcanvas {
        border-left: 1px solid var(--light-border);
        width: 400px !important;
    }
    
    .offcanvas-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--light-border);
    }
    
    .offcanvas-body {
        padding: 1.5rem;
    }
    
    /* Lista de compañías mejorada */
    .company-item {
        transition: var(--transition);
        border-radius: var(--border-radius-sm) !important;
        margin-bottom: 0.75rem;
        padding: 1rem !important;
        border: 1px solid var(--light-border);
        display: flex;
        align-items: center;
    }
    
    .company-item:hover {
        background: var(--light-bg);
        transform: translateX(2px);
    }
    
    /* Selector de color mejorado */
    .color-picker {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: var(--transition);
    }
    
    .color-picker:hover {
        transform: scale(1.1);
    }
    
    /* Interruptores mejorados */
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
        cursor: pointer;
        margin-right: 0.75rem;
    }
    
    /* Secciones mejoradas */
    .section-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        font-weight: 600;
    }
    
    /* Badges mejorados */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
    }
    
    /* Archivos adjuntos mejorados */
    .att-thumb {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--light-border);
        background: var(--light-bg);
    }
    
    .att-card {
        border: 1px solid var(--light-border);
        border-radius: var(--border-radius-sm);
        padding: 0.75rem;
        height: 100%;
        transition: var(--transition);
    }
    
    .att-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--box-shadow-sm);
    }
    
    .att-name {
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0.5rem 0;
        font-weight: 500;
        color: var(--dark-text);
    }
    
    /* Notas mejoradas - NUEVOS ESTILOS */
    .notes-container {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 8px;
        margin-bottom: 15px;
        border: 1px solid var(--light-border);
        border-radius: var(--border-radius-sm);
        background-color: #fafafa;
        padding: 10px;
    }
    
    .notes-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .notes-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .notes-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .notes-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    .note-item {
        border-radius: var(--border-radius-sm);
        padding: 10px;
        margin-bottom: 10px;
        background: white;
        border-left: 3px solid var(--primary-color);
        transition: var(--transition);
        font-size: 0.85rem;
    }
    
    .note-item:hover {
        transform: translateX(2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .note-item:last-child {
        margin-bottom: 0;
    }
    
    .note-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .note-author {
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--dark-text);
    }
    
    .note-time {
        font-size: 0.7rem;
        color: var(--muted-text);
    }
    
    .note-content {
        font-size: 0.8rem;
        line-height: 1.4;
        color: var(--dark-text);
        margin: 0;
        word-break: break-word;
    }
    
    .empty-notes {
        text-align: center;
        padding: 20px;
        color: var(--muted-text);
        font-size: 0.85rem;
    }
    
    .note-input-container {
        margin-top: 15px;
    }
    
    #note-content {
        font-size: 0.85rem;
        resize: vertical;
        min-height: 80px;
        max-height: 120px;
    }
    
    .notes-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .notes-count {
        font-size: 0.75rem;
        color: var(--muted-text);
        background: var(--light-bg);
        padding: 2px 8px;
        border-radius: 10px;
    }
    
    /* Estado vacío */
    .empty-state {
        color: var(--muted-text);
        text-align: center;
        padding: 2rem;
    }
    
    .empty-state i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Nuevos estilos para el grid de archivos */
    .attachments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    /* Responsividad mejorada */
    @media (max-width: 992px) {
        #calendar-container {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        #calendar-container {
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .fc-header-toolbar {
            flex-direction: column;
            gap: 0.5rem;
            padding: 0.75rem;
        }
        
        .fc-toolbar-chunk {
            width: 100%;
        }
        
        .settings-toggle {
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
        }
        
        .attachments-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .offcanvas {
            width: 100% !important;
        }
    }
</style>

@section('content')
<div class="container-fluid py-4">

    <!-- Header mejorado -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary text-white rounded-circle p-3 me-3">
                    <i class="fas fa-calendar-alt fa-lg"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0" style="color: var(--primary-color);">FareFinder Calendar</h2>
                    <p class="text-muted mb-0">Visualize and manage all jobs and emergencies in one place</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 text-md-end">
            <button class="btn settings-toggle" data-bs-toggle="offcanvas" data-bs-target="#companiesCanvas">
                <i class="fas fa-palette me-2"></i> Customize View
            </button>
        </div>
    </div>

    <!-- Contenedor del calendario -->
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>
    
    <!-- Offcanvas para configuración -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="companiesCanvas" aria-labelledby="companiesCanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="companiesCanvasLabel">
                <i class="fas fa-sliders-h me-2"></i>Calendar Customization
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <div class="alert alert-light mb-4">
                <i class="fas fa-info-circle me-2"></i> Customize company visibility and colors for better visualization
            </div>
            
            <h6 class="fw-bold mb-3 text-uppercase text-muted">Company Settings</h6>
            <ul id="companies-list" class="list-group list-group-flush">
            @forelse($companies as $c)
                <li class="list-group-item company-item py-3 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <!-- Toggle switch -->
                            <div class="form-check form-switch me-3">
                                <input 
                                    type="checkbox"
                                    class="form-check-input company-toggle" 
                                    role="switch" 
                                    id="toggle-{{ Str::slug($c['name']) }}" 
                                    data-name="{{ $c['name'] }}"
                                    {{ ($c['active'] ?? true) ? 'checked' : '' }}>
                            </div>

                            <!-- Nombre de la compañía -->
                            <label class="form-check-label fw-semibold" for="toggle-{{ Str::slug($c['name']) }}">
                                {{ $c['name'] }}
                            </label>
                        </div>
                        
                        <!-- Selector de color -->
                        <div class="d-flex align-items-center">
                            <input
                                type="color"
                                class="form-control form-control-color company-color d-none"
                                data-name="{{ $c['name'] }}"
                                value="{{ $c['color'] }}"
                                id="color-{{ Str::slug($c['name']) }}">
                            <label for="color-{{ Str::slug($c['name']) }}" 
                                   class="color-picker me-2" 
                                   style="background-color: {{ $c['color'] }}"
                                   title="Change color"></label>
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted text-center py-4">No companies available</li>
            @endforelse
            </ul>
            
            <div class="mt-4 pt-3 border-top text-center">
                <small class="text-muted"><i class="fas fa-sync-alt me-1"></i> Changes apply instantly</small>
            </div>
        </div>
    </div>

    <!-- Modal detalle/asignación -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- Header con icono y acciones -->
                <div class="modal-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-white text-primary me-3">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="eventModalLabel">Event Details</h5>
                            <div class="d-flex align-items-center mt-1">
                                <span class="badge bg-white text-dark me-2" id="event-type-badge"></span>
                                <small class="text-white-50" id="event-date-display"></small>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
        
                <!-- Body reorganizado -->
                <div class="modal-body p-0">
                    <div class="container-fluid">
                        <div class="row g-0">
                            <!-- Sección izquierda - Información principal -->
                            <div class="col-lg-8 p-4 border-end">
                                <!-- Tarjeta de información general -->
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">General Information</h6>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="eventActions" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash-alt me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body" id="event-info">
                                        <!-- Información dinámica -->
                                    </div>
                                </div>
                                
                                <!-- Tarjeta de archivos adjuntos -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-paperclip text-primary me-2"></i>
                                                <h6 class="mb-0 fw-bold">Attachments</h6>
                                            </div>
                                      
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="attachments-grid" id="attachments-list">
                                            <!-- Archivos dinámicos -->
                                            <div class="empty-state text-center py-4">
                                                <i class="fas fa-folder-open fa-2x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No attachments available</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección derecha - Interacción -->
                            <div class="col-lg-4 p-4">
                                <!-- Tarjeta de notas - VERSIÓN MEJORADA -->
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <div class="notes-section-header">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-comments text-primary me-2"></i>
                                                <h6 class="mb-0 fw-bold">Operation Notes</h6>
                                            </div>
                                            <span class="notes-count" id="notes-count">0 notes</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="notes-list" class="notes-container">
                                            <!-- Notas dinámicas -->
                                            <div class="empty-notes">
                                                <i class="fas fa-comment-dots mb-2" style="font-size: 1.5rem;"></i>
                                                <p class="small">No notes yet</p>
                                            </div>
                                        </div>
                                        <div class="note-input-container">
                                            <textarea id="note-content" class="form-control mb-2" placeholder="Write a note..."></textarea>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Press Enter to send</small>
                                                <button id="btn-note" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-paper-plane me-1"></i> Send
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tarjeta de asignación -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-users text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">Crew Assignment</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Select Crew Member</label>
                                            <select id="select-crew" class="form-select form-select-sm">
                                                <option value="">-- Select a crew --</option>
                                                @foreach($crews as $crew)
                                                    <option value="{{ $crew->id }}">{{ $crew->name }} ({{ $crew->company }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button id="btn-assign" class="btn btn-success w-100 btn-sm">
                                            <i class="fas fa-save me-1"></i> Save Assignment
                                        </button>
                                        <div class="assigned-crew mt-3 pt-2 border-top" id="current-assignment">
                                            <!-- Asignación actual -->
                                            <small class="text-muted">No crew assigned</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer con acciones rápidas -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-outline-primary">
                        <i class="fas fa-history me-1"></i> History
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>  
    </div>

    
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== CONSTANTES Y REFERENCIAS =====
        const calendarEl = document.getElementById('calendar');
        const modalEl = document.getElementById('eventModal');
        const eventInfo = document.getElementById('event-info');
        const selectCrew = document.getElementById('select-crew');
        const btnAssign = document.getElementById('btn-assign');
        const notesList = document.getElementById('notes-list');
        const noteContent = document.getElementById('note-content');
        const btnNote = document.getElementById('btn-note');
        const eventTypeBadge = document.getElementById('event-type-badge');
        const attachmentsList = document.getElementById('attachments-list');
        const notesCount = document.getElementById('notes-count');
        
        let currentEvent = { type: null, id: null };
        let abortController = new AbortController(); // Para cancelar peticiones

        // ===== HELPERS =====
        const Helpers = {
            fileExt(u = '') { 
                try {
                    const x = new URL(u, location.origin);
                    const n = (x.pathname.split('/').pop() || '').toLowerCase().split('?')[0].split('#')[0];
                    const p = n.split('.');
                    return p.length > 1 ? p.pop() : '';
                } catch {
                    return '';
                } 
            },
            
            isImg(ext) { 
                return ['jpg','jpeg','png','webp','gif','bmp','svg','heic','heif'].includes(ext); 
            },
            
            noteWhen(n) {
                if (n.created_at_human) return n.created_at_human;
                if (n.created_at_iso) {
                    const d = new Date(n.created_at_iso);
                    return isNaN(d) ? '' : d.toLocaleString();
                }
                if (n.created_at) {
                    const d = new Date(n.created_at);
                    return isNaN(d) ? String(n.created_at) : d.toLocaleString();
                }
                return '';
            },
            
            formatDate(dateString) {
                if (!dateString) return '—';
                const date = new Date(dateString);
                return isNaN(date) ? '—' : date.toLocaleDateString();
            },
            
            formatNoteDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return isNaN(date) ? '' : date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            },
            
            // NUEVO: Función para validar y corregir colores hexadecimales
            validateColor(color) {
                if (!color) return '#4361ee'; // Color por defecto
                
                // Si el color no empieza con #, añadirlo
                if (!color.startsWith('#')) {
                    color = '#' + color;
                }
                
                // Validar formato hexadecimal
                const hexRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
                if (!hexRegex.test(color)) {
                    console.warn(`Color inválido: ${color}, usando color por defecto`);
                    return '#4361ee'; // Color por defecto
                }
                
                return color;
            }
        };

        // ===== FUNCIONES DE RENDERIZADO =====
        const Render = {
            notes(notes) {
                if (!notes || !notes.length) {
                    notesList.innerHTML = `
                        <div class="empty-notes">
                            <i class="fas fa-comment-dots mb-2" style="font-size: 1.5rem;"></i>
                            <p class="small">No notes yet</p>
                        </div>
                    `;
                    notesCount.textContent = '0 notes';
                    return;
                }
                
                notesCount.textContent = notes.length + ' note' + (notes.length !== 1 ? 's' : '');
                
                const fragment = document.createDocumentFragment();
                notes.forEach(n => {
                    const div = document.createElement('div');
                    div.className = 'note-item';
                    div.innerHTML = `
                        <div class="note-header">
                            <span class="note-author">${n.user_name ?? 'System'}</span>
                            <span class="note-time">${Helpers.formatNoteDate(n.created_at)}</span>
                        </div>
                        <p class="note-content">${n.content ?? ''}</p>
                    `;
                    fragment.appendChild(div);
                });
                
                notesList.innerHTML = '';
                notesList.appendChild(fragment);
                
                // Hacer scroll al principio para ver las notas más recientes
                notesList.scrollTop = 0;
            },
            
            addAttachmentGroup(title, urls, container) {
                const items = Array.isArray(urls) ? urls.filter(Boolean) : [];
                if (!items.length) return 0;
                
                const group = document.createElement('div');
                group.className = 'mb-3';
                
                group.innerHTML = `
                    <h6 class="section-title d-flex align-items-center">
                        <i class="fas fa-folder-open me-2 text-muted"></i>
                        ${title} 
                        <span class="badge bg-light text-dark ms-2 fs-8">${items.length}</span>
                    </h6>
                    <div class="compact-attachments-grid"></div>
                `;
                
                const grid = group.querySelector('.compact-attachments-grid');
                const fragment = document.createDocumentFragment();
                
                items.forEach((url) => {
                    const ext = Helpers.fileExt(url);
                    const fileName = url.split('/').pop();
                    const isImage = Helpers.isImg(ext);
                    
                    const attachmentItem = document.createElement('div');
                    attachmentItem.className = 'compact-attachment-item';
                    attachmentItem.innerHTML = `
                        <div class="d-flex align-items-center p-2 border rounded bg-white">
                            <div class="file-icon me-2">
                                ${isImage 
                                    ? '<i class="fas fa-file-image text-primary"></i>'
                                    : `<i class="fas ${ext === 'pdf' ? 'fa-file-pdf text-danger' : 'fa-file text-secondary'}"></i>`}
                            </div>
                            <div class="file-info flex-grow-1 overflow-hidden">
                                <div class="file-name small text-truncate">${fileName}</div>
                                <div class="file-meta x-small text-muted">${ext.toUpperCase()} file</div>
                            </div>
                            <div class="file-actions ms-2 d-flex">
                                <a href="${url}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="${url}" download class="btn btn-sm btn-outline-secondary py-0 px-2" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    `;
                    fragment.appendChild(attachmentItem);
                });
                
                grid.appendChild(fragment);
                container.appendChild(group);
                return items.length;
            },
            
            emptyAttachments() {
                attachmentsList.innerHTML = `
                    <div class="text-center py-3">
                        <i class="fas fa-folder-open fa-lg text-muted mb-2 opacity-50"></i>
                        <p class="text-muted small mb-0">No attachments available</p>
                    </div>
                `;
            }
        };

        // ===== FUNCIONES DE LA API =====
        const API = {
            async fetchWithTimeout(url, options = {}, timeout = 10000) {
                const controller = new AbortController();
                const id = setTimeout(() => controller.abort(), timeout);
                
                try {
                    const response = await fetch(url, {
                        ...options,
                        signal: controller.signal
                    });
                    clearTimeout(id);
                    return response;
                } catch (error) {
                    clearTimeout(id);
                    throw error;
                }
            },
            
            async getEventDetails(type, id) {
                try {
                    abortController.abort(); // Cancelar petición anterior
                    abortController = new AbortController();
                    
                    const response = await this.fetchWithTimeout(
                        `{{ url('superadmin/calendar/event') }}/${type}/${id}`,
                        { signal: abortController.signal }
                    );
                    
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return await response.json();
                } catch (error) {
                    if (error.name === 'AbortError') {
                        console.log('Fetch aborted');
                    } else {
                        throw error;
                    }
                }
            },
            
            async assignCrew(data) {
                const response = await fetch('{{ route("superadmin.calendar.assign") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                return response.json();
            },
            
            async storeNote(data) {
                const response = await fetch('{{ route("superadmin.calendar.storeNote") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                return response.json();
            },
            
            async updateCompanyColor(data) {
                // Validar el color antes de enviarlo
                data.color = Helpers.validateColor(data.color);
                
                const response = await fetch('{{ route("superadmin.calendar.company.updateColor") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                return response;
            },
            
            async updateCompanyVisibility(data) {
                const response = await fetch('{{ route("superadmin.calendar.company.updateVisibility") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                return response;
            }
        };

        // ===== CONFIGURACIÓN DE FULLCALENDAR =====
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'en',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            events: {
                url: '{{ route("superadmin.calendar.events") }}',
                method: 'GET',
                failure: function() {
                    console.error('Error loading events');
                    // Podrías mostrar un mensaje al usuario aquí
                }
            },
            eventDisplay: 'block',
            eventTimeFormat: { 
                hour: '2-digit', 
                minute: '2-digit', 
                meridiem: false,
                hour12: false
            },
            dayMaxEvents: 3, // Mostrar "+ más" cuando hay muchos eventos
            eventOrder: 'start,-duration,title', // Ordenar eventos
            
            // Renderizado optimizado de eventos
            eventContent: function(arg) {
                const event = arg.event;
                const crewName = event.extendedProps.crewName;
                
                // Usar createElement en lugar de strings HTML para mejor rendimiento
                const eventDiv = document.createElement('div');
                eventDiv.className = 'fc-event-main';
                
                const titleDiv = document.createElement('div');
                titleDiv.className = 'fc-event-title';
                titleDiv.textContent = event.title;
                eventDiv.appendChild(titleDiv);
                
                if (crewName) {
                    const crewDiv = document.createElement('div');
                    crewDiv.className = 'fc-event-crew';
                    
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-user me-1';
                    
                    crewDiv.appendChild(icon);
                    crewDiv.appendChild(document.createTextNode(crewName));
                    eventDiv.appendChild(crewDiv);
                }
                
                return { domNodes: [eventDiv] };
            },
            
            // Estilo de eventos
            eventDidMount: function(info) {
                const event = info.event;
                
                if (event.extendedProps.crewName) {
                    info.el.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
                    info.el.style.borderLeft = '4px solid var(--primary-color)';
                }
                
                // Tooltip para eventos con título largo
                if (event.title.length > 30) {
                    info.el.title = event.title;
                }
                
                // Diferentes colores según el tipo
                if (event.extendedProps.type === 'emergency') {
                    info.el.style.borderLeft = '4px solid var(--danger-color)';
                }
            },
            
            // Manejo de clic en evento
            eventClick: async function(info) {
                const { id, extendedProps } = info.event;
                currentEvent = { id, type: extendedProps.type };
                
                // Actualizar UI
                eventTypeBadge.textContent = extendedProps.type === 'job' ? 'JOB' : 'EMERGENCY';
                eventTypeBadge.className = extendedProps.type === 'job' ? 
                    'badge bg-success' : 'badge bg-danger';
                
                eventInfo.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading event details...</p></div>';
                attachmentsList.innerHTML = '';
                notesList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
                
                try {
                    const json = await API.getEventDetails(extendedProps.type, id);
                    if (!json) return; // Request fue cancelado
                    
                    const d = json.data;
                    let html = '';
                    let totalAttachments = 0;
                    
                    // Limpiar contenedor de adjuntos
                    attachmentsList.innerHTML = '';
                    
                    if (extendedProps.type === 'job') {
                        html = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="section-title">Request Details</h6>
                                <p><strong><i class="fas fa-calendar me-2"></i>Requested On:</strong> ${Helpers.formatDate(d.install_date_requested)}</p>
                                <p><strong><i class="fas fa-building me-2"></i>Company:</strong> ${d.company_name}</p>
                                <p><strong><i class="fas fa-user-tie me-2"></i>Rep:</strong> ${d.company_rep}</p>
                                <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> ${d.company_rep_phone}</p>
                                <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                                    <a href="mailto:${d.company_rep_email}">${d.company_rep_email}</a>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="section-title">Customer Information</h6>
                                <p><strong><i class="fas fa-user me-2"></i>Name:</strong> ${d.customer_first_name} ${d.customer_last_name || ''}</p>
                                <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> ${d.customer_phone_number}</p>
                                
                                <h6 class="section-title mt-3">Job Address</h6>
                                <p><i class="fas fa-map-marker-alt me-2"></i>
                                    ${d.job_address_street_address}
                                    ${d.job_address_street_address_line_2 ? '<br>' + d.job_address_street_address_line_2 : ''}
                                    <br>${d.job_address_city}, ${d.job_address_state} ${d.job_address_zip_code}
                                </p>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="section-title">Materials & Delivery</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Roof Loaded:</strong></td>
                                            <td>${d.material_roof_loaded}</td>
                                            <td><strong>Delivery Date:</strong></td>
                                            <td>${Helpers.formatDate(d.delivery_date)}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Starter Bundles:</strong></td>
                                            <td>${d.starter_bundles_ordered}</td>
                                            <td><strong>Hip & Ridge:</strong></td>
                                            <td>${d.hip_and_ridge_ordered}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Field Shingles:</strong></td>
                                            <td>${d.field_shingle_bundles_ordered}</td>
                                            <td><strong>Cap Rolls:</strong></td>
                                            <td>${d.modified_bitumen_cap_rolls_ordered}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        `;
                        
                        totalAttachments += Render.addAttachmentGroup('Aerial Measurements', d.aerial_measurement, attachmentsList);
                        totalAttachments += Render.addAttachmentGroup('Material Order', d.material_order, attachmentsList);
                        totalAttachments += Render.addAttachmentGroup('Other Uploads', d.file_upload, attachmentsList);
                    } else {
                        html = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="section-title">Emergency Details</h6>
                                <p><strong><i class="fas fa-calendar me-2"></i>Date Submitted:</strong> ${Helpers.formatDate(d.date_submitted)}</p>
                                <p><strong><i class="fas fa-exclamation-triangle me-2"></i>Type:</strong> ${d.type_of_supplement}</p>
                                <p><strong><i class="fas fa-building me-2"></i>Company:</strong> ${d.company_name}</p>
                                <p><strong><i class="fas fa-envelope me-2"></i>Contact:</strong> 
                                    <a href="mailto:${d.company_contact_email}">${d.company_contact_email}</a>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="section-title">Location</h6>
                                <p><i class="fas fa-map-marker-alt me-2"></i>
                                    ${d.job_address}
                                    ${d.job_address_line2 ? '<br>' + d.job_address_line2 : ''}
                                    <br>${d.job_city}, ${d.job_state} ${d.job_zip_code}
                                </p>
                                
                                <div class="alert alert-light mt-3">
                                    <p class="mb-1"><strong>Terms Accepted:</strong> ${d.terms_conditions ? '✅ Yes' : '❌ No'}</p>
                                    <p class="mb-0"><strong>Requirements:</strong> ${d.requirements ? '✅ Met' : '❌ Pending'}</p>
                                </div>
                            </div>
                        </div>
                        `;
                        
                        totalAttachments += Render.addAttachmentGroup('Aerial Images', d.aerial_measurement_path, attachmentsList);
                        totalAttachments += Render.addAttachmentGroup('Contract Files', d.contract_upload_path, attachmentsList);
                        totalAttachments += Render.addAttachmentGroup('Picture Uploads', d.file_picture_upload_path, attachmentsList);
                    }
                    
                    if (totalAttachments === 0) {
                        Render.emptyAttachments();
                    }
                    
                    eventInfo.innerHTML = html;
                    Render.notes(d.notes || []);
                    noteContent.value = '';
                    selectCrew.value = d.crew_id || '';
                    
                    // Mostrar modal
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    
                } catch (error) {
                    console.error('Error loading event details:', error);
                    eventInfo.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading event details. Please try again.
                        </div>
                    `;
                }
            }
        });

        // Inicializar calendario
        calendar.render();
        
        // ===== MANEJADORES DE EVENTOS =====
        // Asignar crew
        btnAssign.addEventListener('click', async () => {
            if (!currentEvent.id || !selectCrew.value) return;
            
            const originalHtml = btnAssign.innerHTML;
            btnAssign.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
            btnAssign.disabled = true;
            
            try {
                const resp = await API.assignCrew({
                    type: currentEvent.type,
                    id: currentEvent.id,
                    crew_id: selectCrew.value
                });
                
                if (resp.success) {
                    calendar.refetchEvents();
                    bootstrap.Modal.getInstance(modalEl).hide();
                }
            } catch (error) {
                console.error('Assignment error:', error);
                alert('Error saving assignment. Please try again.');
            } finally {
                btnAssign.innerHTML = originalHtml;
                btnAssign.disabled = false;
            }
        });

        // Agregar nota
        btnNote.addEventListener('click', async () => {
            const content = noteContent.value.trim();
            if (!content || !currentEvent.id) return;
            
            const originalHtml = btnNote.innerHTML;
            btnNote.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btnNote.disabled = true;
            
            try {
                const n = await API.storeNote({
                    type: currentEvent.type,
                    id: currentEvent.id,
                    content: content
                });
                
                // Prepend nueva nota
                const div = document.createElement('div');
                div.className = 'note-item';
                div.innerHTML = `
                    <div class="note-header">
                        <span class="note-author">${n.user_name ?? 'System'}</span>
                        <span class="note-time">${Helpers.formatNoteDate(n.created_at)}</span>
                    </div>
                    <p class="note-content">${n.content ?? ''}</p>
                `;
                notesList.prepend(div);
                
                // Actualizar contador
                const currentCount = parseInt(notesCount.textContent) || 0;
                notesCount.textContent = (currentCount + 1) + ' note' + (currentCount + 1 !== 1 ? 's' : '');
                
                // Eliminar estado vacío si existe
                const emptyState = notesList.querySelector('.empty-notes');
                if (emptyState) {
                    emptyState.remove();
                }
                
                noteContent.value = '';
                
                // Hacer scroll al principio
                notesList.scrollTop = 0;
            } catch (error) {
                console.error('Note save error:', error);
                alert('Error saving note. Please try again.');
            } finally {
                btnNote.innerHTML = originalHtml;
                btnNote.disabled = false;
            }
        });
        
        // Enviar nota con Enter
        noteContent.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                btnNote.click();
            }
        });
            
        // ===== MANEJADORES PARA CONFIGURACIÓN DE COMPAÑÍAS =====
        
        // Manejar cambios en selectores de color
        document.querySelectorAll('.company-color').forEach(input => {
            input.addEventListener('change', async function() {
                const name = this.dataset.name;
                try {
                    const validColor = Helpers.validateColor(this.value);
                    document.querySelector(`label[for="color-${name.toLowerCase().replace(/ /g, '-')}"]`).style.backgroundColor = validColor;
                    await API.updateCompanyColor({ name, color: validColor });
                    calendar.refetchEvents();
                } catch (error) {
                    console.error('Color update error:', error);
                }
            });
        });

        // Manejar cambios en switches de visibilidad
        document.querySelectorAll('.form-check-input').forEach(input => {
            if (input.dataset.name) { // Solo los que tienen data-name (son los de compañías)
                input.addEventListener('change', async function() {
                    const name = this.dataset.name;
                    try {
                        await API.updateCompanyVisibility({ name, active: this.checked });
                        calendar.refetchEvents();
                    } catch (error) {
                        console.error('Visibility update error:', error);
                        // Revertir el cambio en caso de error
                        this.checked = !this.checked;
                    }
                });
            }
        });

        // Delegación de eventos para los visualizadores de color
        document.getElementById('companies-list').addEventListener('click', (e) => {
            if (e.target.matches('.color-picker')) {
                const colorInput = document.getElementById(e.target.getAttribute('for'));
                colorInput.click();
            }
        });
        
        // Mejorar rendimiento en resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => calendar.updateSize(), 250);
        });
    });
</script>