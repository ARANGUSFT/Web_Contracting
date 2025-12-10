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
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => null, 'assignment' => null, 'page' => null]) }}">Reset All</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['assignment' => 'assigned', 'page' => null]) }}">Assigned Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['assignment' => 'unassigned', 'page' => null]) }}">Unassigned Only</a></li>
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
                            @php
                                $isActive = request('status') == $key;
                                $pipelineUrl = $isActive ? 
                                    request()->fullUrlWithQuery(['status' => null, 'page' => null]) : 
                                    request()->fullUrlWithQuery(['status' => $key, 'page' => null]);
                            @endphp
                            <a href="{{ $pipelineUrl }}" class="text-decoration-none pipeline-link">
                                <div class="pipeline-item text-center position-relative" data-bs-toggle="tooltip" title="{{ $tooltip }}">
                                    <div class="status-circle {{ $color }} {{ $isActive ? 'selected-status' : '' }}">
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
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 🎛️ FILTER CONTROLS MEJORADOS -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise"></i> Reset All
                            </a>
                            <div class="vr"></div>
                            <div class="selected-filters-container">
                                <small class="text-muted">
                                    @php
                                        $activeFilters = [];
                                        if (request('search')) $activeFilters[] = 'Search';
                                        
                                        // Status filter - CORREGIDO PARA MANEJAR ARRAYS
                                        $statuses = request('status', []);
                                        if (!is_array($statuses)) {
                                            $statuses = $statuses ? [$statuses] : [];
                                        }
                                        $validStatuses = array_filter($statuses, function($status) {
                                            return $status !== 'all' && !empty($status);
                                        });
                                        if (count($validStatuses) > 0) {
                                            if (count($validStatuses) === 1) {
                                                $activeFilters[] = 'Status: ' . ucfirst($validStatuses[0]);
                                            } else {
                                                $activeFilters[] = count($validStatuses) . ' statuses';
                                            }
                                        }
                                        
                                        if (request('assignment') && request('assignment') != 'all') $activeFilters[] = 'Assignment: ' . ucfirst(request('assignment'));
                                        if (request('seller') && request('seller') != 'all') $activeFilters[] = 'Seller';
                                        if (request('lastContact') && request('lastContact') != 'all') $activeFilters[] = 'Last Contact';
                                        if (request('amount') && request('amount') != 'all') $activeFilters[] = 'Amount';
                                    @endphp
                                    {{ count($activeFilters) ? 'Active: ' . implode(', ', $activeFilters) : 'No active filters' }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="text-muted small badge bg-light text-dark">
                            {{ $leads->total() }} total leads
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔍 SEARCH + FILTER BAR MEJORADO CON FORM -->
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <!-- SEARCH -->
                        <div class="col-md-4">
                            <div class="input-group search-container">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0" placeholder="Search by name, email, phone, location...">
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                    <!-- IMPROVED STATUS FILTER WITH MULTISELECT -->
                        <div class="col-md-3">
                            <div class="dropdown">
                                <button class="btn btn-light border-0 shadow-sm w-100 text-start dropdown-toggle position-relative" 
                                        type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-funnel me-1"></i> 
                                    <span class="status-filter-text">
                                        @php
                                            $selectedStatuses = request('status', []);
                                            if (!is_array($selectedStatuses)) {
                                                $selectedStatuses = $selectedStatuses ? [$selectedStatuses] : [];
                                            }
                                            $selectedCount = count($selectedStatuses);
                                        @endphp
                                        @if($selectedCount > 0)
                                            {{ $selectedCount }} status(es)
                                        @else
                                            All statuses
                                        @endif
                                    </span>
                                    @if($selectedCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                            {{ $selectedCount }}
                                        </span>
                                    @endif
                                </button>
                                <div class="dropdown-menu shadow-lg p-3" style="width: 280px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-dark fw-semibold">Filter by Status</h6>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllStatus">
                                                All
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="clearAllStatus">
                                                None
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="status-checkboxes" style="max-height: 200px; overflow-y: auto;">
                                        @php
                                            $statusOptions = [
                                                'leads' => ['label' => 'Lead', 'color' => 'bg-warning'],
                                                'prospect' => ['label' => 'Prospect', 'color' => 'bg-orange'],
                                                'approved' => ['label' => 'Approved', 'color' => 'bg-success'],
                                                'completed' => ['label' => 'Completed', 'color' => 'bg-primary'],
                                                'invoiced' => ['label' => 'Invoiced', 'color' => 'bg-danger'],
                                                'finish' => ['label' => 'Finish', 'color' => 'bg-info'],
                                                'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-secondary'],
                                            ];
                                        @endphp
                                        
                                        @foreach($statusOptions as $value => $option)
                                        <div class="form-check status-option py-1">
                                            <input class="form-check-input status-checkbox" type="checkbox" 
                                                name="status[]" value="{{ $value }}" 
                                                id="status_{{ $value }}"
                                                {{ in_array($value, $selectedStatuses) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100 d-flex align-items-center py-1" for="status_{{ $value }}">
                                                <span class="badge {{ $option['color'] }} me-2" style="width: 12px; height: 12px; border-radius: 50%;"></span>
                                                <span class="flex-grow-1 small">{{ $option['label'] }}</span>
                                                <span class="badge bg-light text-dark border small">
                                                    {{ $statusCounts[$value] ?? 0 }}
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="border-top pt-2 mt-2">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" id="applyStatusFilter">
                                                <i class="bi bi-check-lg me-1"></i> Apply Filters
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearStatusFilter()">
                                                <i class="bi bi-x-circle me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- ASSIGNMENT FILTER -->
                        <div class="col-md-3">
                            <select name="assignment" class="form-select border-0 shadow-sm">
                                <option value="all">All Assignments</option>
                                <option value="assigned" {{ request('assignment') == 'assigned' ? 'selected' : '' }}>Assigned Only</option>
                                <option value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Unassigned Only</option>
                            </select>
                        </div>

                    
                    </div>

                    <!-- FILTROS AVANZADOS -->
                    <div class="row mt-3 advanced-filters" style="display: {{ request('lastContact') || request('amount') ? 'block' : 'none' }};">
                        <div class="col-12">
                            <div class="border-top pt-3">
                                <h6 class="text-muted mb-2">Advanced Filters</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small">Last Contact</label>
                                        <select class="form-select form-select-sm" name="lastContact">
                                            <option value="all">Any Time</option>
                                            <option value="today" {{ request('lastContact') == 'today' ? 'selected' : '' }}>Today</option>
                                            <option value="week" {{ request('lastContact') == 'week' ? 'selected' : '' }}>This Week</option>
                                            <option value="month" {{ request('lastContact') == 'month' ? 'selected' : '' }}>This Month</option>
                                            <option value="older" {{ request('lastContact') == 'older' ? 'selected' : '' }}>Older than Month</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Amount Range</label>
                                        <select class="form-select form-select-sm" name="amount">
                                            <option value="all">Any Amount</option>
                                            <option value="0-1000" {{ request('amount') == '0-1000' ? 'selected' : '' }}>$0 - $1,000</option>
                                            <option value="1000-5000" {{ request('amount') == '1000-5000' ? 'selected' : '' }}>$1,000 - $5,000</option>
                                            <option value="5000+" {{ request('amount') == '5000+' ? 'selected' : '' }}>$5,000+</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 toggle-advanced-filters">
                                <i class="bi bi-chevron-down"></i> Advanced Filters
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-filter"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


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
                        
                        // Formatear información de contacto
                        $location = "{$lead->street} {$lead->suite}, {$lead->city}, {$lead->state} {$lead->zip}";
                    @endphp

                    <div class="col-md-6 col-xl-4 mb-4">

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

                                    <!-- MENU MEJORADO -->
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            
                                            <li>
                                                <a class="dropdown-item text-warning" href="{{ route('project.view', $lead->id) }}">
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
                                            <span class="small text-truncate">{{ $lead->phone }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-envelope text-danger me-2"></i>
                                            <span class="small text-truncate">{{ $lead->email }}</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <i class="bi bi-geo-alt text-warning me-2 mt-1"></i>
                                            <span class="small text-truncate">{{ $location }}</span>
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
                            <a href="{{ request()->url() }}" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Reset Filters
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- PAGINATION MEJORADO CON FILTROS -->
        @if($leads->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} results
                </div>
                <div>
                    {{ $leads->appends(request()->except('page'))->links() }}
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
            --transition-speed: 0.3s;
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
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) #f1f5f9;
        }

        .pipeline-container::-webkit-scrollbar {
            height: 6px;
        }

        .pipeline-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .pipeline-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        
        .pipeline-scroll {
            display: flex;
            justify-content: space-between;
            min-width: min(750px, 100%);
            padding: 0 20px;
            gap: 10px;
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
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 3px solid transparent;
            font-size: 1.2rem;
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
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 1; }
            70% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(0.8); opacity: 1; }
        }

        .status-circle.selected-status .status-pulse {
            display: block;
        }

        .pipeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0.75rem;
            transition: transform var(--transition-speed) ease;
            flex: 1;
            position: relative;
            min-width: 80px;
        }

        .pipeline-link:hover {
            text-decoration: none;
        }

        .pipeline-link:hover .status-circle {
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
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
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: linear-gradient(135deg, #ffffff 0%, #fdfefe 100%);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid #f1f5f9;
        }

        .lead-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--hover-shadow);
            border-color: #e2e8f0;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            position: relative;
            z-index: 1;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, transparent 100%);
            z-index: -1;
        }

        /* FILTROS MEJORADOS */
        .search-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all var(--transition-speed) ease;
            position: relative;
        }

        .search-container:focus-within {
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .form-select {
            border-radius: 10px;
            transition: all var(--transition-speed) ease;
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
            transition: all var(--transition-speed) ease;
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-sm {
            border-radius: 8px;
        }

        /* EFECTOS DE CARGA */
        .loading-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* RESPONSIVE MEJORADO */
        @media (max-width: 768px) {
            .pipeline-scroll {
                min-width: 650px;
                padding: 0 10px;
                gap: 5px;
            }
            
            .status-circle {
                width: 60px;
                height: 60px;
                font-size: 1rem;
            }
            
            .pipeline-item {
                margin: 0.5rem;
                min-width: 70px;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .pipeline-scroll {
                min-width: 550px;
            }
            
            .status-circle {
                width: 50px;
                height: 50px;
                font-size: 0.9rem;
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
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* TOOLTIPS */
        .tooltip {
            font-size: 0.8rem;
        }

        /* TEXT TRUNCATION */
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>



    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // INICIALIZAR TOOLTIPS
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // TOGGLE ADVANCED FILTERS
        const toggleAdvanced = document.querySelector('.toggle-advanced-filters');
        const advancedFilters = document.querySelector('.advanced-filters');
        
        if (toggleAdvanced && advancedFilters) {
            toggleAdvanced.addEventListener('click', function() {
                const isVisible = advancedFilters.style.display !== 'none';
                advancedFilters.style.display = isVisible ? 'none' : 'block';
                
                const icon = this.querySelector('i');
                const text = this.querySelector('span') || this;
                
                if (isVisible) {
                    icon.className = 'bi bi-chevron-down';
                    if (text.textContent.includes('Hide')) {
                        text.innerHTML = '<i class="bi bi-chevron-down"></i> Advanced Filters';
                    }
                } else {
                    icon.className = 'bi bi-chevron-up';
                    if (text.textContent.includes('Advanced')) {
                        text.innerHTML = '<i class="bi bi-chevron-up"></i> Hide Filters';
                    }
                }
            });
        }

        // MULTISELECT DE ESTADOS MEJORADO
        const statusDropdown = document.getElementById('statusDropdown');
        const statusCheckboxes = document.querySelectorAll('.status-checkbox');
        const selectAllStatus = document.getElementById('selectAllStatus');
        const clearAllStatus = document.getElementById('clearAllStatus');
        const applyStatusFilter = document.getElementById('applyStatusFilter');
        const statusFilterText = document.querySelector('.status-filter-text');

        // Función para actualizar el texto del botón
        function updateStatusFilterText() {
            const checkedBoxes = document.querySelectorAll('.status-checkbox:checked');
            const count = checkedBoxes.length;
            
            if (count === 0) {
                statusFilterText.textContent = 'All status';
            } else if (count === 1) {
                const label = checkedBoxes[0].closest('.status-option').querySelector('.form-check-label .flex-grow-1').textContent.trim();
                statusFilterText.textContent = label;
            } else {
                statusFilterText.textContent = count + ' estado(s)';
            }
            
            // Actualizar badge de conteo
            const badge = statusDropdown.querySelector('.badge');
            if (count > 0) {
                if (!badge) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary';
                    newBadge.textContent = count;
                    statusDropdown.appendChild(newBadge);
                } else {
                    badge.textContent = count;
                }
            } else if (badge) {
                badge.remove();
            }
        }

        // Seleccionar Todos
        if (selectAllStatus) {
            selectAllStatus.addEventListener('click', function() {
                statusCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateStatusFilterText();
            });
        }

        // Limpiar Todos
        if (clearAllStatus) {
            clearAllStatus.addEventListener('click', function() {
                statusCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateStatusFilterText();
            });
        }

        // Aplicar filtro de estados
        if (applyStatusFilter) {
            applyStatusFilter.addEventListener('click', function() {
                // Efecto visual
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Aplicando...';
                this.disabled = true;
                
                // Cerrar dropdown
                const dropdownInstance = bootstrap.Dropdown.getInstance(statusDropdown);
                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
                
                // Enviar formulario
                setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 400);
            });
        }

        // Actualizar texto cuando cambian los checkboxes
        statusCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateStatusFilterText);
        });

        // Inicializar texto al cargar
        updateStatusFilterText();

        // Función global para limpiar filtro de estados
        window.clearStatusFilter = function() {
            statusCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateStatusFilterText();
            
            // Cerrar dropdown y enviar formulario
            const dropdownInstance = bootstrap.Dropdown.getInstance(statusDropdown);
            if (dropdownInstance) {
                dropdownInstance.hide();
            }
            
            setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 300);
        };

        // AUTO-SUBMIT ON FILTER CHANGE - OTROS SELECTS
        const autoSubmitSelects = document.querySelectorAll(
            'select[name="assignment"], select[name="seller"], select[name="lastContact"], select[name="amount"]'
        );
        
        autoSubmitSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.style.opacity = '0.7';
                setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 300);
            });
        });

        // SEARCH DEBOUNCE MEJORADO
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        const searchContainer = document.querySelector('.search-container');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                if (this.value.length > 0) {
                    searchContainer.style.boxShadow = '0 4px 12px rgba(59, 130, 246, 0.2)';
                } else {
                    searchContainer.style.boxShadow = '';
                }
                
                searchTimeout = setTimeout(() => {
                    const submitBtn = document.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalHtml = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Searching...';
                        submitBtn.disabled = true;
                        
                        setTimeout(() => {
                            submitBtn.innerHTML = originalHtml;
                            submitBtn.disabled = false;
                        }, 2000);
                    }
                    
                    document.getElementById('filterForm').submit();
                }, 800);
            });
            
            const clearSearchBtn = searchContainer.querySelector('.btn-outline-secondary');
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchInput.value = '';
                    searchContainer.style.boxShadow = '';
                    document.getElementById('filterForm').submit();
                });
            }
        }

        // MEJORAR INTERACCIONES DE CARDS
        const leadCards = document.querySelectorAll('.lead-card');
        leadCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.transition = 'all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // ANIMACIÓN DE CARGA PARA FILTROS APLICADOS
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.innerHTML.includes('Searching')) {
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Applying...';
                    submitBtn.disabled = true;
                    
                    e.preventDefault();
                    setTimeout(() => {
                        this.submit();
                    }, 100);
                }
            });
        }

        // SCROLL SUAVE PARA PIPELINE
        const pipelineContainer = document.querySelector('.pipeline-container');
        if (pipelineContainer) {
            let isScrolling = false;
            
            pipelineContainer.addEventListener('wheel', (e) => {
                if (!isScrolling) {
                    isScrolling = true;
                    e.preventDefault();
                    pipelineContainer.scrollLeft += e.deltaY * 2;
                    
                    setTimeout(() => {
                        isScrolling = false;
                    }, 50);
                }
            });
        }

        // FEEDBACK VISUAL PARA FILTROS ACTIVOS - ACTUALIZADO PARA MÚLTIPLES ESTADOS
        function updateActiveFiltersVisual() {
            const urlParams = new URLSearchParams(window.location.search);
            const pipelineItems = document.querySelectorAll('.pipeline-item');
            const selectedStatuses = urlParams.getAll('status[]');
            
            pipelineItems.forEach(item => {
                const statusCircle = item.querySelector('.status-circle');
                const statusLink = item.querySelector('.pipeline-link');
                
                if (statusLink) {
                    const href = statusLink.href;
                    const statusMatch = href.match(/status=([^&]*)/);
                    const status = statusMatch ? decodeURIComponent(statusMatch[1]) : null;
                    
                    if (status && selectedStatuses.includes(status)) {
                        statusCircle.classList.add('selected-status');
                        statusCircle.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.5)';
                    } else {
                        statusCircle.classList.remove('selected-status');
                        statusCircle.style.boxShadow = '';
                    }
                }
            });
        }
        
        updateActiveFiltersVisual();

        // MEJORAR RESPONSIVE BEHAVIOR
        function handleResponsive() {
            const pipelineScroll = document.querySelector('.pipeline-scroll');
            if (window.innerWidth < 768) {
                pipelineScroll.style.minWidth = '650px';
            } else {
                pipelineScroll.style.minWidth = 'min(750px, 100%)';
            }
        }
        
        window.addEventListener('resize', handleResponsive);
        handleResponsive();
    });

    // ANIMACIÓN DE ENTRADA PARA ELEMENTOS
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.lead-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
    </script>