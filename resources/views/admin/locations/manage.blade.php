@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    {{-- HEADER COMPACTO --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Location Management</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Managing locations for <span class="font-medium text-blue-600">{{ $company->company_name }}</span>
                </p>
            </div>
            <button onclick="openAddState()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                           text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus text-xs"></i>
                Add State
            </button>
        </div>
    </div>

    {{-- ESTADOS EN GRID --}}
    @if($locations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($locations as $state => $items)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-sm transition-shadow">
                    {{-- ENCABEZADO DEL ESTADO --}}
                    <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                <i class="fas fa-flag text-blue-600 text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $state }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">
                                {{ $items->count() }} {{ $items->count() === 1 ? 'item' : 'items' }}
                            </span>
                            <button onclick="openAddCity('{{ $state }}')"
                                    class="text-xs text-blue-600 hover:text-blue-800 p-1">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>

                    {{-- CONTENIDO COMPACTO --}}
                    <div class="p-3 space-y-2">
                        {{-- PRECIO BASE --}}
                        @if($items->where('city', null)->count() > 0)
                            @foreach($items->where('city', null) as $location)
                                <div class="flex items-center justify-between px-3 py-2 bg-blue-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-layer-group text-blue-500 text-xs"></i>
                                        <span class="text-xs text-gray-700">Base Price</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('superadmin.locations.prices.index', $location) }}"
                                           class="text-xs text-blue-600 hover:text-blue-800 p-1">
                                            <i class="fas fa-dollar-sign"></i>
                                        </a>
                                        <a href="{{ route('superadmin.locations.edit', $location) }}"
                                           class="text-xs text-gray-600 hover:text-gray-800 p-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-2">
                                <span class="text-xs text-gray-500">No base price set</span>
                            </div>
                        @endif

                        {{-- CIUDADES --}}
                        <div class="pt-2 border-t border-gray-100">
                            <div class="space-y-1">
                                @foreach($items->whereNotNull('city')->take(3) as $location)
                                    <div class="flex items-center justify-between px-2 py-1.5 hover:bg-gray-50 rounded">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-city text-green-500 text-xs"></i>
                                            <span class="text-xs text-gray-700 truncate max-w-[120px]">
                                                {{ $location->city }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('superadmin.locations.prices.index', $location) }}"
                                               class="text-xs text-green-600 hover:text-green-800 p-1">
                                                <i class="fas fa-dollar-sign"></i>
                                            </a>
                                            <a href="{{ route('superadmin.locations.edit', $location) }}"
                                               class="text-xs text-gray-600 hover:text-gray-800 p-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                                @if($items->whereNotNull('city')->count() > 3)
                                    <div class="text-center pt-1">
                                        <span class="text-xs text-gray-500">
                                            +{{ $items->whereNotNull('city')->count() - 3 }} more
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- ESTADO VACÍO COMPACTO --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-map-marker-alt text-gray-400 text-lg"></i>
            </div>
            <h3 class="text-sm font-semibold text-gray-700 mb-1">No Locations Yet</h3>
            <p class="text-xs text-gray-500 mb-4">Start by adding your first state</p>
            <button onclick="openAddState()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus"></i>
                Add First State
            </button>
        </div>
    @endif

    {{-- ESTADÍSTICAS --}}
    @if($locations->count() > 0)
        @php
            $totalStates = $locations->count();
            $totalBasePrices = 0;
            $totalCities = 0;
            
            foreach($locations as $items) {
                $totalBasePrices += $items->where('city', null)->count();
                $totalCities += $items->whereNotNull('city')->count();
            }
        @endphp

        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-flag text-gray-400"></i>
                    <span>{{ $totalStates }} {{ $totalStates === 1 ? 'state' : 'states' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-layer-group text-gray-400"></i>
                    <span>{{ $totalBasePrices }} base {{ $totalBasePrices === 1 ? 'price' : 'prices' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-city text-gray-400"></i>
                    <span>{{ $totalCities }} city {{ $totalCities === 1 ? 'override' : 'overrides' }}</span>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- ================= MODALES COMPACTOS ================= --}}

{{-- MODAL PARA AGREGAR ESTADO --}}
<div id="addStateModal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-sm transform transition-all duration-200 scale-95 opacity-0" 
         id="stateModalContent">
        <div class="p-4 border-b">
            <h3 class="text-sm font-semibold text-gray-900">Add New State</h3>
            <p class="text-xs text-gray-500 mt-1">Add a state to establish base pricing</p>
        </div>

        <form method="POST"
              action="{{ route('superadmin.companies.locations.store', $company) }}">
            @csrf
            <div class="p-4">
                <input type="hidden" name="locations[0][city]" value="">

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        State Code <span class="text-red-500">*</span>
                    </label>
                    <input name="locations[0][state]"
                           required maxlength="5"
                           placeholder="CA, NY, TX"
                           class="w-full border border-gray-300 rounded px-3 py-2 
                                  text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 
                                  focus:border-blue-500 uppercase"
                           oninput="this.value = this.value.toUpperCase()">
                    <p class="mt-1 text-xs text-gray-500">
                        2-letter state code (uppercase)
                    </p>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 rounded-b-lg flex justify-end gap-2">
                <button type="button" onclick="closeModals()"
                        class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm 
                               rounded hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-3 py-1.5 bg-blue-600 text-white text-sm 
                               rounded hover:bg-blue-700 transition-colors">
                    Save State
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PARA AGREGAR CIUDAD --}}
<div id="addCityModal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-sm transform transition-all duration-200 scale-95 opacity-0" 
         id="cityModalContent">
        <div class="p-4 border-b">
            <h3 class="text-sm font-semibold text-gray-900">Add City Override</h3>
            <p class="text-xs text-gray-500 mt-1" id="cityStateLabel">
                Add a city-specific price override
            </p>
        </div>

        <form method="POST"
              action="{{ route('superadmin.companies.locations.store', $company) }}">
            @csrf
            <div class="p-4 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        State
                    </label>
                    <input type="text" id="cityStateReadonly"
                           readonly
                           class="w-full border border-gray-300 rounded px-3 py-2 
                                  text-sm bg-gray-50 text-gray-700">
                    <input type="hidden" id="cityStateInput" name="locations[0][state]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        City Name <span class="text-red-500">*</span>
                    </label>
                    <input name="locations[0][city]"
                           required
                           placeholder="Los Angeles, New York, Houston"
                           class="w-full border border-gray-300 rounded px-3 py-2 
                                  text-sm focus:outline-none focus:ring-1 focus:ring-green-500 
                                  focus:border-green-500">
                    <p class="mt-1 text-xs text-gray-500">
                        City-specific prices will override state base prices
                    </p>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 rounded-b-lg flex justify-end gap-2">
                <button type="button" onclick="closeModals()"
                        class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm 
                               rounded hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-3 py-1.5 bg-green-600 text-white text-sm 
                               rounded hover:bg-green-700 transition-colors">
                    Add City
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
function openAddState() {
    const modal = document.getElementById('addStateModal');
    const content = document.getElementById('stateModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function openAddCity(state) {
    const modal = document.getElementById('addCityModal');
    const content = document.getElementById('cityModalContent');
    
    modal.classList.remove('hidden');
    document.getElementById('cityStateLabel').textContent = `Add city in ${state}`;
    document.getElementById('cityStateInput').value = state;
    document.getElementById('cityStateReadonly').value = state;
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModals() {
    const stateContent = document.getElementById('stateModalContent');
    const cityContent = document.getElementById('cityModalContent');
    
    stateContent.classList.remove('scale-100', 'opacity-100');
    stateContent.classList.add('scale-95', 'opacity-0');
    
    cityContent.classList.remove('scale-100', 'opacity-100');
    cityContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        document.getElementById('addStateModal').classList.add('hidden');
        document.getElementById('addCityModal').classList.add('hidden');
    }, 200);
}

// Cerrar modal al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    const modals = ['addStateModal', 'addCityModal'];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModals();
                }
            });
        }
    });
});
</script>

<style>
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.max-w-\[120px\] {
    max-width: 120px;
}

.transform {
    transition-property: transform, opacity;
}

.duration-200 {
    transition-duration: 200ms;
}
</style>
@endsection