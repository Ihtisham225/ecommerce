<div class="sticky top-0 z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 backdrop-blur-md bg-opacity-90 dark:bg-opacity-90 shadow-sm">
    <!-- Match same max width and horizontal padding as nav -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-3">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Editor') }}
            </h2>
            <p x-ref="productTitle" class="text-sm text-gray-500 mt-1">
                {{ $product->exists ? 'Editing: ' . ($product->title['en'] ?? 'Untitled') : 'Create New Product' }}
            </p>
        </div>

        <div class="flex gap-3">
            <!-- 📝 Save Draft Button -->
            <button type="button"
                @click.prevent="saveDraft"
                class="relative px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 
                    dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                <svg x-show="saving" x-cloak 
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 animate-spin text-gray-500"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-text="saving ? 'Saving...' : 'Update Draft'"></span>
            </button>

            <!-- 🚀 Publish Button -->
            <button id="publish-product-btn"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Save & Publish') }}
            </button>
        </div>
    </div>
</div>
