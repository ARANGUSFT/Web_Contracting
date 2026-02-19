@extends('admin.layouts.superadmin')

@section('content')
<div class="container py-4">
    <div class="mb-5">
        <h1 class="h3 fw-normal text-dark mb-2">Prepare Invoice for PDF</h1>
        <p class="text-muted">Customize invoice details before generating the PDF document.</p>
    </div>

    <form method="POST" action="{{ route('superadmin.invoices.generateCustomPdf', $invoice) }}" id="invoiceForm">
        @csrf

        {{-- INVOICE DETAILS --}}
        <div class="mb-8 p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Invoice Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Basic information for this invoice</p>
                </div>
                
                {{-- Optional: Add action buttons here if needed --}}
                {{-- <div class="mt-3 sm:mt-0">
                    <button type="button" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Edit Details
                    </button>
                </div> --}}
            </div>
            
            <div class="space-y-6">
                {{-- Top Row: Trailer & Crew --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Trailer Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Trailer Status
                            </span>
                        </label>
                        <div class="flex items-center">
                            <div class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-medium
                                {{ $invoice->crew?->has_trailer 
                                    ? 'bg-green-50 text-green-800 border border-green-200' 
                                    : 'bg-gray-50 text-gray-700 border border-gray-200' }}">
                                @if($invoice->crew?->has_trailer)
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    With Trailer
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    No Trailer
                                @endif
                            </div>
                        </div>
                        @if($invoice->crew?->has_trailer)
                            <p class="mt-1.5 text-xs text-green-600">Trailer fees may apply to this invoice</p>
                        @endif
                    </div>
                    
                    {{-- Crew --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                Assigned Crew
                            </span>
                        </label>
                        <div class="flex items-center">
                            @if($invoice->crew)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-medium text-sm">
                                            {{ substr($invoice->crew->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-gray-900 font-medium">{{ $invoice->crew->name }}</div>
                                        <div class="text-xs text-gray-500">Crew ID: {{ $invoice->crew->id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    <span class="font-medium">Not assigned</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Middle Row: Invoice Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Invoice Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="invoice_date">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Invoice Date
                            </span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input 
                                type="date" 
                                id="invoice_date"
                                name="invoice_date" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ $invoice->invoice_date }}"
                                required
                            >
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500">The date this invoice was created</p>
                    </div>
                    
                    {{-- Optional: Add Due Date field here if needed --}}
                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                        <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm">
                    </div> --}}
                </div>
                
                {{-- Bottom Row: Job Address --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="address">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Job Address
                        </span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="address"
                            name="address" 
                            class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            value="{{ old('address', $invoice->address) }}"
                            placeholder="Enter full address (e.g., 123 Main St, City, State ZIP)"
                            required
                        >
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500">The location where the work was performed</p>
                </div>
            </div>
        </div>

        {{-- ADD ITEMS SECTION --}}
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <h6 class="text-uppercase text-muted fw-semibold mb-0">Add Items</h6>
                <div class="border-bottom flex-grow-1 mx-3"></div>
            </div>

            <div class="row g-3">
                {{-- PREDEFINED ITEM --}}
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <label class="form-label small text-muted fw-medium">Add Predefined Item</label>
                            <div class="row g-2">
                                <div class="col-7">
                                 <select class="form-select form-select-sm" id="availableItemSelect">
                                    <option value="">Select from catalog...</option>

                                    @foreach($availableItems->groupBy(fn($item) => $item->category->name ?? 'Uncategorized') as $category => $items)
                                        <optgroup label="{{ $category }}">
                                            @foreach($items as $item)

                                                @php
                                                    $price = $invoice->crew
                                                        ? $item->getCrewPrice($invoice->crew->has_trailer)
                                                        : 0;
                                                @endphp

                                                <option value="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-description="{{ $item->description }}"
                                                    data-price="{{ $price }}">
                                                    
                                                    {{ $item->name }} — ${{ number_format($price, 2) }}

                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>


                                </div>
                                <div class="col-3">
                                    <input type="number" id="newItemQty" class="form-control form-control-sm" 
                                           value="1" min="1" placeholder="Qty">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addNewItem()">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CUSTOM ITEM --}}
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <label class="form-label small text-muted fw-medium">Add Custom Item</label>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="text" id="customDescription" class="form-control form-control-sm" 
                                           placeholder="Description">
                                </div>
                                <div class="col-2">
                                    <input type="number" id="customPrice" step="0.01" class="form-control form-control-sm" 
                                           placeholder="Price">
                                </div>
                                <div class="col-2">
                                    <input type="number" id="customQty" class="form-control form-control-sm" 
                                           value="1" min="1" placeholder="Qty">
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="addCustomItem()">
                                        Add Custom
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ITEMS TABLE --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="text-uppercase text-muted fw-semibold mb-0">Invoice Items</h6>
                    <small class="text-muted">Items included in this invoice</small>
                </div>
                <span class="badge bg-light text-dark border">
                    {{ $invoice->payoutItems->count() }}
                </span>
            </div>

            <div class="table-responsive border rounded">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 border-0" style="width: 50%;">Description</th>
                            <th class="text-end border-0">Price</th>
                            <th class="text-end border-0">Qty</th>
                            <th class="text-end border-0">Total</th>
                            <th class="text-center border-0" style="width: 50px;"></th>
                        </tr>
                    </thead>
              <tbody id="invoiceItemsTableBody">
@foreach($invoice->payoutItems as $index => $item)
<tr class="border-top">

    <td class="ps-3">
        <input type="hidden"
               name="items[{{ $index }}][description]"
               value="{{ $item->description }}">
        <div class="text-dark">
            {{ $item->description }}
        </div>
    </td>

    <td>
        <input type="number"
               step="0.01"
               name="items[{{ $index }}][price]"
               class="form-control form-control-sm text-end price-input"
               value="{{ old('items.' . $index . '.price', $item->price) }}"
               style="width: 100px;">
    </td>

    <td>
        <input type="number"
               name="items[{{ $index }}][quantity]"
               class="form-control form-control-sm text-end quantity-input"
               value="{{ $item->quantity }}"
               style="width: 80px;">
    </td>

    <td class="text-end text-dark fw-medium">
        $<span class="subtotal-text">
            {{ number_format($item->price * $item->quantity, 2) }}
        </span>
    </td>

    <td class="text-center">
        <button type="button"
                class="btn btn-sm btn-link text-danger"
                onclick="removeRow(this)">
            <i class="fas fa-times"></i>
        </button>
    </td>

</tr>
@endforeach
</tbody>


                </table>
            </div>
        </div>

        {{-- TOTALS --}}
        <div class="row justify-content-end mb-5">
            <div class="col-md-4">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-medium">$<span id="subtotalTotal">0.00</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax</span>
                        <span class="fw-medium">$<span id="taxTotal">0.00</span></span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium">Total</span>
                        <span class="fw-bold fs-5">$<span id="grandTotal">0.00</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="border-top pt-4">
            <div class="d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-file-pdf me-2"></i> Generate PDF Invoice
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@php
    $rowCount = $invoice->payoutItems->count() > 0 ? $invoice->payoutItems->count() : $invoice->items->count();
@endphp

<script>
let rowIndex = {{ $rowCount }};

function addNewItem() {
    const select = document.getElementById('availableItemSelect');
    const selected = select.options[select.selectedIndex];
    const qty = parseInt(document.getElementById('newItemQty').value) || 1;

    if (!selected.value) return;

    const description = selected.dataset.description || selected.dataset.name;
    const price = parseFloat(selected.dataset.price) || 0;
    addItemToTable(description, price, qty);
    
    select.selectedIndex = 0;
    document.getElementById('newItemQty').value = 1;
}

function addCustomItem() {
    const desc = document.getElementById('customDescription').value.trim();
    const price = parseFloat(document.getElementById('customPrice').value);
    const qty = parseInt(document.getElementById('customQty').value);

    if (!desc || isNaN(price) || isNaN(qty)) return;

    addItemToTable(desc, price, qty);
    
    document.getElementById('customDescription').value = '';
    document.getElementById('customPrice').value = '';
    document.getElementById('customQty').value = 1;
}

function addItemToTable(description, price, quantity) {
    const tableBody = document.getElementById('invoiceItemsTableBody');
    
    const newRow = document.createElement('tr');
    newRow.className = 'border-top';
    newRow.innerHTML = `
        <td class="ps-3">
            <input type="hidden" name="items[${rowIndex}][description]" value="${description}">
            <div class="text-dark">${description}</div>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${rowIndex}][price]"
                   class="form-control form-control-sm text-end price-input" style="width: 100px;"
                   value="${price.toFixed(2)}">
        </td>
        <td>
            <input type="number" name="items[${rowIndex}][quantity]"
                   class="form-control form-control-sm text-end quantity-input" style="width: 80px;"
                   value="${quantity}" min="1">
        </td>
        <td class="text-end text-dark fw-medium">
            $<span class="subtotal-text">${(price * quantity).toFixed(2)}</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    
    tableBody.appendChild(newRow);
    rowIndex++;
    updateSubtotals();
}

function removeRow(button) {
    button.closest('tr').remove();
    updateSubtotals();
}

function updateSubtotals() {
    let subtotal = 0;
    document.querySelectorAll('#invoiceItemsTableBody tr').forEach(row => {
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        const qty = parseInt(row.querySelector('.quantity-input')?.value) || 0;
        const lineTotal = price * qty;
        subtotal += lineTotal;

        const target = row.querySelector('.subtotal-text');
        if (target) {
            target.textContent = lineTotal.toFixed(2);
        }
    });

    const tax = 0;
    const total = subtotal + tax;

    document.getElementById('subtotalTotal').textContent = subtotal.toFixed(2);
    document.getElementById('taxTotal').textContent = tax.toFixed(2);
    document.getElementById('grandTotal').textContent = total.toFixed(2);
}

document.addEventListener('change', function (e) {
    if (
        e.target.classList.contains('price-input') ||
        e.target.classList.contains('quantity-input')
    ) {
        updateSubtotals();
    }
});



document.addEventListener('DOMContentLoaded', updateSubtotals);
</script>

<style>
.card {
    background: #fff;
    border: 1px solid #dee2e6;
}

.form-control, .form-select {
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.form-control:focus, .form-select:focus {
    border-color: #6c757d;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.1);
}

.table th {
    font-weight: 500;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 0.75rem;
    vertical-align: middle;
}

.btn-link.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.1);
    border-radius: 4px;
}

.border-bottom {
    border-bottom: 1px solid #e9ecef !important;
}

.text-muted {
    color: #6c757d !important;
}

.bg-light-subtle {
    background-color: #f8f9fa;
}
</style>