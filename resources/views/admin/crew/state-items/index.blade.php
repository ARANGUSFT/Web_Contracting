@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6">

    {{-- HEADER MEJORADO --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $crew->name }}</h1>
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                        {{ $state }}
                    </span>
                </div>
                <p class="text-gray-600">Manage items and prices for this state</p>
            </div>
            
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('superadmin.crews.states', $crew->id) }}"
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium">
                    <i class="fas fa-arrow-left"></i>
                    Back to states
                </a>
            </div>
        </div>
        
        {{-- STATS BAR --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Total Items</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $items->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE ITEM CARD MEJORADA --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus text-green-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Add New Item</h3>
                <p class="text-sm text-gray-500">Fill the form below to create a new item</p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('superadmin.crews.states.items.store', [$crew->id, $state]) }}"
              class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- NAME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Item Name <span class="text-red-500">*</span>
                    </label>
                    <input name="name"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  placeholder:text-gray-400"
                           placeholder="e.g., Tear Off, Installation, Repair"
                           required>
                </div>

                {{-- PRICE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">$</span>
                        <input name="price"
                               type="number"
                               step="0.01"
                               min="0"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      placeholder:text-gray-400"
                               placeholder="0.00"
                               required>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                     focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                     placeholder:text-gray-400"
                              rows="3"
                              placeholder="Add a detailed description for this item..."></textarea>
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 
                               text-white font-medium rounded-lg hover:bg-green-700 
                               transition-colors">
                    <i class="fas fa-plus"></i>
                    Add New Item
                </button>
            </div>
        </form>
    </div>

    {{-- ITEMS TABLE MEJORADA --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        {{-- TABLE HEADER --}}
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Registered Items</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }} total
                    </p>
                </div>
                
                @if($items->count())
                <div class="mt-3 sm:mt-0">
                    <input type="text" 
                           id="searchItems"
                           placeholder="Search items..."
                           class="px-4 py-2 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  text-sm w-full sm:w-64">
                </div>
                @endif
            </div>
        </div>

        {{-- TABLE CONTENT --}}
        @if($items->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Item Details</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Price</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTable">
                        @foreach($items as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors item-row">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-box text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $item->name }}</h4>
                                        @if($item->description)
                                            <p class="text-sm text-gray-500 mt-1">{{ $item->description }}</p>
                                        @endif
                                        <div class="mt-1 text-xs text-gray-400">
                                            ID: {{ $item->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-lg font-bold text-green-700">
                                    ${{ number_format($item->price, 2) }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- EDIT --}}
                                    <a href="{{ route('superadmin.crews.states.items.edit', [$crew->id, $state, $item->id]) }}"
                                       class="inline-flex items-center gap-2 px-3 py-1.5 
                                              bg-blue-50 text-blue-700 rounded-lg text-sm font-medium
                                              hover:bg-blue-100 transition-colors"
                                       title="Edit item">
                                        <i class="fas fa-edit text-xs"></i>
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <form method="POST"
                                          action="{{ route('superadmin.crew-state-items.destroy', $item->id) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this item?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 px-3 py-1.5 
                                                       bg-red-50 text-red-700 rounded-lg text-sm font-medium
                                                       hover:bg-red-100 transition-colors"
                                                title="Delete item">
                                            <i class="fas fa-trash text-xs"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="p-10 text-center">
                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center 
                            bg-gray-100 rounded-full">
                    <i class="fas fa-box text-gray-400 text-xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">No Items Found</h4>
                <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                    Start by adding your first item using the form above.
                </p>
            </div>
        @endif
    </div>

    {{-- FOOTER NAVIGATION --}}
    @if($items->count())
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div>
                Showing {{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }}
            </div>
            <div class="flex items-center gap-1">
                <i class="fas fa-info-circle"></i>
                <span>Click Edit to modify items</span>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
// Búsqueda en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchItems');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

// Validación del formulario
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        const priceInput = form.querySelector('input[name="price"]');
        if (priceInput && parseFloat(priceInput.value) < 0) {
            e.preventDefault();
            alert('Price cannot be negative');
            priceInput.focus();
        }
    });
}
</script>

<style>
/* Estilos personalizados */
input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Animaciones suaves */
tr {
    transition: all 0.2s ease;
}

/* Estilo para precios */
.text-green-700 {
    color: #059669;
}

/* Badge styles */
.bg-blue-100 {
    background-color: #dbeafe;
}

.bg-green-100 {
    background-color: #d1fae5;
}

.bg-red-50 {
    background-color: #fef2f2;
}
</style>
@endsection