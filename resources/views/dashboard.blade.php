@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Minimalista -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 fw-600 text-dark mb-2">Dashboard</h1>
            <p class="text-gray-500">Lead overview & performance metrics</p>
        </div>
        <div>
            <a href="{{ route('leads.create') }}" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus me-2"></i> New Lead
            </a>
        </div>
    </div>

    <!-- Metrics Minimalistas -->
    @php
        $totalLeads = array_sum($statusCounts);
        $totalValue = array_sum($statusSums);
        
        // Colores según tu especificación
        $statusColors = [
            'leads' => '#FFC107',     // Amarillo
            'prospect' => '#FD7E14',  // Naranja
            'completed' => '#28A745', // Verde
            'invoiced' => '#DC3545',  // Rojo
            'finish' => '#007BFF',    // Azul
            'cancel' => '#6C757D',    // Gris
        ];
        
        // Calculamos la tasa de conversión
        $conversionRate = $totalLeads > 0 ? (($statusCounts['completed'] ?? 0) / $totalLeads * 100) : 0;
    @endphp

    <div class="row g-3 mb-5">
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('leads.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm bg-white clickable-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-gray-500 small mb-2">Total Leads</p>
                                <h2 class="fw-700 mb-0">{{ number_format($totalLeads) }}</h2>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-gray-500 small mb-2">Total Value</p>
                            <h2 class="fw-700 mb-0">{{ number_format($totalValue, 0) }}</h2>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-currency-dollar fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('leads.index', ['status' => 'leads']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm bg-white clickable-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-gray-500 small mb-2">Active Leads</p>
                                <h2 class="fw-700 mb-0">{{ $statusCounts['leads'] ?? 0 }}</h2>
                            </div>
                            <div style="color: {{ $statusColors['leads'] }}">
                                <i class="bi bi-circle-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-gray-500 small mb-2">Conversion Rate</p>
                            <h2 class="fw-700 mb-0">
                                {{ number_format($conversionRate, 1) }}%
                            </h2>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-graph-up fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column: Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-600 mb-1">Lead Distribution</h5>
                            <p class="text-gray-500 small">Status breakdown visualization</p>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="chart-minimal-container">
                                <canvas id="leadChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="ps-md-4">
                                <h6 class="fw-600 mb-4">Status Summary</h6>
                                
                                @foreach($chartStatusData as $item)
                                @php
                                    $statusName = strtolower($item['name']);
                                    $statusColor = $statusColors[$statusName] ?? '#6C757D';
                                    $percentage = $totalLeads > 0 ? ($item['y'] / $totalLeads * 100) : 0;
                                @endphp
                                <a href="{{ route('leads.index', ['status' => $statusName]) }}" class="text-decoration-none">
                                    <div class="status-item mb-3 clickable-item">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="status-color me-3" style="background-color: {{ $statusColor }}"></div>
                                            <span class="flex-grow-1 fw-500 text-dark">{{ ucfirst($statusName) }}</span>
                                            <span class="fw-600 text-dark">{{ $item['y'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-3" style="height: 4px;">
                                                <div class="progress-bar rounded" style="width: {{ $percentage }}%; background-color: {{ $statusColor }}"></div>
                                            </div>
                                            <span class="text-gray-500 small">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                                
                                <div class="mt-5 pt-3 border-top">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <a href="{{ route('leads.index') }}" class="text-decoration-none">
                                                <h4 class="fw-700 mb-1 text-dark">{{ $totalLeads }}</h4>
                                                <p class="text-gray-500 small mb-0">Total Leads</p>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="fw-700 mb-1 text-dark">{{ number_format($totalValue, 0) }}</h4>
                                            <p class="text-gray-500 small mb-0">Total Value</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                <!-- Quick Stats -->
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body p-4">
                        <h5 class="fw-600 mb-4">Performance Metrics</h5>
                        
                        <div class="row g-3 mb-4">
                            @foreach(['leads', 'prospect', 'completed', 'invoiced'] as $status)
                            @if(isset($statusCounts[$status]))
                            <div class="col-6">
                                <a href="{{ route('leads.index', ['status' => $status]) }}" class="text-decoration-none">
                                    <div class="metric-box p-3 border rounded clickable-item">
                                        <div class="d-flex align-items-center">
                                            <div class="metric-icon me-3" style="color: {{ $statusColors[$status] }}">
                                                <i class="bi bi-circle-fill fs-5"></i>
                                            </div>
                                            <div>
                                                <h4 class="fw-700 mb-0 text-dark">{{ $statusCounts[$status] }}</h4>
                                                <p class="text-gray-500 small mb-0">{{ ucfirst($status) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        
                        <!-- Conversion Progress -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-gray-500">Conversion Rate</span>
                                <span class="fw-600">{{ number_format($conversionRate, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success rounded" style="width: {{ $conversionRate }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Value Metrics -->
                        <div class="border-top pt-4">
                            <div class="row g-3">
                                <div class="col-4 text-center">
                                    <h6 class="fw-600 mb-1 text-dark">{{ number_format($totalValue, 0) }}</h6>
                                    <p class="text-gray-500 small mb-0">Total</p>
                                </div>
                                <div class="col-4 text-center">
                                    @php
                                        $avgValue = $totalLeads > 0 ? $totalValue / $totalLeads : 0;
                                    @endphp
                                    <h6 class="fw-600 mb-1 text-dark">{{ number_format($avgValue, 0) }}</h6>
                                    <p class="text-gray-500 small mb-0">Avg</p>
                                </div>
                                <div class="col-4 text-center">
                                    <a href="{{ route('leads.index') }}" class="text-decoration-none">
                                        <h6 class="fw-600 mb-1 text-dark">{{ $totalLeads }}</h6>
                                        <p class="text-gray-500 small mb-0">Leads</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Leads -->
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-600 mb-0">Recent Leads</h5>
                            <a href="{{ route('leads.index') }}" class="text-primary small text-decoration-none">
                                View all
                            </a>
                        </div>
                        
                        <div class="leads-list">
                            @forelse($leads as $lead)
                            @php
                                $leadStatus = strtolower($lead->status ?? 'leads');
                                $leadColor = $statusColors[$leadStatus] ?? '#6C757D';
                            @endphp
                            <a href="{{ route('leads.show', $lead->id) }}" class="text-decoration-none">
                                <div class="lead-item mb-3 pb-3 border-bottom clickable-item">
                                    <div class="d-flex align-items-center">
                                        <div class="lead-avatar me-3">
                                            <div class="avatar-circle" style="background-color: {{ $leadColor }}20; color: {{ $leadColor }}">
                                                {{ strtoupper(substr($lead->first_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-600 mb-0 text-dark">{{ $lead->first_name }} {{ $lead->last_name }}</h6>
                                                <span class="badge rounded-pill" style="background-color: {{ $leadColor }}20; color: {{ $leadColor }}">
                                                    {{ ucfirst($leadStatus) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-500 small mb-1">
                                                <i class="bi bi-envelope me-1"></i>{{ $lead->email ?? 'No email' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-gray-500 small">
                                                    {{ optional($lead->created_at)->format('M d') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-4">
                                <div class="text-gray-400 mb-3">
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                                <p class="text-gray-500 mb-0">No leads available</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('leads.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus me-2"></i> Add New Lead
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    initMinimalChart(@json($chartStatusData));
});

function initMinimalChart(chartData) {
    if (typeof Chart === 'undefined' || !chartData || chartData.length === 0) return;
    
    const canvas = document.getElementById('leadChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Colores según especificación
    const colorMap = {
        'leads': '#FFC107',
        'prospect': '#FD7E14',
        'completed': '#28A745',
        'invoiced': '#DC3545',
        'finish': '#007BFF',
        'cancel': '#6C757D'
    };
    
    const labels = chartData.map(item => item.name);
    const data = chartData.map(item => item.y);
    const colors = chartData.map(item => colorMap[item.name.toLowerCase()] || '#6C757D');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 4,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '75%',
            animation: {
                animateScale: false,
                animateRotate: true,
                duration: 1000
            }
        }
    });
}
</script>

<!-- Minimalist Styles -->
<style>
    /* Variables de color */
    :root {
        --yellow: #FFC107;
        --orange: #FD7E14;
        --green: #28A745;
        --red: #DC3545;
        --blue: #007BFF;
        --gray: #6C757D;
        --light-gray: #F8F9FA;
        --border-color: #E9ECEF;
        --text-dark: #212529;
        --text-gray: #6C757D;
    }
    
    /* Tipografía minimalista */
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    
    .text-gray-500 { color: var(--text-gray) !important; }
    .text-gray-400 { color: #ADB5BD !important; }
    
    /* Layout */
    .container-fluid {
        max-width: 1400px;
    }
    
    /* Cards minimalistas */
    .card {
        border-radius: 12px;
        border: none;
        background: white;
    }
    
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
    }
    
    .border-0 { border: none; }
    
    /* Metric Cards */
    .metric-box {
        background: var(--light-gray);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .metric-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    /* Chart */
    .chart-minimal-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
    
    /* Status items */
    .status-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .status-item {
        padding: 8px 0;
    }
    
    /* Progress bars */
    .progress {
        background-color: var(--border-color);
        border-radius: 2px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 2px;
    }
    
    /* Recent Leads */
    .leads-list {
        max-height: 380px;
        overflow-y: auto;
        padding-right: 8px;
    }
    
    .leads-list::-webkit-scrollbar {
        width: 4px;
    }
    
    .leads-list::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 2px;
    }
    
    .leads-list::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 2px;
    }
    
    .lead-item {
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.2s ease;
    }
    
    .lead-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 4px 10px;
        font-size: 11px;
    }
    
    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 8px 20px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background-color: var(--blue);
        border-color: var(--blue);
    }
    
    .btn-outline-primary {
        color: var(--blue);
        border-color: var(--blue);
        background: transparent;
    }
    
    .btn-outline-primary:hover {
        background-color: var(--blue);
        color: white;
    }
    
    /* Form controls */
    .form-control {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 10px 14px;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .chart-minimal-container {
            height: 240px;
        }
        
        .leads-list {
            max-height: 320px;
        }
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .chart-minimal-container {
            height: 220px;
        }
        
        .metric-box {
            padding: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        h2.fw-700 {
            font-size: 1.75rem;
        }
        
        .btn {
            padding: 6px 16px;
            font-size: 0.875rem;
        }
    }
    
    /* Animaciones suaves */
    .card {
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important;
    }
    
    /* Espaciado consistente */
    .mb-4 { margin-bottom: 1.5rem !important; }
    .mb-5 { margin-bottom: 2rem !important; }
    .p-4 { padding: 1.5rem !important; }
    
    /* Bordes sutiles */
    .border-bottom {
        border-bottom: 1px solid var(--border-color) !important;
    }
    
    .border-top {
        border-top: 1px solid var(--border-color) !important;
    }
    
    /* Elementos clickeables */
    .clickable-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .clickable-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
        border-color: var(--blue) !important;
    }
    
    .clickable-item {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .clickable-item:hover {
        background-color: rgba(0, 123, 255, 0.05);
        transform: translateX(2px);
    }
    
    /* Enlaces sin subrayado */
    .text-decoration-none {
        text-decoration: none !important;
    }
    
    /* Color de texto para enlaces */
    a.text-decoration-none:hover .text-dark {
        color: var(--blue) !important;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

@endsection