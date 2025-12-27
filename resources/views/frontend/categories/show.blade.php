<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">

            <!-- Category Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-10">
                <div class="bg-gradient-to-r from-[#1B5388] to-indigo-900 py-16 text-center text-white">
                    <h1 class="text-4xl font-bold">{{ $category->name }}</h1>
                    <p class="mt-2 text-blue-100">
                        {{ $category->products_count ?? 0 }} {{ __("products available") }}
                    </p>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Breadcrumb -->
                    <nav class="text-sm text-gray-600 dark:text-gray-300 flex flex-wrap items-center gap-2">
                        <a href="{{ route('categories.index') }}" class="hover:text-[#1B5388]">
                            {{ __('All Categories') }}
                        </a>
                        
                        @if($category->parent)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5l7 7-7 7" />
                            </svg>
                            <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-[#1B5388]">
                                {{ $category->parent->name }}
                            </a>
                        @endif

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                    </nav>

                    <!-- Category Description -->
                    @if($category->description)
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">
                                {{ __("About this Category") }}
                            </h2>
                            <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                                {!! $category->description !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children && $category->children->count() > 0)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __("Subcategories") }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child->slug) }}"
                               class="block bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $child->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                            {{ $child->products_count ?? 0 }} {{ __("products") }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Categories -->
            @if(isset($relatedCategories) && $relatedCategories->count() > 0)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __("Related Categories") }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($relatedCategories as $relatedCategory)
                            <a href="{{ route('categories.show', $relatedCategory->slug) }}"
                               class="block bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition-all p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $relatedCategory->name }}
                                        </h3>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $relatedCategory->products_count ?? 0 }} {{ __("products") }}
                                        </p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Products Section -->
            <div>
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 md:mb-0">
                        {{ __("Products in this Category") }}
                    </h2>

                    <!-- Product Filters -->
                    <div class="flex space-x-4">
                        <select name="sort" id="product-sort"
                                class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388] text-sm"
                                onchange="window.location.href = this.value ? '{{ request()->fullUrl() }}' + '&sort=' + this.value : '{{ route('categories.show', $category->slug) }}'">
                            <option value="">{{ __("Sort by") }}</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                {{ __("Newest") }}
                            </option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>
                                {{ __("Name (A-Z)") }}
                            </option>
                            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>
                                {{ __("Name (Z-A)") }}
                            </option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                {{ __("Price: Low to High") }}
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                {{ __("Price: High to Low") }}
                            </option>
                        </select>
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-2xl group">
                                <!-- Product Image -->
                                <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image->file_path) }}"
                                             alt="{{ $product->title }}"
                                             class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Product Status Badge -->
                                    @if($product->is_featured)
                                        <span class="absolute top-3 left-3 px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded">
                                            {{ __("Featured") }}
                                        </span>
                                    @endif
                                    
                                    <!-- Quick View Button -->
                                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="bg-white dark:bg-gray-900 p-2 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Product Content -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-[#1B5388] transition line-clamp-2">
                                        <a href="{{ route('products.show', $product->slug) }}">
                                            {{ $product->title }}
                                        </a>
                                    </h3>

                                    <!-- Product Description -->
                                    @if($product->short_description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                            {!! $product->short_description !!}
                                        </p>
                                    @endif

                                    <!-- Price -->
                                    <div class="flex items-center justify-between mt-auto">
                                        <div>
                                            @if($product->price)
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                    {{ config('app.currency_symbol') }}{{ number_format($product->price, 2) }}
                                                </span>
                                                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 line-through ml-2">
                                                        {{ config('app.currency_symbol') }}{{ number_format($product->compare_at_price, 2) }}
                                                    </span>
                                                    @php
                                                        $discount = 100 - (($product->price / $product->compare_at_price) * 100);
                                                    @endphp
                                                    <span class="text-xs font-semibold text-red-500 ml-2">
                                                        {{ round($discount) }}% {{ __("OFF") }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">
                                                    {{ __("Price on request") }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="flex-1 bg-[#1B5388] hover:bg-[#0a2444] text-white text-sm font-semibold py-2 px-4 rounded-lg text-center transition">
                                            {{ __("View Details") }}
                                        </a>
                                        @if($product->price)
                                            <button type="button"
                                                    class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 p-2 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">
                            {{ __("No products found") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __("No products are available in this category yet.") }}
                        </p>
                        <a href="{{ route('categories.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-[#1B5388] hover:bg-[#0a2444] text-white rounded-lg transition font-semibold">
                            {{ __("Browse All Categories") }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>