<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100"
    x-data="variantManager(
        {{ $product->id }},
        {{ json_encode($product->options->map(fn($opt) => [
            'id' => $opt->id,
            'name' => $opt->name,
            'values' => $opt->values,
        ]) ?? []) }},
        {{ json_encode($product->variants->map(fn($v) => [
            'id' => $v->id,
            'title' => $v->title,
            'sku' => $v->sku,
            'barcode' => $v->barcode,
            'price' => (float)$v->price,
            'compare_at_price' => (float)$v->compare_at_price,
            'cost' => (float)$v->cost,
            'stock_quantity' => (int)$v->stock_quantity,
            'track_quantity' => (bool)$v->track_quantity,
            'taxable' => (bool)$v->taxable,
            'options' => $v->options,
            'image_id' => $v->image_id,
        ]) ?? []) }},
        {{ json_encode($product->documents->map(fn($doc) => [
            'id' => $doc->id,
            'url' => $doc->url,
            'name' => $doc->name,
            'document_type' => $doc->document_type,
        ]) ?? []) }},
        '{{ $storeSetting?->currency_code ?? 'USD' }}',
        '{{ $currencySymbol }}'
    )">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-900">{{ __('Product Options & Variants') }}</h3>
            <p class="text-sm text-gray-500">Add options like size, color, etc. to create variants</p>
        </div>
    </div>

    {{-- OPTIONS BUILDER --}}
    <div class="space-y-4 mb-8">
        <template x-for="(option, index) in options" :key="index">
            <div class="border border-gray-200 p-5 rounded-lg hover:border-gray-300 transition-colors">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Option Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Option Name</label>
                        <div class="flex gap-2">
                            <select x-model="option.name"
                                @change="saveOption(index)"
                                class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                                <option value="">Select option type</option>
                                <option value="Color">Color</option>
                                <option value="Size">Size</option>
                                <option value="Material">Material</option>
                                <option value="Style">Style</option>
                                <option value="custom">Custom option</option>
                            </select>
                            <template x-if="option.name === 'custom'">
                                <input type="text"
                                    x-model="option.customName"
                                    placeholder="Enter custom option"
                                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                                    @blur="saveOption(index)">
                            </template>
                        </div>
                    </div>

                    <!-- Option Values -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Option Values</label>
                        <div class="border border-gray-300 rounded-md p-2 min-h-[42px] focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                            <div class="flex flex-wrap gap-1 mb-1">
                                <template x-for="(value, valueIndex) in option.values" :key="valueIndex">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <span x-text="value"></span>
                                        <button type="button"
                                            @click="removeValue(index, valueIndex)"
                                            class="ml-1 text-indigo-600 hover:text-indigo-800">
                                            ×
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <input type="text"
                                x-ref="`valueInput${index}`"
                                placeholder="Type a value and press Enter"
                                class="w-full border-0 p-0 focus:ring-0 text-sm"
                                @keydown.enter.prevent="addValue(index, $event.target)"
                                @blur="if($event.target.value) addValue(index, $event.target)">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Press Enter to add values</p>
                    </div>
                </div>
                <button type="button"
                    class="mt-3 text-red-600 text-sm hover:text-red-800 flex items-center gap-1 transition-colors"
                    @click="deleteOption(index)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Remove Option
                </button>
            </div>
        </template>

        <button type="button"
            class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:text-gray-700 hover:border-gray-400 transition-colors flex items-center justify-center gap-2"
            @click="addOption()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Another Option
        </button>
    </div>

    {{-- VARIANTS MANAGEMENT --}}
    <template x-if="hasOptions">
        <div class="bg-white dark:bg-gray-800 rounded-lg">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Product Variants') }}</h3>
                    <p class="text-sm text-gray-500" x-text="`${variants.length} variants`"></p>
                </div>
                <button type="button"
                    @click="generateVariants()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    Generate Variants
                </button>
            </div>

            <!-- Quick Actions Toolbar -->
            <div class="mb-6 p-5 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Quick Apply to All Variants</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <!-- Price -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Price</label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                </div>
                                <input type="number"
                                    :step="stepValue"
                                    x-model="quickActions.price"
                                    placeholder="0.00"
                                    class="w-full pl-6 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            </div>
                            <button type="button"
                                @click="applyToAll('price', quickActions.price)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Compare at Price -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Compare at Price</label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                </div>
                                <input type="number"
                                    :step="stepValue"
                                    x-model="quickActions.compare_at_price"
                                    placeholder="0.00"
                                    class="w-full pl-6 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            </div>
                            <button type="button"
                                @click="applyToAll('compare_at_price', quickActions.compare_at_price)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Cost -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cost</label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                </div>
                                <input type="number"
                                    :step="stepValue"
                                    x-model="quickActions.cost"
                                    placeholder="0.00"
                                    class="w-full pl-6 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            </div>
                            <button type="button"
                                @click="applyToAll('cost', quickActions.cost)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Quantity</label>
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="quickActions.stock_quantity"
                                placeholder="0"
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            <button type="button"
                                @click="applyToAll('stock_quantity', quickActions.stock_quantity)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Second Row: Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                    <!-- Track Quantity -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Track Quantity</label>
                        <div class="flex items-center gap-2">
                            <select x-model="quickActions.track_quantity"
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                                <option value="true">Track Quantity</option>
                                <option value="false">Don't Track</option>
                            </select>
                            <button type="button"
                                @click="applyToAll('track_quantity', quickActions.track_quantity === 'true')"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Tax Setting -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tax Setting</label>
                        <div class="flex items-center gap-2">
                            <select x-model="quickActions.taxable"
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                                <option value="true">Taxable</option>
                                <option value="false">Not Taxable</option>
                            </select>
                            <button type="button"
                                @click="applyToAll('taxable', quickActions.taxable === 'true')"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Quick Actions</label>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                @click="clearAllQuickActions()"
                                class="flex-1 px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-xs font-medium transition">
                                Clear All
                            </button>
                            <button type="button"
                                @click="applyAllQuickActions()"
                                class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-medium transition">
                                Apply All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VARIANTS TABLE --}}
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compare at</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(variant, idx) in variants" :key="idx">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900" x-text="variant.title"></td>

                                <!-- Image Selection -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative" x-data="{ open: false }">
                                        <button type="button"
                                            @click="open = !open"
                                            class="flex items-center gap-2 p-1 border border-gray-300 rounded-md hover:border-gray-400 transition-colors">
                                            <template x-if="getVariantImage(variant)">
                                                <div class="w-10 h-10 rounded border overflow-hidden flex-shrink-0">
                                                    <img :src="getVariantImage(variant)" class="w-full h-full object-contain">
                                                </div>
                                            </template>
                                            <template x-if="!getVariantImage(variant)">
                                                <div class="w-10 h-10 rounded border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                        </button>

                                        <div x-show="open"
                                            @click.away="open = false"
                                            class="absolute top-full left-0 mt-1 w-48 max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-md shadow-lg z-10">
                                            <div class="p-2">
                                                <button type="button"
                                                    @click="variant.image_id = null; open = false; saveVariant(variant)"
                                                    class="w-full flex items-center gap-2 p-2 rounded hover:bg-gray-100 text-left mb-2">
                                                    <div class="w-8 h-8 rounded border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">No image</span>
                                                </button>

                                                <div class="grid grid-cols-2 gap-1">
                                                    <template x-for="img in productMedia" :key="img.id">
                                                        <button type="button"
                                                            @click="variant.image_id = img.id; open = false; saveVariant(variant)"
                                                            class="aspect-square rounded border-2 transition-all"
                                                            :class="variant.image_id == img.id ? 'border-indigo-500' : 'border-gray-200 hover:border-gray-300'">
                                                            <img :src="img.url" class="w-full h-full object-contain rounded">
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pricing -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                        </div>
                                        <input type="number"
                                            :step="stepValue"
                                            x-model="variant.price"
                                            @blur="saveVariant(variant)"
                                            @input="updateVariantCalculations(variant)"
                                            class="w-20 pl-6 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </div>
                                </td>

                                <!-- Compare at Price -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                        </div>
                                        <input type="number"
                                            :step="stepValue"
                                            x-model="variant.compare_at_price"
                                            @blur="saveVariant(variant)"
                                            class="w-20 pl-6 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border"
                                            placeholder="0.00">
                                    </div>
                                </td>

                                <!-- Cost -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                        </div>
                                        <input type="number"
                                            :step="stepValue"
                                            x-model="variant.cost"
                                            @blur="saveVariant(variant)"
                                            @input="updateVariantCalculations(variant)"
                                            class="w-20 pl-6 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </div>
                                </td>

                                <!-- Profit & Margin -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <template x-if="variant.price > 0 && variant.cost > 0">
                                        <div class="text-xs">
                                            <div class="font-semibold text-green-600"
                                                x-text="`${currencySymbol}${calculateProfit(variant).toFixed(maxDecimals)}`"></div>
                                            <div class="text-gray-500"
                                                x-text="`${calculateMargin(variant)}%`"></div>
                                        </div>
                                    </template>
                                    <template x-if="!(variant.price > 0 && variant.cost > 0)">
                                        <span class="text-gray-400 text-xs">—</span>
                                    </template>
                                </td>

                                <!-- Inventory -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" x-model="variant.track_quantity"
                                            @change="saveVariant(variant)"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <template x-if="variant.track_quantity">
                                            <input type="number" x-model="variant.stock_quantity"
                                                @blur="saveVariant(variant)"
                                                class="w-16 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                        </template>
                                        <template x-if="!variant.track_quantity">
                                            <span class="text-gray-400 text-xs">∞</span>
                                        </template>
                                    </div>
                                </td>

                                <!-- Tax -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox" x-model="variant.taxable"
                                        @change="saveVariant(variant)"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button type="button"
                                        @click="deleteVariant(variant, idx)"
                                        class="text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Summary Footer -->
            <div class="mt-4 flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div class="text-sm text-gray-600">
                    <span x-text="variants.length"></span> variants
                </div>
                <div class="text-sm text-gray-600" x-show="totalProfit > 0">
                    Total Profit: <span class="font-semibold text-green-600" x-text="`${currencySymbol}${totalProfit.toFixed(maxDecimals)}`"></span>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function variantManager(productId, initialOptions = [], initialVariants = [], productMedia = [], currencyCode = 'USD', currencySymbol = '$') {
        return {
            productId: productId,
            options: initialOptions.map(opt => ({
                id: opt.id,
                name: opt.name,
                customName: '',
                values: opt.values
            })) || [],
            variants: initialVariants || [],
            productMedia: productMedia || [],
            currencyCode: currencyCode,
            currencySymbol: currencySymbol,

            // Quick Actions
            quickActions: {
                price: '',
                compare_at_price: '',
                cost: '',
                stock_quantity: '',
                track_quantity: 'true',
                taxable: 'true'
            },

            // Getters
            get maxDecimals() {
                return this.currencyCode === 'KWD' ? 3 : 2;
            },
            get stepValue() {
                return this.currencyCode === 'KWD' ? 0.001 : 0.01;
            },
            get hasOptions() {
                return this.options.some(opt => opt.name && opt.values.length > 0);
            },
            get totalProfit() {
                return this.variants.reduce((total, variant) => {
                    return total + this.calculateProfit(variant);
                }, 0);
            },

            // Option Methods
            getOptionName(option) {
                return option.name === 'custom' ? option.customName : option.name;
            },

            addOption() {
                this.options.push({
                    id: null,
                    name: '',
                    customName: '',
                    values: []
                });
            },

            async saveOption(index) {
                const option = this.options[index];
                const optionName = this.getOptionName(option);

                if (!optionName || option.values.length === 0) {
                    this.showToast('Option name and values are required', 'error');
                    return;
                }

                try {
                    const url = option.id ?
                        `/admin/products/${this.productId}/options/${option.id}` :
                        `/admin/products/${this.productId}/options`;

                    const method = option.id ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name: optionName,
                            values: option.values
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        if (!option.id) {
                            option.id = data.option.id;
                        }
                        this.showToast('Option saved successfully', 'success');
                        this.generateVariants();
                    } else {
                        this.showToast('Failed to save option', 'error');
                    }
                } catch (error) {
                    this.showToast('Failed to save option', 'error');
                }
            },

            async deleteOption(index) {
                const option = this.options[index];

                if (option.id) {
                    if (!confirm('Are you sure you want to delete this option? All variants will be deleted.')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/products/${this.productId}/options`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                option_ids: [option.id]
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.options.splice(index, 1);
                            this.variants = [];
                            this.showToast('Option deleted successfully', 'success');
                        } else {
                            this.showToast('Failed to delete option', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to delete option', 'error');
                    }
                } else {
                    this.options.splice(index, 1);
                }
            },

            addValue(optionIndex, inputElement) {
                const trimmedValue = inputElement.value.trim();

                if (trimmedValue && !this.options[optionIndex].values.includes(trimmedValue)) {
                    this.options[optionIndex].values.push(trimmedValue);
                    this.saveOption(optionIndex);
                }

                inputElement.value = '';
            },

            removeValue(optionIndex, valueIndex) {
                this.options[optionIndex].values.splice(valueIndex, 1);
                this.saveOption(optionIndex);
            },

            // Variant Methods
            generateVariants() {
                const validOptions = this.options
                    .filter(opt => this.getOptionName(opt) && opt.values.length > 0)
                    .map(opt => ({
                        name: this.getOptionName(opt),
                        values: opt.values
                    }));

                if (!validOptions.length) {
                    this.variants = [];
                    return;
                }

                const combinations = validOptions.reduce((acc, option) => {
                    if (acc.length === 0) return option.values.map(v => [v]);
                    return acc.flatMap(a => option.values.map(v => [...a, v]));
                }, []);

                const newVariants = combinations.map(values => {
                    const title = values.join(' / ');
                    const existing = this.variants.find(v => v.title === title);

                    if (existing) {
                        return existing;
                    }

                    // Generate SKU for new variant
                    const base = this.productId ? `PROD-${this.productId}` : 'NEW';
                    const variantText = values.join('-').replace(/\s+/g, '-').toUpperCase();
                    const timestamp = Date.now().toString().slice(-6);

                    return {
                        id: null,
                        title,
                        sku: `${base}-${variantText}-${timestamp}`,
                        barcode: '',
                        price: 0,
                        compare_at_price: 0,
                        cost: 0,
                        stock_quantity: 0,
                        track_quantity: true,
                        taxable: true,
                        options: validOptions.reduce((acc, opt, i) => {
                            acc[opt.name] = values[i];
                            return acc;
                        }, {}),
                        image_id: null,
                    };
                });

                this.variants = newVariants;
            },

            async saveVariant(variant) {
                try {
                    // Ensure SKU is generated if not present
                    if (!variant.sku) {
                        const base = this.productId ? `PROD-${this.productId}` : 'NEW';
                        const variantText = variant.title.replace(/\s+/g, '-').replace(/\//g, '-').toUpperCase();
                        const timestamp = Date.now().toString().slice(-6);
                        variant.sku = `${base}-${variantText}-${timestamp}`;
                    }

                    const url = variant.id ?
                        `/admin/products/${this.productId}/variants/${variant.id}` :
                        `/admin/products/${this.productId}/variants`;

                    const method = variant.id ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(variant)
                    });

                    const data = await response.json();
                    if (data.success) {
                        if (!variant.id) {
                            variant.id = data.variant.id;
                            variant.sku = data.variant.sku; // Use server-generated SKU
                        }
                        this.showToast('Variant saved successfully', 'success');
                    } else {
                        this.showToast('Failed to save variant', 'error');
                    }
                } catch (error) {
                    this.showToast('Failed to save variant', 'error');
                }
            },

            async saveAllVariants() {
                try {
                    const variantsToUpdate = this.variants.filter(v => v.id);

                    if (variantsToUpdate.length === 0) return;

                    const response = await fetch(`/admin/products/${this.productId}/variants/batch`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            variants: variantsToUpdate.map(v => ({
                                id: v.id,
                                price: v.price,
                                compare_at_price: v.compare_at_price,
                                cost: v.cost,
                                stock_quantity: v.stock_quantity,
                                track_quantity: v.track_quantity,
                                taxable: v.taxable,
                                image_id: v.image_id
                            }))
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.showToast('All variants saved successfully', 'success');
                    } else {
                        this.showToast('Failed to save variants', 'error');
                    }
                } catch (error) {
                    this.showToast('Failed to save variants', 'error');
                }
            },

            async deleteVariant(variant, index) {
                if (!confirm('Are you sure you want to delete this variant?')) {
                    return;
                }

                if (variant.id) {
                    try {
                        const response = await fetch(`/admin/products/${this.productId}/variants/${variant.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.variants.splice(index, 1);
                            this.showToast('Variant deleted successfully', 'success');
                        } else {
                            this.showToast('Failed to delete variant', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to delete variant', 'error');
                    }
                } else {
                    this.variants.splice(index, 1);
                }
            },

            // Helper Methods
            getVariantImage(variant) {
                if (!variant.image_id) return null;
                const media = this.productMedia.find(img => img.id == variant.image_id);
                return media ? media.url : null;
            },

            calculateProfit(variant) {
                return Math.max(0, (variant.price || 0) - (variant.cost || 0));
            },

            calculateMargin(variant) {
                if (!variant.price || variant.price <= 0) return 0;
                const profit = this.calculateProfit(variant);
                return ((profit / variant.price) * 100).toFixed(1);
            },

            updateVariantCalculations(variant) {
                // This function is called when price or cost changes
                // The profit display will automatically update due to Alpine's reactivity
            },

            applyToAll(field, value) {
                if (value === null || value === undefined || value === '') return;

                this.variants.forEach(variant => {
                    if (field === 'price' || field === 'compare_at_price' || field === 'cost') {
                        variant[field] = parseFloat(value);
                    } else if (field === 'stock_quantity') {
                        variant[field] = parseInt(value);
                    } else if (field === 'taxable' || field === 'track_quantity') {
                        variant[field] = Boolean(value);
                    }
                });
            },

            clearAllQuickActions() {
                this.quickActions = {
                    price: '',
                    compare_at_price: '',
                    cost: '',
                    stock_quantity: '',
                    track_quantity: 'true',
                    taxable: 'true'
                };
            },

            applyAllQuickActions() {
                if (this.quickActions.price) this.applyToAll('price', this.quickActions.price);
                if (this.quickActions.compare_at_price) this.applyToAll('compare_at_price', this.quickActions.compare_at_price);
                if (this.quickActions.cost) this.applyToAll('cost', this.quickActions.cost);
                if (this.quickActions.stock_quantity) this.applyToAll('stock_quantity', this.quickActions.stock_quantity);
                this.applyToAll('track_quantity', this.quickActions.track_quantity === 'true');
                this.applyToAll('taxable', this.quickActions.taxable === 'true');
            },

            showToast(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                type === 'success' ? 'bg-green-600 text-white' : 
                type === 'error' ? 'bg-red-600 text-white' : 
                'bg-blue-600 text-white'
            }`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        };
    }
</script>