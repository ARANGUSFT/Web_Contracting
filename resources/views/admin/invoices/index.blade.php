@extends('admin.layouts.superadmin')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Invoices</h2>
            <small class="text-muted">Manage, filter and review all invoices</small>
        </div>

        <a href="{{ route('superadmin.invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create Invoice
        </a>
    </div>

    {{-- FILTERS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-2">
                    <label class="form-label small text-muted">Invoice #</label>
                    <input type="text"
                           name="invoice_number"
                           class="form-control"
                           placeholder="INV-2025"
                           value="{{ request('invoice_number') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted">Company</label>
                    <select name="company_id" class="form-control">
                        <option value="">All companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">State</label>
                    <select name="state" class="form-control">
                        <option value="">All</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}"
                                {{ request('state') == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent"  {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid"  {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

    <div class="col-md-2">
    <label class="form-label small text-muted">Date</label>
    <select name="period" class="form-control">
        <option value="">All time</option>

        <option value="this_month"
            {{ request('period') === 'this_month' ? 'selected' : '' }}>
            This month
        </option>

        <option value="last_3_months"
            {{ request('period') === 'last_3_months' ? 'selected' : '' }}>
            Last 3 months
        </option>

        <option value="last_6_months"
            {{ request('period') === 'last_6_months' ? 'selected' : '' }}>
            Last 6 months
        </option>

        <option value="last_12_months"
            {{ request('period') === 'last_12_months' ? 'selected' : '' }}>
            Last 12 months
        </option>

        <option value="this_year"
            {{ request('period') === 'this_year' ? 'selected' : '' }}>
            This year
        </option>
    </select>
</div>


                <div class="col-md-1 d-flex gap-2">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>

                    <a href="{{ route('superadmin.invoices.index') }}"
                       class="btn btn-outline-secondary w-100">
                        <i class="fas fa-eraser"></i>
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Bill To</th>
                        <th>Date</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="fw-semibold">
                                {{ $invoice->invoice_number }}
                            </td>

                            <td>
                                {{ $invoice->companyLocation->user->company_name ?? '—' }}
                            </td>

                            <td>
                                {{ $invoice->companyLocation->state ?? '—' }}
                                {{ $invoice->companyLocation->city ? ' - '.$invoice->companyLocation->city : '' }}
                            </td>

                            <td>
                                {{ $invoice->bill_to ?? '—' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
                            </td>

                            <td class="text-end fw-semibold">
                                ${{ number_format($invoice->total, 2) }}
                            </td>

                            <td class="text-center">
                                <span class="badge
                                    {{ $invoice->status === 'paid' ? 'bg-success' :
                                       ($invoice->status === 'sent' ? 'bg-primary' : 'bg-secondary') }}">
                                    {{ strtoupper($invoice->status) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('superadmin.invoices.show', $invoice) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No invoices found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $invoices->links() }}
    </div>

</div>
@endsection
