@extends('admin.layouts.superadmin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('superadmin.invoices.index') }}"
            class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>

            <div>
                <h2 class="fw-bold mb-0">Edit Invoice</h2>
                <small class="text-muted">{{ $invoice->invoice_number }}</small>
            </div>
        </div>

        <span class="badge 
            {{ $invoice->status === 'paid' ? 'bg-success' : 
            ($invoice->status === 'sent' ? 'bg-primary' : 'bg-secondary') }}">
            {{ strtoupper($invoice->status) }}
        </span>

    </div>


    <div id="alertBox" class="alert alert-danger d-none"></div>

    <div class="row">

        {{-- LEFT SIDE --}}
        <div class="col-md-8">

            {{-- INVOICE INFO --}}
            <div class="card mb-4">
                <div class="card-header fw-bold">Invoice Information</div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Company</label>
                        <select id="company" class="form-control">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $invoice->companyLocation->user_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <select id="location" class="form-control">
                            <option value="{{ $invoice->company_location_id }}">
                                {{ $invoice->companyLocation->state }}
                                {{ $invoice->companyLocation->city ? ' - '.$invoice->companyLocation->city : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Customer Email</label>
                        <input type="email" id="customer_email"
                               class="form-control"
                               value="{{ $invoice->customer_email }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Bill To</label>
                        <input type="text" id="bill_to"
                               class="form-control"
                               value="{{ $invoice->bill_to }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" id="invoice_date"
                               class="form-control"
                               value="{{ $invoice->invoice_date }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Due Date</label>
                        <input type="date" id="due_date"
                               class="form-control"
                               value="{{ $invoice->due_date }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select id="status" class="form-control">
                            <option value="draft" {{ $invoice->status=='draft'?'selected':'' }}>Draft</option>
                            <option value="sent"  {{ $invoice->status=='sent'?'selected':'' }}>Sent</option>
                            <option value="paid"  {{ $invoice->status=='paid'?'selected':'' }}>Paid</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- AVAILABLE ITEMS --}}
            <div class="card mb-4">
                <div class="card-header fw-bold">Available Items</div>
                <div class="card-body p-0">
                    <table class="table mb-0" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th width="120">Price</th>
                                <th width="120">Qty</th>
                                <th width="100">Add</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Select a company to load items
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- INVOICE ITEMS --}}
            <div class="card mb-4">
                <div class="card-header fw-bold">Invoice Items</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th width="120">Qty</th>
                                <th width="120">Price</th>
                                <th width="120">Total</th>
                                <th width="80"></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems"></tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- SUMMARY --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header fw-bold">Summary</div>
                <div class="card-body">

                    <p>Subtotal: <strong id="subtotal">$0.00</strong></p>
                    <p>Tax: <strong id="taxDisplay">$0.00</strong></p>
                    <p>Total: <strong id="total">$0.00</strong></p>

                    <button class="btn btn-primary w-100 mt-3"
                            onclick="updateInvoice()">
                        Update Invoice
                    </button>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- ================== JAVASCRIPT ================== --}}
<script>
/* =====================================================
   GLOBALS & HELPERS
===================================================== */
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// Items ya cargados desde el controlador
let invoiceItems = @json($invoiceItems);

// Location actual seleccionada
let currentLocationId = document.getElementById('location')?.value || null;

function money(n) {
    return '$' + Number(n).toFixed(2);
}

/* =====================================================
   LOAD LOCATIONS BY COMPANY
===================================================== */
document.getElementById('company').addEventListener('change', e => {
    const companyId = e.target.value;
    const locationSelect = document.getElementById('location');

    locationSelect.innerHTML = '<option value="">Select location</option>';
    currentLocationId = null;

    if (!companyId) return;

    fetch(`/superadmin/companies/${companyId}/locations`)
        .then(r => r.json())
        .then(data => {
            data.forEach(l => {
                locationSelect.innerHTML += `
                    <option value="${l.id}">
                        ${l.state} ${l.city ? ' - ' + l.city : ''}
                    </option>`;
            });
        });
});

/* =====================================================
   LOAD AVAILABLE ITEMS BY LOCATION
===================================================== */
document.getElementById('location').addEventListener('change', e => {
    currentLocationId = e.target.value;

    if (!currentLocationId) return;

    fetch(`/superadmin/locations/${currentLocationId}/items/json`)
        .then(r => r.json())
        .then(items => renderAvailableItems(items));
});

