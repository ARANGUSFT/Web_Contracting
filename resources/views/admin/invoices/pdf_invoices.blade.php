<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 22px;
            color: #00b2cc;
            margin-bottom: 0;
        }

        .company-info {
            font-size: 12px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h4 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .info-pair {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background: #e6f4f6;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        th {
            text-align: left;
            font-weight: bold;
        }

        td.text-right {
            text-align: right;
        }

        .totals {
            width: 40%;
            float: right;
            margin-top: 20px;
        }

        .totals td {
            border: none;
            padding: 4px 6px;
        }

        .totals tr.total {
            background: #e6f4f6;
            font-weight: bold;
        }

        .balance-due {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
        }

        .logo {
            position: absolute;
            right: 30px;
            top: 30px;
        }

        .logo img {
            height: 60px;
        }

        .footer-note {
            font-size: 10px;
            color: #777;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="logo">
     <img src="{{ public_path('img/ayf.png') }}" alt="Company Logo">
    </div>

    <div class="header">
        <h2 class="invoice-title">INVOICE</h2>

        <div class="company-info">
            <strong>A&F RESTORATION SERVICES</strong><br>
            (407)9850405<br>
            fsanchez@afrestoration.com
        </div>
    </div>


    <div class="section">
        <table>
            <tr>
                <td width="50%">
                    <h4>BILL TO</h4>
                    <p>
                        {{ $invoice->companyLocation->user->company_name ?? '—' }}<br>
                        {{ $invoice->address }}<br>
                        {{ $invoice->customer_email }}<br>
                        {{ $invoice->companyLocation->state }} {{ $invoice->companyLocation->city }}
                    </p>
                </td>
                <td width="50%">
                    <h4>INVOICE #{{ $invoice->invoice_number }}</h4>
                    <p>
                        <strong>Date:</strong> {{ $invoice->invoice_date }}<br>
                        <strong>Due:</strong> {{ $invoice->due_date ?? '—' }}<br>
                        <strong>Terms:</strong> Due on receipt
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>ITEMS</h4>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Sub-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
{{-- Totals --}}
<div class="section totals-section">
    

    <div class="balance-due">
        BALANCE DUE
        <div class="balance-amount">
            ${{ number_format($invoice->total, 2) }}
        </div>
    </div>
</div>

{{-- Notes --}}
@if($invoice->notes)
    <div class="section notes-section">
        <p class="notes-text">{{ $invoice->notes }}</p>
    </div>
@endif

{{-- Footer --}}
<div class="footer-note">
    Thanks for your business!
</div>

</body>
</html>
