@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- HEADER --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('superadmin.locations.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Locations
            </a>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Locations</h1>
            <p class="text-sm text-gray-600 mt-1">
                Configure state-based locations with optional city overrides for selected company
            </p>
        </div>
    </div>

    <form method="POST"
          action="{{ route('superadmin.locations.store') }}"
          id="locations-form"
          class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        @csrf

        {{-- FORM HEADER --}}
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Location Configuration</h2>
        </div>

        <div class="p-6 space-y-8">
            {{-- COMPANY SELECTION --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Company <span class="text-red-500">*</span>
                </label>
                <select name="user_id" required
                        id="company-select"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        onchange="updateCompanyName()">
                    <option value="" selected disabled>Select a company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" data-name="{{ $company->company_name }}">
                            {{ $company->company_name }} 
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500" id="company-selection-hint">
                    Select a company to enable location configuration
                </p>
            </div>

            {{-- SELECTED COMPANY DISPLAY --}}
            <div id="selected-company" class="hidden p-4 bg-blue-50 border border-blue-100 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Selected Company:</p>
                        <p class="text-lg font-semibold text-blue-900" id="company-name-display"></p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                        Ready to configure
                    </span>
                </div>
            </div>

            {{-- STATES CONTAINER --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700">U.S. States</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Expand states to configure base pricing and city overrides
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-gray-600" id="enabled-count">
                                0 states enabled
                            </span>
                            <button type="button"
                                    onclick="expandAll()"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Expand All
                            </button>
                        </div>
                    </div>
                </div>

                {{-- STATES GRID --}}
                <div class="max-h-[600px] overflow-y-auto p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @php
                            $states = [
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
                        @endphp

                        @foreach($states as $code => $name)
                        <div class="border border-gray-200 rounded-lg hover:border-gray-300 transition-colors"
                             id="state-card-{{ $code }}">
                            {{-- STATE HEADER --}}
                            <div class="flex items-center justify-between p-3 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox"
                                           id="state-toggle-{{ $code }}"
                                           onchange="toggleState('{{ $code }}', this.checked)"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="state-toggle-{{ $code }}"
                                           class="font-medium text-gray-700 cursor-pointer select-none">
                                        {{ $code }}
                                    </label>
                                    <span class="text-xs text-gray-500">{{ $name }}</span>
                                </div>
                                <button type="button"
                                        onclick="toggleDetails('{{ $code }}')"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-chevron-down text-xs" id="state-arrow-{{ $code }}"></i>
                                </button>
                            </div>

                            {{-- STATE BODY --}}
                            <div id="state-body-{{ $code }}"
                                 class="hidden px-3 py-4 space-y-4 bg-gray-50/50">
                                {{-- STATE BASE OPTION --}}
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox"
                                               id="state-base-{{ $code }}"
                                               onchange="toggleStateBase('{{ $code }}', this.checked)"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                            State-wide Pricing
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 pl-6">
                                        Apply pricing to entire state (no city-specific rates)
                                    </p>
                                </div>

                                {{-- CITIES SECTION --}}
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">City Overrides</span>
                                        <button type="button"
                                                onclick="addCity('{{ $code }}')"
                                                class="text-xs text-green-600 hover:text-green-800 font-medium">
                                            + Add City
                                        </button>
                                    </div>

                                    <div id="cities-{{ $code }}" class="space-y-2"></div>

                                    <div class="mt-3">
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fas fa-info-circle"></i>
                                            Cities override state-wide pricing when specified
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div id="summary-card" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Configuration Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="bg-white p-3 rounded border">
                        <p class="text-gray-500">States Enabled</p>
                        <p class="font-semibold text-gray-800" id="summary-states">0</p>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <p class="text-gray-500">State-wide Pricing</p>
                        <p class="font-semibold text-gray-800" id="summary-state-base">0</p>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <p class="text-gray-500">Cities Added</p>
                        <p class="font-semibold text-gray-800" id="summary-cities">0</p>
                    </div>
                </div>
            </div>

            {{-- HIDDEN FIELDS CONTAINER --}}
            <div id="hidden-fields-container"></div>
        </div>

        {{-- ACTIONS --}}
        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600" id="form-status">
                    No locations configured yet
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.locations.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            id="submit-btn"
                            class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Save Locations
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// State management
let locationIndex = 0;
let enabledStates = new Set();
let stateBaseConfig = new Set();
let cityCount = 0;

// Update summary
function updateSummary() {
    const summaryCard = document.getElementById('summary-card');
    const enabledCount = enabledStates.size;
    
    if (enabledCount > 0) {
        summaryCard.classList.remove('hidden');
        document.getElementById('summary-states').textContent = enabledCount;
        document.getElementById('summary-state-base').textContent = stateBaseConfig.size;
        document.getElementById('summary-cities').textContent = cityCount;
        
        document.getElementById('form-status').innerHTML = `
            <span class="text-green-600 font-medium">
                <i class="fas fa-check-circle mr-1"></i>
                ${enabledCount} state(s) configured
            </span>
        `;
        
        document.getElementById('enabled-count').textContent = `${enabledCount} states enabled`;
        document.getElementById('submit-btn').disabled = false;
    } else {
        summaryCard.classList.add('hidden');
        document.getElementById('form-status').textContent = 'No locations configured yet';
        document.getElementById('enabled-count').textContent = '0 states enabled';
        document.getElementById('submit-btn').disabled = true;
    }
}

// Toggle state details
function toggleDetails(state) {
    const body = document.getElementById(`state-body-${state}`);
    const arrow = document.getElementById(`state-arrow-${state}`);
    const isHidden = body.classList.contains('hidden');
    
    body.classList.toggle('hidden');
    arrow.classList.toggle('fa-chevron-down');
    arrow.classList.toggle('fa-chevron-up');
    
    // Auto-enable if opening details
    if (isHidden && !enabledStates.has(state)) {
        document.getElementById(`state-toggle-${state}`).checked = true;
        toggleState(state, true);
    }
}

// Toggle state
function toggleState(state, enabled) {
    const card = document.getElementById(`state-card-${state}`);
    const body = document.getElementById(`state-body-${state}`);
    
    if (enabled) {
        enabledStates.add(state);
        card.classList.add('border-blue-200', 'bg-blue-50/30');
        body.classList.remove('hidden');
        document.getElementById(`state-arrow-${state}`).classList.replace('fa-chevron-down', 'fa-chevron-up');
    } else {
        enabledStates.delete(state);
        card.classList.remove('border-blue-200', 'bg-blue-50/30');
        
        // Clear state base if disabled
        if (stateBaseConfig.has(state)) {
            document.getElementById(`state-base-${state}`).checked = false;
            toggleStateBase(state, false);
        }
        
        // Clear all cities
        const cityContainer = document.getElementById(`cities-${state}`);
        cityContainer.innerHTML = '';
        updateCityCount();
    }
    
    updateSummary();
}

// Toggle state base
function toggleStateBase(state, enabled) {
    const container = document.getElementById('hidden-fields-container');
    const existingField = container.querySelector(`[data-state-base="${state}"]`);
    
    if (enabled) {
        stateBaseConfig.add(state);
        if (!existingField) {
            container.insertAdjacentHTML('beforeend', `
                <input type="hidden"
                       data-state-base="${state}"
                       name="locations[${locationIndex}][state]"
                       value="${state}">
                <input type="hidden"
                       name="locations[${locationIndex}][type]"
                       value="state_base">
            `);
            locationIndex++;
        }
    } else {
        stateBaseConfig.delete(state);
        if (existingField) {
            existingField.remove();
            existingField.nextElementSibling?.remove();
        }
    }
    
    updateSummary();
}

// Add city
function addCity(state) {
    if (!enabledStates.has(state)) {
        alert('Please enable the state first');
        document.getElementById(`state-toggle-${state}`).checked = true;
        toggleState(state, true);
        return;
    }
    
    const container = document.getElementById(`cities-${state}`);
    const cityId = Date.now();
    
    container.insertAdjacentHTML('beforeend', `
        <div class="flex items-center gap-2 bg-white p-2 rounded border" id="city-${cityId}">
            <input type="hidden"
                   name="locations[${locationIndex}][state]"
                   value="${state}">
            <input type="hidden"
                   name="locations[${locationIndex}][type]"
                   value="city">
            <input type="text"
                   name="locations[${locationIndex}][city]"
                   placeholder="Enter city name"
                   class="flex-1 border-0 px-2 py-1 text-sm focus:ring-0 focus:outline-none"
                   required
                   oninput="validateCity(this, '${state}')">
            <button type="button"
                    onclick="removeCity('${cityId}')"
                    class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `);
    
    locationIndex++;
    cityCount++;
    updateSummary();
}

// Remove city
function removeCity(cityId) {
    const element = document.getElementById(`city-${cityId}`);
    if (element) {
        element.remove();
        cityCount--;
        updateSummary();
    }
}

// Validate city name
function validateCity(input, state) {
    const cityName = input.value.trim();
    const cityContainer = document.getElementById(`cities-${state}`);
    const cityInputs = cityContainer.querySelectorAll('input[name*="[city]"]');
    
    // Check for duplicates
    let duplicates = 0;
    cityInputs.forEach(otherInput => {
        if (otherInput !== input && otherInput.value.trim().toLowerCase() === cityName.toLowerCase()) {
            duplicates++;
        }
    });
    
    if (duplicates > 0) {
        input.classList.add('border-red-300', 'bg-red-50');
        input.title = 'Duplicate city name';
    } else {
        input.classList.remove('border-red-300', 'bg-red-50');
        input.title = '';
    }
}

// Update city count
function updateCityCount() {
    cityCount = document.querySelectorAll('[id^="city-"]').length;
    updateSummary();
}

// Company selection
function updateCompanyName() {
    const select = document.getElementById('company-select');
    const selectedOption = select.options[select.selectedIndex];
    const companyName = selectedOption.dataset.name;
    const display = document.getElementById('company-name-display');
    const hint = document.getElementById('company-selection-hint');
    
    if (companyName) {
        document.getElementById('selected-company').classList.remove('hidden');
        display.textContent = companyName;
        hint.textContent = 'Company selected. You can now configure locations.';
        hint.classList.add('text-green-600');
    }
}

// Expand all states
function expandAll() {
    document.querySelectorAll('[id^="state-body-"]').forEach(body => {
        body.classList.remove('hidden');
    });
    document.querySelectorAll('[id^="state-arrow-"]').forEach(arrow => {
        arrow.classList.replace('fa-chevron-down', 'fa-chevron-up');
    });
}

// Form validation
document.getElementById('locations-form').addEventListener('submit', function(e) {
    const companySelect = document.getElementById('company-select');
    if (!companySelect.value) {
        e.preventDefault();
        alert('Please select a company');
        companySelect.focus();
        return;
    }
    
    if (enabledStates.size === 0) {
        e.preventDefault();
        alert('Please enable at least one state');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
    submitBtn.disabled = true;
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit-btn').disabled = true;
    updateSummary();
});
</script>

<style>
/* Custom scrollbar */
.max-h-\[600px\]::-webkit-scrollbar {
    width: 6px;
}

.max-h-\[600px\]::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.max-h-\[600px\]::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.max-h-\[600px\]::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
@endsection