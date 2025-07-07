{{-- resources/views/admin/calendar/index.blade.php --}}
@extends('admin.layouts.superadmin')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css" rel="stylesheet">


@section('content')
<div class="container py-4">
    <h1 class="mb-4">Calendario de Trabajos y Emergencias</h1>

    <!-- Filtro de cuadrillas -->
    <div class="mb-3">
        <label for="crewFilter" class="form-label">Filtrar por Equipo:</label>
        <select id="crewFilter" class="form-select" style="max-width: 300px;">
            <option value="">Todos</option>
            @foreach($crews as $crew)
                <option value="{{ $crew->id }}">{{ $crew->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Contenedor del calendario -->
    <div id="calendar"></div>
</div>

<!-- Modal para detalles -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Detalles del Evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <table class="table table-sm">
          <tbody id="eventDetailsBody">
            {{-- Se llenará dinámicamente --}}
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection

    <!-- Popper & Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.min.js"></script>
    <!-- FullCalendar UMD bundle -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl    = document.getElementById('calendar');
        const crewFilter    = document.getElementById('crewFilter');
        const storagePrefix = '{{ asset("storage") }}';

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: {
                url: '{{ route("superadmin.calendar.events") }}',
                extraParams: () => ({ crew_id: crewFilter.value })
            },
            eventClick: function(info) {
                const payload = info.event.extendedProps.payload;
                const tbody   = document.getElementById('eventDetailsBody');
                tbody.innerHTML = '';

                // Título
                const trTitle = document.createElement('tr');
                trTitle.innerHTML = `<th colspan="2" class="text-center">${info.event.title}</th>`;
                tbody.appendChild(trTitle);

                // Iterar propiedades
                Object.entries(payload).forEach(([key, value]) => {
                    let label = key.replace(/_/g, ' ');
                    let cell  = '';

                    if (Array.isArray(value)) {
                        // Array: rutas de archivos?
                        if (value.length) {
                            cell = value.map(item => {
                                // Aseguramos que item sea string
                                let path = typeof item === 'string'
                                    ? item
                                    : JSON.stringify(item);
                                let filename = path.split('/').pop();
                                let href = path.startsWith('http')
                                    ? path
                                    : `${storagePrefix}/${path}`;
                                return `<a href="${href}" target="_blank">${filename}</a>`;
                            }).join('<br>');
                        } else {
                            cell = '<em>—</em>';
                        }
                    } else {
                        // Valor simple
                        cell = (value === null || value === '')
                            ? '<em>—</em>'
                            : value;
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `<th>${label}</th><td>${cell}</td>`;
                    tbody.appendChild(row);
                });

                new bootstrap.Modal(document.getElementById('eventModal')).show();
            }
        });

        calendar.render();
        crewFilter.addEventListener('change', () => calendar.refetchEvents());
    });
    </script>

