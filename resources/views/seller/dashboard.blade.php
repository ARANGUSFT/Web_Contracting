@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-center"><i class="bi bi-person-lines-fill"></i> My Assigned Leads</h2>

    <!-- 🔹 Current Pipeline -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Current Pipeline</h5>
            <span class="text-muted">Active Jobs: {{ array_sum($statusCounts) }}</span>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center flex-wrap gap-3">
                @foreach ([
                    'leads' => ['L', 'bg-warning', 'Lead'],
                    'prospect' => ['P', 'bg-orange', 'Prospect'],
                    'approved' => ['A', 'bg-success', 'Approved'],
                    'completed' => ['C', 'bg-primary', 'Completed'],
                    'invoiced' => ['I', 'bg-danger', 'Invoiced']
                ] as $key => [$letter, $color, $label])
                    <div class="pipeline-item text-center">
                        <div class="status-circle {{ $color }}" onclick="toggleStatusFilter('{{ $key }}')">
                            {{ $letter }}
                        </div>
                        <div class="status-count mt-1">{{ $statusCounts[$key] }}</div>
                        <div class="form-check mt-2">
                            <input class="form-check-input status-checkbox" type="checkbox" id="status-{{ $key }}" value="{{ $key }}" onchange="filterLeads()">
                            <label class="form-check-label small" for="status-{{ $key }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 🔹 Filters -->
    <div class="card shadow-lg p-4 mb-4 filter-card">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" id="searchName" class="form-control filter-input" placeholder="🔍 Search by name..." onkeyup="filterLeads()">
            </div>
            <div class="col-md-6">
                <input type="text" id="searchPhone" class="form-control filter-input" placeholder="📞 Search by phone..." onkeyup="filterLeads()">
            </div>
        </div>
    </div>

    <!-- 🔹 Leads Table -->
    @if($leads->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-circle"></i> You have no assigned leads.
        </div>
    @else
        <div class="table-responsive">
            <table class="table leads-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-person-fill"></i> Name</th>
                        <th><i class="bi bi-telephone-fill"></i> Phone</th>
                        <th><i class="bi bi-envelope-fill"></i> Email</th>
                        <th><i class="bi bi-bar-chart-fill"></i> Status</th>
                        <th><i class="bi bi-gear-fill"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr class="lead-row">
                            <td class="lead-name">
                                <strong>{{ $lead->first_name }} {{ $lead->last_name }}</strong>
                            </td>
                            <td class="lead-phone">
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-dark">
                                    <i class="bi bi-telephone text-success"></i> {{ $lead->phone }}
                                </a>
                            </td>
                            <td>
                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-dark">
                                    <i class="bi bi-envelope text-danger"></i> {{ $lead->email }}
                                </a>
                            </td>
                            <td class="lead-status">
                                @php
                                    $status = $statusMap[$lead->estado] ?? ['name' => 'Unknown', 'color' => 'bg-secondary'];
                                @endphp
                                <span class="badge {{ $status['color'] }}">
                                    {{ $status['name'] }}
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('seller.leads.show', $lead->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('seller.leads.edit', $lead->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- 🔹 Custom Styles -->
<style>
    

        /* Pipeline Styling */
        .pipeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            width: 80px;
        }

        .status-circle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .status-circle:hover {
            transform: scale(1.1);
        }

        .status-count {
            font-size: 1rem;
            font-weight: bold;
        }

        /* Filters */
        .filter-card {
            border-radius: 10px;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .filter-input {
            border-radius: 8px;
            padding: 10px;
            transition: 0.2s;
        }

        .filter-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }

        /* Table Styling */
        /* 📌 Diseño de la Tabla */
    .leads-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        background: white;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* 📌 Encabezados */
    .leads-table thead {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: white;
    }

    .leads-table th {
        padding: 15px;
        text-align: left;
        font-weight: bold;
    }

    /* 📌 Filas */
    .leads-table tbody tr {
        transition: all 0.3s ease-in-out;
        border-bottom: 1px solid #ddd;
    }

    .leads-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
        transform: scale(1.01);
    }

    /* 📌 Celdas */
    .leads-table td {
        padding: 15px;
    }

    /* 📌 Botones */
    .actions .btn {
        margin-right: 5px;
        transition: all 0.3s;
    }

    .actions .btn:hover {
        transform: scale(1.1);
    }

    /* 📌 Status Badges */
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white !important;
    }

    /* 📌 Responsividad */
    @media (max-width: 768px) {
        .leads-table {
            font-size: 14px;
        }
        
        .actions .btn {
            font-size: 12px;
            padding: 5px 10px;
        }
    }
</style>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Agregar eventos de cambio a los checkboxes de estado
        document.querySelectorAll(".status-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", filterLeads);
        });

        // Agregar eventos de entrada a los campos de búsqueda
        document.getElementById("searchName").addEventListener("input", filterLeads);
        document.getElementById("searchPhone").addEventListener("input", filterLeads);
    });

    function filterLeads() {
        let searchName = document.getElementById("searchName").value.toLowerCase();
        let searchPhone = document.getElementById("searchPhone").value.toLowerCase();

        // Obtener los estados seleccionados (permite múltiples selecciones)
        let selectedStatuses = [];
        document.querySelectorAll(".status-checkbox:checked").forEach(checkbox => {
            selectedStatuses.push(checkbox.value.toLowerCase());
        });

        document.querySelectorAll(".lead-row").forEach(row => {
            let name = row.querySelector(".lead-name").textContent.toLowerCase();
            let phone = row.querySelector(".lead-phone").textContent.toLowerCase();
            let status = row.querySelector(".lead-status span").textContent.toLowerCase();

            // Comprobar si el estado de la fila coincide con alguno de los seleccionados
            let statusMatch = selectedStatuses.length === 0 || selectedStatuses.some(statusFilter => status.includes(statusFilter));

            let showRow = 
                (name.includes(searchName) || searchName === "") &&
                (phone.includes(searchPhone) || searchPhone === "") &&
                statusMatch;

            row.style.display = showRow ? "" : "none";
        });
    }

    // Permitir filtrar al hacer clic en los círculos de estado
    function toggleStatusFilter(statusKey) {
        let checkbox = document.getElementById(`status-${statusKey}`);
        checkbox.checked = !checkbox.checked;
        filterLeads();
    }
</script>

@endsection
