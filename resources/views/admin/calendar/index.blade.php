
@extends('admin.layouts.superadmin')


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    #calendar { max-width: 1000px; margin: 2rem auto; }
    .fc-event { cursor: pointer; }
</style>


@section('content')

    <div class="container-fluid py-4">

        <div class="row mb-4">
            <div class="col text-center">
                <h2 class="fw-bold">FareFinder</h2>
            </div>
        </div>

        <div id="calendar"></div>

        {{-- Botón offcanvas colores --}}
        <button class="btn btn-outline-secondary mt-4" data-bs-toggle="offcanvas" data-bs-target="#companiesCanvas">
            🎨 Colours by Company
        </button>

        {{-- Offcanvas para configuración de colores --}}
        <div class="offcanvas offcanvas-end" tabindex="-1" id="companiesCanvas" aria-labelledby="companiesCanvasLabel">
            <div class="offcanvas-header">
            <h5 id="companiesCanvasLabel">Set Colors</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul id="companies-list" class="list-group">
                    @forelse($companies as $c)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $c['name'] }}</span>
                        <input type="color" value="{{ $c['color'] }}" data-name="{{ $c['name'] }}" class="form-control form-control-color" style="width:3rem">
                        </li>
                    @empty
                        <li class="list-group-item">No companies to set up.</li>
                    @endforelse
                </ul>

                <small class="text-muted d-block mt-2">Your changes are saved automatically</small>
            </div>
        </div>
    
    </div>

    
    {{-- Modal detalle/asignación --}}
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
    
            {{-- Body --}}
            <div class="modal-body">
            <div class="container-fluid">
                <div class="row gy-4">
    
                {{-- IZQUIERDA: Info dinámica + attachments --}}
                <div class="col-lg-7">
                    {{-- Información general (Job o Emergency) --}}
                    <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <strong>General Information</strong>
                    </div>
                    <div class="card-body" id="event-info">
                        {{-- Aquí inyecta tu JS la plantilla según type --}}
                    </div>
                    </div>
    
                    {{-- Attachments (si aplica) --}}
                    <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Attachments</strong>
                    </div>
                    <div class="card-body" id="attachments-list">
                        {{-- Enlaces de archivos inyectados por JS --}}
                    </div>
                    </div>
                </div>
    
                {{-- DERECHA: notas + asignar crew --}}
                <div class="col-lg-5">
                    {{-- Operación Notes --}}
                    <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <strong>Operation Notes</strong>
                    </div>
                    <div class="card-body">
                        <div id="notes-list" class="mb-3" style="max-height:200px; overflow-y:auto;"></div>
                        <div class="d-flex">
                        <textarea id="note-content" class="form-control me-2" rows="2" placeholder="Write a note..."></textarea>
                        <button id="btn-note" class="btn btn-secondary">Add</button>
                        </div>
                    </div>
                    </div>
    
                    {{-- Assign Crew --}}
                    <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Assign Crew</strong>
                    </div>
                    <div class="card-body">
                        <select id="select-crew" class="form-select mb-3">
                        <option value="">-- Select a crew --</option>
                        @foreach($crews as $crew)
                            <option value="{{ $crew->id }}">{{ $crew->name }} ({{ $crew->company }})</option>
                        @endforeach
                        </select>
                        <button id="btn-assign" class="btn btn-success w-100">Save Assignment</button>
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
            const savedColors = @json($companyColorMap ?? []);
            const calendarEl = document.getElementById('calendar');
            const modalEl    = document.getElementById('eventModal');
            const eventInfo  = document.getElementById('event-info');
            const selectCrew = document.getElementById('select-crew');
            const btnAssign  = document.getElementById('btn-assign');
            const notesList   = document.getElementById('notes-list');
            const noteContent = document.getElementById('note-content');
            const btnNote     = document.getElementById('btn-note');
            let currentEvent = { type: null, id: null };


            // Función para renderizar notas en el modal
            function renderNotes(notes) {
                notesList.innerHTML = '';
                notes.forEach(n => {
                    const div = document.createElement('div');
                    div.className = 'border rounded p-2 mb-1';
                    div.innerHTML = `
                        <small><strong>${n.user_name}</strong> <em>${n.created_at}</em></small>
                        <p class="mb-0">${n.content}</p>
                    `;
                    notesList.append(div);
                });
            }
        
            // Inicialización de FullCalendar
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'en',
                headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: [
        'dayGridMonth',  // vista mes

        'listMonth',     // lista de eventos del mes
        
      ].join(',')
    },

    
                events: '{{ route("superadmin.calendar.events") }}',

                // 1️⃣ Mostrar título + nombre de crew
                eventContent(arg) {
                    let html = `<div class="fc-event-title">${arg.event.title}</div>`;
                    if (arg.event.extendedProps.crewName) {
                        html += `<div class="fc-event-crew text-truncate" style="font-size:.8em;">
                                    👷 ${arg.event.extendedProps.crewName}
                                </div>`;
                    }
                    return { html };
                },
        
                // 2️⃣ Borde verde si ya hay crew asignada
                eventDidMount: function(info) {
                    if (info.event.extendedProps.crewName) {
                        info.el.style.border = '2px solid #23232a';
                    }
                },
        
                eventClick(info) {
                const { id, extendedProps } = info.event;
                currentEvent = { id, type: extendedProps.type };

                // limpiamos contenedores
                document.getElementById('event-info').innerHTML = '';
                document.getElementById('attachments-list').innerHTML = '';

                fetch(`{{ url('superadmin/calendar/event') }}/${extendedProps.type}/${id}`)
                    .then(res => res.json())
                    .then(json => {
                    const d = json.data;
                    let html = '';

                    if (extendedProps.type === 'job') {
                        // —— JOBRequest layout ——
                        html += `
                        <h6 class="text-uppercase">General Information</h6>
                        <p><strong>Requested On:</strong> ${new Date(d.install_date_requested).toLocaleDateString()}</p>
                        <p><strong>Company:</strong> ${d.company_name}</p>
                        <p><strong>Rep:</strong> ${d.company_rep} — ${d.company_rep_phone} — 
                            <a href="mailto:${d.company_rep_email}">${d.company_rep_email}</a>
                        </p>
                        <hr>
                        <h6 class="text-uppercase mt-3">Customer</h6>
                        <p>${d.customer_first_name} ${d.customer_last_name || ''} — ${d.customer_phone_number}</p>
                        <hr>
                        <h6 class="text-uppercase mt-3">Job Address</h6>
                        <p>
                            ${d.job_address_street_address}
                            ${d.job_address_street_address_line_2 ? '– ' + d.job_address_street_address_line_2 : ''}
                            , ${d.job_address_city}, ${d.job_address_state} ${d.job_address_zip_code}
                        </p>
                        <hr>
                        <h6 class="text-uppercase mt-3">Materials & Delivery</h6>
                        <ul class="ps-3">
                            <li>Roof Loaded: ${d.material_roof_loaded}</li>
                            <li>Starter Bundles: ${d.starter_bundles_ordered}</li>
                            <li>Hip & Ridge: ${d.hip_and_ridge_ordered}</li>
                            <li>Field Shingles: ${d.field_shingle_bundles_ordered}</li>
                            <li>Cap Rolls: ${d.modified_bitumen_cap_rolls_ordered}</li>
                            <li>Delivery Date: ${d.delivery_date ? new Date(d.delivery_date).toLocaleDateString() : '—'}</li>
                        </ul>
                        `;

                        // archivos (ajusta si quieres todos los enlaces)
                        if (d.aerial_measurement?.length) {
                        document.getElementById('attachments-list').innerHTML +=
                            `<p><strong>Aerial Measurements:</strong> <a href="${d.aerial_measurement[0]}" target="_blank">View</a></p>`;
                        }
                        if (d.material_order?.length) {
                        document.getElementById('attachments-list').innerHTML +=
                            `<p><strong>Material Order:</strong> <a href="${d.material_order[0]}" target="_blank">View</a></p>`;
                        }
                    } else {
                        // —— Emergencies layout ——
                        html += `
                        <h6 class="text-uppercase">Emergency Details</h6>
                        <p><strong>Date Submitted:</strong> ${new Date(d.date_submitted).toLocaleDateString()}</p>
                        <p><strong>Supplement Type:</strong> ${d.type_of_supplement}</p>
                        <p><strong>Company:</strong> ${d.company_name} — 
                            <a href="mailto:${d.company_contact_email}">${d.company_contact_email}</a>
                        </p>
                        <hr>
                        <h6 class="text-uppercase mt-3">Location & Terms</h6>
                        <p>
                            ${d.job_address}
                            ${d.job_address_line2 ? '– ' + d.job_address_line2 : ''}
                            , ${d.job_city}, ${d.job_state} ${d.job_zip_code}
                        </p>
                        <p><strong>Terms Accepted:</strong> ${d.terms_conditions ? 'Yes' : 'No'}</p>
                        <p><strong>Requirements:</strong> ${d.requirements ? 'Met' : 'Pending'}</p>
                        `;
                        if (d.aerial_measurement_path?.length) {
                        document.getElementById('attachments-list').innerHTML +=
                            `<p><strong>Aerial Images:</strong> <a href="${d.aerial_measurement_path[0]}" target="_blank">View</a></p>`;
                        }
                    }

                // siempre inyectamos
                document.getElementById('event-info').innerHTML = html;
                renderNotes(d.notes || []);
                noteContent.value = '';
                selectCrew.value = d.crew_id || '';
                new bootstrap.Modal(modalEl).show();
                });
                
            }


            });

            calendar.render();
        
            // Asignar crew
            btnAssign.addEventListener('click', () => {
                if (!currentEvent.id || !selectCrew.value) return;
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
                });
            });


            // 5️⃣ Agregar nota
            btnNote.addEventListener('click', () => {
                const content = noteContent.value.trim();
                if (!content || !currentEvent.id) return;
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
                    div.className = 'border rounded p-2 mb-1';
                    div.innerHTML = `
                        <small><strong>${n.user_name}</strong> <em>${n.created_at}</em></small>
                        <p class="mb-0">${n.content}</p>
                    `;
                    notesList.prepend(div);
                    noteContent.value = '';
                });
            });
                
            // Guardar colores
            document.getElementById('companies-list').addEventListener('change', e => {
                if (e.target.matches('input[type="color"]')) {
                    const name  = e.target.dataset.name;
                    const color = e.target.value;
                    fetch('{{ route("superadmin.calendar.company.updateColor") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type':'application/json',
                            'X-CSRF-TOKEN':'{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ name, color })
                    })
                    .then(() => calendar.refetchEvents());
                }
            });

        });
</script>
        
        

