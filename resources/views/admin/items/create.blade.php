@extends('admin.layouts.superadmin')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Header with title and description --}}
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create new item
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            Items are used for global services and can have location-specific prices.
        </p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('superadmin.items.store') }}" class="space-y-6">
        @csrf

        {{-- Card: Basic information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Basic information
                </h2>
            </div>
            <div class="p-6 space-y-5">
                {{-- Item name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                        Item name <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="{{ old('name') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 text-sm @error('name') border-red-300 @enderror"
                        placeholder="E.g.: Roof replacement labor"
                    >
                    @error('name')
                        <p class="text-sm text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Category
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 text-sm bg-white @error('category_id') border-red-300 @enderror"
                    >
                        <option value="">— No category —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Helps organize items and price lists.
                    </p>
                    @error('category_id')
                        <p class="text-sm text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">...</svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                
            </div>
        </div>

        {{-- Card: Price settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Price settings
                </h2>
            </div>
            <div class="p-6 space-y-5">
                {{-- Global price --}}
                <div>
                    <label for="global_price" class="block text-sm font-medium text-gray-700 mb-1">
                        Global price (fallback)
                    </label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input
                            id="global_price"
                            name="global_price"
                            type="number"
                            step="0.01"
                            min="0"
                            value="{{ old('global_price') }}"
                            class="block w-full pl-7 pr-12 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 text-sm @error('global_price') border-red-300 @enderror"
                            placeholder="0.00"
                        >
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Used when no state or city‑specific price exists.
                    </p>
                    @error('global_price')
                        <p class="text-sm text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">...</svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Crew prices (2 columns) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="crew_price_with_trailer" class="block text-sm font-medium text-gray-700 mb-1">
                            With trailer
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input
                                id="crew_price_with_trailer"
                                name="crew_price_with_trailer"
                                type="number"
                                step="0.01"
                                min="0"
                                value="{{ old('crew_price_with_trailer') }}"
                                class="block w-full pl-7 pr-12 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 text-sm @error('crew_price_with_trailer') border-red-300 @enderror"
                                placeholder="0.00"
                            >
                        </div>
                        @error('crew_price_with_trailer')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="crew_price_without_trailer" class="block text-sm font-medium text-gray-700 mb-1">
                            Without trailer
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input
                                id="crew_price_without_trailer"
                                name="crew_price_without_trailer"
                                type="number"
                                step="0.01"
                                min="0"
                                value="{{ old('crew_price_without_trailer') }}"
                                class="block w-full pl-7 pr-12 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 text-sm @error('crew_price_without_trailer') border-red-300 @enderror"
                                placeholder="0.00"
                            >
                        </div>
                        @error('crew_price_without_trailer')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Amount paid to crew based on trailer availability.
                </p>
            </div>
        </div>

        {{-- Card: Status & actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 space-y-5">
                {{-- Active checkbox --}}
                <div class="flex items-center">
                    <input
                        id="is_active"
                        name="is_active"
                        type="checkbox"
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition"
                    >
                    <label for="is_active" class="ml-3 text-sm text-gray-700">
                        Active item
                    </label>
                    <span class="ml-2 text-xs text-gray-500">(inactive items are not shown in new estimates)</span>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-wrap justify-end gap-3">
                <a
                    href="{{ route('superadmin.items.index') }}"
                    class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save item
                </button>
            </div>
        </div>
    </form>
</div>
@endsection