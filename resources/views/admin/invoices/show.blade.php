@extends('admin.layouts.superadmin')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                Invoice #{{ $invoice->invoice_number }}
            </h2>
            <small class="text-muted">
                Created on {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
            </small>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge
                {{ $invoice->status === 'paid' ? 'bg-success' :
                   ($invoice->status === 'sent' ? 'bg-primary' : 'bg-secondary') }}">
                {{ strtoupper($invoice->status) }}
            </span>

            <a href="{{ route('superadmin.invoices.edit', $invoice) }}"
               class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        </div>
    </div>

    {{-- BILL TO / INFO --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Bill To</div>
                <div class="card-body">
                    <strong>{{ $invoice->bill_to ?? '—' }}</strong><br>
                    {{ $invoice->customer_email ?? '—' }}<br>
                    {{ $invoice->companyLocation->state ?? '' }}
                    {{ $invoice->companyLocation->city ? ' - '.$invoice->companyLocation->city : '' }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Invoice Details</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                    <p class="mb-1"><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                    <p class="mb-0"><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ITEMS --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">Items</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">${{ number_format($item->price, 2) }}</td>
                            <td class="text-end fw-semibold">
                                ${{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOTALS --}}
    <div class="row justify-content-end mb-4">
        <div class="col-md-4">
            <table class="table">
                <tr>
                    <th>Subtotal</th>
                    <td class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td class="text-end">${{ number_format($invoice->tax, 2) }}</td>
                </tr>
                <tr class="table-light">
                    <th>Total</th>
                    <td class="text-end fw-bold">
                        ${{ number_format($invoice->total, 2) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- MEMO & NOTES --}}
    @if($invoice->memo || $invoice->notes)
    <div class="row mb-4">
        @if($invoice->memo)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Memo</div>
                <div class="card-body">
                    {{ $invoice->memo }}
                </div>
            </div>
        </div>
        @endif

        @if($invoice->notes)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Notes</div>
                <div class="card-body">
                    {{ $invoice->notes }}
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ATTACHMENTS --}}
    @if($invoice->attachments->count())
    <div class="card mb-4">
        <div class="card-header fw-bold">Attachments</div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($invoice->attachments as $file)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-paperclip me-2"></i>
                            {{ $file->original_name }}
                        </span>
                        <a href="{{ asset('storage/'.$file->file_path) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary">
                            Download
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- FOOTER ACTIONS --}}
    <div class="d-flex gap-2">
        <a href="{{ route('superadmin.invoices.index') }}"
           class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Invoices
        </a>
    </div>

</div>
@endsection
