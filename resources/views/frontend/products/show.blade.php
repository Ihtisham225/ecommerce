<x-landing-layout>
    <x-landing-navbar />

    <main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                            {{ __("Home") }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('products.index') }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                            {{ __("Products") }}
                        </a>
                    </li>
                    @foreach($product->categories as $category)
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-gray-900 dark:text-white font-medium truncate max-w-xs">
                            {{ $product->translate('title') }}
                        </span>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Images -->
                <div data-aos="fade-right">
                    <!-- Main Image -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6">
                        <div class="relative h-96 flex items-center justify-center">
                            @if($product->mainImage)
                                <img src="{{ asset('storage/' . $product->mainImage->first()->file_path) }}" 
                                     alt="{{ $product->translate('title') }}"
                                     class="max-w-full max-h-full object-contain" 
                                     id="mainImage">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                    <svg class="w-24 h-24 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Sale Badge -->
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <div class="absolute top-4 left-4">
                                    <span class="px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-sm font-semibold rounded-full shadow-lg">
                                        {{ __("SALE") }}
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Stock Badge -->
                            <div class="absolute top-4 right-4">
                                @if($product->stock_status === 'in_stock')
                                    <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full shadow-lg">
                                        {{ __("In Stock") }}
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-full shadow-lg">
                                        {{ __("Out of Stock") }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="flex space-x-3 overflow-x-auto pb-2 thumbnails" id="productThumbnails">
                        @if($product->mainImage)
                        <button onclick="changeImage('{{ asset('storage/' . $product->mainImage->first()->file_path) }}')"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-rose-500 thumbnail-btn active-thumbnail"
                                data-image-src="{{ asset('storage/' . $product->mainImage->first()->file_path) }}">
                            <img src="{{ asset('storage/' . $product->mainImage->first()->file_path) }}" 
                                 alt="{{ $product->translate('title') }}"
                                 class="w-full h-full object-contain">
                        </button>
                        @endif
                        @foreach($product->galleryImages as $image)
                        <button onclick="changeImage('{{ asset('storage/' . $image->file_path) }}')"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-700 hover:border-rose-500 transition-colors thumbnail-btn"
                                data-image-src="{{ asset('storage/' . $image->file_path) }}">
                            <img src="{{ asset('storage/' . $image->file_path) }}" 
                                 alt="{{ $product->translate('title') }}"
                                 class="w-full h-full object-contain">
                        </button>
                        @endforeach
                    </div>
                    
                    <!-- Description Section -->
                    <div class="mt-8" data-aos="fade-up">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __("Description") }}</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <div class="text-gray-600 dark:text-gray-400 leading-relaxed prose dark:prose-invert max-w-none">
                                {!! $product->translate('description') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Actions -->
                <div data-aos="fade-left">
                    <!-- Brand -->
                    @if($product->brand)
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-700 text-rose-600 dark:text-rose-400">
                                {{ $product->brand->name }}
                            </span>
                        </div>
                    @endif

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $product->translate('title') }}
                    </h1>

                    <!-- Rating -->
                    <div class="flex items-center mb-6">
                        <div class="flex text-amber-400 mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">(24 {{ __("reviews") }})</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white" id="productPrice">
                                @if($product->variants->count() > 0 && $product->variants->first())
                                    {{ $currencySymbol }}{{ number_format($product->variants->first()->price, $productDecimals) }}
                                @else
                                    {{ $currencySymbol }}{{ number_format($product->price, $productDecimals) }}
                                @endif
                            </span>
                            
                            @if($product->variants->count() > 0)
                                @php
                                    $firstVariant = $product->variants->first();
                                    $hasComparePrice = $firstVariant && $firstVariant->compare_at_price && $firstVariant->compare_at_price > $firstVariant->price;
                                @endphp
                                
                                @if($hasComparePrice)
                                    <span class="text-2xl text-gray-500 line-through" id="productComparePrice">
                                        {{ $currencySymbol }}{{ number_format($firstVariant->compare_at_price, $productDecimals) }}
                                    </span>
                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold rounded-lg" id="productDiscount">
                                        {{ round((($firstVariant->compare_at_price - $firstVariant->price) / $firstVariant->compare_at_price) * 100) }}% OFF
                                    </span>
                                @else
                                    <span class="text-2xl text-gray-500 line-through hidden" id="productComparePrice"></span>
                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold rounded-lg hidden" id="productDiscount"></span>
                                @endif
                            @elseif($product->compare_price && $product->compare_price > $product->price)
                                <span class="text-2xl text-gray-500 line-through" id="productComparePrice">
                                    {{ $currencySymbol }}{{ number_format($product->compare_price, $productDecimals) }}
                                </span>
                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold rounded-lg" id="productDiscount">
                                    {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-2" id="productStock">
                            @if($product->variants->count() > 0 && $product->variants->first())
                                @if($product->variants->first()->stock_quantity > 0)
                                    {{ __("Only") }} {{ $product->variants->first()->stock_quantity }} {{ __("items left") }}
                                @endif
                            @elseif($product->stock_quantity > 0)
                                {{ __("Only") }} {{ $product->stock_quantity }} {{ __("items left") }}
                            @endif
                        </p>
                    </div>

                    <!-- Variants - Simple UI/UX -->
                    @if($product->variants->count() > 0)
                        @php
                            $firstVariant = $product->variants->first();
                            $firstVariantId = $firstVariant ? $firstVariant->id : null;
                            
                            // Prepare variant data with image information
                            $variantsWithImages = [];
                            foreach($product->variants as $variant) {
                                $variantData = [
                                    'id' => $variant->id,
                                    'sku' => $variant->sku,
                                    'price' => $variant->price,
                                    'compare_at_price' => $variant->compare_at_price,
                                    'stock_quantity' => $variant->stock_quantity,
                                    'options' => $variant->options,
                                ];
                                
                                // Add variant-specific image if exists
                                if ($variant->image) {
                                    $variantData['image'] = asset('storage/' . $variant->image->file_path);
                                }
                                
                                $variantsWithImages[] = $variantData;
                            }
                        @endphp
                        
                        @if($firstVariantId)
                        <div class="mb-8" id="variantSelector" 
                             data-variants='@json($variantsWithImages)' 
                             data-currency='{{ $currencySymbol }}' 
                             data-decimals='{{ $productDecimals }}'
                             data-main-image='{{ $product->mainImage ? asset('storage/' . $product->mainImage->first()->file_path) : "" }}'>
                            
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __("Available Options") }}</h3>
                            
                            @foreach($variantGroups as $optionName => $optionValues)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $optionName }}
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($optionValues as $optionValue => $variantId)
                                        @php
                                            $variant = $product->variants->firstWhere('id', $variantId);
                                        @endphp
                                        @if($variant)
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                name="variant_{{ Str::slug($optionName) }}" 
                                                value="{{ $variant->id }}"
                                                class="sr-only peer variant-radio"
                                                data-variant-id="{{ $variant->id }}"
                                                @if($loop->first) checked @endif>
                                            <div class="px-4 py-2 rounded-lg border-2 peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-rose-500 dark:hover:border-rose-500 transition-all duration-300">
                                                {{ $optionValue }}
                                                @if($variant->stock_quantity <= 0)
                                                    <span class="text-xs text-red-500 ml-1">({{ __("Out of Stock") }})</span>
                                                @endif
                                            </div>
                                        </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Add a hidden input for variant_id -->
                            <input type="hidden" id="variantIdInput" name="variant_id" value="{{ $firstVariantId }}">
                            
                            <!-- Selected Variant Info -->
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg" id="selectedVariantInfo">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __("SKU") }}</span>
                                        <p class="font-medium text-gray-900 dark:text-white" id="variantSKU">
                                            {{ $firstVariant->sku ?? $product->sku }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __("Stock") }}</span>
                                        <p class="font-medium text-gray-900 dark:text-white" id="variantStock">
                                            @if($firstVariant && $firstVariant->stock_quantity > 0)
                                                {{ __("In Stock") }} ({{ $firstVariant->stock_quantity }})
                                            @else
                                                {{ __("Out of Stock") }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Quantity & Add to Cart -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Quantity -->
                            <div class="flex items-center bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                <button type="button" 
                                        class="w-12 h-12 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400"
                                        onclick="changeQuantity(-1)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                    id="quantity" 
                                    value="1" 
                                    min="1" 
                                    max="{{ $product->variants->count() > 0 ? ($product->variants->first()->stock_quantity ?? 0) : $product->stock_quantity }}"
                                    class="w-16 h-12 text-center bg-transparent text-gray-900 dark:text-white focus:outline-none">
                                <button type="button" 
                                        class="w-12 h-12 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400"
                                        onclick="changeQuantity(1)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="button"
                                    id="addToCartBtn"
                                    data-product-id="{{ $product->id }}"
                                    class="flex-1 px-8 py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center group add-to-cart-btn"
                                    {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                                <svg class="w-6 h-6 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ $product->stock_status === 'in_stock' ? __("Add to Cart") : __("Out of Stock") }}
                            </button>

                            <!-- Buy Now Button -->
                            <button type="button"
                                    id="buyNowBtn"
                                    data-product-id="{{ $product->id }}"
                                    class="flex-1 px-8 py-4 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center group buy-now-btn"
                                    {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                                <svg class="w-6 h-6 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                {{ $product->stock_status === 'in_stock' ? __("Buy Now") : __("Out of Stock") }}
                            </button>

                            <!-- Wishlist -->
                            <button type="button"
                                    id="wishlistBtn"
                                    class="px-6 py-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:text-rose-600 dark:hover:text-rose-400 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <svg class="w-6 h-6 group-hover:fill-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __("Product Details") }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @if($product->collections->count() > 0)
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __("Collection") }}</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->collections->pluck('title')->implode(', ') }}</p>
                            </div>
                            @endif
                            @if($product->categories->count() > 0)
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __("Category") }}</span>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $product->categories->pluck('name')->implode(', ') }}
                                </p>
                            </div>
                            @endif
                            @if($product->shipping->weight)
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __("Weight") }}</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->shipping->weight }} kg</p>
                            </div>
                            @endif
                            @if($product->shipping->height && $product->shipping->width && $product->shipping->length)
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __("Dimensions") }}</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->shipping->height }} x {{ $product->shipping->width }} x {{ $product->shipping->length }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <section class="mt-20" data-aos="fade-up">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __("You Might Also Like") }}
                    </h2>
                    <a href="{{ route('products.index') }}" 
                       class="text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 font-medium flex items-center group">
                        {{ __("View All") }}
                        <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                            <div class="relative h-48 overflow-hidden">
                                @if($relatedProduct->mainImage)
                                    <img src="{{ asset('storage/' . $relatedProduct->mainImage->first()->file_path) }}" 
                                         alt="{{ $relatedProduct->translate('title') }}"
                                         class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-700">
                                @endif
                            </div>
                            <div class="p-4">
                                @if($relatedProduct->brand)
                                    <div class="text-xs font-semibold text-rose-500 dark:text-rose-400 uppercase tracking-wider mb-1">
                                        {{ $relatedProduct->brand->name }}
                                    </div>
                                @endif
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                                    {{ $relatedProduct->translate('title') }}
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $currencySymbol }}{{ number_format($relatedProduct->price, $productDecimals) }}
                                    </span>
                                    @if($relatedProduct->stock_status === 'in_stock')
                                        @php
                                            // Check if this product is in cart
                                            $inCart = false;
                                            if(auth()->check()) {
                                                $inCart = \App\Models\Cart::where('customer_id', auth()->id())
                                                    ->where('product_id', $relatedProduct->id)
                                                    ->exists();
                                            }
                                        @endphp
                                        
                                        @if($inCart)
                                            <a href="{{ route('cart.index') }}" 
                                            class="p-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <button type="button"
                                                    onclick="event.preventDefault(); event.stopPropagation(); addRelatedToCart({{ $relatedProduct->id }}, event)"
                                                    class="p-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>

