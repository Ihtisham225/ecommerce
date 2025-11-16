<tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
    {{-- Product Title --}}
    <td class="px-4 py-3 align-top">
        <div class="font-medium text-gray-900 dark:text-white" x-text="item.title"></div>
        <template x-if="item.variant_name">
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <span x-text="item.variant_name"></span>
            </div>
        </template>
    </td>

    {{-- SKU --}}
    <td class="px-4 py-3 align-top">
        <span class="text-gray-600 dark:text-gray-300 font-mono text-sm" x-text="item.sku"></span>
    </td>

    {{-- Qty --}}
    <td class="px-4 py-3">
        <input
            type="number"
            step="1"
            min="1"
            class="w-20 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
            x-model.number="item.qty"
            @input.debounce.500ms="updateItemTotals()"
        >
    </td>

    {{-- Price --}}
    <td class="px-4 py-3">
        <div class="relative">
            <input
                type="number"
                step="0.001"
                class="w-28 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                x-model.number="item.price"
                @input.debounce.500ms="updateItemTotals()"
            >
            <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs text-gray-400">
                {{ $currencySymbol }}
            </span>
        </div>
    </td>

    {{-- Total --}}
    <td class="px-4 py-3">
        <div class="font-medium text-gray-900 dark:text-white">
            <span x-text="(parseFloat(item.qty || 0) * parseFloat(item.price || 0)).toFixed(3)"></span>
            <span class="text-gray-500 text-sm ml-1">{{ $currencySymbol }}</span>
        </div>
    </td>

    {{-- Remove --}}
    <td class="px-4 py-3 text-center">
        <button
            @click="removeItem(index)"
            class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20"
            title="Remove item"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                </path>
            </svg>
        </button>
    </td>
</tr>