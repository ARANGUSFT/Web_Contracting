@extends('admin.layouts.superadmin')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-soft: #dbeafe;
        --secondary: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1e293b;
        --muted: #94a3b8;
        --light: #f8fafc;
        --border: #e2e8f0;
        --card-bg: #ffffff;
        --body-bg: #f1f5f9;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        --radius-lg: 1rem;
        --radius-md: 0.75rem;
        --radius-sm: 0.5rem;
        --transition: all 0.2s ease;
    }

    body {
        background-color: var(--body-bg);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--dark);
    }

    /* Contenedor principal del calendario */
    #calendar-container {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
        margin-top: 1.5rem;
        border: 1px solid var(--border);
    }

    #calendar {
        --fc-border-color: var(--border);
        --fc-today-bg-color: var(--primary-soft);
        --fc-event-bg-color: var(--primary);
        --fc-event-border-color: transparent;
        --fc-event-text-color: white;
        --fc-event-selected-overlay-color: rgba(0,0,0,0.1);
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: var(--light);
        --fc-list-event-hover-bg-color: var(--primary-soft);
    }

    .fc-header-toolbar {
        background: var(--light);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1.5rem !important;
        border: 1px solid var(--border);
    }

    .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
    }

    .fc-button {
        background: white !important;
        border: 1px solid var(--border) !important;
        color: var(--dark) !important;
        border-radius: var(--radius-sm) !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.875rem;
        font-weight: 500;
        transition: var(--transition);
        box-shadow: var(--shadow-sm) !important;
    }

    .fc-button:hover {
        background: var(--light) !important;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md) !important;
    }

    .fc-button-active {
        background: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
    }

    .fc-event {
        border: none !important;
        border-radius: var(--radius-sm) !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        cursor: pointer;
        margin-bottom: 0.25rem;
        border-left: 4px solid rgba(255,255,255,0.5) !important;
    }

    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .fc-event-title {
        font-weight: 600;
        white-space: normal;
    }

    .fc-event-crew {
        font-size: 0.7rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.15rem;
    }

    .fc-daygrid-day-number, .fc-col-header-cell-cushion {
        color: var(--dark);
        font-weight: 500;
        text-decoration: none;
    }

    /* Botón de configuración */
    .settings-toggle {
        background: var(--primary);
        color: white;
        border-radius: 9999px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        border: none;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .settings-toggle:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Enlace de retroceso (Back to Dashboard) */
    a.inline-flex {
        background: white;
        border: 1px solid var(--border);
        border-radius: 9999px;
        padding: 0.6rem 1.5rem;
        color: var(--dark);
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    a.inline-flex:hover {
        background: var(--light);
        border-color: var(--muted);
        transform: translateY(-1px);
    }

    /* Offcanvas */
    .offcanvas {
        border-left: 1px solid var(--border);
        width: 400px !important;
        box-shadow: var(--shadow-lg);
    }

    .offcanvas-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .offcanvas-title {
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .offcanvas-body {
        padding: 1.5rem;
    }

    /* Lista de compañías */
    .company-item {
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        margin-bottom: 0.75rem;
        padding: 0.75rem 1rem;
        background: white;
        transition: var(--transition);
    }

    .company-item:hover {
        background: var(--light);
        border-color: var(--primary);
    }

    .form-switch .form-check-input {
        width: 2.5rem;
        height: 1.25rem;
        cursor: pointer;
        background-color: var(--border);
        border-color: var(--border);
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .color-picker {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: var(--shadow-sm);
        cursor: pointer;
        transition: var(--transition);
    }

    .color-picker:hover {
        transform: scale(1.1);
        box-shadow: var(--shadow-md);
    }

    /* Modal principal */
    .modal-xl .modal-content {
        border-radius: var(--radius-lg);
        border: none;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-header.bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Tarjetas internas del modal */
    .card {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        background: var(--card-bg);
    }

    .card:hover {
        box-shadow: var(--shadow-md);
    }

    .card-header {
        background: var(--light);
        border-bottom: 1px solid var(--border);
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Sección de archivos adjuntos */
    .attachments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .attachment-card {
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        background: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: var(--transition);
    }

    .attachment-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }

    .attachment-icon {
        width: 2.5rem;
        height: 2.5rem;
        background: var(--light);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        flex-shrink: 0;
    }

    .attachment-info {
        flex: 1;
        min-width: 0;
    }

    .attachment-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }

    .attachment-meta {
        font-size: 0.7rem;
        color: var(--muted);
    }

    .attachment-actions {
        display: flex;
        gap: 0.25rem;
        opacity: 0;
        transition: var(--transition);
    }

    .attachment-card:hover .attachment-actions {
        opacity: 1;
    }

    .attachment-actions .btn-sm {
        padding: 0.15rem 0.4rem;
        font-size: 0.7rem;
        border-radius: var(--radius-sm);
    }

    /* Notas */
    .notes-container {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--light);
        padding: 0.75rem;
    }

    .notes-container::-webkit-scrollbar {
        width: 6px;
    }

    .notes-container::-webkit-scrollbar-track {
        background: var(--border);
        border-radius: 10px;
    }

    .notes-container::-webkit-scrollbar-thumb {
        background: var(--muted);
        border-radius: 10px;
    }

    .note-item {
        background: white;
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 4px solid var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .note-item:last-child {
        margin-bottom: 0;
    }

    .note-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }

    .note-author {
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--dark);
    }

    .note-time {
        font-size: 0.7rem;
        color: var(--muted);
    }

    .note-content {
        font-size: 0.8rem;
        line-height: 1.4;
        margin: 0;
        word-break: break-word;
    }

    .empty-notes {
        text-align: center;
        padding: 2rem;
        color: var(--muted);
        font-size: 0.9rem;
    }

    .note-input-container textarea {
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        font-size: 0.85rem;
        resize: vertical;
    }

    /* Crew assignment */
    #select-crew {
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        font-size: 0.9rem;
    }

    #btn-assign {
        background: var(--success);
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: var(--transition);
    }

    #btn-assign:hover {
        background: #0d9488;
        transform: translateY(-1px);
    }

    .assigned-crew {
        font-size: 0.85rem;
        color: var(--dark);
        background: var(--light);
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        margin-top: 1rem;
    }

    /* Footer del modal */
    .modal-footer {
        border-top: 1px solid var(--border);
        padding: 1rem 1.5rem;
        background: var(--light);
    }

    .modal-footer .btn {
        border-radius: var(--radius-sm);
        font-weight: 500;
        padding: 0.5rem 1.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .fc-header-toolbar {
            flex-direction: column;
            gap: 0.5rem;
        }

        .settings-toggle {
            width: 100%;
            justify-content: center;
        }

        a.inline-flex {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .offcanvas {
            width: 100% !important;
        }

        .attachments-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        }
    }

    /* Utilidades adicionales */
    .text-white-75 {
        color: rgba(255,255,255,0.75);
    }

    .bg-soft-primary {
        background-color: var(--primary-soft);
    }

    .fs-8 {
        font-size: 0.7rem;
    }
