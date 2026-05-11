@props(['item', 'type'])

@php
    $route = $type === 'job'
        ? route('jobs.update-payment', $item->id)
        : route('emergencies.update-payment', $item->id);
@endphp

<div class="bg-white rounded-xl shadow p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Payment Information</h3>

    {{-- Current status --}}
    @if($item->payment_status === 'paid')
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <span class="text-green-600 font-semibold">✅ Paid</span>
            <span class="text-gray-700">${{ number_format($item->amount, 2) }}</span>
            <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($item->payment_date)->format('M d, Y') }}</span>
            @if($item->payment_receipt_path)
                <a href="{{ route('receipt.view', [$type, $item->id]) }}"
                   target="_blank"
                   class="ml-auto text-blue-600 text-sm hover:underline">
                    📄 View receipt
                </a>
            @endif
        </div>
    @else
        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <span class="text-yellow-700 font-semibold">⏳ Payment pending</span>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
                <input type="number"
                       step="0.01"
                       name="amount"
                       value="{{ $item->amount }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment date</label>
                <input type="date"
                       name="payment_date"
                       value="{{ $item->payment_date }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="payment_status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="unpaid" {{ $item->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid"   {{ $item->payment_status === 'paid'   ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Payment receipt (PDF)
                @if($item->payment_receipt_path)
                    <span class="text-green-600 ml-2 text-xs">✅ Receipt already uploaded</span>
                @endif
            </label>
            <input type="file"
                   name="payment_receipt"
                   accept=".pdf"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        @if($errors->any())
            <div class="mt-3 text-red-600 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="mt-3 text-green-600 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <button type="submit"
                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
            Save payment
        </button>
    </form>
</div>