@extends('admin.layouts.superadmin')

@section('title', 'Edit Crew')

@section('content')
<div class="min-h-screen bg-gray-50/30 p-6">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.crew.index') }}" 
               class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Crew</h1>
                <p class="text-gray-500 mt-1">Update crew information and operating states</p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Form Header -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Crew Information</h2>
                        <p class="text-sm text-gray-600">Update the crew details and states</p>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mx-8 mt-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <h3 class="text-sm font-semibold text-red-800">Please fix the following errors:</h3>
                    </div>
                    <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Content -->
            <form method="POST" action="{{ route('superadmin.crew.update', $crew) }}" class="p-8">
                @csrf 
                @method('PUT')

                <!-- Basic Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Crew Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Crew Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <input name="name" 
                                   type="text" 
                                   value="{{ old('name', $crew->name) }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200"
                                   placeholder="Enter crew name"
                                   required>
                        </div>
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
                            <input name="company" 
                                   type="text" 
                                   value="{{ old('company', $crew->company) }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200"
                                   placeholder="Enter company name"
                                   required>
                        </div>
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
                            <input name="email" 
                                   type="email" 
                                   value="{{ old('email', $crew->email) }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200"
                                   placeholder="Enter email address"
                                   required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input name="phone" 
                                   type="tel" 
                                   value="{{ old('phone', $crew->phone) }}"
                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200"
                                   placeholder="Enter phone number">
                        </div>
                    </div>
                </div>


                <!-- Trailer Availability -->
                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                Trailer Availability
                            </h3>
                            <p class="text-sm text-gray-500">
                                Indicate if this crew operates with its own trailer
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- fallback -->
                            <input type="hidden" name="has_trailer" value="0">

                            <span class="text-sm font-medium {{ $crew->has_trailer ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $crew->has_trailer ? 'With Trailer' : 'No Trailer' }}
                            </span>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                    name="has_trailer"
                                    value="1"
                                    class="sr-only peer"
                                    {{ old('has_trailer', $crew->has_trailer) ? 'checked' : '' }}>

                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none
                                            peer-focus:ring-2 peer-focus:ring-blue-300
                                            rounded-full peer
                                            peer-checked:after:translate-x-6
                                            peer-checked:after:border-white
                                            after:content-['']
                                            after:absolute after:top-0.5 after:left-0.5
                                            after:bg-white after:border-gray-300 after:border
                                            after:rounded-full after:h-5 after:w-5
                                            after:transition-all
                                            peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>
                    </div>

                    @error('has_trailer')
                        <div class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <!-- Status Toggle -->
                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Crew Status</h3>
                            <p class="text-sm text-gray-500">Set the crew as active or inactive</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <span class="text-sm font-medium {{ $crew->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $crew->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       class="sr-only peer"
                                       {{ old('is_active', $crew->is_active) ? 'checked' : '' }}>
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div>
                    </div>
                    @error('is_active')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Operating States Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Operating States</h3>
                            <p class="text-sm text-gray-500">Select the states where this crew operates</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    onclick="selectAllStates()" 
                                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Select All
                            </button>
                            <span class="text-gray-300">|</span>
                            <button type="button" 
                                    onclick="deselectAllStates()" 
                                    class="text-sm text-gray-600 hover:text-gray-700 font-medium">
                                Deselect All
                            </button>
                        </div>
                    </div>

                    @php
                        $availableStates = [
                            'AL' => 'Alabama',     'AK' => 'Alaska',       'AZ' => 'Arizona',      'AR' => 'Arkansas',
                            'CA' => 'California',  'CO' => 'Colorado',     'CT' => 'Connecticut',  'DE' => 'Delaware',
                            'FL' => 'Florida',     'GA' => 'Georgia',      'HI' => 'Hawaii',       'ID' => 'Idaho',
                            'IL' => 'Illinois',    'IN' => 'Indiana',      'IA' => 'Iowa',         'KS' => 'Kansas',
                            'KY' => 'Kentucky',    'LA' => 'Louisiana',    'ME' => 'Maine',        'MD' => 'Maryland',
                            'MA' => 'Massachusetts','MI' => 'Michigan',    'MN' => 'Minnesota',    'MS' => 'Mississippi',
                            'MO' => 'Missouri',    'MT' => 'Montana',      'NE' => 'Nebraska',     'NV' => 'Nevada',
                            'NH' => 'New Hampshire','NJ' => 'New Jersey',  'NM' => 'New Mexico',   'NY' => 'New York',
                            'NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio',       'OK' => 'Oklahoma',
                            'OR' => 'Oregon',      'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                            'SD' => 'South Dakota','TN' => 'Tennessee',    'TX' => 'Texas',        'UT' => 'Utah',
                            'VT' => 'Vermont',     'VA' => 'Virginia',     'WA' => 'Washington',   'WV' => 'West Virginia',
                            'WI' => 'Wisconsin',   'WY' => 'Wyoming'
                        ];

                        $selectedStates = old('states', $crew->states ?? []);
                        $selectedStates = is_array($selectedStates) ? $selectedStates : [];
                    @endphp

                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 max-h-96 overflow-y-auto p-2">
                            @foreach($availableStates as $code => $name)
                                <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 cursor-pointer group">
                                    <input type="checkbox" 
                                           name="states[]" 
                                           value="{{ $code }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ in_array($code, $selectedStates) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                        {{ $name }} <span class="text-gray-400">({{ $code }})</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    @error('states')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('superadmin.crew.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                    <button type="submit" 
                            class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update Crew</span>
                        <div class="absolute inset-0 bg-white/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Crew Summary -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Current summary of teams
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-3 shadow-md">
                            {{ strtoupper(substr($crew->name, 0, 1)) }}
                        </div>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $crew->name }}</p>
                        <p class="text-xs text-gray-500">Crew Name</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-3 shadow-md">
                            <i class="fas fa-building text-lg"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $crew->company }}</p>
                        <p class="text-xs text-gray-500">Company</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-3 shadow-md">
                            <i class="fas fa-envelope text-lg"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $crew->email }}</p>
                        <p class="text-xs text-gray-500">Email</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-3 shadow-md">
                            <i class="fas fa-{{ $crew->is_active ? 'check' : 'times' }}-circle text-lg"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">{{ $crew->is_active ? 'Active' : 'Inactive' }}</p>
                        <p class="text-xs text-gray-500">Status</p>
                    </div>
                </div>
                
                @if(count($selectedStates) > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Currently Operating in {{ count($selectedStates) }} States</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selectedStates as $state)
                                @if(isset($availableStates[$state]))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">
                                        {{ $availableStates[$state] }} ({{ $state }})
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
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
    
    /* Custom toggle switch */
    input:checked + div {
        background-color: #10b981;
    }
    
    input:checked + div:after {
        transform: translateX(1.5rem);
    }
    
    /* Checkbox styling */
    input[type="checkbox"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced form interactions
        const formInputs = document.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-xl');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
        
        // Form submission loading state
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Updating Crew...</span>
            `;
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
        
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
    });

    // Select all states function
    function selectAllStates() {
        const checkboxes = document.querySelectorAll('input[name="states[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    // Deselect all states function
    function deselectAllStates() {
        const checkboxes = document.querySelectorAll('input[name="states[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    // Quick select regions (optional enhancement)
    function selectRegion(region) {
        const regions = {
            west: ['CA', 'OR', 'WA', 'NV', 'AZ', 'UT', 'ID', 'MT', 'WY', 'CO', 'NM'],
            midwest: ['IL', 'IN', 'MI', 'OH', 'WI', 'MN', 'IA', 'MO', 'ND', 'SD', 'NE', 'KS'],
            south: ['TX', 'OK', 'AR', 'LA', 'MS', 'AL', 'TN', 'KY', 'FL', 'GA', 'SC', 'NC', 'VA', 'WV'],
            northeast: ['PA', 'NY', 'NJ', 'CT', 'RI', 'MA', 'VT', 'NH', 'ME', 'MD', 'DE']
        };
        
        const checkboxes = document.querySelectorAll('input[name="states[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = regions[region].includes(checkbox.value);
        });
    }
</script>

@endsection