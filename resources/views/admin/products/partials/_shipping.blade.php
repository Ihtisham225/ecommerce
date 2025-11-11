<div 
    x-data="shippingManager(
        {{ $product->requires_shipping ? 'true' : 'false' }},
        {
            weight: {{ $product->weight ?? 'null' }},
            width: {{ $product->width ?? 'null' }},
            height: {{ $product->height ?? 'null' }},
            length: {{ $product->length ?? 'null' }}
        }
    )"
    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100 space-y-6"
>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Shipping') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200 space-y-6">
        <h4 class="font-medium text-gray-900">Package Dimensions</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                <input type="number" step="0.01" x-model.number="weight" @input.debounce.1000ms="triggerAutosave()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                    focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Width (cm)</label>
                <input type="number" step="0.01" x-model.number="width" @input.debounce.1000ms="triggerAutosave()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                    focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                <input type="number" step="0.01" x-model.number="height" @input.debounce.1000ms="triggerAutosave()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                    focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Length (cm)</label>
                <input type="number" step="0.01" x-model.number="length" @input.debounce.1000ms="triggerAutosave()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                    focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
        </div>
    </div>
</div>

<script>
function shippingManager(initialShipping = false, initialDimensions = {}) {
    return {
        requiresShipping: initialShipping,
        weight: initialDimensions.weight ?? null,
        width: initialDimensions.width ?? null,
        height: initialDimensions.height ?? null,
        length: initialDimensions.length ?? null,

        setRequiresShipping(state) {
            this.requiresShipping = !!state;
        },

        getShippingData() {
            return {
                requires_shipping: this.requiresShipping ? 1 : 0,
                weight: this.requiresShipping ? this.weight : null,
                width: this.requiresShipping ? this.width : null,
                height: this.requiresShipping ? this.height : null,
                length: this.requiresShipping ? this.length : null,
            };
        },
    };
}
</script>
