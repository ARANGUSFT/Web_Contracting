@extends('admin.layouts.superadmin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('superadmin.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <div>
                <h3 class="mb-1">Edit Invoice</h3>
                <small class="text-muted">{{ $invoice->invoice_number }}</small>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <span class="badge 
                {{ $invoice->status === 'paid' ? 'bg-success' : 
                ($invoice->status === 'sent' ? 'bg-primary' : 'bg-secondary') }} align-self-center">
                {{ strtoupper($invoice->status) }}
            </span>
            <button class="btn btn-primary" onclick="updateInvoice()">
                <i class="fas fa-save me-1"></i> Update Invoice
            </button>
        </div>
    </div>

    {{-- Alert --}}
    <div id="alertBox" class="alert alert-danger d-none mb-3"></div>

    <div class="row g-3">
        {{-- LEFT SIDE --}}
        <div class="col-lg-8">
            {{-- INVOICE INFO --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Invoice Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <select id="company" class="form-select">
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
                            <select id="location" class="form-select">
                                <option value="{{ $invoice->company_location_id }}">
                                    {{ $invoice->companyLocation->state }}
                                    {{ $invoice->companyLocation->city ? ' - '.$invoice->companyLocation->city : '' }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Crew</label>
                            <select id="crew_id" class="form-select">
                                <option value="">Select crew</option>
                                @foreach($crews as $crew)
                                    <option value="{{ $crew->id }}"
                                        {{ $invoice->crew_id == $crew->id ? 'selected' : '' }}>
                                        {{ $crew->name }}
                                        {{ $crew->has_trailer ? '-Trailer' : '-No trailer' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-md-6">
                            <label class="form-label">Customer Email</label>
                            <input type="email" id="customer_email"
                                   class="form-control"
                                   value="{{ $invoice->customer_email }}">
                        </div>

                        {{-- Address --}}
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address"
                                value="{{ old('address', $invoice->address) }}">
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
                            <select id="status" class="form-select">
                                <option value="draft" {{ $invoice->status=='draft'?'selected':'' }}>Draft</option>
                                <option value="sent" {{ $invoice->status=='sent'?'selected':'' }}>Sent</option>
                                <option value="paid" {{ $invoice->status=='paid'?'selected':'' }}>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Notes (optional)</label>
                            <textarea id="notes" class="form-control" rows="2"
                                      placeholder="Notes that will appear on the invoice">{{ $invoice->notes }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Memo (optional)</label>
                            <textarea id="memo" class="form-control" rows="2"
                                      placeholder="Internal memo">{{ $invoice->memo }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ADD ITEMS SECTION --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Add Items to Invoice</h6>
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Select Item</label>
                            <select id="itemSelect" class="form-select">
                                <option value="">Select an item...</option>
                                <option value="" disabled>Loading items...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <input type="number" id="itemQuantity" class="form-control" 
                                       min="1" value="1">
                                <button class="btn btn-outline-secondary" type="button" onclick="addItem()">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Unit Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" id="itemPrice" class="form-control" 
                                       placeholder="0.00" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <small class="text-muted mt-2 d-block">
                        Select an item to see its price. The price comes from the selected location.
                    </small>
                </div>
            </div>

            {{-- INVOICE ITEMS --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Invoice Items</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center" width="150">Quantity</th>
                                    <th class="text-end" width="120">Price</th>
                                    <th class="text-end" width="120">Total</th>
                                    <th class="text-center" width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                @if(count($invoiceItems) > 0)
                                    @foreach($invoiceItems as $item)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="fw-medium">{{ $item['name'] }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity({{ $loop->index }})">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="text" 
                                                       class="form-control form-control-sm text-center quantity-input" 
                                                       value="{{ $item['quantity'] }}"
                                                       style="width: 60px;"
                                                       onchange="updateQuantity({{ $loop->index }}, this.value)">
                                                <button class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity({{ $loop->index }})">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-end align-middle">
                                            <span class="price-display">${{ number_format($item['price'], 2) }}</span>
                                        </td>
                                        <td class="text-end align-middle fw-medium">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-outline-danger" onclick="removeItem({{ $loop->index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No items added to this invoice
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Summary</h6>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-semibold" id="subtotal">${{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span class="fw-semibold" id="taxDisplay">${{ number_format($invoice->tax, 2) }}</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <span class="h6">Total:</span>
                            <span class="h5 fw-bold text-primary" id="total">${{ number_format($invoice->total, 2) }}</span>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary w-100 mb-2" onclick="updateInvoice()">
                        <i class="fas fa-save me-1"></i> Update Invoice
                    </button>
                    
                    <a href="{{ route('superadmin.invoices.show', $invoice) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </div>
            
            {{-- Quick Info --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Info</h6>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Customer</small>
                        <span class="fw-semibold">{{ $invoice->bill_to }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Email</small>
                        <span class="fw-semibold">{{ $invoice->customer_email }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Due Date</small>
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y') }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Items</small>
                        <span class="fw-semibold" id="itemsCount">{{ count($invoiceItems) }}</span>
                    </div>
                    
                    <div class="mb-0">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge 
                            {{ $invoice->status === 'paid' ? 'bg-success' : 
                            ($invoice->status === 'sent' ? 'bg-primary' : 'bg-secondary') }}">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/* =====================================================
   GLOBALS & HELPERS
===================================================== */
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// Items ya cargados desde el backend (edit)
let invoiceItems = @json($invoiceItems ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);

// Location actual
let currentLocationId = {{ $invoice->company_location_id }};
let allItems = [];

// Helpers
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

function showAlert(message, type = 'danger') {
    const alertBox = document.getElementById('alertBox');
    alertBox.textContent = message;
    alertBox.className = `alert alert-${type} mb-3`;
    alertBox.classList.remove('d-none');
    
    setTimeout(() => {
        alertBox.classList.add('d-none');
    }, 5000);
}

// Actualizar el resumen
function updateSummary() {
    let subtotal = 0;
    
    invoiceItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    
    const tax = parseFloat("{{ $invoice->tax }}") || 0;
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('taxDisplay').textContent = formatCurrency(tax);
    document.getElementById('total').textContent = formatCurrency(total);
    document.getElementById('itemsCount').textContent = invoiceItems.length;
}

/* =====================================================
   LOAD ITEMS BY LOCATION
===================================================== */
document.getElementById('location').addEventListener('change', async function(e) {
    currentLocationId = e.target.value;
    const itemSelect = document.getElementById('itemSelect');

    if (!currentLocationId) {
        itemSelect.innerHTML = '<option value="">Select a location first</option>';
        return;
    }

    itemSelect.innerHTML = '<option value="">Loading items...</option>';

    try {
        const response = await fetch(`/superadmin/invoices/location/${currentLocationId}/items`);
        const items = await response.json();
        
        allItems = items;
        updateItemSelect(items);
    } catch (error) {
        console.error('Error:', error);
        itemSelect.innerHTML = '<option value="">Error loading items</option>';
    }
});

/* =====================================================
   UPDATE ITEM SELECT DROPDOWN
===================================================== */
function updateItemSelect(items) {
    const itemSelect = document.getElementById('itemSelect');
    itemSelect.innerHTML = '<option value="">Select an item...</option>';

    if (!items || !items.length) {
        itemSelect.innerHTML += '<option value="" disabled>No items available</option>';
        return;
    }

    // Agrupar por categoría
    const grouped = {};

    items.forEach(item => {
        const category = item.category || 'Uncategorized';

        if (!grouped[category]) {
            grouped[category] = [];
        }

        grouped[category].push(item);
    });

    // Crear optgroups
    Object.keys(grouped).forEach(category => {
        const optgroup = document.createElement('optgroup');
        optgroup.label = category;

        grouped[category].forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.name} - ${formatCurrency(item.price)}`;
            option.dataset.price = item.price;
            option.dataset.name = item.name;

            optgroup.appendChild(option);
        });

        itemSelect.appendChild(optgroup);
    });
}


/* =====================================================
   ADD ITEM FUNCTIONS
===================================================== */
function addItem() {
    const itemSelect = document.getElementById('itemSelect');
    const quantityInput = document.getElementById('itemQuantity');
    
    const itemId = itemSelect.value;
    const quantity = parseInt(quantityInput.value) || 1;
    
    if (!itemId) {
        showAlert('Please select an item first');
        return;
    }
    
    if (quantity < 1) {
        showAlert('Quantity must be at least 1');
        quantityInput.value = 1;
        return;
    }
    
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    const price = parseFloat(selectedOption.dataset.price) || 0;
    const itemName = selectedOption.dataset.name;
    
    // Check if item already exists in invoice
    const existingIndex = invoiceItems.findIndex(i => Number(i.id) === Number(itemId));

    if (existingIndex > -1) {
        // Update quantity
        invoiceItems[existingIndex].quantity = Number(invoiceItems[existingIndex].quantity) + quantity;
    } else {
        // Add new item
        invoiceItems.push({
            id: Number(itemId),
            name: String(itemName),
            price: price,
            quantity: quantity
        });
    }
    
    // Reset selection
    itemSelect.value = '';
    document.getElementById('itemPrice').value = '';
    quantityInput.value = 1;
    
    // Render invoice items
    renderInvoiceItems();
}

/* =====================================================
   MANAGE INVOICE ITEMS
===================================================== */
function increaseQuantity(index) {
    invoiceItems[index].quantity++;
    renderInvoiceItems();
}

function decreaseQuantity(index) {
    if (invoiceItems[index].quantity > 1) {
        invoiceItems[index].quantity--;
        renderInvoiceItems();
    }
}

function updateQuantity(index, value) {
    const newQuantity = parseInt(value) || 1;
    
    if (newQuantity < 1) {
        showAlert('Quantity must be at least 1');
        invoiceItems[index].quantity = 1;
    } else {
        invoiceItems[index].quantity = newQuantity;
    }
    
    renderInvoiceItems();
}

function removeItem(index) {
    if (confirm('Are you sure you want to remove this item?')) {
        invoiceItems.splice(index, 1);
        renderInvoiceItems();
    }
}

function renderInvoiceItems() {
    const tbody = document.getElementById('invoiceItems');
    
    if (invoiceItems.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    No items added to this invoice
                </td>
            </tr>`;
        updateSummary();
        return;
    }
    
    let html = '';
    invoiceItems.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        
        html += `
            <tr>
                <td class="align-middle">
                    <div class="fw-medium">${item.name}</div>
                </td>
                <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity(${index})">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="text" 
                               class="form-control form-control-sm text-center quantity-input" 
                               value="${item.quantity}"
                               style="width: 60px;"
                               onchange="updateQuantity(${index}, this.value)">
                        <button class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity(${index})">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td class="text-end align-middle">
                    <span class="price-display">${formatCurrency(item.price)}</span>
                </td>
                <td class="text-end align-middle fw-medium">
                    ${formatCurrency(itemTotal)}
                </td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
    });
    
    tbody.innerHTML = html;
    updateSummary();
}

/* =====================================================
   INITIAL LOAD (EDIT MODE)
===================================================== */
document.addEventListener('DOMContentLoaded', async () => {
    // Cargar items para la ubicación actual
    if (currentLocationId) {
        try {
            const response = await fetch(`/superadmin/invoices/location/${currentLocationId}/items`);
            const items = await response.json();
            
            allItems = items;
            updateItemSelect(items);
        } catch (error) {
            console.error('Error loading items:', error);
            document.getElementById('itemSelect').innerHTML = '<option value="">Error loading items</option>';
        }
    }
    
    // Inicializar el resumen
    updateSummary();
});

/* =====================================================
   UPDATE INVOICE
===================================================== */
async function updateInvoice() {
    // Validations
    if (invoiceItems.length === 0) {
        showAlert('Please add at least one item to the invoice');
        return;
    }
    
    const locationId = document.getElementById('location').value;
    if (!locationId) {
        showAlert('Please select a location');
        return;
    }
    
    const billTo = document.getElementById('bill_to').value;
    if (!billTo.trim()) {
        showAlert('Please enter bill to information');
        return;
    }

    const payloadItems = invoiceItems.map(i => ({
        id: i.id,
        price: i.price,
        quantity: i.quantity
    }));

    // Show loading
    const updateBtn = document.querySelector('button[onclick="updateInvoice()"]');
    const originalText = updateBtn.innerHTML;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
    updateBtn.disabled = true;

    try {
        const response = await fetch("{{ route('superadmin.invoices.update', $invoice) }}", {
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": csrf,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                company_location_id: locationId,
                crew_id: document.getElementById('crew_id').value,
                invoice_date: document.getElementById('invoice_date').value,
                due_date: document.getElementById('due_date').value,
                customer_email: document.getElementById('customer_email').value,
                bill_to: billTo,
                address: document.getElementById('address').value,
                status: document.getElementById('status').value,
                notes: document.getElementById('notes').value,
                memo: document.getElementById('memo').value,
                items: payloadItems
            })

        });
        
        const res = await response.json();
        
        updateBtn.innerHTML = originalText;
        updateBtn.disabled = false;
        
        if (res.success) {
            showAlert('Invoice updated successfully', 'success');
            setTimeout(() => {
                window.location.href = `/superadmin/invoices/${res.invoice_id}`;
            }, 1500);
        } else {
            showAlert(res.message || 'Error updating invoice');
        }
    } catch (error) {
        console.error('Error:', error);
        updateBtn.innerHTML = originalText;
        updateBtn.disabled = false;
        showAlert('Server error while updating invoice');
    }
}
</script>

<style>
.card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.card-title {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

.form-label {
    font-weight: 500;
    color: #555;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #555;
    border-bottom: 2px solid #e0e0e0;
}

.table td {
    vertical-align: middle;
    border-color: #f0f0f0;
    font-size: 0.875rem;
}

.input-group .btn {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.quantity-input {
    width: 60px !important;
    text-align: center;
}

hr {
    opacity: 0.3;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
}
</style>
@endsection