@extends('admin.layouts.superadmin')

@section('title', 'Crew Management')

@section('content')
<div class="min-h-screen bg-gray-50/30 p-6">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-2 border-white rounded-full"></div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Crew Management</h1>
                    <p class="text-gray-500 mt-1">Manage your work teams and assignments</p>
                </div>
            </div>
            <a href="{{ route('superadmin.crew.create') }}" 
               class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-xs"></i>
                    </div>
                    <span>New Crew</span>
                </div>
                <div class="absolute inset-0 bg-white/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Crews -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Crews</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $crews->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-blue-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                <span>All active work teams</span>
            </div>
        </div>

        <!-- Assigned -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Assigned</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $crews->filter(fn($crew) => $crew->subcontractors->isNotEmpty())->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-check text-green-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <i class="fas fa-users text-green-500 mr-1"></i>
                <span>With assigned managers</span>
            </div>
        </div>

        <!-- Active -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $crews->where('is_active', true)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bolt text-emerald-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <i class="fas fa-circle text-emerald-500 text-xs mr-1"></i>
                <span>Currently active</span>
            </div>
        </div>

        <!-- Available -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Available</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $crews->filter(fn($crew) => $crew->subcontractors->isEmpty())->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-amber-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <i class="fas fa-user-plus text-amber-500 mr-1"></i>
                <span>Ready for assignment</span>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Search & Filters</h3>
                    <p class="text-sm text-gray-600 mt-1">Find specific crews using filters</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-blue-600 font-medium">
                    <i class="fas fa-filter"></i>
                    <span>Customize your search</span>
                </div>
            </div>
            
            <!-- Applied filters indicator -->
            <div id="appliedFilters" class="mt-4 flex flex-wrap gap-2 hidden">
                <span class="text-sm text-gray-600">Applied filters:</span>
            </div>
        </div>
        
        <!-- Filters Content -->
        <div class="p-6">
            <form method="GET" action="{{ route('superadmin.crew.index') }}" id="searchForm">
                <div class="space-y-6">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-search text-blue-500"></i>
                            Crew Search
                        </label>
                        <div class="relative max-w-2xl">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="searchInput"
                                   value="{{ request('search') }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 shadow-sm"
                                   placeholder="Search by name, company, specialty...">
                        </div>
                    </div>

                    <!-- Expandable Filters -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <!-- Filters Toggle -->
                        <button type="button" id="filtersToggle" class="w-full p-4 bg-gray-50 hover:bg-gray-100 text-left flex justify-between items-center transition-colors duration-200">
                            <span class="font-semibold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-sliders-h text-blue-500"></i>
                                Advanced Filters
                            </span>
                            <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="toggleIcon"></i>
                        </button>
                        
                        <!-- Filters Content -->
                        <div id="filtersContent" class="p-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                            @php
                                $availableStates = [
                                    'AL'=>'Alabama', 'AK'=>'Alaska', 'AZ'=>'Arizona', 'AR'=>'Arkansas', 'CA'=>'California',
                                    'CO'=>'Colorado', 'CT'=>'Connecticut', 'DE'=>'Delaware', 'FL'=>'Florida', 'GA'=>'Georgia',
                                    'HI'=>'Hawaii', 'ID'=>'Idaho', 'IL'=>'Illinois', 'IN'=>'Indiana', 'IA'=>'Iowa',
                                    'KS'=>'Kansas', 'KY'=>'Kentucky', 'LA'=>'Louisiana', 'ME'=>'Maine', 'MD'=>'Maryland',
                                    'MA'=>'Massachusetts', 'MI'=>'Michigan', 'MN'=>'Minnesota', 'MS'=>'Mississippi', 'MO'=>'Missouri',
                                    'MT'=>'Montana', 'NE'=>'Nebraska', 'NV'=>'Nevada', 'NH'=>'New Hampshire', 'NJ'=>'New Jersey',
                                    'NM'=>'New Mexico', 'NY'=>'New York', 'NC'=>'North Carolina', 'ND'=>'North Dakota',
                                    'OH'=>'Ohio', 'OK'=>'Oklahoma', 'OR'=>'Oregon', 'PA'=>'Pennsylvania', 'RI'=>'Rhode Island',
                                    'SC'=>'South Carolina', 'SD'=>'South Dakota', 'TN'=>'Tennessee', 'TX'=>'Texas', 'UT'=>'Utah',
                                    'VT'=>'Vermont', 'VA'=>'Virginia', 'WA'=>'Washington', 'WV'=>'West Virginia',
                                    'WI'=>'Wisconsin', 'WY'=>'Wyoming'
                                ];
                                $selectedStates = request()->get('states', []);
                            @endphp

                            <!-- States Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Operating States
                                </label>
                                <div class="relative">
                                    <select name="states[]" 
                                            multiple 
                                            id="statesSelect"
                                            class="multiselect block w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 shadow-sm py-2">
                                        @foreach($availableStates as $code => $name)
                                            <option value="{{ $code }}" {{ in_array($code, $selectedStates) ? 'selected' : '' }}>
                                                {{ $name }} ({{ $code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Hold Ctrl (Cmd on Mac) to select multiple states</p>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <select name="status" 
                                        id="statusSelect"
                                        class="block w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 shadow-sm py-2.5">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-200">
                        <button type="button" 
                                id="resetButton"
                                class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl font-medium transition-all duration-200 hover:bg-gray-50 flex items-center justify-center gap-2">
                            <i class="fas fa-redo"></i>
                            Reset
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105 shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Crew List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($crews->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-sm mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">No crews yet</h3>
                    <p class="text-gray-500 mb-6">Start building your team by adding the first crew</p>
                    <a href="{{ route('superadmin.crew.create') }}" 
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        <span>Create First Crew</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Crew Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 p-6">
                @foreach ($crews as $crew)
                <div class="group relative bg-white border border-gray-200 rounded-2xl hover:border-blue-300 transition-all duration-300 hover:shadow-lg">
                    <!-- Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md">
                                        {{ strtoupper(substr($crew->name, 0, 1)) }}
                                    </div>
                                    @if($crew->is_active)
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-2 border-white rounded-full"></div>
                                    @else
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-red-400 border-2 border-white rounded-full"></div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $crew->name }}</h3>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="fas fa-building"></i>
                                        <span>{{ $crew->company }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                @if($crew->subcontractors->isNotEmpty())
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        <i class="fas fa-user-check"></i>
                                        Assigned
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                        <i class="fas fa-clock"></i>
                                        Available
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Contact Info -->
                        <div class="grid grid-cols-1 gap-3 mb-4">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-gray-500"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-600 truncate">{{ $crew->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-gray-600">{{ $crew->phone ?: 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- States -->
                        @if($crew->states && is_array($crew->states) && count($crew->states))
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Operating States</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($crew->states, 0, 4) as $state)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $state }}
                                        </span>
                                    @endforeach
                                    @if(count($crew->states) > 4)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 cursor-help"
                                              title="{{ implode(', ', array_slice($crew->states, 4)) }}">
                                            +{{ count($crew->states) - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Subcontractors -->
                        @if($crew->subcontractors->isNotEmpty())
                            <div class="border-t border-gray-100 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-3">Assigned Managers</p>
                                <div class="space-y-2">
                                    @foreach($crew->subcontractors as $sub)
                                        <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center text-white font-medium text-sm">
                                                {{ strtoupper(substr($sub->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $sub->name }} {{ $sub->last_name }}
                                                </p>
                                                <p class="text-xs text-gray-500 truncate">{{ $sub->company_name }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($crew->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        <i class="fas fa-times-circle"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('superadmin.crew.assign', $crew->id) }}" 
                                   class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg font-medium transition-all duration-200 text-sm border border-blue-200 hover:border-blue-300"
                                   title="Assign managers">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Assign</span>
                                </a>
                                
                                <a href="{{ route('superadmin.crew.edit', $crew->id) }}" 
                                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg font-medium transition-all duration-200 text-sm border border-gray-300 hover:border-gray-400"
                                   title="Edit crew">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('superadmin.crew.destroy', $crew->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this crew?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg font-medium transition-all duration-200 text-sm border border-red-200 hover:border-red-300"
                                            title="Delete crew">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($crews->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing {{ $crews->firstItem() }} to {{ $crews->lastItem() }} of {{ $crews->total() }} crews
                        </div>
                        <div class="flex gap-1">
                            {{ $crews->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    /* Custom scrollbar for multi-select */
    select[multiple] {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f9fafb;
    }
    
    select[multiple]::-webkit-scrollbar {
        width: 6px;
    }
    
    select[multiple]::-webkit-scrollbar-track {
        background: #f9fafb;
        border-radius: 3px;
    }
    
    select[multiple]::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }
    
    select[multiple]::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    
    /* Smooth transitions */
    .crew-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Gradient text for special elements */
    .gradient-text {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filtersToggle = document.getElementById('filtersToggle');
        const filtersContent = document.getElementById('filtersContent');
        const toggleIcon = document.getElementById('toggleIcon');
        const resetButton = document.getElementById('resetButton');
        const searchForm = document.getElementById('searchForm');
        const appliedFiltersContainer = document.getElementById('appliedFilters');
        const statesSelect = document.getElementById('statesSelect');
        const statusSelect = document.getElementById('statusSelect');
        const searchInput = document.getElementById('searchInput');
        
        // Toggle filters visibility
        if (filtersToggle) {
            filtersToggle.addEventListener('click', function() {
                filtersContent.classList.toggle('hidden');
                toggleIcon.classList.toggle('rotate-180');
            });
        }
        
        // Reset form
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                searchForm.reset();
                updateAppliedFilters();
            });
        }
        
        // Handle form submission
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                updateAppliedFilters();
            });
        }
        
        // Update applied filters display
        function updateAppliedFilters() {
            if (!appliedFiltersContainer) return;
            
            appliedFiltersContainer.innerHTML = '<span class="text-sm text-gray-600">Applied filters:</span>';
            
            // Search filter
            if (searchInput && searchInput.value) {
                addAppliedFilter('search', `Search: "${searchInput.value}"`);
            }
            
            // States filter
            if (statesSelect) {
                const selectedStates = Array.from(statesSelect.selectedOptions).map(option => option.text);
                if (selectedStates.length > 0) {
                    const statesText = selectedStates.length > 2 
                        ? `${selectedStates.length} states selected`
                        : selectedStates.join(', ');
                    addAppliedFilter('states', `States: ${statesText}`);
                }
            }
            
            // Status filter
            if (statusSelect && statusSelect.value) {
                const statusText = statusSelect.options[statusSelect.selectedIndex].text;
                addAppliedFilter('status', `Status: ${statusText}`);
            }
            
            // Show/hide applied filters container
            if (appliedFiltersContainer.children.length > 1) {
                appliedFiltersContainer.classList.remove('hidden');
            } else {
                appliedFiltersContainer.classList.add('hidden');
            }
        }
        
        // Add individual applied filter badge
        function addAppliedFilter(type, text) {
            const filterBadge = document.createElement('div');
            filterBadge.className = 'applied-filter bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1';
            filterBadge.innerHTML = `
                ${text}
                <button type="button" class="text-blue-500 hover:text-blue-700" data-filter-type="${type}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add remove functionality
            const removeButton = filterBadge.querySelector('button');
            removeButton.addEventListener('click', function() {
                if (type === 'search') {
                    searchInput.value = '';
                } else if (type === 'states') {
                    Array.from(statesSelect.options).forEach(option => option.selected = false);
                } else if (type === 'status') {
                    statusSelect.value = '';
                }
                updateAppliedFilters();
            });
            
            appliedFiltersContainer.appendChild(filterBadge);
        }
        
        // Initialize
        updateAppliedFilters();

        // Add smooth animations to cards
        const cards = document.querySelectorAll('.group');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Enhanced form interactions
        const formInputs = document.querySelectorAll('input, select');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
    });
</script>

@endsection