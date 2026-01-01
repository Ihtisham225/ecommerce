<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Order #{{ $order->order_number }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Placed: {{ $order->created_at->format('M j, Y \\a\\t g:i A') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('customer.orders.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>

                @if($order->status === 'completed')
                <button onclick="downloadInvoice({{ $order->id }})"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Invoice
                </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Status Banner -->
            <div class="mb-6">
                <div class="bg-gradient-to-r 
                    @if($order->status === 'completed') from-green-500 to-emerald-600
                    @elseif($order->status === 'cancelled') from-red-500 to-pink-600
                    @elseif($order->status === 'processing') from-blue-500 to-indigo-600
                    @else from-yellow-500 to-orange-600
                    @endif rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold mb-2">Order Status: {{ ucfirst($order->status) }}</h3>
                            <p class="text-white/90">
                                @if($order->status === 'completed')
                                ✅ Your order has been successfully completed.
                                @elseif($order->status === 'cancelled')
                                ❌ Your order has been cancelled.
                                @elseif($order->status === 'processing')
                                🔄 Your order is being processed.
                                @else
                                ⏳ Your order is pending confirmation.
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $order->created_at->format('M j, Y') }}
                            </span>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                                @if($order->source === 'online')
                                🌐 Online Order
                                @else
                                🏪 In-Store Purchase
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Items</h3>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    @if($item->product && $item->product->mainImage)
                                    <img src="{{ $item->product->mainImage->first()->url }}"
                                        class="w-20 h-20 rounded-lg object-contain border border-gray-200 dark:border-gray-600">
                                    @else
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->title }}</h4>
                                                @if($item->sku)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">SKU: {{ $item->sku }}</p>
                                                @endif
                                                @if($item->variant)
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Variant: {{ $item->variant->title }}
                                                </p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $currencySymbol }}{{ number_format($item->price, $decimals) }} × {{ $item->quantity }}
                                                </p>
                                                <p class="font-bold text-lg text-indigo-600 dark:text-indigo-400">
                                                    {{ $currencySymbol }}{{ number_format($item->total, $decimals) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}
                                    </span>
                                </div>
                                @if($order->discount_total > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Discount</span>
                                    <span class="font-medium text-red-600 dark:text-red-400">
                                        -{{ $currencySymbol }}{{ number_format($order->discount_total, $decimals) }}
                                    </span>
                                </div>
                                @endif
                                @if($order->tax_total > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tax</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $currencySymbol }}{{ number_format($order->tax_total, $decimals) }}
                                    </span>
                                </div>
                                @endif
                                @if($order->shipping_total > 0 && $order->source === 'online')
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $currencySymbol }}{{ number_format($order->shipping_total, $decimals) }}
                                    </span>
                                </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-600">
                                    <span>Total</span>
                                    <span class="text-xl">
                                        {{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    @if($order->history->count())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Timeline</h3>
                        </div>
                        <div class="p-6">
                            <div class="relative">
                                @foreach($order->history->sortBy('created_at') as $index => $history)
                                <div class="flex items-start mb-6 last:mb-0">
                                    <div class="flex flex-col items-center mr-4">
                                        <div class="w-8 h-8 rounded-full 
                                                {{ $history->new_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $history->new_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $history->new_status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ in_array($history->new_status, ['pending', 'confirmed']) ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        @if($index < $order->history->count() - 1)
                                            <div class="w-0.5 h-full bg-gray-300 dark:bg-gray-600 mt-2"></div>
                                            @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    Status changed to {{ ucfirst($history->new_status) }}
                                                </p>
                                                @if($history->notes)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $history->notes }}</p>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                {{ $history->created_at->format('M j, g:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column: Order Info -->
                <div class="space-y-6">
                    <!-- Payment Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Status</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatusColors[$order->payment_status] }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Order Total</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Amount Paid</span>
                                        <span class="font-semibold text-green-600 dark:text-green-400">
                                            {{ $currencySymbol }}{{ number_format($totalPaid, $decimals) }}
                                        </span>
                                    </div>
                                    @if($balanceDue > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Balance Due</span>
                                        <span class="font-semibold text-orange-600 dark:text-orange-400">
                                            {{ $currencySymbol }}{{ number_format($balanceDue, $decimals) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Payment Progress -->
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">Payment Progress</span>
                                        <span class="font-semibold">{{ round($paymentPercentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="
                                            @if($paymentPercentage == 100) bg-green-600
                                            @elseif($paymentPercentage > 0) bg-blue-600
                                            @else bg-yellow-600
                                            @endif
                                            h-2 rounded-full transition-all duration-500"
                                            style="width: {{ min($paymentPercentage, 100) }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Methods -->
                                @if($order->payments->count())
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Payment Methods</h4>
                                    <div class="space-y-2">
                                        @foreach($order->payments as $payment)
                                        <div class="text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    {{ ucfirst($payment->method) }} Payment
                                                </span>
                                                <span class="font-medium">
                                                    {{ $currencySymbol }}{{ number_format($payment->amount, $decimals) }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $payment->pivot->created_at->format('M j, g:i A') }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    @if($order->source === 'online' && $shippingAddress)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Shipping Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Status</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $shippingStatusColors[$order->shipping_status] }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->shipping_status)) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Shipping Address</h4>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <p class="font-medium">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</p>
                                        <p>{{ $shippingAddress->address_line_1 }}</p>
                                        @if($shippingAddress->address_line_2)
                                        <p>{{ $shippingAddress->address_line_2 }}</p>
                                        @endif
                                        <p>
                                            @if($shippingAddress->city){{ $shippingAddress->city }}, @endif
                                            @if($shippingAddress->state){{ $shippingAddress->state }} @endif
                                            @if($shippingAddress->postal_code){{ $shippingAddress->postal_code }}@endif
                                        </p>
                                        @if($shippingAddress->country)
                                        <p>{{ $shippingAddress->country }}</p>
                                        @endif
                                        @if($shippingAddress->phone)
                                        <p class="mt-2">📞 {{ $shippingAddress->phone }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tracking Info -->
                                @if($order->fulfillments->count())
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tracking Information</h4>
                                    <div class="space-y-2">
                                        @foreach($order->fulfillments as $fulfillment)
                                        <div class="text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600 dark:text-gray-400">Tracking #</span>
                                                @if($fulfillment->tracking_number)
                                                <a href="{{ $fulfillment->tracking_url ?? '#' }}"
                                                    target="_blank"
                                                    class="font-mono text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    {{ $fulfillment->tracking_number }}
                                                </a>
                                                @else
                                                <span class="text-gray-500">Not available</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $fulfillment->created_at->format('M j, g:i A') }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Billing Information -->
                    @if($billingAddress)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Billing Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="font-medium">{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}</p>
                                <p>{{ $billingAddress->address_line_1 }}</p>
                                @if($billingAddress->address_line_2)
                                <p>{{ $billingAddress->address_line_2 }}</p>
                                @endif
                                <p>
                                    @if($billingAddress->city){{ $billingAddress->city }}, @endif
                                    @if($billingAddress->state){{ $billingAddress->state }} @endif
                                    @if($billingAddress->postal_code){{ $billingAddress->postal_code }}@endif
                                </p>
                                @if($billingAddress->country)
                                <p>{{ $billingAddress->country }}</p>
                                @endif
                                @if($billingAddress->phone)
                                <p class="mt-2">📞 {{ $billingAddress->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Need Help? -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Need Help?</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Have questions about your order? We're here to help.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('contact.us') }}"
                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Support
                            </a>
                            <a href="#"
                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                View FAQs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadInvoice(orderId) {
            fetch(`/customer/orders/${orderId}/invoice`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.download_url) {
                        window.open(data.download_url, '_blank');
                    } else {
                        alert('Unable to download invoice. Please try again.');
                    }
                })
                .catch(error => {
                    alert('Error downloading invoice');
                });
        }
    </script>
</x-app-layout>