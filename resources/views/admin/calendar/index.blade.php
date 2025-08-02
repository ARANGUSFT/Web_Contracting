@extends('admin.layouts.superadmin')


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4cc9f0;
        --light-bg: #f8f9fa;
        --dark-text: #212529;
    }
    
    #calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-top: 1.5rem;
    }
    
    #calendar {
        margin: 0 auto;
    }
    
    .fc-header-toolbar {
        margin-bottom: 1.5rem !important;
    }
    
    .fc-toolbar-title {
        font-weight: 600;
        color: var(--dark-text);
    }
    
    .fc-button {
        background: white !important;
        border: 1px solid #dee2e6 !important;
        color: var(--dark-text) !important;
        transition: all 0.2s;
    }
    
    .fc-button:hover {
        background: var(--light-bg) !important;
    }
    
    .fc-button-active {
        background: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }
    
    .fc-event {
        border: none !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .fc-event:hover {
        transform: translateY(-2px);
    }
    
    .fc-event-title {
        font-weight: 500;
    }
    
    .fc-event-crew {
        font-size: 0.75rem;
        opacity: 0.9;
    }
    
    .settings-toggle {
        background: var(--primary-color);
        color: white;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .settings-toggle:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }
    
    /* Modal mejorado */
    .modal-xl .modal-content {
        border-radius: 12px;
        border: none;
    }
    
    .modal-header {
        background: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0 !important;
    }
    
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .card-header {
        background: var(--light-bg);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 600;
    }
    
    /* Offcanvas mejorado */
    .offcanvas {
        border-left: 1px solid rgba(0,0,0,0.05);
    }
    
    .company-item {
        transition: all 0.2s;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
    }
    
    .company-item:hover {
        background: var(--light-bg);
    }
    
    .color-picker {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        cursor: pointer;
    }
    
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
        cursor: pointer;
    }
</style>


