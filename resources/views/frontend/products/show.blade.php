<x-landing-layout>
    <x-landing-navbar />

    <!-- Product Detail -->
    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <div class="mb-8">
                <nav class="flex text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('products.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Products</a>
                    @if($product->categories->count())
                        <span class="mx-2">/</span>
                        <a href="{{ route('products.index', ['category' => $product->categories->first()->slug]) }}" 
                           class="hover:text-blue-600 dark:hover:text-blue-400">
                            {{ $product->categories->first()->name }}
                        </a>
                    @endif
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 dark:text-white">{{ $product->translate('title') ?? 'Untitled' }}</span>
                </nav>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Images -->
                <div>
                    <!-- Main Image -->
                    <div class="rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 mb-6">
                        @if($product->mainImage()->exists())
                            <img id="mainImage" 
                                 src="{{ Storage::url($product->mainImage()->first()->file_path) }}" 
                                 alt="{{ $product->translate('title') ?? 'Untitled' }}"
                                 class="w-full h-auto object-cover cursor-zoom-in"
                                 data-zoom="{{ Storage::url($product->mainImage()->first()->file_path) }}">
                        @else
                            <div class="w-full h-96 flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if($product->documents->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-4">
                        @foreach($product->documents as $image)
                        <button onclick="changeMainImage('{{ Storage::url($image->file_path) }}')"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 focus:outline-none focus:border-blue-500">
                            <img src="{{ Storage::url($image->file_path) }}" 
                                 alt="{{ $product->translate('title') ?? 'Untitled' }}"
                                 class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <!-- Brand & Vendor -->
                    <div class="flex items-center gap-4 mb-6">
                        @if($product->brand)
                        <div class="flex items-center gap-2">
                            @if($product->brand->logo)
                            <img src="{{ Storage::url($product->brand->logo) }}" 
                                 alt="{{ $product->brand->name }}"
                                 class="w-8 h-8 object-cover rounded-full">
                            @endif
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $product->brand->name }}</span>
                        </div>
                        @endif
                        
                        @if($product->vendor)
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Sold by</span>
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ $product->vendor->name }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $product->translate('title') ?? 'Untitled' }}
                    </h1>

                    <!-- SKU -->
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        SKU: <span class="font-medium">{{ $product->sku }}</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ $productSymbol }}{{ number_format($product->price, $productDecimals) }}
                            </span>
                            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                <span class="text-2xl text-gray-500 dark:text-gray-400 line-through">
                                    {{ $productSymbol }}{{ number_format($product->compare_at_price, $productDecimals) }}
                                </span>
                                <span class="px-3 py-1 bg-red-500 text-white text-sm font-bold rounded-full">
                                    Save {{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-8">
                        @if($product->stock_status === 'in_stock')
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">In Stock</span>
                                @if($product->track_stock && $product->stock_quantity > 0)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        ({{ $product->stock_quantity }} available)
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Out of Stock</span>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Description</h3>
                        <div class="prose prose-lg dark:prose-invert max-w-none">
                              {!! $product->translate('description') ?? '<p class="text-gray-500 italic">No description available.</p>' !!}
                        </div>
                    </div>

                    <!-- Product Options (if any) -->
                    @if($product->has_options && $product->options->count())
                    <div class="mb-8">
                        @foreach($product->options as $option)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ $option->name }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($option->values as $value)
                                <button type="button"
                                        class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-400 dark:hover:text-blue-400 focus:outline-none focus:border-blue-500 focus:text-blue-600">
                                    {{ $value->value }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Quantity & Actions -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Quantity Selector -->
                            <div class="flex items-center">
                                <button onclick="updateQuantity(-1)" 
                                        class="w-12 h-12 flex items-center justify-center border border-gray-300 dark:border-gray-600 rounded-l-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                       id="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->track_stock ? $product->stock_quantity : 999 }}"
                                       class="w-16 h-12 text-center border-t border-b border-gray-300 dark:border-gray-600 bg-transparent focus:outline-none">
                                <button onclick="updateQuantity(1)" 
                                        class="w-12 h-12 flex items-center justify-center border border-gray-300 dark:border-gray-600 rounded-r-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex-1 flex flex-col sm:flex-row gap-4">
                                @if($product->stock_status === 'in_stock')
                                <button onclick="addToCart({{ $product->id }})" 
                                        class="flex-1 px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span>Add to Cart</span>
                                </button>

                                <form action="{{ route('checkout.direct-purchase') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" id="directQuantity" name="quantity" value="1">
                                    <button type="submit" 
                                            class="w-full px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span>Buy Now</span>
                                    </button>
                                </form>
                                @else
                                <button disabled 
                                        class="flex-1 px-8 py-3 bg-gray-400 dark:bg-gray-600 text-white rounded-lg font-semibold cursor-not-allowed">
                                    Out of Stock
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Categories</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                       class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm rounded-full hover:bg-blue-200 dark:hover:bg-blue-800/50">
                                        {{ $category->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @if($product->vendor)
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Vendor</h4>
                                <a href="{{ route('vendors.show', $product->vendor->slug) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $product->vendor->name }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count())
    <section class="py-16 bg-gray-50 dark:bg-gray-900/50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                @php
                    $relatedCurrency = $relatedProduct->vendor?->currency_code ?? 'KWD';
                    $relatedSymbol = $currencySymbols[$relatedCurrency] ?? $relatedCurrency;
                    $relatedDecimals = $relatedCurrency === 'KWD' ? 3 : 2;
                    $relatedImages = collect();
                    if ($relatedProduct->mainImage()->exists()) {
                        $relatedImages->push($relatedProduct->mainImage()->first());
                    }
                    $relatedImages = $relatedImages->merge($relatedProduct->galleryImages);
                @endphp
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                        <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-gray-700">
                            @if($relatedImages->count())
                                <img src="{{ Storage::url($relatedImages->first()->file_path) }}" 
                                     alt="{{ $relatedProduct->translate('title') ?? 'Untitled' }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-2 line-clamp-2">
                                {{ $relatedProduct->translate('title') ?? 'Untitled' }}
                            </h3>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $relatedSymbol }}{{ number_format($relatedProduct->price, $relatedDecimals) }}
                                </span>
                                @if($relatedProduct->stock_status === 'in_stock')
                                <button onclick="addToCart({{ $relatedProduct->id }})" 
                                        class="w-8 h-8 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <x-landing-footer />

    <script>
        // Quantity update
        function updateQuantity(change) {
            const input = document.getElementById('quantity');
            const directInput = document.getElementById('directQuantity');
            let current = parseInt(input.value);
            const max = parseInt(input.max);
            
            current += change;
            if (current < 1) current = 1;
            if (current > max) current = max;
            
            input.value = current;
            if (directInput) {
                directInput.value = current;
            }
        }

        // Change main image
        function changeMainImage(src) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = src;
            mainImage.dataset.zoom = src;
            
            // Update active thumbnail
            document.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('border-blue-500');
            });
            event.currentTarget.classList.add('border-blue-500');
        }

        // Add to cart with quantity
        function addToCart(productId) {
            const quantity = document.getElementById('quantity')?.value || 1;
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: parseInt(quantity)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartCount = document.querySelector('[data-cart-count]');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                        cartCount.classList.add('animate-ping');
                        setTimeout(() => cartCount.classList.remove('animate-ping'), 600);
                    }
                    
                    showNotification('✓ Added to cart', 'success');
                } else {
                    showNotification(data.message || 'Failed to add product', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add product', 'error');
            });
        }

        // Image zoom
        document.getElementById('mainImage')?.addEventListener('click', function() {
            const zoomSrc = this.dataset.zoom;
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full">
                    <img src="${zoomSrc}" alt="Product Image" class="w-full h-auto">
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="absolute top-4 right-4 w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
        });

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