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
                            <input class="form-check-input status-checkbox" hidden type="checkbox" id="status-{{ $key }}" 
                                value="{{ strtolower($label) }}" onchange="filterLeads()">
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

    <!-- 🔹 Leads Responsive Table - Diseño Limpio -->
    @if($leads->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-circle"></i> You have no assigned leads.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell">Phone</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>
                                <strong>{{ $lead->first_name }} {{ $lead->last_name }}</strong>
                                <div class="small d-block d-sm-none mt-1">
                                    <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-muted">
                                        <i class="bi bi-telephone"></i> {{ $lead->phone }}
                                    </a>
                                    <br>
                                    <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-muted">
                                        <i class="bi bi-envelope"></i> {{ $lead->email }}
                                    </a>
                                </div>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-muted">
                                    <i class="bi bi-telephone"></i> {{ $lead->phone }}
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-muted">
                                    <i class="bi bi-envelope"></i> {{ $lead->email }}
                                </a>
                            </td>
                            <td>
                                @php
                                    $status = $statusMap[$lead->estado] ?? ['name' => 'Unknown', 'color' => 'bg-secondary'];
                                @endphp
                                <span class="badge {{ $status['color'] }}">
                                    {{ $status['name'] }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('seller.leads.show', $lead->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye">View</i>
                                </a>
                                <a href="{{ route('seller.leads.edit', $lead->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil-square">Edit</i>
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
    // Función para filtrar leads
    function filterLeads() {
        const statusCheckboxes = document.querySelectorAll('.status-checkbox');
        const searchName = document.getElementById('searchName').value.toLowerCase();
        const searchPhone = document.getElementById('searchPhone').value.toLowerCase();
    
        // Obtén los estados seleccionados
        const selectedStatuses = Array.from(statusCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
    
        // Recorre cada fila de la tabla
        document.querySelectorAll('.table tbody tr').forEach(row => {
            const leadName = row.querySelector('td:first-child').textContent.toLowerCase();
            const leadPhone = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const leadStatus = row.querySelector('td:nth-child(4) .badge').textContent.trim().toLowerCase();
    
            // Comprueba coincidencias en nombre y teléfono
            const matchesName = !searchName || leadName.includes(searchName);
            const matchesPhone = !searchPhone || leadPhone.includes(searchPhone);
    
            // Comprueba coincidencias en estados
            const matchesStatus = selectedStatuses.length === 0 || selectedStatuses.includes(leadStatus);
    
            // Mostrar u ocultar filas según coincidencias
            if (matchesName && matchesPhone && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Activar filtro inicial
    document.addEventListener('DOMContentLoaded', () => {
        filterLeads();
    });
    
    // Función para activar filtros al hacer clic en el círculo de estado
    function toggleStatusFilter(key) {
        const checkbox = document.getElementById('status-' + key);
        checkbox.checked = !checkbox.checked;
        filterLeads();
    }
</script>
    

@endsection
