@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom">
            <h4 class="mb-3 mb-md-0 text-primary"><i class="bi bi-calendar-week me-2"></i> Job & Emergency Calendar</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('jobs.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> New Job
                </a>
                <a href="{{ route('emergency.form') }}" class="btn btn-danger btn-sm">
                    <i class="bi bi-exclamation-triangle me-1"></i> New Emergency
                </a>
            </div>
            
        </div>
        <div class="card-body bg-light">
            <div id="calendar" class="bg-white p-3 rounded shadow-sm border"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-calendar-event me-2"></i> Events for <span id="modalDate"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white" id="modalContent"></div>
        </div>
    </div>
</div>
@endsection

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        let allEvents = [];
    
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch("{{ route('calendar.data') }}")
                    .then(res => res.json())
                    .then(data => {
                        const events = Array.isArray(data.events) ? data.events : [];
                        allEvents = events;
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error('Error loading events:', error);
                        failureCallback(error);
                    });
            },
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
            dateClick: function(info) {
                const clickedDate = info.dateStr;
                const eventsForDate = allEvents.filter(ev => ev.start.startsWith(clickedDate));
                let content = "";
    
                if (eventsForDate.length === 0) {
                    content = "<p class='text-muted'>No events for this date.</p>";
                } else {
                    content = "<ul class='list-group'>";
                    eventsForDate.forEach(ev => {
                        content += `<li class="list-group-item">
                            <strong>${ev.title}</strong><br>
                            <a href="${ev.url}" class="text-decoration-none text-primary">View details</a>
                        </li>`;
                    });
                    content += "</ul>";
                }
    
                document.getElementById("modalDate").innerText = clickedDate;
                document.getElementById("modalContent").innerHTML = content;
    
                const modal = new bootstrap.Modal(document.getElementById('calendarModal'));
                modal.show();
            },
            height: "auto"
        });
    
        calendar.render();
    
        // Si necesitas recargar desde otro lado: window.refreshCalendar()
        window.refreshCalendar = function() {
            calendar.refetchEvents();
        };
    });
    </script>
    