<script>
// DOM Ready function
document.addEventListener('DOMContentLoaded', function() {
    // Current active image source
    let currentImageSrc = '';
    
    // Main image change function
    function changeImage(imageSrc) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage && imageSrc) {
            mainImage.src = imageSrc;
            currentImageSrc = imageSrc;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnails button').forEach(btn => {
                btn.classList.remove('border-rose-500', 'active-thumbnail');
                btn.classList.add('border-gray-200', 'dark:border-gray-700');
            });
            
            // Find and activate the corresponding thumbnail
            const activeThumbnail = document.querySelector(`.thumbnail-btn[data-image-src="${imageSrc}"]`);
            if (activeThumbnail) {
                activeThumbnail.classList.add('border-rose-500', 'active-thumbnail');
                activeThumbnail.classList.remove('border-gray-200', 'dark:border-gray-700');
            }
        }
    }
    
    // Quantity controls
    function changeQuantity(change) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + change;
        const max = parseInt(input.max);
        const min = parseInt(input.min);
        
        if (value < min) value = min;
        if (value > max) value = max;
        
        input.value = value;
    }
    
    // Update variant display - ONLY changes main image, not gallery
    function updateVariantDisplay(variantId) {
        const variantSelector = document.getElementById('variantSelector');
        if (!variantSelector) return;
        
        const variants = JSON.parse(variantSelector.getAttribute('data-variants'));
        const mainProductImage = variantSelector.getAttribute('data-main-image');
        const currencySymbol = variantSelector.getAttribute('data-currency');
        const decimals = parseInt(variantSelector.getAttribute('data-decimals'));
        
        const variant = variants.find(v => v.id == variantId) || variants[0];
        if (!variant) return;
        
        // Update price
        const priceElement = document.getElementById('productPrice');
        if (priceElement) {
            priceElement.textContent = currencySymbol + parseFloat(variant.price).toFixed(decimals);
        }
        
        // Update compare price and discount
        const comparePriceEl = document.getElementById('productComparePrice');
        const discountEl = document.getElementById('productDiscount');
        
        if (variant.compare_at_price && variant.compare_at_price > variant.price) {
            if (comparePriceEl) {
                comparePriceEl.textContent = currencySymbol + 
                    parseFloat(variant.compare_at_price).toFixed(decimals);
                comparePriceEl.classList.remove('hidden');
            }
            if (discountEl) {
                const discountPercent = Math.round(
                    ((variant.compare_at_price - variant.price) / variant.compare_at_price) * 100
                );
                discountEl.textContent = discountPercent + '% OFF';
                discountEl.classList.remove('hidden');
            }
        } else {
            if (comparePriceEl) comparePriceEl.classList.add('hidden');
            if (discountEl) discountEl.classList.add('hidden');
        }
        
        // Update stock display
        updateStockDisplay(variant);
        
        // Update max quantity
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.max = variant.stock_quantity;
            if (parseInt(quantityInput.value) > variant.stock_quantity) {
                quantityInput.value = variant.stock_quantity;
            }
        }
        
        // Update hidden input
        document.getElementById('variantIdInput').value = variantId;
        
        // Update variant info display
        document.getElementById('variantSKU').textContent = variant.sku || '{{ $product->sku }}';
        document.getElementById('variantStock').textContent = variant.stock_quantity > 0 
            ? '{{ __("In Stock") }} (' + variant.stock_quantity + ')' 
            : '{{ __("Out of Stock") }}';
        
        // Update main image based on variant
        // If variant has its own image, use it
        // Otherwise, use the current image or default to main product image
        if (variant.image) {
            // Variant has its own image - update main image
            changeImage(variant.image);
        } else {
            // Variant doesn't have specific image
            // If user has selected a gallery image, keep it
            // Otherwise, use main product image
            if (!currentImageSrc || currentImageSrc === '') {
                changeImage(mainProductImage);
            }
            // If user has already selected a gallery image, do nothing - keep it
        }
    }
    
    // Update stock display and buttons
    function updateStockDisplay(variant) {
        const stockEl = document.getElementById('productStock');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const buyNowBtn = document.getElementById('buyNowBtn');
        
        if (variant.stock_quantity > 0) {
            stockEl.textContent = '{{ __("Only") }} ' + variant.stock_quantity + ' {{ __("items left") }}';
            stockEl.className = 'text-sm text-green-600 dark:text-green-400 mt-2';
            
            // Enable buttons
            addToCartBtn.disabled = false;
            buyNowBtn.disabled = false;
            
            // Update button text
            addToCartBtn.innerHTML = `
                <svg class="w-6 h-6 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ __("Add to Cart") }}
            `;
            
            buyNowBtn.innerHTML = `
                <svg class="w-6 h-6 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                {{ __("Buy Now") }}
            `;
        } else {
            stockEl.textContent = '{{ __("Out of Stock") }}';
            stockEl.className = 'text-sm text-red-600 dark:text-red-400 mt-2';
            
            // Disable buttons
            addToCartBtn.disabled = true;
            buyNowBtn.disabled = true;
            
            // Update button text
            addToCartBtn.innerHTML = `
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ __("Out of Stock") }}
            `;
            
            buyNowBtn.innerHTML = `
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                {{ __("Out of Stock") }}
            `;
        }
    }
    
    // Get selected variant ID
    function getSelectedVariantId() {
        const checkedRadio = document.querySelector('input[name^="variant_"]:checked');
        return checkedRadio ? checkedRadio.value : null;
    }
    
    // Add to cart functionality
    async function addToCart(productId, event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const quantity = document.getElementById('quantity')?.value || 1;
        const variantId = getSelectedVariantId();
        
        console.log('Variant ID:', variantId);
        
        const button = document.getElementById('addToCartBtn');
        const originalHtml = button.innerHTML;
        const originalClasses = button.className;
        
        // Show loading state
        button.innerHTML = `
            <svg class="w-6 h-6 mr-3 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __("Adding...") }}
        `;
        button.disabled = true;
        
        try {
            // Prepare form data
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', parseInt(quantity));
            
            // Add variant_id if exists
            if (variantId) {
                formData.append('variant_id', variantId);
            }
            
            // Get variant selector to get options
            const variantSelector = document.getElementById('variantSelector');
            if (variantSelector) {
                const variants = JSON.parse(variantSelector.getAttribute('data-variants'));
                const variant = variants.find(v => v.id == variantId) || variants[0];
                if (variant && variant.options) {
                    formData.append('options', JSON.stringify(variant.options));
                }
            }
            
            // Make API call
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Success state
                button.innerHTML = `
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __("Added!") }}
                `;
                button.classList.remove('from-rose-500', 'to-pink-500', 'hover:from-rose-600', 'hover:to-pink-600');
                button.classList.add('bg-green-500', 'hover:bg-green-600');
                
                // Update cart count
                updateCartCount(data.cart_count);
                
                // Show notification
                const variantName = variantId ? ' ({{ __("Selected Variant") }})' : '';
                showNotification(`✓ ${quantity} × "${document.querySelector('h1').textContent}${variantName}" {{ __("added to cart") }}`, 'success');
                
                // Reset button after 3 seconds
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.className = originalClasses;
                    button.disabled = false;
                }, 3000);
            } else {
                // Error state
                button.innerHTML = `{{ __("Error") }}!`;
                button.classList.remove('from-rose-500', 'to-pink-500');
                button.classList.add('bg-red-500');
                
                showNotification(data.message || '{{ __("Failed to add item to cart") }}', 'error');
                
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.className = originalClasses;
                    button.disabled = false;
                }, 3000);
            }
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = `{{ __("Error") }}!`;
            button.classList.remove('from-rose-500', 'to-pink-500');
            button.classList.add('bg-red-500');
            
            showNotification('{{ __("Network error. Please try again.") }}', 'error');
            
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.className = originalClasses;
                button.disabled = false;
            }, 3000);
        }
    }
    
    // Buy Now functionality (direct purchase)
    async function buyNow(productId, event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const quantity = document.getElementById('quantity')?.value || 1;
        const variantId = getSelectedVariantId();
        
        console.log('Buy Now - Variant ID:', variantId);
        
        const button = document.getElementById('buyNowBtn');
        const originalHtml = button.innerHTML;
        
        // Show loading state
        button.innerHTML = `
            <svg class="w-6 h-6 mr-3 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __("Processing...") }}
        `;
        button.disabled = true;
        
        try {
            // Prepare form data
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', parseInt(quantity));
            
            // Add variant_id if exists
            if (variantId) {
                formData.append('variant_id', variantId);
            }
            
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update cart count
                updateCartCount(data.cart_count);
                
                // Redirect to checkout
                window.location.href = '{{ route("checkout.index") }}';
            } else {
                // Error state
                button.innerHTML = `{{ __("Error") }}!`;
                showNotification(data.message || '{{ __("Failed to process purchase") }}', 'error');
                
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }, 3000);
            }
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = `{{ __("Error") }}!`;
            showNotification('{{ __("Network error. Please try again.") }}', 'error');
            
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.disabled = false;
            }, 3000);
        }
    }
    
    // Update cart count in navbar
    function updateCartCount(count) {
        // Find cart badge in desktop navbar
        const desktopCart = document.querySelector('a[href*="cart"] .cart-badge');
        if (desktopCart) {
            desktopCart.textContent = count;
            desktopCart.classList.remove('hidden');
            desktopCart.classList.add('animate-pulse');
            setTimeout(() => desktopCart.classList.remove('animate-pulse'), 1000);
        }
        
        // Find cart badge in mobile navbar
        const mobileCart = document.querySelector('.md\\:hidden a[href*="cart"] .cart-badge');
        if (mobileCart) {
            mobileCart.textContent = count;
            mobileCart.classList.remove('hidden');
            mobileCart.classList.add('animate-pulse');
            setTimeout(() => mobileCart.classList.remove('animate-pulse'), 1000);
        }
        
        // If no badge exists, create one (for first time)
        if (count > 0) {
            const cartLinks = document.querySelectorAll('a[href*="cart"]');
            cartLinks.forEach(link => {
                let badge = link.querySelector('.cart-badge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'cart-badge absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse';
                    badge.textContent = count;
                    link.classList.add('relative');
                    link.appendChild(badge);
                    
                    // Remove pulse animation after 1 second
                    setTimeout(() => badge.classList.remove('animate-pulse'), 1000);
                }
            });
        }
    }
    
    // Add related product to cart
    async function addRelatedToCart(productId, event) {
        const button = event.currentTarget;
        const originalHtml = button.innerHTML;
        
        // Show loading state
        button.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        button.disabled = true;
        
        try {
            // Prepare form data
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Make API call
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Success state
                button.innerHTML = `
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                
                // Update cart count
                updateCartCount(data.cart_count);
                
                // Show notification
                showNotification(`✓ {{ __("Product added to cart") }}`, 'success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }, 2000);
            } else {
                // Error state
                button.innerHTML = '!';
                showNotification(data.message || '{{ __("Failed to add product") }}', 'error');
                
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }, 2000);
            }
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = '!';
            showNotification('{{ __("Network error") }}', 'error');
            
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.disabled = false;
            }, 2000);
        }
    }
    
    // Show notification
    function showNotification(message, type = 'success') {
        // Remove existing notifications
        document.querySelectorAll('.custom-notification').forEach(el => el.remove());
        
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl border-l-4 transform translate-x-full transition-transform duration-300 ${
            type === 'success' 
                ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-white border-green-500' 
                : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-white border-red-500'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3 ${
                    type === 'success' ? 'text-green-500' : 'text-red-500'
                }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${
                        type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                    }
                </svg>
                <span class="font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
        
        // Click to dismiss
        notification.addEventListener('click', () => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        });
    }
    
    // Initialize event listeners
    function initializeEventListeners() {
        // Set initial current image
        const initialMainImage = document.getElementById('mainImage');
        if (initialMainImage) {
            currentImageSrc = initialMainImage.src;
        }
        
        // Quantity input validation
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.getAttribute('max')) || 999;
                const min = parseInt(this.getAttribute('min')) || 1;
                
                if (isNaN(value) || value < min) value = min;
                if (value > max) value = max;
                
                this.value = value;
            });
            
            quantityInput.addEventListener('input', function() {
                // Prevent non-numeric input
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
        
        // Add to cart button event listener
        const addToCartBtn = document.getElementById('addToCartBtn');
        if (addToCartBtn && !addToCartBtn.disabled) {
            addToCartBtn.addEventListener('click', function(e) {
                const productId = this.getAttribute('data-product-id');
                addToCart(productId, e);
            });
        }
        
        // Buy now button event listener
        const buyNowBtn = document.getElementById('buyNowBtn');
        if (buyNowBtn && !buyNowBtn.disabled) {
            buyNowBtn.addEventListener('click', function(e) {
                const productId = this.getAttribute('data-product-id');
                buyNow(productId, e);
            });
        }
        
        // Wishlist functionality
        const wishlistBtn = document.getElementById('wishlistBtn');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function() {
                const heartIcon = this.querySelector('svg');
                const isFilled = heartIcon.classList.contains('fill-rose-500');
                
                // Toggle visual state
                if (isFilled) {
                    heartIcon.classList.remove('fill-rose-500');
                } else {
                    heartIcon.classList.add('fill-rose-500');
                }
                
                // Show notification
                showNotification(
                    isFilled 
                        ? '{{ __("Removed from wishlist") }}' 
                        : '{{ __("Added to wishlist!") }}',
                    'success'
                );
            });
        }
        
        // Variant radio button change listeners
        document.querySelectorAll('.variant-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                updateVariantDisplay(this.value);
            });
        });
        
        // Make functions globally available
        window.changeImage = changeImage;
        window.changeQuantity = changeQuantity;
        window.updateVariantDisplay = updateVariantDisplay;
        window.addRelatedToCart = addRelatedToCart;
    }
    
    // Initialize everything
    initializeEventListeners();
});
</script>