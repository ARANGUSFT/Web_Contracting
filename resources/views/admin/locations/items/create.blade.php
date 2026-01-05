@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-2xl mx-auto px-4">

    {{-- HEADER COMPACTO --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('superadmin.locations.items.index', $location) }}"
               class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Create Item</h1>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>{{ $location->user->company_name }}</span>
                    <span>•</span>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs">
                        {{ $location->state }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- FORMULARIO COMPACTO --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
        <form method="POST"
              action="{{ route('superadmin.locations.items.store', $location) }}"
              class="space-y-4">
            @csrf

            {{-- NAME FIELD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Item Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       class="w-full px-3 py-2 border border-gray-300 rounded 
                              focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                       placeholder="e.g., Asphalt Shingle R&R"
                       required>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- PRICE FIELD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Price <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500 text-sm">$</span>
                    <input type="number"
                           name="price"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded 
                                  focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                           placeholder="0.00"
                           required>
                </div>
                @error('price')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- DESCRIPTION FIELD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea name="description"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded 
                                 focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                          placeholder="Optional description"></textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ACTIVE CHECKBOX --}}
            <div class="pt-3 border-t">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="text-sm font-medium text-gray-900">Active</span>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Item will be available for selection
                        </p>
                    </div>
                </label>
            </div>

            {{-- ACTIONS COMPACTAS --}}
            <div class="pt-4 border-t mt-6">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('superadmin.locations.items.index', $location) }}"
                       class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium 
                              rounded hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium 
                                   rounded hover:bg-green-700 flex items-center gap-1">
                        <i class="fas fa-plus text-xs"></i>
                        Create Item
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- INFO CARD COMPACTA --}}
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-2 text-sm">
            <i class="fas fa-lightbulb text-blue-600 mt-0.5"></i>
            <div>
                <p class="font-medium text-blue-900 mb-1">Quick Tips</p>
                <ul class="text-blue-800 text-xs space-y-1 list-disc list-inside">
                    <li>Use clear, descriptive item names</li>
                    <li>Prices can be updated later</li>
                    <li>Inactive items won't show in lists</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const priceInput = form.querySelector('input[name="price"]');
    
    // Auto-formato de precio
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value) && value >= 0) {
                this.value = value.toFixed(2);
            }
        });
    }
    
    // Validación al enviar
    form.addEventListener('submit', function(e) {
        // Validar precio
        if (priceInput && parseFloat(priceInput.value) < 0) {
            e.preventDefault();
            alert('Price cannot be negative');
            priceInput.focus();
            return false;
        }
        
        // Validar nombre
        const nameInput = form.querySelector('input[name="name"]');
        if (nameInput && nameInput.value.trim().length < 2) {
            e.preventDefault();
            alert('Item name must be at least 2 characters long');
            nameInput.focus();
            return false;
        }
        
        return true;
    });
});
</script>

<style>
/* Estilos compactos */
.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

/* Inputs compactos */
.px-3\.py-2 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

/* Botones compactos */
.px-4\.py-2 {
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

/* Checkbox más grande */
input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
}

/* Badge de estado */
.bg-blue-50 {
    background-color: #eff6ff;
}

/* Info card colors */
.bg-blue-50 {
    background-color: #eff6ff;
}

.border-blue-200 {
    border-color: #bfdbfe;
}

.text-blue-900 {
    color: #1e3a8a;
}

.text-blue-800 {
    color: #1e40af;
}

/* List styling */
.list-disc.list-inside {
    list-style-position: inside;
}

.space-y-1 > * + * {
    margin-top: 0.25rem;
}

/* Focus states */
.focus\:ring-1:focus {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}

/* Espaciado reducido */
.space-y-4 > * + * {
    margin-top: 1rem;
}
</style>
@endsection