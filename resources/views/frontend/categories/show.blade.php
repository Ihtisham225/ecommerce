<x-landing-layout>
    <x-landing-navbar />

    <main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8" aria-label="Breadcrumb" data-aos="fade-up">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                            {{ __("Home") }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('categories.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                            {{ __("Categories") }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-900 dark:text-white font-medium truncate">
                            {{ $category->localized_name }}
                        </span>
                    </li>
                </ol>
            </nav>

            <!-- Category Header -->
            <div class="bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-8 mb-10" data-aos="fade-up">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <!-- Category Icon -->
                    <div class="flex-shrink-0">
                        @php
                            // Use the same icon logic from categories.index
                            $iconSets = [
                                [
                                    'name' => 'clothing',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                                    'color' => 'text-rose-600 dark:text-rose-400',
                                    'bg' => 'from-rose-500 to-pink-500',
                                    'keywords' => ['shirt', 'top', 't-shirt', 'blouse', 'sweater', 'hoodie', 'jacket']
                                ],
                                [
                                    'name' => 'bags',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                                    'color' => 'text-amber-600 dark:text-amber-400',
                                    'bg' => 'from-amber-500 to-orange-500',
                                    'keywords' => ['bag', 'purse', 'handbag', 'backpack', 'tote', 'clutch']
                                ],
                                [
                                    'name' => 'pants',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                                    'color' => 'text-blue-600 dark:text-blue-400',
                                    'bg' => 'from-blue-500 to-indigo-500',
                                    'keywords' => ['pant', 'jean', 'trouser', 'legging', 'short', 'skirt']
                                ],
                                [
                                    'name' => 'shoes',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>',
                                    'color' => 'text-green-600 dark:text-green-400',
                                    'bg' => 'from-green-500 to-emerald-500',
                                    'keywords' => ['shoe', 'foot', 'sneaker', 'boot', 'sandal', 'heel', 'loafer']
                                ],
                                [
                                    'name' => 'dresses',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                                    'color' => 'text-purple-600 dark:text-purple-400',
                                    'bg' => 'from-purple-500 to-pink-500',
                                    'keywords' => ['dress', 'gown', 'jumpsuit', 'romper', 'kimono']
                                ],
                                [
                                    'name' => 'accessories',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
                                    'color' => 'text-red-600 dark:text-red-400',
                                    'bg' => 'from-red-500 to-pink-500',
                                    'keywords' => ['watch', 'jewelry', 'belt', 'hat', 'scarf', 'sunglass', 'glove']
                                ]
                            ];
                            
                            $categoryName = $category->localized_name ?? $category->name;
                            $selectedIcon = $iconSets[0];
                            $lowerName = strtolower($categoryName);
                            
                            foreach ($iconSets as $iconSet) {
                                foreach ($iconSet['keywords'] as $keyword) {
                                    if (str_contains($lowerName, $keyword)) {
                                        $selectedIcon = $iconSet;
                                        break 2;
                                    }
                                }
                            }
                            
                            if ($selectedIcon['name'] === 'clothing') {
                                $iconIndex = $category->id % count($iconSets);
                                $selectedIcon = $iconSets[$iconIndex];
                            }
                        @endphp
                        
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br {{ $selectedIcon['bg'] }} shadow-xl flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $selectedIcon['icon'] !!}
                            </svg>
                        </div>
                    </div>

                    <!-- Category Info -->
                    <div class="flex-1 text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $category->localized_name }}
                        </h1>
                        
                        @if($category->localized_description)
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 max-w-3xl">
                            {{ $category->localized_description }}
                        </p>
                        @endif

                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <span class="font-bold text-2xl">{{ $products->total() }}</span> {{ __("Products") }}
                                </span>
                            </div>
                            
                            @if($category->parent)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ __("Parent:") }}
                                    <a href="{{ route('categories.show', $category->parent->slug) }}" 
                                       class="font-medium text-rose-600 dark:text-rose-400 hover:underline">
                                        {{ $category->parent->localized_name }}
                                    </a>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="mb-16">
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8" data-aos="fade-up">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ __("Products in this Category") }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __("Browse our collection of") }} {{ $category->localized_name }}
                        </p>
                    </div>

                    <!-- Sort and Search -->
                    <div class="flex items-center gap-4 mt-4 md:mt-0">
                        <!-- Search Form -->
                        <form action="{{ route('categories.show', $category->slug) }}" method="GET" class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('Search products...') }}"
                                   class="px-4 py-2 pl-10 pr-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent w-full md:w-64 transition-all duration-300">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            @if(request('search'))
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                            @endif
                        </form>

                        <!-- Sort Dropdown -->
                        <form action="{{ route('categories.show', $category->slug) }}" method="GET" class="flex items-center">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" 
                                    onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __("Latest") }}</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{ __("Title: A-Z") }}</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{ __("Title: Z-A") }}</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __("Price: Low to High") }}</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __("Price: High to Low") }}</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" data-aos="fade-up">
                    @foreach($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 border border-gray-200 dark:border-gray-700">
                        
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
                            
                            <!-- Sale Badge -->
                            @if($product->discount_price && $product->discount_price < $product->price)
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    {{ __("SALE") }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Category Badge -->
                            @if($product->categories->count() > 0)
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-black/60 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                                    {{ $product->categories->first()->localized_name }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-3 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                                {{ $product->translate('title') }}
                            </h3>
                            
                            <!-- Price -->
                            <div class="flex items-center gap-2 mb-3">
                                @if($product->discount_price && $product->discount_price < $product->price)
                                <span class="text-2xl font-bold text-rose-600 dark:text-rose-400">
                                    {{ config('app.currency_symbol') }}{{ number_format($product->discount_price, 2) }}
                                </span>
                                <span class="text-lg text-gray-500 dark:text-gray-400 line-through">
                                    {{ config('app.currency_symbol') }}{{ number_format($product->price, 2) }}
                                </span>
                                @else
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ config('app.currency_symbol') }}{{ number_format($product->price, 2) }}
                                </span>
                                @endif
                            </div>
                            
                            <!-- Short Description -->
                            @if($product->short_description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $product->translate('short_description') }}
                            </p>
                            @endif
                            
                            <!-- View Product Button -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-rose-600 dark:text-rose-400 font-medium group/link">
                                    <span class="mr-2">{{ __("View Details") }}</span>
                                    <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </div>
                                
                                <!-- Quick Add to Cart -->
                                <button type="button" 
                                        onclick="event.preventDefault(); event.stopPropagation();"
                                        class="p-2 rounded-full bg-rose-50 dark:bg-gray-700 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-gray-600 transition-colors group/cart">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="mt-10">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
                @endif

                <!-- No Products Found -->
                @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center" data-aos="fade-up">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/20 dark:to-pink-900/20 flex items-center justify-center">
                        <svg class="w-12 h-12 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                        {{ __("No Products Found") }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        {{ __("Try adjusting your search or browse other categories") }}
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('categories.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 group">
                            <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __("Browse Categories") }}
                        </a>
                        @if(request('search'))
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg shadow hover:shadow-md transition-all duration-300">
                            {{ __("Clear Search") }}
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Related Categories -->
            @if($relatedCategories->count() > 0)
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ __("Related Categories") }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __("Explore similar fashion categories") }}
                        </p>
                    </div>
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center text-rose-600 dark:text-rose-400 font-medium hover:text-rose-700 dark:hover:text-rose-300 transition-colors group">
                        {{ __("View All") }}
                        <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6" data-aos="fade-up">
                    @foreach($relatedCategories as $relatedCategory)
                    @php
                        // Reuse icon logic for related categories
                        $categoryName = $relatedCategory->localized_name ?? $relatedCategory->name;
                        $selectedIcon = $iconSets[0];
                        $lowerName = strtolower($categoryName);
                        
                        foreach ($iconSets as $iconSet) {
                            foreach ($iconSet['keywords'] as $keyword) {
                                if (str_contains($lowerName, $keyword)) {
                                    $selectedIcon = $iconSet;
                                    break 2;
                                }
                            }
                        }
                        
                        if ($selectedIcon['name'] === 'clothing') {
                            $iconIndex = $relatedCategory->id % count($iconSets);
                            $selectedIcon = $iconSets[$iconIndex];
                        }
                        
                        // Light background for related categories
                        $bgMap = [
                            'clothing' => 'from-rose-50 to-pink-50 dark:from-gray-700 dark:to-gray-800',
                            'bags' => 'from-amber-50 to-orange-50 dark:from-gray-700 dark:to-gray-800',
                            'pants' => 'from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800',
                            'shoes' => 'from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-800',
                            'dresses' => 'from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-800',
                            'accessories' => 'from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-800',
                        ];
                        $iconBg = $bgMap[$selectedIcon['name']] ?? 'from-rose-50 to-pink-50 dark:from-gray-700 dark:to-gray-800';
                    @endphp
                    
                    <a href="{{ route('categories.show', $relatedCategory->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 border border-gray-200 dark:border-gray-700 text-center">
                        
                        <!-- Category Icon -->
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br {{ $iconBg }} flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 {{ $selectedIcon['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $selectedIcon['icon'] !!}
                            </svg>
                        </div>
                        
                        <!-- Category Name -->
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                            {{ $relatedCategory->localized_name }}
                        </h4>
                        
                        <!-- Product Count -->
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $relatedCategory->products_count }} {{ __("products") }}
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Category Description (if long) -->
            @if($category->long_description && $category->translate('long_description'))
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-10" data-aos="fade-up">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    {{ __("About") }} {{ $category->localized_name }}
                </h3>
                <div class="prose prose-lg dark:prose-invert max-w-none">
                    {!! $category->translate('long_description') !!}
                </div>
            </div>
            @endif

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 rounded-2xl shadow-2xl overflow-hidden" data-aos="fade-up">
                <div class="relative px-8 py-12 md:py-16 text-center">
                    <div class="relative z-10">
                        <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">
                            {{ __("Can't find what you're looking for?") }}
                        </h3>
                        <p class="text-rose-100 text-lg mb-8 max-w-2xl mx-auto">
                            {{ __("Browse our complete collection of fashion items") }}
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center">
                            <a href="{{ route('products.index') }}" 
                               class="inline-flex items-center px-8 py-4 bg-white text-rose-600 font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                {{ __("Browse All Products") }}
                            </a>
                            <a href="{{ route('categories.index') }}" 
                               class="inline-flex items-center px-8 py-4 bg-black/20 backdrop-blur-sm text-white font-bold rounded-lg border-2 border-white/30 hover:bg-white/10 hover:border-white/50 transition-all duration-300 group">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                {{ __("All Categories") }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>