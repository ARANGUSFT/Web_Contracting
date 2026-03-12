<!-- ================= CONTRIBUTION TAB ================= -->
<div class="tab-pane fade" id="contribution">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-circle bg-success-subtle">
                        <i class="bi bi-receipt text-success"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold text-dark">Financial Contributions</h4>
                        <p class="text-muted small mb-0">Track contract value, payments, and contribution details</p>
                    </div>
                </div>

                <button type="button" class="btn btn-primary rounded-pill px-4" id="addRow">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Contribution
                </button>
            </div>

            <form id="contributionsForm" method="POST" action="{{ route('leads.finanzas.update', $lead->id) }}">
                @csrf
                @method('PUT')

                <!-- Contract Summary -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="card border-0 bg-light rounded-4 h-100">
                            <div class="card-body">
                                <label for="contractValue" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-cash-stack me-2 text-primary"></i>
                                    Contract Value
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                               <input type="text"
       name="contract_value"
       value="{{ old('contract_value', $lead->contract_value) }}"
       class="form-control money-input"
       required
       id="contractValue"
       inputmode="decimal">
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Enter the total contract amount for this lead.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 bg-light rounded-4 h-100">
                            <div class="card-body">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="bi bi-calculator me-2 text-success"></i>
                                    Current Paid Total
                                </label>
                                <div id="balanceDisplay" class="display-6 fw-bold text-success mb-2">$0.00</div>
                                <small class="text-muted">
                                    This value updates automatically based on the contributions below.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contribution Table -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 py-3 rounded-top-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h6 class="mb-0 fw-semibold text-dark">
                                <i class="bi bi-piggy-bank me-2 text-primary"></i>
                                Contributions List
                            </h6>
                            <span class="text-muted small">
                                Add and manage all payments related to this contract
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 contribution-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3">Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Check #</th>
                                        <th>Notes</th>
                                        <th class="text-center" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="aportTable">
                                    @foreach($lead->finanzas ?? [] as $index => $aporte)
                                        <tr>
                                            <td class="px-3">
                                                <input type="date"
                                                       name="finanzas[{{ $index }}][date]"
                                                       class="form-control"
                                                       value="{{ old("finanzas.$index.date", $aporte['date']) }}">
                                            </td>
<td>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="text"
               name="finanzas[{{ $index }}][amount]"
               class="form-control aporte-value money-input"
               value="{{ old("finanzas.$index.amount", $aporte['amount']) }}"
               data-existing="1"
               inputmode="decimal">
    </div>
