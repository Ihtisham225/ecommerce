<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1">
        <!-- Fashion Hero Section -->
        <section class="relative overflow-hidden py-20 md:py-32 px-4 bg-gradient-to-br from-rose-50 to-pink-50 dark:from-gray-900 dark:to-gray-800" data-aos="fade-up" data-aos-delay="100">
            <!-- Fashion Pattern Background -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 dark:opacity-5">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=" 60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="none" fill-rule="evenodd" %3E%3Cg fill="%239C92AC" fill-opacity="0.2" %3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>

            <!-- Fashion Icons Floating -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-1/4 left-1/4 w-12 h-12 text-rose-300 dark:text-rose-800 opacity-70 animate-float">
                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 2h12a1 1 0 011 1v18a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1zm6 15a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </div>
                <div class="absolute top-1/3 right-1/4 w-16 h-16 text-pink-300 dark:text-pink-800 opacity-60 animate-float animation-delay-1200">
                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </div>
                <div class="absolute bottom-1/4 left-1/3 w-20 h-20 text-purple-300 dark:text-purple-800 opacity-50 animate-float animation-delay-1800">
                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z" />
                    </svg>
                </div>
            </div>

            <div class="container mx-auto relative z-10">
                <div class="flex flex-col lg:flex-row items-center">
                    <!-- Text Content -->
                    <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-10">
                        <div class="mb-6 animate-slide-left">
                            <span class="inline-flex items-center px-4 py-2 text-sm font-semibold bg-gradient-to-r from-rose-400 to-pink-500 text-white backdrop-blur-sm rounded-full mb-4">
                                <span class="icon-container mr-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                {{ __("Spring Collection 2024") }}
                            </span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 animate-slide-left animation-delay-200 text-gray-900 dark:text-white">
                            {{ __("Elevate Your") }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-pink-500 dark:from-rose-400 dark:to-pink-400">{{ __("Style Game") }}</span>
                        </h1>
                        <p class="text-xl mb-8 text-gray-600 dark:text-gray-300 animate-slide-left animation-delay-400">
                            {{ __("Discover premium fashion wear, curated collections, and timeless pieces that define your unique style.") }}
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-12 animate-fade-in animation-delay-600">
                            <!-- Shop Now Button -->
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-rose-500 to-pink-600 dark:from-rose-600 dark:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-rose-600 hover:to-pink-700 dark:hover:from-rose-700 dark:hover:to-pink-800 transition-all duration-300 transform hover:-translate-y-1 text-center group">
                                <span class="icon-container mr-2 transition-transform group-hover:translate-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </span>
                                {{ __("Shop Collection") }}
                            </a>

                            <!-- Categories Button -->
                            <a href="{{ route('categories.index') }}"
                                class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 font-semibold rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 transform hover:-translate-y-1 text-center border border-gray-200 dark:border-gray-700 group">
                                <span class="icon-container mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </span>
                                {{ __("Browse All") }}
                            </a>
                        </div>

                        <!-- Fashion Stats -->
                        <div class="grid grid-cols-3 gap-4 animate-fade-in animation-delay-800">
                            <div class="text-center p-4 rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-2xl font-bold bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent dark:from-rose-400 dark:to-pink-400">50+</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ __("Designer Brands") }}</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-2xl font-bold bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent dark:from-rose-400 dark:to-pink-400">2K+</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ __("Fashion Items") }}</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-2xl font-bold bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent dark:from-rose-400 dark:to-pink-400">98%</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ __("Happy Clients") }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Fashion Models Showcase -->
                    <div class="lg:w-1/2 relative">
                        <div class="relative z-10">
                            <!-- Main Fashion Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Model 1 -->
                                <div class="relative group" data-aos="fade-left" data-aos-delay="300">
                                    <div class="overflow-hidden rounded-2xl shadow-2xl">
                                        <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                            alt="Fashion Model 1"
                                            class="w-full h-64 object-contain transform group-hover:scale-110 transition-transform duration-700">
                                    </div>
                                    <div class="absolute bottom-4 left-4">
                                        <span class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-800 dark:text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ __("Casual Wear") }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Model 2 -->
                                <div class="relative group mt-8" data-aos="fade-left" data-aos-delay="400">
                                    <div class="overflow-hidden rounded-2xl shadow-2xl">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                            alt="Fashion Model 2"
                                            class="w-full h-64 object-contain transform group-hover:scale-110 transition-transform duration-700">
                                    </div>
                                    <div class="absolute bottom-4 left-4">
                                        <span class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-800 dark:text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ __("Evening Dress") }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Fashion Card -->
                            <div class="absolute -bottom-6 -right-6 bg-gradient-to-br from-rose-500 to-pink-600 dark:from-rose-600 dark:to-pink-700 text-white p-6 rounded-2xl shadow-2xl animate-float border border-rose-400/30 max-w-xs">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg">{{ __("Trend Alert") }}</div>
                                        <div class="text-sm opacity-90">{{ __("Spring '24 Collection") }}</div>
                                    </div>
                                </div>
                                <p class="text-sm opacity-90">-30% on selected items</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <div class="w-6 h-10 border-2 border-rose-300 dark:border-rose-600 rounded-full flex justify-center">
                    <div class="w-1 h-3 bg-gradient-to-b from-rose-400 to-pink-500 rounded-full mt-2"></div>
                </div>
            </div>
        </section>

        <!-- Fashion Categories Grid -->
        <section id="categories" class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up">
            <div class="container mx-auto px-6 lg:px-12">
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-full mb-4">
                        {{ __("Collections") }}
                    </span>
                    <h2 class="text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        {{ __("Shop by Category") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Browse our curated fashion categories") }}
                    </p>
                </div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($categories as $category)
                    <a href="#"
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-800"
                        data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">

                        <!-- Category Image/Icon -->
                        <div class="aspect-square flex items-center justify-center p-8">
                            <div class="relative w-20 h-20 flex items-center justify-center">
                                <!-- Background Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30 rounded-2xl transform group-hover:scale-110 transition-transform duration-500"></div>

                                <!-- Icon -->
                                @php
                                $icons = [
                                'tshirt' => '
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
                                'bag' => '
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />',
                                'pants' => '
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
                                'shoe' => '
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />',
                                'dress' => '
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />'
                                ];

                                $categoryName = strtolower($category->name);
                                $iconPath = '';

                                if (str_contains($categoryName, 'shirt') || str_contains($categoryName, 'top')) {
                                $iconPath = $icons['tshirt'];
                                } elseif (str_contains($categoryName, 'bag') || str_contains($categoryName, 'purse')) {
                                $iconPath = $icons['bag'];
                                } elseif (str_contains($categoryName, 'pant') || str_contains($categoryName, 'jean')) {
                                $iconPath = $icons['pants'];
                                } elseif (str_contains($categoryName, 'shoe') || str_contains($categoryName, 'foot')) {
                                $iconPath = $icons['shoe'];
                                } elseif (str_contains($categoryName, 'dress') || str_contains($categoryName, 'skirt')) {
                                $iconPath = $icons['dress'];
                                } else {
                                $iconPath = $icons['tshirt'];
                                }
                                @endphp

                                <svg class="w-12 h-12 text-rose-500 dark:text-rose-400 relative z-10 transform group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $iconPath !!}
                                </svg>
                            </div>
                        </div>

                        <!-- Category Info -->
                        <div class="p-6 pt-0 text-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors mb-2">
                                {{ $category->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $category->products->count() }} {{ __("items") }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Featured Products - New Arrivals -->
        <section class="relative py-20 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800" data-aos="fade-up">
            <div class="container mx-auto px-6 lg:px-12">
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-full mb-4">
                        {{ __("New This Week") }}
                    </span>
                    <h2 class="text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        {{ __("Fresh Arrivals") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Just landed - be the first to shop the latest styles") }}
                    </p>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($latestProducts as $product)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700"
                        data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        <!-- Product Image -->
                        <div class="relative h-80 overflow-hidden">
                            @if($product->documents->first())
                            <img src="{{ asset('storage/' . $product->documents->first()->file_path) }}"
                                alt="{{ $product->translate('title') }}"
                                class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-700">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif

                            <!-- New Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-gradient-to-r from-rose-500 to-pink-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    {{ __("NEW") }}
                                </span>
                            </div>

                            <!-- Quick Actions -->
                            <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-10 h-10 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                                <button class="w-10 h-10 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
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
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-3 line-clamp-2 group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors">
                                {{ $product->translate('title') }}
                            </h3>

                            <!-- Price -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        @if($product->variants->count() > 0)
                                            <!-- Show price range for products with variants -->
                                            @php
                                                $minPrice = $product->variants->min('price');
                                                $maxPrice = $product->variants->max('price');
                                            @endphp
                                            ${{ number_format($minPrice, 2) }}
                                            @if($minPrice != $maxPrice)
                                                - ${{ number_format($maxPrice, 2) }}
                                            @endif
                                        @else
                                            <!-- Show single price for products without variants -->
                                            ${{ number_format($product->current_price, 2) }}
                                            @if($product->compare_at_price && $product->compare_at_price > $product->current_price)
                                                <span class="text-sm text-gray-500 line-through ml-2">
                                                    ${{ number_format($product->compare_at_price, 2) }}
                                                </span>
                                            @endif
                                        @endif
                                    </span>
                                </div>

                                @if($product->variants->count() == 0)
                                    <!-- Size Indicator only for simple products without variants -->
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                        <span>S, M, L</span>
                                    </div>
                                @endif
                            </div>

                            @if($product->variants->count() == 0)
                                <!-- Add to Cart Button ONLY for products WITHOUT variants -->
                                <button class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 dark:from-rose-600 dark:to-pink-700 dark:hover:from-rose-700 dark:hover:to-pink-800 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center group/btn add-to-cart-btn"
                                        data-product-id="{{ $product->id }}">
                                    <svg class="w-5 h-5 mr-2 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ __("Add to Bag") }}
                                </button>
                            @else
                                <!-- For products WITH variants, show a link to the product detail page -->
                                <a href="{{ route('products.show', $product->slug) }}"
                                class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center group">
                                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ __("View Details") }}
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Seasonal Collection Banner -->
        <section class="relative py-20 overflow-hidden" data-aos="fade-up">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="relative rounded-3xl overflow-hidden">
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                            alt="Spring Collection"
                            class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 to-gray-900/40 dark:from-gray-900/90 dark:to-gray-900/60"></div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 p-12 md:p-16 lg:p-20">
                        <div class="max-w-xl">
                            <span class="inline-block px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-sm font-semibold rounded-full mb-6">
                                {{ __("Seasonal Collection") }}
                            </span>
                            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                                {{ __("Spring Bloom Collection 2024") }}
                            </h2>
                            <p class="text-lg text-gray-200 mb-8">
                                {{ __("Fresh colors, lightweight fabrics, and effortless style for the new season. Limited time offer.") }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="#"
                                    class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                                    {{ __("Shop Now") }}
                                    <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                                <a href="#"
                                    class="inline-flex items-center justify-center px-8 py-4 bg-transparent text-white font-semibold rounded-xl border-2 border-white/30 hover:border-white transition-all duration-300">
                                    {{ __("View Lookbook") }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up">
            <div class="container mx-auto px-6 lg:px-12">
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-full mb-4">
                        {{ __("Editor's Pick") }}
                    </span>
                    <h2 class="text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        {{ __("Best Sellers") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Top-rated fashion pieces loved by our community") }}
                    </p>
                </div>

                <!-- Featured Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredProducts as $product)
                    <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700"
                        data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        <!-- Product Image -->
                        <div class="relative h-64 overflow-hidden">
                            @if($product->documents->first())
                            <img src="{{ asset('storage/' . $product->documents->first()->file_path) }}"
                                alt="{{ $product->translate('title') }}"
                                class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-700">
                            @endif

                            <!-- Featured Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    {{ __("BEST SELLER") }}
                                </span>
                            </div>

                            <!-- Rating -->
                            <div class="absolute bottom-4 left-4">
                                <div class="flex items-center bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                    <div class="flex text-amber-400 mr-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            @endfor
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">4.8</span>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    @if($product->brand)
                                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                        {{ $product->brand->name }}
                                    </div>
                                    @endif
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors">
                                        {{ $product->translate('title') }}
                                    </h3>
                                </div>
                                <button class="text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Price & Colors -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        ${{ number_format($product->current_price, 2) }}
                                    </span>
                                    @if($product->compare_at_price && $product->compare_at_price > $product->current_price)
                                    <span class="text-sm text-gray-500 line-through ml-2">
                                        ${{ number_format($product->compare_at_price, 2) }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Color Options -->
                                <div class="flex gap-2">
                                    <div class="w-6 h-6 rounded-full bg-rose-500 border-2 border-white dark:border-gray-800 shadow"></div>
                                    <div class="w-6 h-6 rounded-full bg-gray-800 border-2 border-white dark:border-gray-800 shadow"></div>
                                    <div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-white dark:border-gray-800 shadow"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Fashion Brands -->
        <section class="relative py-20 bg-gray-50 dark:bg-gray-800" data-aos="fade-up">
            <div class="container mx-auto px-6 lg:px-12">
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        {{ __("Featured Brands") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Shop from the world's leading fashion brands") }}
                    </p>
                </div>

                <!-- Brands Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                    @foreach($brands as $brand)
                    <div class="group bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center"
                        data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">

                        <!-- Brand Logo Placeholder -->
                        <div class="w-20 h-20 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center group-hover:from-rose-50 group-hover:to-pink-50 dark:group-hover:from-rose-900/20 dark:group-hover:to-pink-900/20 transition-all duration-500">
                            <span class="text-2xl font-bold text-gray-800 dark:text-white group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors">
                                {{ substr($brand->name, 0, 2) }}
                            </span>
                        </div>

                        <!-- Brand Info -->
                        <div class="text-center">
                            <h3 class="font-semibold text-gray-800 dark:text-white group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors mb-2">
                                {{ $brand->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $brand->products->count() }} {{ __("items") }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <x-landing-footer />

    <script>
        // Add intersection observer for animations
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('[data-aos]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.getAttribute('data-aos-delay') || 0;
                        setTimeout(() => {
                            entry.target.classList.add('aos-animate');
                        }, parseInt(delay));
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            });

            animatedElements.forEach(el => {
                observer.observe(el);
            });

            // Add hover effects for product cards
            const productCards = document.querySelectorAll('[data-aos="fade-up"]');
            productCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-8px)';
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });
        });

        // Cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartButtons = document.querySelectorAll('button:contains("Add to Bag")');

            addToCartButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const productCard = this.closest('[data-aos]');
                    const productTitle = productCard.querySelector('h3').textContent;
                    const productPrice = productCard.querySelector('text-2xl').textContent;

                    // Add animation
                    this.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Adding...';

                    setTimeout(() => {
                        this.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Added to Bag';
                        this.classList.remove('from-rose-500', 'to-pink-500');
                        this.classList.add('bg-green-500', 'hover:bg-green-600');

                        // Show notification
                        showNotification(`${productTitle} added to cart!`);
                    }, 1000);
                });
            });

            function showNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 border-green-500 transform translate-x-full transition-transform duration-300';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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