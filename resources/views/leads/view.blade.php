@extends('layouts.app')

@section('content')


<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="text-primary m-0">
            <i class="bi bi-person-circle"></i> Customer Details
        </h2>
    </div>
    
   
    @if(session('success'))
        <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif


    @php
        $statusList = [
            1 => ['label' => 'Lead', 'color' => 'bg-warning'],
            2 => ['label' => 'Prospect', 'color' => 'bg-orange'],
            3 => ['label' => 'Approved', 'color' => 'bg-success'],
            4 => ['label' => 'Completed', 'color' => 'bg-primary'],
            5 => ['label' => 'Invoiced', 'color' => 'bg-danger'],
        ];

        $currentIndex = array_search($lead->estado, array_keys($statusList));
        $statusKeys = array_keys($statusList);
    @endphp

 
    {{-- Tarjeta --}}
    <div class="card shadow-lg p-4">
        <div class="d-flex justify-content-between align-items-center">

            <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <h4 class="text-primary">{{ $lead->first_name }} {{ $lead->last_name }}</h4>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Textual Info -->
                <div class="text-end me-2">
                    <h5 class="fw-bold mb-0" id="totalAmountText">$0.00</h5>
                    <div class="text-danger fw-bold">Balance Due</div>
                    <div class="text-danger small" id="balanceDueText">$0.00</div>
                </div>
            
                <!-- Chart with percentage -->
                <div class="position-relative" style="width: 70px; height: 70px;">
                    <canvas id="balanceChart" class="balance-chart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle fw-bold small" id="chartPercentageText">0%</div>
                </div>
            </div>
            

        </div>

        <p><strong>📞 Phone:</strong> <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></p>
        <p><strong>📧 Email:</strong> <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></p>
        <p class="small text-muted mb-2">
            <i class="bi bi-geo-alt text-warning"></i>
            {{ $lead->street }} {{ $lead->suite }}, {{ $lead->city }}, {{ $lead->state }} {{ $lead->zip }}
        </p>
        <p><strong>📅 Created At:</strong> {{ $lead->created_at->format('d M, Y') }}</p>
        <p><strong>🕒 Last Touched:</strong> 
            {{ $lead->last_touched_at ? $lead->last_touched_at->diffForHumans() : 'Never' }}
        </p>
        
        


        <form id="statusForm" action="{{ route('leads.assignstatus', $lead->id) }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="status" id="selectedStatus">

            <label class="form-label fw-semibold text-muted">📌 Status:</label>
            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                {{-- Botón Retroceder --}}
                @if ($currentIndex > 0)
                    <button type="button" class="btn btn-outline-secondary" onclick="changeStatus({{ $statusKeys[$currentIndex - 1] }})">
                        &#8592; Back
                    </button>
                @endif

                {{-- Estados en fila --}}
                @foreach ($statusList as $key => $status)
                    <div class="status-box {{ $status['color'] }} {{ $lead->estado == $key ? 'status-active' : 'status-inactive' }}">
                        {{ $status['label'] }}
                    </div>
                @endforeach

                {{-- Botón Avanzar --}}
                @if ($currentIndex < count($statusList) - 1)
                    <button type="button" class="btn btn-outline-primary" onclick="changeStatus({{ $statusKeys[$currentIndex + 1] }})">
                        Next &#8594;
                    </button>
                @endif
            </div>
        </form>

        <hr>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="border p-3 rounded bg-light">
                    <strong>Total Expenses:</strong>
                    <div id="totalExpensesDisplayBelow" class="h5 text-danger">${{ number_format($lead->total_expenses, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border p-3 rounded bg-light">
                    <strong>Total Paid:</strong>
                    <div id="totalPaidDisplayBelow" class="h5 text-primary">${{ number_format($lead->total_paid, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border p-3 rounded bg-light">
                    <strong>Net Profit:</strong>
                    <div id="netProfitDisplayBelow" class="h5 fw-bold {{ $lead->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($lead->net_profit, 2) }}
                    </div>
                </div>
            </div>
        </div>

    
    </div>
    {{-- Fin --}}



    

    <!-- Pestañas -->
    <ul class="nav nav-tabs mt-4" id="leadTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#photos">Photos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#contribution">Contribution</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#expenses">Expenses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#quote">Quote</a>
        </li>
    </ul>


    <!-- Toast de éxito -->
    @if(session('success'))
         <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
             <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
                 <div class="d-flex">
                     <div class="toast-body">
                         {{ session('success') }}
                     </div>
                     <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                 </div>
             </div>
         </div>
    @endif


    <div class="tab-content p-4 bg-white shadow-lg rounded">
        
        <!-- Chat Tab -->
        <div class="tab-pane fade show active" id="chat">
                <h4 class="mb-3"><i class="bi bi-chat-dots me-2"></i> Conversation</h4>
    
                <div id="chat-box" class="border rounded shadow-sm p-3 mb-4" style="height: 350px; overflow-y: auto; background-color: #f2f6fb;">
                    @foreach($messages as $msg)
                        @php
                            $isSeller = isset($msg->team);
                            $senderName = $isSeller ? $msg->team->name : ($msg->user->name ?? 'Usuario');
                            $isMine = $msg->user_id == auth()->id();
    
                            $alignment = $isMine ? 'justify-content-end' : 'justify-content-start';
                            $bubbleClass = $isMine ? 'bg-primary text-white' : 'bg-white text-dark';
                            $nameColor = $isMine ? 'text-light' : 'text-muted';
                            $timeAlign = $isMine ? 'text-end' : 'text-start';
                        @endphp
    
                        <div class="d-flex {{ $alignment }} mb-3">
                            <div class="p-3 rounded shadow-sm {{ $bubbleClass }}" style="max-width: 80%;">
                                <div class="fw-bold small {{ $nameColor }}">{{ $senderName }}</div>
                                <div class="small">{{ $msg->message }}</div>
                                <div class="small text-muted {{ $timeAlign }} mt-1" style="font-size: 0.75rem;">
                                    {{ $msg->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
    
                <form id="chatForm" method="POST" action="{{ route('lead.messages.store') }}">
                    @csrf
                    <input type="hidden" id="lead_id" name="lead_id" value="{{ $lead->id }}">
                    <div class="input-group">
                        <input type="text" id="message" name="message" class="form-control rounded-start-pill" placeholder="Write a message..." required>
                        <button class="btn btn-success rounded-end-pill px-4" type="submit">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
        </div>
        

        <!-- Photos Tab -->
        <div class="tab-pane fade" id="photos">
            <h4><i class="bi bi-images"></i> Photos</h4>

            <!-- Formulario de subida -->
            <form id="uploadForm">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                <div class="mb-3">
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Upload Imagen</button>
            </form>

            <hr>

            <!-- Galería de imágenes -->
            <div class="row g-3" id="gallery-box">
                @foreach ($images as $image)
                    <div class="col-md-4" id="image-{{ $image->id }}">
                        <div class="card shadow">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="gallery-item img-fluid" onclick="showPreview('{{ asset('storage/' . $image->image_path) }}')">
                            <div class="card-body text-center">
        
                                <a href="{{ asset('storage/' . $image->image_path) }}" download class="btn btn-outline-primary btn-sm mt-2">
                                    Download 📥
                                </a>
                                <form action="{{ route('lead.images.destroy', $image->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm mt-2">
                                        Delete ❌
                                    </button>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

       
        <!-- Documents Section -->
        <div class="tab-pane fade" id="documents">
            <h4><i class="bi bi-folder"></i> Documents</h4>

            <div class="accordion" id="documentsAccordion">
                @php
                    $documentTypes = [
                        ['title' => 'Job Paperwork', 'type' => 'files'],
                        ['title' => 'Other', 'type' => 'finanzas'],
                        ['title' => 'Packets', 'type' => 'anexos'],
                        ['title' => 'Invoices', 'type' => 'contratos'],
                    ];

                    $iconsByExtension = [
                        'pdf' => 'bi-file-earmark-pdf text-danger',
                        'xls' => 'bi-file-earmark-spreadsheet text-success',
                        'xlsx' => 'bi-file-earmark-spreadsheet text-success',
                        'doc' => 'bi-file-earmark-word text-primary',
                        'docx' => 'bi-file-earmark-word text-primary',
                        'default' => 'bi-file-earmark text-secondary',
                    ];
                @endphp

                @foreach($documentTypes as $index => $docType)
                    @php
                        $files = $lead->files()->where('type', $docType['type'])->get();
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                <i class="bi bi-folder-fill me-2"></i> {{ $docType['title'] }} ({{ $files->count() }})
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#documentsAccordion">
                            <div class="accordion-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Documento</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($files as $file)
                                            @php
                                                $path = $file->file_path;
                                                $original_name = basename($path);
                                                $extension = pathinfo($path, PATHINFO_EXTENSION);
                                                $iconClass = $iconsByExtension[$extension] ?? $iconsByExtension['default'];
                                            @endphp
                                            <tr>
                                                <td>{{ $original_name }}</td>
                                                <td>
                                                    <i class="bi {{ $iconClass }}"></i> {{ strtoupper($extension) }}
                                                </td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $path) }}" download class="btn btn-sm btn-outline-primary">
                                                        Download <i class="bi bi-download"></i>
                                                    </a>
                                                    <form action="{{ route('leads.files.destroy', $file->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            Delete <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <form action="{{ route('leads.files.store', $lead->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $docType['type'] }}">
                                    <input type="file" name="file" class="form-control mb-2" required>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-upload"></i> Subir {{ $docType['title'] }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


       <!-- Contributions Tab -->
        <div class="tab-pane fade show" id="contribution">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4">
                        <i class="bi bi-receipt me-2"></i> Contribution
                    </h4>

                    <form method="POST" action="{{ route('leads.finanzas.update', $lead->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Contract Value -->
                        <div class="mb-4 row align-items-center">
                            <label for="contractValue" class="col-md-4 col-form-label fw-bold">Contract Value</label>
                            <div class="col-md-8">
                                <input type="number" step="0.01" name="contract_value"
                                    value="{{ old('contract_value', $lead->contract_value) }}"
                                    class="form-control" required id="contractValue">
                            </div>
                        </div>

                        <!-- Contributions Table -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Contributions</label>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Check #</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="aportTable">
                                        @foreach($lead->finanzas ?? [] as $index => $aporte)
                                            <tr>
                                                <td>
                                                    <input type="date" name="finanzas[{{ $index }}][date]"
                                                        class="form-control @error("finanzas.$index.date") is-invalid @enderror"
                                                        value="{{ old("finanzas.$index.date", $aporte['date']) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="finanzas[{{ $index }}][amount]"
                                                        class="form-control aporte-value @error("finanzas.$index.amount") is-invalid @enderror"
                                                        value="{{ old("finanzas.$index.amount", $aporte['amount']) }}">
                                                </td>
                                                <td>
                                                    <select name="finanzas[{{ $index }}][method]"
                                                        class="form-select method-select @error("finanzas.$index.method") is-invalid @enderror">
                                                        <option value="">Select</option>
                                                        <option value="Cash" {{ old("finanzas.$index.method", $aporte['method']) === 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                                                        <option value="Check" {{ old("finanzas.$index.method", $aporte['method']) === 'Check' ? 'selected' : '' }}>🧾 Check</option>
                                                        <option value="Transfer" {{ old("finanzas.$index.method", $aporte['method']) === 'Transfer' ? 'selected' : '' }}>💳 Transfer</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="finanzas[{{ $index }}][check_number]"
                                                        class="form-control check-number-input @error("finanzas.$index.check_number") is-invalid @enderror"
                                                        value="{{ old("finanzas.$index.check_number", $aporte['check_number'] ?? '') }}">
                                                </td>
                                                <td>
                                                    <textarea name="finanzas[{{ $index }}][notes]"
                                                        class="form-control form-control-sm @error("finanzas.$index.notes") is-invalid @enderror"
                                                        placeholder="Add notes..." rows="1">{{ old("finanzas.$index.notes", $aporte['notes']) }}</textarea>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">
                                <i class="bi bi-plus-circle"></i> Add Contribution
                            </button>
                        </div>

                        <!-- Balance -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Balance:</label>
                            <div id="balanceDisplay" class="h5 text-success">$0.00</div>
                        </div>

                        <!-- Submit -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4 mt-3">
                                <i class="bi bi-save me-1"></i> Save Financials
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <!-- Expense -->
        <div class="tab-pane fade show" id="expenses">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4"><i class="bi bi-currency-dollar me-2"></i> Add Expense</h4>

                    <form method="POST" action="{{ route('leads.expenses.update', $lead->id) }}">
                        @csrf
                    
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-nowrap">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th style="min-width: 120px;">Date</th>
                                        <th style="min-width: 180px;">Type</th>
                                        <th style="min-width: 200px;">Amount</th>
                                        <th style="min-width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="expensesTable">
                                    @foreach($lead->expenses ?? [] as $index => $expense)
                                        <tr>
                                            <td>
                                                <input type="date" name="expenses[{{ $index }}][expense_date]" 
                                                       value="{{ $expense->expense_date->format('Y-m-d') }}" 
                                                       class="form-control" required>
                                            </td>
                                            <td>
                                                <select class="form-select expense-type" data-index="{{ $index }}">
                                                    <option value="">Select Type</option>
                                                    <option value="material" {{ $expense->material ? 'selected' : '' }}>Material</option>
                                                    <option value="labor_cost" {{ $expense->labor_cost ? 'selected' : '' }}>Labor</option>
                                                    <option value="commission_percentage" {{ $expense->commission_percentage ? 'selected' : '' }}>Commission</option>
                                                    <option value="permit" {{ $expense->permit ? 'selected' : '' }}>Permit</option>
                                                    <option value="supplement" {{ $expense->supplement ? 'selected' : '' }}>Supplement</option>
                                                    <option value="other_expenses" {{ $expense->other_expenses ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="expenses[{{ $index }}][material]" 
                                                       value="{{ $expense->material }}" 
                                                       class="form-control amount-field mt-2 {{ $expense->material ? '' : 'd-none' }}" 
                                                       placeholder="Material ($)">
                                                
                                                <input type="number" step="0.01" name="expenses[{{ $index }}][labor_cost]" 
                                                       value="{{ $expense->labor_cost }}" 
                                                       class="form-control amount-field mt-2 {{ $expense->labor_cost ? '' : 'd-none' }}" 
                                                       placeholder="Labor ($)">
                                                
                                                <input type="number" step="0.01" name="expenses[{{ $index }}][commission_percentage]" 
                                                       value="{{ $expense->commission_percentage }}" 
                                                       class="form-control amount-field mt-2 {{ $expense->commission_percentage ? '' : 'd-none' }}" 
                                                       placeholder="Commission (%)">
                                                
                                                <input type="text" name="expenses[{{ $index }}][permit]" 
                                                       value="{{ $expense->permit }}" 
                                                       class="form-control amount-field mt-2 {{ $expense->permit ? '' : 'd-none' }}" 
                                                       placeholder="Permit">
                                                
                                                <input type="number" step="0.01" name="expenses[{{ $index }}][supplement]" 
                                                       value="{{ $expense->supplement }}" 
                                                       class="form-control amount-field mt-2 {{ $expense->supplement ? '' : 'd-none' }}" 
                                                       placeholder="Supplement ($)">
                                                
                                                <input type="number" step="0.01" name="expenses[{{ $index }}][other_expenses]" 
                                                       value="{{ $expense->other_expenses }}" 
                                                       class="form-control amount-field mt-2 {{ $expense->other_expenses ? '' : 'd-none' }}" 
                                                       placeholder="Other ($)">
                                            </td>
                                            <td class="text-center">
                                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-expense"
                                                    data-id="{{ $expense->id }}"
                                                    data-url="{{ route('leads.expenses.destroy', [$lead->id, $expense->id]) }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addExpenseRow">
                                <i class="bi bi-plus-circle"></i> Add Expense
                            </button>
                    
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Save Expenses
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>


        <!-- Quote -->
        <div class="tab-pane fade show" id="quote">

            <!-- Form to create a quote -->
            <form method="POST" action="{{ route('quotes.store') }}">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <div class="row">
                    <div class="col-md-4">
                        <label>Sq</label>
                        <input type="number" name="sq" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Material Cost per Sq</label>
                        <input type="number" step="0.01" name="material_cost_per_sq" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Labor Cost per Sq</label>
                        <input type="number" step="0.01" name="labor_cost_per_sq" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Other Costs</label>
                        <input type="number" step="0.01" name="other_costs" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Profit Percentage (%)</label>
                        <input type="number" step="0.01" name="percentage" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Quote</button>
            </form>

            <!-- Quote table -->
            <h5 class="mt-4">Previous Quotes</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sq</th>
                        <th>Material Total</th>
                        <th>Labor Total</th>
                        <th>Other Costs</th>
                        <th>Profit</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->quotes as $quote)
                    <tr>
                        <td>{{ $quote->sq }}</td>
                        <td>{{ number_format($quote->material_total, 2) }}</td>
                        <td>{{ number_format($quote->labor_total, 2) }}</td>
                        <td>{{ number_format($quote->other_costs, 2) }}</td>
                        <td>{{ number_format($quote->profit, 2) }}</td>
                        <td>{{ number_format($quote->quote_total, 2) }}</td>
                        <td>
                            <form action="{{ route('quotes.destroy', $quote->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quote?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>


    </div>

</div>

<!-- Modal para vista previa de imágenes -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewLabel">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="previewImage" class="img-fluid rounded shadow" alt="Vista previa de la imagen">
            </div>
        </div>
    </div>
</div>





<style>
    #chat-box::-webkit-scrollbar {
        width: 6px;
    }
    #chat-box::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.2);
        border-radius: 3px;
    }
</style>



<!--Actulizar pestana sin refrescar pagina-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Restaurar la última pestaña activa
        const lastTab = localStorage.getItem('activeLeadTab');
        if (lastTab) {
            const trigger = document.querySelector(`a[data-bs-toggle="tab"][href="${lastTab}"]`);
            if (trigger) {
                const tab = new bootstrap.Tab(trigger);
                tab.show();
            }
        }
    
        // Guardar pestaña activa al cambiar
        const tabLinks = document.querySelectorAll('#leadTabs a[data-bs-toggle="tab"]');
        tabLinks.forEach(link => {
            link.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('activeLeadTab', e.target.getAttribute('href'));
            });
        });
    });
</script>

{{-- Grafica del balance --}}
<script>
    let chart;
    
    const renderChart = (paid, remaining) => {
        const ctx = document.getElementById('balanceChart').getContext('2d');
        if (chart) chart.destroy();
    
        chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Remaining'],
                datasets: [{
                    data: [paid, remaining],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    };
    
    const updateBalance = () => {
        const aporteInputs = document.querySelectorAll('.aporte-value');
        let total = 0;
        aporteInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
    
        const contractValue = parseFloat(document.getElementById('contractValue').value) || 0;
        const balance = contractValue - total;
        const percentage = contractValue > 0 ? (total / contractValue) * 100 : 0;
    
        // Actualizar textos
        document.getElementById('balanceDisplay').textContent = `$${balance.toFixed(2)}`;
        document.getElementById('totalAmountText').textContent = `$${contractValue.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('balanceDueText').textContent = `$${balance.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('chartPercentageText').textContent = `${percentage.toFixed(0)}%`;
    
        // Cambiar color del porcentaje según el progreso
        const percentEl = document.getElementById('chartPercentageText');
        if (percentage >= 100) {
            percentEl.classList.add('text-success');
            percentEl.classList.remove('text-danger');
        } else {
            percentEl.classList.add('text-danger');
            percentEl.classList.remove('text-success');
        }
    
        renderChart(total, Math.max(0, balance));
    };
    
    document.addEventListener('DOMContentLoaded', function () {
        updateBalance();
    
        document.getElementById('contractValue').addEventListener('input', updateBalance);
        document.querySelectorAll('.aporte-value').forEach(input => {
            input.addEventListener('input', updateBalance);
        });
    });
</script>

{{-- Script Contribution --}}
<script>


    const toggleCheckNumber = (select) => {
        const tr = select.closest('tr');
        const checkInput = tr.querySelector('.check-number-input');
        if (checkInput) {
            if (select.value === 'Check') {
                checkInput.removeAttribute('disabled');
            } else {
                checkInput.value = '';
                checkInput.setAttribute('disabled', true);
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        updateBalance();

        document.getElementById('contractValue').addEventListener('input', updateBalance);

        document.querySelectorAll('.aporte-value').forEach(input => {
            input.addEventListener('input', updateBalance);
        });

        document.querySelectorAll('.method-select').forEach(select => {
            toggleCheckNumber(select);
            select.addEventListener('change', () => toggleCheckNumber(select));
        });

        document.getElementById('addRow').addEventListener('click', function () {
            const tableBody = document.querySelector('#aportTable');
            const rowIndex = tableBody.querySelectorAll('tr').length;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="date" name="finanzas[${rowIndex}][date]" class="form-control" required /></td>
                <td><input type="number" step="0.01" name="finanzas[${rowIndex}][amount]" class="form-control aporte-value" required /></td>
                <td>
                    <select name="finanzas[${rowIndex}][method]" class="form-control method-select">
                        <option value="">Select</option>
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="finanzas[${rowIndex}][check_number]" class="form-control check-number-input" disabled />
                </td>
                <td><input type="text" name="finanzas[${rowIndex}][notes]" class="form-control" /></td>
                <td><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
            `;

            tableBody.appendChild(newRow);

            newRow.querySelector('.aporte-value').addEventListener('input', updateBalance);
            const methodSelect = newRow.querySelector('.method-select');
            methodSelect.addEventListener('change', () => toggleCheckNumber(methodSelect));

            updateBalance();
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
                updateBalance();
            }
        });
    });
</script>







{{-- Expenses --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = document.querySelectorAll('#expensesTable tr').length;
    
        function toggleAmountFields(select) {
            const row = select.closest('tr');
            const index = select.dataset.index;
            const selected = select.value;
    
            row.querySelectorAll('.amount-field').forEach(input => {
                input.classList.add('d-none');
            });
    
            const visibleInput = row.querySelector(`[name="expenses[${index}][${selected}]"]`);
            if (visibleInput) {
                visibleInput.classList.remove('d-none');
            }
        }
    
        function bindEventsToRow(row) {
            const select = row.querySelector('.expense-type');
            if (!select) return;
    
            select.addEventListener('change', function () {
                toggleAmountFields(this);
            });
    
            toggleAmountFields(select); // Inicializar
        }
    
        document.querySelectorAll('#expensesTable tr').forEach(row => bindEventsToRow(row));
    
        document.getElementById('addExpenseRow').addEventListener('click', function () {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="date" name="expenses[${rowIndex}][expense_date]" class="form-control" required>
                </td>
                <td>
                    <select class="form-select expense-type" data-index="${rowIndex}">
                        <option value="">Select Type</option>
                        <option value="material">Material</option>
                        <option value="labor_cost">Labor</option>
                        <option value="commission_percentage">Commission</option>
                        <option value="permit">Permit</option>
                        <option value="supplement">Supplement</option>
                        <option value="other_expenses">Other</option>
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" name="expenses[${rowIndex}][material]" class="form-control amount-field d-none" placeholder="Material ($)">
                    <input type="number" step="0.01" name="expenses[${rowIndex}][labor_cost]" class="form-control amount-field d-none" placeholder="Labor ($)">
                    <input type="number" step="0.01" name="expenses[${rowIndex}][commission_percentage]" class="form-control amount-field d-none" placeholder="Commission (%)">
                    <input type="text" name="expenses[${rowIndex}][permit]" class="form-control amount-field d-none" placeholder="Permit">
                    <input type="number" step="0.01" name="expenses[${rowIndex}][supplement]" class="form-control amount-field d-none" placeholder="Supplement ($)">
                    <input type="number" step="0.01" name="expenses[${rowIndex}][other_expenses]" class="form-control amount-field d-none" placeholder="Other ($)">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                </td>
            `;
    
            document.getElementById('expensesTable').appendChild(newRow);
            bindEventsToRow(newRow);
            rowIndex++;
        });
    
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>

{{-- Eliminar Expenses --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-expense').forEach(function (button) {
            button.addEventListener('click', function () {
                const expenseId = this.getAttribute('data-id');
                const deleteUrl = this.getAttribute('data-url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This expense will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                button.closest('tr').remove();
                                Swal.fire('Deleted!', data.success, 'success');
                            } else {
                                Swal.fire('Error', data.error || 'Could not delete expense.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'An error occurred while deleting.', 'error');
                        });
                    }
                });
            });
        });
    });

</script>
    



{{-- Total expenses / paid / nex profit --}}
<script>
    let expensesChart = null;
    
    const renderExpensesChart = (expenses, profit) => {
        const ctx = document.getElementById('expensesOnlyChart')?.getContext('2d');
        if (!ctx) return;
    
        if (expensesChart) expensesChart.destroy();
    
        expensesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Expenses', 'Net Profit'],
                datasets: [{
                    data: [expenses, profit],
                    backgroundColor: ['#dc3545', '#198754']
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Expenses vs Profit' }
                }
            }
        });
    };
    
    const updateExpenseSummary = () => {
        const contractValue = parseFloat(document.getElementById('contractValue')?.value) || 0;
        let totalExpenses = 0;
    
        document.querySelectorAll('#expensesTable tr').forEach(row => {
            const type = row.querySelector('.expense-type')?.value;
            if (!type) return;
    
            const input = row.querySelector(`[name*="[${type}]"]`);
            if (!input || input.classList.contains('d-none')) return;
    
            const val = parseFloat(input.value) || 0;
            if (type === 'commission_percentage') {
                totalExpenses += contractValue * (val / 100);
            } else {
                totalExpenses += val;
            }
        });
    
        let totalPaid = 0;
        document.querySelectorAll('.aporte-value').forEach(input => {
            totalPaid += parseFloat(input.value) || 0;
        });
    
        const netProfit = totalPaid - totalExpenses;
    
        document.getElementById('totalExpensesDisplayBelow').textContent = `$${totalExpenses.toFixed(2)}`;
        document.getElementById('totalPaidDisplayBelow').textContent = `$${totalPaid.toFixed(2)}`;
    
        const netEl = document.getElementById('netProfitDisplayBelow');
        netEl.textContent = `$${netProfit.toFixed(2)}`;
        netEl.className = 'h5 fw-bold ' + (netProfit >= 0 ? 'text-success' : 'text-danger');
    
        renderExpensesChart(totalExpenses, netProfit);
    };
    
    document.addEventListener('input', function (e) {
        if (
            e.target.closest('#expensesTable') ||
            e.target.classList.contains('aporte-value') ||
            e.target.id === 'contractValue'
        ) {
            updateExpenseSummary();
        }
    });
    
    document.addEventListener('DOMContentLoaded', () => {
        updateExpenseSummary(); // al cargar
    });
</script>

<!-- Script Eliminar documento-->
<script>
    function deleteDocument(filePath, fileType) {
        if (confirm('¿Estás seguro de que deseas eliminar este archivo?')) {
            document.getElementById('deleteFileType').value = fileType;
            document.getElementById('deleteFilePath').value = filePath;
            document.getElementById('deleteDocumentForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'))
        toastElList.map(function (toastEl) {
            new bootstrap.Toast(toastEl).show()
        })
    });
</script>

{{-- Imagenes--}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let leadId = document.querySelector('input[name="lead_id"]').value;

        // Manejo de subida de imágenes
        document.getElementById("uploadForm").addEventListener("submit", function (event) {
            event.preventDefault();
            
            let formData = new FormData(this);

            fetch("{{ route('lead.images.store') }}", {
                method: "POST",
                body: formData,
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Imagen subida correctamente.");
                    location.reload(); // Recargar la galería
                } else {
                    alert("Error al subir la imagen.");
                }
            })
            .catch(error => console.error("Error enviando imagen:", error));
        });
    });

    // modal view
    function showPreview(imageSrc) {
            if (!imageSrc) return;
            const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            document.getElementById("previewImage").src = imageSrc;
            modal.show();
        }

    // Eliminar imagen con AJAX
    function deleteImage(imageId) {
        if (!confirm("¿Estás seguro de que quieres eliminar esta imagen?")) return;

        fetch(`/lead-images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`image-${imageId}`).remove();
                alert("Imagen eliminada correctamente.");
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error("Error al eliminar imagen:", error));
    }
</script>
    
    

    
    
    

<style>
    .balance-chart {
        width: 70px !important;
        height: 70px !important;
    }
    #chartPercentageText {
        font-size: 0.9rem;
        font-weight: bold;
        color: #dc3545; /* default red */
    }
</style>

<script>
    function changeStatus(newStatus) {
        if (confirm('Are you sure you want to change the status?')) {
            document.getElementById('selectedStatus').value = newStatus;
            document.getElementById('statusForm').submit();
        }
    }
</script>

<style>
    .status-box {
        padding: 8px 16px;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        min-width: 100px;
        text-align: center;
        transition: all 0.3s ease-in-out;
    }
    .bg-orange {
        background-color: #f79646 !important;
    }
    .status-active {
        border: 3px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        transform: scale(1.05);
        z-index: 1;
    }
    .status-inactive {
        opacity: 0.5;
    }
</style>

<style>
    body { background: #2270be; }
    .card { border-radius: 10px; background: white; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }

    /* Estilo para la galería */
    .gallery-item {
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .gallery-item:hover {
        transform: scale(1.05);
    }

    /* Asegurar que la imagen en el modal se centre y se ajuste */
    #previewImage {
        max-width: 100%;
        max-height: 90vh;
        display: block;
        margin: auto;
    }
</style>

@endsection
