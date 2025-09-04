@extends('admin.layouts.superadmin')

@push('styles')
<style>
    .table-responsive-sticky { max-height: 70vh; overflow: auto; }
    .table-responsive-sticky thead th {
        position: sticky; top: 0; z-index: 2; background: var(--bs-body-bg, #fff);
    }
    .text-truncate-1 { max-width: 340px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .w-110 { width: 110px; }
    .w-140 { width: 140px; }
    .w-170 { width: 170px; }
    .w-260 { width: 260px; }
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="mb-0">Invoices</h1>

        <div class="w-25">
            <input id="invoicesFilter" type="text" class="form-control form-control-sm" placeholder="Filter by Job, Crew or Address...">
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @php
        $grandTotal = ($totals['paid'] ?? 0) + ($totals['due'] ?? 0);
        $globalPercent = $grandTotal > 0 ? round((($totals['paid'] ?? 0) / $grandTotal) * 100) : 0;
    @endphp

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-bold text-muted">Total Paid</div>
                    <div class="fs-3 fw-semibold">${{ number_format($totals['paid'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-bold text-muted">Total Due</div>
                    <div class="fs-3 fw-semibold">${{ number_format($totals['due'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-bold text-muted">Overall Progress</div>
                    <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $globalPercent }}">
                        <div class="progress-bar {{ $globalPercent>=100?'bg-success':($globalPercent>=60?'bg-info':($globalPercent>=30?'bg-warning':'bg-danger')) }}"
                             style="width: {{ $globalPercent }}%">{{ $globalPercent }}%</div>
                    </div>
                    <small class="text-muted d-block mt-1">
                        Paid: ${{ number_format($totals['paid'] ?? 0, 2) }} / Total: ${{ number_format($grandTotal, 2) }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    @if($invoices->isEmpty())
        <div class="alert alert-info">No jobs to display.</div>
    @else
    <div class="table-responsive table-responsive-sticky">
        <table class="table table-sm table-hover table-bordered align-middle" id="invoicesTable">
            <thead>
                <tr>
                    <th>Job Number</th>
                    <th>Crew</th>
                    <th>Job Address</th>
                    <th class="w-260">Progress</th>
                    <th class="w-170 text-end">Total</th>
                    <th class="w-140 text-end">Remaining</th>
                    <th class="w-140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $row)
                    @php
                        $total = (float)($row['paid'] + $row['due']);
                        $percent = $total > 0 ? round(($row['paid'] / $total) * 100) : 0;
                        $barClass = $percent>=100?'bg-success':($percent>=60?'bg-info':($percent>=30?'bg-warning':'bg-danger'));
                        $addressFull = $row['job_address'] ?? '—';
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $row['job_number'] }}</td>
                        <td>{{ $row['crew_project'] ?? '—' }}</td>
                        <td title="{{ $addressFull }}"><span class="text-truncate-1 d-inline-block">{{ $addressFull }}</span></td>
                        <td>
                            <div class="progress mb-1" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percent }}">
                                <div class="progress-bar {{ $barClass }}" style="width: {{ $percent }}%">{{ $percent }}%</div>
                            </div>
                            <small class="text-muted d-block">
                                Paid: ${{ number_format($row['paid'], 2) }} / Total: ${{ number_format($total, 2) }}
                            </small>
                        </td>

                        <td class="text-end">
                            <form action="{{ route('superadmin.invoices.store') }}" method="POST" class="d-inline-flex justify-content-end gap-2 align-items-center total-form">
                                @csrf
                                <input type="hidden" name="calendar_id" value="{{ $row['calendar_id'] }}">
                                <div class="input-group input-group-sm w-110">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="total" class="form-control form-control-sm"
                                           step="0.01" min="0" value="{{ number_format($total, 2, '.', '') }}" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Set</button>
                            </form>
                            <small class="text-muted d-block">Remaining = Total - Paid</small>
                        </td>

                        <td class="text-end">
                            <span class="badge bg-warning text-dark">${{ number_format($row['due'], 2) }}</span>
                        </td>

                        <td>
                            <a href="{{ route('superadmin.invoices.open', $row['calendar_id']) }}"
                               class="btn btn-sm btn-outline-secondary w-100">
                                History
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $globalPercent = $grandTotal > 0 ? round((($totals['paid'] ?? 0) / $grandTotal) * 100) : 0;
                    $globalClass = $globalPercent>=100?'bg-success':($globalPercent>=60?'bg-info':($globalPercent>=30?'bg-warning':'bg-danger'));
                @endphp
                <tr>
                    <th colspan="3" class="text-end align-middle">
                        <div class="d-flex flex-column">
                            <span>Overall Progress</span>
                            <small class="text-muted">Paid: ${{ number_format($totals['paid'] ?? 0, 2) }} / Total: ${{ number_format($grandTotal, 2) }}</small>
                        </div>
                    </th>
                    <th class="align-middle">
                        <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $globalPercent }}">
                            <div class="progress-bar {{ $globalClass }}" style="width: {{ $globalPercent }}%">{{ $globalPercent }}%</div>
                        </div>
                    </th>
                    <th></th>
                    <th colspan="2" class="text-end align-middle">
                        <span class="fw-semibold">Remaining: ${{ number_format($totals['due'] ?? 0, 2) }}</span>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const input = document.getElementById('invoicesFilter');
        const table = document.getElementById('invoicesTable');
        if(!input || !table) return;

        input.addEventListener('input', function(){
            const q = this.value.trim().toLowerCase();
            const rows = table.tBodies[0].rows;
            for (let i = 0; i < rows.length; i++) {
                const cellsText = (rows[i].cells[0].innerText + ' ' + rows[i].cells[1].innerText + ' ' + rows[i].cells[2].innerText).toLowerCase();
                rows[i].style.display = cellsText.includes(q) ? '' : 'none';
            }
        });
    })();

    (function(){
        document.querySelectorAll('.total-form').forEach(function(form){
            const btn = form.querySelector('button[type="submit"]');
            const input = form.querySelector('input[name="total"]');

            form.addEventListener('submit', function(){
                if(btn){ btn.disabled = true; btn.innerText = 'Saving...'; }
            });
            if(input){
                input.addEventListener('blur', function(){
                    const val = parseFloat(this.value || 0);
                    this.value = isNaN(val) ? '' : val.toFixed(2);
                });
            }
        });
    })();
</script>
@endpush
