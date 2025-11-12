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
            <!-- 💾 Save Button -->
            <button type="button"
                @click.prevent="saveDraft"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md transition-colors flex items-center gap-2">
                <svg x-show="saving" x-cloak 
                    class="w-4 h-4 animate-spin text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-text="saving ? 'Saving...' : 'Save'"></span>
            </button>

            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">
                Back to List
            </a>
        </div>
    </div>
</div>
