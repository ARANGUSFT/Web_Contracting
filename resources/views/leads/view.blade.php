@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <div class="container mt-4">

    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>

                    <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                       <i class="bi bi-arrow-left me-1"></i> Back to Leads
                    </a>

                    <h2 class="text-primary m-0 d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle"></i>
                        {{ $lead->first_name }} {{ $lead->last_name }}
                    </h2>

                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="text-muted small fw-semibold">Contract Value</div>
                        <h4 class="fw-bold mb-1" id="totalAmountText">$0.00</h4>
                        <div class="text-danger fw-semibold small">Balance Due</div>
                        <div class="text-danger small" id="balanceDueText">$0.00</div>
                    </div>

                    <div class="position-relative" style="width: 74px; height: 74px;">
                        <canvas id="balanceChart" class="balance-chart"></canvas>
                        <div
                            class="position-absolute top-50 start-50 translate-middle fw-bold small"
                            id="chartPercentageText">
                            0%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $statusList = [
            1 => ['label' => 'Lead', 'color' => 'bg-warning', 'icon' => 'bi-person'],
            2 => ['label' => 'Prospect', 'color' => 'bg-orange', 'icon' => 'bi-search'],
            3 => ['label' => 'Approved', 'color' => 'bg-success', 'icon' => 'bi-check-circle'],
            4 => ['label' => 'Completed', 'color' => 'bg-primary', 'icon' => 'bi-check-all'],
            5 => ['label' => 'Invoiced', 'color' => 'bg-info', 'icon' => 'bi-receipt'],
            6 => ['label' => 'Finish', 'color' => 'bg-secondary', 'icon' => 'bi-flag-fill'],
            7 => ['label' => 'Cancelled', 'color' => 'bg-danger', 'icon' => 'bi-x-circle'],
        ];

        $currentIndex = array_search($lead->estado, array_keys($statusList));
        $statusKeys = array_keys($statusList);
    @endphp

    <!-- Main Card -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            


                <span class="badge {{ $statusList[$lead->estado]['color'] }} fs-6 px-3 py-2 rounded-pill">
                    <i class="bi {{ $statusList[$lead->estado]['icon'] }} me-1"></i>
                    {{ $statusList[$lead->estado]['label'] }}
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">

            <!-- Contact Section -->
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h5 class="mb-0 fw-semibold text-dark">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>
                        Contact Information
                    </h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-telephone text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small fw-semibold">Phone</div>
                                        <a href="tel:{{ $lead->phone }}" class="text-decoration-none fw-medium">
                                            {{ $lead->phone }}
                                        </a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-envelope text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small fw-semibold">Email</div>
                                        <a href="mailto:{{ $lead->email }}" class="text-decoration-none fw-medium">
                                            {{ $lead->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-calendar text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small fw-semibold">Created</div>
                                        <span class="fw-medium">{{ $lead->created_at->format('d M, Y') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-clock text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small fw-semibold">Last Touched</div>
                                        <span class="fw-medium">
                                            {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Address Section -->
            <div class="mb-4">
                <div class="card border-0 bg-light rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="me-3 mt-1">
                                <i class="bi bi-geo-alt-fill text-warning fs-5"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold mb-1">Address</div>
                                <div class="fw-medium text-dark">
                                    {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Status Progress -->
            <div class="mb-4">
                <div class="card border-0 bg-light rounded-4">
                    <div class="card-body">
                        <form id="statusForm" action="{{ route('leads.assignstatus', $lead->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" id="selectedStatus">

                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <h5 class="mb-0 fw-semibold text-dark">
                                    <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                                    Status Progress
                                </h5>
                            </div>

                            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                                @if ($currentIndex > 0 && $lead->estado < 3)
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                        onclick="changeStatus({{ $statusKeys[$currentIndex - 1] }})">
                                        <i class="bi bi-arrow-left me-1"></i> Back
                                    </button>
                                @endif

                                @foreach ($statusList as $key => $status)
                                    <div
                                        class="status-box {{ $status['color'] }} {{ $lead->estado == $key ? 'status-active' : 'status-inactive' }}"
                                        data-bs-toggle="tooltip"
                                        title="{{ $status['label'] }}">
                                        <i class="bi {{ $status['icon'] }}"></i>
                                    </div>
                                @endforeach

                                @if ($currentIndex < count($statusList) - 1 && $lead->estado != 7)
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                        onclick="handleNextClick({{ $statusKeys[$currentIndex + 1] }})">
                                        Next <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Approved Lead Form -->
            @if ($lead->estado == 2)
                <div class="card border-success mt-4 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Approved Lead - Submit Installation Information
                        </h6>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('leads.submitApprovedData', $lead->id) }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <!-- Company Information -->
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100 rounded-4">
                                        <div class="card-body">
                                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                                <i class="bi bi-building me-1"></i> Company Information
                                            </h6>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Company Name</label>
                                                <input
                                                    type="text"
                                                    name="company_name"
                                                    value="{{ $lead->user->company_name ?? '' }}"
                                                    class="form-control form-control-sm bg-white"
                                                    readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Representative</label>
                                                <input
                                                    type="text"
                                                    name="company_representative"
                                                    value="{{ $lead->user->name ?? '' }} {{ $lead->user->last_name ?? '' }}"
                                                    class="form-control form-control-sm bg-white"
                                                    readonly>
                                            </div>

                                            <div class="mb-0">
                                                <label class="form-label small fw-bold">Phone</label>
                                                <input
                                                    type="text"
                                                    name="company_phone"
                                                    value="{{ $lead->user->phone ?? '' }}"
                                                    class="form-control form-control-sm bg-white"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lead Information -->
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100 rounded-4">
                                        <div class="card-body">
                                            <h6 class="text-success border-bottom pb-2 mb-3">
                                                <i class="bi bi-person me-1"></i> Lead Information
                                            </h6>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Lead Name</label>
                                                <input
                                                    type="text"
                                                    name="lead_name"
                                                    value="{{ $lead->first_name }}"
                                                    class="form-control form-control-sm"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Address</label>
                                                <input
                                                    type="text"
                                                    name="lead_address"
                                                    value="{{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}"
                                                    class="form-control form-control-sm"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Phone</label>
                                                <input
                                                    type="text"
                                                    name="lead_phone"
                                                    value="{{ $lead->phone }}"
                                                    class="form-control form-control-sm"
                                                    required>
                                            </div>

                                            <div class="mb-0">
                                                <label class="form-label small fw-bold">Installation Date</label>
                                                <input
                                                    type="date"
                                                    name="installation_date"
                                                    value="{{ $lead->installation_date }}"
                                                    class="form-control form-control-sm"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="mt-4">
                                <label class="form-label small fw-bold">Additional Notes</label>
                                <textarea
                                    name="extra_info"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Enter any additional information or notes..."></textarea>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success rounded-pill px-4">
                                    <i class="bi bi-send-fill me-1"></i> Submit Information
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            <!-- Financial Summary -->
            <div class="row mt-4 pt-3 border-top g-3">

                <div class="col-md-4">
                    <div class="card border-danger h-100 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-up-circle text-danger h3 mb-2"></i>
                            <h6 class="card-title text-muted mb-2">Total Expenses</h6>
                            <div id="totalExpensesDisplayBelow" class="h4 text-danger fw-bold mb-0">
                                ${{ number_format($lead->total_expenses ?? 0, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-primary h-100 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-coin text-primary h3 mb-2"></i>
                            <h6 class="card-title text-muted mb-2">Total Paid</h6>
                            <div id="totalPaidDisplayBelow" class="h4 text-primary fw-bold mb-0">
                                ${{ number_format($lead->total_paid ?? 0, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card {{ $lead->net_profit >= 0 ? 'border-success' : 'border-warning' }} h-100 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up {{ $lead->net_profit >= 0 ? 'text-success' : 'text-warning' }} h3 mb-2"></i>
                            <h6 class="card-title text-muted mb-2">Net Profit</h6>
                            <div
                                id="netProfitDisplayBelow"
                                class="h4 fw-bold mb-0 {{ $lead->net_profit >= 0 ? 'text-success' : 'text-warning' }}">
                                ${{ number_format($lead->net_profit ?? 0, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>




    <!-- Pestañas -->
    <ul class="nav nav-tabs mt-4" id="leadTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#photos">Photos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#contribution">Contribution</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#expenses">Expenses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#quote">Quote</a>
        </li>
    </ul>


    <!-- Toast de éxito -->
    @if(session('success'))
         <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
             <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
                 <div class="d-flex">
                     <div class="toast-body">
                         {{ session('success') }}
                     </div>
                     <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                 </div>
             </div>
         </div>
    @endif


 
    <div class="tab-content p-4 bg-white shadow-lg rounded">

        @include('leads.tabs.chat')

        @include('leads.tabs.photos')

        @include('leads.tabs.documents')

        @include('leads.tabs.contribution')

        @include('leads.tabs.expenses')

        @include('leads.tabs.quote')

    </div>

</div>



<style>
        /* =============================================
    VARIABLES GLOBALES Y RESET
    ============================================= */
    :root {
        --primary-color: #0d6efd;
        --primary-light: rgba(13, 110, 253, 0.1);
        --success-color: #198754;
        --danger-color: #dc3545;
        --border-color: #e9ecef;
        --hover-color: #f8f9fa;
        --border-radius: 1rem;
        --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background: #2270be;
    }

    /* =============================================
    TARJETAS Y CONTENEDORES PRINCIPALES
    ============================================= */
    .card {
        border-radius: 10px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* =============================================
    ESTADOS (status-box)
    ============================================= */
    .status-box {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        padding: 8px 16px;
        min-width: 100px;
        text-align: center;
    }

    .status-active {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border: 3px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        transform: scale(1.05);
        z-index: 1;
    }

    .status-inactive {
        opacity: 0.6;
    }

    .status-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .bg-orange {
        background-color: #f79646 !important;
    }

    /* =============================================
    ZONA DE SUBIDA DE ARCHIVOS (UPLOAD ZONE)
    ============================================= */
    .upload-zone {
        border: 2px dashed #dee2e6;
        transition: var(--transition);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
        overflow: hidden;
    }

    .upload-zone::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: var(--transition);
    }

    .upload-zone:hover::before {
        left: 100%;
    }

    .upload-zone:hover {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .upload-zone.dragover {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        transform: scale(1.02);
    }

    /* =============================================
    GALERÍA DE IMÁGENES
    ============================================= */
    .gallery-card {
        border: none;
        border-radius: var(--border-radius);
        transition: var(--transition);
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow);
    }

    .gallery-card .card-img-top {
        transition: var(--transition);
    }

    .gallery-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .image-overlay {
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
        opacity: 0;
        transition: var(--transition);
    }

    .gallery-card:hover .image-overlay {
        opacity: 1;
    }

    .image-counter {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        font-weight: 600;
        z-index: 10;
    }

    .gallery-item {
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    /* =============================================
    VISTA PREVIA DE IMÁGENES (PREVIEW)
    ============================================= */
    .preview-image {
        border-radius: var(--border-radius);
        transition: var(--transition);
        overflow: hidden;
    }

    .preview-image:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .remove-preview {
        background: rgba(220, 53, 69, 0.9);
        border: none;
        transition: var(--transition);
        z-index: 20;
    }

    .remove-preview:hover {
        background: var(--danger-color);
        transform: scale(1.1);
    }

    /* =============================================
    BARRA DE PROGRESO
    ============================================= */
    .progress {
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--primary-color), #4dabf7);
        transition: width 0.6s ease;
    }

    /* =============================================
    ACCIONES EN MASA (BULK ACTIONS)
    ============================================= */
    .sticky-top {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    /* =============================================
    BOTONES PERSONALIZADOS
    ============================================= */
    .btn {
        transition: var(--transition);
        border: none;
        border-radius: 6px;
        font-weight: 400;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #4dabf7);
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color), #20c997);
        border: none;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #e35d6a);
        border: none;
    }

    .btn-outline-primary,
    .btn-outline-secondary,
    .btn-outline-danger {
        border-color: var(--border-color);
        color: #6c757d;
    }

    .btn-outline-primary:hover {
        border-color: #0d6efd;
        background-color: #0d6efd;
        color: white;
    }

    .btn-outline-secondary:hover {
        border-color: #6c757d;
        background-color: #6c757d;
        color: white;
    }

    .btn-outline-danger:hover {
        border-color: #dc3545;
        background-color: #dc3545;
        color: white;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
    }

    /* =============================================
    LISTA DE ARCHIVOS SELECCIONADOS
    ============================================= */
    .selected-files-list {
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 0.75rem;
        background: white;
        margin-bottom: 0.5rem;
    }

    .selected-file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .selected-file-item:last-child {
        border-bottom: none;
    }

    .selected-file-item:hover {
        background: var(--hover-color);
    }

    .file-info-small {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }

    .file-icon-small {
        font-size: 0.875rem;
        width: 16px;
    }

    .file-name-small {
        font-size: 0.875rem;
        color: #374151;
        flex: 1;
    }

    .file-size {
        font-size: 0.75rem;
        color: #6b7280;
        margin-left: 0.5rem;
    }

    /* =============================================
    CARPETAS (FOLDERS) Y ARCHIVOS
    ============================================= */
    .folders-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .folder-item {
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        background: white;
        overflow: hidden;
    }

    .folder-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: var(--transition);
        background: none;
    }

    .folder-header:hover {
        background: var(--hover-color);
    }

    .folder-info {
        display: flex;
        align-items: center;
        flex: 1;
        cursor: pointer;
    }

    .folder-info i {
        font-size: 1.25rem;
    }

    .folder-info h6 {
        font-weight: 500;
    }

    .folder-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .folder-actions .btn {
        padding: 0.375rem;
        border-radius: 4px;
    }

    .folder-actions .bi-chevron-down {
        transition: var(--transition);
    }

    .folder-header[aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }

    .folder-content {
        padding: 1.25rem;
        border-top: 1px solid var(--border-color);
    }

    /* Lista de archivos dentro de carpeta */
    .files-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border-radius: var(--radius);
        transition: var(--transition);
    }

    .file-item:hover {
        background: var(--hover-color);
    }

    .file-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .file-info i {
        font-size: 1.125rem;
        width: 24px;
    }

    .file-details {
        display: flex;
        flex-direction: column;
    }

    .file-name {
        font-weight: 400;
        font-size: 0.9rem;
        color: #212529;
    }

    .file-actions {
        display: flex;
        gap: 0.25rem;
        opacity: 0;
        transition: var(--transition);
    }

    .file-item:hover .file-actions {
        opacity: 1;
    }

    .file-actions .btn {
        padding: 0.375rem;
        border-radius: 4px;
    }

    .empty-folder {
        padding: 2rem 1rem;
    }

    /* =============================================
    MODALES
    ============================================= */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem 1.5rem 0.5rem;
    }

    .modal-body {
        padding: 0.5rem 1.5rem;
    }

    .modal-footer {
        padding: 0.5rem 1.5rem 1.5rem;
    }

    /* Vista previa de archivos en modal */
    #filePreviewContent embed,
    #filePreviewContent iframe {
        width: 100%;
        height: 70vh;
        border: none;
    }

    #filePreviewContent img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
    }

    #filePreviewContent pre {
        width: 100%;
        height: 70vh;
        overflow: auto;
        background: #f8f9fa;
        padding: 1rem;
        margin: 0;
        border: none;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }

    /* =============================================
    FORMULARIOS
    ============================================= */
    .form-control {
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 0.75rem;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }

    .form-control-lg {
        padding: 1rem;
    }

    /* =============================================
    GRÁFICA DE BALANCE (DOUGHNUT)
    ============================================= */
    .balance-chart {
        border-radius: 50%;
        width: 70px !important;
        height: 70px !important;
    }

    #chartPercentageText {
        font-size: 0.9rem;
        font-weight: bold;
        color: #dc3545; /* rojo por defecto */
    }

    /* =============================================
    CHAT BOX (SCROLL PERSONALIZADO)
    ============================================= */
    #chat-box::-webkit-scrollbar {
        width: 6px;
    }
    #chat-box::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }

    /* =============================================
    ANIMACIONES
    ============================================= */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .gallery-card,
    .preview-image {
        animation: fadeInUp 0.5s ease-out;
    }

    .collapsing {
        transition: height 0.2s ease;
    }

    /* Estados de carga */
    .uploading {
        opacity: 0.7;
        pointer-events: none;
    }

    .gallery-loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Selección de imágenes */
    .select-image:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .gallery-card.selected {
        border: 2px solid var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    /* =============================================
    RESPONSIVE
    ============================================= */
    @media (max-width: 768px) {
        .upload-zone {
            padding: 2rem 1rem !important;
        }
        .upload-zone-content h5 {
            font-size: 1.1rem;
        }
        .gallery-card .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }
        .bulk-actions-card .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .folder-header {
            padding: 0.875rem 1rem;
        }
        .folder-content {
            padding: 1rem;
        }
        .file-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .file-actions {
            opacity: 1;
            align-self: flex-end;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
        .folder-actions {
            gap: 0.25rem;
        }
        .selected-files-list {
            max-height: 120px;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }
        .upload-zone {
            padding: 1.5rem 0.75rem !important;
        }
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
    }
</style>


<script>
    // =============================================
    // GLOBAL HELPERS
    // =============================================

    const APPROVED_STATUS = 2;
    const COMPLETED_STATUS = 3;
    const CURRENT_STATUS = {{ $lead->estado ?? 0 }};
    const APPROVED_DATA_SUBMITTED = {{ $lead->approved_data_submitted ? 'true' : 'false' }};

    let chart = null;

    function parseMoney(value) {
        if (value === null || value === undefined) return 0;
        const cleaned = value.toString().replace(/,/g, '').trim();
        const number = parseFloat(cleaned);
        return isNaN(number) ? 0 : number;
    }

    function formatMoney(value, locale = 'en-US') {
        const number = Number(value) || 0;
        return number.toLocaleString(locale, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // =============================================
    // DOM READY
    // =============================================

    document.addEventListener('DOMContentLoaded', function () {
        initializeTooltips();
        initializeToasts();
        initializeTabs();

        if (typeof initializeFilePreviews === 'function') initializeFilePreviews();
        if (typeof initializeFolderUploads === 'function') initializeFolderUploads();
        if (typeof initializeContributions === 'function') initializeContributions();
        if (typeof initializeExpenses === 'function') initializeExpenses();
        if (typeof initializeBalanceChart === 'function') initializeBalanceChart();

        updateBalance();
        updateExpenseSummary();
    });

    // =============================================
    // BOOTSTRAP HELPERS
    // =============================================

    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function initializeToasts() {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl).show();
        });
    }

    function initializeTabs() {
        const lastTab = localStorage.getItem('activeLeadTab');

        if (lastTab) {
            const trigger = document.querySelector(`#leadTabs a[data-bs-toggle="tab"][href="${lastTab}"]`);
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        }

        const tabLinks = document.querySelectorAll('#leadTabs a[data-bs-toggle="tab"]');
        tabLinks.forEach(link => {
            link.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('activeLeadTab', e.target.getAttribute('href'));
            });
        });
    }

    // =============================================
    // STATUS MANAGEMENT
    // =============================================

    function changeStatus(nextStatus) {
        const selectedStatusInput = document.getElementById('selectedStatus');
        const statusForm = document.getElementById('statusForm');

        if (!selectedStatusInput || !statusForm) return;

        const currentStatusValue = parseInt(selectedStatusInput.value || CURRENT_STATUS);

        if (currentStatusValue >= COMPLETED_STATUS && nextStatus < currentStatusValue) {
            Swal.fire({
                icon: 'error',
                title: 'Action denied',
                text: 'You cannot go back to a previous status after reaching Completed.',
                confirmButtonText: 'Understood'
            });
            return;
        }

        if (
            currentStatusValue === APPROVED_STATUS &&
            !APPROVED_DATA_SUBMITTED &&
            nextStatus === COMPLETED_STATUS
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Action required',
                text: 'You must complete and submit the additional information form before moving to Completed.',
                confirmButtonText: 'Understood'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to change the status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                selectedStatusInput.value = nextStatus;
                statusForm.submit();
            }
        });
    }

    function handleNextClick(nextStatus) {
        if (
            nextStatus === COMPLETED_STATUS &&
            CURRENT_STATUS === APPROVED_STATUS &&
            !APPROVED_DATA_SUBMITTED
        ) {
            const approvedCard = document.querySelector('.card.border-success');

            if (approvedCard) {
                approvedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            Swal.fire({
                icon: 'info',
                title: 'Complete the form',
                text: 'Before marking this lead as completed, please fill out the required information.'
            });

            return;
        }

        changeStatus(nextStatus);
    }

    // =============================================
    // BALANCE CHART
    // =============================================

    function renderChart(paid, remaining) {
        const chartCanvas = document.getElementById('balanceChart');
        if (!chartCanvas) return;

        const ctx = chartCanvas.getContext('2d');
        if (!ctx) return;

        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Remaining'],
                datasets: [{
                    data: [paid, remaining],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    function updateBalance() {
        const contractValueInput = document.getElementById('contractValue');
        const contractValue = parseMoney(contractValueInput ? contractValueInput.value : 0);

        let totalPaid = 0;

        document.querySelectorAll('.aporte-value').forEach(input => {
            totalPaid += parseMoney(input.value);
        });

        const balance = contractValue - totalPaid;
        const percentage = contractValue > 0 ? (totalPaid / contractValue) * 100 : 0;

        const balanceDisplay = document.getElementById('balanceDisplay');
        if (balanceDisplay) {
            balanceDisplay.textContent = `$${formatMoney(totalPaid)}`;
        }

        const totalAmountText = document.getElementById('totalAmountText');
        if (totalAmountText) {
            totalAmountText.textContent = `$${formatMoney(contractValue)}`;
        }

        const balanceDueText = document.getElementById('balanceDueText');
        if (balanceDueText) {
            balanceDueText.textContent = `$${formatMoney(Math.max(0, balance))}`;
        }

        const chartPercentageText = document.getElementById('chartPercentageText');
        if (chartPercentageText) {
            chartPercentageText.textContent = `${percentage.toFixed(0)}%`;
            chartPercentageText.classList.toggle('text-success', percentage >= 100);
            chartPercentageText.classList.toggle('text-danger', percentage < 100);
        }

        renderChart(totalPaid, Math.max(0, balance));
    }

    function initializeBalanceChart() {
        const contractInput = document.getElementById('contractValue');

        if (contractInput && !contractInput.dataset.balanceBound) {
            contractInput.addEventListener('input', function () {
                updateBalance();
                updateExpenseSummary();
            });

            contractInput.dataset.balanceBound = 'true';
        }

        if (!document.body.dataset.balanceDelegationBound) {
            document.addEventListener('input', function (e) {
                if (e.target.classList.contains('aporte-value')) {
                    updateBalance();
                    updateExpenseSummary();
                }
            });

            document.body.dataset.balanceDelegationBound = 'true';
        }

        updateBalance();
    }

    // =============================================
    // FINANCIAL SUMMARY
    // =============================================

    function updateExpenseSummary() {
        const contractValue = parseMoney(document.getElementById('contractValue')?.value || 0);
        const baseExpenses = parseMoney('{{ $lead->total_expenses }}');
        const basePaid = parseMoney('{{ $lead->total_paid }}');

        let dynamicExpenses = 0;
        let dynamicPaid = 0;

        document.querySelectorAll('#expenseTableBody tr').forEach(row => {
            const type = row.querySelector('.expense-type')?.value;
            if (!type) return;

            const input = row.querySelector('.amount-field');
            if (!input || input.disabled) return;

            const value = parseMoney(input.value);

            if (type === 'commission') {
                dynamicExpenses += contractValue * (value / 100);
            } else {
                dynamicExpenses += value;
            }
        });

        document.querySelectorAll('.aporte-value:not([data-existing="1"])').forEach(input => {
            dynamicPaid += parseMoney(input.value);
        });

        const totalExpenses = baseExpenses + dynamicExpenses;
        const totalPaid = basePaid + dynamicPaid;
        const netProfit = totalPaid - totalExpenses;

        const totalExpensesEl = document.getElementById('totalExpensesDisplayBelow');
        if (totalExpensesEl) {
            totalExpensesEl.textContent = `$${formatMoney(totalExpenses)}`;
        }

        const totalPaidEl = document.getElementById('totalPaidDisplayBelow');
        if (totalPaidEl) {
            totalPaidEl.textContent = `$${formatMoney(totalPaid)}`;
        }

        const netProfitEl = document.getElementById('netProfitDisplayBelow');
        if (netProfitEl) {
            netProfitEl.textContent = `$${formatMoney(netProfit)}`;
            netProfitEl.className = `h4 fw-bold ${netProfit >= 0 ? 'text-success' : 'text-danger'}`;
        }
    }

    if (!document.body.dataset.expenseSummaryDelegationBound) {
        document.addEventListener('input', function (e) {
            if (
                e.target.closest('#expenseTableBody') ||
                e.target.classList.contains('aporte-value') ||
                e.target.id === 'contractValue'
            ) {
                updateExpenseSummary();
            }
        });

        document.body.dataset.expenseSummaryDelegationBound = 'true';
    }


</script>

@endsection