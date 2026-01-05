@extends('admin.layouts.superadmin')

@section('title', 'Create Crew')

@section('content')
<div class="min-h-screen bg-gray-50/30 p-6">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-2 border-white rounded-full"></div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Crew</h1>
                    <p class="text-gray-500 mt-1">Register a new work team with all necessary details</p>
                </div>
            </div>
            <a href="{{ route('superadmin.crew.index') }}" 
               class="group relative bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    <span>Back to Crews</span>
                </div>
                <div class="absolute inset-0 bg-white/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-6xl mx-auto">
        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Form Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Crew Information</h3>
                        <p class="text-sm text-gray-600">Fill in the essential details for the new crew</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form method="POST" action="{{ route('superadmin.crew.store') }}" id="crewForm">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Basic Information
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Crew Name -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Crew Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-users text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 @error('name') border-red-500 @enderror" 
                                           placeholder="Enter crew name"
                                           required>
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Company -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Company <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="company" 
                                           value="{{ old('company') }}" 
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 @error('company') border-red-500 @enderror" 
                                           placeholder="Company name"
                                           required>
                                </div>
                                @error('company')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 @error('email') border-red-500 @enderror" 
                                           placeholder="crew@example.com"
                                           required>
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Phone Number
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 @error('phone') border-red-500 @enderror" 
                                           placeholder="(123) 456-7890">
                                </div>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Trailer Availability -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-truck text-blue-500"></i>
                            Trailer Availability
                        </h4>

                        <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            
                            <!-- Toggle -->
                            <div class="flex items-center gap-3">
                                <div class="relative inline-block w-12 h-6">
                                    <!-- hidden fallback -->
                                    <input type="hidden" name="has_trailer" value="0">

                                    <input type="checkbox"
                                        id="has_trailer"
                                        name="has_trailer"
                                        value="1"
                                        {{ old('has_trailer') ? 'checked' : '' }}
                                        class="absolute w-12 h-6 rounded-full bg-gray-300 checked:bg-blue-600
                                                cursor-pointer transition-colors duration-200 appearance-none">

                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full
                                                transition-transform duration-200
                                                {{ old('has_trailer') ? 'translate-x-6' : '' }}">
                                    </span>
                                </div>

                                <div>
                                    <label for="has_trailer" class="text-sm font-medium text-gray-700 cursor-pointer">
                                        Has Trailer
                                    </label>
                                    <p class="text-xs text-gray-500">
                                        This crew operates with its own trailer
                                    </p>
                                </div>
                            </div>

                            <!-- Badge -->
                            <div class="flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium
                                {{ old('has_trailer') ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                <i class="fas {{ old('has_trailer') ? 'fa-truck' : 'fa-ban' }}"></i>
                                <span>{{ old('has_trailer') ? 'With Trailer' : 'No Trailer' }}</span>
                            </div>

                        </div>
                    </div>


                    <!-- Status Toggle -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-toggle-on text-blue-500"></i>
                            Crew Status
                        </h4>
                        
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="relative inline-block w-12 h-6">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="absolute w-12 h-6 rounded-full bg-gray-300 checked:bg-blue-600 cursor-pointer transition-colors duration-200 appearance-none">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform {{ old('is_active', true) ? 'translate-x-6' : '' }}"></span>
                                </div>
                                <div>
                                    <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                                        Active Crew
                                    </label>
                                    <p class="text-xs text-gray-500">Crew will be available for assignments</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium {{ old('is_active', true) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                <i class="fas {{ old('is_active', true) ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                <span>{{ old('is_active', true) ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Operating States Section -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-blue-500"></i>
                            Operating States
                        </h4>
                        
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                            @php
                                $availableStates = [
                                    'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
                                    'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
                                    'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
                                    'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
                                    'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
                                    'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
                                    'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
                                    'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
                                    'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
                                    'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                                    'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
                                    'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
                                    'WI' => 'Wisconsin', 'WY' => 'Wyoming'
                                ];

                                $selectedStates = old('states', []);
                            @endphp

                            <!-- States Selection Controls -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <button type="button" 
                                        id="selectAllStates" 
                                        class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-check-square mr-1"></i>Select All
                                </button>
                                <button type="button" 
                                        id="deselectAllStates" 
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-times-circle mr-1"></i>Deselect All
                                </button>
                                <div class="ml-auto text-sm text-gray-500">
                                    <span id="selectedCount">0</span> states selected
                                </div>
                            </div>

                            <!-- States Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 max-h-96 overflow-y-auto p-2">
                                @foreach($availableStates as $code => $name)
                                    <div class="relative">
                                        <input type="checkbox" 
                                               name="states[]" 
                                               value="{{ $code }}" 
                                               id="state_{{ $code }}" 
                                               class="hidden peer"
                                               {{ in_array($code, $selectedStates) ? 'checked' : '' }}>
                                        <label for="state_{{ $code }}" 
                                               class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-sm hover:border-gray-300">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900 text-sm">{{ $name }}</div>
                                                <div class="text-xs text-gray-500">{{ $code }}</div>
                                            </div>
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-blue-500 peer-checked:border-blue-500 flex items-center justify-center transition-colors duration-200">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></i>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('states')
                                <p class="text-red-500 text-sm mt-3 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('superadmin.crew.index') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium transition-all duration-200 hover:bg-gray-50 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105 shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            Create Crew
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for states grid */
    .max-h-96 {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f9fafb;
    }
    
    .max-h-96::-webkit-scrollbar {
        width: 6px;
    }
    
    .max-h-96::-webkit-scrollbar-track {
        background: #f9fafb;
        border-radius: 3px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    
    /* Smooth transitions for form elements */
    .form-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Custom checkbox animation */
    input[type="checkbox"]:checked + label {
        animation: pulse 0.3s ease-in-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status toggle functionality
        const statusToggle = document.getElementById('is_active');
        const statusBadge = document.querySelector('.flex.items-center.gap-2.px-3');
        
        if (statusToggle) {
            statusToggle.addEventListener('change', function() {
                const isActive = this.checked;
                if (isActive) {
                    statusBadge.className = 'flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle"></i><span>Active</span>';
                } else {
                    statusBadge.className = 'flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700';
                    statusBadge.innerHTML = '<i class="fas fa-times-circle"></i><span>Inactive</span>';
                }
            });
        }
        
        // States selection functionality
        const selectAllBtn = document.getElementById('selectAllStates');
        const deselectAllBtn = document.getElementById('deselectAllStates');
        const stateCheckboxes = document.querySelectorAll('input[name="states[]"]');
        const selectedCount = document.getElementById('selectedCount');
        
        // Update selected count
        function updateSelectedCount() {
            const checkedCount = Array.from(stateCheckboxes).filter(checkbox => checkbox.checked).length;
            selectedCount.textContent = checkedCount;
        }
        
        // Initialize count
        updateSelectedCount();
        
        // Select all states
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                stateCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    // Trigger change event to update UI
                    checkbox.dispatchEvent(new Event('change'));
                });
                updateSelectedCount();
            });
        }
        
        // Deselect all states
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                stateCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    // Trigger change event to update UI
                    checkbox.dispatchEvent(new Event('change'));
                });
                updateSelectedCount();
            });
        }
        
        // Update count when individual checkboxes change
        stateCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Form validation
        const form = document.getElementById('crewForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-xl shadow-lg z-50';
                    errorDiv.innerHTML = `
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Please fill in all required fields</span>
                        </div>
                    `;
                    document.body.appendChild(errorDiv);
                    
                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorDiv.remove();
                    }, 3000);
                }
            });
        }
        
        // Phone number formatting
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    value = value.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                    e.target.value = !value[2] ? value[1] : '(' + value[1] + ') ' + value[2] + (value[3] ? '-' + value[3] : '');
                }
            });
        }
        
        // Add focus effects to form inputs
        const formInputs = document.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-xl');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
    });
</script>

@endsection