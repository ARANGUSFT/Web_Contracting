@extends('admin.layouts.superadmin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Create Invoice</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item active">New</li>
                </ol>
            </nav>
        </div>
        <button class="btn btn-primary" onclick="saveInvoice()" id="saveButton">
            <i class="fas fa-save me-1"></i>Save Invoice
        </button>
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
                                <option value="">Select company</option>
                                @foreach($companies as $company)
                                    <option 
                                        value="{{ $company->id }}"
                                        data-name="{{ $company->company_name }}"
                                        data-email="{{ $company->email }}"
                                        data-address="{{ $company->address ?? '' }}">
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="company_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <select id="location" class="form-select" disabled>
                                <option value="">First select a company</option>
                            </select>
                            <div id="location_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Crew</label>
                            <select id="crew_id" class="form-select">
                                <option value="">Select crew</option>
                                @foreach($crews as $crew)
                                    <option value="{{ $crew->id }}">
                                        {{ $crew->name }} ({{ $crew->has_trailer ? 'Trailer' : 'No trailer' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" id="invoice_date" class="form-control" value="{{ now()->toDateString() }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Due Date</label>
                            <input type="date" id="due_date" class="form-control" 
                                   value="{{ now()->addDays(15)->toDateString() }}"
                                   onblur="validateDueDate()">
                            <div id="due_date_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" id="invoice_number" class="form-control"
                                value="{{ $nextInvoiceNumber }}">
                            <small class="text-muted">You can edit this number</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Customer Email</label>
                            <input type="email" id="customer_email" class="form-control" 
                                   placeholder="customer@example.com"
                                   onblur="validateField('customer_email', 'email', 'Invalid email format')">
                            <div id="customer_email_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address"
                                value="{{ old('address', $invoice->address ?? '') }}"  placeholder="Address">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Bill To <span class="text-danger">*</span></label>
                            <input type="text" id="bill_to" class="form-control" 
                                   placeholder="Customer name or company"
                                   onblur="validateField('bill_to', 'required|min:3', 'This field is required and must be at least 3 characters')">
                            <div id="bill_to_error" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Notes (optional)</label>
                            <textarea id="notes" class="form-control" rows="2"
                                      placeholder="Notes that will appear on the invoice"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Internal Memo (optional)</label>
                            <textarea id="memo" class="form-control" rows="2"
                                      placeholder="Internal notes for reference"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items Section --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-list me-2"></i>Invoice Items</h6>
                    
                    {{-- Item selection --}}
                    <div class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label">Select Items to Add</label>
                                <select id="itemSelect" class="form-select">
                                    <option value="">Select an item...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quantity</label>
                                <div class="input-group">
                                    <input type="number" id="itemQuantity" class="form-control" 
                                           min="1" value="1" placeholder="Qty">
                                    <button class="btn btn-outline-secondary" type="button" onclick="addItemManually()">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Select an item to automatically add it to the invoice</small>

                        <div class="mt-3">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="toggleCustomItem()">
                                + Add Custom Item
                            </button>
                        </div>

                        {{-- Custom item --}}
                        <div id="customItemBox" class="card mt-3 d-none">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <input type="text" id="customName" class="form-control form-control-sm"
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
                    
                    {{-- Items table --}}
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center" width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No items added
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="items_error" class="invalid-feedback" style="display: none;">You must add at least one item</div>
                </div>
            </div>

            {{-- Attachments --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-paperclip me-2"></i>Attachments</h6>
                    
                    <div class="border rounded p-3 mb-3" style="border-style: dashed !important; background: #f8f9fa;">
                        <div class="text-center">
                            <p class="mb-2">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                            </p>
                            <p class="mb-1">Drag files here or</p>
                            <label class="btn btn-sm btn-outline-secondary mb-2" for="attachments">
                                Select Files
                            </label>
                            <input type="file" id="attachments" class="d-none" multiple
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                            <p class="text-muted small mb-0">
                                PDF, images, Word, Excel • Max 10MB per file
                            </p>
                        </div>
                    </div>
                    
                    <div id="attachmentsList">
                        <p class="text-muted small text-center mb-0">No attachments</p>
                    </div>
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
                            <span class="fw-semibold" id="subtotal">$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h6">Total:</span>
                            <span class="h5 fw-bold text-primary" id="total">$0.00</span>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary w-100 mb-2" onclick="saveInvoice()" id="saveButtonSide">
                        <i class="fas fa-save me-1"></i>Save Invoice
                    </button>
                    
                    <button class="btn btn-outline-secondary w-100" onclick="window.history.back()">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                </div>
            </div>
            
            {{-- Quick Information --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Information</h6>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Customer</small>
                        <span id="quickBillTo" class="fw-semibold">-</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Email</small>
                        <span id="quickEmail" class="fw-semibold">-</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Due Date</small>
                        <span id="quickDueDate" class="fw-semibold">{{ now()->addDays(15)->format('m/d/Y') }}</span>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted d-block">Items</small>
                        <span id="quickItemsCount" class="fw-semibold">0</span>
                    </div>
                    
                    <div class="mb-0">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge bg-light text-dark">Draft</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
    .card { border: 1px solid #e0e0e0; }
    .card-title { font-weight: 600; color: #333; }
    .form-label { font-weight: 500; color: #555; margin-bottom: 0.25rem; }
    .table th { font-weight: 600; font-size: 0.875rem; color: #555; border-bottom: 2px solid #e0e0e0; }
    .table td { vertical-align: middle; border-color: #f0f0f0; }
    .btn-outline-primary:hover { background-color: #0d6efd; color: white; }
    .badge { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
    hr { opacity: 0.3; }
    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { display: none; width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545; }
    .input-group-sm .form-control, .input-group-sm .btn { height: calc(1.5em + 0.5rem + 2px); }
    .note-textarea { font-size: 0.75rem; resize: none; overflow: hidden; min-height: 1.5rem; background: #f8f9fa; border: none; }
    .note-textarea:focus { background: #fff; border: 1px solid #ced4da; }
</style>

<script>
    // ==================== GLOBAL VARIABLES ====================
    let selectedFiles = [];
    let invoiceItems = [];
    let allItems = [];
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // ==================== INITIALIZATION ====================
    document.addEventListener('DOMContentLoaded', function() {
        // Update quick info on input
        ['bill_to', 'customer_email', 'due_date'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateQuickInfo);
        });
        
        setupCompanyLocation();
        setupAttachments();
        updateQuickInfo();
        validateForm(); // initial button state

        // Real-time validation for required fields
        document.getElementById('company').addEventListener('change', function() {
            validateField('company', 'required', 'Please select a company');
        });
        document.getElementById('location').addEventListener('change', function() {
            validateField('location', 'required', 'Please select a location');
        });
    });

    // ==================== COMPANY / LOCATION SETUP ====================
    function setupCompanyLocation() {
        const companySelect = document.getElementById('company');
        const locationSelect = document.getElementById('location');

        companySelect.addEventListener('change', function () {
            const companyId = this.value;
            const selected = this.options[this.selectedIndex];
            const name = selected.getAttribute('data-name') || '';
            const email = selected.getAttribute('data-email') || '';
            const address = selected.getAttribute('data-address') || '';

            document.getElementById('bill_to').value = name;
            document.getElementById('customer_email').value = email;
            document.getElementById('address').value = address;

            updateQuickInfo();
            validateField('company', 'required', 'Please select a company');

            if (!companyId) {
                locationSelect.innerHTML = '<option value="">First select a company</option>';
                locationSelect.disabled = true;
                resetItems();
                return;
            }

            locationSelect.innerHTML = '<option value="">Loading...</option>';
            fetch(`/superadmin/companies/${companyId}/locations/ajax`)
                .then(response => response.json())
                .then(data => {
                    locationSelect.innerHTML = '<option value="">Select location</option>';
                    data.forEach(location => {
                        const option = document.createElement('option');
                        option.value = location.id;
                        const label = location.city ? `${location.city}, ${location.state}` : location.state;
                        option.textContent = label;
                        locationSelect.appendChild(option);
                    });
                    locationSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    locationSelect.innerHTML = '<option value="">Error loading</option>';
                });
        });

        locationSelect.addEventListener('change', function () {
            const locationId = this.value;
            validateField('location', 'required', 'Please select a location');
            if (!locationId) {
                resetItems();
                return;
            }
            loadItems(locationId);
        });
    }

    function resetItems() {
        allItems = [];
        document.getElementById('itemSelect').innerHTML = '<option value="">Select a location first</option>';
    }

    function loadItems(locationId) {
        const itemSelect = document.getElementById('itemSelect');
        itemSelect.innerHTML = '<option value="">Loading items...</option>';
        fetch(`/superadmin/invoices/location/${locationId}/items`)
            .then(response => response.json())
            .then(items => {
                allItems = items;
                updateItemSelect();
            })
            .catch(error => {
                console.error('Error:', error);
                itemSelect.innerHTML = '<option value="">Error loading items</option>';
            });
    }

    function updateItemSelect() {
        const itemSelect = document.getElementById('itemSelect');
        itemSelect.innerHTML = '<option value="">Select an item...</option>';

        if (!allItems || allItems.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No items available';
            option.disabled = true;
            itemSelect.appendChild(option);
            return;
        }

        const grouped = {};
        allItems.forEach(item => {
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
                option.textContent = `${item.name} - $${parseFloat(item.price).toFixed(2)}`;
                option.dataset.price = item.price;
                option.dataset.name = item.name;
                optgroup.appendChild(option);
            });
            itemSelect.appendChild(optgroup);
        });
    }

    // ==================== CUSTOM ITEMS ====================
    function toggleCustomItem() {
        const box = document.getElementById('customItemBox');
        box.classList.toggle('d-none');
        if (!box.classList.contains('d-none')) {
            document.getElementById('customName').focus();
        }
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

        // Clear fields and hide
        document.getElementById('customName').value = '';
        document.getElementById('customPrice').value = '';
        document.getElementById('customQty').value = 1;
        document.getElementById('customItemBox').classList.add('d-none');

        renderInvoiceItems();
        updateQuickInfo();
        validateForm();
    }

    // ==================== INVOICE ITEMS HANDLING ====================
    function addItemToInvoice() {
        const itemSelect = document.getElementById('itemSelect');
        const quantityInput = document.getElementById('itemQuantity');
        
        const itemId = itemSelect.value;
        const quantity = parseInt(quantityInput.value) || 1;
        
        if (!itemId) return;
        
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const itemName = selectedOption.dataset.name;
        
        const existingIndex = invoiceItems.findIndex(item => item.id == itemId);
        if (existingIndex > -1) {
            invoiceItems[existingIndex].quantity += quantity;
        } else {
            invoiceItems.push({
                id: itemId,
                name: itemName,
                price: price,
                quantity: quantity,
                note: ''
            });
        }
        
        itemSelect.value = '';
        quantityInput.value = 1;
        renderInvoiceItems();
        updateQuickInfo();
        validateForm();
    }

    function addItemManually() {
        const itemSelect = document.getElementById('itemSelect');
        if (!itemSelect.value) {
            showAlert('Please select an item first');
            return;
        }
        addItemToInvoice();
    }

    function updateItemQuantity(index, change) {
        const newQty = invoiceItems[index].quantity + change;
        if (newQty < 1) {
            showAlert('Quantity cannot be less than 1');
            return;
        }
        invoiceItems[index].quantity = newQty;
        renderInvoiceItems();
        updateQuickInfo();
    }

    function updateQuantityInput(index, value) {
        const newQty = parseInt(value) || 1;
        invoiceItems[index].quantity = newQty < 1 ? 1 : newQty;
        renderInvoiceItems();
        updateQuickInfo();
    }

    function updateItemNote(index, value) {
        invoiceItems[index].note = value;
    }

    function removeInvoiceItem(index) {
        if (confirm('Remove this item from the invoice?')) {
            invoiceItems.splice(index, 1);
            renderInvoiceItems();
            updateQuickInfo();
            validateForm();
        }
    }

    // New function to update price when input changes
    function updateItemPrice(index, newPrice) {
        let price = parseFloat(newPrice);
        if (isNaN(price) || price < 0) price = 0;
        invoiceItems[index].price = price;
        renderInvoiceItems();   // re-renders the table to reflect the new price and totals
        updateQuickInfo();
        validateForm();
    }

    function renderInvoiceItems() {
        const tbody = document.getElementById('invoiceItems');
        let subtotal = 0;

        if (invoiceItems.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">No items added</td></tr>`;
            updateSummary(0);
            return;
        }

        let html = '';
        invoiceItems.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            html += `
                <tr>
                    <td>
                        <div class="fw-semibold">${item.name}</div>
                        <small class="text-muted">$${item.price.toFixed(2)} each</small>
                        <textarea 
                            class="form-control form-control-sm note-textarea mt-1"
                            placeholder="Add note..."
                            oninput="autoResize(this); updateItemNote(${index}, this.value)"
                            rows="1">${item.note || ''}</textarea>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 110px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateItemQuantity(${index}, -1)">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" onchange="updateQuantityInput(${index}, this.value)">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateItemQuantity(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end" style="width: 120px;">
                        <input type="number"
                               class="form-control form-control-sm text-end"
                               value="${item.price.toFixed(2)}"
                               step="0.01"
                               min="0"
                               onchange="updateItemPrice(${index}, this.value)"
                               style="width: 100px; display: inline-block;">
                    </td>
                    <td class="text-end fw-semibold">$${itemTotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger" onclick="removeInvoiceItem(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        updateSummary(subtotal);
    }

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = (el.scrollHeight) + 'px';
    }

    function updateSummary(subtotal) {
        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('total').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('quickItemsCount').textContent = invoiceItems.length;
    }

    // ==================== VALIDATIONS ====================
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

        // Enable/disable save buttons
        const formValid = companyOk && locationOk && billToOk && emailOk && dueDateOk && itemsOk;
        const saveButtons = document.querySelectorAll('#saveButton, #saveButtonSide');
        saveButtons.forEach(btn => {
            btn.disabled = !formValid;
        });

        return formValid;
    }

    // ==================== ATTACHMENTS ====================
    function setupAttachments() {
        const input = document.getElementById('attachments');
        const list = document.getElementById('attachmentsList');
        const dropZone = input.closest('.card-body').querySelector('.border');

        input.addEventListener('change', () => {
            const files = Array.from(input.files);
            handleFiles(files);
            setTimeout(() => { input.value = ''; }, 100);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#0d6efd';
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#dee2e6';
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#dee2e6';
            handleFiles(e.dataTransfer.files);
        });

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File ${file.name} exceeds 10MB limit`);
                    return;
                }
                selectedFiles.push(file);
            });
            renderAttachments();
        }

        window.removeAttachment = function(index) {
            selectedFiles.splice(index, 1);
            renderAttachments();
        };

        function renderAttachments() {
            if (selectedFiles.length === 0) {
                list.innerHTML = '<p class="text-muted small text-center mb-0">No attachments</p>';
                return;
            }

            let html = '';
            selectedFiles.forEach((file, index) => {
                const size = (file.size / 1024 / 1024).toFixed(2);
                const icon = file.type.includes('image') ? 'fa-image' :
                            file.type.includes('pdf') ? 'fa-file-pdf' :
                            file.type.includes('word') ? 'fa-file-word' :
                            file.type.includes('excel') ? 'fa-file-excel' : 'fa-file';
                html += `
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div class="d-flex align-items-center">
                            <i class="fas ${icon} text-muted me-2"></i>
                            <div>
                                <div class="small">${file.name}</div>
                                <div class="text-muted extra-small">${size} MB</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-link text-danger" onclick="removeAttachment(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
            list.innerHTML = html;
        }
    }

    // ==================== QUICK INFO UPDATE ====================
    function updateQuickInfo() {
        document.getElementById('quickBillTo').textContent = document.getElementById('bill_to').value || '-';
        document.getElementById('quickEmail').textContent = document.getElementById('customer_email').value || '-';
        const dueDate = document.getElementById('due_date').value;
        if (dueDate) {
            const date = new Date(dueDate + 'T00:00:00');
            document.getElementById('quickDueDate').textContent = date.toLocaleDateString('en-US');
        }
    }

    // ==================== SAVE INVOICE ====================
    function saveInvoice() {
        if (!validateForm()) {
            showAlert('Please correct the errors before saving');
            return;
        }

        if (!confirm('Create this invoice?')) return;

        const formData = new FormData();
        formData.append('company_location_id', document.getElementById('location').value);
        formData.append('crew_id', document.getElementById('crew_id').value);
        formData.append('invoice_date', document.getElementById('invoice_date').value);
        formData.append('due_date', document.getElementById('due_date').value);
        formData.append('customer_email', document.getElementById('customer_email').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('bill_to', document.getElementById('bill_to').value);
        formData.append('memo', document.getElementById('memo').value);
        formData.append('notes', document.getElementById('notes').value);
        formData.append('invoice_number', document.getElementById('invoice_number').value);

        invoiceItems.forEach((item, index) => {
            formData.append(`items[${index}][id]`, item.id ?? '');
            formData.append(`items[${index}][name]`, item.name.trim());
            formData.append(`items[${index}][price]`, Number(item.price));
            formData.append(`items[${index}][quantity]`, Number(item.quantity));
            formData.append(`items[${index}][note]`, item.note?.trim() ?? '');
        });

        selectedFiles.forEach(file => {
            formData.append('attachments[]', file);
        });

        showLoading(true);

        fetch("{{ route('superadmin.invoices.store') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            if (data.success) {
                window.location.href = `/superadmin/invoices/${data.invoice_id}`;
            } else {
                showAlert(data.message || 'Error creating invoice');
            }
        })
        .catch(error => {
            showLoading(false);
            console.error('Error:', error);
            showAlert('Server error');
        });
    }

    function showAlert(message) {
        const alert = document.getElementById('topAlert');
        alert.textContent = message;
        alert.classList.remove('d-none');
        setTimeout(() => alert.classList.add('d-none'), 5000);
    }

    function showLoading(show) {
        const buttons = document.querySelectorAll('#saveButton, #saveButtonSide');
        buttons.forEach(btn => {
            if (show) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
                btn.disabled = true;
            } else {
                btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Invoice';
                btn.disabled = false;
            }
        });
    }
</script>
@endsection