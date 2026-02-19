@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Item Categories</h1>
            <p class="text-sm text-gray-600">Organize items by category</p>
        </div>

        <a href="{{ route('superadmin.item-categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2
                  bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            <i class="fas fa-plus text-xs"></i>
            New Category
        </a>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        {{ $category->name }}
                    </td>


                    <td class="px-4 py-3 text-center">
                        @if($category->is_active)
                            <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                                Active
                            </span>
                        @else
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full">
                                Inactive
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-right text-sm">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('superadmin.item-categories.edit', $category) }}"
                               class="text-blue-600 hover:underline">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('superadmin.item-categories.destroy', $category) }}"
                                  onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        No categories created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