</td>
                                            <td>
                                                <select name="finanzas[{{ $index }}][method]" class="form-select method-select">
                                                    <option value="">Select</option>
                                                    <option value="Cash" {{ $aporte['method'] === 'Cash' ? 'selected' : '' }}>Cash</option>
                                                    <option value="Check" {{ $aporte['method'] === 'Check' ? 'selected' : '' }}>Check</option>
                                                    <option value="Transfer" {{ $aporte['method'] === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="text"
                                                       name="finanzas[{{ $index }}][check_number]"
                                                       class="form-control check-number-input"
                                                       value="{{ $aporte['check_number'] ?? '' }}">
                                            </td>

                                            <td>
                                                <textarea name="finanzas[{{ $index }}][notes]"
                                                          rows="1"
                                                          class="form-control form-control-sm"
                                                          placeholder="Add notes...">{{ $aporte['notes'] }}</textarea>
                                            </td>

                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-outline-danger btn-sm rounded-circle remove-row"
                                                        title="Remove">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if(empty($lead->finanzas) || count($lead->finanzas) === 0)
                                        <tr class="contribution-empty-row">
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="bi bi-receipt-cutoff d-block mb-2 fs-4"></i>
                                                No contributions added yet.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
                    <div class="text-muted small">
                        Changes are not saved until you click <strong>Save Financials</strong>.
                    </div>

                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-save me-2"></i>
                        Save Financials
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>




    // =============================================
    // CONTRIBUTIONS
    // =============================================

    function initializeContributions() {
        const tableBody = document.getElementById('aportTable');
        if (!tableBody) return;

        const addBtn = document.getElementById('addRow');
        const contributionsForm = document.getElementById('contributionsForm');

        if (addBtn) {
            addBtn.removeEventListener('click', addContributionRow);
            addBtn.addEventListener('click', addContributionRow);
        }

        if (!tableBody.dataset.bound) {
            tableBody.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.remove-row');
                if (!removeBtn) return;

                const row = removeBtn.closest('tr');
                if (!row) return;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete contribution?',
                        text: 'This contribution will be removed and saved immediately.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.remove();
                            ensureContributionEmptyRow();

                            if (typeof updateBalance === 'function') {
                                updateBalance();
                            }

                            if (contributionsForm) {
                                contributionsForm.submit();
                            }
                        }
                    });
                } else {
                    if (confirm('Delete this contribution?')) {
                        row.remove();
                        ensureContributionEmptyRow();

                        if (typeof updateBalance === 'function') {
                            updateBalance();
                        }

                        if (contributionsForm) {
                            contributionsForm.submit();
                        }
                    }
                }
            });

            tableBody.dataset.bound = 'true';
        }

        tableBody.querySelectorAll('tr').forEach(row => {
            if (!row.classList.contains('contribution-empty-row')) {
                bindContributionRow(row);
            }
        });
    }

    function addContributionRow() {
        const tableBody = document.getElementById('aportTable');
        if (!tableBody) return;

        const emptyRow = tableBody.querySelector('.contribution-empty-row');
        if (emptyRow) {
            emptyRow.remove();
        }

        const rowIndex = tableBody.querySelectorAll('tr:not(.contribution-empty-row)').length;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="px-3">
                <input
                    type="date"
                    name="finanzas[${rowIndex}][date]"
                    class="form-control"
                    required>
            </td>

            <td>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input
                        type="number"
                        step="0.01"
                        name="finanzas[${rowIndex}][amount]"
                        class="form-control aporte-value"
                        required>
                </div>
            </td>

            <td>
                <select
                    name="finanzas[${rowIndex}][method]"
                    class="form-select method-select">
                    <option value="">Select</option>
                    <option value="Cash">Cash</option>
                    <option value="Check">Check</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </td>

            <td>
                <input
                    type="text"
                    name="finanzas[${rowIndex}][check_number]"
                    class="form-control check-number-input"
                    disabled>
            </td>

            <td>
                <textarea
                    name="finanzas[${rowIndex}][notes]"
                    rows="1"
                    class="form-control form-control-sm"
                    placeholder="Add notes..."></textarea>
            </td>

            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm rounded-circle remove-row"
                    title="Remove">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        bindContributionRow(newRow);

        if (typeof updateBalance === 'function') {
            updateBalance();
        }
    }

    function bindContributionRow(row) {
        const methodSelect = row.querySelector('.method-select');
        const checkInput = row.querySelector('.check-number-input');
        const amountInput = row.querySelector('.aporte-value');

        if (methodSelect && checkInput && !methodSelect.dataset.bound) {
            const toggleCheckNumber = () => {
                if (methodSelect.value === 'Check') {
                    checkInput.removeAttribute('disabled');
                } else {
                    checkInput.value = '';
                    checkInput.setAttribute('disabled', true);
                }
            };

            methodSelect.addEventListener('change', toggleCheckNumber);
            methodSelect.dataset.bound = 'true';
            toggleCheckNumber();
        }

        if (amountInput && !amountInput.dataset.bound) {
            amountInput.addEventListener('input', function () {
                if (typeof updateBalance === 'function') {
                    updateBalance();
                }
            });
            amountInput.dataset.bound = 'true';
        }
    }

    function ensureContributionEmptyRow() {
        const tableBody = document.getElementById('aportTable');
        if (!tableBody) return;

        const realRows = [...tableBody.querySelectorAll('tr')]
            .filter(row => !row.classList.contains('contribution-empty-row'));

        const existingEmptyRow = tableBody.querySelector('.contribution-empty-row');

        if (realRows.length === 0 && !existingEmptyRow) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'contribution-empty-row';
            emptyRow.innerHTML = `
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-receipt-cutoff d-block mb-2 fs-4"></i>
                    No contributions added yet.
                </td>
            `;
            tableBody.appendChild(emptyRow);
        }

        if (realRows.length > 0 && existingEmptyRow) {
            existingEmptyRow.remove();
        }
    }




    document.addEventListener('DOMContentLoaded', function () {
        initializeContributions();
        initializeContributionMoneyFormatting();
        updateBalance();
        prepareContributionsFormBeforeSubmit();
    });

    function initializeContributions() {
        const tableBody = document.getElementById('aportTable');
        const addBtn = document.getElementById('addRow');

        if (!tableBody) return;

        if (addBtn) {
            addBtn.removeEventListener('click', addContributionRow);
            addBtn.addEventListener('click', addContributionRow);
        }

        if (!tableBody.dataset.bound) {
            tableBody.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.remove-row');
                if (!removeBtn) return;

                const row = removeBtn.closest('tr');
                if (!row) return;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete contribution?',
                        text: 'This contribution will be removed.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.remove();
                            ensureContributionEmptyRow();
                            reindexContributionRows();
                            updateBalance();
                        }
                    });
                } else {
                    if (confirm('Delete this contribution?')) {
                        row.remove();
                        ensureContributionEmptyRow();
                        reindexContributionRows();
                        updateBalance();
                    }
                }
            });

            tableBody.addEventListener('change', function (e) {
                if (e.target.classList.contains('method-select')) {
                    const row = e.target.closest('tr');
                    toggleCheckNumber(row);
                }
            });

            tableBody.addEventListener('input', function (e) {
                if (e.target.classList.contains('aporte-value') || e.target.id === 'contractValue') {
                    updateBalance();
                }
            });

            tableBody.dataset.bound = 'true';
        }

        tableBody.querySelectorAll('tr').forEach(row => {
            if (!row.classList.contains('contribution-empty-row')) {
                bindContributionRow(row);
            }
        });
    }

    function addContributionRow() {
        const tableBody = document.getElementById('aportTable');
        if (!tableBody) return;

        const emptyRow = tableBody.querySelector('.contribution-empty-row');
        if (emptyRow) emptyRow.remove();

        const rowIndex = tableBody.querySelectorAll('tr:not(.contribution-empty-row)').length;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="px-3">
                <input
                    type="date"
                    name="finanzas[${rowIndex}][date]"
                    class="form-control">
            </td>

            <td>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input
                        type="text"
                        name="finanzas[${rowIndex}][amount]"
                        class="form-control aporte-value money-input"
                        inputmode="decimal">
                </div>
            </td>

            <td>
                <select
                    name="finanzas[${rowIndex}][method]"
                    class="form-select method-select">
                    <option value="">Select</option>
                    <option value="Cash">Cash</option>
                    <option value="Check">Check</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </td>

            <td>
                <input
                    type="text"
                    name="finanzas[${rowIndex}][check_number]"
                    class="form-control check-number-input"
                    disabled>
            </td>

            <td>
                <textarea
                    name="finanzas[${rowIndex}][notes]"
                    rows="1"
                    class="form-control form-control-sm"
                    placeholder="Add notes..."></textarea>
            </td>

            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm rounded-circle remove-row"
                    title="Remove">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        bindContributionRow(newRow);
        initializeContributionMoneyFormatting(newRow);
        updateBalance();
    }

    function bindContributionRow(row) {
        if (!row) return;
        toggleCheckNumber(row);

        const amountInput = row.querySelector('.aporte-value');
        if (amountInput && !amountInput.dataset.bound) {
            amountInput.addEventListener('input', updateBalance);
            amountInput.dataset.bound = 'true';
        }
    }

    function toggleCheckNumber(row) {
        if (!row) return;

        const methodSelect = row.querySelector('.method-select');
        const checkInput = row.querySelector('.check-number-input');

        if (!methodSelect || !checkInput) return;

        if (methodSelect.value === 'Check') {
            checkInput.removeAttribute('disabled');
        } else {
            checkInput.value = '';
            checkInput.setAttribute('disabled', true);
        }
    }

    function ensureContributionEmptyRow() {
        const tableBody = document.getElementById('aportTable');
        if (!tableBody) return;

        const realRows = [...tableBody.querySelectorAll('tr')]
            .filter(row => !row.classList.contains('contribution-empty-row'));

        const existingEmptyRow = tableBody.querySelector('.contribution-empty-row');

        if (realRows.length === 0 && !existingEmptyRow) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'contribution-empty-row';
            emptyRow.innerHTML = `
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-receipt-cutoff d-block mb-2 fs-4"></i>
                    No contributions added yet.
                </td>
            `;
            tableBody.appendChild(emptyRow);
        }

        if (realRows.length > 0 && existingEmptyRow) {
            existingEmptyRow.remove();
        }
    }

    function reindexContributionRows() {
        const tableBody = document.getElementById('aportTable');
        if (!tableBody) return;

        const rows = [...tableBody.querySelectorAll('tr')]
            .filter(row => !row.classList.contains('contribution-empty-row'));

        rows.forEach((row, index) => {
            row.querySelectorAll('input, select, textarea').forEach(field => {
                const name = field.getAttribute('name');
                if (!name) return;

                const newName = name.replace(/finanzas\[\d+\]/, `finanzas[${index}]`);
                field.setAttribute('name', newName);
            });
        });
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

    function initializeContributionMoneyFormatting(scope = document) {
        const contractValue = document.getElementById('contractValue');

        if (contractValue && !contractValue.dataset.boundMoney) {
            if (contractValue.value) {
                contractValue.value = formatMoneyValue(contractValue.value);
            }

            contractValue.addEventListener('focus', function () {
                this.value = unformatMoneyValue(this.value);
            });

            contractValue.addEventListener('blur', function () {
                this.value = formatMoneyValue(this.value);
                updateBalance();
            });

            contractValue.dataset.boundMoney = 'true';
        }

        scope.querySelectorAll('.aporte-value').forEach(input => {
            if (!input.dataset.boundMoney) {
                if (input.value) {
                    input.value = formatMoneyValue(input.value);
                }

                input.addEventListener('focus', function () {
                    this.value = unformatMoneyValue(this.value);
                });

                input.addEventListener('blur', function () {
                    this.value = formatMoneyValue(this.value);
                    updateBalance();
                });

                input.dataset.boundMoney = 'true';
            }
        });
    }

    function updateBalance() {
        const contractInput = document.getElementById('contractValue');
        const balanceDisplay = document.getElementById('balanceDisplay');

        if (!balanceDisplay) return;

        const contractValue = parseFloat(unformatMoneyValue(contractInput?.value || '0')) || 0;

        let totalPaid = 0;

        document.querySelectorAll('.aporte-value').forEach(input => {
            totalPaid += parseFloat(unformatMoneyValue(input.value || '0')) || 0;
        });

        balanceDisplay.textContent = `$${totalPaid.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;

        const totalAmountText = document.getElementById('totalAmountText');
        const balanceDueText = document.getElementById('balanceDueText');
        const chartPercentageText = document.getElementById('chartPercentageText');

        if (totalAmountText) {
            totalAmountText.textContent = `$${contractValue.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;
        }

        const due = contractValue - totalPaid;

        if (balanceDueText) {
            balanceDueText.textContent = `$${Math.max(due, 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;
        }

        if (chartPercentageText) {
            const percentage = contractValue > 0 ? Math.min((totalPaid / contractValue) * 100, 100) : 0;
            chartPercentageText.textContent = `${percentage.toFixed(0)}%`;
        }
    }

    function prepareContributionsFormBeforeSubmit() {
        const form = document.getElementById('contributionsForm');
        if (!form) return;

        form.addEventListener('submit', function () {
            const contractValue = document.getElementById('contractValue');
            if (contractValue) {
                contractValue.value = unformatMoneyValue(contractValue.value);
            }

            document.querySelectorAll('.aporte-value').forEach(input => {
                input.value = unformatMoneyValue(input.value);
            });
        });
    }
</script>