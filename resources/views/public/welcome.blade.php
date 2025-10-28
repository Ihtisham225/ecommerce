<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1">
        <!-- Enhanced Hero Section -->
        <section class="relative overflow-hidden py-20 md:py-32 px-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800" data-aos="fade-up" data-aos-delay="100">
            <!-- Animated Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30">
                <div class="absolute -top-4 -left-4 w-72 h-72 bg-white dark:bg-blue-800 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow"></div>
                <div class="absolute top-1/4 -right-8 w-96 h-96 bg-blue-300 dark:bg-indigo-700 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-800"></div>
                <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-blue-200 dark:bg-purple-700 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-400"></div>
            </div>

            <!-- Floating Particles -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-500 dark:bg-blue-400 rounded-full opacity-70 animate-float"></div>
                <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-indigo-500 dark:bg-indigo-400 rounded-full opacity-60 animate-float animation-delay-1200"></div>
                <div class="absolute bottom-1/4 left-1/3 w-4 h-4 bg-purple-500 dark:bg-purple-400 rounded-full opacity-50 animate-float animation-delay-1800"></div>
                <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-blue-400 dark:bg-blue-300 rounded-full opacity-80 animate-float animation-delay-2400"></div>
            </div>

            <div class="container mx-auto relative z-10">
                <div class="flex flex-col lg:flex-row items-center">
                    <!-- Text Content -->
                    <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-10">
                        <div class="mb-6 animate-slide-left">
                            <span class="inline-flex items-center px-4 py-2 text-sm font-semibold bg-primary/30 dark:bg-primary/20 backdrop-blur-sm rounded-full mb-4 border border-primary/20 dark:border-primary/30">
                                <span class="icon-container mr-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M12 11L4 7M12 11V21M4 7V17L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                {{ __("Premium Ecommerce Store") }}
                            </span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 animate-slide-left animation-delay-200 text-gray-900 dark:text-white">
                            {{ __("Discover") }} <span class="text-primary bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">{{ __("Amazing Products") }}</span> {{ __("for Every Need") }}
                        </h1>
                        <p class="text-xl mb-8 text-primary/70 dark:text-blue-200 animate-slide-left animation-delay-400">
                            {{ __("Shop the latest trends and premium quality products with fast delivery and excellent customer service.") }}
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-12 animate-fade-in animation-delay-600">
                            <!-- Shop Now Button -->
                            <a href="{{ route('products.index') }}" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary to-indigo-600 dark:from-blue-600 dark:to-indigo-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:from-primary/90 hover:to-indigo-600/90 transition-all duration-300 transform hover:-translate-y-1 text-center group">
                                <span class="icon-container mr-2 transition-transform group-hover:translate-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </span>
                                {{ __("Shop Now") }}
                            </a>

                            <!-- Categories Button -->
                            <a href="{{ route('categories.index') }}"
                            class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-primary dark:text-blue-400 font-semibold rounded-xl shadow-md hover:shadow-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 transform hover:-translate-y-1 text-center border border-gray-200 dark:border-gray-700">
                                <span class="icon-container mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </span>
                                {{ __("Browse Categories") }}
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 animate-fade-in animation-delay-800">
                            <div class="text-center p-4 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-3xl font-bold text-primary dark:text-blue-400">10K+</div>
                                <div class="text-primary/70 dark:text-blue-300">{{ __("Happy Customers") }}</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-3xl font-bold text-primary dark:text-blue-400">500+</div>
                                <div class="text-primary/70 dark:text-blue-300">{{ __("Premium Products") }}</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-3xl font-bold text-primary dark:text-blue-400">24/7</div>
                                <div class="text-primary/70 dark:text-blue-300">{{ __("Customer Support") }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Content -->
                    <div class="lg:w-1/2 relative">
                        <div class="relative z-10">
                            <!-- Main Card -->
                            <div class="bg-gradient-to-br from-blue-400/10 to-indigo-500/10 dark:from-blue-900/20 dark:to-indigo-900/20 backdrop-filter backdrop-blur-lg rounded-2xl p-2 shadow-2xl transform transition-all duration-500 hero-card border border-white/20 dark:border-gray-700/30">
                                <div class="overflow-hidden rounded-xl">
                                    <img
                                        src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80"
                                        alt="Ecommerce shopping"
                                        class="w-full h-auto rounded-xl shadow-lg transform transition-transform duration-700 group-hover:scale-105">
                                </div>

                                <!-- Floating elements -->
                                <div class="absolute -bottom-4 -left-4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 p-4 rounded-xl shadow-lg max-w-xs animate-float border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1B5388" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="font-bold">{{ __("Quality Guarantee") }}</div>
                                    </div>
                                    <p class="text-sm">{{ __("100% quality assured products") }}</p>
                                </div>

                                <div class="absolute -top-4 -right-4 bg-primary dark:bg-blue-700 text-white p-3 rounded-xl shadow-lg animate-float animation-delay-400 border border-blue-400/30">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M12 11L4 7M12 11V21M4 7V17L12 21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ __("Fast Delivery") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute -top-4 -left-4 w-24 h-24 rounded-full bg-blue-500/30 dark:bg-blue-700/40 blur-xl animate-pulse-slow"></div>
                        <div class="absolute -bottom-4 -right-4 w-32 h-32 rounded-full bg-indigo-500/30 dark:bg-indigo-700/40 blur-xl animate-pulse-slow animation-delay-800"></div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <a href="#categories" class="w-6 h-10 border-2 border-primary/50 dark:border-blue-600 rounded-full flex justify-center">
                    <div class="w-1 h-3 bg-primary/70 dark:bg-blue-600 rounded-full mt-2"></div>
                </a>
            </div>
        </section>

        <!-- Categories Slider Section -->
        <section id="categories" class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">
                
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Categories") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Shop by Category") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Browse our wide range of product categories") }}
                    </p>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper categoriesSwiper">
                    <div class="swiper-wrapper">
                        @foreach($categories as $category)
                        <div class="swiper-slide w-64">
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-md text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 dark:border-gray-800">
                                
                                <!-- Category Icon/Image -->
                                <div class="h-20 w-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    @if($category->documents->first())
                                        <img src="{{ asset('storage/' . $category->documents->first()->file_path) }}" 
                                            alt="{{ $category->name }}" 
                                            class="w-12 h-12 object-contain">
                                    @else
                                        <svg class="w-12 h-12 text-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                    @endif
                                </div>

                                <!-- Category Name -->
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">
                                    {{ $category->translate('name') }}
                                </h3>
                                
                                <!-- Product Count -->
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    {{ $category->products->count() }} {{ __("products") }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Navigation + Pagination -->
                    <div class="swiper-button-next !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-button-prev !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-pagination !bottom-0 mt-6"></div>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section id="featured-products" class="relative py-20 bg-gray-50 dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Featured") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Featured Products") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Handpicked selection of our best products") }}
                    </p>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($featuredProducts as $product)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 ease-out hover:-translate-y-2 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Product Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($product->documents->first())
                                <img src="{{ asset('storage/' . $product->documents->first()->file_path) }}" 
                                    alt="{{ $product->title }}"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500">
                                    {{ __("No Image") }}
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-primary dark:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                {{ __("Featured") }}
                            </div>
                        </div>

                        <!-- Product Content -->
                        <div class="p-6">
                            <!-- Brand -->
                            @if($product->brand)
                                <span class="text-sm font-semibold text-primary dark:text-blue-400 uppercase tracking-wide">
                                    {{ $product->brand->name }}
                                </span>
                            @endif

                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2 mb-3 group-hover:text-primary dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                {{ $product->translate('title') }}
                            </h3>

                            <!-- Price -->
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-2xl font-bold text-primary dark:text-blue-400">
                                    ${{ number_format($product->current_price, 2) }}
                                </span>
                                @if($product->compare_at_price && $product->compare_at_price > $product->current_price)
                                    <span class="text-lg text-gray-500 line-through">
                                        ${{ number_format($product->compare_at_price, 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Stock Status -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-medium {{ $product->stock_status === 'in_stock' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ __(ucfirst(str_replace('_', ' ', $product->stock_status))) }}
                                </span>
                                @if($product->stock_quantity > 0)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $product->stock_quantity }} {{ __("in stock") }}
                                    </span>
                                @endif
                            </div>

                            <!-- CTA -->
                            <a href="{{ route('products.show', $product->slug) }}"
                            class="w-full inline-flex items-center justify-center px-5 py-3 bg-primary dark:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-primary/90 dark:hover:bg-blue-700 transition-colors group/btn">
                                {{ __("View Product") }}
                                <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View All Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('products.featured') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary dark:border-blue-600 text-primary dark:text-blue-400 font-semibold rounded-lg hover:bg-primary dark:hover:bg-blue-600 hover:text-white transition-all duration-300">
                        {{ __("View All Featured Products") }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Collections Slider Section -->
        <section id="collections" class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">
                
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Collections") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Shop Our Collections") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Curated collections for every style and need") }}
                    </p>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper collectionsSwiper">
                    <div class="swiper-wrapper">
                        @foreach($collections as $collection)
                        <div class="swiper-slide w-80">
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 ease-out hover:-translate-y-2 border border-gray-100 dark:border-gray-800">
                                
                                <!-- Collection Image -->
                                <div class="relative h-48 overflow-hidden">
                                    @if($collection->documents->first())
                                        <img src="{{ asset('storage/' . $collection->documents->first()->file_path) }}" 
                                            alt="{{ $collection->title }}"
                                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-200 dark:from-blue-900/40 dark:to-indigo-900/40 text-gray-500">
                                            <svg class="w-16 h-16 text-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>

                                <!-- Collection Content -->
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">
                                        {{ $collection->translate('title') }}
                                    </h3>
                                    
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-5 leading-relaxed line-clamp-2">
                                        {{ $collection->translate('description') }}
                                    </p>
                                    
                                    <a href="{{ route('collections.show', $collection->slug) }}"
                                    class="inline-flex items-center text-sm font-medium text-primary dark:text-blue-400 hover:underline group/readmore">
                                        {{ __("View Collection") }}
                                        <svg class="w-4 h-4 ml-1 transition-transform group-hover/readmore:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Navigation + Pagination -->
                    <div class="swiper-button-next !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-button-prev !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-pagination !bottom-0 mt-6"></div>
                </div>
            </div>
        </section>

        <!-- Latest Products Section -->
        <section id="latest-products" class="relative py-20 bg-gray-50 dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("New Arrivals") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Latest Products") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Check out our newest additions") }}
                    </p>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($latestProducts as $product)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 ease-out hover:-translate-y-2 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Product Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($product->documents->first())
                                <img src="{{ asset('storage/' . $product->documents->first()->file_path) }}" 
                                    alt="{{ $product->title }}"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500">
                                    {{ __("No Image") }}
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                {{ __("New") }}
                            </div>
                        </div>

                        <!-- Product Content -->
                        <div class="p-6">
                            <!-- Brand -->
                            @if($product->brand)
                                <span class="text-sm font-semibold text-primary dark:text-blue-400 uppercase tracking-wide">
                                    {{ $product->brand->name }}
                                </span>
                            @endif

                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2 mb-3 group-hover:text-primary dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                {{ $product->translate('title') }}
                            </h3>

                            <!-- Price -->
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-2xl font-bold text-primary dark:text-blue-400">
                                    ${{ number_format($product->current_price, 2) }}
                                </span>
                                @if($product->compare_at_price && $product->compare_at_price > $product->current_price)
                                    <span class="text-lg text-gray-500 line-through">
                                        ${{ number_format($product->compare_at_price, 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- CTA -->
                            <a href="{{ route('products.show', $product->slug) }}"
                            class="w-full inline-flex items-center justify-center px-5 py-3 bg-primary dark:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-primary/90 dark:hover:bg-blue-700 transition-colors group/btn">
                                {{ __("View Product") }}
                                <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View All Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary dark:border-blue-600 text-primary dark:text-blue-400 font-semibold rounded-lg hover:bg-primary dark:hover:bg-blue-600 hover:text-white transition-all duration-300">
                        {{ __("View All Products") }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Brands Section -->
        <section id="brands" class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Brands") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Shop by Brand") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Discover products from your favorite brands") }}
                    </p>
                </div>

                <!-- Brands Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                    @foreach($brands as $brand)
                    <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-500 ease-out hover:-translate-y-2 border border-gray-100 dark:border-gray-800 text-center" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="h-16 flex items-center justify-center mb-4">
                            <!-- Brand logo would go here -->
                            <span class="text-xl font-bold text-gray-800 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">
                                {{ $brand->name }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $brand->products->count() }} {{ __("products") }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative py-20 bg-gradient-to-r from-primary to-indigo-600 dark:from-blue-800 dark:to-indigo-900" data-aos="zoom-in" data-aos-delay="200">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-4 -left-4 w-72 h-72 bg-white/10 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow"></div>
                <div class="absolute top-1/4 -right-8 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-800"></div>
                <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-blue-400/10 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-400"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl font-extrabold text-white mb-6">
                        {{ __("Ready to Start Shopping?") }}
                    </h2>
                    <p class="text-xl text-blue-100 mb-10">
                        {{ __("Join thousands of satisfied customers and discover amazing products") }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary font-semibold rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-300 transform hover:-translate-y-1">
                            <span class="icon-container mr-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            {{ __("Start Shopping") }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-primary transition-all duration-300">
                            <span class="icon-container mr-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            {{ __("Create Account") }}
                        </a>
                    </div>
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
                        entry.target.classList.add('aos-animate');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            animatedElements.forEach(el => {
                observer.observe(el);
            });

            // Initialize Swiper sliders
            const categorySwiper = new Swiper('.categoriesSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.categoriesSwiper .swiper-button-next',
                    prevEl: '.categoriesSwiper .swiper-button-prev',
                },
                pagination: {
                    el: '.categoriesSwiper .swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                },
            });

            const collectionSwiper = new Swiper('.collectionsSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.collectionsSwiper .swiper-button-next',
                    prevEl: '.collectionsSwiper .swiper-button-prev',
                },
                pagination: {
                    el: '.collectionsSwiper .swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 3 },
                },
            });
        });
    </script>
</x-landing-layout>