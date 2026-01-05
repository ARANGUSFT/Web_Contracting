@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- HEADER COMPACTO --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Company Locations</h1>
                <p class="text-sm text-gray-600">Organized by company</p>
            </div>
            <a href="{{ route('superadmin.locations.create') }}"
               class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 
                      text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus text-xs"></i>
                New Location
            </a>
        </div>
    </div>

    {{-- FILTRO COMPACTO --}}
    @if($locations->count() > 0)
    <div class="mb-4">
        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-3">
            <div class="flex items-center gap-2 text-sm">
                <i class="fas fa-filter text-gray-400"></i>
                <span class="font-medium">Filter:</span>
            </div>
            <select id="companyFilter" class="flex-1 px-3 py-1.5 border border-gray-300 rounded 
                   focus:ring-1 focus:ring-blue-500 text-sm">
                <option value="">All Companies</option>
               @foreach($locations->groupBy('user_id') as $userId => $userLocations)
                    @php
                        $user = $userLocations->first()->user;
                    @endphp

                    @if($user && $user->is_admin == 0)
                        <option value="company-{{ $userId }}">
                            {{ $user->company_name ?? 'Unnamed' }}
                            ({{ $userLocations->count() }})
                        </option>
                    @endif
                @endforeach

            </select>
            <div class="text-sm text-gray-500">
                {{ $locations->count() }} locations
            </div>
        </div>
    </div>
    @endif

    {{-- LISTADO COMPACTO POR EMPRESA --}}
    @if($locations->count() > 0)
        @foreach($locations->groupBy('user_id') as $userId => $userLocations)
            @php
                $company = $userLocations->first()->user;
                $companyName = $company->company_name ?? 'Unnamed Company';
                $locationsCount = $userLocations->count();
            @endphp
            
            <div class="company-card bg-white border border-gray-200 rounded-lg mb-3 
                        overflow-hidden" id="company-{{ $userId }}">
                {{-- HEADER DE EMPRESA COMPACTO --}}
                <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                            @if($companyName)
                                <span class="text-blue-700 font-bold text-sm">
                                    {{ substr($companyName, 0, 1) }}
                                </span>
                            @else
                                <i class="fas fa-building text-blue-600 text-xs"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">{{ $companyName }}</h3>
                            @if($company && $company->email)
                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ $company->email }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-500">{{ $locationsCount }} locations</span>
                    </div>
                </div>

                {{-- UBICACIONES COMPACTAS --}}
                <div class="divide-y">
                    @foreach($userLocations as $location)
                    <div class="px-4 py-3 hover:bg-gray-50 flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="text-sm font-medium text-gray-900">{{ $location->city }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 text-xs 
                                             bg-blue-50 text-blue-700 rounded-full">
                                    {{ $location->state }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-1">
                            {{-- PRICES --}}
                            <a href="{{ route('superadmin.locations.items.index', $location) }}" 
                               class="inline-flex items-center gap-1 px-2 py-1 
                                      bg-green-50 text-green-700 rounded text-xs font-medium
                                      hover:bg-green-100">
                                <i class="fas fa-dollar-sign text-xs"></i>
                                Prices
                            </a>

                            {{-- EDIT --}}
                            <a href="{{ route('superadmin.locations.edit', $location) }}"
                               class="inline-flex items-center gap-1 px-2 py-1 
                                      bg-yellow-50 text-yellow-700 rounded text-xs font-medium
                                      hover:bg-yellow-100">
                                <i class="fas fa-edit text-xs"></i>
                                Edit
                            </a>

                            {{-- DELETE --}}
                            <form method="POST"
                                  action="{{ route('superadmin.locations.destroy', $location) }}"
                                  class="inline"
                                  onsubmit="return confirm('Delete {{ $location->city }}, {{ $location->state }}?')">
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
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        {{-- EMPTY STATE COMPACTO --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center 
                        bg-blue-50 rounded-full">
                <i class="fas fa-building text-blue-400"></i>
            </div>
            <h4 class="text-base font-semibold text-gray-900 mb-2">No Locations Found</h4>
            <p class="text-sm text-gray-600 mb-4">
                Start by adding the first location
            </p>
            <a href="{{ route('superadmin.locations.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 
                      text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus"></i>
                Add First Location
            </a>
        </div>
    @endif

</div>

<script>
// Filtro compacto por empresa
document.addEventListener('DOMContentLoaded', function() {
    const companyFilter = document.getElementById('companyFilter');
    if (companyFilter) {
        companyFilter.addEventListener('change', function(e) {
            const selectedCompany = e.target.value;
            const allCompanies = document.querySelectorAll('.company-card');
            
            allCompanies.forEach(company => {
                if (!selectedCompany || company.id === selectedCompany) {
                    company.style.display = 'block';
                } else {
                    company.style.display = 'none';
                }
            });
        });
    }
});
</script>

<style>
.company-card {
    transition: all 0.2s ease;
}

.company-card:hover {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Estilos compactos */
.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

/* Espaciado compacto */
.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-3 {
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

/* Divider sutil */
.divide-y > :not([hidden]) ~ :not([hidden]) {
    border-top-width: 1px;
    border-color: #f3f4f6;
}
</style>
@endsection