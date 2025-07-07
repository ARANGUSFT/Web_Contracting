@extends('admin.layouts.superadmin')

@section('title', 'Edit Crew')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4">Edit Crew</h1>

    <form method="POST" action="{{ route('superadmin.crew.update', $crew) }}">
        @csrf @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Crew Name *</label>
                <input name="name" class="form-control" value="{{ old('name', $crew->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Company *</label>
                <input name="company" class="form-control" value="{{ old('company', $crew->company) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input name="email" type="email" class="form-control" value="{{ old('email', $crew->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control" value="{{ old('phone', $crew->phone) }}">
            </div>

            <!-- Status Toggle -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Status</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0"> {{-- Este se envía si el switch está apagado --}}
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $crew->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                @error('is_active')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>



            <div class="col-12">
                <label class="form-label">Operating States</label>
            
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
            
                <div class="row">
                    @foreach($availableStates as $code => $name)
                        <div class="col-md-3 col-sm-4 col-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="states[]" value="{{ $code }}"
                                    id="state_{{ $code }}"
                                    {{ in_array($code, $selectedStates) ? 'checked' : '' }}>
                                <label class="form-check-label" for="state_{{ $code }}">{{ $name }} ({{ $code }})</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            
                @error('states')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
        </div>

        

        <div class="mt-4">
            <a href="{{ route('superadmin.crew.index') }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Crew</button>
        </div>
    </form>
</div>
@endsection
