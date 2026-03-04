@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Modern Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <div>
            <h1 class="display-6 fw-semibold text-dark mb-1">Dashboard</h1>
            <p class="text-secondary-emphasis mb-0">
                Overview of your pipeline and financial performance
            </p>
        </div>

        <div class="d-flex align-items-center gap-4">
       

            <a href="{{ route('leads.create') }}" class="btn btn-primary rounded-3 px-4 py-2 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> New Lead
            </a>
        </div>
    </div>

    @php
        $totalLeads = array_sum($statusCounts);
        $totalValue = array_sum($statusSums);
        
        // Pastel colors for each status (based on originals)
        $statusColors = [
            'leads'     => '#FFB347', // Yellowish orange
            'prospect'  => '#FF8C42', // Orange
            'completed' => '#6FCF97', // Soft green
            'invoiced'  => '#EB5757', // Red
            'finish'    => '#5D9BEC', // Blue
            'cancel'    => '#A0A0A0', // Gray
        ];
        
        $conversionRate = $totalLeads > 0 ? (($statusCounts['completed'] ?? 0) / $totalLeads * 100) : 0;
    @endphp

    <!-- Metric Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Leads -->
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('leads.index') }}" class="text-decoration-none">
                <div class="card metric-card h-100 border-0 shadow-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary text-uppercase small fw-semibold">Total Leads</span>
                                <h2 class="fw-bold text-dark mt-2 mb-0">{{ number_format($totalLeads) }}</h2>
                            </div>
                            <div class="icon-shape bg-primary-soft text-primary rounded-3">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Total Value -->
        <div class="col-lg-3 col-md-6">
            <div class="card metric-card h-100 border-0 shadow-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary text-uppercase small fw-semibold">Total Value</span>
                            <h2 class="fw-bold text-dark mt-2 mb-0">{{ number_format($totalValue, 0) }}</h2>
                        </div>
                        <div class="icon-shape bg-success-soft text-success rounded-3">
                            <i class="bi bi-currency-dollar fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conversion Rate -->
        <div class="col-lg-3 col-md-6">
            <div class="card metric-card h-100 border-0 shadow-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary text-uppercase small fw-semibold">Conversion Rate</span>
                            <h2 class="fw-bold text-dark mt-2 mb-0">{{ number_format($conversionRate, 1) }}%</h2>
                        </div>
                        <div class="icon-shape bg-warning-soft text-warning rounded-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="col-lg-3 col-md-6">
            <div class="card metric-card h-100 border-0 shadow-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary text-uppercase small fw-semibold">Outstanding Balance</span>
                            <h2 class="fw-bold text-dark mt-2 mb-0">${{ number_format($totalOwed ?? 0, 2) }}</h2>
                        </div>
                        <div class="icon-shape bg-danger-soft text-danger rounded-3">
                            <i class="bi bi-credit-card fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Chart and Side Panel -->
    <div class="row g-4">
        <!-- Left Column: Distribution Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-semibold mb-1">Lead Distribution</h5>
                            <p class="text-secondary small">Breakdown by status</p>
                        </div>
                    </div>
                    
                    <div class="row g-4 align-items-center">
                        <div class="col-md-7">
                            <div class="chart-container">
                                <canvas id="leadChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="status-legend">
                                <h6 class="fw-semibold mb-3">Status Summary</h6>
                                @foreach($chartStatusData as $item)
                                    @php
                                        $statusName = strtolower($item['name']);
                                        $color = $statusColors[$statusName] ?? '#A0A0A0';
                                        $percentage = $totalLeads > 0 ? ($item['y'] / $totalLeads * 100) : 0;
                                    @endphp
                                    <a href="{{ route('leads.index', ['status' => $statusName]) }}" class="text-decoration-none">
                                        <div class="status-item mb-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="status-dot me-2" style="background-color: {{ $color }}"></span>
                                                <span class="flex-grow-1 text-dark">{{ ucfirst($statusName) }}</span>
                                                <span class="fw-semibold">{{ $item['y'] }}</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar rounded-pill" style="width: {{ $percentage }}%; background-color: {{ $color }}"></div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                
                                <div class="mt-4 pt-3 border-top">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <a href="{{ route('leads.index') }}" class="text-decoration-none">
                                                <h4 class="fw-bold text-dark mb-1">{{ $totalLeads }}</h4>
                                                <p class="text-secondary small mb-0">Total Leads</p>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="fw-bold text-dark mb-1">{{ number_format($totalValue, 0) }}</h4>
                                            <p class="text-secondary small mb-0">Total Value</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Metrics and Recent Leads -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                <!-- Performance Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4">Performance Metrics</h5>
                        
                        <div class="row g-3 mb-4">
                            @foreach(['leads', 'prospect', 'completed', 'invoiced'] as $status)
                                @if(isset($statusCounts[$status]))
                                <div class="col-6">
                                    <a href="{{ route('leads.index', ['status' => $status]) }}" class="text-decoration-none">
                                        <div class="metric-mini p-3 rounded-3 border">
                                            <div class="d-flex align-items-center">
                                                <div class="mini-icon me-3" style="color: {{ $statusColors[$status] }}">
                                                    <i class="bi bi-circle-fill"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-0">{{ $statusCounts[$status] }}</h5>
                                                    <span class="text-secondary small">{{ ucfirst($status) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Conversion</span>
                                <span class="fw-semibold">{{ number_format($conversionRate, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success rounded-pill" style="width: {{ $conversionRate }}%"></div>
                            </div>
                        </div>
                        
                        <div class="border-top pt-4">
                            <div class="row g-3">
                                <div class="col-4 text-center">
                                    <h6 class="fw-bold text-dark mb-1">{{ number_format($totalValue, 0) }}</h6>
                                    <p class="text-secondary small mb-0">Total</p>
                                </div>
                                <div class="col-4 text-center">
                                    <h6 class="fw-bold text-dark mb-1">{{ number_format($totalLeads > 0 ? $totalValue / $totalLeads : 0, 0) }}</h6>
                                    <p class="text-secondary small mb-0">Average</p>
                                </div>
                                <div class="col-4 text-center">
                                    <a href="{{ route('leads.index') }}" class="text-decoration-none">
                                        <h6 class="fw-bold text-dark mb-1">{{ $totalLeads }}</h6>
                                        <p class="text-secondary small mb-0">Leads</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Leads -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-semibold mb-0">Recent Leads</h5>
                            <a href="{{ route('leads.index') }}" class="btn btn-link text-primary p-0">View all</a>
                        </div>
                        
                        <div class="recent-leads-list">
                            @forelse($leads as $lead)
                                @php
                                    $leadStatus = strtolower($lead->status ?? 'leads');
                                    $leadColor = $statusColors[$leadStatus] ?? '#A0A0A0';
                                    $initials = strtoupper(substr($lead->first_name, 0, 1) . substr($lead->last_name, 0, 1));
                                @endphp
                                <a href="{{ route('leads.show', $lead->id) }}" class="text-decoration-none">
                                    <div class="recent-lead-item mb-3 pb-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3" style="background-color: {{ $leadColor }}20; color: {{ $leadColor }}">
                                                {{ $initials }}
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h6 class="fw-semibold text-dark text-truncate mb-0">{{ $lead->first_name }} {{ $lead->last_name }}</h6>
                                                    <span class="badge rounded-pill" style="background-color: {{ $leadColor }}20; color: {{ $leadColor }}">
                                                        {{ ucfirst($leadStatus) }}
                                                    </span>
                                                </div>
                                                <p class="text-secondary small text-truncate mb-1">
                                                    <i class="bi bi-envelope me-1"></i>{{ $lead->email ?? 'No email' }}
                                                </p>
                                                <span class="text-secondary small">
                                                    <i class="bi bi-calendar3 me-1"></i>{{ optional($lead->created_at)->format('d M, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <div class="text-secondary mb-3">
                                        <i class="bi bi-people fs-1"></i>
                                    </div>
                                    <p class="text-secondary">No leads available</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('leads.create') }}" class="btn btn-outline-primary w-100 rounded-3">
                                <i class="bi bi-plus-lg me-2"></i> Add New Lead
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    const chartData = @json($chartStatusData);
    if (!chartData || chartData.length === 0) return;

    const canvas = document.getElementById('leadChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    const colorMap = {
        'leads': '#FFB347',
        'prospect': '#FF8C42',
        'completed': '#6FCF97',
        'invoiced': '#EB5757',
        'finish': '#5D9BEC',
        'cancel': '#A0A0A0'
    };

    const labels = chartData.map(item => item.name);
    const data = chartData.map(item => item.y);
    const backgroundColors = chartData.map(item => colorMap[item.name.toLowerCase()] || '#A0A0A0');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors,
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(33, 37, 41, 0.9)',
                    titleColor: '#F8F9FA',
                    bodyColor: '#F8F9FA',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: (context) => {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1200
            }
        }
    });
});
</script>

<!-- Custom Styles -->
<style>
    :root {
        --primary: #0d6efd;
        --primary-soft: #e7f1ff;
        --success: #198754;
        --success-soft: #e1f7e7;
        --warning: #ffc107;
        --warning-soft: #fff3cd;
        --danger: #dc3545;
        --danger-soft: #f8dddf;
        --secondary: #6c757d;
        --light: #f8f9fa;
        --dark: #212529;
        --border-color: #dee2e6;
    }

    body {
        background-color: #f5f7fb;
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }

    /* Metric Cards */
    .metric-card {
        border-radius: 20px;
        background: white;
        transition: all 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }

    .icon-shape {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light);
    }
    .bg-primary-soft { background-color: var(--primary-soft); }
    .bg-success-soft { background-color: var(--success-soft); }
    .bg-warning-soft { background-color: var(--warning-soft); }

    /* Chart */
    .chart-container {
        position: relative;
        height: 260px;
        width: 100%;
    }

    /* Status Legend */
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-item {
        cursor: pointer;
        padding: 6px 0;
        transition: background 0.1s;
    }
    .status-item:hover {
        background: rgba(0,0,0,0.02);
    }

    /* Mini Metrics */
    .metric-mini {
        background: white;
        border-color: var(--border-color) !important;
        transition: all 0.2s;
    }
    .metric-mini:hover {
        background: var(--light);
        border-color: var(--primary) !important;
    }
    .mini-icon {
        font-size: 1.2rem;
        line-height: 1;
    }

    /* Recent Leads List */
    .recent-leads-list {
        max-height: 380px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .recent-leads-list::-webkit-scrollbar {
        width: 4px;
    }
    .recent-leads-list::-webkit-scrollbar-track {
        background: var(--light);
        border-radius: 10px;
    }
    .recent-leads-list::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 10px;
    }

    .avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .recent-lead-item {
        transition: all 0.2s;
    }
    .recent-lead-item:hover {
        transform: translateX(4px);
        border-bottom-color: var(--primary) !important;
    }

    .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        border-radius: 30px;
    }

    /* Buttons */
    .btn {
        border-radius: 12px;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
    }
    .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
    }
    .btn-outline-primary {
        border-color: var(--primary);
        color: var(--primary);
    }
    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
    }

    /* Soft shadows */
    .shadow-hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .shadow-hover:hover {
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }

    /* Text */
    .text-secondary {
        color: var(--secondary) !important;
    }
    .fw-semibold {
        font-weight: 600;
    }

    /* Borders */
    .border-bottom {
        border-bottom: 1px solid var(--border-color) !important;
    }
    .border-top {
        border-top: 1px solid var(--border-color) !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .display-6 {
            font-size: 1.8rem;
        }
        .icon-shape {
            width: 48px;
            height: 48px;
        }
        .icon-shape i {
            font-size: 1.4rem;
        }
        .chart-container {
            height: 200px;
        }
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Inter font (optional) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
@endsection