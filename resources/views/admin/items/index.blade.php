@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- ================= HEADER WITH FILTERS ================= --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Global Items</h1>
            <p class="text-sm text-gray-500 mt-1">Service catalog grouped by category</p>
        </div>

        <a href="{{ route('superadmin.items.create') }}"
           class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> New Item
        </a>
    </div>

    {{-- ================= FILTER BAR ================= --}}
    <form method="GET" action="{{ route('superadmin.items.index') }}" class="mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Search --}}
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name or description..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Status --}}
                <div class="relative">
                    <i class="fas fa-flag absolute left-3 top-3.5 text-gray-400 text-sm"></i>
                    <select name="status" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">All statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Global price --}}
                <div class="relative">
                    <i class="fas fa-dollar-sign absolute left-3 top-3.5 text-gray-400 text-sm"></i>
                    <select name="price_status" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">All prices</option>
                        <option value="with" {{ request('price_status') == 'with' ? 'selected' : '' }}>With global price</option>
                        <option value="without" {{ request('price_status') == 'without' ? 'selected' : '' }}>Without global price</option>
                    </select>
                </div>

                {{-- Category --}}
                <div class="relative">
                    <i class="fas fa-folder absolute left-3 top-3.5 text-gray-400 text-sm"></i>
                    <select name="category_id" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('superadmin.items.index') }}"
                       class="px-4 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- ================= LEGEND & STATS ================= --}}
    @php
        $totalItems = $items->total();
        $withPrice = $items->filter(fn($item) => !empty($item->global_price) && $item->global_price > 0)->count();
        $withoutPrice = $totalItems - $withPrice;
        $activeItems = $items->filter(fn($item) => $item->is_active)->count();
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-sm font-medium text-gray-700">Legend:</span>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium border border-blue-200">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span> With global price
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-yellow-50 text-yellow-700 text-xs font-medium border border-yellow-200">
                    <span class="w-2 h-2 bg-yellow-500 rounded-full"></span> Without global price
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-200">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> Active
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium border border-gray-200">
                    <span class="w-2 h-2 bg-gray-500 rounded-full"></span> Inactive
                </span>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items
        </div>
    </div>

    {{-- ================= STAT CARDS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Items</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalItems }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                <i class="fas fa-box text-xl"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">With global price</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $withPrice }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-xl text-green-600">
                <i class="fas fa-dollar-sign text-xl"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Missing price</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $withoutPrice }}</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-xl text-yellow-600">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Active items</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeItems }}</p>
            </div>
            <div class="p-3 bg-indigo-100 rounded-xl text-indigo-600">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
    </div>

    {{-- ================= ITEMS BY CATEGORY ================= --}}
    @php
        $groupedItems = $items->groupBy(fn($item) => optional($item->category)->name ?? 'Uncategorized');
    @endphp

    @forelse($groupedItems as $categoryName => $categoryItems)
        <div class="mb-8 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            {{-- Category header --}}
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold {{ $categoryName === 'Uncategorized' ? 'bg-gray-200 text-gray-800' : 'bg-indigo-100 text-indigo-800' }}">
                        <i class="fas fa-folder mr-2"></i> {{ $categoryName }}
                    </span>
                    <span class="text-sm text-gray-500 font-medium">{{ $categoryItems->count() }} items</span>
                </div>
                @php
                    $catWithPrice = $categoryItems->filter(fn($i) => !empty($i->global_price) && $i->global_price > 0)->count();
                    $percentage = $categoryItems->count() > 0 ? round(($catWithPrice / $categoryItems->count()) * 100) : 0;
                @endphp
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-600">Global price:</span>
                    <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-gray-800 font-medium">{{ $percentage }}%</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-4 text-left">Item</th>
                            <th class="px-6 py-4 text-left">Global Price</th>
                            <th class="px-6 py-4 text-left">Crew (w/ trailer)</th>
                            <th class="px-6 py-4 text-left">Crew (w/o trailer)</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categoryItems as $item)
                            @php $hasPrice = !empty($item->global_price) && $item->global_price > 0; @endphp
                            <tr class="transition-colors hover:bg-gray-50 {{ $hasPrice ? 'bg-blue-50/20' : 'bg-yellow-50/20' }} {{ !$item->is_active ? 'opacity-70' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ $hasPrice ? 'bg-blue-200 text-blue-700' : 'bg-yellow-200 text-yellow-700' }}">
                                            <i class="fas fa-box text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                            @if($item->description)
                                                <p class="text-xs text-gray-500 mt-0.5 max-w-xs truncate">{{ Str::limit($item->description, 60) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($hasPrice)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                            <i class="fas fa-dollar-sign mr-1 text-xs"></i> ${{ number_format($item->global_price, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">
                                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i> Specific
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($item->crew_price_with_trailer) && $item->crew_price_with_trailer > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-sm font-medium">
                                            <i class="fas fa-truck mr-1 text-xs"></i> ${{ number_format($item->crew_price_with_trailer, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($item->crew_price_without_trailer) && $item->crew_price_without_trailer > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-medium">
                                            <i class="fas fa-user mr-1 text-xs"></i> ${{ number_format($item->crew_price_without_trailer, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                            <i class="fas fa-circle mr-1.5 text-2xs {{ $item->is_active ? 'text-green-500' : 'text-gray-500' }}"></i>
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($item->updated_at)
                                            <span class="text-xs text-gray-400" title="{{ $item->updated_at->format('d/m/Y H:i') }}">
                                                <i class="far fa-clock mr-1"></i> {{ $item->updated_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('superadmin.items.edit', $item) }}"
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="showDeleteModal({{ $item->id }})"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                            <i class="fas fa-trash-alt"></i>
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
        {{-- Empty state --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-box-open text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No items found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Get started by creating your first global item in the service catalog.</p>
            <a href="{{ route('superadmin.items.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Create first item
            </a>
        </div>
    @endforelse

    {{-- ================= PAGINATION ================= --}}
    @if($items->hasPages())
        <div class="mt-8">
            {{ $items->appends(request()->query())->links() }}
        </div>
    @endif
</div>

{{-- ================= IMPROVED DELETE MODAL ================= --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden z-50 transition-opacity">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl transform transition-all">
        <div class="flex items-center justify-center w-14 h-14 bg-red-100 rounded-full mb-4 mx-auto">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete item?</h3>
        <p class="text-gray-600 text-center mb-6">This action is irreversible. The item will be permanently deleted.</p>
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-gray-200">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
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

    // Close on outside click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) hideDeleteModal();
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideDeleteModal();
    });
</script>
@endsection

@push('styles')
<style>
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    /* Smooth transitions */
    table tr { transition: background-color 0.15s ease; }
    .backdrop-blur-sm { backdrop-filter: blur(4px); }
</style>
@endpush