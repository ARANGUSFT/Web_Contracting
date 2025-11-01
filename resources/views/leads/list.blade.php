@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- 🧭 CURRENT PIPELINE -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-kanban"></i> Current Pipeline</h5>
            <span class="badge bg-secondary text-white px-3 py-2">Active Jobs: {{ array_sum($statusCounts) }}</span>
        </div>

        <div class="card-body text-center">
            <div class="d-flex justify-content-around flex-wrap gap-4">
                @foreach ([
                    'leads' => ['L', 'bg-warning', 'Lead'],
                    'prospect' => ['P', 'bg-orange', 'Prospect'],
                    'approved' => ['A', 'bg-success', 'Approved'],
                    'completed' => ['C', 'bg-primary', 'Completed'],
                    'invoiced' => ['I', 'bg-danger', 'Invoiced'],
                    'canceled' => ['X', 'bg-secondary', 'Canceled']
                ] as $key => [$letter, $color, $label])
                <div class="pipeline-item text-center position-relative">
                    <div class="status-circle {{ $color }}" data-status="{{ $key }}">
                        {{ $letter }}
                    </div>
                    <div class="status-count fw-bold mt-2">{{ $statusCounts[$key] ?? 0 }}</div>
                    @unless($key === 'leads' || $key === 'canceled')
                        <div class="text-muted small">${{ number_format($statusSums[$key] ?? 0, 2) }}</div>
                    @endunless
                    <small class="d-block mt-1 fw-semibold text-secondary">{{ $label }}</small>
                </div>
                @endforeach
            </div>
            
            <!-- 🎛️ FILTER CONTROLS -->
            <div class="mt-4 d-flex justify-content-center gap-2 flex-wrap">
                <button id="selectAllBtn" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-check-all"></i> Select All
                </button>
                <button id="resetFiltersBtn" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filters
                </button>
            </div>
            <div id="resultsCounter" class="text-muted small mt-2"></div>
        </div>
    </div>

    <!-- 🔍 SEARCH + FILTER BAR -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div class="input-group" style="max-width: 350px;">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control border-0" placeholder="Search leads...">
            </div>

            <div class="d-flex gap-2">
                <button id="assignedBtn" class="btn btn-outline-success filter-assignment">
                    <i class="bi bi-person-check"></i> Assigned
                </button>
                <button id="notAssignedBtn" class="btn btn-outline-danger filter-assignment">
                    <i class="bi bi-person-x"></i> Not Assigned
                </button>
                <select id="sellerFilter" class="form-select border-0 shadow-sm">
                    <option value="all">All Sellers</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- 🗂 LEAD CARDS -->
    <div class="row" id="leadContainer">
        @foreach ($leads as $lead)
        @php
            $statusClasses = [
                'Lead' => 'bg-warning',
                'Prospect' => 'bg-orange',
                'Approved' => 'bg-success',
                'Completed' => 'bg-primary',
                'Invoiced' => 'bg-danger',
                'Canceled' => 'bg-secondary',
            ];
            $statusLabel = $lead->statusText();
            $badgeClass = $statusClasses[$statusLabel] ?? 'bg-secondary';
        @endphp

        <div class="col-md-6 col-lg-4 mb-4 lead-item" 
             data-status="{{ strtolower($lead->statusText()) }}" 
             data-assigned="{{ $lead->team_id ? 'yes' : 'no' }}"
             data-seller="{{ $lead->team_id }}">
            <div class="card shadow-sm border-0 rounded-4 lead-card h-100 overflow-hidden">

                <!-- 🧩 HEADER -->
                <div class="card-header bg-white border-0 rounded-top-4 py-3 d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-semibold mb-0 text-dark">
                            {{ $lead->first_name }} {{ $lead->last_name }}
                        </h5>
                        <span class="badge {{ $badgeClass }} text-white mt-1">{{ $statusLabel }}</span>
                    </div>

                    <!-- ⚙️ MENU 3 DOTS -->
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="{{ route('leads.edit', $lead->id) }}">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Lead
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-warning" href="{{ route('leads.show', $lead->id) }}">
                                    <i class="bi bi-eye me-2"></i> View Details
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- 🧾 BODY -->
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-warning"></i>
                        {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
                    </p>
                    <p class="small mb-3">
                        <i class="bi bi-telephone text-success"></i> {{ $lead->phone }} <br>
                        <i class="bi bi-envelope text-danger"></i> {{ $lead->email }}
                    </p>

                    <div class="border-top border-light pt-2 mb-3">
                        <p class="small text-muted mb-1"><strong>Last Touched:</strong>
                            {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
                        </p>
                    </div>

                    <!-- 👥 ASSIGN SELLER -->
                    <form action="{{ route('leads.assignSales', $lead->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('PUT')
                        <label class="form-label small fw-bold text-secondary">Assign Seller:</label>
                        <select name="team_id" class="form-select form-select-sm border-0 shadow-sm select-seller">
                            <option value="">Select Seller</option>
                            <option value="" class="text-danger">-- Remove Assignment --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" {{ $lead->team_id == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-2 shadow-sm">
                            <i class="bi bi-check-circle"></i> Assign
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- 🔄 PAGINATION -->
    <div class="d-flex justify-content-center mt-4">
        {{ $leads->links() }}
    </div>
</div>
@endsection


<style>
    /* PIPELINE STYLES */
    .status-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        transition: all 0.25s ease-in-out;
    }

    .status-circle:hover {
        transform: scale(1.15);
        box-shadow: 0 5px 12px rgba(0,0,0,0.2);
    }

    .status-circle.selected-status {
        border: 3px solid #000;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

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

    .status-count {
        margin-top: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        color: #374151;
    }

    /* CARD STYLES */
    .lead-card {
        transition: all 0.25s ease-in-out;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .lead-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
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

    .card-header {
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
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

    /* FILTER STYLES */
    .filter-assignment.selected-filter {
        background-color: #111827;
        color: #fff;
        border-color: #111827;
        transform: scale(1.05);
    }

    .input-group {
        display: flex;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
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

    /* STATUS COLORS */
    .bg-warning  { background-color: #fbbf24; }
    .bg-orange   { background-color: #fb923c; }
    .bg-success  { background-color: #22c55e; }
    .bg-primary  { background-color: #3b82f6; }
    .bg-danger   { background-color: #ef4444; }
    .bg-secondary { background-color: #6b7280; }

    /* FORM CONTROLS */
    .form-select.select-seller {
        background-color: #e7f1ff;
        border-color: #0d6efd;
    }

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

    /* DROPDOWN */
    .dropdown-item:hover {
        background-color: #f3f4f6;
    }

    /* RESULTS COUNTER */
    #resultsCounter {
        font-weight: 500;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        display: inline-block;
    }
</style>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectedStatuses = new Set();
        let assignedFilter = null;
        let currentSellerFilter = 'all';

        // DOM ELEMENTS
        const searchInput = document.getElementById("searchInput");
        const assignedBtn = document.getElementById("assignedBtn");
        const notAssignedBtn = document.getElementById("notAssignedBtn");
        const sellerFilter = document.getElementById("sellerFilter");
        const selectAllBtn = document.getElementById("selectAllBtn");
        const resetFiltersBtn = document.getElementById("resetFiltersBtn");
        const resultsCounter = document.getElementById("resultsCounter");

        // DEBUG FUNCTION - TEMPORAL
        function debugAllStatuses() {
            console.log('=== DEBUG STATUSES ===');
            console.log('Selected statuses:', Array.from(selectedStatuses));
            
            // Mostrar todos los data-status de los círculos
            console.log('Pipeline circles:');
            document.querySelectorAll('.status-circle').forEach(circle => {
                console.log(' -', circle.dataset.status, 'Element:', circle);
            });
            
            // Mostrar todos los data-status de los lead items
            console.log('Lead items:');
            document.querySelectorAll('.lead-item').forEach(lead => {
                console.log(' -', lead.dataset.status, 'Lead:', lead.querySelector('h5')?.textContent);
            });
        }

        // EVENT LISTENERS
        if (searchInput) searchInput.addEventListener("keyup", filterLeads);
        if (assignedBtn) assignedBtn.addEventListener("click", () => filterByAssignment(true, assignedBtn));
        if (notAssignedBtn) notAssignedBtn.addEventListener("click", () => filterByAssignment(false, notAssignedBtn));
        if (sellerFilter) sellerFilter.addEventListener("change", (e) => filterBySeller(e.target.value));
        if (selectAllBtn) selectAllBtn.addEventListener("click", toggleSelectAll);
        if (resetFiltersBtn) resetFiltersBtn.addEventListener("click", resetFilters);

        // STATUS CIRCLES
        document.querySelectorAll(".status-circle").forEach(circle => {
            circle.addEventListener("click", () => {
                const status = circle.dataset.status;
                toggleStatusFilter(status, circle);
            });
        });

        // FILTER FUNCTIONS
        function toggleStatusFilter(status, circle) {
            if (selectedStatuses.has(status)) {
                selectedStatuses.delete(status);
                circle.classList.remove("selected-status");
            } else {
                selectedStatuses.add(status);
                circle.classList.add("selected-status");
            }
            updateSelectAllButton();
            filterLeads();
            debugAllStatuses(); // TEMPORAL - para debug
        }

        function filterLeads() {
            const query = searchInput?.value.toLowerCase() || "";
            const leads = document.querySelectorAll(".lead-item");
            let visibleCount = 0;

            // MAPA COMPLETO DE ESTADOS - solución definitiva
            const statusMap = {
                'lead': 'leads',           // lead item -> pipeline circle
                'prospect': 'prospect',
                'approved': 'approved', 
                'completed': 'completed',
                'invoiced': 'invoiced',
                'canceled': 'canceled',
                'cancelled': 'canceled',   // por si hay typo
                'closed': 'canceled'       // por si usas closed en algún lugar
            };

            leads.forEach(lead => {
                const leadText = lead.innerText.toLowerCase();
                const leadStatus = lead.dataset.status;
                const assigned = lead.dataset.assigned === "yes";
                const seller = lead.dataset.seller;

                const matchSearch = !query || leadText.includes(query);
                
                // USAR EL MAPA COMPLETO
                const mappedStatus = statusMap[leadStatus] || leadStatus;
                
                console.log('Comparing:', 'leadStatus:', leadStatus, 'mappedStatus:', mappedStatus, 'selectedStatuses:', Array.from(selectedStatuses));
                
                const matchStatus = selectedStatuses.size === 0 || selectedStatuses.has(mappedStatus);
                const matchAssigned = assignedFilter === null || assignedFilter === assigned;
                const matchSeller = currentSellerFilter === 'all' || seller === currentSellerFilter;

                const shouldShow = matchSearch && matchStatus && matchAssigned && matchSeller;
                
                lead.style.display = shouldShow ? "block" : "none";
                if (shouldShow) visibleCount++;
            });

            updateResultsCounter(visibleCount, leads.length);
        }

        function filterByAssignment(assigned, element) {
            assignedFilter = assigned;
            document.querySelectorAll(".filter-assignment").forEach(btn => 
                btn.classList.remove("selected-filter")
            );
            if (element) element.classList.add("selected-filter");
            filterLeads();
        }

        function filterBySeller(sellerId) {
            currentSellerFilter = sellerId;
            filterLeads();
        }

        function toggleSelectAll() {
            const allCircles = document.querySelectorAll(".status-circle");
            const allSelected = selectedStatuses.size === allCircles.length;
            
            if (allSelected) {
                selectedStatuses.clear();
                allCircles.forEach(circle => circle.classList.remove("selected-status"));
            } else {
                selectedStatuses.clear();
                allCircles.forEach(circle => {
                    const status = circle.dataset.status;
                    selectedStatuses.add(status);
                    circle.classList.add("selected-status");
                });
            }
            updateSelectAllButton();
            filterLeads();
        }

        function resetFilters() {
            selectedStatuses.clear();
            assignedFilter = null;
            currentSellerFilter = 'all';
            
            if (searchInput) searchInput.value = '';
            if (sellerFilter) sellerFilter.value = 'all';
            
            document.querySelectorAll(".status-circle").forEach(circle => 
                circle.classList.remove("selected-status")
            );
            
            document.querySelectorAll(".filter-assignment").forEach(btn => 
                btn.classList.remove("selected-filter")
            );
            
            updateSelectAllButton();
            filterLeads();
        }

        // UTILITY FUNCTIONS
        function updateSelectAllButton() {
            const allCircles = document.querySelectorAll(".status-circle");
            if (selectAllBtn) {
                selectAllBtn.innerHTML = selectedStatuses.size === allCircles.length 
                    ? '<i class="bi bi-x-circle"></i> Deselect All' 
                    : '<i class="bi bi-check-all"></i> Select All';
            }
        }

        function updateResultsCounter(visible, total) {
            if (resultsCounter) {
                resultsCounter.textContent = `Showing ${visible} of ${total} leads`;
                resultsCounter.style.display = 'block';
            }
        }

        // INITIALIZATION
        filterLeads();
        updateSelectAllButton();
        
        // DEBUG TEMPORAL
        setTimeout(debugAllStatuses, 1000);
    });

    // AUTO-CLOSE ALERTS
    setTimeout(function() {
        let alert = document.getElementById("customAlert");
        if (alert) {
            alert.classList.add("fade-out");
            setTimeout(() => alert.remove(), 500);
        }
    }, 2000);
</script>