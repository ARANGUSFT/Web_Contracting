<!-- ================= QUOTE TAB ================= -->
<div class="tab-pane fade" id="quote">

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-circle bg-primary-subtle">
                        <i class="bi bi-file-earmark-text text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold text-dark">Quotes</h4>
                        <p class="text-muted small mb-0">Create and manage estimates for this lead</p>
                    </div>
                </div>
            </div>

            <!-- Create Quote Form -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4">
                    <h6 class="mb-0 fw-semibold text-dark">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Create New Quote
                    </h6>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('quotes.store') }}">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sq</label>
                                <input type="number" name="sq" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Material Cost per Sq</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="material_cost_per_sq" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Labor Cost per Sq</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="labor_cost_per_sq" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Other Costs</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="other_costs" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Profit Percentage</label>

                                <div class="quote-percentage-wrap">
                                    <select name="percentage" class="form-select quote-percentage-select" required>
                                        <option value="">Select percentage</option>
                                        @for($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}">{{ $i }}%</option>
                                        @endfor
                                    </select>

                                    <span class="quote-percentage-badge">%</span>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>
                                Save Quote
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Previous Quotes -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4">
                    <h6 class="mb-0 fw-semibold text-dark">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Previous Quotes
                    </h6>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">Sq</th>
                                    <th>Material Total</th>
                                    <th>Labor Total</th>
                                    <th>Other Costs</th>
                                    <th>Percentage</th>
                                    <th>Profit</th>
                                    <th>Total</th>
                                    <th class="text-end px-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lead->quotes as $quote)
                                    <tr>
                                        <td class="px-3 fw-semibold">{{ $quote->sq }}</td>
                                        <td>${{ number_format($quote->material_total, 2) }}</td>
                                        <td>${{ number_format($quote->labor_total, 2) }}</td>
                                        <td>${{ number_format($quote->other_costs, 2) }}</td>
                                        <td>{{ number_format($quote->percentage, 0) }}%</td>
                                        <td>${{ number_format($quote->profit, 2) }}</td>
                                        <td class="fw-bold text-success">${{ number_format($quote->quote_total, 2) }}</td>

                                        <td class="text-end px-3">
                                            <form id="delete-quote-form-{{ $quote->id }}"
                                                  action="{{ route('quotes.destroy', $quote->id) }}"
                                                  method="POST"
                                                  class="d-inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-circle"
                                                        onclick="confirmDelete({{ $quote->id }})"
                                                        title="Delete this quote">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-file-earmark-x d-block fs-3 mb-2"></i>
                                            No quotes created yet.
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

<style>
    .quote-percentage-wrap {
        position: relative;
    }

    .quote-percentage-select {
        height: 48px;
        padding: 0.75rem 3rem 0.75rem 1rem;
        border: 1px solid #dbe3ec;
        border-radius: 14px;
        background-color: #fff;
        font-weight: 600;
        color: #212529;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #6c757d 50%),
            linear-gradient(135deg, #6c757d 50%, transparent 50%);
        background-position:
            calc(100% - 18px) calc(50% - 3px),
            calc(100% - 12px) calc(50% - 3px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
    }

    .quote-percentage-select:hover {
        border-color: #b8c7d8;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .quote-percentage-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
        outline: none;
    }

    .quote-percentage-badge {
        position: absolute;
        top: 50%;
        right: 38px;
        transform: translateY(-50%);
        font-size: 0.85rem;
        font-weight: 700;
        color: #6c757d;
        pointer-events: none;
        background: #f8f9fa;
        border-radius: 999px;
        padding: 2px 8px;
    }
</style>


<script>
        // =============================================
    // QUOTES
    // =============================================

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-quote-form-${id}`)?.submit();
            }
        });
    }
</script>