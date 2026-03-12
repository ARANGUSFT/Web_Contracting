@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- 🧭 CURRENT PIPELINE -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
            @php
                $activeJobs = collect($statusCounts)->except('cancelled')->sum();
                $pipelineStages = [
                    'leads'     => ['letter' => 'L', 'color' => 'bg-warning',   'label' => 'Lead',      'tooltip' => 'New potential clients'],
                    'prospect'  => ['letter' => 'P', 'color' => 'bg-orange',   'label' => 'Prospect',   'tooltip' => 'Qualified opportunities'],
                    'approved'  => ['letter' => 'A', 'color' => 'bg-success',  'label' => 'Approved',   'tooltip' => 'Approved projects'],
                    'completed' => ['letter' => 'C', 'color' => 'bg-primary',  'label' => 'Completed',  'tooltip' => 'Work completed'],
                    'invoiced'  => ['letter' => 'I', 'color' => 'bg-danger',   'label' => 'Invoiced',   'tooltip' => 'Invoiced to client'],
                    'finish'    => ['letter' => 'F', 'color' => 'bg-info',     'label' => 'Finished',   'tooltip' => 'Project finalized'],
                    'cancelled' => ['letter' => 'X', 'color' => 'bg-secondary','label' => 'Cancelled',  'tooltip' => 'Cancelled projects'],
                ];
            @endphp

            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-kanban"></i> Current Pipeline
            </h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary text-white px-3 py-2">
                    Active Jobs: {{ $activeJobs }}
                </span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Quick filters">
                        <i class="bi bi-filter"></i> Quick Filters
                    </button>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => null, 'assignment' => null, 'page' => null]) }}" aria-label="Reset all filters">Reset All</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['assignment' => 'assigned', 'page' => null]) }}">Assigned Only</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['assignment' => 'unassigned', 'page' => null]) }}">Unassigned Only</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- PIPELINE CON SCROLL HORIZONTAL MEJORADO -->
            <div class="pipeline-container" role="navigation" aria-label="Pipeline stages">
                <div class="pipeline-scroll">
                    @foreach($pipelineStages as $key => $stage)
                        @php
                            $isActive = request('status') == $key;
                            $pipelineUrl = $isActive
                                ? request()->fullUrlWithQuery(['status' => null, 'page' => null])
                                : request()->fullUrlWithQuery(['status' => $key, 'page' => null]);
                        @endphp
                        <a href="{{ $pipelineUrl }}"
                           class="text-decoration-none pipeline-link"
                           @if($stage['tooltip']) data-bs-toggle="tooltip" title="{{ $stage['tooltip'] }}" @endif
                           @if($isActive) aria-current="page" @endif>
                            <div class="pipeline-item text-center position-relative">
                                <div class="status-circle {{ $stage['color'] }} {{ $isActive ? 'selected-status' : '' }}" aria-label="{{ $stage['label'] }} status">
                                    {{ $stage['letter'] }}
                                    @if($isActive)<div class="status-pulse" aria-hidden="true"></div>@endif
                                </div>

                                <div class="status-count fw-bold mt-2">
                                    {{ $statusCounts[$key] ?? 0 }}
                                </div>

                                @if(!in_array($key, ['leads', 'cancelled']))
                                    <div class="text-muted small status-amount">
                                        ${{ number_format($statusSums[$key] ?? 0, 2) }}
                                    </div>
                                @endif

                                <small class="d-block mt-1 fw-semibold text-secondary">
                                    {{ $stage['label'] }}
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
                        <a href="{{ request()->url() }}" class="btn btn-outline-secondary btn-sm" aria-label="Reset all filters">
                            <i class="bi bi-arrow-clockwise"></i> Reset All
                        </a>
                        <div class="vr"></div>
                        <div class="selected-filters-container">
                            <small class="text-muted">
                                @php
                                    $activeFilters = [];
                                    if (request('search')) $activeFilters[] = 'Search';
                                    $statuses = request('status', []);
                                    if (!is_array($statuses)) $statuses = $statuses ? [$statuses] : [];
                                    $validStatuses = array_filter($statuses, fn($s) => $s !== 'all' && !empty($s));
                                    if (count($validStatuses) > 0) {
                                        $activeFilters[] = count($validStatuses) === 1 ? 'Status: ' . ucfirst($validStatuses[0]) : count($validStatuses) . ' statuses';
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
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0" placeholder="Search by name, email, phone, location..." aria-label="Search leads">
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}" class="btn btn-outline-secondary" aria-label="Clear search">
                                    <i class="bi bi-x"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- IMPROVED STATUS FILTER WITH MULTISELECT -->
                    <div class="col-md-3">
                        <div class="dropdown">
                            <button class="btn btn-light border-0 shadow-sm w-100 text-start dropdown-toggle position-relative"
                                    type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="Filter by status, currently {{ $selectedCount = count(array_filter((array)request('status'))) }} selected">
                                <i class="bi bi-funnel me-1"></i>
                                <span class="status-filter-text">
                                    @php
                                        $selectedStatuses = request('status', []);
                                        if (!is_array($selectedStatuses)) $selectedStatuses = $selectedStatuses ? [$selectedStatuses] : [];
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
                                    <h6 class="mb-0 text-dark fw-semibold" id="statusDropdownLabel">Filter by Status</h6>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Select or clear all statuses">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllStatus">All</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearAllStatus">None</button>
                                    </div>
                                </div>

                                <div class="status-checkboxes" style="max-height: 200px; overflow-y: auto;" role="group" aria-labelledby="statusDropdownLabel">
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
                                            <span class="badge {{ $option['color'] }} me-2" style="width: 12px; height: 12px; border-radius: 50%;" aria-hidden="true"></span>
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
                        <select name="assignment" class="form-select border-0 shadow-sm" aria-label="Filter by assignment">
                            <option value="all">All Assignments</option>
                            <option value="assigned" {{ request('assignment') == 'assigned' ? 'selected' : '' }}>Assigned Only</option>
                            <option value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Unassigned Only</option>
                        </select>
                    </div>

                    <!-- SELLER FILTER -->
                    <div class="col-md-2">
                        <select name="seller" class="form-select border-0 shadow-sm" aria-label="Filter by seller">
                            <option value="all">Sales Rep</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ request('seller') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
