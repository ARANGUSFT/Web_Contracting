@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-1">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>My Assigned Leads
                    </h1>
                    <p class="text-muted mb-0 d-none d-md-block">Manage and track your sales pipeline</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2 align-items-start align-items-sm-center w-100 w-md-auto">
                    <span class="badge bg-primary fs-6 p-2 mb-2 mb-sm-0">
                        <i class="bi bi-people me-1"></i>Total: {{ $leads->total() }}
                    </span>
                    <div class="view-toggle">
                        <button class="view-btn active" id="cardViewBtn">
                            <i class="bi bi-grid-3x3-gap me-1"></i>Cards
                        </button>
                        <button class="view-btn" id="tableViewBtn">
                            <i class="bi bi-list-task me-1"></i>Table
                        </button>
                    </div>
                </div>
            </div>
            <hr class="my-3">
        </div>
    </div>

    <!-- Status Pipeline Horizontal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm status-pipeline">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bi bi-funnel me-2 text-primary"></i>Sales Pipeline
                        <small class="text-muted ms-2 d-none d-md-inline">(Click to filter)</small>
                    </h5>
                </div>
                <div class="card-body p-0 p-md-3">
                    <div class="status-track-container">
                        <div class="status-track">
                            @php
                                $statusConfig = [
                                    'leads' => ['L', 'bg-warning', 'Lead', 'bi-person', 1],
                                    'prospect' => ['P', 'bg-orange', 'Prospect', 'bi-eye', 2],
                                    'approved' => ['A', 'bg-success', 'Approved', 'bi-check-circle', 3],
                                    'completed' => ['C', 'bg-primary', 'Completed', 'bi-flag', 4],
                                    'invoiced' => ['I', 'bg-danger', 'Invoiced', 'bi-receipt', 5],
                                    'finish' => ['F', 'bg-secondary', 'Finish', 'bi-check-all', 6],
                                    'cancelled' => ['X', 'bg-dark', 'Cancelled', 'bi-x-circle', 7]
                                ];
                            @endphp
                            
                            @foreach ($statusConfig as $key => [$letter, $color, $label, $icon, $estado])
                            <div class="status-item" data-status="{{ $estado }}" onclick="toggleStatusFilter({{ $estado }})">
                                <div class="status-indicator {{ $color }}">
                                    <i class="bi {{ $icon }} text-white"></i>
                                </div>
                                <div class="status-count">{{ $statusCounts[$key] ?? 0 }}</div>
                                <div class="status-label">{{ $label }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm filters-section">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label small text-muted mb-1">Search by Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       id="searchName" 
                                       class="form-control border-start-0" 
                                       placeholder="Enter client name..."
                                       onkeyup="debounceFilterLeads()">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label small text-muted mb-1">Search by Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-telephone text-muted"></i>
                                </span>
                                <input type="text" 
                                       id="searchPhone" 
                                       class="form-control border-start-0" 
                                       placeholder="Enter phone number..."
                                       onkeyup="debounceFilterLeads()">
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <label class="form-label small text-muted mb-1">Active Filters</label>
                            <div class="active-filters">
                                <span class="badge bg-light text-dark border" id="defaultFilterText">No filters applied</span>
                                <div id="activeFilters" class="d-none d-flex flex-wrap gap-2"></div>
                                <button class="btn btn-sm btn-outline-secondary mt-2 mt-lg-0 ms-lg-2" onclick="clearAllFilters()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Cards View -->
    <div id="cardsView">
        @if($leads->isEmpty())
        <div class="text-center py-5 empty-state">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No leads assigned</h4>
            <p class="text-muted">You don't have any leads in your pipeline yet.</p>
        </div>
        @else
        <div class="row" id="leadsGrid">
            @foreach ($leads as $lead)
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3 lead-card-container" data-status="{{ $lead->estado }}" data-name="{{ strtolower($lead->first_name . ' ' . $lead->last_name) }}">
                <div class="lead-card h-100">
                    <div class="lead-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <div class="client-avatar me-3">
                                    {{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 lead-name">{{ $lead->first_name }} {{ $lead->last_name }}</h6>
                                    <small class="text-muted">Added: {{ $lead->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @php
                                $status = $statusMap[$lead->estado] ?? ['name' => 'Unknown', 'color' => 'bg-secondary'];
                            @endphp
                            <span class="badge-status {{ $status['color'] }}">{{ $status['name'] }}</span>
                        </div>
                    </div>
                    <div class="lead-body">
                        <div class="contact-info">
                            @if($lead->phone)
                            <div class="contact-item">
                                <i class="bi bi-telephone"></i>
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-muted text-truncate d-block">{{ $lead->phone }}</a>
                            </div>
                            @else
                            <div class="contact-item">
                                <i class="bi bi-telephone"></i>
                                <span class="text-muted">No phone</span>
                            </div>
                            @endif
                            
                            @if($lead->email)
                            <div class="contact-item">
                                <i class="bi bi-envelope"></i>
                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-muted text-truncate d-block">{{ $lead->email }}</a>
                            </div>
                            @else
                            <div class="contact-item">
                                <i class="bi bi-envelope"></i>
                                <span class="text-muted">No email</span>
                            </div>
                            @endif
                            
                            <div class="contact-item">
                                <i class="bi bi-calendar"></i>
                                <small class="text-muted">Updated: {{ $lead->updated_at->format('M j, Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="lead-footer">
                        <small class="text-muted d-none d-sm-block">Last: {{ $lead->updated_at->diffForHumans() }}</small>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('seller.leads.show', $lead->id) }}" 
                               class="btn btn-outline-primary" 
                               data-bs-toggle="tooltip" 
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('seller.leads.edit', $lead->id) }}" 
                               class="btn btn-outline-secondary" 
                               data-bs-toggle="tooltip" 
                               title="Edit Lead">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="text-muted small mb-2 mb-md-0">
                                Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} results
                            </div>
                            <nav aria-label="Lead pagination" class="mt-2 mt-md-0">
                                {{ $leads->onEachSide(1)->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>

    <!-- Table View (Hidden by Default) -->
    <div id="tableView" class="d-none">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @if($leads->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h4 class="text-muted mt-3">No leads assigned</h4>
                            <p class="text-muted">You don't have any leads in your pipeline yet.</p>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 ps-md-4">Client</th>
                                        <th class="d-none d-lg-table-cell">Contact</th>
                                        <th>Status</th>
                                        <th class="d-none d-md-table-cell">Last Updated</th>
                                        <th class="text-end pe-3 pe-md-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="leadsTableBody">
                                    @foreach ($leads as $lead)
                                    <tr class="lead-row" data-status="{{ $lead->estado }}" data-name="{{ strtolower($lead->first_name . ' ' . $lead->last_name) }}">
                                        <td class="ps-3 ps-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="bi bi-person text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong class="d-block">{{ $lead->first_name }} {{ $lead->last_name }}</strong>
                                                    <small class="text-muted d-block d-lg-none">
                                                        <i class="bi bi-telephone me-1"></i>{{ $lead->phone ?? 'No phone' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <div>
                                                @if($lead->phone)
                                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-muted d-block mb-1">
                                                    <i class="bi bi-telephone me-2"></i>{{ $lead->phone }}
                                                </a>
                                                @else
                                                <span class="text-muted d-block mb-1">
                                                    <i class="bi bi-telephone me-2"></i>No phone
                                                </span>
                                                @endif
                                                
                                                @if($lead->email)
                                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-muted d-block">
                                                    <i class="bi bi-envelope me-2"></i>{{ $lead->email }}
                                                </a>
                                                @else
                                                <span class="text-muted d-block">
                                                    <i class="bi bi-envelope me-2"></i>No email
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $status = $statusMap[$lead->estado] ?? ['name' => 'Unknown', 'color' => 'bg-secondary'];
                                            @endphp
                                            <span class="badge {{ $status['color'] }} rounded-pill px-3 py-2">
                                                {{ $status['name'] }}
                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <small class="text-muted">
                                                {{ $lead->updated_at->format('M j, Y') }}
                                            </small>
                                        </td>
                                        <td class="text-end pe-3 pe-md-4">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('seller.leads.show', $lead->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('seller.leads.edit', $lead->id) }}" 
                                                   class="btn btn-outline-secondary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Lead">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination for Table View -->
                        @if($leads->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                <div class="text-muted small mb-2 mb-md-0">
                                    Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} results
                                </div>
                                <nav aria-label="Lead pagination">
                                    {{ $leads->onEachSide(1)->links() }}
                                </nav>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State for Filtering -->
    <div id="emptyState" class="d-none">
        <div class="text-center py-5 empty-state">
            <i class="bi bi-search display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No leads found</h4>
            <p class="text-muted">Try adjusting your search or filters</p>
            <button class="btn btn-primary mt-2" onclick="clearAllFilters()">Clear All Filters</button>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #007bff;
    --warning-color: #ffc107;
    --success-color: #198754;
    --danger-color: #dc3545;
    --orange-color: #fd7e14;
    --secondary-color: #6c757d;
    --dark-color: #343a40;
}

/* Status Pipeline Horizontal - Mejorado para Responsive */
.status-pipeline {
    border-radius: 16px;
}

.status-track-container {
    overflow-x: auto;
    padding: 0.5rem;
    -webkit-overflow-scrolling: touch;
}

.status-track {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: min(100%, 800px);
    padding: 0.5rem 0;
    margin: 0 auto;
}

.status-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: 12px;
    flex: 1;
    min-width: 70px;
    max-width: 100px;
}

.status-item:hover {
    transform: translateY(-2px);
    background-color: rgba(0, 0, 0, 0.03);
}

.status-item.active {
    background-color: rgba(0, 123, 255, 0.1);
}

.status-indicator {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
    color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.status-count {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--dark-color);
    line-height: 1;
}

.status-label {
    font-size: 0.75rem;
    text-align: center;
    color: var(--secondary-color);
    font-weight: 500;
    line-height: 1.2;
}

/* Lead Cards - Mejorado para Responsive */
.lead-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary-color);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.lead-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.lead-header {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
}

.lead-body {
    padding: 1rem;
    flex-grow: 1;
}

.lead-footer {
    padding: 0.75rem 1rem;
    background-color: #f9fafc;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.client-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), #3a56d4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
}

.lead-name {
    font-size: 0.95rem;
    line-height: 1.3;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    color: var(--secondary-color);
    font-size: 0.875rem;
}

.contact-item i {
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.badge-status {
    padding: 0.4rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    color: white;
    white-space: nowrap;
}

/* Filters Section - Mejorado para Responsive */
.filters-section {
    border-radius: 12px;
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.filter-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: white;
    white-space: nowrap;
}

.view-toggle {
    display: flex;
    background: #f1f3f9;
    border-radius: 10px;
    padding: 0.25rem;
    width: 100%;
    max-width: 250px;
}

.view-btn {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: none;
    background: transparent;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
    flex: 1;
    white-space: nowrap;
}

.view-btn.active {
    background: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--secondary-color);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Status Colors (Conservando los originales) */
.bg-orange {
    background-color: var(--orange-color) !important;
}

.bg-dark {
    background-color: var(--dark-color) !important;
}

/* Responsive Breakpoints Mejorados */
@media (max-width: 1400px) {
    .lead-card-container {
        padding: 0.5rem;
    }
}

@media (max-width: 1200px) {
    .status-indicator {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .status-count {
        font-size: 1.1rem;
    }
    
    .status-label {
        font-size: 0.7rem;
    }
}

@media (max-width: 992px) {
    .status-track {
        min-width: min(100%, 700px);
    }
    
    .status-item {
        min-width: 65px;
        padding: 0.4rem;
    }
    
    .client-avatar {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .lead-header,
    .lead-body,
    .lead-footer {
        padding: 0.875rem;
    }
}

@media (max-width: 768px) {
    .status-pipeline, .filters-section {
        border-radius: 10px;
    }
    
    .status-track-container {
        padding: 0.25rem;
    }
    
    .status-indicator {
        width: 40px;
        height: 40px;
        font-size: 1rem;
        margin-bottom: 0.4rem;
    }
    
    .status-count {
        font-size: 1rem;
    }
    
    .status-label {
        font-size: 0.65rem;
    }
    
    .view-toggle {
        max-width: 100%;
    }
    
    .lead-name {
        font-size: 0.9rem;
    }
    
    .contact-item {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .status-item {
        min-width: 60px;
        padding: 0.3rem;
    }
    
    .status-indicator {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .status-count {
        font-size: 0.9rem;
    }
    
    .lead-header,
    .lead-body,
    .lead-footer {
        padding: 0.75rem;
    }
    
    .client-avatar {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
        margin-right: 0.75rem;
    }
    
    .badge-status {
        padding: 0.3rem 0.6rem;
        font-size: 0.7rem;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .empty-state i {
        font-size: 2.5rem;
    }
    
    .empty-state h4 {
        font-size: 1.25rem;
    }
}

@media (max-width: 400px) {
    .status-track {
        min-width: min(100%, 500px);
    }
    
    .status-item {
        min-width: 55px;
    }
    
    .status-indicator {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    .col-12.col-sm-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .lead-card,
    .status-item,
    .view-btn {
        transition: none;
    }
}

/* Scrollbar personalizado para el pipeline */
.status-track-container::-webkit-scrollbar {
    height: 6px;
}

.status-track-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.status-track-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.status-track-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
// Estado global de la aplicación
const appState = {
    activeFilters: new Set(),
    currentView: 'cards',
    debounceTimer: null
};

// Configuración de estados
const statusConfig = {
    1: { label: 'Lead', class: 'bg-warning' },
    2: { label: 'Prospect', class: 'bg-orange' },
    3: { label: 'Approved', class: 'bg-success' },
    4: { label: 'Completed', class: 'bg-primary' },
    5: { label: 'Invoiced', class: 'bg-danger' },
    6: { label: 'Finish', class: 'bg-secondary' },
    7: { label: 'Cancelled', class: 'bg-dark' }
};

// Debounce para mejorar rendimiento
function debounceFilterLeads() {
    clearTimeout(appState.debounceTimer);
    appState.debounceTimer = setTimeout(filterLeads, 300);
}

// Función principal de filtrado
function filterLeads() {
    const searchName = document.getElementById('searchName').value.toLowerCase();
    const searchPhone = document.getElementById('searchPhone').value.toLowerCase();
    
    let visibleCount = 0;
    const containers = document.querySelectorAll('.lead-card-container, .lead-row');
    
    containers.forEach(container => {
        const leadName = container.getAttribute('data-name');
        const leadStatus = container.getAttribute('data-status');
        
        const matchesName = !searchName || leadName.includes(searchName);
        const matchesStatus = appState.activeFilters.size === 0 || appState.activeFilters.has(leadStatus);
        const matchesPhone = !searchPhone || container.textContent.toLowerCase().includes(searchPhone);
        
        if (matchesName && matchesPhone && matchesStatus) {
            container.style.display = appState.currentView === 'cards' ? 'block' : '';
            visibleCount++;
        } else {
            container.style.display = 'none';
        }
    });
    
    updateActiveFiltersDisplay();
    updateEmptyState(visibleCount);
}

// Manejo de filtros por estado
function toggleStatusFilter(estado) {
    const item = document.querySelector(`.status-item[data-status="${estado}"]`);
    
    if (appState.activeFilters.has(estado.toString())) {
        appState.activeFilters.delete(estado.toString());
        item.classList.remove('active');
    } else {
        appState.activeFilters.add(estado.toString());
        item.classList.add('active');
    }
    
    filterLeads();
}

// Actualizar visualización de filtros activos
function updateActiveFiltersDisplay() {
    const container = document.getElementById('activeFilters');
    const defaultText = document.getElementById('defaultFilterText');
    container.innerHTML = '';
    
    if (appState.activeFilters.size === 0) {
        defaultText.classList.remove('d-none');
        container.classList.add('d-none');
        return;
    }
    
    defaultText.classList.add('d-none');
    container.classList.remove('d-none');
    
    appState.activeFilters.forEach(estado => {
        const statusLabel = statusConfig[parseInt(estado)]?.label || 'Unknown';
        const statusClass = statusConfig[parseInt(estado)]?.class || 'bg-primary';
        
        const badge = document.createElement('span');
        badge.className = `filter-badge ${statusClass}`;
        badge.innerHTML = `${statusLabel} <i class="bi bi-x ms-1" onclick="event.stopPropagation(); removeFilter(${estado})"></i>`;
        container.appendChild(badge);
    });
}

// Remover filtro individual
function removeFilter(estado) {
    appState.activeFilters.delete(estado.toString());
    const item = document.querySelector(`.status-item[data-status="${estado}"]`);
    
    item.classList.remove('active');
    filterLeads();
}

// Limpiar todos los filtros
function clearAllFilters() {
    appState.activeFilters.clear();
    document.querySelectorAll('.status-item').forEach(item => {
        item.classList.remove('active');
    });
    document.getElementById('searchName').value = '';
    document.getElementById('searchPhone').value = '';
    filterLeads();
}

// Manejo de estado vacío
function updateEmptyState(visibleCount) {
    const cardsView = document.getElementById('cardsView');
    const tableView = document.getElementById('tableView');
    const emptyState = document.getElementById('emptyState');
    
    const currentView = appState.currentView === 'cards' ? cardsView : tableView;
    
    if (visibleCount === 0) {
        currentView.classList.add('d-none');
        emptyState.classList.remove('d-none');
    } else {
        currentView.classList.remove('d-none');
        emptyState.classList.add('d-none');
    }
}

// Cambiar vista entre tarjetas y tabla
document.getElementById('cardViewBtn').addEventListener('click', function() {
    this.classList.add('active');
    document.getElementById('tableViewBtn').classList.remove('active');
    appState.currentView = 'cards';
    document.getElementById('cardsView').classList.remove('d-none');
    document.getElementById('tableView').classList.add('d-none');
    filterLeads();
});

document.getElementById('tableViewBtn').addEventListener('click', function() {
    this.classList.add('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    appState.currentView = 'table';
    document.getElementById('tableView').classList.remove('d-none');
    document.getElementById('cardsView').classList.add('d-none');
    filterLeads();
});

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Ajustar pipeline en carga
    adjustPipelineForMobile();
    
    filterLeads();
});

// Ajustar pipeline para móviles
function adjustPipelineForMobile() {
    const pipelineContainer = document.querySelector('.status-track-container');
    const pipeline = document.querySelector('.status-track');
    
    if (pipeline && pipelineContainer) {
        const containerWidth = pipelineContainer.offsetWidth;
        const pipelineWidth = pipeline.scrollWidth;
        
        if (pipelineWidth > containerWidth) {
            pipelineContainer.style.paddingBottom = '1rem';
        }
    }
}

// Reajustar en redimensionamiento
window.addEventListener('resize', function() {
    adjustPipelineForMobile();
});
</script>

@endsection