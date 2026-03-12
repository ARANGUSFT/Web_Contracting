<!-- ================= EXPENSES TAB ================= -->
<div class="tab-pane fade" id="expenses">

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-circle bg-danger-subtle">
                        <i class="bi bi-cash-coin text-danger"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold text-dark">Expenses</h4>
                        <p class="text-muted small mb-0">Track and manage all project expenses</p>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="addExpenseRow">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Expense
                </button>
            </div>

            <!-- Add Expenses Form -->
            <form id="expensesForm" action="{{ route('lead-expenses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 py-3 rounded-top-4">
                        <h6 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-wallet2 me-2 text-primary"></i>
                            Add New Expenses
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 expense-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3">Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Notes</th>
                                        <th class="text-center" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="expenseTableBody">
                                    <tr>
                                        <td class="px-3">
                                            <input type="date"
                                                   name="expenses[0][expense_date]"
                                                   class="form-control">
                                        </td>

                                        <td>
                                            <select name="expenses[0][type]" class="form-select expense-type">
                                                <option value="">Select</option>
                                                <option value="material">Material</option>
                                                <option value="labor">Labor</option>
                                                <option value="permit">Permit</option>
                                                <option value="supplement">Supplement</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text amount-prefix">$</span>

                                                <input type="text"
                                                       name="expenses[0][amount]"
                                                       class="form-control amount-field"
                                                       placeholder="0.00"
                                                       inputmode="decimal"
                                                       disabled>

                                                <span class="input-group-text commission-label d-none">%</span>
                                            </div>
                                        </td>

                                        <td>
                                            <textarea name="expenses[0][notes]"
                                                      rows="1"
                                                      class="form-control form-control-sm"
                                                      placeholder="Add notes..."></textarea>
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm rounded-circle remove-expense-row"
                                                    title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small">
                        Add one or more expenses, then click <strong>Save Expenses</strong>.
                    </div>

                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-save me-2"></i>
                        Save Expenses
                    </button>
                </div>
            </form>

            <hr class="my-5">

            <!-- Registered Expenses -->
            <div class="mb-3">
                <h5 class="mb-1 fw-semibold text-dark">
                    <i class="bi bi-card-checklist me-2 text-primary"></i>
                    Registered Expenses
                </h5>
                <p class="text-muted small mb-0">Saved expenses for this lead</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Notes</th>
                                    <th class="text-end px-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="registeredExpensesTable">
                                @forelse($lead->expenses as $expense)
                                    <tr>
                                        <td class="px-3">
                                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary text-capitalize">
                                                {{ str_replace('_', ' ', $expense->type) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($expense->type === 'commission')
                                                {{ number_format($expense->amount, 2) }}%
                                            @else
                                                ${{ number_format($expense->amount, 2) }}
                                            @endif
                                        </td>

                                        <td class="text-muted">
                                            {{ $expense->notes ?: '—' }}
                                        </td>

                                        <td class="text-end px-3">
                                            <form action="{{ route('lead-expenses.destroy', $expense->id) }}"
                                                  method="POST"
                                                  class="delete-expense-form d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded-circle"
                                                        title="Delete expense">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyExpensesRow">
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-wallet2 d-block fs-3 mb-2"></i>
                                            No expenses registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


<script>
// =============================================
// EXPENSES
// =============================================

document.addEventListener('DOMContentLoaded', function () {
    initializeExpenses();
});

function initializeExpenses() {
    const expenseTableBody = document.getElementById('expenseTableBody');
    const addExpenseRowBtn = document.getElementById('addExpenseRow');
    const expensesForm = document.getElementById('expensesForm');

    if (!expenseTableBody) return;

    expenseTableBody.querySelectorAll('tr').forEach(row => {
        bindExpenseRow(row);
    });

    if (addExpenseRowBtn) {
        addExpenseRowBtn.removeEventListener('click', addExpenseRow);
        addExpenseRowBtn.addEventListener('click', addExpenseRow);
    }

    if (!expenseTableBody.dataset.bound) {
        expenseTableBody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-expense-row');
            if (!removeBtn) return;

            const row = removeBtn.closest('tr');
            if (!row) return;

            const totalRows = expenseTableBody.querySelectorAll('tr').length;

            if (totalRows === 1) {
                clearExpenseRow(row);
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete expense row?',
                    text: 'This unsaved row will be removed.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        reindexExpenseRows();
                    }
                });
            } else {
                if (confirm('Delete this expense row?')) {
                    row.remove();
                    reindexExpenseRows();
                }
            }
        });

        expenseTableBody.addEventListener('change', function (e) {
            if (e.target.classList.contains('expense-type')) {
                toggleExpenseAmountField(e.target);
            }
        });

        expenseTableBody.dataset.bound = 'true';
    }

    document.querySelectorAll('.delete-expense-form').forEach(function (form) {
        if (form.dataset.bound === 'true') return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete expense?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams(new FormData(form))
                        })
                        .then(response => {
                            if (response.ok) {
                                const row = form.closest('tr');
                                if (row) row.remove();

                                ensureRegisteredExpensesEmptyRow();

                                if (typeof updateExpenseSummary === 'function') {
                                    updateExpenseSummary();
                                }

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted',
                                        text: 'Expense removed successfully.',
                                        timer: 1200,
                                        showConfirmButton: false
                                    });
                                }
                            } else {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire('Error', 'Could not delete the expense.', 'error');
                                } else {
                                    alert('Could not delete the expense.');
                                }
                            }
                        })
                        .catch(() => {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Error', 'Network error.', 'error');
                            } else {
                                alert('Network error.');
                            }
                        });
                    }
                });
            } else {
                if (confirm('Delete this expense?')) {
                    form.submit();
                }
            }
        });

        form.dataset.bound = 'true';
    });

    if (expensesForm && !expensesForm.dataset.bound) {
        expensesForm.addEventListener('submit', function () {
            prepareExpenseFormBeforeSubmit();
        });

        expensesForm.dataset.bound = 'true';
    }
}

