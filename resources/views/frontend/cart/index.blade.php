<x-landing-layout>
    <x-landing-navbar />

    <!-- Cart Header -->
    <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Your Cart</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Review your selected medical products
                </p>
            </div>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if(count($items) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <!-- Cart Items List -->
                        @foreach($items as $item)
                            <div class="flex flex-col sm:flex-row gap-6 py-6 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700' : '' }}">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <a href="{{ route('products.show', $item['product_slug']) }}" 
                                    class="block w-24 h-24 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                        @if($item['product_image'])
                                        <img src="{{ $item['product_image'] }}" 
                                            alt="{{ $item['product_name'] }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </a>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <a href="{{ route('products.show', $item['product_slug']) }}" 
                                            class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                {{ $item['product_name'] }}
                                            </a>
                                            
                                            <!-- Options if any -->
                                            @if(!empty($item['options']))
                                            <div class="mt-2">
                                                @foreach($item['options'] as $option => $value)
                                                <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                                    {{ $option }}: {{ $value }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif
                                            
                                            <!-- Vendor Info -->
                                            @if($item['product']->vendor)
                                            <div class="mt-1">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    Sold by: {{ $item['product']->vendor->name ?? 'Unknown Vendor' }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Price -->
                                        <div class="text-right">
                                            <div class="text-xl font-bold text-gray-900 dark:text-white">
                                                @if($baseCurrency === 'KWD')
                                                    {{ number_format($item['total'], 3) }}
                                                @else
                                                    {{ number_format($item['total'], 2) }}
                                                @endif
                                                {{ $currencySymbol }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                @if($baseCurrency === 'KWD')
                                                    {{ number_format($item['price'], 3) }}
                                                @else
                                                    {{ number_format($item['price'], 2) }}
                                                @endif
                                                {{ $currencySymbol }} each
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center">
                                                <button onclick="updateQuantity('{{ $item['id'] }}', -1)" 
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 dark:border-gray-600 rounded-l-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                <input type="number" 
                                                    id="quantity-{{ $item['id'] }}"
                                                    value="{{ $item['quantity'] }}" 
                                                    min="1" 
                                                    class="w-12 h-8 text-center border-t border-b border-gray-300 dark:border-gray-600 bg-transparent focus:outline-none"
                                                    onchange="updateQuantityInput('{{ $item['id'] }}', this.value)">
                                                <button onclick="updateQuantity('{{ $item['id'] }}', 1)" 
                                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 dark:border-gray-600 rounded-r-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Remove Button -->
                                        <button onclick="removeItem('{{ $item['id'] }}')" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center gap-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="text-sm font-medium">Remove</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Cart Actions -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('products.index') }}" 
                               class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span>Continue Shopping</span>
                            </a>
                            
                            <button onclick="clearCart()" 
                                    class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-300 rounded-lg font-medium transition-colors">
                                Clear Cart
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>
                        
                        <!-- Summary Details -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($subtotal, 3) }}
                                    @else
                                        {{ number_format($subtotal, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            
                            @if($tax > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tax (5%)</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($tax, 3) }}
                                    @else
                                        {{ number_format($tax, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            @endif
                            
                            @if($shipping > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($shipping, 3) }}
                                    @else
                                        {{ number_format($shipping, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            @endif
                            
                            @if($discount > 0)
                            <div class="flex justify-between text-green-600 dark:text-green-400">
                                <span>Discount</span>
                                <span class="font-medium">
                                    -
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($discount, 3) }}
                                    @else
                                        {{ number_format($discount, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Total -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700 mb-6">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($total, 3) }}
                                    @else
                                        {{ number_format($total, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Including all taxes and shipping
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <a href="{{ route('checkout.index') }}" 
                           class="block w-full py-4 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-xl font-bold text-center transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl mb-4">
                            Proceed to Checkout
                        </a>
                        
                        <!-- Direct Purchase Button -->
                        <form action="{{ route('checkout.direct-purchase') }}" method="POST">
                            @csrf
                            <input type="hidden" name="direct_checkout" value="1">
                            @foreach($items as $key => $item)
                            <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $item['product_id'] }}">
                            <input type="hidden" name="products[{{ $key }}][quantity]" value="{{ $item['quantity'] }}">
                            @endforeach
                            <button type="submit" 
                                    class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl font-bold transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <span>Buy Now</span>
                            </button>
                        </form>
                        
                        <!-- Payment Methods -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">We accept:</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-6 bg-blue-100 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-blue-600">COD</span>
                                </div>
                                <div class="w-10 h-6 bg-purple-100 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-purple-600">CC</span>
                                </div>
                                <div class="w-10 h-6 bg-green-100 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-green-600">PP</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty Cart State -->
            <div class="max-w-md mx-auto text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Your cart is empty</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    Looks like you haven't added any products to your cart yet.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-8 py-3 rounded-lg font-medium transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                    Start Shopping
                </a>
            </div>
            @endif
        </div>
    </section>

    <x-landing-footer />

    <script>
        // Update quantity
        function updateQuantity(itemId, change) {
            const input = document.getElementById(`quantity-${itemId}`);
            let current = parseInt(input.value);
            
            current += change;
            if (current < 1) current = 1;
            
            updateQuantityInput(itemId, current);
        }

        function updateQuantityInput(itemId, quantity) {
            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    quantity: parseInt(quantity)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update cart', 'error');
            });
        }

        function removeItem(itemId) {
            if (!confirm('Are you sure you want to remove this item?')) return;
            
            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    item_id: itemId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to remove item', 'error');
            });
        }

        // Clear cart
        function clearCart() {
            if (!confirm('Are you sure you want to clear your cart?')) return;
            
            fetch('{{ route("cart.clear") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Cart cleared', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Failed to clear cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to clear cart', 'error');
            });
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `
                fixed top-6 right-6 px-6 py-4 rounded-xl shadow-2xl z-50 
                transform transition-all duration-500 translate-x-full
                ${type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' 
                    : 'bg-gradient-to-r from-red-500 to-pink-500 text-white'
                }
            `;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    ${type === 'success' 
                        ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                        : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    }
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
    </script>
</x-landing-layout>