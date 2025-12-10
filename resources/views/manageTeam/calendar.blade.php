@extends('layouts.app')

@section('title', 'My Schedule Calendar')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-3 p-3 me-3">
                        <i class="bi bi-calendar-week text-white fs-2"></i>
                    </div>
                    <div>
                        <h1 class="text-primary mb-1">My Schedule Calendar</h1>
                        <p class="text-muted mb-0">Manage your appointments and assignments in one place</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                 
                    <button class="btn btn-primary" id="todayBtn">
                        <i class="bi bi-calendar-check me-2"></i>Today
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count(array_filter($events, fn($e) => $e['type'] === 'Job Request')) }}</h4>
                            <p class="mb-0 opacity-75">Job Requests</p>
                        </div>
                        <i class="bi bi-briefcase fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-danger text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count(array_filter($events, fn($e) => $e['type'] === 'Emergency')) }}</h4>
                            <p class="mb-0 opacity-75">Emergencies</p>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-purple text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count(array_filter($events, fn($e) => $e['type'] === 'Approved')) }}</h4>
                            <p class="mb-0 opacity-75">Lead Approved</p>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count($events) }}</h4>
                            <p class="mb-0 opacity-75">Total Events</p>
                        </div>
                        <i class="bi bi-calendar-event fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Calendar Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="bi bi-calendar-range me-2"></i>Schedule Overview
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-job me-2">Job Request</span>
                            <span class="badge bg-emergency me-2">Emergency</span>
                            <span class="badge bg-lead">Approved</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="calendar" class="p-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Event Details Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Event Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light p-0">
                {{-- Event Header --}}
                <div class="bg-white p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="text-primary mb-2" id="modalEventTitle"></h4>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="badge fs-6" id="modalEventTypeBadge"></span>
                                <span class="text-muted">
                                    <i class="bi bi-calendar-date me-1"></i>
                                    <span id="modalEventDate"></span>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" id="eventActionBtn">
                            <i class="bi bi-arrow-up-right-square me-1"></i>View Details
                        </button>
                    </div>
                </div>

                {{-- Content Sections --}}
                <div class="p-4">
                    {{-- Basic Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-3"><i class="bi bi-building me-2"></i>Company Information</h6>
                            <div class="bg-white p-3 rounded border">
                                <div class="mb-2">
                                    <strong>Company:</strong>
                                    <div id="modalEventCompany" class="text-muted"></div>
                                </div>
                                <div class="mb-2">
                                    <strong>Representative:</strong>
                                    <div id="modalEventRep" class="text-muted"></div>
                                    <small id="modalEventRepContact" class="text-muted"></small>
                                </div>
                                <div>
                                    <strong>Customer:</strong>
                                    <div id="modalEventCustomer" class="text-muted"></div>
                                    <small id="modalEventCustomerContact" class="text-muted"></small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Location</h6>
                            <div class="bg-white p-3 rounded border">
                                <p class="mb-0" id="modalEventAddress"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Materials & Instructions --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-3"><i class="bi bi-tools me-2"></i>Materials</h6>
                            <div class="bg-white p-3 rounded border">
                                <div id="modalEventMaterials"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-3"><i class="bi bi-chat-left-text me-2"></i>Special Instructions</h6>
                            <div class="bg-white p-3 rounded border">
                                <p id="modalEventInstructions" class="mb-0"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Team & Files --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-3"><i class="bi bi-people me-2"></i>Assigned Team</h6>
                            <div class="bg-white p-3 rounded border">
                                <div id="modalEventTeam"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-3"><i class="bi bi-paperclip me-2"></i>Attached Files</h6>
                            <div class="bg-white p-3 rounded border">
                                <div id="modalEventFiles"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- FullCalendar Assets --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
:root {
    --job-color: #198754;
    --emergency-color: #dc3545;
    --lead-color: #670ebb;
    --purple: #670ebb;
}

.bg-purple { background-color: var(--purple) !important; }
.bg-job { background-color: var(--job-color) !important; }
.bg-emergency { background-color: var(--emergency-color) !important; }
.bg-lead { background-color: var(--lead-color) !important; }

.card {
    border-radius: 12px;
}

#calendar {
    min-height: 700px;
}

.fc-header-toolbar {
    padding: 1.5rem;
    margin-bottom: 0 !important;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.fc-toolbar-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
}

.fc-button {
    border-radius: 8px !important;
    border: 1px solid #dee2e6 !important;
    background-color: white !important;
    color: #495057 !important;
    font-weight: 500 !important;
    padding: 0.5rem 1rem !important;
}

.fc-button-primary:not(:disabled).fc-button-active {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.fc-button:hover {
    background-color: #e9ecef !important;
    transform: translateY(-1px);
}

.fc-event {
    border-radius: 8px !important;
    border: none !important;
    padding: 8px 12px !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.fc-event:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.event-job-request { background-color: var(--job-color) !important; }
.event-emergency { background-color: var(--emergency-color) !important; }
.event-lead-approval { background-color: var(--lead-color) !important; }

.fc-daygrid-event-dot { display: none !important; }

.modal-content {
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.team-member-item {
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 8px;
    border-left: 3px solid #0d6efd;
}

.file-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
}

.file-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.material-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid #dee2e6;
}

.material-item:last-child {
    border-bottom: none;
}

.material-value {
    font-weight: 600;
    color: #0d6efd;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    
    #calendar {
        min-height: 500px;
    }
    
    .fc-toolbar {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Calendar Configuration
    const calendarEl = document.getElementById('calendar');
    let currentEvents = @json($events);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: "auto",
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        buttonText: {
            today: 'Today',
            list: 'List'
        },
        events: currentEvents,
        eventDisplay: 'block',
        firstDay: 1,
        navLinks: true,
        dayMaxEvents: 3,

        eventClick: function (info) {
            const event = info.event;
            const props = event.extendedProps;

            console.log('Event clicked:', event.title);
            console.log('Event type:', props.type);
            console.log('Event URL:', event.url);

            // Handle Lead Approval redirect - VERIFICAR SI ESTÁ FUNCIONANDO
            if (props.type === 'Approved' && event.url) {
                console.log('Redirecting to lead:', event.url);
                window.location.href = event.url;
                info.jsEvent.preventDefault();
                return;
            }

            // Para otros tipos de eventos, mostrar el modal
            populateEventModal(event, props);
            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
            
            info.jsEvent.preventDefault();
        },

        eventDidMount: function (info) {
            const eventType = info.event.extendedProps.type?.toLowerCase().replace(' ', '-');
            if (eventType) {
                info.el.classList.add(`event-${eventType}`);
            }

            if (info.event.extendedProps.company) {
                info.el.setAttribute('title', `${info.event.title}\n${info.event.extendedProps.company}`);
            }
        }
    });

    // Initialize Calendar
    calendar.render();

    // Today Button
    document.getElementById('todayBtn').addEventListener('click', function() {
        calendar.today();
    });

    // Filter Functionality
    document.querySelectorAll('.filter-option').forEach(option => {
        option.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active state
            document.querySelectorAll('.filter-option').forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Filter events
            let filteredEvents = currentEvents;
            if (filter !== 'all') {
                filteredEvents = currentEvents.filter(event => 
                    event.type.toLowerCase().includes(filter)
                );
            }
            
            // Update calendar
            calendar.removeAllEvents();
            calendar.addEventSource(filteredEvents);
        });
    });

    // Populate Event Modal with Data
    function populateEventModal(event, props) {
        // Basic Information
        document.getElementById('modalEventTitle').textContent = event.title || 'No Title';
        document.getElementById('modalEventDate').textContent = event.start ? event.start.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : 'Date not set';
        
        // Type Badge
        const typeBadge = document.getElementById('modalEventTypeBadge');
        typeBadge.textContent = props.type || 'Not specified';
        typeBadge.className = 'badge fs-6 ' + getEventTypeClass(props.type);

        // Action Button - Para leads approved
        const actionBtn = document.getElementById('eventActionBtn');
        if (event.url) {
            actionBtn.style.display = 'block';
            // Si es un lead approved, redirigir en la misma ventana
            if (props.type === 'Approved') {
                actionBtn.onclick = () => {
                    console.log('Redirecting to lead from modal:', event.url);
                    window.location.href = event.url;
                };
                actionBtn.innerHTML = '<i class="bi bi-arrow-up-right-square me-1"></i>View Lead Details';
            } else {
                // Para otros tipos, abrir en nueva pestaña
                actionBtn.onclick = () => window.open(event.url, '_blank');
                actionBtn.innerHTML = '<i class="bi bi-arrow-up-right-square me-1"></i>View Details';
            }
        } else {
            actionBtn.style.display = 'none';
        }

        // Company Information
        document.getElementById('modalEventCompany').textContent = props.company || 'Not specified';
        document.getElementById('modalEventRep').textContent = props.rep || props.email || 'Not specified';
        document.getElementById('modalEventRepContact').textContent = [props.rep_phone, props.rep_email].filter(Boolean).join(' • ') || '';
        document.getElementById('modalEventCustomer').textContent = props.customer || 'Not specified';
        document.getElementById('modalEventCustomerContact').textContent = props.customer_phone || '';

        // Location
        document.getElementById('modalEventAddress').textContent = props.address || 'Address not provided';

        // Materials
        const materialsDiv = document.getElementById('modalEventMaterials');
        if (props.materials) {
            materialsDiv.innerHTML = `
                <div class="material-item">
                    <span>Starter Bundles:</span>
                    <span class="material-value">${props.materials.starter || 0}</span>
                </div>
                <div class="material-item">
                    <span>Hip & Ridge:</span>
                    <span class="material-value">${props.materials.hip || 0}</span>
                </div>
                <div class="material-item">
                    <span>Field Shingles:</span>
                    <span class="material-value">${props.materials.field || 0}</span>
                </div>
                <div class="material-item">
                    <span>Modified Bitumen:</span>
                    <span class="material-value">${props.materials.modified || 0}</span>
                </div>
            `;
        } else {
            materialsDiv.innerHTML = `<p class="text-muted mb-0">${props.supplement || 'No materials specified'}</p>`;
        }

        // Instructions
        const instructions = props.special_instructions || 'No special instructions provided';
        document.getElementById('modalEventInstructions').textContent = instructions;

        // Team Members
        const teamDiv = document.getElementById('modalEventTeam');
        teamDiv.innerHTML = '';
        if (props.team?.length) {
            props.team.forEach(member => {
                const memberDiv = document.createElement('div');
                memberDiv.className = 'team-member-item';
                memberDiv.innerHTML = `<i class="bi bi-person-check me-2 text-success"></i>${member}`;
                teamDiv.appendChild(memberDiv);
            });
        } else {
            teamDiv.innerHTML = '<div class="text-center text-muted py-2"><i class="bi bi-person-x me-2"></i>No team members assigned</div>';
        }

        // Files
        const filesDiv = document.getElementById('modalEventFiles');
        filesDiv.innerHTML = '';

        if (props.files?.length) {
            props.files.forEach(file => {
                const fileName = file.name;
                const filePath = file.path;
                const extMatch = fileName.match(/\.(\w+)$/);
                const ext = (extMatch?.[1] || '').toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext);

                const fileDiv = document.createElement('div');
                fileDiv.className = 'file-item';
                fileDiv.innerHTML = `
                    <i class="bi ${isImage ? 'bi-file-image text-primary' : 'bi-file-earmark-text text-secondary'} me-3"></i>
                    <div class="flex-grow-1">
                        <div class="small fw-medium">${fileName}</div>
                        <div class="text-muted extra-small">${file.label}</div>
                    </div>
                    <a href="/storage/${filePath}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                        <i class="bi bi-download"></i>
                    </a>
                `;
                filesDiv.appendChild(fileDiv);
            });
        } else {
            filesDiv.innerHTML = '<div class="text-center text-muted py-2"><i class="bi bi-inbox me-2"></i>No files attached</div>';
        }
    }

    // Get CSS Class for Event Type Badge
    function getEventTypeClass(type) {
        switch(type?.toLowerCase()) {
            case 'job request': return 'bg-job';
            case 'emergency': return 'bg-emergency';
            case 'approved': return 'bg-lead';
            default: return 'bg-secondary';
        }
    }
});
</script>
@endsection