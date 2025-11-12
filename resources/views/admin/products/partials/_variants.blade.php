<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100"
    x-data="variantManager(
        {{ json_encode($product->options->map(fn($opt) => [
            'name' => $opt->name,
            'values' => $opt->values,
        ]) ?? []) }},
        {{ json_encode($product->variants ?? []) }},
        {{ json_encode($product->documents ?? []) }},
        '{{ $storeSetting?->currency_code ?? 'USD' }}',
        '{{ $currencySymbol }}'
    )"
    @submit.prevent="prepareSubmission($el)">

    <!-- Hidden JSON fields for backend -->
    <input type="hidden" name="variants_json" x-ref="variantsJson">
    <input type="hidden" name="options_json" x-ref="optionsJson">

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
                    <!-- Option Name with Predefined Options -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Option Name</label>
                        <div class="flex gap-2">
                            <select x-model="option.name" @input.debounce.1000ms="triggerAutosave()"
                                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                                    @change="if(option.name === 'custom') $nextTick(() => $refs[`customInput${index}`].focus())">
                                <option value="">Select option type</option>
                                <option value="Color">Color</option>
                                <option value="Size">Size</option>
                                <option value="Material">Material</option>
                                <option value="Style">Style</option>
                                <option value="custom">Custom option</option>
                            </select>
                            <template x-if="option.name === 'custom'">
                                <input type="text" @input.debounce.1000ms="triggerAutosave()"
                                        x-ref="`customInput${index}`"
                                        x-model="option.customName"
                                        placeholder="Enter custom option"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                                        @input.debounce.500ms="generateVariants()">
                            </template>
                        </div>
                    </div>

                    <!-- Option Values with Tag Input -->
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
                            <input type="text" @input.debounce.1000ms="triggerAutosave()"
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
                        @click="removeOption(index)"
                        @input.debounce.1000ms="triggerAutosave()">
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
                    <p class="text-sm text-gray-500" x-text="`${variants.length} variants generated`"></p>
                </div>
            </div>

            <!-- Quick Actions Toolbar -->
            <div class="mb-6 p-5 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    Quick Apply to All Variants
                </h4>

                <!-- First Row: Pricing Fields -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
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
                                       @input.debounce.1000ms="triggerAutosave()"
                                       placeholder="0.00"
                                       class="w-full pl-6 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            </div>
                            <button type="button"
                                @click="applyToAll('price', quickActions.price)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium whitespace-nowrap">
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
                                       @input.debounce.1000ms="triggerAutosave()"
                                       placeholder="0.00"
                                       class="w-full pl-6 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            </div>
                            <button type="button"
                                @click="applyToAll('compare_at_price', quickActions.compare_at_price)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium whitespace-nowrap">
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
                                       @input.debounce.1000ms="triggerAutosave()"
                                       placeholder="0.00"
                                       class="w-full pl-6 border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            </div>
                            <button type="button"
                                @click="applyToAll('cost', quickActions.cost)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium whitespace-nowrap">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Quantity</label>
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="quickActions.quantity" @input.debounce.1000ms="triggerAutosave()"
                                placeholder="0"
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                            <button type="button"
                                @click="applyToAll('quantity', quickActions.quantity)"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium whitespace-nowrap">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Second Row: Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Track Quantity -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Track Quantity</label>
                        <div class="flex items-center gap-2">
                            <select x-model="quickActions.track_quantity" @input.debounce.1000ms="triggerAutosave()"
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                                <option value="true">Track Quantity</option>
                                <option value="false">Don't Track</option>
                            </select>
                            <button type="button"
                                @click="applyToAll('track_quantity', quickActions.track_quantity === 'true')"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium whitespace-nowrap">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Tax Setting -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tax Setting</label>
                        <div class="flex items-center gap-2">
                            <select x-model="quickActions.taxable" @input.debounce.1000ms="triggerAutosave()"
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2.5 border bg-white">
                                <option value="true">Taxable</option>
                                <option value="false">Not Taxable</option>
                            </select>
                            <button type="button"
                                @click="applyToAll('taxable', quickActions.taxable === 'true')"
                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs font-medium whitespace-nowrap">
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compare at</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(variant, idx) in variants" :key="idx">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900" x-text="variant.title"></td>
                                
                                <!-- Image Selection with Visual Dropdown -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative" x-data="{ open: false }">
                                        <!-- Current Image Preview as Dropdown Trigger -->
                                        <button type="button"
                                                @click="open = !open"
                                                class="flex items-center gap-2 p-1 border border-gray-300 rounded-md hover:border-gray-400 transition-colors">
                                            <template x-if="getVariantImage(variant)">
                                                <div class="w-10 h-10 rounded border overflow-hidden flex-shrink-0">
                                                    <img :src="getVariantImage(variant)" class="w-full h-full object-cover">
                                                </div>
                                            </template>
                                            <template x-if="!getVariantImage(variant)">
                                                <div class="w-10 h-10 rounded border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu with Image Grid -->
                                        <div x-show="open"
                                             @click.away="open = false"
                                             class="absolute top-full left-0 mt-1 w-64 max-h-80 overflow-y-auto bg-white border border-gray-200 rounded-md shadow-lg z-10">
                                            <div class="p-2">
                                                <!-- No Image Option -->
                                                <button type="button"
                                                        @click="variant.image_id = null; open = false"
                                                        class="w-full flex items-center gap-3 p-2 rounded hover:bg-gray-100 text-left mb-2">
                                                    <div class="w-12 h-12 rounded border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 flex-shrink-0">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">No image</div>
                                                        <div class="text-xs text-gray-500">Remove image</div>
                                                    </div>
                                                </button>

                                                <div class="border-t pt-2">
                                                    <!-- Image Grid -->
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <template x-for="img in productMedia" :key="img.id">
                                                            <button type="button"
                                                                    @click="variant.image_id = img.id; open = false"
                                                                    class="aspect-square rounded border-2 transition-all"
                                                                    :class="variant.image_id === img.id ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-gray-300'">
                                                                <img :src="img.url" 
                                                                     :alt="img.alt || img.name"
                                                                     class="w-full h-full object-cover rounded">
                                                            </button>
                                                        </template>
                                                    </div>

                                                    <!-- Empty State -->
                                                    <template x-if="productMedia.length === 0">
                                                        <div class="text-center py-4 text-gray-500">
                                                            <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <p class="text-sm">No images available</p>
                                                            <p class="text-xs">Upload images first</p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- SKU -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" x-model="variant.sku" 
                                            class="w-24 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border"
                                            placeholder="SKU-001" disabled>
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
                                               @input.debounce.1000ms="triggerAutosave()"
                                               class="w-20 pl-6 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border"
                                               @input="updateProfit(variant)">
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                        </div>
                                        <input type="number" 
                                               :step="stepValue"
                                               x-model="variant.compare_at_price" 
                                               @input.debounce.1000ms="triggerAutosave()"
                                               placeholder="0.00"
                                               class="w-20 pl-6 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs" x-text="currencySymbol"></span>
                                        </div>
                                        <input type="number" 
                                               :step="stepValue"
                                               x-model="variant.cost" 
                                               @input.debounce.1000ms="triggerAutosave()"
                                               class="w-20 pl-6 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border"
                                               @input="updateProfit(variant)">
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
                                        <input type="checkbox" x-model="variant.track_quantity" @input.debounce.1000ms="triggerAutosave()"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <template x-if="variant.track_quantity">
                                            <input type="number" x-model="variant.quantity" @input.debounce.1000ms="triggerAutosave()"
                                                    class="w-16 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                        </template>
                                        <template x-if="!variant.track_quantity">
                                            <span class="text-gray-400 text-xs">∞</span>
                                        </template>
                                    </div>
                                </td>

                                <!-- Tax -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox" x-model="variant.taxable" @input.debounce.1000ms="triggerAutosave()"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
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
function variantManager(initialOptions = [], initialVariants = [], productMedia = [], currencyCode = 'USD', currencySymbol = '$') {
    return {
        options: initialOptions.length ? initialOptions.map(opt => ({
            name: opt.name,
            customName: '',
            values: Array.isArray(opt.values) ? opt.values : [opt.values].filter(Boolean)
        })) : [{ name: '', customName: '', values: [] }],

        variants: initialVariants.length ? initialVariants.map(v => ({
            id: v.id ?? null,
            title: v.title ?? '',
            sku: v.sku ?? '',
            barcode: v.barcode ?? '',
            price: parseFloat(v.price ?? 0),
            compare_at_price: parseFloat(v.compare_at_price ?? 0),
            cost: parseFloat(v.cost ?? 0),
            quantity: parseInt(v.quantity ?? v.stock_quantity ?? 0),
            track_quantity: v.track_quantity ?? true,
            taxable: v.taxable ?? true,
            options: v.options ?? {},
            image_id: v.image_id ?? v.documents?.find(d => d.document_type === 'main')?.id ?? null,
        })) : [],

        productMedia: productMedia || [],
        currencyCode: currencyCode,
        currencySymbol: currencySymbol,

        // Currency-aware properties
        get maxDecimals() {
            return this.currencyCode === 'KWD' ? 3 : 2;
        },
        get stepValue() {
            return this.currencyCode === 'KWD' ? 0.001 : 0.01;
        },

        // Quick Actions State
        quickActions: {
            price: '',
            compare_at_price: '',
            cost: '',
            quantity: '',
            track_quantity: 'true',
            taxable: 'true'
        },

        get hasOptions() {
            return this.options.some(opt => opt.name && opt.values.length > 0);
        },

        get totalProfit() {
            return this.variants.reduce((total, variant) => {
                return total + this.calculateProfit(variant);
            }, 0);
        },

        getOptionName(option) {
            return option.name === 'custom' ? option.customName : option.name;
        },

        addOption() {
            this.options.push({ name: '', customName: '', values: [] });
        },

        removeOption(index) {
            this.options.splice(index, 1);
            this.generateVariants();
        },

        addValue(optionIndex, inputElement) {
            const trimmedValue = inputElement.value.trim();
            
            if (trimmedValue && !this.options[optionIndex].values.includes(trimmedValue)) {
                this.options[optionIndex].values.push(trimmedValue);
                this.generateVariants();
            }
            
            // Clear the input field
            inputElement.value = '';
        },

        removeValue(optionIndex, valueIndex) {
            this.options[optionIndex].values.splice(valueIndex, 1);
            this.generateVariants();
        },

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
                    return {
                        ...existing,
                        options: validOptions.reduce((acc, opt, i) => {
                            acc[opt.name] = values[i];
                            return acc;
                        }, {})
                    };
                }

                return {
                    id: null,
                    title,
                    sku: '',
                    barcode: '',
                    price: 0,
                    compare_at_price: 0,
                    cost: 0,
                    quantity: 0,
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

        getVariantImage(variant) {
            if (!variant.image_id) return null;
            const variantImageId = parseInt(variant.image_id);
            const media = this.productMedia.find(img => parseInt(img.id) === variantImageId);
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

        updateProfit(variant) {
            // This function is called when price or cost changes
            // The profit display will automatically update due to Alpine's reactivity
        },

        applyToAll(field, value) {
            if (value === null || value === undefined || value === '') return;
            
            this.variants.forEach(variant => {
                if (field === 'price' || field === 'compare_at_price' || field === 'cost') {
                    variant[field] = parseFloat(value);
                } else if (field === 'quantity') {
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
                quantity: '',
                track_quantity: 'true',
                taxable: 'true'
            };
        },

        applyAllQuickActions() {
            if (this.quickActions.price) this.applyToAll('price', this.quickActions.price);
            if (this.quickActions.compare_at_price) this.applyToAll('compare_at_price', this.quickActions.compare_at_price);
            if (this.quickActions.cost) this.applyToAll('cost', this.quickActions.cost);
            if (this.quickActions.quantity) this.applyToAll('quantity', this.quickActions.quantity);
            this.applyToAll('track_quantity', this.quickActions.track_quantity === 'true');
            this.applyToAll('taxable', this.quickActions.taxable === 'true');
        },

        prepareSubmission(form) {
            const sanitizedVariants = this.variants.map(v => ({
                id: v.id,
                title: v.title,
                sku: v.sku,
                barcode: v.barcode,
                price: parseFloat(v.price || 0),
                compare_at_price: parseFloat(v.compare_at_price || 0),
                cost: parseFloat(v.cost || 0),
                quantity: parseInt(v.quantity || 0),
                track_quantity: !!v.track_quantity,
                taxable: !!v.taxable,
                options: v.options || {},
                image_id: v.image_id,
            }));

            const sanitizedOptions = this.options
                .filter(opt => this.getOptionName(opt) && opt.values.length > 0)
                .map(opt => ({
                    name: this.getOptionName(opt),
                    values: opt.values
                }));

            form.querySelector('[x-ref="variantsJson"]').value = JSON.stringify(sanitizedVariants);
            form.querySelector('[x-ref="optionsJson"]').value = JSON.stringify(sanitizedOptions);
        },
    };
}
</script>