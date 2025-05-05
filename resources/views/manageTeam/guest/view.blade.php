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

            <a href="{{ route('guest.dashboard') }}" class="btn btn-secondary">
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
        
        


        <form id="statusForm" action="{{ route('manager.assignstatus', $lead->id) }}" method="POST" class="mb-3">
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
                            $senderName = $isSeller ? $msg->team->name : ($msg->user->company_name ?? 'Usuario');
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
    document.addEventListener('DOMContentLoaded', function () {
    
        const updateBalance = () => {
            const contractValue = parseFloat(document.getElementById('contractValue')?.value) || 0;
            let total = 0;
    
            document.querySelectorAll('.aporte-value').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
    
            document.getElementById('balanceDisplay').textContent = `$${total.toFixed(2)}`;
        };
    
        const toggleCheckNumber = (select) => {
            const tr = select.closest('tr');
            const checkInput = tr.querySelector('.check-number-input');
            if (!checkInput) return;
    
            if (select.value === 'Check') {
                checkInput.removeAttribute('disabled');
            } else {
                checkInput.value = '';
                checkInput.setAttribute('disabled', true);
            }
        };
    
        const bindEventsToRow = (row) => {
            row.querySelector('.aporte-value')?.addEventListener('input', updateBalance);
            const methodSelect = row.querySelector('.method-select');
            if (methodSelect) {
                toggleCheckNumber(methodSelect);
                methodSelect.addEventListener('change', () => toggleCheckNumber(methodSelect));
            }
        };
    
        const addContributionRow = () => {
            const tableBody = document.querySelector('#aportTable');
            const rowIndex = tableBody.querySelectorAll('tr').length;
    
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="date" name="finanzas[${rowIndex}][date]" class="form-control" required /></td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="finanzas[${rowIndex}][amount]" class="form-control aporte-value" required />
                    </div>
                </td>
                <td>
                    <select name="finanzas[${rowIndex}][method]" class="form-select method-select">
                        <option value="">Select</option>
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </td>
                <td><input type="text" name="finanzas[${rowIndex}][check_number]" class="form-control check-number-input" disabled /></td>
                <td><input type="text" name="finanzas[${rowIndex}][notes]" class="form-control" /></td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
    
            tableBody.appendChild(newRow);
            bindEventsToRow(newRow);
            updateBalance();
        };
    
        // Inicialización
        updateBalance();
        document.getElementById('contractValue')?.addEventListener('input', updateBalance);
    
        document.querySelectorAll('#aportTable tr').forEach(row => bindEventsToRow(row));
    
        document.getElementById('addRow')?.addEventListener('click', addContributionRow);
    
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr')?.remove();
                updateBalance();
            }
        });
    
    });

 
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('aportTable');

        // Delegación de evento para remover filas
        tableBody.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('tr');
                row.remove();
                updateBalance(); // Si estás usando el balance, actualízalo
            }
        });
    });


</script>
    

{{-- Expenses --}}
<script>
        document.addEventListener('DOMContentLoaded', function () {

            // Toggle amount field visibility
            function toggleAmountField() {
                const select = document.querySelector('select[name="expenses[0][type]"]');
                const input = document.querySelector('input[name="expenses[0][amount]"]');
                const percentLabel = document.querySelector('.commission-label');
                if (!select || !input) return;

                if (select.value === '') {
                    input.classList.add('d-none');
                    input.value = '';
                    percentLabel?.classList.add('d-none');
                } else {
                    input.classList.remove('d-none');
                    if (select.value === 'commission') {
                        percentLabel?.classList.remove('d-none');
                        input.placeholder = "%";
                    } else {
                        percentLabel?.classList.add('d-none');
                        input.placeholder = "$";
                    }
                }
            }

            // Bind toggle to each selector
            document.querySelectorAll('.expense-type').forEach(function (select) {
                select.addEventListener('change', function () {
                    toggleAmountField(this);
                });
                toggleAmountField(select); // initialize
            });

            // SweetAlert delete confirmation
            document.querySelectorAll('.delete-expense-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Delete expense?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#d33',
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
                                    form.closest('tr').remove();
                                    Swal.fire('Deleted!', 'Expense has been removed.', 'success');
                                } else {
                                    Swal.fire('Error!', 'Could not delete expense.', 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error!', 'Network error.', 'error');
                            });
                        }
                    });
                });
            });

        });
</script>

{{-- Quote --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-quote-form-${id}`).submit();
            }
        });
    }
</script>







{{-- Total expenses / paid / nex profit --}}
<script>
   
        const updateExpenseSummary = () => {
        const contractValue = parseFloat(document.getElementById('contractValue')?.value) || 0;
        const baseExpenses = parseFloat('{{ $lead->total_expenses }}') || 0;
        const basePaid = parseFloat('{{ $lead->total_paid }}') || 0;

        let dynamicExpenses = 0;

        document.querySelectorAll('form[action*="lead-expenses"] tbody tr').forEach(row => {
            const type = row.querySelector('.expense-type')?.value;
            if (!type) return;

            const input = row.querySelector('input[name*="[amount]"]');
            if (!input || input.classList.contains('d-none')) return;

            const val = parseFloat(input.value) || 0;
            if (type === 'commission') {
                dynamicExpenses += contractValue * (val / 100);
            } else {
                dynamicExpenses += val;
            }
        });

        let dynamicPaid = 0;
        document.querySelectorAll('.aporte-value:not([data-existing="1"])').forEach(input => {
            dynamicPaid += parseFloat(input.value) || 0;
        });


        const totalExpenses = baseExpenses + dynamicExpenses;
        const totalPaid = basePaid + dynamicPaid;
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
                e.target.closest('form[action*="lead-expenses"]') ||
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
    const currentStatus = document.getElementById('selectedStatus').value;

    if (currentStatus == newStatus) {
        alert('You are already on this status.');
        return;
    }

    Swal.fire({
        title: 'Change Status',
        text: 'Are you sure you want to change the status?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('selectedStatus').value = newStatus;
            document.getElementById('statusForm').submit();
        }
    });
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
