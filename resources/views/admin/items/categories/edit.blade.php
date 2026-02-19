@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('superadmin.item-categories.index') }}"
           class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Category</h1>
            <p class="text-sm text-gray-600">
                {{ $itemCategory->name }}
            </p>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form method="POST"
              action="{{ route('superadmin.item-categories.update', $itemCategory) }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium mb-1">Category Name *</label>
                <input name="name"
                       required
                       value="{{ old('name', $itemCategory->name) }}"
                       class="w-full border px-3 py-2 rounded text-sm">
                @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

      

            {{-- STATUS --}}
            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ $itemCategory->is_active ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600">
                <label class="text-sm">Active</label>
            </div>

            {{-- ACTIONS --}}
            <div class="pt-4 border-t flex justify-end gap-2">
                <a href="{{ route('superadmin.item-categories.index') }}"
                   class="px-4 py-2 border rounded text-sm">
                    Cancel
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Update Category
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