function addExpenseRow() {
    const expenseTableBody = document.getElementById('expenseTableBody');
    if (!expenseTableBody) return;

    const rowIndex = expenseTableBody.querySelectorAll('tr').length;

    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="px-3">
            <input type="date"
                   name="expenses[${rowIndex}][expense_date]"
                   class="form-control">
        </td>

        <td>
            <select name="expenses[${rowIndex}][type]" class="form-select expense-type">
                <option value="">Select</option>
                <option value="material">Material</option>
                <option value="labor">Labor</option>
                <option value="permit">Permit</option>
                <option value="supplement">Supplement</option>
                <option value="other">Other</option>
            </select>
        </td>

        <td>
            <div class="input-group">
                <span class="input-group-text amount-prefix">$</span>
                <input type="text"
                       name="expenses[${rowIndex}][amount]"
                       class="form-control amount-field"
                       placeholder="0.00"
                       inputmode="decimal"
                       disabled>
                <span class="input-group-text commission-label d-none">%</span>
            </div>
        </td>

        <td>
            <textarea name="expenses[${rowIndex}][notes]"
                      rows="1"
                      class="form-control form-control-sm"
                      placeholder="Add notes..."></textarea>
        </td>

        <td class="text-center">
            <button type="button"
                    class="btn btn-outline-danger btn-sm rounded-circle remove-expense-row"
                    title="Remove">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;

    expenseTableBody.appendChild(newRow);
    bindExpenseRow(newRow);
}

function bindExpenseRow(row) {
    if (!row) return;

    const select = row.querySelector('.expense-type');
    const amountInput = row.querySelector('.amount-field');

    if (select) {
        toggleExpenseAmountField(select);
    }

    if (amountInput && !amountInput.dataset.boundMoney) {
        amountInput.addEventListener('focus', function () {
            if (!this.classList.contains('commission-mode')) {
                this.value = unformatMoneyValue(this.value);
            }
        });

        amountInput.addEventListener('blur', function () {
            if (!this.classList.contains('commission-mode')) {
                this.value = formatMoneyValue(this.value);
            }
        });

        amountInput.dataset.boundMoney = 'true';
    }
}

