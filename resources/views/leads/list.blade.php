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
                    'leads' => ['L', 'bg-warning', 'lead'],
                    'prospect' => ['P', 'bg-orange', 'prospect'],
                    'approved' => ['A', 'bg-success', 'approved'],
                    'completed' => ['C', 'bg-primary', 'completed'],
                    'invoiced' => ['I', 'bg-danger', 'invoiced']
                ] as $key => [$letter, $color, $label])
                   

                <div class="pipeline-item text-center">
                    <div class="status-circle {{ $color }} mb-1" data-status="{{ $key }}">
                        {{ $letter }}
                    </div>
                    <div class="status-count">{{ $statusCounts[$key] ?? 0 }}</div>
                    @unless($key === 'leads')
                    <p class="small text-muted mb-0">
                        ${{ number_format($statusSums[$key] ?? 0, 2) }}
                    </p>
                @endunless
                
                    <small class="d-block mt-1">{{ $label }}</small>
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

        <select id="sellerFilter" class="form-select" onchange="filterBySeller(this.value)">
            <option value="all">All Sellers</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>
        
    </div>





    <!-- Lista de leads en formato de tarjetas -->
    <div class="row" id="leadContainer">
        @foreach ($leads as $lead)
            <div class="col-md-6 col-lg-4 mb-4 lead-item" data-status="{{ strtolower($lead->statusText()) }}" data-assigned="{{ $lead->team_id ? 'yes' : 'no' }}">
                <div class="card">

                    @php
                        $statusClasses = [
                            'Lead' => 'bg-warning',
                            'Prospect' => 'bg-orange',
                            'Approved' => 'bg-success',
                            'Completed' => 'bg-primary',
                            'Invoiced' => 'bg-danger',
                        ];
                    
                        $statusLabel = $lead->statusText();
                        $badgeClass = $statusClasses[$statusLabel] ?? 'bg-secondary';
                    @endphp
                
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $lead->first_name }} {{ $lead->last_name }}</h5>
                        <span class="badge text-white {{ $badgeClass }}">
                            {{ $statusLabel }}
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
                            <hr>
                            <p><strong>🕒 Last Touched:</strong> 
                                {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
                            </p>
                        </p><hr>
    
    
                        
                        <div class="mb-3">
                            <form action="{{ route('leads.assignSales', $lead->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label class="form-label small fw-bold">Assign Seller:</label>
                                <select name="team_id" class="form-select select-seller" required>
                                    <option value="">Select Seller</option>
                                    <option value="" class="text-danger">-- Remove Assignment --</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" {{ $lead->team_id == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Assign</button>
                            </form>
                        </div><hr>
                        
        
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




<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectedStatuses = new Set(); // Estados seleccionados
        let assignedFilter = null; // Asignación (sí, no, todos)

        // 🔍 Búsqueda en tiempo real
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("keyup", filterLeads);
        }

        // 🎯 Botones de asignación
        const assignedBtn = document.getElementById("assignedBtn");
        const notAssignedBtn = document.getElementById("notAssignedBtn");

        if (assignedBtn) assignedBtn.addEventListener("click", () => filterByAssignment(true, assignedBtn));
        if (notAssignedBtn) notAssignedBtn.addEventListener("click", () => filterByAssignment(false, notAssignedBtn));

        // 🚀 Círculos de estado
        document.querySelectorAll(".status-circle").forEach(circle => {
            const status = circle.dataset.status;
            circle.addEventListener("click", () => toggleStatusFilter(status, circle));
        });

        // ✅ Alternar estado seleccionado
        function toggleStatusFilter(status, circle) {
            if (selectedStatuses.has(status)) {
                selectedStatuses.delete(status);
                circle.classList.remove("selected-status");
            } else {
                selectedStatuses.add(status);
                circle.classList.add("selected-status");
            }
            filterLeads();
        }

        // ✅ Filtrar leads combinando búsqueda, estado y asignación
        function filterLeads() {
            const query = searchInput?.value.toLowerCase() || "";
            const leads = document.querySelectorAll(".lead-item");

            leads.forEach(lead => {
                const leadText = lead.innerText.toLowerCase();
                const leadStatus = lead.dataset.status;
                const assigned = lead.dataset.assigned === "yes";

                const matchSearch = !query || leadText.includes(query);
                const matchStatus = selectedStatuses.size === 0 || selectedStatuses.has(leadStatus);
                const matchAssigned = assignedFilter === null || assignedFilter === assigned;

                lead.style.display = (matchSearch && matchStatus && matchAssigned) ? "block" : "none";
            });
        }

        // ✅ Filtrar por asignación
        function filterByAssignment(assigned, element) {
            assignedFilter = assigned;

            // Quitar clase activa a todos los botones
            document.querySelectorAll(".filter-assignment").forEach(btn =>
                btn.classList.remove("selected-filter")
            );

            // Marcar botón activo
            if (element) element.classList.add("selected-filter");

            filterLeads();
        }

        // Filtro inicial
        filterLeads();
    });

    // 🏹 Redirección segura a edición
    function redirectToEdit(url, event) {
        const tag = event.target.tagName.toLowerCase();
        if (tag !== "select" && tag !== "button") {
            window.location.href = url;
        }
    }
</script>

<style>
    .status-circle.selected-status {
    border: 3px solid black;
    transform: scale(1.05);
    transition: all 0.2s ease;
    }
</style>







<style>
    /* CARD STYLES */
    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .lead-item {
        display: flex;
    }

    .lead-item .card {
        flex: 1;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .card-header,
    .card-footer {
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-color: #e5e7eb;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
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

    /* BADGE STYLES */
    .badge-status {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 10px;
        color: #ffffff;
    }

    /* FORM CONTROLS */
    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-select,
    .btn {
        border-radius: 0.25rem;
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

    /* PIPELINE ITEMS */
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

    /* STATUS COLORS */
    .bg-warning  { background-color: #fbbf24; }
    .bg-orange   { background-color: #fb923c; }
    .bg-success  { background-color: #22c55e; }
    .bg-primary  { background-color: #3b82f6; }
    .bg-danger   { background-color: #ef4444; }

    .status-count {
        margin-top: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        color: #374151;
    }

    /* FORM CHECK */
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

    /* SEARCH BAR AND FILTERS */
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

    /* BUTTON VARIANTS */
    .btn-outline-success {
        border-color: #22c55e;
        color: #22c55e;
    }

    .btn-outline-success:hover,
    .btn-outline-success.active {
        background-color: #22c55e;
        color: #ffffff;
    }

    .btn-outline-danger {
        border-color: #ef4444;
        color: #ef4444;
    }

    .btn-outline-danger:hover,
    .btn-outline-danger.active {
        background-color: #ef4444;
        color: #ffffff;
    }



</style>
    

<script>
    function filterBySeller(sellerId) {
        document.querySelectorAll('.lead-item').forEach(item => {
            const itemSellerId = item.querySelector('.select-seller').value;
            item.style.display = (sellerId === 'all' || itemSellerId === sellerId) ? '' : 'none';
        });
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
