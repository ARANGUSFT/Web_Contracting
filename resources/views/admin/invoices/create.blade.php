@extends('admin.layouts.superadmin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-3">

    <h3 class="mb-3">Create Invoice</h3>

    <div id="topAlert" class="alert alert-danger d-none"></div>

    <div class="row">

        {{-- LEFT SIDE --}}
        <div class="col-md-8">

            {{-- BASIC INFO --}}
            <div class="card mb-3">
                <div class="card-header fw-bold">Invoice Information</div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label>Company</label>
                        <select id="company" class="form-control">
                            <option value="">Select company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Location</label>
                        <select id="location" class="form-control">
                            <option value="">Select location</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Invoice Date</label>
                        <input type="date" id="invoice_date" class="form-control" value="{{ now()->toDateString() }}">
                    </div>

                    <div class="col-md-6">
                        <label>Due Date</label>
                        <input type="date" id="due_date" class="form-control" value="{{ now()->addDays(15)->toDateString() }}">
                    </div>

                    <div class="col-md-6">
                        <label>Customer Email</label>
                        <input type="email" id="customer_email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Bill To</label>
                        <input type="text" id="bill_to" class="form-control">
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">
                            Invoice Number
                            <small class="text-muted">(editable)</small>
                        </label>

                        <input type="text"
                            id="invoice_number"
                            class="form-control"
                            value="INV-{{ now()->format('YmdHis') }}"
                            placeholder="INV-20250101-001">
                    </div>


                    <div class="col-md-12">
                        <label>Memo</label>
                        <textarea id="memo"
                                class="form-control"
                                rows="2"
                                placeholder="Internal memo (optional)"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label>Notes</label>
                        <textarea id="notes"
                                class="form-control"
                                rows="3"
                                placeholder="Notes that will appear on the invoice (optional)"></textarea>
                    </div>


             {{-- ATTACHMENTS --}}
<div class="card mb-3">
    <div class="card-header fw-bold d-flex align-items-center gap-2">
        <i class="fas fa-paperclip"></i> Attachments
    </div>

    <div class="card-body">

        {{-- Upload area --}}
        <div class="border rounded p-3 text-center mb-3"
             style="border-style: dashed !important; cursor:pointer"
             onclick="document.getElementById('attachments').click()">

            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
            <p class="mb-1 fw-semibold">Click to upload or drag files here</p>
            <small class="text-muted">
                PDF, Images, Word, Excel • Max 10MB each
            </small>
        </div>

        <input type="file"
               id="attachments"
               name="attachments[]"
               class="d-none"
               multiple
               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">

        {{-- File list --}}
        <ul class="list-group" id="attachmentsList"></ul>

    </div>
</div>




                </div>
            </div>

            {{-- ITEMS --}}
            <div class="card mb-3">
                <div class="card-header">Items</div>
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
                            <tr><td colspan="4" class="text-center">Select location</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SELECTED ITEMS --}}
            <div class="card mb-3">
                <div class="card-header">Invoice Items</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems">
                            <tr><td colspan="5" class="text-center">No items</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- SUMMARY --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Summary</div>
                <div class="card-body">
                    <p>Subtotal: <strong id="subtotal">$0.00</strong></p>
                    <p>Tax: <strong id="taxDisplay">$0.00</strong></p>
                    <p>Total: <strong id="total">$0.00</strong></p>

                    <button class="btn btn-success w-100" onclick="saveInvoice()">Save Invoice</button>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
let selectedFiles = [];

const input = document.getElementById('attachments');
const list  = document.getElementById('attachmentsList');

input.addEventListener('change', () => {
    for (let file of input.files) {
        selectedFiles.push(file);
    }
    renderFiles();
    input.value = ''; // reset input
});

