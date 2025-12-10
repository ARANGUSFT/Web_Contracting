@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- 🧭 CURRENT PIPELINE -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
            @php
                $activeJobs = collect($statusCounts)->except('cancelled')->sum();
            @endphp

            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-kanban"></i> Current Pipeline
            </h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary text-white px-3 py-2">
                    Active Jobs: {{ $activeJobs }}
                </span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-filter"></i> Quick Filters
                    </button>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item quick-filter" href="#" data-filter="active">Active Leads</a></li>
                        <li><a class="dropdown-item quick-filter" href="#" data-filter="assigned">Assigned Only</a></li>
                        <li><a class="dropdown-item quick-filter" href="#" data-filter="unassigned">Unassigned Only</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item quick-filter" href="#" data-filter="reset">Reset All</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- PIPELINE CON SCROLL HORIZONTAL MEJORADO -->
            <div class="pipeline-container">
                <div class="pipeline-scroll">
                    @foreach ([
                        'leads'     => ['L', 'bg-warning',   'Lead', 'New potential clients'],
                        'prospect'  => ['P', 'bg-orange',   'Prospect', 'Qualified opportunities'],
                        'approved'  => ['A', 'bg-success',  'Approved', 'Approved projects'],
                        'completed' => ['C', 'bg-primary',  'Completed', 'Work completed'],
                        'invoiced'  => ['I', 'bg-danger',   'Invoiced', 'Invoiced to client'],
                        'finish'    => ['F', 'bg-info',     'Finish', 'Project finalized'],
                        'cancelled' => ['X', 'bg-secondary','Cancelled', 'Cancelled projects'],
                    ] as $key => [$letter, $color, $label, $tooltip])
                        <div class="pipeline-item text-center position-relative" data-bs-toggle="tooltip" title="{{ $tooltip }}">
                            <div class="status-circle {{ $color }}" data-status="{{ $key }}">
                                {{ $letter }}
                                <div class="status-pulse"></div>
                            </div>

                            <div class="status-count fw-bold mt-2">
                                {{ $statusCounts[$key] ?? 0 }}
                            </div>

                            @if($key !== 'leads' && $key !== 'cancelled')
                                <div class="text-muted small status-amount">
                                    ${{ number_format($statusSums[$key] ?? 0, 2) }}
                                </div>
                            @endif

                            <small class="d-block mt-1 fw-semibold text-secondary">
                                {{ $label }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 🎛️ FILTER CONTROLS MEJORADOS -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <button id="selectAllBtn" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-check-all"></i> Select All
                        </button>
                        <button id="resetFiltersBtn" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                        <div class="vr"></div>
                        <div class="selected-filters-container">
                            <small class="text-muted" id="activeFiltersText">No active filters</small>
                        </div>
                    </div>
                    
                    <div id="resultsCounter" class="text-muted small badge bg-light text-dark">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔍 SEARCH + FILTER BAR MEJORADO -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <!-- SEARCH -->
                <div class="col-md-4">
                    <div class="input-group search-container">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control border-0" placeholder="Search by name, email, phone, location...">
                        <button class="btn btn-outline-secondary clear-search" type="button" style="display: none;">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>

                <!-- STATUS FILTER -->
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select border-0 shadow-sm">
                        <option value="all">All Statuses</option>
                        <option value="leads">Lead</option>
                        <option value="prospect">Prospect</option>
                        <option value="approved">Approved</option>
                        <option value="completed">Completed</option>
                        <option value="invoiced">Invoiced</option>
                        <option value="finish">Finish</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- ASSIGNMENT FILTER -->
                <div class="col-md-3">
                    <select id="assignmentFilter" class="form-select border-0 shadow-sm">
                        <option value="all">All Assignments</option>
                        <option value="assigned">Assigned Only</option>
                        <option value="unassigned">Unassigned Only</option>
                    </select>
                </div>

             
            </div>

            <!-- FILTROS AVANZADOS -->
            <div class="row mt-3 advanced-filters" style="display: none;">
                <div class="col-12">
                    <div class="border-top pt-3">
                        <h6 class="text-muted mb-2">Advanced Filters</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small">Last Contact</label>
                                <select class="form-select form-select-sm" id="lastContactFilter">
                                    <option value="all">Any Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="older">Older than Month</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Amount Range</label>
                                <select class="form-select form-select-sm" id="amountFilter">
                                    <option value="all">Any Amount</option>
                                    <option value="0-1000">$0 - $1,000</option>
                                    <option value="1000-5000">$1,000 - $5,000</option>
                                    <option value="5000+">$5,000+</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <button class="btn btn-sm btn-link text-decoration-none p-0 toggle-advanced-filters">
                        <i class="bi bi-chevron-down"></i> Advanced Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 🗂 LEAD CARDS MEJORADOS -->
    <div class="position-relative">
        <div class="row" id="leadContainer">
            @if(count($leads) > 0)
                @foreach ($leads as $lead)
                @php
                    $statusClasses = [
                        'Lead' => 'bg-warning',
                        'Prospect' => 'bg-orange',
                        'Approved' => 'bg-success',
                        'Completed' => 'bg-primary',
                        'Invoiced' => 'bg-danger',
                        'Finish' => 'bg-info',
                        'Cancelled' => 'bg-secondary',
                    ];
                    $statusLabel = $lead->statusText();
                    $badgeClass = $statusClasses[$statusLabel] ?? 'bg-secondary';
                    
                    // Calcular días desde último contacto
                    $lastContactDays = $lead->last_touched_at ? $lead->last_touched_at->diffInDays(now()) : 999;
                    $contactBadgeClass = $lastContactDays > 30 ? 'bg-warning' : ($lastContactDays > 7 ? 'bg-info' : 'bg-success');
                @endphp

                <div class="col-md-6 col-xl-4 mb-4 lead-item" 
                     data-status="{{ strtolower($lead->statusText()) }}" 
                     data-assigned="{{ $lead->team_id ? 'yes' : 'no' }}"
                     data-seller="{{ $lead->team_id }}"
                     data-last-contact="{{ $lastContactDays }}"
                     data-amount="{{ $lead->amount ?? 0 }}">

                    <div class="card shadow-sm border-0 rounded-4 lead-card h-100 overflow-hidden">

                        <!-- HEADER MEJORADO -->
                        <div class="card-header bg-white border-0 rounded-top-4 py-3 position-relative">
                            <!-- BADGE DE PRIORIDAD -->
                            @if($lastContactDays > 30)
                                <span class="position-absolute top-0 start-0 translate-middle badge bg-danger rounded-pill">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </span>
                            @endif

                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="fw-semibold mb-1 text-dark">
                                        {{ $lead->first_name }} {{ $lead->last_name }}
                                    </h5>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge {{ $badgeClass }} text-white">{{ $statusLabel }}</span>
                                        <span class="badge {{ $contactBadgeClass }} text-white">
                                            <i class="bi bi-clock"></i> {{ $lastContactDays }}d
                                        </span>
                                        @if($lead->team)
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-person"></i> {{ $lead->team->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- MENU MEJORADO (SOLO EDIT Y VIEW) -->
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                       
                                        <li>
                                            <a class="dropdown-item text-warning" href="{{ route('manager.manage', $lead->id) }}">
                                                <i class="bi bi-eye me-2"></i> View Details
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- BODY MEJORADO -->
                        <div class="card-body">
                            <div class="lead-info">
                                <!-- INFORMACIÓN DE CONTACTO -->
                                <div class="contact-info mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-telephone text-success me-2"></i>
                                        <span class="small">{{ $lead->phone }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope text-danger me-2"></i>
                                        <span class="small text-truncate">{{ $lead->email }}</span>
                                    </div>
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="bi bi-geo-alt text-warning me-2 mt-1"></i>
                                        <span class="small">
                                            {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
                                        </span>
                                    </div>
                                </div>

                                <!-- INFORMACIÓN ADICIONAL -->
                                <div class="additional-info border-top pt-3">
                                    <div class="row small text-muted">
                                        <div class="col-6">
                                            <strong>Created:</strong><br>
                                            {{ $lead->created_at->format('M j, Y') }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Last Touch:</strong><br>
                                            {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- EMPTY STATE MEJORADO -->
                <div class="col-12">
                    <div class="empty-state text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                        <h4 class="text-muted">No leads found</h4>
                        <p class="text-muted mb-4">There are currently no leads matching your criteria.</p>
                        <button class="btn btn-primary reset-filters-empty">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filters
                        </button>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- LOADING INDICATOR MEJORADO -->
        <div id="loadingIndicator" class="loading-overlay" style="display: none;">
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Filtering leads...</p>
            </div>
        </div>
    </div>

    <!-- PAGINATION MEJORADO -->
    @if(count($leads) > 0)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} results
            </div>
            <div>
                {{ $leads->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

<style>
    :root {
        --primary-color: #3b82f6;
        --secondary-color: #6b7280;
        --success-color: #22c55e;
        --warning-color: #fbbf24;
        --danger-color: #ef4444;
        --orange-color: #fb923c;
        --info-color: #06b6d4;
        --light-bg: #f9fafb;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    body {
        background-color: #f8fafc;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    /* PIPELINE MEJORADO */
    .pipeline-container {
        position: relative;
        overflow-x: auto;
        padding-bottom: 15px;
    }
    
    .pipeline-scroll {
        display: flex;
        justify-content: space-between;
        min-width: 750px;
        padding: 0 20px;
    }
    
    .status-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        position: relative;
        border: 3px solid transparent;
    }

    .status-circle:hover {
        transform: scale(1.15) translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .status-circle.selected-status {
        border: 3px solid #1f2937;
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    }

    .status-pulse {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 16px;
        height: 16px;
        background: #191931ab;
        border-radius: 50%;
        border: 2px solid;
        display: none;
    }

    .status-circle.selected-status .status-pulse {
        display: block;
    }

    .pipeline-item:not(:last-child) .status-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 50px;
        height: 2px;
        z-index: -1;
    }

    .pipeline-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0.75rem;
        transition: transform 0.3s ease;
        flex: 1;
        position: relative;
    }

    .status-count {
        margin-top: 0.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
    }

    .status-amount {
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* CARDS MEJORADAS */
    .lead-card {
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #ffffff 0%, #fdfefe 100%);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid #f1f5f9;
    }

    .lead-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--hover-shadow);
        border-color: #e2e8f0;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
    }

    /* FILTROS MEJORADOS */
    .search-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .search-container:focus-within {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .form-select {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: var(--primary-color);
    }

    /* BADGES MEJORADOS */
    .badge {
        font-size: 0.7em;
        font-weight: 600;
        padding: 0.35em 0.65em;
    }

    /* LOADING MEJORADO */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        border-radius: 16px;
        backdrop-filter: blur(5px);
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* EMPTY STATE MEJORADO */
    .empty-state {
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 20px;
        border: 2px dashed #e2e8f0;
    }

    /* COLORES MEJORADOS */
    .bg-warning  { background: linear-gradient(135deg, var(--warning-color), #f59e0b) !important; }
    .bg-orange   { background: linear-gradient(135deg, var(--orange-color), #ea580c) !important; }
    .bg-success  { background: linear-gradient(135deg, var(--success-color), #16a34a) !important; }
    .bg-primary  { background: linear-gradient(135deg, var(--primary-color), #1d4ed8) !important; }
    .bg-danger   { background: linear-gradient(135deg, var(--danger-color), #dc2626) !important; }
    .bg-info     { background: linear-gradient(135deg, var(--info-color), #0891b2) !important; }
    .bg-secondary { background: linear-gradient(135deg, var(--secondary-color), #4b5563) !important; }

    /* BOTONES MEJORADOS */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-sm {
        border-radius: 8px;
    }

    /* RESPONSIVE MEJORADO */
    @media (max-width: 768px) {
        .pipeline-scroll {
            min-width: 650px;
            padding: 0 10px;
        }
        
        .status-circle {
            width: 60px;
            height: 60px;
        }
        
        .pipeline-item:not(:last-child) .status-circle::after {
            width: 35px;
        }
        
        .card-header {
            padding: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }

    /* ANIMACIONES */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .lead-item {
        animation: fadeIn 0.5s ease;
    }

    /* FILTROS ACTIVOS */
    .selected-filters-container {
        background: #f1f5f9;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    /* TOOLTIPS */
    .tooltip {
        font-size: 0.8rem;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectedStatuses = new Set();
    let currentFilters = {
        search: '',
        status: 'all',
        assignment: 'all',
        seller: 'all',
        lastContact: 'all',
        amount: 'all'
    };
    let debounceTimer;

    // INICIALIZAR TOOLTIPS
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ELEMENTOS DEL DOM
    const elements = {
        searchInput: document.getElementById("searchInput"),
        statusFilter: document.getElementById("statusFilter"),
        assignmentFilter: document.getElementById("assignmentFilter"),
        sellerFilter: document.getElementById("sellerFilter"),
        lastContactFilter: document.getElementById("lastContactFilter"),
        amountFilter: document.getElementById("amountFilter"),
        selectAllBtn: document.getElementById("selectAllBtn"),
        resetFiltersBtn: document.getElementById("resetFiltersBtn"),
        resultsCounter: document.getElementById("resultsCounter"),
        loadingIndicator: document.getElementById("loadingIndicator"),
        activeFiltersText: document.getElementById("activeFiltersText"),
        clearSearch: document.querySelector('.clear-search'),
        toggleAdvanced: document.querySelector('.toggle-advanced-filters'),
        advancedFilters: document.querySelector('.advanced-filters'),
        quickFilters: document.querySelectorAll('.quick-filter'),
        resetEmpty: document.querySelector('.reset-filters-empty')
    };

    // EVENT LISTENERS
    if (elements.searchInput) {
        elements.searchInput.addEventListener("keyup", (e) => {
            currentFilters.search = e.target.value;
            if (elements.clearSearch) {
                elements.clearSearch.style.display = e.target.value ? 'block' : 'none';
            }
            debounceFilterLeads();
        });
    }

    if (elements.clearSearch) {
        elements.clearSearch.addEventListener("click", () => {
            elements.searchInput.value = '';
            currentFilters.search = '';
            elements.clearSearch.style.display = 'none';
            filterLeads();
        });
    }

    // Listeners para filtros select
    ['statusFilter', 'assignmentFilter', 'sellerFilter', 'lastContactFilter', 'amountFilter'].forEach(filterId => {
        if (elements[filterId]) {
            elements[filterId].addEventListener("change", (e) => {
                currentFilters[filterId.replace('Filter', '').toLowerCase()] = e.target.value;
                filterLeads();
            });
        }
    });

    // Listeners para botones
    if (elements.selectAllBtn) elements.selectAllBtn.addEventListener("click", toggleSelectAll);
    if (elements.resetFiltersBtn) elements.resetFiltersBtn.addEventListener("click", resetFilters);
    if (elements.resetEmpty) elements.resetEmpty.addEventListener("click", resetFilters);

    // Filtros avanzados
    if (elements.toggleAdvanced) {
        elements.toggleAdvanced.addEventListener("click", (e) => {
            e.preventDefault();
            const isVisible = elements.advancedFilters.style.display !== 'none';
            elements.advancedFilters.style.display = isVisible ? 'none' : 'block';
            elements.toggleAdvanced.innerHTML = isVisible ? 
                '<i class="bi bi-chevron-down"></i> Advanced Filters' : 
                '<i class="bi bi-chevron-up"></i> Hide Filters';
        });
    }

    // Filtros rápidos
    elements.quickFilters.forEach(filter => {
        filter.addEventListener("click", (e) => {
            e.preventDefault();
            const filterType = e.target.dataset.filter;
            applyQuickFilter(filterType);
        });
    });

    // STATUS CIRCLES
    document.querySelectorAll(".status-circle").forEach(circle => {
        circle.addEventListener("click", () => {
            const status = circle.dataset.status;
            toggleStatusFilter(status, circle);
        });
    });

    // FUNCIONES DE FILTRADO
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
    }

    function applyQuickFilter(type) {
        switch(type) {
            case 'active':
                selectedStatuses.clear();
                ['leads', 'prospect', 'approved'].forEach(status => selectedStatuses.add(status));
                document.querySelectorAll(".status-circle").forEach(circle => {
                    circle.classList.toggle("selected-status", selectedStatuses.has(circle.dataset.status));
                });
                break;
            case 'assigned':
                currentFilters.assignment = 'assigned';
                elements.assignmentFilter.value = 'assigned';
                break;
            case 'unassigned':
                currentFilters.assignment = 'unassigned';
                elements.assignmentFilter.value = 'unassigned';
                break;
            case 'reset':
                resetFilters();
                return;
        }
        updateSelectAllButton();
        filterLeads();
    }

    function filterLeads() {
        showLoading();
        
        const leads = document.querySelectorAll(".lead-item");
        let visibleCount = 0;

        leads.forEach(lead => {
            const leadText = lead.innerText.toLowerCase();
            const leadStatus = lead.dataset.status;
            const assigned = lead.dataset.assigned === "yes";
            const seller = lead.dataset.seller;
            const lastContact = parseInt(lead.dataset.lastContact);
            const amount = parseFloat(lead.dataset.amount);

            // Aplicar todos los filtros
            const matchSearch = !currentFilters.search || leadText.includes(currentFilters.search.toLowerCase());
            const matchStatus = currentFilters.status === 'all' || leadStatus === currentFilters.status;
            const matchSelectedStatuses = selectedStatuses.size === 0 || selectedStatuses.has(leadStatus);
            const matchAssignment = currentFilters.assignment === 'all' || 
                                  (currentFilters.assignment === 'assigned' && assigned) ||
                                  (currentFilters.assignment === 'unassigned' && !assigned);
            const matchSeller = currentFilters.seller === 'all' || seller === currentFilters.seller;
            const matchLastContact = filterLastContact(lastContact);
            const matchAmount = filterAmount(amount);

            const shouldShow = matchSearch && matchStatus && matchSelectedStatuses && 
                             matchAssignment && matchSeller && matchLastContact && matchAmount;
            
            lead.style.display = shouldShow ? "block" : "none";
            if (shouldShow) visibleCount++;
        });

        updateResultsCounter(visibleCount, leads.length);
        updateActiveFiltersText();
        hideLoading();
    }

    function filterLastContact(days) {
        switch(currentFilters.lastContact) {
            case 'today': return days === 0;
            case 'week': return days <= 7;
            case 'month': return days <= 30;
            case 'older': return days > 30;
            default: return true;
        }
    }

    function filterAmount(amount) {
        switch(currentFilters.amount) {
            case '0-1000': return amount > 0 && amount <= 1000;
            case '1000-5000': return amount > 1000 && amount <= 5000;
            case '5000+': return amount > 5000;
            default: return true;
        }
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
        currentFilters = {
            search: '',
            status: 'all',
            assignment: 'all',
            seller: 'all',
            lastContact: 'all',
            amount: 'all'
        };
        
        // Resetear valores de los inputs
        if (elements.searchInput) elements.searchInput.value = '';
        if (elements.clearSearch) elements.clearSearch.style.display = 'none';
        if (elements.statusFilter) elements.statusFilter.value = 'all';
        if (elements.assignmentFilter) elements.assignmentFilter.value = 'all';
        if (elements.sellerFilter) elements.sellerFilter.value = 'all';
        if (elements.lastContactFilter) elements.lastContactFilter.value = 'all';
        if (elements.amountFilter) elements.amountFilter.value = 'all';
        
        document.querySelectorAll(".status-circle").forEach(circle => 
            circle.classList.remove("selected-status")
        );
        
        updateSelectAllButton();
        filterLeads();
    }

    // FUNCIONES UTILITARIAS
    function updateSelectAllButton() {
        const allCircles = document.querySelectorAll(".status-circle");
        if (elements.selectAllBtn) {
            elements.selectAllBtn.innerHTML = selectedStatuses.size === allCircles.length ? 
                '<i class="bi bi-x-circle"></i> Deselect All' : 
                '<i class="bi bi-check-all"></i> Select All';
        }
    }

    function updateResultsCounter(visible, total) {
        if (elements.resultsCounter) {
            elements.resultsCounter.textContent = `${visible} of ${total} leads`;
            elements.resultsCounter.className = `text-muted small badge ${visible === 0 ? 'bg-warning text-dark' : 'bg-light text-dark'}`;
        }
    }

    function updateActiveFiltersText() {
        if (elements.activeFiltersText) {
            const activeFilters = [];
            if (currentFilters.search) activeFilters.push('search');
            if (currentFilters.status !== 'all') activeFilters.push('status');
            if (currentFilters.assignment !== 'all') activeFilters.push('assignment');
            if (currentFilters.seller !== 'all') activeFilters.push('seller');
            if (selectedStatuses.size > 0) activeFilters.push(`${selectedStatuses.size} statuses`);
            
            elements.activeFiltersText.textContent = activeFilters.length > 0 ? 
                `Active: ${activeFilters.join(', ')}` : 
                'No active filters';
        }
    }

    function showLoading() {
        if (elements.loadingIndicator) {
            elements.loadingIndicator.style.display = 'flex';
        }
    }

    function hideLoading() {
        if (elements.loadingIndicator) {
            elements.loadingIndicator.style.display = 'none';
        }
    }

    function debounceFilterLeads() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(filterLeads, 400);
    }

    // INICIALIZACIÓN
    filterLeads();
    updateSelectAllButton();
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