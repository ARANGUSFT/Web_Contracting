@extends('admin.layouts.superadmin')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
                <p class="text-gray-600 text-sm mt-1">Manage and review all invoice records</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" 
                           placeholder="Search invoices..." 
                           class="pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 w-64">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <a href="{{ route('superadmin.invoices.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Invoice
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total</p>
            <div class="flex items-baseline mt-1">
                <p class="text-xl font-bold text-gray-900">{{ $invoices->total() }}</p>
                <p class="ml-1 text-sm text-gray-500">invoices</p>
            </div>
        </div>
       <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</p>
    <p class="text-xl font-bold text-gray-900 mt-1">${{ number_format($invoices->sum('total'), 2) }}</p>
</div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</p>
            <p class="text-xl font-bold text-amber-600 mt-1">{{ $invoices->where('status', 'sent')->count() }}</p>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</p>
            <p class="text-xl font-bold text-green-600 mt-1">{{ $invoices->where('status', 'paid')->count() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Filters</h3>
                <button @click="showFilters = !showFilters" 
                        class="text-xs text-gray-500 hover:text-gray-700">
                    <span x-text="showFilters ? 'Hide' : 'Show'"></span> Filters
                </button>
            </div>
        </div>
        
        <div x-show="showFilters" x-collapse class="px-4 py-4">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="status" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    {{-- Company --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Company</label>
                        <select name="company_id" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                    {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Range --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Date Range</label>
                        <select name="period" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <option value="">All Time</option>
                            <option value="this_month" {{ request('period') === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_30_days" {{ request('period') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_quarter" {{ request('period') === 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                            <option value="this_year" {{ request('period') === 'this_year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>

                    {{-- State --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">State</label>
                        <select name="state" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                            <option value="">All States</option>
                            @foreach($states as $state)
                                <option value="{{ $state }}" 
                                    {{ request('state') == $state ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        {{ $invoices->total() }} records found
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('superadmin.invoices.index') }}"
                           class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Clear
                        </a>
                        <button type="submit" 
                                class="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-green-700">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-red-700">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Compact Table --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Invoice
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Dates
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Payout
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Margin
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Invoice Number & Bill To --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $invoice->invoice_number }}
                                    </span>
                                    <span class="text-xs text-gray-500 truncate max-w-[180px] mt-0.5">
                                        {{ $invoice->bill_to ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Company Info --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-900 font-medium">
                                        {{ $invoice->companyLocation->user->company_name ?? '—' }}
                                    </span>
                                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span>{{ $invoice->companyLocation->state ?? '—' }}</span>
                                        @if($invoice->companyLocation->city)
                                            <span>• {{ $invoice->companyLocation->city }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Dates --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
                                    </span>
                                    <span class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Due {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d') }}
                                    </span>
                                </div>
                            </td>


                            

                            {{-- Amount --}}
                            <td class="px-4 py-3">
                                <span class="text-sm font-bold text-gray-900">
                                    ${{ number_format($invoice->total, 2) }}
                                </span>
                            </td>


                            @php
                                $payoutTotal = $invoice->payoutItems->sum(fn($item) => $item->price * $item->quantity);
                            @endphp
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-800">
                                    ${{ number_format($payoutTotal, 2) }}
                                </span>
                            </td>

{{-- Margin with visual bar --}}
<td class="px-4 py-3">
    @php
        $margin = $invoice->total - $payoutTotal;
        $percent = $invoice->total > 0 ? ($margin / $invoice->total) * 100 : 0;
        $barWidth = min(max($percent, 0), 100); // solo positivos en barra
    @endphp

    <div class="w-36">
        {{-- Text labels --}}
        <div class="flex justify-between text-xs mb-1">
            <span class="{{ $margin >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ${{ number_format($margin, 2) }}
            </span>
            <span class="text-gray-500">{{ number_format($percent, 1) }}%</span>
        </div>

        {{-- Visual bar --}}
        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div class="h-full {{ $margin >= 0 ? 'bg-green-500' : 'bg-red-500' }}"
                 style="width: {{ abs($percent) > 100 ? 100 : abs($percent) }}%;">
            </div>
        </div>
    </div>
</td>


                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @php
                                    $statusConfig = [
                                        'draft' => [
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-700',
                                            'label' => 'Draft'
                                        ],
                                        'sent' => [
                                            'bg' => 'bg-blue-50',
                                            'text' => 'text-blue-700',
                                            'label' => 'Sent'
                                        ],
                                        'paid' => [
                                            'bg' => 'bg-green-50',
                                            'text' => 'text-green-700',
                                            'label' => 'Paid'
                                        ]
                                    ];
                                    $config = $statusConfig[$invoice->status] ?? $statusConfig['draft'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                   

                                    {{-- Edit --}}
                                    @if($invoice->status === 'draft')
                                        <a href="{{ route('superadmin.invoices.edit', $invoice) }}"
                                           class="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded transition-colors"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- More Actions --}}
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                        
                                   <div x-show="open" 
                                        @click.away="open = false"
                                        class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                        <div class="py-1">

                                            
                                            {{-- PDF Download --}}
                                            <a href="{{ route('superadmin.invoices.pdf', $invoice) }}" 
                                            target="_blank"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                            title="Download PDF">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                Download PDF
                                            </a>

                                            {{-- Prepare Payout --}}
                                            <a href="{{ route('superadmin.invoices.prepare', $invoice) }}"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Prepare Payout
                                            </a>

                                            {{-- View Details --}}
                                            @if($invoice->status !== 'draft')
                                                <a href="{{ route('superadmin.invoices.edit', $invoice) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View Details
                                                </a>
                                            @endif

                                            {{-- Divider --}}
                                            <div class="border-t border-gray-100 my-1"></div>


                                            {{-- Delete --}}
                                            <form action="{{ route('superadmin.invoices.destroy', $invoice) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('Delete this invoice?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center">
                                <div class="text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500">No invoices found</p>
                                    @if(request()->hasAny(['status', 'company_id', 'period', 'state']))
                                        <p class="text-xs text-gray-400 mt-1">Try adjusting your filters</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Compact Pagination --}}
        @if($invoices->hasPages())
            <div class="border-t border-gray-200">
                <div class="px-4 py-3 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($invoices->onFirstPage())
                            <span class="px-2 py-1 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $invoices->previousPageUrl() }}" 
                               class="px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                Previous
                            </a>
                        @endif

                        @foreach($invoices->getUrlRange(max(1, $invoices->currentPage() - 2), min($invoices->lastPage(), $invoices->currentPage() + 2)) as $page => $url)
                            @if($page == $invoices->currentPage())
                                <span class="px-2 py-1 text-sm bg-gray-900 text-white rounded">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" 
                                   class="px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($invoices->hasMorePages())
                            <a href="{{ $invoices->nextPageUrl() }}" 
                               class="px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                Next
                            </a>
                        @else
                            <span class="px-2 py-1 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Quick Actions Bar (Fixed at bottom on mobile) --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg p-3">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                {{ $invoices->count() }} shown
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.invoices.create') }}" 
                   class="inline-flex items-center gap-1 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New
                </a>
                <button @click="document.querySelector('[x-ref=filters]').scrollIntoView({ behavior: 'smooth' })"
                        class="inline-flex items-center gap-1 px-3 py-2 border border-gray-300 text-gray-700 text-xs font-medium rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </div>
</div>



{{-- Add Alpine.js --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
/* Compact table styles */
.container-fluid {
    padding-bottom: 4rem; /* Space for mobile actions bar */
}

.min-w-full th {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.min-w-full td {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    vertical-align: top;
}

/* Reduce row height */
tr {
    height: 3.5rem;
}

/* Make table more compact */
table {
    font-size: 0.875rem; /* text-sm */
}

/* Status badges */
.inline-flex.items-center.px-2\.5.py-0\.5 {
    padding-top: 0.125rem;
    padding-bottom: 0.125rem;
}

/* Action buttons */
.p-1\.5 {
    padding: 0.375rem;
}

/* Scrollbar styling for table */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f7fafc;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 3px;
}

/* Hover effects */
tr:hover {
    background-color: #f9fafb;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .min-w-full th,
    .min-w-full td {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    /* Hide some columns on mobile */
    th:nth-child(3), /* Dates column */
    td:nth-child(3) {
        display: none;
    }
}

/* Smooth transitions */
* {
    transition: background-color 0.2s ease, border-color 0.2s ease;
}
</style>

<script>
// Initialize Alpine.js data
document.addEventListener('alpine:init', () => {
    Alpine.data('tableData', () => ({
        showFilters: false,
        selectedRows: [],
        
        toggleAll() {
            if (this.selectedRows.length === {{ $invoices->count() }}) {
                this.selectedRows = [];
            } else {
                this.selectedRows = Array.from({length: {{ $invoices->count() }}}, (_, i) => i);
            }
        },
        
        toggleRow(index) {
            if (this.selectedRows.includes(index)) {
                this.selectedRows = this.selectedRows.filter(i => i !== index);
            } else {
                this.selectedRows.push(index);
            }
        }
    }));
});
</script>
@endsection