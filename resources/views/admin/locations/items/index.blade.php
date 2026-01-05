@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- HEADER COMPACTO --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
            <div class="mb-3 sm:mb-0">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('superadmin.locations.index') }}"
                       class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $location->state }} Items</h1>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span>{{ $location->user->company_name }}</span>
                            <span>•</span>
                            <span>{{ $items->count() }} items</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.locations.items.create', $location) }}"
                   class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 
                          text-white text-sm font-medium rounded hover:bg-green-700">
                    <i class="fas fa-plus text-xs"></i>
                    New Item
                </a>
            </div>
        </div>
    </div>

    {{-- TABLA COMPACTA --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        {{-- ENCABEZADO CON BÚSQUEDA --}}
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="text-sm text-gray-600">
                    Items and prices specific to {{ $location->state }}
                </div>
                
                @if($items->count() > 0)
                <div class="relative w-full sm:w-48">
                    <input type="text" 
                           id="searchItems"
                           placeholder="Search items..."
                           class="w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded 
                                  focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm">
                    <i class="fas fa-search absolute left-2.5 top-2 text-gray-400 text-xs"></i>
                </div>
                @endif
            </div>
        </div>

        {{-- TABLA --}}
        @if($items->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs">
                            <th class="px-4 py-2.5 text-left font-medium text-gray-700">Name</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-700">Description</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-700">Price</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-700">Status</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTable" class="divide-y divide-gray-100">
                        @foreach($items as $item)
                        <tr class="item-row hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                            </td>
                            
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-600 max-w-xs truncate">
                                    {{ $item->description ?: '—' }}
                                </div>
                            </td>
                            
                            <td class="px-4 py-3">
                                <div class="text-sm font-bold text-green-700">
                                    ${{ number_format($item->price, 2) }}
                                </div>
                            </td>
                            
                            <td class="px-4 py-3">
                                @if($item->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 
                                                 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 
                                                 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    {{-- EDIT --}}
                                    <a href="{{ route('superadmin.locations.items.edit', [$location, $item]) }}"
                                       class="inline-flex items-center gap-1 px-2 py-1 
                                              bg-blue-50 text-blue-700 rounded text-xs font-medium
                                              hover:bg-blue-100">
                                        <i class="fas fa-edit text-xs"></i>
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <form method="POST"
                                          action="{{ route('superadmin.locations.items.destroy', [$location, $item]) }}"
                                          class="inline"
                                          onsubmit="return confirm('Delete item: {{ addslashes($item->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2 py-1 
                                                       bg-red-50 text-red-700 rounded text-xs font-medium
                                                       hover:bg-red-100">
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
            {{-- EMPTY STATE COMPACTO --}}
            <div class="px-6 py-8 text-center">
                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center 
                            bg-gray-100 rounded-full">
                    <i class="fas fa-box text-gray-400"></i>
                </div>
                <h4 class="text-base font-semibold text-gray-900 mb-2">No Items Yet</h4>
                <p class="text-sm text-gray-600 mb-4">
                    Start by adding the first item for {{ $location->state }}
                </p>
                <a href="{{ route('superadmin.locations.items.create', $location) }}"
                   class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 
                          text-white text-sm font-medium rounded hover:bg-green-700">
                    <i class="fas fa-plus"></i>
                    Create First Item
                </a>
            </div>
        @endif
    </div>

    {{-- FOOTER COMPACTO --}}
    @if($items->count() > 0)
    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
        <div>
            Showing {{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }}
        </div>
        <div class="flex items-center gap-1">
            <i class="fas fa-info-circle text-xs"></i>
            <span>Prices are specific to {{ $location->state }}</span>
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
    
    // Atajo Ctrl+F para búsqueda
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            const searchInput = document.getElementById('searchItems');
            if (searchInput) {
                searchInput.focus();
            }
        }
    });
});
</script>

<style>
/* Estilos compactos para tabla */
.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

/* Padding compacto */
.px-4\.py-2\.5 {
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: 0.625rem;
    padding-bottom: 0.625rem;
}

.px-4\.py-3 {
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

/* Botones compactos */
.px-2\.py-1 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

/* Truncar descripción larga */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Ancho máximo para descripción */
.max-w-xs {
    max-width: 20rem;
}

/* Badges compactos */
.inline-flex.items-center.gap-1.px-2\.py-1 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-top: 0.125rem;
    padding-bottom: 0.125rem;
}

/* Divider sutil */
.divide-y > :not([hidden]) ~ :not([hidden]) {
    border-top-width: 1px;
    border-color: #f3f4f6;
}

/* Hover en filas */
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}
</style>
@endsection