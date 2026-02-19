@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('superadmin.locations.index') }}"
           class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Location</h1>
            <p class="text-sm text-gray-600">
                {{ $location->user->company_name }}
            </p>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST"
              action="{{ route('superadmin.locations.update', $location) }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            {{-- STATE --}}
            <div>
                <label class="block text-sm font-medium mb-1">State *</label>
                <input type="text"
                       name="state"
                       maxlength="5"
                       value="{{ old('state', $location->state) }}"
                       required
                       class="w-full border px-3 py-2 rounded text-sm">
                @error('state') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- CITY --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    City <span class="text-xs text-gray-500">(optional)</span>
                </label>
                <input type="text"
                       name="city"
                       value="{{ old('city', $location->city) }}"
                       class="w-full border px-3 py-2 rounded text-sm">
                @error('city') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- ACTIONS --}}
            <div class="pt-4 border-t flex justify-end gap-2">
                <a href="{{ route('superadmin.locations.index') }}"
                   class="px-4 py-2 border rounded text-sm">
                    Cancel
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Update Location
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
