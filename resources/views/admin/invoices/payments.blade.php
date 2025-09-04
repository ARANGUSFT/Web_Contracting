@extends('admin.layouts.superadmin')


<style>
    .table-responsive-sticky {
        max-height: 70vh;
        overflow: auto;
    }
    .table-responsive-sticky thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: var(--bs-body-bg, #fff);
    }
    .w-100px { width: 100px; }
    .w-120px { width: 120px; }
    .w-140px { width: 140px; }
    .w-180px { width: 180px; }
</style>


@section('content')
@php
    // Reutilizamos la colección en la tabla y en los modales
    $payments = $invoice->payments()->latest('paid_at')->get();
@endphp

<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="mb-0">Payment History</h1>
        <a href="{{ route('superadmin.invoices.index') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to Invoices
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div><strong>Job Number:</strong> {{ $jobNumber }}</div>
            <div><strong>Crew:</strong> {{ $crew ?? '—' }}</div>
            <div><strong>Address:</strong> {{ $address ?? '—' }}</div>
            <div class="mt-2 d-flex gap-2">
                <span class="badge bg-success">Paid: ${{ number_format($invoice->paid, 2) }}</span>
                <span class="badge bg-warning text-dark">Remaining: ${{ number_format($invoice->due, 2) }}</span>
            </div>
        </div>
    </div>
{{-- =========================
=   Record Payment (Form)  =
========================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header fw-semibold">
        <i class="bi bi-wallet2 me-1"></i> Record Payment
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('superadmin.invoices.payments.store', $invoice) }}"
              enctype="multipart/form-data"
              class="row g-3"
              novalidate>
            @csrf

            {{-- Amount --}}
            <div class="col-md-3">
                <label class="form-label" for="amount">Amount</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input
                        id="amount"
                        type="number"
                        name="amount"
                        class="form-control @error('amount') is-invalid @enderror"
                        step="0.01"
                        min="0.01"
                        value="{{ old('amount') }}"
                        inputmode="decimal"
                        required
                        autocomplete="off">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Date --}}
            <div class="col-md-3">
                <label class="form-label" for="paid_at">Date</label>
                <input
                    id="paid_at"
                    type="date"
                    name="paid_at"
                    class="form-control @error('paid_at') is-invalid @enderror"
                    value="{{ old('paid_at', date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}"
                    required>
                @error('paid_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Method --}}
            <div class="col-md-3">
                <label class="form-label" for="method">Method</label>
                <select id="method" name="method" class="form-select @error('method') is-invalid @enderror">
                    <option value="">Select method...</option>
                    @foreach(['Wire','Check','Cash','Credit Card','ACH','Zelle','Other'] as $opt)
                        <option value="{{ $opt }}" {{ old('method')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Reference (tipo) --}}
            <div class="col-md-3">
                <label class="form-label" for="reference">Reference</label>
                <select id="reference" name="reference" class="form-select @error('reference') is-invalid @enderror">
                    <option value="">Select reference...</option>
                    @foreach(['Transaction #','Check #','Invoice #','Receipt #','None','Other'] as $opt)
                        <option value="{{ $opt }}" {{ old('reference')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Note --}}
            <div class="col-12">
                <label class="form-label" for="note">Note</label>
                <textarea
                    id="note"
                    name="note"
                    class="form-control @error('note') is-invalid @enderror"
                    rows="2"
                    placeholder="Payment reason/details">{{ old('note') }}</textarea>
                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Attachments --}}
            <div class="col-12">
                <label class="form-label" for="attachments">Attachments (PDF/IMG, multiple)</label>
                <input
                    id="attachments"
                    type="file"
                    name="attachments[]"
                    class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror"
                    multiple
                    accept=".pdf,image/*">
                <div class="form-text">
                    Max 5MB per file. Requires <code>php artisan storage:link</code>.
                </div>
                @error('attachments')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('attachments.*')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="col-12">
                <button class="btn btn-primary" type="submit"
                        onclick="this.disabled=true;this.form.submit();">
                    <i class="bi bi-save me-1"></i> Save payment
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================
=     Recorded Payments     =
========================= --}}
<div class="card shadow-sm">
    <div class="card-header fw-semibold">
        <i class="bi bi-cash-coin me-1"></i> Recorded Payments
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-hover m-0 align-middle text-nowrap">
            <thead class="table-light">
                <tr>
                    <th class="w-120px">Date</th>
                    <th class="w-120px text-end">Amount</th>
                    <th class="w-140px">Method</th>
                    <th class="w-180px">Reference</th>
                    <th>Note</th>
                    <th class="w-220px">Attachments</th>
                    <th class="w-110px text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td>{{ $p->paid_at?->format('Y-m-d') }}</td>
                        <td class="text-end">${{ number_format($p->amount, 2) }}</td>
                        <td>{{ $p->method ?? '—' }}</td>
                        <td>{{ $p->reference ?? '—' }}</td>
                        <td class="text-truncate" style="max-width:220px">{{ $p->note ?? '—' }}</td>

                        {{-- Attachments as chips with View / Download --}}
                        <td>
                            @php $urls = $p->attachment_urls; @endphp
                            @if(!empty($urls))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($urls as $i => $url)
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Attachment {{ $i+1 }}">
                                            <a href="{{ $url }}" target="_blank" rel="noopener"
                                               class="btn btn-outline-secondary"
                                               title="View attachment {{ $i+1 }}">
                                                <i class="bi bi-paperclip me-1"></i> View {{ $i+1 }}
                                            </a>
                                            {{-- Usa tu ruta de descarga: invoices.payments.download --}}
                                            <a href="{{ route('superadmin.invoices.payments.download', ['invoice' => $p->invoice_id, 'payment' => $p->id, 'index' => $i]) }}"
                                               class="btn btn-outline-success"
                                               title="Download attachment {{ $i+1 }}">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="text-center">
                            <div class="d-flex flex-column gap-1">
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editPaymentModal-{{ $p->id }}"
                                        title="Edit payment">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form method="POST" action="{{ route('superadmin.invoices.payments.destroy', [$invoice, $p]) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete payment?')"
                                            title="Delete payment">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No payments yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


    {{-- ====== MODALS (fuera del contenedor con overflow) ====== --}}
    @foreach($payments as $p)
    <div class="modal fade" id="editPaymentModal-{{ $p->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <form method="POST" action="{{ route('superadmin.invoices.payments.update', [$invoice, $p]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h5 class="modal-title">Edit Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label">Amount</label>
                  <input type="number" name="amount" class="form-control" step="0.01" min="0.01"
                         value="{{ number_format($p->amount, 2, '.', '') }}" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Date</label>
                  <input type="date" name="paid_at" class="form-control"
                         value="{{ optional($p->paid_at)->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Method</label>
                  @php $m = $p->method; @endphp
                  <select name="method" class="form-select">
                    <option value="">Select method...</option>
                    <option value="Wire"        {{ $m==='Wire' ? 'selected' : '' }}>Wire</option>
                    <option value="Check"       {{ $m==='Check' ? 'selected' : '' }}>Check</option>
                    <option value="Cash"        {{ $m==='Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Credit Card" {{ $m==='Credit Card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="ACH"         {{ $m==='ACH' ? 'selected' : '' }}>ACH</option>
                    <option value="Zelle"       {{ $m==='Zelle' ? 'selected' : '' }}>Zelle</option>
                    <option value="Other"       {{ $m==='Other' ? 'selected' : '' }}>Other</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Reference</label>
                  @php $r = $p->reference; @endphp
                  <select name="reference" class="form-select">
                    <option value="">Select reference...</option>
                    <option value="Transaction #" {{ $r==='Transaction #' ? 'selected' : '' }}>Transaction #</option>
                    <option value="Check #"       {{ $r==='Check #' ? 'selected' : '' }}>Check #</option>
                    <option value="Invoice #"     {{ $r==='Invoice #' ? 'selected' : '' }}>Invoice #</option>
                    <option value="Receipt #"     {{ $r==='Receipt #' ? 'selected' : '' }}>Receipt #</option>
                    <option value="None"          {{ $r==='None' ? 'selected' : '' }}>None</option>
                    <option value="Other"         {{ $r==='Other' ? 'selected' : '' }}>Other</option>
                  </select>
                </div>

                <div class="col-12">
                  <label class="form-label">Note</label>
                  <textarea name="note" class="form-control" rows="2" placeholder="Payment reason/details">{{ $p->note }}</textarea>
                </div>

                {{-- Existing attachments with remove checkboxes --}}
                <div class="col-12">
                  <label class="form-label">Existing attachments</label>
                  @php $existing = $p->attachments ?? []; @endphp

                  @if(!empty($existing))
                    <ul class="list-group mb-2">
                      @foreach($existing as $i => $path)
                        @php
                          $isAbsolute = preg_match('#^https?://#i', $path);
                          $url  = $isAbsolute ? $path : \Storage::disk('public')->url($path);  // ← URL pública
                          $name = basename($path);
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <div class="text-truncate" style="max-width: 60%">
                            <i class="bi bi-paperclip me-1"></i>
                            <a href="{{ $url }}" target="_blank" rel="noopener">{{ $name }}</a>
                          </div>
                          <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('superadmin.invoices.payments.download', ['invoice' => $p->invoice_id, 'payment' => $p->id, 'index' => $i]) }}" class="btn btn-sm btn-outline-success">Download</a>
                         
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  @else
                    <div class="text-muted">No attachments.</div>
                  @endif
                </div>


                {{-- Add new attachments --}}
                <div class="col-12">
                  <label class="form-label">Add attachments (PDF/IMG, multiple)</label>
                  <input type="file" name="attachments[]" class="form-control" multiple>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary">Update payment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endforeach
    {{-- ====== /MODALS ====== --}}
</div>
@endsection


<script>
  // Fallback: si Bootstrap JS no está cargado, lo cargamos desde CDN
  if (typeof bootstrap === 'undefined') {
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
    document.head.appendChild(s);
  }

  // Autofocus en Amount al abrir cualquier modal
  document.addEventListener('shown.bs.modal', function (e) {
    var input = e.target.querySelector('input[name="amount"]');
    if (input) { input.focus(); input.select(); }
  }, true);
</script>

