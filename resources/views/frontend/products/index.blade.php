<x-landing-layout>
    <x-landing-navbar />

    <main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10" data-aos="fade-up">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $pageTitle ?? __('All Products') }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    {{ __("Discover our curated collection of premium fashion items") }}
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:w-1/4" data-aos="fade-right">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                            {{ __("Filters") }}
                        </h3>

                        <!-- Search -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __("Search") }}
                            </label>
                            <form action="{{ route('products.index') }}" method="GET">
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="{{ __('Search products...') }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    <button type="submit" 
                                            class="absolute right-3 top-3 text-gray-400 hover:text-rose-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Categories -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ __("Categories") }}</h4>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}" 
                                   class="block py-2 px-3 rounded-lg text-sm {{ !request('category') ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __("All Categories") }}
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                   class="block py-2 px-3 rounded-lg text-sm flex justify-between items-center {{ request('category') == $category->slug ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-xs bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-full">
                                        {{ $category->products_count }}
                                    </span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brands -->
                        @if($brands->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ __("Brands") }}</h4>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}" 
                                   class="block py-2 px-3 rounded-lg text-sm {{ !request('brand') ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __("All Brands") }}
                                </a>
                                @foreach($brands as $brand)
                                <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" 
                                   class="block py-2 px-3 rounded-lg text-sm flex justify-between items-center {{ request('brand') == $brand->slug ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    <span>{{ $brand->name }}</span>
                                    <span class="text-xs bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-full">
                                        {{ $brand->products_count }}
                                    </span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ __("Price Range") }}</h4>
                            <form action="{{ route('products.index') }}" method="GET" class="space-y-3">
                                <div class="flex gap-3">
                                    <input type="number" 
                                           name="min_price" 
                                           value="{{ request('min_price') }}"
                                           placeholder="Min"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                                    <input type="number" 
                                           name="max_price" 
                                           value="{{ request('max_price') }}"
                                           placeholder="Max"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                                </div>
                                <button type="submit" 
                                        class="w-full py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-medium rounded-lg transition-all duration-300">
                                    {{ __("Apply") }}
                                </button>
                            </form>
                        </div>

                        <!-- Stock Status -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ __("Availability") }}</h4>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}" 
                                   class="block py-2 px-3 rounded-lg text-sm {{ !request('stock') ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __("All") }}
                                </a>
                                <a href="{{ route('products.index', ['stock' => 'in_stock']) }}" 
                                   class="block py-2 px-3 rounded-lg text-sm {{ request('stock') == 'in_stock' ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __("In Stock") }}
                                </a>
                                <a href="{{ route('products.index', ['stock' => 'out_of_stock']) }}" 
                                   class="block py-2 px-3 rounded-lg text-sm {{ request('stock') == 'out_of_stock' ? 'bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __("Out of Stock") }}
                                </a>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        @if(request()->anyFilled(['search', 'category', 'brand', 'min_price', 'max_price', 'stock']))
                        <div>
                            <a href="{{ route('products.index') }}" 
                               class="block w-full py-3 text-center text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-300">
                                {{ __("Clear All Filters") }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="lg:w-3/4" data-aos="fade-left">
                    <!-- Sort and Results Header -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ __("Showing") }} <span class="font-semibold text-rose-600 dark:text-rose-400">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> 
                                    {{ __("of") }} <span class="font-semibold text-rose-600 dark:text-rose-400">{{ $products->total() }}</span> {{ __("products") }}
                                </p>
                            </div>
                            <div>
                                <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-3">
                                    @foreach(request()->except('sort') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="sort" 
                                            onchange="this.form.submit()"
                                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __("Latest Arrivals") }}</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __("Price: Low to High") }}</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __("Price: High to Low") }}</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __("Name: A-Z") }}</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __("Name: Z-A") }}</option>
                                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>{{ __("Featured") }}</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700">
                            <!-- Product Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if($product->mainImage)
                                    <img src="{{ asset('storage/' . $product->mainImage->first()->file_path) }}" 
                                         alt="{{ $product->translate('title') }}"
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Quick Actions -->
                                <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button class="w-10 h-10 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110 group/heart">
                                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 group-hover/heart:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Stock Badge -->
                                <div class="absolute bottom-4 left-4">
                                    @if($product->stock_status === 'in_stock')
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                            {{ __("In Stock") }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                            {{ __("Out of Stock") }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-6">
                                <!-- Brand -->
                                @if($product->brand)
                                    <div class="text-xs font-semibold text-rose-500 dark:text-rose-400 uppercase tracking-wider mb-2">
                                        {{ $product->brand->name }}
                                    </div>
                                @endif

                                <!-- Title -->
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-3 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->translate('title') }}
                                    </a>
                                </h3>

                                <!-- Price -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $currencySymbol }}{{ number_format($product->price, 2) }}
                                        </span>
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                            <span class="text-sm text-gray-500 line-through">
                                                {{ $currencySymbol }}{{ number_format($product->compare_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Add to Cart Button -->
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="block w-full py-3 text-center bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 group/btn">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        {{ __("View Details") }}
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="mt-10">
                        {{ $products->links('frontend.pagination.custom') }}
                    </div>
                    @endif
                    @else
                    <!-- No Products Found -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/20 dark:to-pink-900/20 flex items-center justify-center">
                            <svg class="w-12 h-12 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ __("No Products Found") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __("Try adjusting your filters or search term") }}
                        </p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                            {{ __("View All Products") }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-landing-footer />

    <script>
        // Add to wishlist functionality
        document.addEventListener('DOMContentLoaded', function() {
            const wishlistButtons = document.querySelectorAll('.group/heart');
            
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const heartIcon = this.querySelector('svg');
                    heartIcon.classList.toggle('text-rose-500');
                    heartIcon.classList.toggle('fill-rose-500');
                    
                    // Show notification
                    showNotification('Product added to wishlist!');
                });
            });
            
            function showNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 z-50 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 border-rose-500 transform translate-x-full transition-transform duration-300';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-rose-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
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
        });
    </script>
</x-landing-layout>