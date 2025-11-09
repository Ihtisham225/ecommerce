<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100" x-data="{ showTax: {{ $product->charge_tax ? 'true' : 'false' }} }">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Pricing') }}</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">$</span>
                </div>
                <input type="number" step="0.01" name="price" x-model="price"
                    class="pl-8 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Compare at Price</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">$</span>
                </div>
                <input type="number" step="0.01" name="compare_at_price" x-model="compare_at_price"
                    class="pl-8 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
            <p class="text-xs text-gray-500 mt-1">Show as discounted price</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cost per Item</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">$</span>
                </div>
                <input type="number" step="0.01" name="cost" x-model="cost"
                    class="pl-8 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
            </div>
            <p class="text-xs text-gray-500 mt-1">Your cost for this item</p>
        </div>
    </div>

    {{-- Profit & Margin Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-100">
            <label class="block text-sm font-medium text-green-800 mb-1">Estimated Profit</label>
            <p class="text-2xl font-bold text-green-900" x-text="formattedProfit"></p>
            <p class="text-xs text-green-600 mt-1" x-show="price && cost" 
                x-text="`Per item sold`"></p>
        </div>
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-lg border border-blue-100">
            <label class="block text-sm font-medium text-blue-800 mb-1">Profit Margin</label>
            <p class="text-2xl font-bold text-blue-900" x-text="formattedMargin"></p>
            <p class="text-xs text-blue-600 mt-1" x-show="price && cost" 
                x-text="`Based on current pricing`"></p>
        </div>
    </div>

    {{-- TAX SETTINGS --}}
    <div class="mt-6 pt-6 border-t border-gray-200">
        <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" name="charge_tax" value="1" x-model="charge_tax"
                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <div>
                <span class="font-medium text-gray-900">Charge tax on this product</span>
                <p class="text-sm text-gray-500">Apply standard tax rates to this product</p>
            </div>
        </label>
    </div>
</div>