/* =====================================================
   RENDER AVAILABLE ITEMS
===================================================== */
function renderAvailableItems(items) {
    const tbody = document.querySelector('#itemsTable tbody');
    tbody.innerHTML = '';

    if (!items.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No items available
                </td>
            </tr>`;
        return;
    }

    items.forEach(i => {
        tbody.innerHTML += `
            <tr>
                <td>${i.name}</td>
                <td>${money(i.price)}</td>
                <td>
                    <input type="number" min="1" value="1"
                           id="qty-${i.id}" class="form-control">
                </td>
                <td>
                    <button class="btn btn-sm btn-primary"
                        onclick="addItem(${i.id}, '${i.name}', ${i.price})">
                        Add
                    </button>
                </td>
            </tr>`;
    });
}

/* =====================================================
   ADD / REMOVE ITEMS
===================================================== */
function addItem(id, name, price) {
    const qtyInput = document.getElementById(`qty-${id}`);
    const qty = qtyInput ? parseInt(qtyInput.value) : 1;

    const existing = invoiceItems.find(i => i.id === id);

    if (existing) {
        existing.quantity += qty;
    } else {
        invoiceItems.push({ id, name, price, quantity: qty });
    }

    renderInvoice();

    // 🔥 IMPORTANTE: NO perder los available items
    if (currentLocationId) {
        fetch(`/superadmin/locations/${currentLocationId}/items/json`)
            .then(r => r.json())
            .then(items => renderAvailableItems(items));
    }
}

function removeItem(index) {
    invoiceItems.splice(index, 1);
    renderInvoice();
}

/* =====================================================
   RENDER INVOICE ITEMS + TOTALS
===================================================== */
function renderInvoice() {
    const tbody = document.getElementById('invoiceItems');
    tbody.innerHTML = '';

    let subtotal = 0;

    if (!invoiceItems.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No items added
                </td>
            </tr>`;
    }

    invoiceItems.forEach((i, idx) => {
        const lineTotal = i.price * i.quantity;
        subtotal += lineTotal;

        tbody.innerHTML += `
            <tr>
                <td>${i.name}</td>
                <td>${i.quantity}</td>
                <td>${money(i.price)}</td>
                <td>${money(lineTotal)}</td>
                <td>
                    <button class="btn btn-sm btn-danger"
                        onclick="removeItem(${idx})">
                        X
                    </button>
                </td>
            </tr>`;
    });

    document.getElementById('subtotal').innerText   = money(subtotal);
    document.getElementById('taxDisplay').innerText = money(0);
    document.getElementById('total').innerText      = money(subtotal);
}

/* =====================================================
   INITIAL LOAD (EDIT MODE)
===================================================== */
document.addEventListener('DOMContentLoaded', () => {
    renderInvoice();

    const companySelect  = document.getElementById('company');
    const locationSelect = document.getElementById('location');

    if (companySelect?.value && locationSelect?.value) {
        currentLocationId = locationSelect.value;

        // Cargar locations
        fetch(`/superadmin/companies/${companySelect.value}/locations`)
            .then(r => r.json())
            .then(data => {
                locationSelect.innerHTML = '<option value="">Select location</option>';

                data.forEach(l => {
                    locationSelect.innerHTML += `
                        <option value="${l.id}"
                            ${l.id == currentLocationId ? 'selected' : ''}>
                            ${l.state} ${l.city ? ' - ' + l.city : ''}
                        </option>`;
                });

                // 🔥 Cargar items automáticamente
                locationSelect.dispatchEvent(new Event('change'));
            });
    }
});

/* =====================================================
   UPDATE INVOICE
===================================================== */
function updateInvoice() {
    fetch("{{ route('superadmin.invoices.update', $invoice) }}", {
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": csrf,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            company_location_id: document.getElementById('location').value,
            invoice_date: document.getElementById('invoice_date').value,
            due_date: document.getElementById('due_date').value,
            customer_email: document.getElementById('customer_email').value,
            bill_to: document.getElementById('bill_to').value,
            status: document.getElementById('status').value,
            items: invoiceItems
        })
    })
    .then(r => r.json())
    .then(res => {
        alert('Invoice updated successfully');
        window.location.href = `/superadmin/invoices/${res.invoice_id}`;
    })
    .catch(() => alert('Error updating invoice'));
}
</script>

@endsection
