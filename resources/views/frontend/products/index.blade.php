<x-landing-layout>
    <x-landing-navbar />

    <!-- Products Header -->
    <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Medical Products Catalog
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">
                        Browse our comprehensive collection of medical supplies
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <form method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search products..." 
                               class="w-full md:w-64 px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg sticky top-24">
                        <!-- Categories -->
                        <div class="mb-8">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Categories</h3>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}" 
                                   class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ !request('category') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    <span>All Categories</span>
                                    <span class="text-sm">{{ $categories->sum('products_count') }}</span>
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                   class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('category') == $category->slug ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-sm">{{ $category->products_count }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-8">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Price Range</h3>
                            <form method="GET">
                                <div class="flex gap-3 mb-4">
                                    <input type="number" 
                                           name="min_price" 
                                           value="{{ request('min_price') }}"
                                           placeholder="Min" 
                                           class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="self-center text-gray-500">to</span>
                                    <input type="number" 
                                           name="max_price" 
                                           value="{{ request('max_price') }}"
                                           placeholder="Max" 
                                           class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                                    Apply Filter
                                </button>
                            </form>
                        </div>

                        <!-- Brands -->
                        <div class="mb-8">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Brands</h3>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($brands as $brand)
                                <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" 
                                   class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('brand') == $brand->slug ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    <div class="flex items-center gap-3">
                                        @if($brand->logo)
                                        <img src="{{ Storage::url($brand->logo) }}" 
                                             alt="{{ $brand->name }}"
                                             class="w-6 h-6 object-cover rounded-full">
                                        @endif
                                        <span>{{ $brand->name }}</span>
                                    </div>
                                    <span class="text-sm">{{ $brand->products_count }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div class="mb-8">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Stock Status</h3>
                            <div class="space-y-2">
                                <a href="{{ route('products.index', request()->except('stock')) }}" 
                                   class="block py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ !request('stock') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    All Products
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->except('stock'), ['stock' => 'in_stock'])) }}" 
                                   class="block py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('stock') == 'in_stock' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    In Stock
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->except('stock'), ['stock' => 'out_of_stock'])) }}" 
                                   class="block py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('stock') == 'out_of_stock' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                    Out of Stock
                                </a>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        @if(request()->anyFilled(['search', 'category', 'brand', 'min_price', 'max_price', 'stock']))
                        <a href="{{ route('products.index') }}" 
                           class="block w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-300 rounded-lg font-medium text-center transition-colors">
                            Clear All Filters
                        </a>
                        @endif
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="lg:w-3/4">
                    <!-- Sort Bar -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                        <div class="text-gray-600 dark:text-gray-400">
                            Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-600 dark:text-gray-400">Sort by:</span>
                            <select onchange="window.location.href = this.value" 
                                    class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" 
                                        {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>
                                    Latest
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                                        {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Price: Low to High
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                                        {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                    Price: High to Low
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name_asc'])) }}"
                                        {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                    Name: A-Z
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name_desc'])) }}"
                                        {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                    Name: Z-A
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'featured'])) }}"
                                        {{ request('sort') == 'featured' ? 'selected' : '' }}>
                                    Featured
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                        @php
                            $productCurrency = $product->vendor?->currency_code ?? 'KWD';
                            $productSymbol = $currencySymbols[$productCurrency] ?? $productCurrency;
                            $productDecimals = $productCurrency === 'KWD' ? 3 : 2;
                            $mainImage = $product->mainImage()->first();
                            $allImages = collect();
                            if ($product->mainImage()->exists()) {
                                $allImages->push($product->mainImage()->first());
                            }
                            $allImages = $allImages->merge($product->galleryImages);
                        @endphp
                        
                        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                            <!-- Product Image -->
                            <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-700">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($allImages->count())
                                        <img src="{{ Storage::url($allImages->first()->file_path) }}" 
                                             alt="{{ $product->translate('title') ?? 'Untitled' }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-20 h-20 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                
                                <!-- Badges -->
                                <div class="absolute top-4 left-4 flex flex-col gap-2">
                                    @if($product->is_featured)
                                    <span class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                                        Featured
                                    </span>
                                    @endif
                                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                    <span class="inline-block bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                                        Save {{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%
                                    </span>
                                    @endif
                                </div>
                                
                                @if($product->stock_status === 'out_of_stock')
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-block bg-gray-800/90 text-white text-xs font-bold px-3 py-1.5 rounded-full backdrop-blur-sm">
                                            Out of Stock
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Quick Actions -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center gap-3">
                                    <button onclick="addToCart({{ $product->id }})" 
                                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-blue-500 hover:text-white shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-blue-500 hover:text-white shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <button onclick="quickPurchase({{ $product->id }})" 
                                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-150 w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white shadow-lg hover:from-green-600 hover:to-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="p-5">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($product->brand)
                                        <div class="flex items-center gap-2 mb-2">
                                            @if($product->brand->logo)
                                                <img src="{{ Storage::url($product->brand->logo) }}" 
                                                     alt="{{ $product->brand->name }}"
                                                     class="w-5 h-5 object-contain rounded-full">
                                            @endif
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $product->brand->name }}</span>
                                        </div>
                                    @endif
                                    
                                    <h3 class="font-bold text-gray-900 dark:text-white text-base mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                        {{ $product->translate('title') ?? 'Untitled' }}
                                    </h3>
                                </a>
                                
                                <!-- Price -->
                                <div class="flex items-center justify-between mt-4">
                                    <div>
                                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ $productSymbol }}{{ number_format($product->price, $productDecimals) }}
                                        </span>
                                        @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                            <span class="text-sm text-gray-500 dark:text-gray-400 line-through ml-2">
                                                {{ $productSymbol }}{{ number_format($product->compare_at_price, $productDecimals) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($product->stock_status === 'in_stock')
                                        <button onclick="addToCart({{ $product->id }})" 
                                                class="w-10 h-10 flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-full transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Out of Stock</span>
                                    @endif
                                </div>
                                
                                <!-- Direct Purchase Button -->
                                @if($product->stock_status === 'in_stock')
                                <form action="{{ route('checkout.direct-purchase') }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="w-full py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg font-medium transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span>Buy Now</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="mt-12">
                        {{ $products->withQueryString()->links() }}
                    </div>
                    @endif

                    @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No products found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            Try adjusting your search or filter to find what you're looking for.
                        </p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Clear Filters
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <x-landing-footer />

    <script>
        // Enhanced add to cart function
        function addToCart(productId, quantity = 1) {
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;
            
            // Add loading state
            button.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            button.disabled = true;
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count in navbar
                    const cartCount = document.querySelector('[data-cart-count]');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                        cartCount.classList.add('animate-ping');
                        setTimeout(() => cartCount.classList.remove('animate-ping'), 600);
                    }
                    
                    // Show success notification
                    showNotification('✓ Added to cart', 'success');
                    
                    // Animate button
                    button.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    `;
                    button.classList.add('bg-green-500', 'from-green-500', 'to-green-600');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('bg-green-500', 'from-green-500', 'to-green-600');
                        button.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Failed to add product');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add product', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }

        // Quick purchase function (direct checkout)
        function quickPurchase(productId, quantity = 1) {
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;
            
            // Add loading state
            button.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            `;
            button.disabled = true;
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("checkout.direct-purchase") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_id';
            productInput.value = productId;
            
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = quantity;
            
            form.appendChild(csrfToken);
            form.appendChild(productInput);
            form.appendChild(quantityInput);
            document.body.appendChild(form);
            form.submit();
        }

        // Notification function
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
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);
            
            // Remove after delay
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
    </script>
</x-landing-layout>