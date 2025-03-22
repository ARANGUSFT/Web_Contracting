@extends('layouts.app')

@section('content')


<div class="container py-5">

    <!-- Current Pipeline -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Current Pipeline</h5>
            <span class="text-muted">Active Jobs: {{ array_sum($statusCounts) }}</span>
        </div>
        <div class="card-body text-center">
            <!-- Círculos de Estado + Checkboxes -->
            <div class="d-flex justify-content-around flex-wrap">
                @foreach ([
                    'leads' => ['L', 'bg-warning', 'Lead'],
                    'prospect' => ['P', 'bg-orange', 'Prospect'],
                    'approved' => ['A', 'bg-success', 'Approved'],
                    'completed' => ['C', 'bg-primary', 'Completed'],
                    'invoiced' => ['I', 'bg-danger', 'Invoiced']
                ] as $key => [$letter, $color, $label])
                    <div class="pipeline-item">
                        <div class="status-circle {{ $color }}" onclick="toggleStatusFilter('{{ $key }}')">
                            {{ $letter }}
                        </div>
                        <div class="status-count">{{ $statusCounts[$key] }}</div>
                        
                        <div class="form-check mt-2">
                            <input class="form-check-input status-checkbox" type="checkbox" id="status-{{ $key }}" value="{{ $key }}" onchange="filterLeads()">
                            <label class="form-check-label small" for="status-{{ $key }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Barra de búsqueda y filtros -->
    <div class="input-group mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Leads..." onkeyup="searchLeads()">
        <button id="assignedBtn" class="btn btn-outline-success filter-assignment" data-type="assigned" onclick="filterByAssignment(true, this)">
            <i class="bi bi-person-check"></i> Assigned
        </button>
        <button id="notAssignedBtn" class="btn btn-outline-danger filter-assignment" data-type="not-assigned" onclick="filterByAssignment(false, this)">
            <i class="bi bi-person-x"></i> Not Assigned
        </button>
    </div>
    




    <!-- Lista de leads en formato de tarjetas -->
    <div class="row" id="leadContainer">
        @foreach ($leads as $lead)
            <div class="col-md-6 col-lg-4 mb-4 lead-item" data-status="{{ strtolower($lead->statusText()) }}" data-assigned="{{ $lead->team_id ? 'yes' : 'no' }}">
                <div class="card">

                    <div class="card-header">
                        <h5>{{ $lead->first_name }} {{ $lead->last_name }}</h5>
                        <span class="badge badge-status {{ strtolower($lead->statusText()) }}">
                            {{ $lead->statusText() }}
                        </span>
                    </div>
    
                    <div class="card-body">
                        <p class="small text-muted mb-2">
                            <i class="bi bi-geo-alt text-warning"></i>
                            {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
                        </p>
    
                        <p class="mb-1 small">
                            <i class="bi bi-telephone text-success"></i> {{ $lead->phone }} <br>
                            <i class="bi bi-envelope text-danger"></i> {{ $lead->email }} <br>
                           
                            <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </p><hr>
    
    
                        
                        <div class="mb-3">
                            <form action="{{ route('leads.assignSales', $lead->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label class="form-label small fw-bold">Assign Seller:</label>
                                <select name="team_id" class="form-select select-seller" required>
                                    <option value="">Select Seller</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" {{ $lead->team_id == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Assign</button>
                            </form>
                        </div><hr>
    
                        <div class="card-footer">
                            <form action="{{ route('leads.assignstatus', $lead->id) }}" method="POST">
                                @csrf
                                <label class="form-label small fw-bold">Change Status:</label>
                                <select name="status" class="form-select select-status">
                                    @foreach ([1 => 'Lead', 2 => 'Prospect', 3 => 'Approved', 4 => 'Completed', 5 => 'Invoiced', 6 => 'Closed'] as $key => $status)
                                        <option value="{{ $key }}" {{ $lead->estado == $key ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-secondary btn-sm w-100 mt-2">Save</button>
                            </form>

                        </div><br>
                        
        
                        <a href="{{ route('leads.show', $lead->id) }}" class="btn btn-warning btn-sm w-100 mt-2">
                            <i class="bi bi-pencil-square"></i> View
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    

    


    <!-- 🔄 Contenedor de Paginación -->
    <div class="pagination-container mt-4 d-flex justify-content-center">
        <ul class="pagination" id="pagination"></ul>
    </div>

</div>




<style>
 

    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h5 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .card-body {
        padding: 1rem 1.5rem;
        text-align: left;
    }

    .card-footer {
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #e5e7eb;
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 10px;
        color: #ffffff;
    }

    .select-seller {
        border-color: #0d6efd;
    }

    .form-select.select-seller {
        background-color: #e7f1ff;
    }

    .select-status {
        background-color: #f0f8ff;
    }

    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-select, .btn {
        border-radius: 0.25rem;
    }






    /* Pipeline items styling */
    .pipeline-item {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        margin: 0.75rem;
        transition: transform 0.2s;
    }
    
    .pipeline-item:hover {
        transform: scale(1.05);
    }
    
    .status-circle {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }
    
    /* Status Colors */
    .bg-warning { background-color: #fbbf24; }
    .bg-orange { background-color: #fb923c; }
    .bg-success { background-color: #22c55e; }
    .bg-primary { background-color: #3b82f6; }
    .bg-danger { background-color: #ef4444; }
    
    .status-count {
        margin-top: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        color: #374151;
    }
    
    .form-check {
        margin-top: 0.5rem;
    }
    
    .form-check-label {
        font-size: 0.875rem;
        color: #6b7280;
        cursor: pointer;
    }
    
    .form-check-input {
        cursor: pointer;
    }








    
    
    /* Search bar and filters styling */
    .input-group {
        display: flex;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }
    
    .input-group .form-control {
        padding: 0.75rem 1rem;
        border: none;
        flex-grow: 1;
    }
    
    .input-group .form-control:focus {
        outline: none;
        box-shadow: none;
    }
    
    .filter-assignment {
        padding: 0.75rem 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    
    .btn-outline-success {
        border-color: #22c55e;
        color: #22c55e;
    }
    
    .btn-outline-success:hover, .btn-outline-success.active {
        background-color: #22c55e;
        color: #ffffff;
    }
    
    .btn-outline-danger {
        border-color: #ef4444;
        color: #ef4444;
    }
    
    .btn-outline-danger:hover, .btn-outline-danger.active {
        background-color: #ef4444;
        color: #ffffff;
    }



















</style>

<script>
        document.addEventListener("DOMContentLoaded", function () {
        let selectedStatuses = new Set(); // Estados seleccionados
        let assignedFilter = null; // Asignados (true), No Asignados (false), Todos (null)

        // 🔍 Búsqueda en tiempo real
        function searchLeads() {
            filterLeads();
        }

        // 🎯 Filtrar por estado (permite múltiples estados con checkboxes)
        function toggleStatusFilter(status) {
            let checkbox = document.getElementById(`status-${status}`);
            checkbox.checked = !checkbox.checked; // Alternar selección
            updateSelectedStatuses();
        }

        function updateSelectedStatuses() {
            selectedStatuses.clear();
            document.querySelectorAll(".status-checkbox:checked").forEach(checkbox => {
                selectedStatuses.add(checkbox.value);
            });
            updatePipelineUI();
            filterLeads();
        }

        // 👥 Filtrar por asignación (asignado o no asignado)
        function filterByAssignment(assigned, element) {
            assignedFilter = assigned;

            // Remueve la clase activa de todos los botones de asignación
            document.querySelectorAll(".filter-assignment").forEach(btn => btn.classList.remove("selected-filter"));

            // Agrega la clase activa al botón seleccionado
            if (element) element.classList.add("selected-filter");

            filterLeads();
        }

        // 🔄 Aplica los filtros combinados (búsqueda, estado y asignación)
        function filterLeads() {
            let searchQuery = document.getElementById("searchInput") ? document.getElementById("searchInput").value.toLowerCase() : "";
            let leads = document.querySelectorAll(".lead-item");

            leads.forEach(lead => {
                let text = lead.innerText.toLowerCase();
                let status = lead.getAttribute("data-status");
                let assigned = lead.getAttribute("data-assigned") === "yes";

                let matchesSearch = searchQuery === "" || text.includes(searchQuery);
                let matchesStatus = selectedStatuses.size === 0 || selectedStatuses.has(status);
                let matchesAssignment = assignedFilter === null || assignedFilter === assigned;

                lead.style.display = (matchesSearch && matchesStatus && matchesAssignment) ? "block" : "none";
            });
        }

        // 🎨 Actualiza la UI del Pipeline (resalta los estados seleccionados)
        function updatePipelineUI() {
            document.querySelectorAll(".status-circle").forEach(circle => {
                let status = circle.dataset.status;
                if (selectedStatuses.has(status)) {
                    circle.classList.add("selected-status");
                } else {
                    circle.classList.remove("selected-status");
                }
            });
        }

        // 🚀 Inicializar checkboxes de estado
        document.querySelectorAll(".status-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", updateSelectedStatuses);
        });

        // 🎯 Asigna eventos a los botones de estado
        document.querySelectorAll(".status-circle").forEach(circle => {
            let status = circle.dataset.status;
            circle.addEventListener("click", function () {
                toggleStatusFilter(status);
            });
        });

        // 🎯 Inicializar botones de asignación
        let assignedBtn = document.getElementById("assignedBtn");
        let notAssignedBtn = document.getElementById("notAssignedBtn");

        if (assignedBtn) assignedBtn.addEventListener("click", function () { filterByAssignment(true, this); });
        if (notAssignedBtn) notAssignedBtn.addEventListener("click", function () { filterByAssignment(false, this); });

        // 📝 Evento para la búsqueda en tiempo real
        let searchInput = document.getElementById("searchInput");
        if (searchInput) searchInput.addEventListener("keyup", searchLeads);
    });

    // 🏹 Redirige a la página de edición cuando se hace clic en una tarjeta (excepto en el formulario)
    function redirectToEdit(url, event) {
        let target = event.target;

        // Evita redireccionar si se hizo clic en un select o botón dentro de la tarjeta
        if (target.tagName.toLowerCase() !== "select" && target.tagName.toLowerCase() !== "button") {
            window.location.href = url;
        }
    }


</script>

<script>
    // ⏳ Cierra la alerta después de 2 segundos automáticamente
    setTimeout(function() {
        let alert = document.getElementById("customAlert");
        if (alert) {
            alert.classList.add("fade-out"); // Agrega clase de desvanecimiento
            setTimeout(() => alert.remove(), 500); // Remueve después de la animación
        }
    }, 2000); // 2000ms = 2 segundos

    
</script>


@endsection
