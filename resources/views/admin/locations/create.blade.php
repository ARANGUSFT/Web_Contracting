@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-2xl mx-auto px-4">

    {{-- HEADER COMPACTO --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('superadmin.locations.index') }}"
               class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Add Locations</h1>
                <p class="text-sm text-gray-600">Add multiple locations for a company</p>
            </div>
        </div>
    </div>

    {{-- FORMULARIO COMPACTO --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
        <form method="POST" 
              action="{{ route('superadmin.locations.store') }}"
              class="space-y-4">
            @csrf
            
            {{-- COMPANY SELECT --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Company <span class="text-red-500">*</span>
                </label>

                <select name="user_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded 
                            focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                        required>
                    <option value="">Select a company</option>

                    @foreach(\App\Models\User::where('is_admin', 0)->orderBy('company_name')->get() as $company)
                        <option value="{{ $company->id }}">
                            {{ $company->company_name }}
                            @if($company->email)
                                <span class="text-gray-400 text-xs">({{ $company->email }})</span>
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- STATES MULTIPLE SELECT --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-sm font-medium text-gray-700">
                        States <span class="text-red-500">*</span>
                    </label>
                    <button type="button" 
                            onclick="selectAllStates()"
                            class="text-xs text-blue-600 hover:text-blue-800">
                        Select all
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto p-2 border border-gray-300 rounded">
                    @php
                        $states = [
                            'AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA',
                            'HI','ID','IL','IN','IA','KS','KY','LA','ME','MD',
                            'MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ',
                            'NM','NY','NC','ND','OH','OK','OR','PA','RI','SC',
                            'SD','TN','TX','UT','VT','VA','WA','WV','WI','WY'
                        ];
                    @endphp

                    @foreach($states as $state)
                        <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                            <input type="checkbox" 
                                   name="states[]" 
                                   value="{{ $state }}"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm">{{ $state }}</span>
                        </label>
                    @endforeach
                </div>
                
                <div class="flex items-center justify-between mt-2">
                    <span id="selectedCount" class="text-xs text-gray-500">0 states selected</span>
                    <span class="text-xs text-gray-500">
                        Click to select multiple
                    </span>
                </div>
            </div>

            {{-- CITY FIELD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    City <span class="text-gray-500 text-xs">(optional, applies to all selected states)</span>
                </label>
                <input type="text" 
                       name="city" 
                       class="w-full px-3 py-2 border border-gray-300 rounded 
                              focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                       placeholder="e.g., Main Office">
            </div>

            {{-- ACTIONS COMPACTAS --}}
            <div class="pt-4 border-t mt-6">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('superadmin.locations.index') }}"
                       class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium 
                              rounded hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium 
                                   rounded hover:bg-green-700 flex items-center gap-1">
                        <i class="fas fa-plus text-xs"></i>
                        Save Locations
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- INFO CARD COMPACTA --}}
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-2 text-sm">
            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
            <div>
                <p class="font-medium text-blue-900 mb-1">How it works</p>
                <p class="text-blue-800 text-xs">
                    Selecting multiple states will create a separate location record for each state with the same city (if provided).
                </p>
            </div>
        </div>
    </div>

</div>

<script>
// Contador de estados seleccionados
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('input[name="states[]"]');
    const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
    document.getElementById('selectedCount').textContent = `${selected} states selected`;
    
    // Actualizar botón de submit si no hay selección
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        if (selected === 0) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

// Seleccionar todos los estados
function selectAllStates() {
    const checkboxes = document.querySelectorAll('input[name="states[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
    
    updateSelectedCount();
}

// Inicializar contador y eventos
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar contador
    updateSelectedCount();
    
    // Añadir eventos a los checkboxes
    const checkboxes = document.querySelectorAll('input[name="states[]"]');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
    
    // Validar formulario al enviar
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const companySelect = form.querySelector('select[name="user_id"]');
        const selectedStates = Array.from(checkboxes).filter(cb => cb.checked);
        
        if (!companySelect.value) {
            e.preventDefault();
            alert('Please select a company');
            companySelect.focus();
            return false;
        }
        
        if (selectedStates.length === 0) {
            e.preventDefault();
            alert('Please select at least one state');
            return false;
        }
        
        return true;
    });
});
</script>

<style>
/* Estilos compactos */
.max-h-48 {
    max-height: 12rem;
}

/* Scroll personalizado */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Checkboxes más grandes */
input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
}

/* Hover en checkboxes */
label:hover {
    background-color: #f9fafb;
}

/* Info card */
.bg-blue-50 {
    background-color: #eff6ff;
}

.border-blue-200 {
    border-color: #bfdbfe;
}

/* Botón deshabilitado */
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@endsection