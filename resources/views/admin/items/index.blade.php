@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Global Items</h1>
            <p class="text-sm text-gray-500 mt-1">
                Service catalog grouped by category
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" 
                       placeholder="Search items..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
            </div>
            
            <a href="{{ route('superadmin.items.create') }}"
               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-sm hover:shadow-md">
                <i class="fas fa-plus mr-2"></i> New Item
            </a>
        </div>
    </div>

    {{-- ================= LEGEND ================= --}}
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-sm font-medium text-gray-700">Status Legend:</span>
            <div class="flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    With global price
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-yellow-50 text-yellow-700 text-xs font-medium">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                    Missing price
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Active
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                    <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                    Inactive
                </span>
            </div>
        </div>
    </div>

    {{-- ================= STATS SUMMARY ================= --}}
    @php
        $totalItems = $items->count();
        $withPrice = $items->filter(fn($item) => !empty($item->global_price) && $item->global_price > 0)->count();
        $withoutPrice = $totalItems - $withPrice;
        $activeItems = $items->filter(fn($item) => $item->is_active)->count();
    @endphp
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalItems }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">With Global Price</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $withPrice }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Missing Price</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $withoutPrice }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeItems }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <i class="fas fa-check-circle text-indigo-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= ITEMS BY CATEGORY ================= --}}
    @php
        $groupedItems = $items->groupBy(fn($item) =>
            optional($item->category)->name ?? 'Uncategorized'
        );
    @endphp

    @forelse($groupedItems as $categoryName => $categoryItems)
        {{-- CATEGORY CARD --}}
        <div class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all hover:shadow-md">
            
            {{-- CATEGORY HEADER --}}
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                            {{ $categoryName === 'Uncategorized'
                                ? 'bg-gray-100 text-gray-700'
                                : 'bg-gradient-to-r from-indigo-100 to-indigo-50 text-indigo-700'
                            }}">
                            <i class="fas fa-folder mr-2"></i>
                            {{ $categoryName }}
                        </span>
                        
                        <span class="text-sm text-gray-500 font-medium">
                            {{ $categoryItems->count() }} item{{ $categoryItems->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    @php
                        $categoryWithPrice = $categoryItems->filter(fn($item) => !empty($item->global_price) && $item->global_price > 0)->count();
                        $percentage = $categoryItems->count() > 0 ? round(($categoryWithPrice / $categoryItems->count()) * 100) : 0;
                    @endphp
                    <span class="flex items-center gap-1">
                        <i class="fas fa-percentage"></i>
                        {{ $percentage }}% with price
                    </span>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-4 text-left">Item</th>
                            <th class="px-6 py-4 text-left">Global Price</th>
                            <th class="px-6 py-4 text-left">Crew (Trailer)</th>
                            <th class="px-6 py-4 text-left">Crew (No Trailer)</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($categoryItems as $item)
                            @php
                                $hasPrice = !empty($item->global_price) && $item->global_price > 0;
                            @endphp

                            <tr class="transition-colors hover:bg-gray-50/50
                                {{ $hasPrice ? 'bg-blue-50/30' : 'bg-yellow-50/30' }}
                                {{ !$item->is_active ? 'opacity-75' : '' }}">
                                
                                {{-- ITEM --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg 
                                            {{ $hasPrice ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-600' }}">
                                            <i class="fas fa-box text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                            @if($item->description)
                                                <p class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">
                                                    {{ Str::limit($item->description, 60) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- PRICE --}}
                                <td class="px-6 py-4">
                                    @if($hasPrice)
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                                <i class="fas fa-dollar-sign mr-1.5 text-xs"></i>
                                                ${{ number_format($item->global_price, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                                            <i class="fas fa-exclamation-circle mr-1.5 text-xs"></i>
                                            Specific Pricing
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if(!empty($item->crew_price_with_trailer) && $item->crew_price_with_trailer > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-sm font-medium">
                                            <i class="fas fa-truck mr-1.5 text-xs"></i>
                                            ${{ number_format($item->crew_price_with_trailer, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-medium">
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if(!empty($item->crew_price_without_trailer) && $item->crew_price_without_trailer > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                                            <i class="fas fa-user mr-1.5 text-xs"></i>
                                            ${{ number_format($item->crew_price_without_trailer, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-medium">
                                            —
                                        </span>
                                    @endif
                                </td>


                                {{-- STATUS --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            {{ $item->is_active 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas fa-circle mr-1.5 text-2xs 
                                                {{ $item->is_active ? 'text-green-500' : 'text-gray-500' }}"></i>
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($item->updated_at)
                                            <span class="text-xs text-gray-500">
                                                Updated {{ $item->updated_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('superadmin.items.edit', $item) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                           title="Edit item">
                                            <i class="fas fa-edit mr-1.5"></i>
                                            Edit
                                        </a>
                                        
                                        <button onclick="showDeleteModal({{ $item->id }})"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                                           title="Delete item">
                                            <i class="fas fa-trash-alt mr-1.5"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    @empty
        {{-- EMPTY STATE --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
            <div class="mx-auto w-24 h-24 flex items-center justify-center bg-gray-100 rounded-full mb-6">
                <i class="fas fa-box-open text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No items found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Get started by creating your first global item in the service catalog.
            </p>
            <a href="{{ route('superadmin.items.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Create First Item
            </a>
        </div>
    @endforelse

    {{-- ================= PAGINATION ================= --}}
    @if($items->hasPages())
        <div class="mt-8 bg-white px-6 py-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results
                </div>
                <div class="flex items-center gap-2">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    @endif

</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-900/70 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-4 mx-auto">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Delete Item</h3>
        <p class="text-gray-600 text-center mb-6">
            Are you sure you want to delete this item? This action cannot be undone.
        </p>
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal(itemId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    
    form.action = "{{ route('superadmin.items.destroy', '') }}/" + itemId;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on outside click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
    }
});
</script>
@endsection

@push('styles')
<style>
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Smooth transitions */
    table tr {
        transition: background-color 0.2s ease;
    }
</style>
@endpush