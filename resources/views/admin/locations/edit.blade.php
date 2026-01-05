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
                <h1 class="text-xl font-bold text-gray-900">Edit Location</h1>
                <p class="text-sm text-gray-600">Update location details</p>
            </div>
        </div>
    </div>

    {{-- FORMULARIO COMPACTO --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
        {{-- INFO DE EMPRESA COMPACTA --}}
        <div class="mb-5 pb-4 border-b">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                    @if($location->user && $location->user->company_name)
                        <span class="text-blue-700 font-medium text-sm">
                            {{ substr($location->user->company_name, 0, 1) }}
                        </span>
                    @else
                        <i class="fas fa-building text-blue-600 text-xs"></i>
                    @endif
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $location->user->company_name ?? 'Unnamed Company' }}</div>
                    <div class="text-xs text-gray-500">Current company (read-only)</div>
                </div>
            </div>
        </div>

        <form method="POST"
              action="{{ route('superadmin.locations.update', $location) }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            {{-- STATE FIELD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    State <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="state"
                       value="{{ old('state', $location->state) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded 
                              focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                       placeholder="e.g., California"
                       required>
                @error('state')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- CITY FIELD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    City <span class="text-gray-500 text-xs">(optional)</span>
                </label>
                <input type="text"
                       name="city"
                       value="{{ old('city', $location->city) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded 
                              focus:ring-1 focus:ring-blue-500 focus:border-transparent text-sm"
                       placeholder="e.g., Los Angeles">
                @error('city')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
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
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium 
                                   rounded hover:bg-blue-700">
                        Update Location
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- INFO ADICIONAL COMPACTA --}}
    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm">
        <div class="flex items-center gap-2 text-gray-600 mb-2">
            <i class="fas fa-info-circle"></i>
            <span class="font-medium">Location Information</span>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div>
                <span class="text-gray-500">Created:</span>
                <span class="font-medium ml-1">{{ $location->created_at->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="text-gray-500">Last Updated:</span>
                <span class="font-medium ml-1">{{ $location->updated_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

</div>

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

/* Espaciado reducido */
.space-y-4 > * + * {
    margin-top: 1rem;
}

/* Focus states más sutiles */
.focus\:ring-1:focus {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}
</style>
@endsection             