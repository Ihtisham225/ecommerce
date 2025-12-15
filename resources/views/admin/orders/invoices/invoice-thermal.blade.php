<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thermal Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.3;
        }
        .receipt {
            padding: 10px 5px;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .underline {
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            margin: 5px 0;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table.items td {
            padding: 3px 0;
        }
        table.totals td {
            padding: 5px 0;
        }
        .store-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .footer {
            font-size: 10px;
            margin-top: 15px;
        }
        .cut-line {
            text-align: center;
            margin: 20px 0;
            color: #666;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Store Header -->
        <div class="center store-name">
            {{ $storeSetting->store_name ?? 'MY STORE' }}
        </div>
        <div class="center">
            {{ $storeSetting->store_phone ?? 'Phone: N/A' }}
        </div>
        <div class="center">
            {{ now()->format('d/m/Y H:i') }}
        </div>
        
        <div class="divider"></div>
        
        <!-- Order Info -->
        <div class="bold center">INVOICE #{{ $order->order_number }}</div>
        <div class="center">{{ $order->created_at->format('d/m/Y H:i') }}</div>
        
        <div class="underline"></div>
        
        <!-- Customer Info -->
        <div class="bold">Customer:</div>
        <div>
            @if($order->customer)
                {{ $order->customer->full_name }}<br>
                @if($order->customer->phone)
                    {{ $order->customer->phone }}<br>
                @endif
            @else
                Guest Customer
            @endif
        </div>
        
        <div class="divider"></div>
        
        <!-- Items -->
        <div class="bold">ITEMS:</div>
        <table class="items">
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ Str::limit($item->title, 20) }}</td>
                    <td class="right">{{ $item->quantity }} × {{ $currencySymbol }}{{ number_format($item->price, $decimals) }}</td>
                    <td class="right">{{ $currencySymbol }}{{ number_format($item->total, $decimals) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="divider"></div>
        
        <!-- Totals -->
        <table class="totals">
            <tr>
                <td>Subtotal:</td>
                <td class="right">{{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}</td>
            </tr>
            @if($order->discount_total > 0)
            <tr>
                <td>Discount:</td>
                <td class="right">-{{ $currencySymbol }}{{ number_format($order->discount_total, $decimals) }}</td>
            </tr>
            @endif
            @if($order->tax_total > 0)
            <tr>
                <td>Tax:</td>
                <td class="right">{{ $currencySymbol }}{{ number_format($order->tax_total, $decimals) }}</td>
            </tr>
            @endif
            @if($order->shipping_total > 0)
            <tr>
                <td>Shipping:</td>
                <td class="right">{{ $currencySymbol }}{{ number_format($order->shipping_total, $decimals) }}</td>
            </tr>
            @endif
            <tr style="border-top: 2px solid #000;">
                <td class="bold">TOTAL:</td>
                <td class="right bold">{{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}</td>
            </tr>
        </table>
        
        <div class="divider"></div>
        
        <!-- Payment Info -->
        <div>
            <div>Payment: {{ ucfirst($order->payment_status) }}</div>
            <div>Method: 
                @if($order->payment_method)
                    {{ ucfirst($order->payment_method) }}
                @else
                    {{ $order->source === 'in_store' ? 'In Store' : 'Online' }}
                @endif
            </div>
        </div>
        
        <div class="cut-line">✂ - - - - - - - - - - - - - - - - - - - - ✂</div>
        
        <!-- Footer -->
        <div class="footer center">
            <div>Thank you for your business!</div>
            <div>{{ $storeSetting->store_name ?? 'My Store' }}</div>
            <div>{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>
    
    <script>
        // Auto-print for thermal printers
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        }
    </script>
</body>
</html>