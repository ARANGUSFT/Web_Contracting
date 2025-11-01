@extends('admin.layouts.superadmin')
@section('title', 'Assign Subcontractors to Crew')

@section('content')
<div class="min-h-screen bg-gray-50/30 p-6">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.crew.index') }}" 
               class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Assign Subcontractors</h1>
                <div class="flex items-center gap-2 mt-1">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <p class="text-gray-500">Assign subcontractors to crew: <span class="font-semibold text-gray-700">{{ $crew->name }}</span></p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-xl border border-blue-200">
                <i class="fas fa-users text-blue-500"></i>
                <span class="text-sm font-medium text-blue-700">{{ $crew->subcontractors->count() }} assigned</span>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Main Assignment Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Assign Subcontractors</h2>
                            <p class="text-sm text-gray-600">Select subcontractors to assign to this crew</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-700" id="selected-count">0 selected</div>
                            <div class="text-xs text-gray-500">of {{ $subcontractors->where('available', true)->count() }} available</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="bg-amber-50 border-b border-amber-200 px-6 py-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
                    <div>
                        <p class="text-sm font-medium text-amber-800">Assignment Restriction</p>
                        <p class="text-xs text-amber-700">Each subcontractor can only be assigned to one crew. Already assigned subcontractors are disabled.</p>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="p-6 border-b border-gray-100 bg-white">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Subcontractors</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="search-input"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200"
                                   placeholder="Search by name, company...">
                        </div>
                    </div>

                    <!-- Filter Toggles -->
                    <div class="lg:col-span-6 flex items-end gap-2">
                        <button type="button" 
                                onclick="selectAllAvailable()"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-check-double"></i>
                            <span>Select All Available</span>
                        </button>
                        <button type="button" 
                                onclick="deselectAll()"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            <span>Clear All</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST" action="{{ route('superadmin.crew.assign.store', $crew->id) }}" id="assignment-form">
                @csrf
                
                <div class="p-6">
                    <!-- Subcontractors Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto p-2" id="subcontractors-container">
                        @foreach ($subcontractors as $sub)
                        @php
                            $isAssignedToThisCrew = $crew->subcontractors->contains($sub->id);
                            $isAssignedToOtherCrew = $sub->crew_id && !$isAssignedToThisCrew;
                            $isAvailable = !$isAssignedToOtherCrew;
                        @endphp

                        <div class="subcontractor-card group relative border-2 rounded-xl transition-all duration-200 p-4 
                                    {{ $isAssignedToThisCrew ? 'border-green-500 bg-green-50' : ($isAvailable ? 'border-gray-200 bg-white hover:border-blue-300' : 'border-gray-100 bg-gray-50') }}"
                             data-search="{{ strtolower($sub->name . ' ' . $sub->last_name . ' ' . $sub->company_name) }}"
                             data-available="{{ $isAvailable ? 'true' : 'false' }}">
                            
                            <!-- Checkbox -->
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    @if($isAvailable)
                                        <input type="checkbox" 
                                               name="subcontractors[]" 
                                               value="{{ $sub->id }}" 
                                               id="sub-{{ $sub->id }}"
                                               class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200"
                                               {{ $isAssignedToThisCrew ? 'checked' : '' }}
                                               onchange="updateSelectionCount()">
                                    @else
                                        <div class="h-5 w-5 bg-gray-200 border border-gray-300 rounded flex items-center justify-center">
                                            <i class="fas fa-lock text-gray-400 text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Subcontractor Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($sub->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <label for="sub-{{ $sub->id }}" class="block text-sm font-semibold text-gray-900 truncate cursor-pointer {{ !$isAvailable ? 'cursor-not-allowed' : '' }}">
                                                {{ $sub->name }} {{ $sub->last_name }}
                                            </label>
                                            <p class="text-xs text-gray-500 truncate">{{ $sub->company_name }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Contact Info -->
                                    <div class="space-y-1 text-xs text-gray-600">
                                        @if($sub->email)
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-envelope text-gray-400 w-3"></i>
                                            <span class="truncate">{{ $sub->email }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($sub->phone)
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-phone text-gray-400 w-3"></i>
                                            <span>{{ $sub->phone }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="mt-2">
                                        @if($isAssignedToThisCrew)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Currently Assigned
                                            </span>
                                        @elseif($isAssignedToOtherCrew)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                                <i class="fas fa-ban mr-1"></i>
                                                Assigned to Another Crew
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                                <i class="fas fa-check mr-1"></i>
                                                Available
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Assigned Crew Info -->
                                    @if($isAssignedToOtherCrew && $sub->crew)
                                    <div class="mt-2 p-2 bg-red-50 rounded border border-red-200">
                                        <p class="text-xs text-red-700">
                                            <span class="font-medium">Currently assigned to:</span> 
                                            {{ $sub->crew->name }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Hover Actions -->
                            @if($isAvailable)
                            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button" 
                                        onclick="toggleSubcontractor({{ $sub->id }})"
                                        class="w-8 h-8 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-500 hover:border-blue-300 transition-all duration-200 shadow-sm">
                                    <i class="fas fa-exchange-alt text-xs"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state" class="hidden text-center py-12">
                        <div class="max-w-sm mx-auto">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No subcontractors found</h3>
                            <p class="text-gray-500">Try adjusting your search criteria</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span id="selection-summary">Select available subcontractors to assign</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('superadmin.crew.index') }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" 
                                    class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                <span>Save Assignments</span>
                                <div class="absolute inset-0 bg-white/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

  

        <!-- Currently Assigned Section -->
        @if($crew->subcontractors->count() > 0)
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    Currently Assigned to This Crew ({{ $crew->subcontractors->count() }})
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($crew->subcontractors as $sub)
                    <div class="flex items-center gap-3 p-4 bg-green-50 rounded-xl border border-green-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white font-medium">
                            {{ strtoupper(substr($sub->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $sub->name }} {{ $sub->last_name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ $sub->company_name }}</p>
                        </div>
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-500 text-xs"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Custom scrollbar */
    #subcontractors-container {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f9fafb;
    }
    
    #subcontractors-container::-webkit-scrollbar {
        width: 6px;
    }
    
    #subcontractors-container::-webkit-scrollbar-track {
        background: #f9fafb;
        border-radius: 3px;
    }
    
    #subcontractors-container::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }
    
    #subcontractors-container::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    
    /* Checkbox styling */
    input[type="checkbox"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    /* Smooth transitions */
    .subcontractor-card {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .subcontractor-card:hover:not(.disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    /* Disabled state */
    .subcontractor-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectionCount();
        setupSearch();
        setupFormSubmission();
    });

    // Search functionality
    function setupSearch() {
        const searchInput = document.getElementById('search-input');
        const subcontractorCards = document.querySelectorAll('.subcontractor-card');
        const emptyState = document.getElementById('empty-state');
        const container = document.getElementById('subcontractors-container');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            let visibleCount = 0;

            subcontractorCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
                container.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                container.classList.remove('hidden');
            }
        });
    }

    // Update selection count and summary
    function updateSelectionCount() {
        const checkboxes = document.querySelectorAll('input[name="subcontractors[]"]:checked');
        const availableCount = document.querySelectorAll('.subcontractor-card[data-available="true"]').length;
        const selectedCount = checkboxes.length;
        
        // Update count display
        document.getElementById('selected-count').textContent = `${selectedCount} selected`;
        
        // Update summary text
        const summary = document.getElementById('selection-summary');
        if (selectedCount === 0) {
            summary.textContent = 'Select available subcontractors to assign';
        } else if (selectedCount === 1) {
            summary.textContent = '1 subcontractor selected';
        } else {
            summary.textContent = `${selectedCount} subcontractors selected`;
        }
        
        // Update card styles for selected items
        document.querySelectorAll('.subcontractor-card').forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                card.classList.add('border-blue-500', 'bg-blue-50');
                card.classList.remove('border-gray-200', 'bg-white');
            } else if (card.getAttribute('data-available') === 'true') {
                card.classList.remove('border-blue-500', 'bg-blue-50');
                card.classList.add('border-gray-200', 'bg-white');
            }
        });
    }

    // Select all available subcontractors
    function selectAllAvailable() {
        document.querySelectorAll('.subcontractor-card[data-available="true"] input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectionCount();
    }

    // Deselect all subcontractors
    function deselectAll() {
        document.querySelectorAll('input[name="subcontractors[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectionCount();
    }

    // Toggle individual subcontractor
    function toggleSubcontractor(id) {
        const checkbox = document.getElementById(`sub-${id}`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            updateSelectionCount();
        }
    }

    // Form submission handling
    function setupFormSubmission() {
        const form = document.getElementById('assignment-form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            const selectedCount = document.querySelectorAll('input[name="subcontractors[]"]:checked').length;
            
            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Saving Assignments...</span>
            `;
            submitBtn.disabled = true;
            
            // Revert after 5 seconds in case of error
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + A to select all available
        if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
            e.preventDefault();
            selectAllAvailable();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            const searchInput = document.getElementById('search-input');
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    });
</script>

@endsection