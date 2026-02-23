@extends('admin.layouts.superadmin')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h1>
                <span class="px-3 py-1 text-xs font-medium rounded-full 
                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                       ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
                </span>
                @if($invoice->due_date)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Due {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                </span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            @if($invoice->status === 'draft')
            <a href="{{ route('superadmin.invoices.edit', $invoice) }}" 
               class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Invoice
            </a>
            @endif
            
            <a href="{{ route('superadmin.invoices.pdf', $invoice) }}" 
               target="_blank"
               class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</p>
            <p class="text-xl font-bold text-gray-900 mt-1">${{ number_format($invoice->total, 2) }}</p>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Items</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $invoice->items->count() }}</p>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</p>
            <p class="text-base font-medium text-gray-900 mt-1 truncate">{{ $invoice->bill_to ?? '—' }}</p>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Company</p>
            <p class="text-base font-medium text-gray-900 mt-1 truncate">{{ $invoice->companyLocation->company->company_name ?? '—' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Items Table --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Invoice Items</h3>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                            {{ $invoice->items->count() }} items
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Qty
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                    Unit Price
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($invoice->items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->description }}</div>
                                    @if($item->note)
                                        <div class="text-xs text-gray-500 mt-1 italic">{{ $item->note }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="text-sm text-gray-900 font-medium">{{ $item->quantity }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-sm text-gray-900">${{ number_format($item->price, 2) }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($item->total, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Totals --}}
                <div class="border-t border-gray-200">
                    <div class="flex justify-end px-5 py-4">
                        <div class="w-full max-w-xs">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">${{ number_format($invoice->tax, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Total</span>
                                        <span class="text-xl font-bold text-gray-900">${{ number_format($invoice->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes & Memo --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Notes --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Notes</h3>
                    </div>
                    @if($invoice->notes)
                        <div class="text-gray-700 whitespace-pre-line">{{ $invoice->notes }}</div>
                    @else
                        <div class="text-gray-400 italic">No notes provided</div>
                    @endif
                </div>
                
                {{-- Memo --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Internal Memo</h3>
                    </div>
                    @if($invoice->memo)
                        <div class="text-gray-700 whitespace-pre-line">{{ $invoice->memo }}</div>
                    @else
                        <div class="text-gray-400 italic">No memo provided</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- Customer Information --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                
                <div class="space-y-4">
                    {{-- Bill To --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Bill To</p>
                        <p class="text-gray-900 font-medium">{{ $invoice->bill_to ?? '—' }}</p>
                    </div>

                    {{-- Address --}}
                    @if($invoice->address)
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Address</p>
                        <p class="text-gray-900">{{ $invoice->address }}</p>
                    </div>
                    @endif

                    {{-- Email --}}
                    @if($invoice->customer_email)
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Email</p>
                        <div class="flex items-center gap-2 text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $invoice->customer_email }}" class="hover:text-gray-600">
                                {{ $invoice->customer_email }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Company & Location --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Company & Location</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Company</p>
                        <p class="text-gray-900 font-medium">{{ $invoice->companyLocation->company->company_name ?? '—' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Location</p>
                        <div class="flex items-center gap-2 text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>
                                {{ $invoice->companyLocation->city ?? '' }}{{ $invoice->companyLocation->city && $invoice->companyLocation->state ? ', ' : '' }}
                                {{ $invoice->companyLocation->state ?? '' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($invoice->crew)
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Crew</p>
                        <div class="flex items-center gap-2 text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>{{ $invoice->crew->name }}</span>
                            @if($invoice->crew->has_trailer)
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                Trailer
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Invoice Details --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Invoice Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Invoice Number</p>
                        <p class="text-gray-900 font-medium">{{ $invoice->invoice_number }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Issue Date</p>
                            <div class="flex items-center gap-2 text-gray-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span>
                            </div>
                        </div>
                        
                        @if($invoice->due_date)
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Due Date</p>
                            <div class="flex items-center gap-2 text-gray-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                        <div class="flex items-center gap-2">
                            @if($invoice->status === 'paid')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Paid
                            </span>
                            @elseif($invoice->status === 'sent')
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Sent
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Draft
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            @if($invoice->attachments->count())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Attachments</h3>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                        {{ $invoice->attachments->count() }} files
                    </span>
                </div>
                
                <div class="space-y-2">
                    @foreach($invoice->attachments as $file)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 truncate max-w-[180px]">
                                    {{ basename($file->original_name) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($file->created_at)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/'.$file->file_path) }}" 
                           target="_blank"
                           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-500">
                Created {{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}
                @if($invoice->updated_at && $invoice->created_at != $invoice->updated_at)
                    • Updated {{ \Carbon\Carbon::parse($invoice->updated_at)->format('M d, Y') }}
                @endif
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.invoices.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Invoices
                </a>
                
                @if($invoice->status === 'draft')
                <a href="{{ route('superadmin.invoices.edit', $invoice) }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Invoice
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    /* Smooth transitions */
    * {
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    
    /* Truncate text with ellipsis */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Ensure consistent spacing */
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
    
    /* Better table row hover */
    tr:hover {
        background-color: #f9fafb;
    }
</style>
@endsection