<!-- 🗂 LEAD CARDS -->
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

                    $lastContactDays = $lead->last_touched_at ? $lead->last_touched_at->diffInDays(now()) : 999;
                    $contactBadgeClass = $lastContactDays > 30 ? 'bg-warning' : ($lastContactDays > 7 ? 'bg-info' : 'bg-success');

                    $location = "{$lead->street} {$lead->suite}, {$lead->city}, {$lead->state} {$lead->zip}";

                    $contractValue = (float) ($lead->contract_value ?? 0);
                    $totalPaid = (float) ($lead->total_paid ?? 0);
                    $balanceDue = max($contractValue - $totalPaid, 0);
                    $paidPercentage = $contractValue > 0 ? round(($totalPaid / $contractValue) * 100) : 0;
                @endphp

                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card lead-card border-0 shadow-sm h-100">

                        <!-- HEADER -->
                        <div class="lead-card__header">

                            <!-- Top row -->
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div class="flex-grow-1 min-w-0">
                                    <h5 class="lead-card__name fw-semibold mb-1">
                                        {{ $lead->first_name }} {{ $lead->last_name }}
                                    </h5>

                                    <div class="small text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        Created {{ $lead->created_at->format('M j, Y') }}
                                    </div>
                                </div>

                                <div class="dropdown flex-shrink-0">
                                    <button
                                        class="btn btn-link text-dark p-0"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        aria-label="Lead options">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leads.edit', $lead->id) }}">
                                                <i class="bi bi-pencil me-2 text-primary"></i> Edit Lead
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('leads.show', $lead->id) }}">
                                                <i class="bi bi-eye me-2"></i> View Details
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge lead-badge {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>


                                @if($lead->team)
                                    <span class="badge lead-badge bg-light text-dark border">
                                        <i class="bi bi-person-circle me-1"></i>{{ $lead->team->name }}
                                    </span>
                                @else
                                    <span class="badge lead-badge bg-light text-muted border">
                                        <i class="bi bi-person-dash me-1"></i>Unassigned
                                    </span>
                                @endif
                            </div>

                            <!-- Balance summary -->
                            <div class="lead-balance-box">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="flex-grow-1">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="small text-muted fw-semibold">Contract Value</div>
                                                <div class="fw-bold text-dark" id="totalAmountText-{{ $lead->id }}">
                                                    ${{ number_format($contractValue, 2) }}
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="small text-danger fw-semibold">Balance Due</div>
                                                <div class="fw-semibold text-danger" id="balanceDueText-{{ $lead->id }}">
                                                    ${{ number_format($balanceDue, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="position-relative lead-balance-chart-wrap flex-shrink-0">
                                        <canvas id="balanceChart-{{ $lead->id }}" class="balance-chart-mini"></canvas>
                                        <div
                                            class="position-absolute top-50 start-50 translate-middle fw-bold small {{ $paidPercentage >= 100 ? 'text-success' : 'text-danger' }}"
                                            id="chartPercentageText-{{ $lead->id }}">
                                            {{ $paidPercentage }}%
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- BODY -->
                        <div class="lead-card__body">

                            <div class="contact-list mb-3">
                                <div class="contact-item mb-2">
                                    <i class="bi bi-telephone text-success"></i>
                                    <span class="small text-truncate">{{ $lead->phone ?: 'No phone' }}</span>
                                </div>

                                <div class="contact-item mb-2">
                                    <i class="bi bi-envelope text-danger"></i>
                                    <span class="small text-truncate">{{ $lead->email ?: 'No email' }}</span>
                                </div>

                                <div class="contact-item">
                                    <i class="bi bi-geo-alt text-warning"></i>
                                    <span class="small text-truncate">{{ $location }}</span>
                                </div>
                            </div>

                            <div class="metrics-row d-flex justify-content-between align-items-start border-top pt-3 gap-3">
                                <div class="metric">
                                    <div class="metric-label">Paid</div>
                                    <div class="metric-value text-primary">
                                        ${{ number_format($totalPaid, 2) }}
                                    </div>
                                </div>

                                <div class="metric text-end">
                                    <div class="metric-label">Last Touch</div>
                                    <div class="metric-value">
                                        <i class="bi bi-clock-history me-1"></i>
                                        {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="lead-card__footer">
                            <form action="{{ route('leads.assignSales', $lead->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                @method('PUT')

                                <i class="bi bi-person-plus text-primary" style="font-size: 1.1rem;"></i>

                                <select
                                    name="team_id"
                                    class="form-select form-select-sm border-0 shadow-none bg-light"
                                    aria-label="Assign seller">
                                    <option value="">Unassigned</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" {{ $lead->team_id == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-sm btn-primary" title="Assign">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted mb-3" aria-hidden="true"></i>
                    <h4 class="text-muted">No leads found</h4>
                    <p class="text-muted mb-4">There are currently no leads matching your criteria.</p>
                    <a href="{{ request()->url() }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Reset Filters
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- PAGINATION MEJORADO CON FILTROS -->
@if($leads->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
        <div class="text-muted small order-2 order-md-1">
            Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} results
        </div>
        <div class="order-1 order-md-2">
            {{ $leads->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
</div>
@endsection



<style>
    .lead-balance-box {
        background: #f8f9fb;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 10px 12px;
        min-height: 82px;
    }

    .lead-balance-chart-wrap {
        width: 64px;
        height: 64px;
        flex-shrink: 0;
    }

    .balance-chart-mini {
        width: 64px !important;
        height: 64px !important;
    }

    .lead-card__header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #eef1f4;
    }

    .lead-card__body {
        padding: 1rem 1.25rem;
    }

    .lead-card__footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #eef1f4;
        margin-top: auto;
    }

    .lead-card__name {
        color: #212529;
        font-size: 1.05rem;
    }
</style>

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
            --hover-shadow: 0 20px 30px -10px rgba(59, 130, 246, 0.3);
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
            background: var(--bg-color);
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

        .pipeline-link:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 4px;
            border-radius: 4px;
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

        /* Tarjetas rediseñadas */
        .lead-card {
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.2s ease;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .lead-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(59, 130, 246, 0.15) !important;
            border-color: transparent;
        }

        .lead-card__header {
            padding: 1.25rem 1.25rem 0.5rem 1.25rem;
        }

        .lead-card__name {
            font-size: 1.1rem;
            line-height: 1.4;
            color: #1e293b;
            letter-spacing: -0.01em;
        }

        .lead-card__body {
            padding: 0.5rem 1.25rem 1rem 1.25rem;
        }

        .lead-card__footer {
            background: #f8fafc;
            border-top: 1px solid #edf2f7;
            padding: 0.75rem 1.25rem;
        }

        /* Badges más elegantes */
        .lead-badge {
            font-weight: 500;
            padding: 0.35em 1em;
            border-radius: 30px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
        }

        .lead-badge i {
            font-size: 0.8rem;
            margin-right: 0.25rem;
        }

        /* Items de contacto */
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #334155;
        }

        .contact-item i {
            width: 1.2rem;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Métricas */
        .metrics-row {
            gap: 1rem;
        }

        .metric {
            flex: 1;
        }

        .metric-label {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.1rem;
        }

        .metric-value {
            font-weight: 600;
            font-size: 0.9rem;
            color: #0f172a;
        }

        /* Fechas adicionales */
        .dates-row {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Footer form */
        .lead-card__footer .form-select-sm {
            font-size: 0.8rem;
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
            background-color: #f1f5f9;
            border-radius: 30px;
        }

        .lead-card__footer .btn-sm {
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
        }

        /* Ajuste para móviles */
        @media (max-width: 576px) {
            .lead-card__name {
                font-size: 1rem;
            }
            .metrics-row {
                flex-direction: column;
                gap: 0.5rem;
            }
            .metric {
                width: 100%;
            }
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

        .form-select, .form-control {
            border-radius: 10px;
            transition: all var(--transition-speed) ease;
        }

        .form-select:focus, .form-control:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: var(--primary-color);
            outline: none;
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

        /* COLORES MEJORADOS (sólidos) */
        .bg-warning  { background: var(--warning-color) !important; }
        .bg-orange   { background: var(--orange-color) !important; }
        .bg-success  { background: var(--success-color) !important; }
        .bg-primary  { background: var(--primary-color) !important; }
        .bg-danger   { background: var(--danger-color) !important; }
        .bg-info     { background: var(--info-color) !important; }
        .bg-secondary { background: var(--secondary-color) !important; }

        /* BOTONES MEJORADOS */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
        }

        .btn:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-sm {
            border-radius: 8px;
        }

        /* Paginación moderna */
        .pagination-modern .pagination {
            gap: 0.25rem;
            margin-bottom: 0;
        }

        .pagination-modern .page-item .page-link {
            border-radius: 8px !important;
            border: none;
            padding: 0.5rem 0.9rem;
            color: #4b5563;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s;
            font-weight: 500;
        }

        .pagination-modern .page-item .page-link:hover {
            background-color: #f1f5f9;
            color: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .pagination-modern .page-item.active .page-link {
            background: var(--primary-color) !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(59,130,246,0.3);
        }

        .pagination-modern .page-item.disabled .page-link {
            background-color: #f9fafb;
            color: #cbd5e1;
            pointer-events: none;
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


        /* Paginación moderna */
    .pagination {
        gap: 0.25rem;
        margin-bottom: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .page-item .page-link {
        border-radius: 8px !important;
        border: none;
        padding: 0.5rem 0.9rem;
        color: #4b5563;
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.2s;
        font-weight: 500;
        margin: 0 2px;
    }

    .page-item .page-link:hover {
        background-color: #f1f5f9;
        color: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .page-item.active .page-link {
        background: var(--primary-color) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(59,130,246,0.3);
    }

    .page-item.disabled .page-link {
        background-color: #f9fafb;
        color: #cbd5e1;
        pointer-events: none;
        opacity: 0.7;
    }

    /* Responsive: en móvil, centrar paginación y apilar */
    @media (max-width: 576px) {
        .pagination {
            gap: 0.15rem;
        }
        .page-link {
            padding: 0.4rem 0.7rem;
            font-size: 0.9rem;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') return;

        @foreach($leads as $lead)
            (function () {
                const canvas = document.getElementById('balanceChart-{{ $lead->id }}');
                if (!canvas) return;

                const contractValue = {{ (float) ($lead->contract_value ?? 0) }};
                const totalPaid = {{ (float) ($lead->total_paid ?? 0) }};
                const remaining = Math.max(contractValue - totalPaid, 0);

                new Chart(canvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Paid', 'Remaining'],
                        datasets: [{
                            data: [totalPaid, remaining],
                            backgroundColor: ['#198754', '#dc3545'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    }
                });
            })();
        @endforeach
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Manejo del multiselect de estado
        const selectAllBtn = document.getElementById('selectAllStatus');
        const clearAllBtn = document.getElementById('clearAllStatus');
        const checkboxes = document.querySelectorAll('.status-checkbox');
        const applyBtn = document.getElementById('applyStatusFilter');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
            });
        }

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
            });
        }

        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                document.getElementById('filterForm').submit();
            });
        }

        // Función global para limpiar filtros de estado
        window.clearStatusFilter = function() {
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('filterForm').submit();
        };
    });
</script>
