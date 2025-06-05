@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-calendar-event me-2"></i>My Assigned Schedule</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>
@endsection


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-sm">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="eventModalLabel">
            <i class="bi bi-info-circle me-2"></i>Event Details
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Title:</strong> <span id="modalEventTitle"></span></p>
          <p><strong>Date:</strong> <span id="modalEventDate"></span></p>
          <p><strong>Type:</strong> <span id="modalEventType"></span></p>
          <hr>
  
          <h6><i class="bi bi-building me-1"></i> Company Info</h6>
          <p><strong>Company:</strong> <span id="modalEventCompany"></span></p>
          <p><strong>Rep / Contact:</strong> <span id="modalEventRep"></span></p>
          <p><strong>Customer:</strong> <span id="modalEventCustomer"></span></p>
          <hr>
  
          <h6><i class="bi bi-geo-alt me-1"></i> Address</h6>
          <p><span id="modalEventAddress"></span></p>
          <hr>
  
          <h6><i class="bi bi-tools me-1"></i> Materials / Supplement</h6>
          <p id="modalEventMaterials" class="mb-2"></p>
          <hr>
  
          <h6><i class="bi bi-chat-left-text me-1"></i> Special Instructions</h6>
          <p id="modalEventInstructions" class="bg-light p-2 rounded"></p>
          <hr>
  
          <h6><i class="bi bi-people me-1"></i> Assigned Team</h6>
          <ul id="modalEventTeam" class="ps-3"></ul>
        </div>
        <div class="modal-footer bg-light">
     
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
  
  
  
  

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');

      const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          height: "auto",
          events: @json($events),
          eventClick: function (info) {
              const e = info.event;
              const props = e.extendedProps;

              // 🔁 Si el evento es "Lead Approval", redirige a su URL
              if (props.type === 'Lead Approval' && e.url) {
                  window.location.href = e.url;
                  info.jsEvent.preventDefault();
                  return;
              }

              // 🔍 Para los demás tipos, mostrar el modal
              document.getElementById('modalEventTitle').textContent = e.title;
              document.getElementById('modalEventDate').textContent = e.start.toLocaleDateString();
              document.getElementById('modalEventType').textContent = props.type ?? 'N/A';
              document.getElementById('modalEventCompany').textContent = props.company ?? 'N/A';
              document.getElementById('modalEventRep').textContent = props.rep ?? props.email ?? 'N/A';
              document.getElementById('modalEventCustomer').textContent = props.customer ?? 'N/A';
              document.getElementById('modalEventAddress').textContent = props.address ?? 'N/A';
              document.getElementById('modalEventInstructions').textContent = props.special_instructions ?? 'N/A';

              const materialText = props.materials
                  ? `Starter: ${props.materials.starter ?? 0}, Hip: ${props.materials.hip ?? 0}, Field: ${props.materials.field ?? 0}, Modified: ${props.materials.modified ?? 0}`
                  : (props.supplement ?? 'N/A');
              document.getElementById('modalEventMaterials').textContent = materialText;

              const teamList = document.getElementById('modalEventTeam');
              teamList.innerHTML = '';
              if (props.team && props.team.length) {
                  props.team.forEach(member => {
                      teamList.innerHTML += `<li>${member}</li>`;
                  });
              } else {
                  teamList.innerHTML = '<li class="text-muted">No team assigned</li>';
              }

              new bootstrap.Modal(document.getElementById('eventModal')).show();
              info.jsEvent.preventDefault();
          }
      });

      calendar.render();
  });
</script>

    

