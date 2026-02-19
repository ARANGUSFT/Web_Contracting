@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- HEADER CON NUEVOS COLORES --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-[#003366] rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Company Locations</h1>
                        <p class="text-sm text-gray-500 mt-1">State base prices with city overrides</p>
                    </div>
                </div>
            </div>
            {{-- BOTÓN CON COLOR ROJO #D70026 --}}
            <a href="{{ route('superadmin.locations.create') }}"
               class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium
                      text-white bg-[#D70026] rounded-lg 
                      hover:bg-[#b30020] transition-all shadow-sm hover:shadow-md">
                <i class="fas fa-plus text-xs"></i>
                Add Location
            </a>
        </div>
    </div>

    {{-- LISTA DE COMPAÑÍAS --}}
    @forelse($locations as $companyId => $states)
        @php
            $company = $states->first()->first()->user;
            $companyStatesCount = $states->count();
            $companyCitiesCount = 0;
            foreach($states as $locationsByState) {
                $companyCitiesCount += $locationsByState->whereNotNull('city')->count();
            }
            
            // Usamos los colores proporcionados con variaciones
            $colorIndex = $companyId % 3;
            $colors = [
                [
                    'primary' => 'bg-[#003366]',
                    'light' => 'bg-[#003366]/10',
                    'text' => 'text-[#003366]',
                    'border' => 'border-[#003366]/20',
                    'hover' => 'hover:bg-[#003366]/20'
                ],
                [
                    'primary' => 'bg-[#D70026]',
                    'light' => 'bg-[#D70026]/10',
                    'text' => 'text-[#D70026]',
                    'border' => 'border-[#D70026]/20',
                    'hover' => 'hover:bg-[#D70026]/20'
                ],
                [
                    'primary' => 'bg-[#003366]',
                    'light' => 'bg-[#003366]/5',
                    'text' => 'text-[#003366]',
                    'border' => 'border-[#003366]/15',
                    'hover' => 'hover:bg-[#003366]/10'
                ],
            ];
            $companyColor = $colors[$colorIndex];
            
            // Color de acento (combinación del azul y rojo)
            $accentColor = $colorIndex === 1 ? 'bg-[#003366]' : 'bg-[#D70026]';
        @endphp

        {{-- TARJETA DE COMPAÑÍA CON ESPACIADO MEJORADO --}}
        <div class="bg-white rounded-xl border border-gray-200 mb-10 overflow-hidden hover:shadow-lg transition-all duration-300 shadow-sm">
            {{-- ENCABEZADO DE COMPAÑÍA CON COLOR PRINCIPAL --}}
            <div class="px-6 py-5 {{ $companyColor['primary'] }} text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/30 shadow-md">
                                <span class="text-white font-bold text-lg">
                                    {{ strtoupper(substr($company->company_name ?? 'C', 0, 1)) }}
                                </span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white border-2 {{ $accentColor }} rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-bold">{{ $company->company_name ?? 'Unnamed Company' }}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-sm text-white/90 truncate max-w-xs">{{ $company->email ?? '' }}</span>
                                <span class="text-xs px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full font-medium">
                                    {{ $companyStatesCount }} states • {{ $companyCitiesCount }} cities
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button onclick="openAddState({{ $company->id }}, '{{ addslashes($company->company_name) }}')"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                                       text-white bg-white/20 backdrop-blur-sm rounded-lg 
                                       hover:bg-white/30 transition-all border border-white/30">
                            <i class="fas fa-plus text-xs"></i>
                            Add State
                        </button>
                    </div>
                </div>
            </div>

            {{-- SEPARADOR VISUAL --}}
            <div class="h-1 w-full {{ $companyColor['primary'] }} opacity-30"></div>

            {{-- LISTA DE ESTADOS EN GRID MEJORADA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-8">
                @foreach($states as $state => $locationsByState)
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-200 hover:border-gray-300 {{ $companyColor['border'] }}">
                        {{-- ENCABEZADO DEL ESTADO --}}
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $companyColor['light'] }} rounded-lg flex items-center justify-center {{ $companyColor['border'] }}">
                                    <i class="fas fa-flag {{ $companyColor['text'] }}"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-gray-900">{{ $state }}</span>
                                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $locationsByState->whereNotNull('city')->count() }} cities</span>
                                        <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $locationsByState->where('city', null)->count() }} base price</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1">
                                <button onclick="openAddCity('{{ $state }}', {{ $company->id }})"
                                        class="p-2 {{ $companyColor['text'] }} {{ $companyColor['hover'] }} rounded-lg transition-colors"
                                        title="Add city override">
                                    <i class="fas fa-plus-circle text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- LISTA MEJORADA DE UBICACIONES --}}
                        <div class="space-y-3">
                            @php
                                $baseLocation = $locationsByState->where('city', null)->first();
                                $cities = $locationsByState->whereNotNull('city')->take(3);
                                $moreCities = $locationsByState->whereNotNull('city')->count() - 3;
                            @endphp

                            {{-- UBICACIÓN BASE MEJORADA --}}
                            @if($baseLocation)
                                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-[#003366]/5 to-[#003366]/10 rounded-lg border border-[#003366]/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-[#003366]/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-layer-group text-[#003366] text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">Base Price</span>
                                            <p class="text-xs text-gray-500">Applies to all cities in {{ $state }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('superadmin.locations.prices.index', $baseLocation) }}"
                                           class="p-2 text-[#003366] hover:text-[#003366]/80 hover:bg-[#003366]/10 rounded-lg transition-colors"
                                           title="Manage prices">
                                            <i class="fas fa-dollar-sign text-sm"></i>
                                        </a>
                                        <a href="{{ route('superadmin.locations.edit', $baseLocation) }}"
                                           class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('superadmin.locations.destroy', $baseLocation) }}"
                                              onsubmit="return confirmDelete(event, 'base price')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-[#D70026] hover:text-[#D70026]/80 hover:bg-[#D70026]/10 rounded-lg transition-colors"
                                                    title="Delete base price">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 bg-gradient-to-r from-[#D70026]/5 to-[#D70026]/10 rounded-lg border border-[#D70026]/20 text-center">
                                    <span class="text-sm text-[#D70026] font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        No base price set
                                    </span>
                                </div>
                            @endif

                            {{-- CIUDADES MEJORADAS --}}
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-medium text-gray-500">City Overrides</span>
                                    <span class="text-xs text-gray-400 font-medium">
                                        {{ $locationsByState->whereNotNull('city')->count() }} total
                                    </span>
                                </div>
                                
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                                    @foreach($cities as $location)
                                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-7 h-7 bg-[#D70026]/10 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-city text-[#D70026] text-xs"></i>
                                                </div>
                                                <span class="text-sm text-gray-700 truncate max-w-[150px]">{{ $location->city }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('superadmin.locations.prices.index', $location) }}"
                                                   class="p-1.5 text-[#D70026] hover:text-[#D70026]/80 hover:bg-[#D70026]/10 rounded-lg"
                                                   title="Manage prices">
                                                    <i class="fas fa-dollar-sign text-xs"></i>
                                                </a>
                                                <a href="{{ route('superadmin.locations.edit', $location) }}"
                                                   class="p-1.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg"
                                                   title="Edit">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                               <form method="POST"
                                                    action="{{ route('superadmin.locations.destroy', $location) }}"
                                                    onsubmit="return confirmDelete(event, 'city override')"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-1.5 text-[#D70026] hover:text-[#D70026]/80 hover:bg-[#D70026]/10 rounded-lg"
                                                            title="Delete">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($moreCities > 0)
                                        <div class="text-center pt-3 border-t border-gray-200">
                                            <span class="text-xs text-gray-500 font-medium">
                                                +{{ $moreCities }} more {{ $moreCities === 1 ? 'city' : 'cities' }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if($locationsByState->whereNotNull('city')->count() === 0)
                                        <div class="text-center py-4">
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-city mr-1"></i>
                                                No city overrides
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- PIE DE TARJETA CON ESPACIO ADICIONAL --}}
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200 text-center">
                <span class="text-xs text-gray-500 font-medium">Company ID: {{ $companyId }} • Last updated: {{ now()->format('M d, Y') }}</span>
            </div>
        </div>
    @empty
        {{-- ESTADO VACÍO MEJORADO --}}
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-300 p-16 text-center">
            <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-map-marker-alt text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">No locations configured</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Start by adding locations for your companies. Each company can have state-wide base prices and city-specific overrides.
            </p>
            <a href="{{ route('superadmin.locations.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium
                      text-white bg-[#D70026] rounded-lg 
                      hover:bg-[#b30020] transition-all shadow-sm hover:shadow">
                <i class="fas fa-plus"></i>
                Add First Location
            </a>
        </div>
    @endforelse

    {{-- ESTADÍSTICAS MEJORADAS CON NUEVOS COLORES --}}
    @if($locations->count() > 0)
        @php
            $totalCompanies = $locations->count();
            $totalStates = 0;
            $totalBasePrices = 0;
            $totalCities = 0;
            
            foreach($locations as $states) {
                $totalStates += $states->count();
                foreach($states as $locationsByState) {
                    $totalBasePrices += $locationsByState->where('city', null)->count();
                    $totalCities += $locationsByState->whereNotNull('city')->count();
                }
            }
        @endphp

        <div class="mt-12 pt-8 border-t border-gray-300">
            <div class="flex flex-wrap items-center justify-center gap-8 text-sm">
                <div class="flex items-center gap-3 px-6 py-3 bg-[#003366]/5 rounded-xl border border-[#003366]/20">
                    <div class="w-8 h-8 bg-[#003366] rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalCompanies }}</div>
                        <div class="text-xs text-gray-600">{{ $totalCompanies === 1 ? 'company' : 'companies' }}</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 px-6 py-3 bg-[#003366]/5 rounded-xl border border-[#003366]/20">
                    <div class="w-8 h-8 bg-[#003366] rounded-full flex items-center justify-center">
                        <i class="fas fa-flag text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalStates }}</div>
                        <div class="text-xs text-gray-600">{{ $totalStates === 1 ? 'state' : 'states' }}</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 px-6 py-3 bg-[#003366]/5 rounded-xl border border-[#003366]/20">
                    <div class="w-8 h-8 bg-[#003366] rounded-full flex items-center justify-center">
                        <i class="fas fa-layer-group text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalBasePrices }}</div>
                        <div class="text-xs text-gray-600">base {{ $totalBasePrices === 1 ? 'price' : 'prices' }}</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 px-6 py-3 bg-[#D70026]/5 rounded-xl border border-[#D70026]/20">
                    <div class="w-8 h-8 bg-[#D70026] rounded-full flex items-center justify-center">
                        <i class="fas fa-city text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalCities }}</div>
                        <div class="text-xs text-gray-600">city {{ $totalCities === 1 ? 'override' : 'overrides' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    /* Estilos personalizados con los colores específicos */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Transiciones mejoradas */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    .transition-colors {
        transition-property: color, background-color, border-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    .transition-opacity {
        transition-property: opacity;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    .duration-300 {
        transition-duration: 300ms;
    }
    
    /* Estilos para truncado */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .max-w-xs {
        max-width: 20rem;
    }
    
    .max-w-\[150px\] {
        max-width: 150px;
    }
    
    /* Hover states específicos */
    .hover\:bg-\[\#003366\]\/20:hover {
        background-color: rgba(0, 51, 102, 0.2);
    }
    
    .hover\:bg-\[\#D70026\]\/20:hover {
        background-color: rgba(215, 0, 38, 0.2);
    }
    
    /* Efectos de gradiente para los colores */
    .bg-gradient-003366 {
        background: linear-gradient(135deg, #003366 0%, #002244 100%);
    }
    
    .bg-gradient-D70026 {
        background: linear-gradient(135deg, #D70026 0%, #b30020 100%);
    }
    
    /* Sombra específica para los botones principales */
    .shadow-primary {
        box-shadow: 0 4px 14px 0 rgba(0, 51, 102, 0.1);
    }
    
    /* Animación sutil para las tarjetas */
    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-card {
        animation: cardFadeIn 0.3s ease-out;
    }
</style>
{{-- ================= MODALES COMPACTOS ================= --}}

{{-- MODAL PARA AGREGAR ESTADO --}}
<div id="addStateModal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-sm transform transition-all duration-200 scale-95 opacity-0" 
         id="stateModalContent">
        <div class="p-4 border-b">
            <h3 class="text-sm font-semibold text-gray-900" id="modalStateTitle">Add New State</h3>
            <p class="text-xs text-gray-500 mt-1" id="modalStateSubtitle">Add a state to establish base pricing</p>
        </div>

        <form method="POST" action="" id="addStateForm">
            @csrf
            <input type="hidden" name="user_id" id="stateCompanyId" value="">
            
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
            <h3 class="text-sm font-semibold text-gray-900" id="modalCityTitle">Add City Override</h3>
            <p class="text-xs text-gray-500 mt-1" id="modalCitySubtitle">
                Add a city-specific price override
            </p>
        </div>

        <form method="POST" action="" id="addCityForm">
            @csrf
            <input type="hidden" name="user_id" id="cityCompanyId" value="">
            
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
function openAddState(companyId, companyName) {
    const modal = document.getElementById('addStateModal');
    const content = document.getElementById('stateModalContent');
    const form = document.getElementById('addStateForm');
    
    // Setear el user_id y la ruta
    document.getElementById('stateCompanyId').value = companyId;
    form.action = "{{ route('superadmin.locations.store') }}";
    
    // Actualizar título del modal
    document.getElementById('modalStateTitle').textContent = `Add State for ${companyName}`;
    document.getElementById('modalStateSubtitle').textContent = `Add a state to establish base pricing for ${companyName}`;
    
    // Limpiar campos anteriores
    form.querySelector('input[name="locations[0][state]"]').value = '';
    
    // Mostrar modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function openAddCity(state, companyId) {
    const modal = document.getElementById('addCityModal');
    const content = document.getElementById('cityModalContent');
    const form = document.getElementById('addCityForm');
    
    // Setear el user_id y la ruta
    document.getElementById('cityCompanyId').value = companyId;
    form.action = "{{ route('superadmin.locations.store') }}";
    
    // Setear valores
    document.getElementById('cityStateInput').value = state;
    document.getElementById('cityStateReadonly').value = state;
    
    // Actualizar título del modal
    document.getElementById('modalCityTitle').textContent = `Add City in ${state}`;
    document.getElementById('modalCitySubtitle').textContent = `Add a city-specific price override for ${state}`;
    
    // Limpiar campo de ciudad anterior
    form.querySelector('input[name="locations[0][city]"]').value = '';
    
    // Mostrar modal
    modal.classList.remove('hidden');
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

function confirmDelete(event, type = 'location') {
    event.preventDefault();
    const form = event.target.closest('form');
    
    let message = '';
    if (type === 'base price') {
        message = `Are you sure you want to delete this base price?\n\nThis will affect all cities in this state that don't have custom overrides.`;
    } else if (type === 'city override') {
        message = 'Are you sure you want to delete this city override?';
    } else {
        message = 'Are you sure you want to delete this location?';
    }
    
    if (confirm(message)) {
        form.submit();
    }
}
</script>
<style>
    /* Clases de utilidad personalizadas */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .max-w-xs {
        max-width: 20rem;
    }

    .max-w-\[120px\] {
        max-width: 120px;
    }

    .hover\:bg-gray-100:hover {
        background-color: #f3f4f6;
    }

    /* Transiciones y transformaciones */
    .transform {
        transition-property: transform, opacity;
    }

    .duration-200 {
        transition-duration: 200ms;
    }

    /* Mejoras para los modales */
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Mejoras para las tarjetas (cards) */
    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }

    /* Estilos para scrollbar personalizado (opcional) */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Mejoras para botones */
    .btn {
        transition: all 0.2s ease;
    }

    .btn:active {
        transform: scale(0.98);
    }

    /* Clase para sombras suaves */
    .shadow-soft {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Clase para bordes redondeados personalizados */
    .rounded-inherit {
        border-radius: inherit;
    }

    /* Clase para texto que se ajusta a múltiples líneas con puntos suspensivos (2 líneas) */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Clase para texto que se ajusta a múltiples líneas con puntos suspensivos (3 líneas) */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

