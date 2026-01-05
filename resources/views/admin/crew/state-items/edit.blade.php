@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6">

    {{-- HEADER MEJORADO --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('superadmin.crews.states.items.index', [$crew->id, $state]) }}"
                       class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Item</h1>
                </div>
                
                <div class="flex items-center gap-3 text-sm">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full font-medium">
                        {{ $crew->name }}
                    </span>
                    <span class="text-gray-500">›</span>
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full font-medium">
                        {{ $state }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 sm:mt-0">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-clock me-1"></i>
                    Last updated: {{ $item->updated_at->format('M d, Y') }}
                </div>
            </div>
        </div>
        
        <p class="text-gray-600">
            Update the item details and pricing information below
        </p>
    </div>

    {{-- FORM CARD MEJORADA --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        {{-- CARD HEADER --}}
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-edit text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Item Details</h3>
                    <p class="text-sm text-gray-500">Item ID: #{{ $item->id }}</p>
                </div>
            </div>
        </div>

        {{-- FORM BODY --}}
        <div class="p-6">
            <form method="POST"
                  action="{{ route('superadmin.crews.states.items.update', [$crew->id, $state, $item->id]) }}"
                  class="space-y-8">
                @csrf
                @method('PUT')

                {{-- NAME FIELD --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-gray-900">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs text-gray-400">Required</span>
                    </div>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $item->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  placeholder:text-gray-400"
                           placeholder="Enter item name (e.g., Tear Off, Installation)"
                           required>
                    @if($errors->has('name'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                {{-- PRICE FIELD --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-gray-900">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs text-gray-400">Required</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">$</span>
                        <input type="number"
                               name="price"
                               step="0.01"
                               min="0"
                               value="{{ old('price', number_format($item->price, 2)) }}"
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      placeholder:text-gray-400"
                               placeholder="0.00"
                               required>
                    </div>
                    @if($errors->has('price'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('price') }}</p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Enter the price in USD. Use decimal points for cents.
                    </p>
                </div>

                {{-- DESCRIPTION FIELD --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-gray-900">Description</label>
                        <span class="text-xs text-gray-400">Optional</span>
                    </div>
                    <textarea name="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                     focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                     placeholder:text-gray-400"
                              placeholder="Add a detailed description for this item (optional)">
{{ old('description', $item->description) }}</textarea>
                    @if($errors->has('description'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('description') }}</p>
                    @endif
                    <p class="mt-1 text-xs text-gray-500">
                        Provide additional details about this item or service.
                    </p>
                </div>

                {{-- FORM FOOTER --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>All changes are saved immediately</span>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('superadmin.crews.states.items.index', [$crew->id, $state]) }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 
                                      border border-gray-300 text-gray-700 font-medium rounded-lg
                                      hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                            
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 
                                           bg-blue-600 text-white font-medium rounded-lg
                                           hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- CURRENT DATA CARD (solo información) --}}
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-5">
        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle text-blue-600"></i>
            Current Item Information
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Created</p>
                <p class="font-medium">{{ $item->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Last Updated</p>
                <p class="font-medium">{{ $item->updated_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>
    </div>

</div>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const priceInput = form.querySelector('input[name="price"]');
    
    form.addEventListener('submit', function(e) {
        // Validate price
        if (priceInput && parseFloat(priceInput.value) < 0) {
            e.preventDefault();
            alert('Price cannot be negative');
            priceInput.focus();
            return false;
        }
        
        // Validate name
        const nameInput = form.querySelector('input[name="name"]');
        if (nameInput && nameInput.value.trim().length < 2) {
            e.preventDefault();
            alert('Item name must be at least 2 characters long');
            nameInput.focus();
            return false;
        }
        
        return true;
    });
    
    // Auto-format price input
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value) && value >= 0) {
                this.value = value.toFixed(2);
            }
        });
    }
});
</script>

<style>
/* Estilos personalizados */
input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Animaciones suaves */
button, a {
    transition: all 0.2s ease;
}

/* Mejor contraste para labels */
.text-sm.font-semibold {
    color: #111827;
}

/* Estilos para badges */
.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-gray-100 {
    background-color: #f3f4f6;
}

/* Espaciado mejorado */
.space-y-8 > * + * {
    margin-top: 2rem;
}

/* Focus states */
.focus\:ring-2:focus {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}
</style>
@endsection