@section('content')
<div class="container-fluid py-4">
    <!-- Header mejorado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--primary-color);">
                <i class="fas fa-calendar-alt me-2"></i>FareFinder Calendar
            </h2>
            <p class="text-muted mb-0">Manage all your jobs and emergencies in one place</p>
        </div>
        
        <button class="btn settings-toggle" data-bs-toggle="offcanvas" data-bs-target="#companiesCanvas">
            <i class="fas fa-palette me-1"></i> Company Settings
        </button>
    </div>

    <!-- Contenedor del calendario -->
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Offcanvas para configuración - Mejorado -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="companiesCanvas" aria-labelledby="companiesCanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="companiesCanvasLabel">
                <i class="fas fa-cog me-2"></i>Company Settings
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <p class="text-muted mb-3">Customize company visibility and colors</p>
            
            <ul id="companies-list" class="list-group list-group-flush">
            @forelse($companies as $c)
                <li class="list-group-item company-item py-3 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <!-- Toggle switch mejorado -->
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
                        
                        <!-- Selector de color mejorado -->
                        <div class="d-flex align-items-center">
                            <input
                                type="color"
                                class="form-control form-control-color company-color d-none"
                                data-name="{{ $c['name'] }}"
                                value="{{ $c['color'] }}"
                                id="color-{{ Str::slug($c['name']) }}">
                            <label for="color-{{ Str::slug($c['name']) }}" 
                                   class="color-picker me-2" 
                                   style="background-color: {{ $c['color'] }}"></label>
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">No companies available</li>
            @endforelse
            </ul>
            
            <div class="mt-4 pt-2 border-top">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Changes are saved automatically</small>
            </div>
        </div>
    </div>

    <!-- Modal detalle/asignación - Mejorado -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- Header con icono -->
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">
                        <i class="fas fa-calendar-detail me-2"></i>Event Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
        
                <!-- Body reorganizado -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row gy-4">
                            <!-- Sección izquierda - Información principal -->
                            <div class="col-lg-7">
                                <!-- Tarjeta de información general -->
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <strong><i class="fas fa-info-circle me-2"></i>General Information</strong>
                                        <span class="badge bg-primary" id="event-type-badge"></span>
                                    </div>
                                    <div class="card-body" id="event-info">
                                        <!-- Información dinámica -->
                                    </div>
                                </div>
                                
                                <!-- Tarjeta de archivos adjuntos -->
                                <div class="card">
                                    <div class="card-header">
                                        <strong><i class="fas fa-paperclip me-2"></i>Attachments</strong>
                                    </div>
                                    <div class="card-body" id="attachments-list">
                                        <!-- Archivos dinámicos -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección derecha - Interacción -->
                            <div class="col-lg-5">
                                <!-- Tarjeta de notas -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <strong><i class="fas fa-comments me-2"></i>Operation Notes</strong>
                                    </div>
                                    <div class="card-body">
                                        <div id="notes-list" class="mb-3" style="max-height:200px; overflow-y:auto;"></div>
                                        <div class="d-flex gap-2">
                                            <textarea id="note-content" class="form-control flex-grow-1" rows="2" 
                                                      placeholder="Write a note..."></textarea>
                                            <button id="btn-note" class="btn btn-primary align-self-start">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tarjeta de asignación -->
                                <div class="card">
                                    <div class="card-header">
                                        <strong><i class="fas fa-users me-2"></i>Assign Crew</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select Crew Member</label>
                                            <select id="select-crew" class="form-select">
                                                <option value="">-- Select a crew --</option>
                                                @foreach($crews as $crew)
                                                    <option value="{{ $crew->id }}">{{ $crew->name }} ({{ $crew->company }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button id="btn-assign" class="btn btn-success w-100">
                                            <i class="fas fa-save me-2"></i>Save Assignment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    const calendarEl = document.getElementById('calendar');
    const modalEl = document.getElementById('eventModal');
    const eventInfo = document.getElementById('event-info');
    const selectCrew = document.getElementById('select-crew');
    const btnAssign = document.getElementById('btn-assign');
    const notesList = document.getElementById('notes-list');
    const noteContent = document.getElementById('note-content');
    const btnNote = document.getElementById('btn-note');
    const eventTypeBadge = document.getElementById('event-type-badge');
    let currentEvent = { type: null, id: null };

    // Inicialización de FullCalendar con configuración mejorada
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'en',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        events: '{{ route("superadmin.calendar.events") }}',
        eventDisplay: 'block',
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },
        
        // Renderizado de eventos mejorado
        eventContent(arg) {
            const crew = arg.event.extendedProps.crewName ? 
                `<div class="fc-event-crew"><i class="fas fa-user me-1"></i>${arg.event.extendedProps.crewName}</div>` : '';
            
            return {
                html: `
                    <div class="fc-event-main">
                        <div class="fc-event-title">${arg.event.title}</div>
                        ${crew}
                    </div>
                `
            };
        },
        
        // Estilo de eventos
        eventDidMount: function(info) {
            if (info.event.extendedProps.crewName) {
                info.el.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
                info.el.style.borderLeft = '4px solid var(--primary-color)';
            }
        },
        
        // Manejo de clic en evento
        eventClick(info) {
            const { id, extendedProps } = info.event;
            currentEvent = { id, type: extendedProps.type };
            
            // Actualizar badge en el modal
            eventTypeBadge.textContent = extendedProps.type === 'job' ? 'JOB' : 'EMERGENCY';
            eventTypeBadge.className = extendedProps.type === 'job' ? 
                'badge bg-success' : 'badge bg-danger';

            // Limpiar contenedores
            eventInfo.innerHTML = '';
            document.getElementById('attachments-list').innerHTML = '';

            // Obtener detalles del evento
            fetch(`{{ url('superadmin/calendar/event') }}/${extendedProps.type}/${id}`)
                .then(res => res.json())
                .then(json => {
                    const d = json.data;
                    let html = '';

                    if (extendedProps.type === 'job') {
                        // Plantilla para trabajos
                        html += `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="section-title">Request Details</h6>
                                <p><strong><i class="fas fa-calendar me-2"></i>Requested On:</strong> ${new Date(d.install_date_requested).toLocaleDateString()}</p>
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
                                            <td>${d.delivery_date ? new Date(d.delivery_date).toLocaleDateString() : '—'}</td>
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

                        // Archivos adjuntos
                        if (d.aerial_measurement?.length) {
                            document.getElementById('attachments-list').innerHTML +=
                                `<a href="${d.aerial_measurement[0]}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-image me-1"></i> Aerial Measurements
                                </a>`;
                        }
                        if (d.material_order?.length) {
                            document.getElementById('attachments-list').innerHTML +=
                                `<a href="${d.material_order[0]}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file-invoice me-1"></i> Material Order
                                </a>`;
                        }
                    } else {
                        // Plantilla para emergencias
                        html += `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="section-title">Emergency Details</h6>
                                <p><strong><i class="fas fa-calendar me-2"></i>Date Submitted:</strong> ${new Date(d.date_submitted).toLocaleDateString()}</p>
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
                        
                        if (d.aerial_measurement_path?.length) {
                            document.getElementById('attachments-list').innerHTML +=
                                `<a href="${d.aerial_measurement_path[0]}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-image me-1"></i> Aerial Images
                                </a>`;
                        }
                    }

                    // Inyectar HTML y mostrar modal
                    eventInfo.innerHTML = html;
                    renderNotes(d.notes || []);
                    noteContent.value = '';
                    selectCrew.value = d.crew_id || '';
                    
                    // Mostrar modal con animación
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                });
        }
    });

    calendar.render();
    
    // Función para renderizar notas
    function renderNotes(notes) {
        notesList.innerHTML = notes.length ? '' : '<p class="text-muted text-center">No notes yet</p>';
        
        notes.forEach(n => {
            const div = document.createElement('div');
            div.className = 'border rounded p-3 mb-2 bg-light';
            div.innerHTML = `
                <div class="d-flex justify-content-between mb-1">
                    <strong>${n.user_name}</strong>
                    <small class="text-muted">${new Date(n.created_at).toLocaleString()}</small>
                </div>
                <p class="mb-0">${n.content}</p>
            `;
            notesList.append(div);
        });
    }

    // Asignar crew
    btnAssign.addEventListener('click', () => {
        if (!currentEvent.id || !selectCrew.value) return;
        
        btnAssign.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
        btnAssign.disabled = true;
        
        fetch('{{ route("superadmin.calendar.assign") }}', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: currentEvent.type,
                id: currentEvent.id,
                crew_id: selectCrew.value
            })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                calendar.refetchEvents();
                bootstrap.Modal.getInstance(modalEl).hide();
            }
        })
        .finally(() => {
            btnAssign.innerHTML = '<i class="fas fa-save me-2"></i> Save Assignment';
            btnAssign.disabled = false;
        });
    });

    // Agregar nota
    btnNote.addEventListener('click', () => {
        const content = noteContent.value.trim();
        if (!content || !currentEvent.id) return;
        
        btnNote.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btnNote.disabled = true;
        
        fetch('{{ route("superadmin.calendar.storeNote") }}', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: currentEvent.type,
                id: currentEvent.id,
                content: content
            })
        })
        .then(res => res.json())
        .then(n => {
            // Prepend nueva nota
            const div = document.createElement('div');
            div.className = 'border rounded p-3 mb-2 bg-light';
            div.innerHTML = `
                <div class="d-flex justify-content-between mb-1">
                    <strong>${n.user_name}</strong>
                    <small class="text-muted">${new Date(n.created_at).toLocaleString()}</small>
                </div>
                <p class="mb-0">${n.content}</p>
            `;
            notesList.prepend(div);
            noteContent.value = '';
        })
        .finally(() => {
            btnNote.innerHTML = '<i class="fas fa-paper-plane"></i>';
            btnNote.disabled = false;
        });
    });
        
    // Manejar cambios en la configuración de compañías
    document.getElementById('companies-list').addEventListener('change', e => {
        const el = e.target;
        const name = el.dataset.name;

        // Cambio de color
        if (el.matches('input.company-color')) {
            // Actualizar el visualizador de color
            document.querySelector(`label[for="color-${Str.slug(name)}"]`).style.backgroundColor = el.value;
            
            fetch('{{ route("superadmin.calendar.company.updateColor") }}', {
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, color: el.value })
            })
            .then(() => calendar.refetchEvents());
        }

        // Cambio de visibilidad
        if (el.matches('input.company-toggle')) {
            fetch('{{ route("superadmin.calendar.company.updateVisibility") }}', {
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, active: el.checked })
            })
            .then(() => calendar.refetchEvents());
        }
    });

    // Delegación de eventos para los visualizadores de color
    document.getElementById('companies-list').addEventListener('click', e => {
        if (e.target.matches('.color-picker')) {
            const colorInput = document.getElementById(e.target.getAttribute('for'));
            colorInput.click();
        }
    });
});
</script>
