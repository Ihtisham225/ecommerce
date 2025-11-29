<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Order #{{ $order->order_number }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Placed: {{ $order->created_at->format('M j, Y \\a\\t g:i A') }} • 
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        {{ $order->source === 'online' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                        {{ $order->source === 'online' ? '🌐 Online' : '🏪 In Store' }}
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.edit', $order) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Order
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $storeSetting = \App\Models\StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $order->currency->code ?? 'USD';
        $currencySymbols = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥', 
            'CAD' => 'C$', 'AUD' => 'A$', 'CHF' => 'CHF', 'CNY' => '¥',
            'INR' => '₹', 'KWD' => 'KD', 'SAR' => 'SR', 'AED' => 'AED'
        ];
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;
        $decimals = $currencyCode === 'KWD' ? 3 : 2;
        
        // Status colors
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'processing' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        ];
        
        $paymentStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'partially_refunded' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        ];
        
        $shippingStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'ready_for_shipment' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        ];
        
        $isInStore = $order->source === 'in_store';
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">
                {{-- Header: Order Summary --}}
                <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row gap-8">
                        {{-- Order Status & Info --}}
                        <div class="w-full lg:w-2/3 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
                                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-wrap gap-3">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$order->status] }} shadow-sm">
                                        <span class="w-2 h-2 rounded-full mr-2 
                                            {{ $order->status === 'pending' ? 'bg-yellow-500' : '' }}
                                            {{ $order->status === 'confirmed' ? 'bg-blue-500' : '' }}
                                            {{ $order->status === 'processing' ? 'bg-indigo-500' : '' }}
                                            {{ $order->status === 'completed' ? 'bg-green-500' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-500' : '' }}
                                        "></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    
                                    @if(!$isInStore)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $paymentStatusColors[$order->payment_status] }} shadow-sm">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                    
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $shippingStatusColors[$order->shipping_status] }} shadow-sm">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $order->shipping_status)) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Customer Information --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Customer Information
                                    </h3>
                                    @if($order->customer)
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->customer->full_name }}</p>
                                                <p class="text-gray-600 dark:text-gray-400">{{ $order->customer->email }}</p>
                                                @if($order->customer->phone)
                                                    <p class="text-gray-600 dark:text-gray-400">{{ $order->customer->phone }}</p>
                                                @endif
                                            </div>
                                            <a href="{{ route('admin.customers.show', $order->customer) }}" 
                                               class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors font-medium">
                                                View Customer Profile
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            Guest Customer
                                        </div>
                                    @endif
                                </div>

                                {{-- Order Details --}}
                                <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Order Details
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Source:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $isInStore ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                {{ $isInStore ? '🏪 In Store' : '🌐 Online' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Currency:</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $order->currency->code ?? 'USD' }}</span>
                                        </div>
                                        @if($order->completed_at)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Completed:</span>
                                                <span class="font-medium text-green-600 dark:text-green-400">{{ $order->completed_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                        @endif
                                        @if($order->cancelled_at)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Cancelled:</span>
                                                <span class="font-medium text-red-600 dark:text-red-400">{{ $order->cancelled_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Order Total --}}
                        <div class="w-full lg:w-1/3">
                            <div class="p-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl text-white shadow-lg">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Order Total
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-white/90">Subtotal:</span>
                                        <span class="font-medium">{{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}</span>
                                    </div>
                                    @if($order->discount_total > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-white/90">Discount:</span>
                                            <span class="font-medium text-red-200">-{{ $currencySymbol }}{{ number_format($order->discount_total, $decimals) }}</span>
                                        </div>
                                    @endif
                                    @if($order->tax_total > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-white/90">Tax:</span>
                                            <span class="font-medium">{{ $currencySymbol }}{{ number_format($order->tax_total, $decimals) }}</span>
                                        </div>
                                    @endif
                                    @if($order->shipping_total > 0 && !$isInStore)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-white/90">Shipping:</span>
                                            <span class="font-medium">{{ $currencySymbol }}{{ number_format($order->shipping_total, $decimals) }}</span>
                                        </div>
                                    @endif
                                    <div class="border-t border-white/20 pt-3 mt-3">
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Total:</span>
                                            <span class="text-xl">{{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content Sections --}}
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    {{-- Order Items --}}
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Order Items ({{ $order->items->count() }})
                        </h3>
                        
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-600 rounded-2xl shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($order->items as $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-3">
                                                        @if($item->product && $item->product->mainImage)
                                                            <img src="{{ $item->product->mainImage->first()->url }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</div>
                                                            @if($item->product)
                                                                <a href="{{ route('admin.products.show', $item->product) }}" 
                                                                   class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors font-medium">
                                                                    View Product
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono bg-gray-50 dark:bg-gray-700/50">{{ $item->sku }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $currencySymbol }}{{ number_format($item->price, $decimals) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $currencySymbol }}{{ number_format($item->total, $decimals) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-white">Subtotal:</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">{{ $order->items->sum('quantity') }}</span> items
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Addresses - Only show for online orders --}}
                    @if(!$isInStore)
                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Billing Address --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Billing Address
                                    @if($billingAddress && $billingAddress->getTable() === 'customers')
                                        <span class="text-xs font-normal text-green-600 bg-green-100 px-2 py-1 rounded-full ml-2">
                                            From Customer Profile
                                        </span>
                                    @elseif($billingAddress)
                                        <span class="text-xs font-normal text-blue-600 bg-blue-100 px-2 py-1 rounded-full ml-2">
                                            From Order
                                        </span>
                                    @endif
                                </h3>
                                @if($billingAddress)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm space-y-3">
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $billingAddress->first_name ?? $order->customer?->first_name }} {{ $billingAddress->last_name ?? $order->customer?->last_name }}
                                        </p>
                                        @if($billingAddress->email ?? $order->customer?->email)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $billingAddress->email ?? $order->customer?->email }}</p>
                                        @endif
                                        @if($billingAddress->phone ?? $order->customer?->phone)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $billingAddress->phone ?? $order->customer?->phone }}</p>
                                        @endif
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $billingAddress->address_line_1 ?? $billingAddress->address1 }}<br>
                                            @if($billingAddress->address_line_2 ?? $billingAddress->address2)
                                                {{ $billingAddress->address_line_2 ?? $billingAddress->address2 }}<br>
                                            @endif
                                            @if($billingAddress->city)
                                                {{ $billingAddress->city }},
                                            @endif
                                            @if($billingAddress->state)
                                                {{ $billingAddress->state }}
                                            @endif
                                            @if($billingAddress->postal_code)
                                                {{ $billingAddress->postal_code }}<br>
                                            @endif
                                            @if($billingAddress->country)
                                                {{ $billingAddress->country }}
                                            @endif
                                        </p>
                                        @if($billingAddress->same_as_shipping)
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Same as Shipping Address
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        No billing address provided
                                    </div>
                                @endif
                            </div>

                            {{-- Shipping Address --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Shipping Address
                                    @if($shippingAddress && $shippingAddress->getTable() === 'customers')
                                        <span class="text-xs font-normal text-green-600 bg-green-100 px-2 py-1 rounded-full ml-2">
                                            From Customer Profile
                                        </span>
                                    @elseif($shippingAddress)
                                        <span class="text-xs font-normal text-blue-600 bg-blue-100 px-2 py-1 rounded-full ml-2">
                                            From Order
                                        </span>
                                    @endif
                                </h3>
                                @if($shippingAddress)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm space-y-3">
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $shippingAddress->first_name ?? $order->customer?->first_name }} {{ $shippingAddress->last_name ?? $order->customer?->last_name }}
                                        </p>
                                        @if($billingAddress->email ?? $order->customer?->email)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $billingAddress->email ?? $order->customer?->email }}</p>
                                        @endif
                                        @if($shippingAddress->phone ?? $order->customer?->phone)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $shippingAddress->phone ?? $order->customer?->phone }}</p>
                                        @endif
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $shippingAddress->address_line_1 ?? $shippingAddress->address1 }}<br>
                                            @if($shippingAddress->address_line_2 ?? $shippingAddress->address2)
                                                {{ $shippingAddress->address_line_2 ?? $shippingAddress->address2 }}<br>
                                            @endif
                                            @if($shippingAddress->city)
                                                {{ $shippingAddress->city }},
                                            @endif
                                            @if($shippingAddress->state)
                                                {{ $shippingAddress->state }}
                                            @endif
                                            @if($shippingAddress->postal_code)
                                                {{ $shippingAddress->postal_code }}<br>
                                            @endif
                                            @if($shippingAddress->country)
                                                {{ $shippingAddress->country }}
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        No shipping address provided
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Payment Information & Notes - Only show for online orders --}}
                    @if(!$isInStore)
                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Payment Details --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Payment Information
                                </h3>
                                
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatusColors[$order->payment_status] }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                            </span>
                                        </div>
                                        @if($order->payments->count())
                                            @foreach($order->payments as $payment)
                                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Method:</span>
                                                            <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $payment->method }}</p>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Amount:</span>
                                                            <p class="font-medium text-gray-900 dark:text-white">
                                                                {{ $currencySymbol }}{{ number_format($payment->amount, $decimals) }}
                                                            </p>
                                                        </div>
                                                        @if($payment->transaction_id)
                                                            <div class="col-span-2">
                                                                <span class="text-gray-500 dark:text-gray-400">Transaction ID:</span>
                                                                <p class="font-mono text-gray-600 dark:text-gray-400 text-xs bg-white dark:bg-gray-600 p-2 rounded border">{{ $payment->transaction_id }}</p>
                                                            </div>
                                                        @endif
                                                        <div class="col-span-2">
                                                            <span class="text-gray-500 dark:text-gray-400">Date:</span>
                                                            <p class="text-gray-600 dark:text-gray-400">{{ $payment->created_at->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                No payment records found
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Order Notes --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Order Notes
                                </h3>
                                
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <div class="space-y-4">
                                        @if($order->notes)
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Customer Notes
                                                </h5>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500">
                                                    {{ $order->notes }}
                                                </p>
                                            </div>
                                        @endif
                                        @if($order->admin_notes)
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                    </svg>
                                                    Admin Notes
                                                </h5>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500">
                                                    {{ $order->admin_notes }}
                                                </p>
                                            </div>
                                        @endif
                                        @if(!$order->notes && !$order->admin_notes)
                                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                No notes available for this order
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Order History - Only show for online orders --}}
                    @if(!$isInStore)
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Order History
                        </h3>
                        
                        <div class="space-y-3">
                            @forelse($order->history->sortByDesc('created_at') as $history)
                                <div class="flex items-start space-x-4 p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="w-3 h-3 mt-2 bg-indigo-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    Status changed from 
                                                    <span class="text-gray-600 dark:text-gray-400 font-medium">{{ $history->old_status ?? 'N/A' }}</span> 
                                                    to 
                                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $history->new_status }}</span>
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $history->created_at->format('M j, Y \\a\\t g:i A') }}
                                                </p>
                                            </div>
                                            @if($history->user)
                                                <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-600 px-2 py-1 rounded-full border">
                                                    by {{ $history->user->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-lg">No history available for this order</p>
                                    <p class="text-sm mt-1">Status changes will appear here</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>