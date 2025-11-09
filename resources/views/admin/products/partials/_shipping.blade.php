<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100" x-data="{ shipping: {{ $product->requires_shipping ? 'true' : 'false' }} }">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Shipping') }}</h3>
    </div>

    <div class="space-y-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" name="requires_shipping" x-model="shipping" value="1"
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <div>
                    <span class="font-medium text-gray-900">This product requires shipping</span>
                    <p class="text-sm text-gray-500">Customer will enter shipping address at checkout</p>
                </div>
            </label>
        </div>

        <template x-if="shipping">
            <div class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
                <h4 class="font-medium text-gray-900">Package Dimensions</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" value="{{ $product->weight ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Width (cm)</label>
                        <input type="number" step="0.01" name="width" value="{{ $product->width ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                        <input type="number" step="0.01" name="height" value="{{ $product->height ?? '' }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>