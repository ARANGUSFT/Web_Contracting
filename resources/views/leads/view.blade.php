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

    
    </div>



    

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
            <a class="nav-link" data-bs-toggle="tab" href="#invoice">Financial Worksheet</a>
        </li>
    </ul>

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
                        ['title' => 'Roof Report', 'type' => 'contratos'],
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


        <!-- Financial Worksheet -->
        <div class="tab-pane fade show" id="invoice">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4"><i class="bi bi-receipt me-2"></i> Financial Worksheet</h4>

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

                        <!-- Contributions -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Contributions</label>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-nowrap">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="min-width: 120px;">Date</th>
                                            <th style="min-width: 100px;">Amount</th>
                                            <th style="min-width: 140px;">Method</th>
                                            <th style="min-width: 140px;">Check #</th>
                                            <th style="min-width: 200px;">Notes</th>
                                            <th style="min-width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="aportTable">
                                        @foreach($lead->finanzas ?? [] as $index => $aporte)
                                            <tr>
                                                <td>
                                                    <input type="date" name="finanzas[{{ $index }}][date]"
                                                        class="form-control @error("finanzas.$index.date") is-invalid @enderror"
                                                        value="{{ old("finanzas.$index.date", $aporte['date']) }}">
                                                    @error("finanzas.$index.date")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="finanzas[{{ $index }}][amount]"
                                                        class="form-control aporte-value @error("finanzas.$index.amount") is-invalid @enderror"
                                                        value="{{ old("finanzas.$index.amount", $aporte['amount']) }}">
                                                    @error("finanzas.$index.amount")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select name="finanzas[{{ $index }}][method]"
                                                        class="form-select @error("finanzas.$index.method") is-invalid @enderror">
                                                        <option value="">Select</option>
                                                        <option value="Cash" {{ old("finanzas.$index.method", $aporte['method']) === 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                                                        <option value="Check" {{ old("finanzas.$index.method", $aporte['method']) === 'Check' ? 'selected' : '' }}>🧾 Check</option>
                                                        <option value="Transfer" {{ old("finanzas.$index.method", $aporte['method']) === 'Transfer' ? 'selected' : '' }}>💳 Transfer</option>
                                                    </select>
                                                    @error("finanzas.$index.method")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" name="finanzas[{{ $index }}][check_number]"
                                                        class="form-control @error("finanzas.$index.check_number") is-invalid @enderror"
                                                        value="{{ old("finanzas.$index.check_number", $aporte['check_number'] ?? '') }}">
                                                    @error("finanzas.$index.check_number")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <textarea name="finanzas[{{ $index }}][notes]"
                                                        rows="1"
                                                        class="form-control form-control-sm @error("finanzas.$index.notes") is-invalid @enderror"
                                                        style="resize: vertical;"
                                                        placeholder="Add notes...">{{ old("finanzas.$index.notes", $aporte['notes']) }}</textarea>
                                                    @error("finanzas.$index.notes")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td class="text-center">
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
