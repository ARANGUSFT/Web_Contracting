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
            <button class="btn btn-primary" onclick="updateInvoice()" id="updateButton">
                <i class="fas fa-save me-1"></i> Update Invoice
            </button>
        </div>
    </div>

    {{-- Top alert --}}
    <div id="topAlert" class="alert alert-danger d-none mb-3"></div>

    <div class="row g-3">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">
            {{-- Invoice Information --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-file-invoice me-2"></i>Invoice Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select id="company" class="form-select" onchange="onCompanyChange()">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ $invoice->companyLocation->user_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="company_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <select id="location" class="form-select" disabled>
                                <option value="{{ $invoice->company_location_id }}">
                                    {{ $invoice->companyLocation->state }}
                                    {{ $invoice->companyLocation->city ? ' - '.$invoice->companyLocation->city : '' }}
                                </option>
                            </select>
                            <div id="location_error" class="invalid-feedback"></div>
                        </div>
 
                       <div class="col-md-6">
                            <label class="form-label">Crew</label>
                            <select id="crew_id" class="form-select" disabled>
                                @php $selectedCrew = $crews->firstWhere('id', $invoice->crew_id); @endphp
                                <option value="{{ $invoice->crew_id }}">
                                    {{ $selectedCrew ? $selectedCrew->name . ($selectedCrew->has_trailer ? ' (Trailer)' : ' (No trailer)') : 'No crew' }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Customer Email</label>
                            <input type="email" id="customer_email"
                                   class="form-control"
                                   value="{{ $invoice->customer_email }}"
                                   onblur="validateField('customer_email', 'email', 'Invalid email format')">
                            <div id="customer_email_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address"
                                value="{{ old('address', $invoice->address) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bill To <span class="text-danger">*</span></label>
                            <input type="text" id="bill_to"
                                   class="form-control"
                                   value="{{ $invoice->bill_to }}"
                                   onblur="validateField('bill_to', 'required|min:3', 'This field is required and must be at least 3 characters')">
                            <div id="bill_to_error" class="invalid-feedback"></div>
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
                                   value="{{ $invoice->due_date }}"
                                   onblur="validateDueDate()">
                            <div id="due_date_error" class="invalid-feedback"></div>
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

            {{-- Add Items Section --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-plus-circle me-2"></i>Add Items to Invoice</h6>
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Select Item</label>
                            <select id="itemSelect" class="form-select">
                                <option value="">Select an item...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <input type="number" id="itemQuantity" class="form-control" 
                                    min="1" value="1">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="addItem()">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <small class="text-muted mt-2 d-block">
                        Select an item to see its price. The price comes from the selected location.
                    </small>

                    {{-- Custom Item Button --}}
                    <div class="mt-3">
                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                onclick="toggleCustomItem()">
                            + Add Custom Item
                        </button>
                    </div>

                    {{-- Custom Item Box --}}
                    <div id="customItemBox" class="card mt-3 d-none">
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <input type="text" id="customName" 
                                        class="form-control form-control-sm"
                                        placeholder="Item name">
                                    <div id="customName_error" class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" id="customPrice"
                                        class="form-control form-control-sm"
                                        placeholder="Unit price"
                                        step="0.01" min="0.01">
                                    <div id="customPrice_error" class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" id="customQty"
                                        class="form-control form-control-sm"
                                        value="1" min="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button"
                                            class="btn btn-sm btn-success w-100"
                                            onclick="addCustomItem()">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invoice Items --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-list me-2"></i>Invoice Items</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center" width="180">Quantity</th>
                                    <th class="text-end" width="120">Unit Price</th>
                                    <th class="text-end" width="120">Total</th>
                                    <th class="text-center" width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                @if(count($invoiceItems) > 0)
                                    @foreach($invoiceItems as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $item['name'] }}</div>
                                            <textarea 
                                                class="form-control form-control-sm note-textarea mt-1"
                                                placeholder="Add note..."
                                                oninput="updateItemNote({{ $loop->index }}, this.value)"
                                                rows="1">{{ $item['note'] ?? '' }}</textarea>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
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
                                        <td class="text-end">
                                            <span class="price-display">${{ number_format($item['price'], 2) }}</span>
                                        </td>
                                        <td class="text-end fw-medium">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>
                                        <td class="text-center">
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
                    <div id="items_error" class="invalid-feedback" style="display: none;">You must add at least one item</div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: SUMMARY --}}
        <div class="col-lg-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-calculator me-2"></i>Summary</h6>
                    
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
                    
                    <button class="btn btn-primary w-100 mb-2" onclick="updateInvoice()" id="updateButtonSide">
                        <i class="fas fa-save me-1"></i> Update Invoice
                    </button>
                    
                    <a href="{{ route('superadmin.invoices.show', $invoice) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </div>
            
            {{-- Quick Information --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Quick Info</h6>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Customer</small>
                        <span class="fw-semibold" id="quickBillTo">{{ $invoice->bill_to }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Email</small>
                        <span class="fw-semibold" id="quickEmail">{{ $invoice->customer_email }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Due Date</small>
                        <span class="fw-semibold" id="quickDueDate">{{ \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y') }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Items</small>
                        <span class="fw-semibold" id="quickItemsCount">{{ count($invoiceItems) }}</span>
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

<style>
    .extra-small { font-size: 0.75rem; }
    .card { border: 1px solid #e0e0e0; border-radius: 8px; }
    .card-title { font-weight: 600; color: #333; font-size: 1rem; }
    .form-label { font-weight: 500; color: #555; margin-bottom: 0.25rem; font-size: 0.875rem; }
    .table th { font-weight: 600; font-size: 0.875rem; color: #555; border-bottom: 2px solid #e0e0e0; }
    .table td { vertical-align: middle; border-color: #f0f0f0; font-size: 0.875rem; }
    .btn-outline-primary:hover { background-color: #0d6efd; color: white; }
    .badge { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
    hr { opacity: 0.3; }
    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { display: none; width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545; }
    .input-group-sm .form-control, .input-group-sm .btn { height: calc(1.5em + 0.5rem + 2px); }
    .note-textarea { font-size: 0.75rem; resize: none; overflow: hidden; min-height: 1.5rem; background: #f8f9fa; border: none; }
    .note-textarea:focus { background: #fff; border: 1px solid #ced4da; }
    .quantity-input { width: 60px !important; text-align: center; }
</style>

<script>
    // ==================== GLOBAL VARIABLES ====================
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let invoiceItems = @json($invoiceItems ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
    let currentLocationId = {{ $invoice->company_location_id }};
    let allItems = [];

    // ==================== HELPER FUNCTIONS ====================
    function formatCurrency(amount) {
        return '$' + parseFloat(amount || 0).toFixed(2);
    }

    function showAlert(message, type = 'danger') {
        const alertBox = document.getElementById('topAlert');
        alertBox.textContent = message;
        alertBox.className = `alert alert-${type} mb-3`;
        alertBox.classList.remove('d-none');
        setTimeout(() => alertBox.classList.add('d-none'), 5000);
    }

    // ==================== VALIDATION ====================
    function validateField(fieldId, rules, message) {
        const field = document.getElementById(fieldId);
        if (!field) return true;

        const value = field.value.trim();
        let isValid = true;
        let errorMsg = message;

        if (rules.includes('required')) {
            isValid = value !== '';
        }
        if (isValid && rules.includes('email')) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = re.test(value);
            errorMsg = 'Invalid email format';
        }
        if (isValid && rules.includes('min:')) {
            const min = parseInt(rules.split('min:')[1]) || 0;
            isValid = value.length >= min;
            errorMsg = `Must be at least ${min} characters`;
        }

        if (!isValid) {
            field.classList.add('is-invalid');
            let errorDiv = document.getElementById(fieldId + '_error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = fieldId + '_error';
                errorDiv.className = 'invalid-feedback';
                field.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = errorMsg;
            errorDiv.style.display = 'block';
        } else {
            field.classList.remove('is-invalid');
            const errorDiv = document.getElementById(fieldId + '_error');
            if (errorDiv) errorDiv.style.display = 'none';
        }
        return isValid;
    }

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) field.classList.remove('is-invalid');
        const errorDiv = document.getElementById(fieldId + '_error');
        if (errorDiv) errorDiv.style.display = 'none';
    }

    function validateDueDate() {
        const invoiceDate = document.getElementById('invoice_date').value;
        const dueDate = document.getElementById('due_date').value;
        if (invoiceDate && dueDate && dueDate < invoiceDate) {
            showFieldError('due_date', 'Due date cannot be before invoice date');
            return false;
        } else {
            clearFieldError('due_date');
            return true;
        }
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        field.classList.add('is-invalid');
        let errorDiv = document.getElementById(fieldId + '_error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = fieldId + '_error';
            errorDiv.className = 'invalid-feedback';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    function validateForm() {
        // Validate main required fields
        const companyOk = validateField('company', 'required', 'Please select a company');
        const locationOk = validateField('location', 'required', 'Please select a location');
        const billToOk = validateField('bill_to', 'required|min:3', 'This field is required and must be at least 3 characters');
        const emailOk = document.getElementById('customer_email').value === '' ? true : validateField('customer_email', 'email', 'Invalid email format');
        const dueDateOk = validateDueDate();

        // Validate at least one item
        const itemsOk = invoiceItems.length > 0;
        const itemsErrorDiv = document.getElementById('items_error');
        if (!itemsOk) {
            itemsErrorDiv.style.display = 'block';
        } else {
            itemsErrorDiv.style.display = 'none';
        }

        // Enable/disable update buttons
        const formValid = companyOk && locationOk && billToOk && emailOk && dueDateOk && itemsOk;
        const updateButtons = document.querySelectorAll('#updateButton, #updateButtonSide');
        updateButtons.forEach(btn => {
            btn.disabled = !formValid;
        });

        return formValid;
    }

    // ==================== COMPANY / LOCATION ====================
    function onCompanyChange() {
        const companyId = document.getElementById('company').value;
        const locationSelect = document.getElementById('location');
        
        if (!companyId) {
            locationSelect.innerHTML = '<option value="">Select a company first</option>';
            locationSelect.disabled = true;
            return;
        }

        locationSelect.innerHTML = '<option value="">Loading...</option>';
        locationSelect.disabled = false;

        fetch(`/superadmin/companies/${companyId}/locations/ajax`)
            .then(response => response.json())
            .then(data => {
                locationSelect.innerHTML = '<option value="">Select location</option>';
                data.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.id;
                    option.textContent = location.city ? `${location.city}, ${location.state}` : location.state;
                    locationSelect.appendChild(option);
                });
                // If current location is in the list, select it
                if (currentLocationId) {
                    const option = Array.from(locationSelect.options).find(opt => opt.value == currentLocationId);
                    if (option) option.selected = true;
                }
            })
            .catch(error => {
                console.error(error);
                locationSelect.innerHTML = '<option value="">Error loading locations</option>';
            });
    }

    // ==================== LOAD ITEMS ====================
    async function loadItemsByLocation(locationId) {
        const itemSelect = document.getElementById('itemSelect');
        if (!locationId) {
            itemSelect.innerHTML = '<option value="">Select a location first</option>';
            return;
        }

        itemSelect.innerHTML = '<option value="">Loading items...</option>';
        try {
            const response = await fetch(`/superadmin/invoices/location/${locationId}/items`);
            const items = await response.json();
            allItems = items;
            updateItemSelect(items);
        } catch (error) {
            console.error(error);
            itemSelect.innerHTML = '<option value="">Error loading items</option>';
        }
    }

    function updateItemSelect(items) {
        const itemSelect = document.getElementById('itemSelect');
        itemSelect.innerHTML = '<option value="">Select an item...</option>';

        if (!items.length) {
            itemSelect.innerHTML += '<option disabled>No items available</option>';
            return;
        }

        // Group by category
        const grouped = {};
        items.forEach(item => {
            const category = item.category || 'Uncategorized';
            if (!grouped[category]) grouped[category] = [];
            grouped[category].push(item);
        });

        Object.keys(grouped).sort().forEach(category => {
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

    // ==================== CUSTOM ITEM ====================
    function toggleCustomItem() {
        document.getElementById('customItemBox').classList.toggle('d-none');
    }

    function addCustomItem() {
        const name = document.getElementById('customName').value.trim();
        const price = parseFloat(document.getElementById('customPrice').value) || 0;
        const qty = parseInt(document.getElementById('customQty').value) || 1;

        // Validations
        let isValid = true;
        if (!name) {
            showFieldError('customName', 'Item name is required');
            isValid = false;
        } else {
            clearFieldError('customName');
        }
        if (price <= 0) {
            showFieldError('customPrice', 'Price must be greater than 0');
            isValid = false;
        } else {
            clearFieldError('customPrice');
        }
        if (!isValid) return;

        invoiceItems.push({
            id: null,
            name: name,
            price: price,
            quantity: qty,
            note: ''
        });

        document.getElementById('customName').value = '';
        document.getElementById('customPrice').value = '';
        document.getElementById('customQty').value = 1;
        document.getElementById('customItemBox').classList.add('d-none');

        renderInvoiceItems();
        validateForm();
    }

    // ==================== ADD ITEM FROM DROPDOWN ====================
    function addItem() {
        const itemSelect = document.getElementById('itemSelect');
        const quantityInput = document.getElementById('itemQuantity');

        const itemId = itemSelect.value;
        const quantity = parseInt(quantityInput.value) || 1;

        if (!itemId) {
            showAlert('Please select an item first');
            return;
        }

        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const itemName = selectedOption.dataset.name;

        const existingIndex = invoiceItems.findIndex(i => Number(i.id) === Number(itemId));

        if (existingIndex > -1) {
            invoiceItems[existingIndex].quantity += quantity;
        } else {
            invoiceItems.push({
                id: Number(itemId),
                name: itemName,
                price: price,
                quantity: quantity,
                note: ''
            });
        }

        itemSelect.value = '';
        quantityInput.value = 1;

        renderInvoiceItems();
        validateForm();
    }

    // ==================== MANAGE INVOICE ITEMS ====================
    function increaseQuantity(index) {
        invoiceItems[index].quantity++;
        renderInvoiceItems();
        validateForm();
    }

    function decreaseQuantity(index) {
        if (invoiceItems[index].quantity > 1) {
            invoiceItems[index].quantity--;
            renderInvoiceItems();
            validateForm();
        }
    }

    function updateQuantity(index, value) {
        const qty = parseInt(value) || 1;
        invoiceItems[index].quantity = qty < 1 ? 1 : qty;
        renderInvoiceItems();
        validateForm();
    }

    function updateItemNote(index, value) {
        invoiceItems[index].note = value;
    }

    function removeItem(index) {
        if (confirm('Remove this item?')) {
            invoiceItems.splice(index, 1);
            renderInvoiceItems();
            validateForm();
        }
    }

    // ==================== RENDER ITEMS ====================
    function renderInvoiceItems() {
        const tbody = document.getElementById('invoiceItems');

        if (!invoiceItems.length) {
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
            const total = item.price * item.quantity;

            html += `
                <tr>
                    <td>
                        <div class="fw-medium">${item.name}</div>
                        <textarea 
                            class="form-control form-control-sm note-textarea mt-1"
                            placeholder="Add note..."
                            oninput="updateItemNote(${index}, this.value)"
                            rows="1">${item.note || ''}</textarea>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
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
                    <td class="text-end">${formatCurrency(item.price)}</td>
                    <td class="text-end fw-medium">${formatCurrency(total)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
        });

        tbody.innerHTML = html;
        updateSummary();
    }

    // ==================== UPDATE SUMMARY ====================
    function updateSummary() {
        let subtotal = invoiceItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = parseFloat("{{ $invoice->tax }}") || 0;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('taxDisplay').textContent = formatCurrency(tax);
        document.getElementById('total').textContent = formatCurrency(total);
        document.getElementById('quickItemsCount').textContent = invoiceItems.length;
        document.getElementById('quickBillTo').textContent = document.getElementById('bill_to').value || '-';
        document.getElementById('quickEmail').textContent = document.getElementById('customer_email').value || '-';
        const dueDate = document.getElementById('due_date').value;
        if (dueDate) {
            const date = new Date(dueDate + 'T00:00:00');
            document.getElementById('quickDueDate').textContent = date.toLocaleDateString('en-US');
        }
    }

    // ==================== UPDATE INVOICE ====================
    async function updateInvoice() {
        if (!validateForm()) {
            showAlert('Please correct the errors before updating');
            return;
        }

        if (!confirm('Update this invoice?')) return;

        const payload = {
            company_location_id: document.getElementById('location').value,
            crew_id: document.getElementById('crew_id').value,
            invoice_date: document.getElementById('invoice_date').value,
            due_date: document.getElementById('due_date').value,
            customer_email: document.getElementById('customer_email').value,
            bill_to: document.getElementById('bill_to').value,
            address: document.getElementById('address').value,
            status: document.getElementById('status').value,
            notes: document.getElementById('notes').value,
            memo: document.getElementById('memo').value,
            items: invoiceItems.map(i => ({
                id: i.id,
                name: i.name,
                price: i.price,
                quantity: i.quantity,
                note: i.note ?? ''
            }))
        };

        const btn = document.querySelector('button[onclick="updateInvoice()"]');
        const originalText = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('superadmin.invoices.update', $invoice) }}", {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            });

            const res = await response.json();

            btn.innerHTML = originalText;
            btn.disabled = false;

            if (res.success) {
                showAlert('Invoice updated successfully', 'success');
                setTimeout(() => {
                    window.location.href = `/superadmin/invoices/${res.invoice_id}`;
                }, 1500);
            } else {
                showAlert(res.message || 'Error updating invoice');
            }
        } catch (error) {
            console.error(error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            showAlert('Server error');
        }
    }

    // ==================== INITIALIZATION ====================
    document.addEventListener('DOMContentLoaded', async () => {
        // Set up event listeners for real-time validation
        document.getElementById('company').addEventListener('change', function() {
            validateField('company', 'required', 'Please select a company');
        });
        document.getElementById('location').addEventListener('change', function() {
            validateField('location', 'required', 'Please select a location');
            loadItemsByLocation(this.value);
        });
        document.getElementById('bill_to').addEventListener('input', function() {
            validateField('bill_to', 'required|min:3', 'This field is required and must be at least 3 characters');
        });
        document.getElementById('customer_email').addEventListener('input', function() {
            validateField('customer_email', 'email', 'Invalid email format');
        });
        document.getElementById('due_date').addEventListener('input', validateDueDate);

        // Load items for current location
        if (currentLocationId) {
            await loadItemsByLocation(currentLocationId);
        }

        // Initial render and validation
        renderInvoiceItems();
        validateForm();
    });
</script>
@endsection