<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payout #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .label-trailer {
            background: black;
            color: white;
            padding: 6px 12px;
            display: inline-block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-box {
            border: 1px solid #000;
            padding: 6px 12px;
            margin-bottom: 10px;
        }

        .info-box strong {
            display: inline-block;
            width: 110px;
        }

        .invoice-box {
            float: right;
            border: 2px solid #000;
            text-align: center;
            font-weight: bold;
        }

        .invoice-box .header {
            background: red;
            color: white;
            padding: 6px;
        }

        .invoice-box .content {
            padding: 6px 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background: #f0f0f0;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            font-size: 11px;
        }

        td.text-right {
            text-align: right;
        }

        .totals {
            font-weight: bold;
            font-size: 14px;
            text-align: right;
            margin-top: 10px;
        }

        .total-amount {
            color: red;
            font-weight: bold;
            font-size: 16px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>

    {{-- TRAILER LABEL --}}
    <div class="label-trailer">
        {{ $invoice->crew?->has_trailer ? 'WITH TRAILER' : 'NO TRAILER' }}
    </div>

    {{-- CREW & ADDRESS --}}
    <div class="clearfix">
        <div style="width: 70%; float: left;">
            <div class="info-box">
                <div>
                    <strong>SUBCONTRACTOR:</strong>
                    {{ $invoice->crew->name ?? '—' }}
                </div>
                <div>
                    <strong>JOB ADDRESS:</strong>
                    {{ $invoice->address ?? '—' }}
                </div>
            </div>
        </div>

        <div class="invoice-box" style="width: 25%;">
            <div class="header">PAYOUT</div>
            <div class="content">
                JOB# {{ $invoice->invoice_number }}<br>
                DATE: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('m/d/Y') }}
            </div>
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    <table>
        <thead>
            <tr>
                <th>DESCRIPTION</th>
                <th class="text-right">UNIT PRICE</th>
                <th class="text-right">QTY</th>
                <th class="text-right">SUB-TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->payoutItems as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-right">No payout items found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $payoutTotal = $invoice->payoutItems->sum('total');
    @endphp

    <div class="totals">
        TOTAL:
        <span class="total-amount">
            ${{ number_format($payoutTotal, 2) }}
        </span>
    </div>

</body>
</html>
