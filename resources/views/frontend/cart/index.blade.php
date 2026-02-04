<x-landing-layout>
    <x-landing-navbar />

    <main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ __("Shopping Cart") }}
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            {{ __("Review your items and proceed to checkout") }}
                        </p>
                    </div>

                    <!-- Cart Stats -->
                    <div class="hidden md:block">
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $item_count }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ __("Items") }}</div>
                            </div>
                            <div class="h-8 w-px bg-gray-300 dark:bg-gray-700"></div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($total, 2) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ __("Total") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($item_count > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2" data-aos="fade-right">
                    <!-- Cart Header -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ __("Your Items") }}
                            </h2>
                            <button onclick="clearCart()"
                                class="text-sm text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __("Clear Cart") }}
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    <div class="space-y-4">
                        @foreach($items as $item)
                        @php
                            // Check if item is an array (from session) or object (from database)
                            $itemId = $item['id'] ?? $item->id ?? '';
                            $product = $item['product'] ?? $item->product ?? null;
                            $quantity = $item['quantity'] ?? $item->quantity ?? 1;
                            $price = $item['price'] ?? $item->price ?? 0;
                            $variantId = $item['variant_id'] ?? $item->variant_id ?? null;
                            $options = $item['options'] ?? $item->options ?? [];
                            
                            // For session cart, product might already be an object
                            // For database cart, product might need to be loaded
                            if ($product && is_array($product)) {
                                $product = (object)$product;
                            }
                            
                            // Get variant if exists
                            $variant = null;
                            $variantImage = null;
                            if ($variantId) {
                                $variant = \App\Models\ProductVariant::with('image')->find($variantId);
                                if ($variant && $variant->image) {
                                    $variantImage = asset('storage/' . $variant->image->file_path);
                                }
                            }
                            
                            // Determine which image to show - variant image first, then product main image
                            $displayImage = $variantImage;
                            if (!$displayImage && $product && method_exists($product, 'mainImage') && $product->mainImage()) {
                                $mainImage = $product->mainImage()->first();
                                if ($mainImage) {
                                    $displayImage = asset('storage/' . $mainImage->file_path);
                                }
                            }
                        @endphp
                        
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700"
                            data-item-id="{{ $itemId }}">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-32 h-32 rounded-xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                            @if($displayImage)
                                            <img src="{{ $displayImage }}"
                                                alt="{{ $product ? ($product->translate('title') ?? ($product->title ?? 'Product')) : 'Product' }}"
                                                class="w-full h-full object-contain">
                                            @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <!-- Brand -->
                                                @if($product && isset($product->brand) && $product->brand)
                                                <div class="text-xs font-semibold text-rose-500 dark:text-rose-400 uppercase tracking-wider mb-1">
                                                    {{ $product->brand->name ?? '' }}
                                                </div>
                                                @endif

                                                <!-- Title -->
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                                    <a href="{{ $product ? route('products.show', $product->slug ?? '#') : '#' }}">
                                                        {{ $product ? ($product->translate('title') ?? ($product->title ?? __('Product not available'))) : __('Product not available') }}
                                                    </a>
                                                </h3>

                                                <!-- Variant Details -->
                                                @if($variant)
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                    <div class="font-medium mb-1">{{ $variant->title ?? 'Variant' }}</div>
                                                    @if($variant->options && is_array($variant->options))
                                                        @foreach($variant->options as $optionName => $optionValue)
                                                            <span class="inline-block mr-3 mb-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">
                                                                <strong>{{ $optionName }}:</strong> {{ $optionValue }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                    @if($variant->sku)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            SKU: {{ $variant->sku }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @elseif($options && is_array($options) && count($options) > 0)
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                    @foreach($options as $optionName => $optionValue)
                                                        <span class="inline-block mr-3 mb-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">
                                                            <strong>{{ $optionName }}:</strong> {{ $optionValue }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                                @endif

                                                <!-- Stock Status -->
                                                <div class="flex items-center mb-4">
                                                    @if($variant && isset($variant->stock_quantity))
                                                        @if($variant->stock_quantity > 0)
                                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">
                                                            {{ __("In Stock") }} ({{ $variant->stock_quantity }} {{ __("available") }})
                                                        </span>
                                                        @else
                                                        <span class="px-2 py-1 text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">
                                                            {{ __("Out of Stock") }}
                                                        </span>
                                                        @endif
                                                    @elseif($product && isset($product->stock_status) && $product->stock_status === 'in_stock')
                                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">
                                                        {{ __("In Stock") }}
                                                    </span>
                                                    @else
                                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">
                                                        {{ __("Out of Stock") }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Price and Actions -->
                                            <div class="flex flex-col items-end gap-4">
                                                <!-- Price -->
                                                <div class="text-right">
                                                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                                        {{ $currencySymbol }}{{ number_format($price * $quantity, 2) }}
                                                    </div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $currencySymbol }}{{ number_format($price, 2) }} {{ __("each") }}
                                                    </div>
                                                    @if($variant && $variant->compare_at_price && $variant->compare_at_price > $price)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                                        {{ $currencySymbol }}{{ number_format($variant->compare_at_price * $quantity, 2) }}
                                                    </div>
                                                    @endif
                                                </div>

                                                <!-- Quantity Controls -->
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700">
                                                        <button type="button"
                                                            onclick="updateQuantity('{{ $itemId }}', {{ $quantity - 1 }})"
                                                            class="w-10 h-10 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                        <input type="number"
                                                            id="quantity-{{ $itemId }}"
                                                            value="{{ $quantity }}"
                                                            min="1"
                                                            max="{{ $variant ? ($variant->stock_quantity ?? 99) : ($product && isset($product->stock_quantity) ? $product->stock_quantity : 99) }}"
                                                            onchange="updateQuantity('{{ $itemId }}', this.value)"
                                                            class="w-16 h-10 text-center bg-transparent text-gray-900 dark:text-white focus:outline-none">
                                                        <button type="button"
                                                            onclick="updateQuantity('{{ $itemId }}', {{ $quantity + 1 }})"
                                                            class="w-10 h-10 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <button type="button"
                                                        onclick="removeItem('{{ $itemId }}')"
                                                        class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1" data-aos="fade-left">
                    <div class="sticky top-24">
                        <!-- Summary Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                                {{ __("Order Summary") }}
                            </h2>

                            <!-- Summary Details -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __("Subtotal") }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                                </div>

                                @if($shipping > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __("Shipping") }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($shipping, 2) }}</span>
                                </div>
                                @endif

                                @if($tax > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __("Tax") }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($tax, 2) }}</span>
                                </div>
                                @endif

                                @if($discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-green-600 dark:text-green-400">{{ __("Discount") }}</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">-{{ $currencySymbol }}{{ number_format($discount, 2) }}</span>
                                </div>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ __("Total") }}</span>
                                        <span class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $currencySymbol }}{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Promo Code -->
                            <div class="mb-6">
                                <button onclick="togglePromoCode()"
                                    class="w-full flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors mb-3">
                                    <span>{{ __("Have a promo code?") }}</span>
                                    <svg class="w-4 h-4 transition-transform" id="promo-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="promo-code-form" class="hidden">
                                    <form class="flex gap-2">
                                        <input type="text"
                                            placeholder="{{ __('Enter promo code') }}"
                                            class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                                        <button type="submit"
                                            class="px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-medium rounded-lg transition-all duration-300">
                                            {{ __("Apply") }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <a href="{{ route('checkout.index') }}"
                                class="block w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 text-center group">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    {{ __("Proceed to Checkout") }}
                                </div>
                            </a>

                            <!-- Security Notice -->
                            <div class="mt-4 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    {{ __("Secure SSL encryption") }}
                                </p>
                            </div>
                        </div>

                        <!-- Continue Shopping -->
                        <a href="{{ route('products.index') }}"
                            class="block w-full py-4 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-rose-500 dark:hover:border-rose-500 hover:text-rose-600 dark:hover:text-rose-400 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center group">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                </svg>
                                {{ __("Continue Shopping") }}
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Empty Cart State -->
            @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center max-w-2xl mx-auto" data-aos="fade-up">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/20 dark:to-pink-900/20 flex items-center justify-center">
                    <svg class="w-12 h-12 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ __("Your cart is empty") }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __("Looks like you haven't added any items to your cart yet.") }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 group">
                        <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __("Start Shopping") }}
                    </a>
                    <a href="{{ route('products.best-sellers') }}"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-rose-500 dark:hover:border-rose-500 hover:text-rose-600 dark:hover:text-rose-400 font-semibold rounded-lg transition-all duration-300">
                        {{ __("View Best Sellers") }}
                    </a>
                </div>
            </div>
            @endif

            <!-- Dynamic Recommended Products -->
            @if($item_count > 0 && !empty($recommendedProducts))
            <section class="mt-20" data-aos="fade-up">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-8">
                    {{ __("You Might Also Like") }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($recommendedProducts as $recommended)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700">
                        <div class="relative h-48 overflow-hidden">
                            <a href="{{ route('products.show', $recommended['slug']) }}">
                                @if($recommended['main_image'])
                                <img src="{{ $recommended['main_image'] }}" 
                                     alt="{{ $recommended['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                @endif
                            </a>
                            @if($recommended['discount_percentage'] > 0)
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-rose-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                    -{{ $recommended['discount_percentage'] }}%
                                </span>
                            </div>
                            @endif
                            @if(!$recommended['in_stock'])
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-gray-800 text-white text-xs font-semibold rounded-full shadow-lg">
                                    {{ __("Out of Stock") }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if(isset($recommended['brand']) && $recommended['brand'])
                            <div class="text-xs font-semibold text-rose-500 dark:text-rose-400 uppercase tracking-wider mb-1">
                                {{ $recommended['brand'] }}
                            </div>
                            @endif
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                                <a href="{{ route('products.show', $recommended['slug']) }}">
                                    {{ $recommended['title'] }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $currencySymbol }}{{ number_format($recommended['price'], 2) }}
                                    </span>
                                    @if($recommended['compare_at_price'] && $recommended['compare_at_price'] > $recommended['price'])
                                    <span class="text-sm text-gray-500 dark:text-gray-400 line-through">
                                        {{ $currencySymbol }}{{ number_format($recommended['compare_at_price'], 2) }}
                                    </span>
                                    @endif
                                </div>
                                @if($recommended['in_stock'])
                                <button onclick="addToCart('{{ $recommended['id'] }}')"
                                        class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </main>

    <x-landing-footer />

    <script>
        // Toggle promo code form
        function togglePromoCode() {
            const form = document.getElementById('promo-code-form');
            const arrow = document.getElementById('promo-arrow');

            form.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        // Add product to cart
        function addToCart(productId) {
            fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Product added to cart', 'success');
                        updateCartStats(data);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred', 'error');
                    console.error('Error:', error);
                });
        }

        // Update cart item quantity
        function updateQuantity(itemId, quantity) {
            if (quantity < 1) {
                removeItem(itemId);
                return;
            }

            const input = document.getElementById(`quantity-${itemId}`);
            input.value = quantity;

            fetch('{{ route("cart.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Cart updated successfully', 'success');
                        updateCartStats(data);
                        updateItemTotal(itemId, data.cart_total);
                    } else {
                        showNotification(data.message, 'error');
                        // Reset to previous value
                        input.value = parseInt(input.value) - (quantity > parseInt(input.value) ? 1 : -1);
                    }
                })
                .catch(error => {
                    showNotification('An error occurred', 'error');
                    console.error('Error:', error);
                });
        }

        // Remove item from cart
        function removeItem(itemId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Item removed from cart', 'success');
                        document.querySelector(`[data-item-id="${itemId}"]`).remove();
                        updateCartStats(data);

                        // Check if cart is empty
                        if (data.cart_count === 0) {
                            location.reload(); // Reload to show empty cart state
                        }
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred', 'error');
                    console.error('Error:', error);
                });
        }

        // Clear entire cart
        function clearCart() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            fetch('{{ route("cart.clear") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Cart cleared', 'success');
                        location.reload(); // Reload to show empty cart state
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred', 'error');
                    console.error('Error:', error);
                });
        }

        // Update cart stats in header
        function updateCartStats(data) {
            // Update cart count in navbar
            const cartBadges = document.querySelectorAll('.cart-badge, [class*="cart-count"]');
            cartBadges.forEach(badge => {
                badge.textContent = data.cart_count;
                badge.classList.add('animate-pulse');
                setTimeout(() => badge.classList.remove('animate-pulse'), 1000);
            });

            // Update cart total in summary
            const totalElement = document.querySelector('[class*="total-amount"]');
            if (totalElement) {
                totalElement.textContent = `{{ $currencySymbol }}${data.cart_total.toFixed(2)}`;
            }

            // Update item count in header
            const itemCountElement = document.querySelector('[class*="item-count"]');
            if (itemCountElement) {
                itemCountElement.textContent = data.cart_count;
            }
        }

        // Update item total price
        function updateItemTotal(itemId, cartTotal) {
            const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
            if (itemElement) {
                const priceElement = itemElement.querySelector('[class*="item-total"]');
                const unitPrice = parseFloat(priceElement.dataset.unitPrice);
                const quantity = parseInt(document.getElementById(`quantity-${itemId}`).value);
                const total = unitPrice * quantity;

                priceElement.textContent = `{{ $currencySymbol }}${total.toFixed(2)}`;

                // Update cart total
                const cartTotalElement = document.querySelector('[class*="cart-total"]');
                if (cartTotalElement) {
                    cartTotalElement.textContent = `{{ $currencySymbol }}${cartTotal.toFixed(2)}`;
                }
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'border-green-500' : 'border-red-500'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                            type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'
                        }"/>
                    </svg>
                    <span>${message}</span>
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
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize cart interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add input validation for quantity
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('change', function() {
                    const min = parseInt(this.min);
                    const max = parseInt(this.max);
                    let value = parseInt(this.value);

                    if (value < min) value = min;
                    if (value > max) value = max;

                    this.value = value;
                });
            });

            // Add animation to cart items
            const cartItems = document.querySelectorAll('[data-item-id]');
            cartItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #f472b6, #ec4899);
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #db2777, #be185d);
        }

        /* Animation for cart items */
        [data-item-id] {
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Rotate animation */
        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Pulse animation for updates */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .animate-pulse {
            animation: pulse 0.5s ease-in-out;
        }
    </style>
</x-landing-layout>