function renderFiles() {
    list.innerHTML = '';

    if (!selectedFiles.length) return;

    selectedFiles.forEach((file, index) => {
        list.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-file me-2 text-secondary"></i>
                    <strong>${file.name}</strong>
                    <br>
                    <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                </div>
                <button class="btn btn-sm btn-outline-danger"
                        onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            </li>
        `;
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderFiles();
}
</script>

{{-- ======================= SCRIPT ======================= --}}
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let invoiceItems = [];

function money(n){ return '$' + Number(n).toFixed(2); }

document.getElementById('company').addEventListener('change', e => {
    fetch(`/superadmin/companies/${e.target.value}/locations`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('location');
            sel.innerHTML = '<option value="">Select location</option>';
            data.forEach(l => {
                sel.innerHTML += `<option value="${l.id}">${l.state} - ${l.city}</option>`;
            });
        });
});

document.getElementById('location').addEventListener('change', e => {
    fetch(`/superadmin/locations/${e.target.value}/items/json`)
        .then(r => r.json())
        .then(items => {
            const tbody = document.querySelector('#itemsTable tbody');
            tbody.innerHTML = '';
            items.forEach(i => {
                tbody.innerHTML += `
                    <tr>
                        <td>${i.name}</td>
                        <td>$${i.price}</td>
                        <td><input type="number" min="1" value="1" id="qty-${i.id}" class="form-control"></td>
                        <td><button class="btn btn-sm btn-primary" onclick="addItem(${i.id}, '${i.name}', ${i.price})">Add</button></td>
                    </tr>`;
            });
        });
});

function addItem(id, name, price) {
    const qty = parseInt(document.getElementById(`qty-${id}`).value);
    const existing = invoiceItems.find(i => i.id === id);

    if (existing) existing.quantity += qty;
    else invoiceItems.push({ id, name, price, quantity: qty });

    renderItems();
}

function renderItems() {
    const tbody = document.getElementById('invoiceItems');
    tbody.innerHTML = '';
    let subtotal = 0;

    invoiceItems.forEach((i, idx) => {
        const total = i.price * i.quantity;
        subtotal += total;

        tbody.innerHTML += `
            <tr>
                <td>${i.name}</td>
                <td>${i.quantity}</td>
                <td>$${i.price}</td>
                <td>$${total.toFixed(2)}</td>
                <td><button class="btn btn-sm btn-danger" onclick="removeItem(${idx})">X</button></td>
            </tr>
        `;
    });

    document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('taxDisplay').innerText = `$0.00`;
    document.getElementById('total').innerText = `$${subtotal.toFixed(2)}`;
}

function removeItem(index){
    invoiceItems.splice(index,1);
    renderItems();
}

function saveInvoice() {

    if (!invoiceItems.length) {
        alert('Please add at least one item');
        return;
    }

    const formData = new FormData();

    // BASIC DATA
    formData.append('company_location_id', document.getElementById('location').value);
    formData.append('invoice_date', document.getElementById('invoice_date').value);
    formData.append('due_date', document.getElementById('due_date').value);
    formData.append('customer_email', document.getElementById('customer_email').value);
    formData.append('bill_to', document.getElementById('bill_to').value);
    formData.append('memo', document.getElementById('memo').value);
    formData.append('notes', document.getElementById('notes').value);
    formData.append(
        'invoice_number',
        document.getElementById('invoice_number').value
    );

    // ITEMS
    invoiceItems.forEach((item, index) => {
        formData.append(`items[${index}][id]`, item.id);
        formData.append(`items[${index}][name]`, item.name);
        formData.append(`items[${index}][price]`, item.price);
        formData.append(`items[${index}][quantity]`, item.quantity);
    });

    // ATTACHMENTS
 selectedFiles.forEach(file => {
    formData.append('attachments[]', file);
});


    fetch("{{ route('superadmin.invoices.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrf
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert('Invoice created successfully');
            window.location.href = `/superadmin/invoices/${res.invoice_id}`;
        } else {
            alert(res.message || 'Error saving invoice');
        }
    })
    .catch(() => {
        alert('Server error while saving invoice');
    });
}
</script>
@endsection
