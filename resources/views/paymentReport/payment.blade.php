@extends('layouts.app')
@section('content')

  
<div class="container py-5">

    <!-- 🔹 Encabezado -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">📊 Financial Report</h2>
        <p class="text-muted mb-0">Overview of all contracts, payments, and balances</p>
    </div>

    <!-- 🔢 Tarjetas Resumen -->
    <div class="row g-4 mb-5 text-white text-center">
        <div class="col-md-4">
            <div class="bg-primary rounded-3 p-4 shadow-sm h-100">
                <i class="bi bi-file-earmark-text fs-1 mb-2 d-block"></i>
                <h6 class="fw-bold">Total Contract</h6>
                <h4 class="fw-semibold">${{ number_format($totalContract ?? 0, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-secondary rounded-3 p-4 shadow-sm h-100">
                <i class="bi bi-cash-coin fs-1 mb-2 d-block"></i>
                <h6 class="fw-bold">Total Paid</h6>
                <h4 class="fw-semibold">${{ number_format($totalPaid ?? 0, 2) }}</h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-danger rounded-3 p-4 shadow-sm h-100">
                <i class="bi bi-wallet2 fs-1 mb-2 d-block"></i>
                <h6 class="fw-bold">Total Balance</h6>
                <h4 class="fw-semibold">${{ number_format($totalBalance ?? 0, 2) }}</h4>
            </div>
        </div>
    </div>

    <!-- 🔍 Filtros de búsqueda -->
    <div class="row mb-3">
        <div class="col">
            <input type="text" class="form-control" placeholder="🔍 Search..." disabled>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary" disabled><i class="bi bi-funnel"></i> Filters</button>
        </div>
    </div>

    <!-- 📊 Tabla de datos -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark text-nowrap">
                <tr>
                    <th><i class="bi bi-building"></i> Project</th>
                    <th><i class="bi bi-person"></i> Salesman</th>
                    <th><i class="bi bi-currency-dollar"></i> Contract Value</th>
                    <th><i class="bi bi-check-circle"></i> Paid</th>
                    <th><i class="bi bi-balance-scale"></i> Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>dd ddd, AK</td>
                    <td><span class="text-muted">Not Assigned</span></td>
                    <td>$0.00</td>
                    <td>-$2,452.00</td>
                    <td>$2,452.00</td>
                    <td><span class="badge bg-success">Paid</span></td>
                </tr>
                <tr>
                    <td>Ddd Ffd, CA</td>
                    <td><span class="text-muted">Not Assigned</span></td>
                    <td>$0.00</td>
                    <td>$0.00</td>
                    <td>$0.00</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



@endsection