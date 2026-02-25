@extends('admin.layouts.superadmin')

@section('content')
<div class="container-fluid px-4 py-6 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h1>
            <span class="px-3 py-1 text-xs font-medium rounded-full 
                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                   ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>
        
        <div class="flex items-center gap-2">
        


            <a href="{{ route('superadmin.invoices.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Invoices
            </a>

            @if($invoice->status === 'draft')
            <a href="{{ route('superadmin.invoices.edit', $invoice) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Invoice
            </a>
            @endif
            
            <a href="{{ route('superadmin.invoices.pdf', $invoice) }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
            <p class="text-sm font-medium text-gray-500">Total Amount</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($invoice->total, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
            <p class="text-sm font-medium text-gray-500">Items</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $invoice->items->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
            <p class="text-sm font-medium text-gray-500">Customer</p>
            <p class="text-base font-semibold text-gray-900 mt-1 truncate" title="{{ $invoice->bill_to }}">{{ $invoice->bill_to ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
            <p class="text-sm font-medium text-gray-500">Company</p>
            <p class="text-base font-semibold text-gray-900 mt-1 truncate" title="{{ $invoice->companyLocation->company->company_name ?? '' }}">{{ $invoice->companyLocation->company->company_name ?? '—' }}</p>
        </div>
    </div>

    {{-- Main Content: Two Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column (2/3 width) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Items Table --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50/80">
                    <h3 class="text-lg font-semibold text-gray-800">Invoice Items</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($invoice->items as $item)
                            <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                <td class="px-5 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->description }}</div>
                                    @if($item->note)
                                        <div class="text-xs text-gray-500 mt-1 italic">{{ $item->note }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-5 py-3 text-right text-sm text-gray-900">${{ number_format($item->price, 2) }}</td>
                                <td class="px-5 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Totals --}}
                <div class="border-t border-gray-200 px-5 py-4 bg-gray-50/80">
                    <div class="flex justify-end">
                        <div class="w-full max-w-xs space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium text-gray-900">${{ number_format($invoice->tax, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 flex justify-between">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="font-bold text-gray-900">${{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes & Memo --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <h4 class="font-semibold text-gray-800">Notes</h4>
                    </div>
                    @if($invoice->notes)
                        <div class="text-gray-700 whitespace-pre-line">{{ $invoice->notes }}</div>
                    @else
                        <div class="text-gray-400 italic">No notes provided</div>
                    @endif
                </div>
                
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h4 class="font-semibold text-gray-800">Internal Memo</h4>
                    </div>
                    @if($invoice->memo)
                        <div class="text-gray-700 whitespace-pre-line">{{ $invoice->memo }}</div>
                    @else
                        <div class="text-gray-400 italic">No memo provided</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column (1/3 width) --}}
        <div class="space-y-6">
            {{-- Customer Information --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Bill To</p>
                        <p class="text-gray-900 font-medium">{{ $invoice->bill_to ?? '—' }}</p>
                    </div>
                    @if($invoice->address)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Address</p>
                        <p class="text-gray-900">{{ $invoice->address }}</p>
                    </div>
                    @endif
                    @if($invoice->customer_email)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <a href="mailto:{{ $invoice->customer_email }}" class="text-gray-900 hover:text-gray-600 transition-colors">
                            {{ $invoice->customer_email }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Company & Location --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Company & Location</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Company</p>
                        <p class="text-gray-900 font-medium">{{ $invoice->companyLocation->company->company_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Location</p>
                        <p class="text-gray-900">
                            {{ $invoice->companyLocation->city ?? '' }}{{ $invoice->companyLocation->city && $invoice->companyLocation->state ? ', ' : '' }}
                            {{ $invoice->companyLocation->state ?? '' }}
                        </p>
                    </div>
                    @if($invoice->crew)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Crew</p>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-900">{{ $invoice->crew->name }}</span>
                            @if($invoice->crew->has_trailer)
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Trailer</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Invoice Details --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Invoice Details</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Invoice Number</p>
                        <p class="text-gray-900 font-medium">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Issue Date</p>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</p>
                        </div>
                        @if($invoice->due_date)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Due Date</p>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            @if($invoice->attachments->count())
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Attachments</h3>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                        {{ $invoice->attachments->count() }} files
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach($invoice->attachments as $file)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50/80 transition-colors duration-150">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 truncate max-w-[160px]" title="{{ basename($file->original_name) }}">
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

    {{-- Footer --}}
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-500">
                Created {{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}
                @if($invoice->updated_at && $invoice->created_at != $invoice->updated_at)
                    • Updated {{ \Carbon\Carbon::parse($invoice->updated_at)->format('M d, Y') }}
                @endif
            </div>
          
        </div>
    </div>
</div>

{{-- Estilos mejorados --}}
<style>
    /* Scroll personalizado más elegante */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* Transiciones suaves para todos los elementos */
    * {
        transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    /* Estilos para los badges de estado */
    .badge-paid {
        @apply bg-green-100 text-green-800;
    }
    .badge-sent {
        @apply bg-blue-100 text-blue-800;
    }
    .badge-draft {
        @apply bg-gray-100 text-gray-800;
    }

    /* Mejorar la apariencia de las tarjetas */
    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }

    /* Líneas divisorias más suaves */
    .border-gray-200 {
        border-color: #e9eef2;
    }

    /* Sombra por defecto en tarjetas */
    .shadow-sm {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    /* Hover en filas de tabla */
    tr:hover {
        background-color: #fafbfc;
    }

    /* Texto truncado con tooltip */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection