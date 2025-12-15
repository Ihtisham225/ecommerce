<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        @page { margin: 50px; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .store-name {
            font-size: 24px;
            font-weight: bold;
            color: #111;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #444;
            margin-top: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #333;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .totals-table td {
            padding: 8px 0;
        }
        .totals-table .total-row {
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Store Header -->
    <div class="header">
        <div class="store-name">{{ $storeSetting->store_name ?? 'My Store' }}</div>
        <div>{{ $storeSetting->store_email ?? 'store@example.com' }} | {{ $storeSetting->store_phone ?? '+1234567890' }}</div>
        <div class="invoice-title">INVOICE</div>
    </div>

    <!-- Order Info -->
    <div class="section">
        <table class="info-table">
            <tr>
                <td width="50%">
                    <strong>Bill To:</strong><br>
                    @if($order->customer)
                        {{ $order->customer->full_name }}<br>
                        {{ $order->customer->email }}<br>
                        {{ $order->customer->phone ?? '' }}<br>
                        @if($billingAddress = $order->billingAddress)
                            {{ $billingAddress->address_line_1 }}<br>
                            @if($billingAddress->address_line_2)
                                {{ $billingAddress->address_line_2 }}<br>
                            @endif
                            {{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}<br>
                            {{ $billingAddress->country }}
                        @endif
                    @else
                        Guest Customer
                    @endif
                </td>
                <td width="50%" style="text-align: right;">
                    <strong>Invoice Details:</strong><br>
                    Invoice No: <strong>{{ $order->order_number }}</strong><br>
                    Order Date: {{ $order->created_at->format('M d, Y') }}<br>
                    Payment Status: {{ ucfirst($order->payment_status) }}<br>
                    Order Status: {{ ucfirst($order->status) }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <div class="section">
        <div class="section-title">Order Items</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="40%">Item</th>
                    <th width="15%">SKU</th>
                    <th width="15%">Quantity</th>
                    <th width="15%">Unit Price</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $currencySymbol }}{{ number_format($item->price, $decimals) }}</td>
                    <td>{{ $currencySymbol }}{{ number_format($item->total, $decimals) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals -->
    <div class="section">
        <table class="totals-table" style="float: right; width: 300px;">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}</td>
            </tr>
            @if($order->discount_total > 0)
            <tr>
                <td>Discount:</td>
                <td style="text-align: right; color: #e53e3e;">-{{ $currencySymbol }}{{ number_format($order->discount_total, $decimals) }}</td>
            </tr>
            @endif
            @if($order->tax_total > 0)
            <tr>
                <td>Tax:</td>
                <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->tax_total, $decimals) }}</td>
            </tr>
            @endif
            @if($order->shipping_total > 0)
            <tr>
                <td>Shipping:</td>
                <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->shipping_total, $decimals) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Grand Total:</td>
                <td style="text-align: right; font-size: 14px;">{{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div style="clear: both;"></div>
    <div class="footer">
        <div>{{ $storeSetting->store_name ?? 'My Store' }}</div>
        <div>Invoice generated on {{ now()->format('M d, Y \a\t h:i A') }}</div>
        <div style="margin-top: 10px;">This is a computer-generated invoice. No signature required.</div>
    </div>
</body>
</html>