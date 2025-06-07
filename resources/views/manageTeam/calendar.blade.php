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
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Event Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                <p id="modalEventMaterials"></p>
                <hr>
                <h6><i class="bi bi-chat-left-text me-1"></i> Special Instructions</h6>
                <p id="modalEventInstructions" class="bg-light p-2 rounded"></p>
                <hr>
                <h6><i class="bi bi-people me-1"></i> Assigned Team</h6>
                <ul id="modalEventTeam" class="ps-3"></ul>
                <hr>
                <h6><i class="bi bi-paperclip me-1"></i> Attached Files</h6>
                <div id="modalEventFiles" class="row px-2"></div>
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
  
              if (props.type === 'Lead Approval' && e.url) {
                  window.location.href = e.url;
                  info.jsEvent.preventDefault();
                  return;
              }
  
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
              if (props.team?.length) {
                  props.team.forEach(member => {
                      teamList.innerHTML += `<li>${member}</li>`;
                  });
              } else {
                  teamList.innerHTML = '<li class="text-muted">No team assigned</li>';
              }
  
              const filesList = document.getElementById('modalEventFiles');
              filesList.innerHTML = '';
  
              const fileGroups = {
                  'Aerial Measurements': [],
                  'Material Orders': [],
                  'Pictures': []
              };
  
              (props.files ?? []).forEach(filePath => {
                  const path = filePath.toLowerCase();
                  if (path.includes('aerials')) {
                      fileGroups['Aerial Measurements'].push(filePath);
                  } else if (path.includes('materials')) {
                      fileGroups['Material Orders'].push(filePath);
                  } else {
                      fileGroups['Pictures'].push(filePath);
                  }
              });
  
              Object.entries(fileGroups).forEach(([label, files]) => {
                  if (!files.length) return;
  
                  filesList.innerHTML += `<div class="col-12 mb-2"><strong>${label}</strong></div>`;
  
                  files.forEach(filePath => {
                      const fileName = filePath.split('/').pop();
                      const ext = fileName.split('.').pop().toLowerCase();
                      const isImage = ['jpg', 'jpeg', 'png'].includes(ext);
  
                      filesList.innerHTML += `
                          <div class="col-md-4 col-lg-3 mb-3">
                              <div class="card h-100 shadow-sm">
                                  ${isImage
                                      ? `<img src="/storage/${filePath}" class="card-img-top img-thumbnail" alt="${fileName}" style="height:150px;object-fit:cover;">`
                                      : `<div class="card-body text-center">
                                          <i class="bi bi-file-earmark-text fs-1 text-secondary mb-2"></i>
                                          <p class="small text-muted text-truncate" title="${fileName}">${fileName}</p>
                                        </div>`}
                                  <div class="card-footer bg-white border-top-0 text-center">
                                      <a href="/storage/${filePath}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                          <i class="bi bi-eye"></i> View
                                      </a>
                                  </div>
                              </div>
                          </div>`;
                  });
              });
  
              if (!props.files || props.files.length === 0) {
                  filesList.innerHTML = '<div class="col-12 text-muted">No files attached</div>';
              }
  
              new bootstrap.Modal(document.getElementById('eventModal')).show();
              info.jsEvent.preventDefault();
          }
      });
  
      calendar.render();
  });
  </script>
  
