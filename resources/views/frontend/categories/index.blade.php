<x-landing-layout>
    <x-landing-navbar />

    <main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10" data-aos="fade-up">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __("Shop by Category") }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    {{ __("Browse our curated fashion categories") }}
                </p>
            </div>

            <!-- Search and Sort -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Search Form -->
                    <form action="{{ route('categories.index') }}" method="GET" class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('Search categories...') }}"
                                   class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            @if(request('search'))
                            <a href="{{ route('categories.index') }}" 
                               class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </form>

                    <!-- Sort Options -->
                    <form action="{{ route('categories.index') }}" method="GET" class="flex items-center gap-3">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" 
                                onchange="this.form.submit()"
                                class="px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __("Name: A-Z") }}</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __("Name: Z-A") }}</option>
                            <option value="products_count" {{ request('sort') == 'products_count' ? 'selected' : '' }}>{{ __("Most Products") }}</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Categories Grid -->
            @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" data-aos="fade-up">
                @foreach($categories as $category)
                @php
                    // Get category name for icon selection
                    $categoryName = $category->localized_name ?? $category->name;
                    
                    // Define icon sets with their colors
                    $iconSets = [
                        [
                            'name' => 'clothing',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                            'color' => 'text-rose-600 dark:text-rose-400',
                            'bg' => 'from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30',
                            'keywords' => ['shirt', 'top', 't-shirt', 'blouse', 'sweater', 'hoodie', 'jacket']
                        ],
                        [
                            'name' => 'bags',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                            'color' => 'text-amber-600 dark:text-amber-400',
                            'bg' => 'from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30',
                            'keywords' => ['bag', 'purse', 'handbag', 'backpack', 'tote', 'clutch']
                        ],
                        [
                            'name' => 'pants',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                            'color' => 'text-blue-600 dark:text-blue-400',
                            'bg' => 'from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30',
                            'keywords' => ['pant', 'jean', 'trouser', 'legging', 'short', 'skirt']
                        ],
                        [
                            'name' => 'shoes',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>',
                            'color' => 'text-green-600 dark:text-green-400',
                            'bg' => 'from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30',
                            'keywords' => ['shoe', 'foot', 'sneaker', 'boot', 'sandal', 'heel', 'loafer']
                        ],
                        [
                            'name' => 'dresses',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                            'color' => 'text-purple-600 dark:text-purple-400',
                            'bg' => 'from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30',
                            'keywords' => ['dress', 'gown', 'jumpsuit', 'romper', 'kimono']
                        ],
                        [
                            'name' => 'accessories',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
                            'color' => 'text-red-600 dark:text-red-400',
                            'bg' => 'from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30',
                            'keywords' => ['watch', 'jewelry', 'belt', 'hat', 'scarf', 'sunglass', 'glove']
                        ]
                    ];
                    
                    // Find matching icon based on category name keywords
                    $selectedIcon = $iconSets[0]; // Default to first icon
                    $lowerName = strtolower($categoryName);
                    
                    foreach ($iconSets as $iconSet) {
                        foreach ($iconSet['keywords'] as $keyword) {
                            if (str_contains($lowerName, $keyword)) {
                                $selectedIcon = $iconSet;
                                break 2;
                            }
                        }
                    }
                    
                    // If no match found, use category ID to pick a consistent random icon
                    if ($selectedIcon['name'] === 'clothing') {
                        $iconIndex = $category->id % count($iconSets);
                        $selectedIcon = $iconSets[$iconIndex];
                    }
                @endphp
                
                <a href="{{ route('categories.show', $category->slug) }}" 
                   class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700">
                    
                    <!-- Category Icon -->
                    <div class="relative h-64 overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br {{ $selectedIcon['bg'] }}">
                            <svg class="w-24 h-24 {{ $selectedIcon['color'] }} group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $selectedIcon['icon'] !!}
                            </svg>
                        </div>
                        
                        <!-- Product Count Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                {{ $category->products_count }} {{ __("items") }}
                            </span>
                        </div>
                    </div>

                    <!-- Category Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                            {{ $category->localized_name }}
                        </h3>
                        
                        @if($category->localized_description)
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                            {{ $category->localized_description }}
                        </p>
                        @endif
                        
                        <!-- View Category Button -->
                        <div class="flex items-center text-rose-600 dark:text-rose-400 font-medium group/link">
                            <span class="mr-2">{{ __("Shop Now") }}</span>
                            <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
            <div class="mt-10">
                {{ $categories->links('vendor.pagination.custom') }}
            </div>
            @endif

            <!-- Empty State -->
            @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center" data-aos="fade-up">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/20 dark:to-pink-900/20 flex items-center justify-center">
                    <svg class="w-12 h-12 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ __("No Categories Found") }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __("Try adjusting your search or browse all products") }}
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    {{ __("Browse All Products") }}
                </a>
            </div>
            @endif
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>