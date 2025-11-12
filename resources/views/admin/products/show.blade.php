<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $product->title['en'] ?? 'Product Details' }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    SKU: {{ $product->sku ?? 'No SKU' }} • 
                    Created: {{ $product->created_at->format('M j, Y') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">
                {{-- Header: image + core details --}}
                <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row gap-8">
                        {{-- Main Image --}}
                        <div class="w-full lg:w-2/5">
                            <div class="space-y-4">
                                @php $mainImage = $product->mainImage()->first(); @endphp
                                @if($mainImage)
                                    <div class="relative rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                                        <img src="{{ $mainImage->url }}" alt="Main Image" class="w-full h-80 lg:h-96 object-cover">
                                    </div>
                                @else
                                    <div class="w-full h-80 lg:h-96 bg-gray-100 dark:bg-gray-700 flex items-center justify-center rounded-2xl">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-gray-500 mt-2">No Image</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Gallery --}}
                                @if($product->galleryImages->count())
                                    <div class="flex space-x-3 overflow-x-auto pb-2">
                                        @foreach($product->galleryImages as $img)
                                            <div class="flex-shrink-0">
                                                <img src="{{ $img->url }}" class="w-20 h-20 rounded-xl object-cover shadow-sm border-2 border-transparent hover:border-indigo-500 transition duration-150">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Main details --}}
                        <div class="w-full lg:w-3/5 space-y-6">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->title['en'] ?? 'Untitled' }}</h1>
                                
                                <div class="flex flex-wrap items-center gap-3 mt-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                                        {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        <span class="w-2 h-2 rounded-full {{ $product->is_active ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></span>
                                        {{ $product->is_active ? 'Active' : 'Draft' }}
                                    </span>

                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                                        {{ $product->is_featured ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        {{ $product->is_featured ? 'Featured' : 'Standard' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Brand --}}
                            @if($product->brand)
                                <div class="flex items-center space-x-2 text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Brand:</span>
                                    <a href="{{ route('admin.brands.show', $product->brand) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition duration-150">
                                        {{ $product->brand->name }}
                                    </a>
                                </div>
                            @endif

                            {{-- Pricing Summary --}}
                            @if(!$product->variants->count())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Price</span>
                                            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                                ${{ number_format($product->price ?? 0, 2) }}
                                            </span>
                                        </div>
                                        @if($product->compare_at_price)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600 dark:text-gray-400">Compare at</span>
                                                <span class="text-lg text-red-600 dark:text-red-400 line-through">
                                                    ${{ number_format($product->compare_at_price ?? 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                        @if($product->cost)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600 dark:text-gray-400">Cost</span>
                                                <span class="text-lg text-blue-600 dark:text-red-400">
                                                    ${{ number_format($product->cost ?? 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($product->track_stock)
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600 dark:text-gray-400">Stock Quantity</span>
                                                <span class="text-xl font-semibold {{ $product->stock_quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $product->stock_quantity ?? 0 }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600 dark:text-gray-400">Track Stock</span>
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Enabled
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Meta Information --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Created: {{ $product->created_at->format('M j, Y \\a\\t g:i A') }}</span>
                                </div>
                                @if($product->published_at)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Published: {{ $product->published_at->format('M j, Y \\a\\t g:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content Sections --}}
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    {{-- Description --}}
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Description
                        </h3>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                            {!! $product->description['en'] ?? '<p class="text-gray-500 italic">No description available.</p>' !!}
                        </div>
                    </div>

                    {{-- Categories, Collections & Tags --}}
                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    Categories
                                </h4>
                                <div class="space-y-2">
                                    @forelse($product->categories as $cat)
                                        <a href="{{ route('admin.categories.show', $cat) }}" 
                                           class="block px-3 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition duration-150 text-gray-700 dark:text-gray-300">
                                            {{ $cat->name }}
                                        </a>
                                    @empty
                                        <p class="text-gray-500 text-sm">No categories</p>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Collections
                                </h4>
                                <div class="space-y-2">
                                    @forelse($product->collections as $col)
                                        <a href="{{ route('admin.collections.show', $col) }}" 
                                           class="block px-3 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition duration-150 text-gray-700 dark:text-gray-300">
                                            {{ $col->title }}
                                        </a>
                                    @empty
                                        <p class="text-gray-500 text-sm">No collections</p>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Tags
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($product->tags as $tag)
                                        <span class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg text-sm">
                                            {{ $tag->name }}
                                        </span>
                                    @empty
                                        <p class="text-gray-500 text-sm">No tags</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Options --}}
                    @if($product->options->count())
                        <div class="p-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Product Options
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($product->options as $option)
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-4 bg-white dark:bg-gray-700/50">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ $option->name }}</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($option->values ?? [] as $value)
                                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm">
                                                    {{ $value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Variants --}}
                    @if($product->variants->count())
                        <div class="p-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    Product Variants
                                </h3>
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-sm">
                                    {{ $product->variants->count() }} variants
                                </span>
                            </div>
                            <div class="overflow-hidden border border-gray-200 dark:border-gray-600 rounded-2xl">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Variant</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($product->variants as $variant)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center space-x-3">
                                                            @if($variant->image)
                                                                <img src="{{ $variant->image->url }}" class="w-10 h-10 rounded-lg object-cover">
                                                            @endif
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $variant->title }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $variant->sku }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($variant->price ?? 0, 2) }}</div>
                                                        @if($variant->compare_at_price)
                                                            <div class="text-xs text-red-600 dark:text-red-400 line-through">${{ number_format($variant->compare_at_price ?? 0, 2) }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-semibold {{ $variant->stock_quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                            {{ $variant->stock_quantity ?? 0 }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex flex-col space-y-1">
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $variant->track_quantity ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                                {{ $variant->track_quantity ? 'Tracked' : 'Not Tracked' }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $variant->taxable ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                                {{ $variant->taxable ? 'Taxable' : 'Non-taxable' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Shipping --}}
                    @if($product->shipping && $product->shipping->requires_shipping)
                        <div class="p-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Shipping Details
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->shipping->weight ?? '0' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Weight (kg)</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->shipping->width ?? '0' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Width (cm)</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->shipping->height ?? '0' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Height (cm)</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->shipping->length ?? '0' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Length (cm)</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>