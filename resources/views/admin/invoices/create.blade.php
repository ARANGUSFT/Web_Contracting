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
        <button class="btn btn-primary" onclick="saveInvoice()">
            <i class="fas fa-save me-1"></i>Save Invoice
        </button>
    </div>

    {{-- Alert --}}
    <div id="topAlert" class="alert alert-danger d-none mb-3"></div>

    <div class="row g-3">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">
            {{-- Invoice Information --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Invoice Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company *</label>
                            <select id="company" class="form-select">
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

                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <select id="location" class="form-select" disabled>
                                <option value="">First select a company</option>
                            </select>
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
                                   value="{{ now()->addDays(15)->toDateString() }}">
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
                                   placeholder="customer@example.com">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="address"
                                value="{{ old('address', $invoice->address ?? '') }}"  placeholder="Address">
                        </div>


                        <div class="col-md-12">
                            <label class="form-label">Bill To *</label>
                            <input type="text" id="bill_to" class="form-control" 
                                   placeholder="Customer name or company">
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
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Invoice Items</h6>
                    
                    {{-- Item selection --}}
                    <div class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label">Select Items to Add</label>
                                <select id="itemSelect" class="form-select" onchange="addItemToInvoice()">
                                    <option value="">Select an item...</option>
                                    <option value="" disabled>Select a location first</option>
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
                    </div>
                    
                    {{-- Items table --}}
                    <div class="table-responsive">
                        <table class="table table-sm">
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
                </div>
            </div>


            {{-- Attachments --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Attachments</h6>
                    
                    <div class="border rounded p-3 mb-3" style="border-style: dashed !important">
                        <div class="text-center">
                            <p class="mb-2">
                                <i class="fas fa-cloud-upload-alt fa-lg text-muted"></i>
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
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Summary</h6>
                    
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
                    
                    <button class="btn btn-primary w-100 mb-2" onclick="saveInvoice()">
                        <i class="fas fa-save me-1"></i>Save Invoice
                    </button>
                    
                    <button class="btn btn-outline-secondary w-100" onclick="window.history.back()">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                </div>
            </div>
            
            {{-- Quick Information --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Information</h6>
                    
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



<script>
    // Global variables
    let selectedFiles = [];
    let invoiceItems = [];
    let allItems = []; // Stores all available items
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Set up field events
        ['bill_to', 'customer_email', 'due_date'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateQuickInfo);
        });
        
        // Set up company and location
        setupCompanyLocation();
        
        // Set up attachments
        setupAttachments();
        
        // Update initial quick info
        updateQuickInfo();
    });

    // Set up company and location
    function setupCompanyLocation() {
        const companySelect = document.getElementById('company');
        const locationSelect = document.getElementById('location');

        companySelect.addEventListener('change', function () {
            const companyId = this.value;

            // 🟢 Autocompletar campos "Bill To", "Email", "Address"
            const selected = this.options[this.selectedIndex];
            const name = selected.getAttribute('data-name') || '';
            const email = selected.getAttribute('data-email') || '';
            const address = selected.getAttribute('data-address') || '';

            document.getElementById('bill_to').value = name;
            document.getElementById('customer_email').value = email;
            document.getElementById('address').value = address;

            updateQuickInfo(); // Actualiza el panel derecho

            // 🔄 Cargar ubicaciones
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

                    const label = location.city
                        ? `${location.city}, ${location.state}`
                        : location.state;

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

            if (!locationId) {
                resetItems();
                return;
            }

            // Load items for this location
            loadItems(locationId);
        });
    }


    // Load items from selected location
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

        // 🔥 Agrupar por categoría
        const grouped = {};

        allItems.forEach(item => {
            const category = item.category || 'Uncategorized';

            if (!grouped[category]) {
                grouped[category] = [];
            }

            grouped[category].push(item);
        });

        // 🔥 Crear optgroups
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


    // Add item to invoice when selected
    function addItemToInvoice() {
        const itemSelect = document.getElementById('itemSelect');
        const quantityInput = document.getElementById('itemQuantity');
        
        const itemId = itemSelect.value;
        const quantity = parseInt(quantityInput.value) || 1;
        
        if (!itemId) {
            return;
        }
        
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const itemName = selectedOption.dataset.name;
        
        // Check if item already exists
        const existingItemIndex = invoiceItems.findIndex(item => item.id == itemId);
        
        if (existingItemIndex > -1) {
            // Update quantity
            invoiceItems[existingItemIndex].quantity += quantity;
        } else {
            // Add new item
            invoiceItems.push({
                id: itemId,
                name: itemName,
                price: price,
                quantity: quantity
            });
        }
        
        // Reset selection
        itemSelect.value = '';
        quantityInput.value = 1;
        
        // Render items
        renderInvoiceItems();
        
        // Update quick info
        updateQuickInfo();
    }

    // Manual add item (button click)
    function addItemManually() {
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
        
        addItemToInvoice();
    }

    // Render invoice items
    function renderInvoiceItems() {
        const tbody = document.getElementById('invoiceItems');
        let subtotal = 0;
        
        if (invoiceItems.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No items added
                    </td>
                </tr>
            `;
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
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateItemQuantity(${index}, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="text" 
                                class="form-control text-center" 
                                value="${item.quantity}"
                                onchange="updateQuantityInput(${index}, this.value)">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateItemQuantity(${index}, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-end">$${item.price.toFixed(2)}</td>
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

    // Update summary
    function updateSummary(subtotal) {
        const total = subtotal;
        
        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        
        // Update item count
        document.getElementById('quickItemsCount').textContent = invoiceItems.length;
    }

    // Update item quantity with buttons
    function updateItemQuantity(index, change) {
        const newQuantity = invoiceItems[index].quantity + change;
        
        if (newQuantity < 1) {
            showAlert('Quantity cannot be less than 1');
            return;
        }
        
        invoiceItems[index].quantity = newQuantity;
        renderInvoiceItems();
    }

    // Update item quantity with input
    function updateQuantityInput(index, value) {
        const newQuantity = parseInt(value) || 1;
        
        if (newQuantity < 1) {
            showAlert('Quantity cannot be less than 1');
            invoiceItems[index].quantity = 1;
        } else {
            invoiceItems[index].quantity = newQuantity;
        }
        
        renderInvoiceItems();
    }

    // Remove item from invoice
    function removeInvoiceItem(index) {
        if (confirm('Remove this item from the invoice?')) {
            invoiceItems.splice(index, 1);
            renderInvoiceItems();
            updateQuickInfo();
        }
    }

    // Reset items when no location selected
    function resetItems() {
        allItems = [];
        const itemSelect = document.getElementById('itemSelect');
        itemSelect.innerHTML = '<option value="">Select a location first</option>';
    }

    // Set up attachments
    function setupAttachments() {
        const input = document.getElementById('attachments');
        const list = document.getElementById('attachmentsList');
        const dropZone = input.closest('.card-body').querySelector('.border');
        
        // ─── SOLO UN LISTENER PARA 'change' ─────────────────────────────
        input.addEventListener('change', () => {
            const files = Array.from(input.files);
            handleFiles(files);
            // Pequeño retraso para evitar reapertura del diálogo
            setTimeout(() => {
                input.value = '';
            }, 100);
        });

        // Drag and drop
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
                            file.type.includes('excel') || file.type.includes('spreadsheet') ? 'fa-file-excel' :
                            'fa-file';
                
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

    // Update quick info
    function updateQuickInfo() {
        document.getElementById('quickBillTo').textContent = 
            document.getElementById('bill_to').value || '-';
        document.getElementById('quickEmail').textContent = 
            document.getElementById('customer_email').value || '-';
        
        const dueDate = document.getElementById('due_date').value;
        if (dueDate) {
            const date = new Date(dueDate);
            document.getElementById('quickDueDate').textContent = 
                date.toLocaleDateString('en-US');
        }
    }

    // Save invoice
    function saveInvoice() {
        // Validations
        const companyId = document.getElementById('company').value;
        const locationId = document.getElementById('location').value;
        const billTo = document.getElementById('bill_to').value;
        
        if (!companyId || !locationId) {
            showAlert('Please select a company and location');
            return;
        }
        
        if (!billTo.trim()) {
            showAlert('Please enter bill to information');
            return;
        }
        
        if (invoiceItems.length === 0) {
            showAlert('Please add at least one item to the invoice');
            return;
        }
        
        // Confirm
        if (!confirm('Create this invoice?')) {
            return;
        }
        
        // Prepare data
        const formData = new FormData();
        
        // Basic data
        formData.append('company_location_id', locationId);
        formData.append('crew_id', document.getElementById('crew_id').value);
        formData.append('invoice_date', document.getElementById('invoice_date').value);
        formData.append('due_date', document.getElementById('due_date').value);
        formData.append('customer_email', document.getElementById('customer_email').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('bill_to', billTo);
        formData.append('memo', document.getElementById('memo').value);
        formData.append('notes', document.getElementById('notes').value);
        formData.append('invoice_number', document.getElementById('invoice_number').value);
        
        // Items
        invoiceItems.forEach((item, index) => {
            formData.append(`items[${index}][id]`, item.id);
            formData.append(`items[${index}][name]`, item.name);
            formData.append(`items[${index}][price]`, item.price);
            formData.append(`items[${index}][quantity]`, item.quantity);
        });
        
        // Attachments
        selectedFiles.forEach(file => {
            formData.append('attachments[]', file);
        });
        
        // Send
        showLoading(true);
        
        fetch("{{ route('superadmin.invoices.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf
            },
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

    // Show alert
    function showAlert(message) {
        const alert = document.getElementById('topAlert');
        alert.textContent = message;
        alert.classList.remove('d-none');
        
        setTimeout(() => {
            alert.classList.add('d-none');
        }, 5000);
    }

    // Show/hide loading
    function showLoading(show) {
        const buttons = document.querySelectorAll('.btn-primary');
        buttons.forEach(button => {
            if (show) {
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
                button.disabled = true;
            } else {
                button.innerHTML = '<i class="fas fa-save me-1"></i>Save Invoice';
                button.disabled = false;
            }
        });
    }
</script>


<style>
    .extra-small {
        font-size: 0.75rem;
    }

    .card {
        border: 1px solid #e0e0e0;
    }

    .card-title {
        font-weight: 600;
        color: #333;
    }

    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 0.25rem;
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
    }

    .input-group-text {
        background-color: #f8f9fa;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    hr {
        opacity: 0.3;
    }
</style>

@endsection