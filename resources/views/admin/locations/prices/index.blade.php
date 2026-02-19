@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    {{-- ================= HEADER ================= --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('superadmin.locations.index') }}"
               class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    Prices – {{ $location->state }} {{ $location->city ? ' / '.$location->city : '' }}
                </h1>
                <p class="text-sm text-gray-600">
                    {{ $location->user->company_name }} • {{ $items->count() }} items
                </p>
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ route('superadmin.locations.prices.store', $location) }}">
        @csrf

        {{-- ================= LEGEND ================= --}}
        <div class="mb-4 px-4 py-3 bg-white border rounded text-sm text-gray-600 flex flex-wrap gap-4">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 bg-blue-400 rounded"></span>
                Global / State
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 bg-green-400 rounded"></span>
                City override
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 bg-yellow-400 rounded"></span>
                Missing price
            </span>
        </div>

        {{-- ================= CATEGORIES ================= --}}
        @foreach($items->groupBy('category_name') as $category => $categoryItems)

            <div class="bg-white border border-gray-200 rounded-lg mb-6 overflow-hidden">

                {{-- CATEGORY HEADER --}}
                <div class="px-5 py-3 bg-gray-50 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded
                                     bg-indigo-100 text-indigo-700">
                            {{ $category ?? 'Uncategorized' }}
                        </span>

                        <span class="text-xs text-gray-500">
                            {{ $categoryItems->count() }} item(s)
                        </span>
                    </div>
                </div>

                {{-- ITEMS TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-4 py-2 text-left">Item</th>
                                <th class="px-4 py-2 text-left">Source</th>
                                <th class="px-4 py-2 text-right w-40">Price</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y text-sm">
                        @foreach($categoryItems as $item)
                            @php
                                $hasPrice = $item->effective_price > 0;
                            @endphp

                            <tr class="
                                hover:bg-gray-50
                                {{ $hasPrice ? 'bg-blue-50' : 'bg-yellow-50' }}
                            ">

                                {{-- ITEM --}}
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $item->name }}
                                </td>

                                {{-- SOURCE --}}
                                <td class="px-4 py-3 text-xs">
                                    @if($item->price_source === 'city')
                                        <span class="px-2 py-0.5 rounded-full
                                                     bg-green-100 text-green-700 font-medium">
                                            City
                                        </span>
                                    @elseif($item->price_source === 'state')
                                        <span class="px-2 py-0.5 rounded-full
                                                     bg-blue-100 text-blue-700 font-medium">
                                            State
                                        </span>
                                    @elseif($item->global_price > 0)
                                        <span class="px-2 py-0.5 rounded-full
                                                     bg-blue-100 text-blue-700 font-medium">
                                            Global
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full
                                                     bg-yellow-100 text-yellow-800 font-medium">
                                            Missing
                                        </span>
                                    @endif
                                </td>

                                {{-- PRICE --}}
                                <td class="px-4 py-3">
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="prices[{{ $item->id }}]"
                                           value="{{ $hasPrice ? number_format($item->effective_price, 2, '.', '') : '' }}"
                                           placeholder="{{ $hasPrice ? '' : 'Set price' }}"
                                           class="
                                               w-full text-right px-2 py-1 text-sm rounded
                                               focus:ring-2 focus:outline-none
                                               {{ $hasPrice
                                                   ? 'border border-blue-300 bg-blue-50 focus:ring-blue-300'
                                                   : 'border-b-2 border-yellow-400 bg-yellow-50 focus:ring-yellow-400'
                                               }}
                                           ">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endforeach

        {{-- ================= FOOTER ================= --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-5 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                Save Prices
            </button>
        </div>

    </form>

</div>
@endsection
