@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="manager-banner card shadow-sm mb-4 border-0" style="background-color: #e0f2fe;">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-gear fs-3 text-primary me-3"></i>
                <div>
                    <h5 class="mb-0 fw-semibold text-primary">Guest Dashboard</h5>
                    <small class="text-muted">You're managing leads and sellers under your supervision.</small>
                </div>
            </div>
        </div>
    </div>
    
    
    
   

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
                    'leads' => ['L', 'bg-warning', ''],
                    'prospect' => ['P', 'bg-orange', ''],
                    'approved' => ['A', 'bg-success', ''],
                    'completed' => ['C', 'bg-primary', ''],
                    'invoiced' => ['I', 'bg-danger', '']
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


    

    <!-- Filtros -->
    <div class="input-group mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for leads..." onkeyup="filterLeads()">
        <select id="sellerFilter" class="form-select" onchange="filterBySellerServer(this.value)">
            <option value="all">All sellers</option>
            @foreach ($sellers as $seller)
                <option value="{{ $seller->id }}" {{ $sellerId == $seller->id ? 'selected' : '' }}>
                    {{ $seller->name }}
                </option>
            @endforeach
        </select>
        
    </div>


    
    <!-- Lista de leads -->
    <div class="row" id="leadContainer">
        @foreach ($leads as $lead)
            <div class="col-md-6 col-lg-4 mb-4 lead-item" data-status="{{ strtolower($lead->statusText()) }}">
                <div class="card">
                    @php
                        $statusClasses = [
                            'Lead' => 'bg-warning',
                            'Prospect' => 'bg-orange',
                            'Approved' => 'bg-success',
                            'Completed' => 'bg-primary',
                            'Invoiced' => 'bg-danger',
                        ];
                        $badgeClass = $statusClasses[$lead->statusText()] ?? 'bg-secondary';
                    @endphp

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $lead->first_name }} {{ $lead->last_name }}</h5>
                        <span class="badge text-white {{ $badgeClass }}">{{ $lead->statusText() }}</span>
                    </div>

                    <div class="card-body">
                        <p class="small text-muted mb-2">
                            📍 {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
                        </p>
                        <p class="mb-2 small">
                            📞 {{ $lead->phone }} <br>
                            ✉️ {{ $lead->email }}
                        </p>
                        <p><strong>🕒 Last Touched:</strong> {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}</p>
            
                        @if ($lead->team)
                            <p class="mb-2"><strong>👤 Assigned Seller:</strong> {{ $lead->team->name }}</p>
                        @else
                            <p class="mb-2 text-muted"><strong>👤 Assigned Seller:</strong> Unassigned</p>
                        @endif
            
                        <a href="{{ route('guest.view', $lead->id) }}" class="btn btn-sm btn-warning w-100 mt-2">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

            


    <!-- Paginación -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $leads->links() }}
    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const sellerFilter = document.getElementById('sellerFilter');
    
        searchInput.addEventListener('input', filterLeads);
        sellerFilter.addEventListener('change', filterLeads);
    
        function filterLeads() {
            const query = searchInput.value.toLowerCase();
            const selectedSellerId = sellerFilter.value;
    
            document.querySelectorAll('.lead-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                const assignedSeller = item.dataset.seller;
    
                const matchesSearch = text.includes(query);
                const matchesSeller = selectedSellerId === 'all' || assignedSeller === selectedSellerId;
    
                if (matchesSearch && matchesSeller) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    
        // Ejecutar filtrado al cargar
        filterLeads();
    });
</script>
    
    

<style>
    .pipeline-item {
        width: 100px;
    }

    .status-circle {
        width: 50px;
        height: 50px;
        line-height: 50px;
        border-radius: 50%;
        text-align: center;
        color: #fff;
        font-weight: bold;
    }

    .bg-orange { background-color: #fb923c; }
</style>




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
 function filterBySellerServer(sellerId) {
        const url = new URL(window.location.href);
        url.searchParams.set('seller_id', sellerId);
        window.location.href = url.toString();
    }

    function filterLeads() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const leads = document.querySelectorAll('.lead-item');

        leads.forEach(lead => {
            const text = lead.textContent.toLowerCase();
            lead.style.display = text.includes(searchTerm) ? '' : 'none';
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
