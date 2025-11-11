<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Search Engine Optimization') }}</h3>
    </div>

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
            <input type="text" name="meta_title" value="{{ $product->meta_title ?? '' }}" placeholder="Optimized page title for search engines" @input.debounce.1000ms="triggerAutosave()"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <textarea name="meta_description" rows="3" placeholder="Brief description for search engine results" @input.debounce.1000ms="triggerAutosave()"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">{{ $product->meta_description ?? '' }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
        </div>
        
        {{-- SEO Preview --}}
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Search Preview</h4>
            <div class="space-y-1 text-sm">
                <div class="text-blue-600 font-medium truncate" id="seo-preview-title">
                    {{ $product->meta_title ?: ($product->title['en'] ?? 'Product Title') }}
                </div>
                <div class="text-green-600 text-xs" id="seo-preview-url">
                    {{ config('app.url') }}/products/{{ $product->slug ?? 'product-slug' }}
                </div>
                <div class="text-gray-600 truncate" id="seo-preview-description">
                    {{ $product->meta_description ?: ($product->description['en'] ?? 'Product description will appear here...') }}
                </div>
            </div>
        </div>
    </div>
</div>