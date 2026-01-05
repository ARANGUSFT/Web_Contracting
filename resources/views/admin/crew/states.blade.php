@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6">

    {{-- HEADER SIMPLIFICADO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $crew->name }}</h1>
                <p class="text-gray-600 mt-1">Select a state to manage items and prices</p>
            </div>
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                {{ count($crew->states ?? []) }} states
            </span>
        </div>
    </div>

    {{-- STATES GRID MEJORADO --}}
    @if($crew->states && count($crew->states))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($crew->states as $state)
                <div class="group relative">
                    <a href="{{ route('superadmin.crews.states.items.index', [$crew->id, $state]) }}"
                       class="block bg-white border border-gray-200 rounded-xl p-5
                              hover:border-blue-400 hover:shadow-lg
                              transition-all duration-300 transform hover:-translate-y-1">
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 flex items-center justify-center 
                                            bg-blue-50 text-blue-600 rounded-lg mr-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $state }}</h4>
                                    <p class="text-sm text-gray-500">Click to manage</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-box text-gray-400 mr-2"></i>
                                <span>Manage items & prices</span>
                            </div>
                        </div>

                        {{-- INDICADOR VISUAL --}}
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 
                                    transition-opacity duration-300">
                            <div class="w-8 h-8 flex items-center justify-center 
                                        bg-blue-600 text-white rounded-full">
                                <i class="fas fa-arrow-right text-sm"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        {{-- EMPTY STATE MEJORADO --}}
        <div class="bg-white border border-gray-200 rounded-xl p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center 
                        bg-gray-100 rounded-full">
                <i class="fas fa-map text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                No States Assigned
            </h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                This crew doesn't have any states assigned yet. 
                Edit the crew to add operating states.
            </p>
            <a href="{{ route('superadmin.crew.index') }}"
               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-arrow-left"></i>
                Back to crews list
            </a>
        </div>
    @endif

    {{-- BOTÓN DE VOLVER MEJORADO --}}
    <div class="mt-10 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <a href="{{ route('superadmin.crew.index') }}"
               class="inline-flex items-center gap-3 px-5 py-3 bg-gray-50 
                      text-gray-700 hover:text-gray-900 hover:bg-gray-100 
                      font-medium rounded-lg transition-colors group">
                <div class="w-8 h-8 flex items-center justify-center 
                            bg-white border border-gray-200 rounded-lg 
                            group-hover:border-gray-300 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <span>Back to All Crews</span>
            </a>
            
            @if($crew->states && count($crew->states))
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                <span>Click on any state to manage items</span>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
/* Animaciones suaves */
.group:hover .group-hover\:translate-y-0 {
    transform: translateY(0);
}

/* Efecto de elevación */
.hover\:shadow-lg {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Transición suave para todos los elementos */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
@endsection