</style>

@section('content')
<div class="container-fluid py-4">
    <!-- Header mejorado -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary text-white rounded-circle p-3 me-3" style="background: var(--primary); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-alt fa-lg"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0" style="color: var(--dark);">FareFinder Calendar</h2>
                    <p class="text-muted mb-0">Visualize and manage all jobs and emergencies in one place</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 text-md-end">
            <button class="btn settings-toggle" data-bs-toggle="offcanvas" data-bs-target="#companiesCanvas">
                <i class="fas fa-palette me-2"></i> Customize View
            </button>
            <a href="{{ route('superadmin.users.index') }}" class="inline-flex ms-2">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Contenedor del calendario -->
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Offcanvas para configuración (sin cambios en la estructura, solo clases si es necesario) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="companiesCanvas" aria-labelledby="companiesCanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="companiesCanvasLabel">
                <i class="fas fa-sliders-h me-2"></i>Calendar Customization
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="alert alert-light border mb-4">
                <i class="fas fa-info-circle me-2"></i> Customize company visibility and colors for better visualization.
            </div>
            <h6 class="fw-bold mb-3 text-uppercase small text-muted">Company Settings</h6>
            <ul id="companies-list" class="list-group list-group-flush">
            @forelse($companies as $c)
                <li class="list-group-item company-item py-3 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="form-check form-switch me-3">
                                <input type="checkbox" class="form-check-input company-toggle" role="switch" id="toggle-{{ Str::slug($c['name']) }}" data-name="{{ $c['name'] }}" {{ ($c['active'] ?? true) ? 'checked' : '' }}>
                            </div>
                            <label class="form-check-label fw-semibold" for="toggle-{{ Str::slug($c['name']) }}">{{ $c['name'] }}</label>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="color" class="form-control form-control-color company-color d-none" data-name="{{ $c['name'] }}" value="{{ $c['color'] }}" id="color-{{ Str::slug($c['name']) }}">
                            <label for="color-{{ Str::slug($c['name']) }}" class="color-picker" style="background-color: {{ $c['color'] }}" title="Change color"></label>
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

    <!-- Modal detalle/asignación (estructura sin cambios, solo clases si hace falta) -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-white text-primary me-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:42px;height:42px;">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="eventModalLabel">Event Details</h5>
                            <div class="d-flex align-items-center mt-1 gap-2 flex-wrap">
                                <span class="badge bg-white text-dark" id="event-type-badge">—</span>
                                <small class="text-white-75" id="event-date-display">—</small>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="container-fluid">
                        <div class="row g-0">
                            <!-- Columna izquierda (8) -->
                            <div class="col-lg-8 p-4 border-end">
                                <!-- General Information -->
                                <div class="card mb-4" id="card-general-info">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">General Information</h6>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="eventActions" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="eventActions">
                                                <li><a class="dropdown-item" href="#" data-action="edit-event"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" data-action="delete-event"><i class="fas fa-trash-alt me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body" id="event-info" data-slot="event-info">
                                        <!-- Contenido dinámico -->
                                    </div>
                                </div>

                                <!-- Attachments -->
                                <div class="card" id="card-attachments">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-paperclip text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">Attachments</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="attachments-grid" id="attachments-list" data-slot="attachments-list">
                                            <!-- Contenido dinámico -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha (4) -->
                            <div class="col-lg-4 p-4">
                                <!-- Notes -->
                                <div class="card mb-4" id="card-notes">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-comments text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">Operation Notes</h6>
                                        </div>
                                        <span class="badge bg-secondary rounded-pill" id="notes-count">0</span>
                                    </div>
                                    <div class="card-body">
                                        <div id="notes-list" class="notes-container">
                                            <!-- Contenido dinámico -->
                                        </div>
                                        <div class="note-input-container">
                                            <label for="note-content" class="form-label small text-muted">Add a note</label>
                                            <textarea id="note-content" class="form-control mb-2" rows="2" placeholder="Write a note…"></textarea>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Press Enter to send</small>
                                                <button id="btn-note" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-paper-plane me-1"></i> Send
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Crew Assignment -->
                                <div class="card" id="card-crew">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-users text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">Crew Assignment</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="select-crew" class="form-label small text-muted">Select Crew Member</label>
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
                                        <div class="assigned-crew mt-3" id="current-assignment">
                                            <small class="text-muted">No crew assigned</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-action="open-history">
                        <i class="fas fa-history me-1"></i> History
                    </button>
                    <button type="button" class="btn btn-primary" data-action="save-changes">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para iframe (deep link) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="eventOffcanvas" aria-labelledby="eventOffcanvasLabel" style="width: 980px; max-width: 100%;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="eventOffcanvasLabel">Detalle</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body p-0">
            <iframe id="eventDetailFrame" src="about:blank" style="border:0; width:100%; height: calc(100vh - 80px);"></iframe>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ====== OFFCANVAS + IFRAME (para deep-link desde Fotos) ======
        if (!document.getElementById('eventOffcanvas')) {
            const tpl = `
            <div class="offcanvas offcanvas-end" tabindex="-1" id="eventOffcanvas" aria-labelledby="eventOffcanvasLabel" style="width: 980px; max-width: 100%;">
                <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="eventOffcanvasLabel">Detalle</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
                </div>
                <div class="offcanvas-body p-0">
                <iframe id="eventDetailFrame" src="about:blank" style="border:0; width:100%; height: calc(100vh - 80px);"></iframe>
                </div>
            </div>`;
            document.body.insertAdjacentHTML('beforeend', tpl);
        }
        function openInOffcanvas(title, url) {
            try {
                document.getElementById('eventOffcanvasLabel').textContent = title || 'Detalle';
                document.getElementById('eventDetailFrame').src = url;
                new bootstrap.Offcanvas('#eventOffcanvas').show();
            } catch (e) {
                console.error('Bootstrap Offcanvas no disponible:', e);
                window.location.href = url; // fallback
            }
        }

        // Builders para rutas show (usando route() si existe; fallback a url())
        const jobShowBase = @json(\Illuminate\Support\Facades\Route::has('superadmin.job_requests.show')
            ? route('superadmin.job_requests.show', ['job_request' => 'JOB_ID'])
            : url('/superadmin/job_requests/JOB_ID')
        );
        const emergShowBase = @json(\Illuminate\Support\Facades\Route::has('superadmin.emergencies.show')
            ? route('superadmin.emergencies.show', ['emergency' => 'EMERG_ID'])
            : url('/superadmin/emergencies/EMERG_ID')
        );
        function buildDetailUrl(type, id) {
            return (type === 'job')
                ? jobShowBase.replace('JOB_ID', encodeURIComponent(id))
                : emergShowBase.replace('EMERG_ID', encodeURIComponent(id));
        }

        // ====== TUS REFERENCIAS DE UI (para el modal actual) ======
        const calendarEl     = document.getElementById('calendar');
        const modalEl        = document.getElementById('eventModal');
        const eventInfo      = document.getElementById('event-info');
        const selectCrew     = document.getElementById('select-crew');
        const btnAssign      = document.getElementById('btn-assign');
        const notesList      = document.getElementById('notes-list');
        const noteContent    = document.getElementById('note-content');
        const btnNote        = document.getElementById('btn-note');
        const eventTypeBadge = document.getElementById('event-type-badge');
        const attachmentsList= document.getElementById('attachments-list');
        const notesCount     = document.getElementById('notes-count');

        let currentEvent = { type: null, id: null };
        let abortController = new AbortController();

        // ====== HELPERS ======
        const Helpers = {
            fileExt(u = '') {
                try {
                    const x = new URL(u, location.origin);
                    const n = (x.pathname.split('/').pop() || '').toLowerCase().split('?')[0].split('#')[0];
                    const p = n.split('.');
                    return p.length > 1 ? p.pop() : '';
                } catch { return ''; }
            },
            isImg(ext) { return ['jpg','jpeg','png','webp','gif','bmp','svg','heic','heif'].includes(ext); },
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
                return isNaN(date) ? '' : date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            },
            validateColor(color) {
                if (!color) return '#4361ee';
                if (!color.startsWith('#')) color = '#' + color;
                return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color) ? color : '#4361ee';
            }
        };

        // ====== RENDER ======
        const Render = {
            notes(notes) {
                if (!notes || !notes.length) {
                    notesList.innerHTML = `
                        <div class="empty-notes">
                            <i class="fas fa-comment-dots mb-2" style="font-size: 1.5rem;"></i>
                            <p class="small">No notes yet</p>
                        </div>`;
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
                        <p class="note-content">${n.content ?? ''}</p>`;
                    fragment.appendChild(div);
                });
                notesList.innerHTML = '';
                notesList.appendChild(fragment);
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
                    <div class="compact-attachments-grid"></div>`;
                const grid = group.querySelector('.compact-attachments-grid');
                const fragment = document.createDocumentFragment();
                items.forEach((url) => {
                    const ext = Helpers.fileExt(url);
                    const fileName = url.split('/').pop();
                    const isImage = Helpers.isImg(ext);
                    const item = document.createElement('div');
                    item.className = 'compact-attachment-item';
                    item.innerHTML = `
                        <div class="d-flex align-items-center p-2 border rounded bg-white">
                            <div class="file-icon me-2">
                                ${isImage ? '<i class="fas fa-file-image text-primary"></i>'
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
                        </div>`;
                    fragment.appendChild(item);
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
                    </div>`;
            }
        };

        // ====== API ======
        const API = {
            async fetchWithTimeout(url, options = {}, timeout = 10000) {
                const controller = new AbortController();
                const id = setTimeout(() => controller.abort(), timeout);
                try {
                    const response = await fetch(url, { ...options, signal: controller.signal });
                    clearTimeout(id);
                    return response;
                } catch (error) { clearTimeout(id); throw error; }
            },
            async getEventDetails(type, id) {
                try {
                    abortController.abort();
                    abortController = new AbortController();
                    const response = await this.fetchWithTimeout(
                        `{{ url('superadmin/calendar/event') }}/${type}/${id}`,
                        { signal: abortController.signal }
                    );
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return await response.json();
                } catch (error) {
                    if (error.name !== 'AbortError') throw error;
                }
            },
            async assignCrew(data) {
                const r = await fetch('{{ route("superadmin.calendar.assign") }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
                return r.json();
            },
            async storeNote(data) {
                const r = await fetch('{{ route("superadmin.calendar.storeNote") }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
                return r.json();
            },
            async updateCompanyColor(data) {
                data.color = Helpers.validateColor(data.color);
                return fetch('{{ route("superadmin.calendar.company.updateColor") }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
            },
            async updateCompanyVisibility(data) {
                return fetch('{{ route("superadmin.calendar.company.updateVisibility") }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
            }
        };

        // ====== FULLCALENDAR (UNA SOLA INSTANCIA) ======
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'en',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
            events: {
                url: '{{ route("superadmin.calendar.events") }}',
                method: 'GET',
                failure: function(){ console.error('Error loading events'); }
            },
            eventDisplay: 'block',
            eventTimeFormat: { hour: '2-digit', minute:'2-digit', meridiem: false, hour12: false },
            dayMaxEvents: 3,
            eventOrder: 'start,-duration,title',

            eventContent: function(arg) {
                const event = arg.event;
                const crewName = event.extendedProps.crewName;
                const eventDiv  = document.createElement('div');
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

            eventDidMount: function(info) {
                const event = info.event;
                if (event.extendedProps.crewName) {
                    info.el.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
                    info.el.style.borderLeft = '4px solid var(--primary-color)';
                }
                if (event.title.length > 30) info.el.title = event.title;
                if (event.extendedProps.type === 'emergency') {
                    info.el.style.borderLeft = '4px solid var(--danger-color)';
                }
            },

            // CLICK EN EVENTO → tu flujo con MODAL (se mantiene)
            eventClick: async function(info) {
                const { id, extendedProps } = info.event;
                currentEvent = { id, type: extendedProps.type };

                eventTypeBadge.textContent = extendedProps.type === 'job' ? 'JOB' : 'EMERGENCY';
                eventTypeBadge.className = extendedProps.type === 'job' ? 'badge bg-success' : 'badge bg-danger';

                eventInfo.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading event details...</p></div>';
                attachmentsList.innerHTML = '';
                notesList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"></div></div>';

                try {
                    const json = await API.getEventDetails(extendedProps.type, id);
                    if (!json) return;
                    const d = json.data;
                    let html = '';
                    let totalAttachments = 0;
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
                                <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> <a href="mailto:${d.company_rep_email}">${d.company_rep_email}</a></p>
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
                        </div>`;
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
                                <p><strong><i class="fas fa-envelope me-2"></i>Contact:</strong> <a href="mailto:${d.company_contact_email}">${d.company_contact_email}</a></p>
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
                        </div>`;
                        totalAttachments += Render.addAttachmentGroup('Aerial Images', d.aerial_measurement_path, attachmentsList);
                        totalAttachments += Render.addAttachmentGroup('Contract Files', d.contract_upload_path, attachmentsList);
                        totalAttachments += Render.addAttachmentGroup('Picture Uploads', d.file_picture_upload_path, attachmentsList);
                    }

                    if (totalAttachments === 0) Render.emptyAttachments();

                    eventInfo.innerHTML = html;
                    Render.notes(d.notes || []);
                    noteContent.value = '';
                    selectCrew.value = d.crew_id || '';

                    new bootstrap.Modal(modalEl).show();

                } catch (error) {
                    console.error('Error loading event details:', error);
                    eventInfo.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading event details. Please try again.
                        </div>`;
                }
            }
        });

        calendar.render();

        // ====== AUTO-ABRIR DESDE FOTOS: ?type=job|emergency&id=123 ======
        const params = new URLSearchParams(location.search);
        const pType  = params.get('type');
        const pId    = params.get('id');
        if ((pType === 'job' || pType === 'emergency') && pId) {
            const url   = buildDetailUrl(pType, pId);
            const title = (pType === 'job' ? 'Job #' : 'Emergency #') + pId;
            openInOffcanvas(title, url);
        }

        // ====== HANDLERS: asignar crew / notas (igual que tenías) ======
        btnAssign.addEventListener('click', async () => {
            if (!currentEvent.id || !selectCrew.value) return;
            const originalHtml = btnAssign.innerHTML;
            btnAssign.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
            btnAssign.disabled = true;
            try {
                const resp = await API.assignCrew({ type: currentEvent.type, id: currentEvent.id, crew_id: selectCrew.value });
                if (resp.success) { calendar.refetchEvents(); bootstrap.Modal.getInstance(modalEl).hide(); }
            } catch (e) {
                console.error('Assignment error:', e);
                alert('Error saving assignment. Please try again.');
            } finally {
                btnAssign.innerHTML = originalHtml;
                btnAssign.disabled  = false;
            }
        });

        btnNote.addEventListener('click', async () => {
            const content = noteContent.value.trim();
            if (!content || !currentEvent.id) return;
            const originalHtml = btnNote.innerHTML;
            btnNote.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btnNote.disabled = true;
            try {
                const n = await API.storeNote({ type: currentEvent.type, id: currentEvent.id, content });
                const div = document.createElement('div');
                div.className = 'note-item';
                div.innerHTML = `
                    <div class="note-header">
                        <span class="note-author">${n.user_name ?? 'System'}</span>
                        <span class="note-time">${Helpers.formatNoteDate(n.created_at)}</span>
                    </div>
                    <p class="note-content">${n.content ?? ''}</p>`;
                notesList.prepend(div);
                const currentCount = parseInt(notesCount.textContent) || 0;
                notesCount.textContent = (currentCount + 1) + ' note' + (currentCount + 1 !== 1 ? 's' : '');
                const emptyState = notesList.querySelector('.empty-notes');
                if (emptyState) emptyState.remove();
                noteContent.value = '';
                notesList.scrollTop = 0;
            } catch (e) {
                console.error('Note save error:', e);
                alert('Error saving note. Please try again.');
            } finally {
                btnNote.innerHTML = originalHtml;
                btnNote.disabled  = false;
            }
        });

        noteContent.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); btnNote.click(); }
        });

        // Colores / visibilidad compañías (igual que tenías)
        document.querySelectorAll('.company-color').forEach(input => {
            input.addEventListener('change', async function() {
                const name = this.dataset.name;
                try {
                    const validColor = Helpers.validateColor(this.value);
                    const lbl = document.querySelector(`label[for="color-${name.toLowerCase().replace(/ /g, '-')}"]`);
                    if (lbl) lbl.style.backgroundColor = validColor;
                    await API.updateCompanyColor({ name, color: validColor });
                    calendar.refetchEvents();
                } catch (e) { console.error('Color update error:', e); }
            });
        });
        document.querySelectorAll('.form-check-input').forEach(input => {
            if (input.dataset.name) {
                input.addEventListener('change', async function() {
                    const name = this.dataset.name;
                    try {
                        await API.updateCompanyVisibility({ name, active: this.checked });
                        calendar.refetchEvents();
                    } catch (e) {
                        console.error('Visibility update error:', e);
                        this.checked = !this.checked;
                    }
                });
            }
        });
        const companiesList = document.getElementById('companies-list');
        if (companiesList) {
            companiesList.addEventListener('click', (e) => {
                if (e.target.matches('.color-picker')) {
                    const colorInput = document.getElementById(e.target.getAttribute('for'));
                    if (colorInput) colorInput.click();
                }
            });
        }
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => calendar.updateSize(), 250);
        });
    });
</script>

