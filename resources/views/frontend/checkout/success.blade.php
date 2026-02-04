<x-landing-layout>
    <x-landing-navbar />

    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="mb-8">
                    <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Order Confirmed!
                    </h1>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Your order #{{ $order->order_number }} has been received and is being processed.
                    </p>
                    
                    @if(session('success'))
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <h3 class="text-lg font-medium text-green-800 dark:text-green-300">{{ session('success') }}</h3>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Store Information -->
                    @if($storeSettings['store_name'])
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-blue-700 dark:text-blue-300 font-medium">
                                Order from: {{ $storeSettings['store_name'] }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Details</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                            <div>
                                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Order Information</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <strong>Order #:</strong> {{ $order->order_number }}<br>
                                    <strong>Date:</strong> {{ $order->created_at->format('F d, Y H:i') }}<br>
                                    <strong>Status:</strong> 
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 
                                          ($order->status === 'processing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 
                                          ($order->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                          ($order->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 
                                          'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span><br>
                                    <strong>Shipping Method:</strong> {{ $order->shipping_method }}<br>
                                    <strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Customer Information</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <strong>Name:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name }}<br>
                                    <strong>Email:</strong> {{ $order->customer->email }}<br>
                                    <strong>Phone:</strong> {{ $order->customer->phone }}
                                </p>
                                
                                <!-- Shipping Address -->
                                @php
                                    $shippingAddress = $order->addresses->where('type', 'shipping')->first();
                                @endphp
                                @if($shippingAddress)
                                <h4 class="font-medium text-gray-700 dark:text-gray-300 mt-4 mb-2">Shipping Address</h4>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $shippingAddress->address_line_1 }}<br>
                                    @if($shippingAddress->address_line_2)
                                        {{ $shippingAddress->address_line_2 }}<br>
                                    @endif
                                </p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="mt-8">
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-4">Order Items</h3>
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    @if($item->product && $item->product->mainImage())
                                    <div class="w-16 h-16 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $item->product->mainImage()->first()->file_path) }}" 
                                             alt="{{ $item->title }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>
                                    @endif
                                    <div class="flex-1 text-left">
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Quantity: {{ $item->quantity }} × 
                                            {{ number_format($item->price, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ number_format($item->total, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-4">Order Summary</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ number_format($order->subtotal, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                    </span>
                                </div>
                                
                                @if($order->shipping_total > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        Shipping ({{ $order->shipping_method }})
                                    </span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ number_format($order->shipping_total, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                    </span>
                                </div>
                                @endif
                                
                                @if($order->tax_total > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        Tax @if($order->tax_rate > 0)({{ $order->tax_rate }}%)@endif
                                    </span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ number_format($order->tax_total, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                    </span>
                                </div>
                                @endif
                                
                                @if($order->discount_total > 0)
                                <div class="flex justify-between text-green-600 dark:text-green-400">
                                    <span>Discount</span>
                                    <span class="font-medium">
                                        -{{ number_format($order->discount_total, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                    </span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($order->grand_total, $order->currency_code === 'KWD' ? 3 : 2) }} {{ $order->currency_symbol }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Notes -->
                        @if($order->notes)
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-4">Order Notes</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400">{{ $order->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('home') }}" 
                           class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                            Continue Shopping
                        </a>
                        @if(Auth::check())
                        <a href="{{ route('customer.orders.index') }}" 
                           class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                            View All Orders
                        </a>
                        @endif
                        <button onclick="window.print()" 
                                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                            Print Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-landing-footer />
</x-landing-layout>