function toggleExpenseAmountField(select) {
    const row = select.closest('tr');
    if (!row) return;

    const amountInput = row.querySelector('.amount-field');
    const amountPrefix = row.querySelector('.amount-prefix');
    const commissionLabel = row.querySelector('.commission-label');

    if (!amountInput || !amountPrefix || !commissionLabel) return;

    if (select.value === '') {
        amountInput.value = '';
        amountInput.setAttribute('disabled', true);
        amountPrefix.classList.remove('d-none');
        commissionLabel.classList.add('d-none');
        amountInput.classList.remove('commission-mode');
        return;
    }

    amountInput.removeAttribute('disabled');

    if (select.value === 'commission') {
        amountPrefix.classList.add('d-none');
        commissionLabel.classList.remove('d-none');
        amountInput.placeholder = '0.00';
        amountInput.classList.add('commission-mode');
        amountInput.value = unformatMoneyValue(amountInput.value);
    } else {
        amountPrefix.classList.remove('d-none');
        commissionLabel.classList.add('d-none');
        amountInput.placeholder = '0.00';
        amountInput.classList.remove('commission-mode');
        amountInput.value = formatMoneyValue(amountInput.value);
    }
}

function clearExpenseRow(row) {
    if (!row) return;

    row.querySelectorAll('input').forEach(input => {
        input.value = '';
    });

    row.querySelectorAll('select').forEach(select => {
        select.value = '';
    });

    row.querySelectorAll('textarea').forEach(textarea => {
        textarea.value = '';
    });

    const amountInput = row.querySelector('.amount-field');
    const amountPrefix = row.querySelector('.amount-prefix');
    const commissionLabel = row.querySelector('.commission-label');

    if (amountInput) {
        amountInput.classList.remove('commission-mode');
        amountInput.setAttribute('disabled', true);
    }

    if (amountPrefix) amountPrefix.classList.remove('d-none');
    if (commissionLabel) commissionLabel.classList.add('d-none');
}

function reindexExpenseRows() {
    const expenseTableBody = document.getElementById('expenseTableBody');
    if (!expenseTableBody) return;

    const rows = expenseTableBody.querySelectorAll('tr');

    rows.forEach((row, index) => {
        row.querySelectorAll('input, select, textarea').forEach(field => {
            const name = field.getAttribute('name');
            if (!name) return;

            const newName = name.replace(/expenses\[\d+\]/, `expenses[${index}]`);
            field.setAttribute('name', newName);
        });
    });
}

function ensureRegisteredExpensesEmptyRow() {
    const table = document.getElementById('registeredExpensesTable');
    if (!table) return;

    const realRows = [...table.querySelectorAll('tr')].filter(row => row.id !== 'emptyExpensesRow');

    if (realRows.length === 0 && !document.getElementById('emptyExpensesRow')) {
        const emptyRow = document.createElement('tr');
        emptyRow.id = 'emptyExpensesRow';
        emptyRow.innerHTML = `
            <td colspan="5" class="text-center text-muted py-4">
                <i class="bi bi-wallet2 d-block fs-3 mb-2"></i>
                No expenses registered yet.
            </td>
        `;
        table.appendChild(emptyRow);
    }
}

function formatMoneyValue(value) {
    if (value === null || value === undefined) return '';

    let clean = value.toString().replace(/[^0-9.]/g, '');
    const parts = clean.split('.');

    if (parts.length > 2) {
        clean = parts[0] + '.' + parts.slice(1).join('');
    }

    const number = parseFloat(clean);
    if (isNaN(number)) return '';

    return number.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function unformatMoneyValue(value) {
    if (value === null || value === undefined) return '';
    return value.toString().replace(/,/g, '').trim();
}

function prepareExpenseFormBeforeSubmit() {
    document.querySelectorAll('#expenseTableBody .amount-field').forEach(input => {
        input.value = unformatMoneyValue(input.value);
    });
}
</script>