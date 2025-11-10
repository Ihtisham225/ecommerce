<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100"
    x-data="variantManager(
        {{ json_encode($product->options->map(fn($opt) => [
            'name' => $opt->name,
            'values' => $opt->values,
        ]) ?? []) }},
        {{ json_encode($product->variants ?? []) }},
        {{ json_encode($product->documents ?? []) }}
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
    <template x-if="hasOptions">
        <div class="space-y-4 mb-8">
            <template x-for="(option, index) in options" :key="index">
                <div class="border-2 border-dashed border-gray-200 p-5 rounded-lg hover:border-gray-300 transition-colors">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Option Name</label>
                            <input type="text" x-model="option.name" placeholder="e.g. Size, Color, Material"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                                @input="generateVariants()"
                                @input.debounce.1000ms="triggerAutosave()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Option Values</label>
                            <input type="text" x-model="option.values"
                                placeholder="Separate with commas: S, M, L"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                                @input="generateVariants()"
                                @input.debounce.1000ms="triggerAutosave()">
                            <p class="text-xs text-gray-500 mt-1">Separate values with commas</p>
                        </div>
                    </div>
                    <button type="button" 
                            class="mt-3 text-red-600 text-sm hover:text-red-800 flex items-center gap-1 transition-colors"
                            @click="removeOption(index)">
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
    </template>

    {{-- VARIANTS MANAGEMENT --}}
    <template x-if="hasOptions">
        <div class="bg-white dark:bg-gray-800 rounded-lg">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Product Variants') }}</h3>
                    <p class="text-sm text-gray-500" x-text="`${variants.length} variants generated`"></p>
                </div>

                <div class="flex gap-2">
                    <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors text-sm flex items-center gap-2"
                            @click="bulkEdit = !bulkEdit"
                            x-text="bulkEdit ? 'Exit Bulk Edit' : 'Bulk Edit'">
                    </button>
                    <button type="button"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>

            {{-- BULK EDIT MODE --}}
            <template x-if="bulkEdit">
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="(variant, idx) in variants" :key="idx">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900" x-text="variant.title"></td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <select x-model="variant.image_id"
                                                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select image</option>
                                            <template x-for="img in media" :key="img.id">
                                                <option :value="img.id" x-text="img.alt || 'Image ' + img.id"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="text" x-model="variant.sku" 
                                                class="w-24 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="number" step="0.01" x-model="variant.price" 
                                                class="w-20 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="number" step="0.01" x-model="variant.compare_at_price" 
                                                class="w-20 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="number" step="0.01" x-model="variant.cost" 
                                                class="w-20 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <template x-if="variant.track_quantity">
                                            <input type="number" x-model="variant.quantity" 
                                                    class="w-16 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2 border">
                                        </template>
                                        <template x-if="!variant.track_quantity">
                                            <span class="text-gray-400 text-xs italic">—</span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>

            {{-- CARD MODE (Default) --}}
            <template x-if="!bulkEdit">
                <div class="grid gap-4">
                    <template x-if="variants.length === 0">
                        <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">No variants yet. Add option values above to generate variants.</p>
                        </div>
                    </template>

                    <template x-for="(variant, idx) in variants" :key="idx">
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-gray-300 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-semibold text-gray-900" x-text="variant.title"></h4>
                                <button type="button" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Image Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Variant Image</label>

                                    <!-- Existing main image -->
                                    <template x-if="variant.existing_main_document_id">
                                        <div class="relative w-24 h-24 rounded-lg overflow-hidden border">
                                            <img :src="productMedia.find(img => img.id === variant.existing_main_document_id)?.url"
                                                class="object-cover w-full h-full">
                                            <button type="button"
                                                    @click="removeMainImage(variant)"
                                                    class="absolute top-1 right-1 bg-white/80 rounded-full p-1 text-red-600 hover:bg-white">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- New upload preview -->
                                    <template x-if="variant.new_main_image">
                                        <div class="relative w-24 h-24 rounded-lg overflow-hidden border mt-2">
                                            <img :src="variant.preview" class="object-cover w-full h-full">
                                            <button type="button"
                                                    @click="variant.new_main_image = null; variant.preview = null"
                                                    class="absolute top-1 right-1 bg-white/80 rounded-full p-1 text-red-600 hover:bg-white">
                                                ✕
                                            </button>
                                        </div>
                                    </template>

                                    <!-- File input -->
                                    <div class="mt-2">
                                        <input type="file"
                                            accept="image/*"
                                            @change="handleImageUpload($event, variant)"
                                            class="block text-sm text-gray-600">
                                    </div>

                                    <!-- Select from existing gallery -->
                                    <div class="mt-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Or choose from product gallery</label>
                                        <select x-model="variant.existing_main_document_id"
                                                class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">— Select —</option>
                                            <template x-for="img in productMedia" :key="img.id">
                                                <option :value="img.id" x-text="img.name || ('Image ' + img.id)"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- SKU -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        SKU (Auto-generated)
                                    </label>
                                    <input type="text"
                                        x-model="variant.sku"
                                        placeholder="Auto-generated by system"
                                        disabled
                                        class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed
                                            focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                                </div>

                                <!-- Pricing -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                                    <input type="number" step="0.01" x-model.number="variant.price"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border" @input.debounce.1000ms="triggerAutosave()">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Compare at Price</label>
                                    <input type="number" step="0.01" x-model.number="variant.compare_at_price"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border" @input.debounce.1000ms="triggerAutosave()">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost per Item</label>
                                    <input type="number" step="0.01" x-model.number="variant.cost"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border" @input.debounce.1000ms="triggerAutosave()">
                                </div>

                                <!-- Profit & Margin Display -->
                                <div class="col-span-full">
                                    <div class="bg-gray-50 p-3 rounded-lg" x-show="variant.price && variant.cost">
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Profit:</span>
                                                <span class="font-semibold text-green-600 ml-2" 
                                                        x-text="'$' + (variant.price - variant.cost).toFixed(2)"></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Margin:</span>
                                                <span class="font-semibold text-green-600 ml-2" 
                                                        x-text="((variant.price - variant.cost) / variant.price * 100).toFixed(1) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory -->
                                <div class="col-span-full border-t pt-4 mt-2">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="flex items-center gap-3">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" x-model="variant.track_quantity" 
                                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @input.debounce.1000ms="triggerAutosave()">
                                                <span class="text-sm font-medium text-gray-700">Track quantity</span>
                                            </label>
                                        </div>
                                        <template x-if="variant.track_quantity">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity in Stock</label>
                                                <input type="number" x-model.number="variant.quantity"
                                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border" @input.debounce.1000ms="triggerAutosave()">
                                            </div>
                                        </template>
                                        <div class="flex items-center gap-3">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" x-model="variant.taxable"
                                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @input.debounce.1000ms="triggerAutosave()">
                                                <span class="text-sm font-medium text-gray-700">Charge tax on this variant</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>

<script>
function variantManager(initialOptions = [], initialVariants = [], productMedia = []) {
    return {
        // 🧩 Product Options (e.g., Size, Color)
        options: initialOptions.length ? initialOptions : [{ name: '', values: '' }],

        // 🧬 Variants (each combination of options)
        variants: initialVariants.length ? initialVariants.map(v => ({
            id: v.id ?? null,
            title: v.title ?? '',
            sku: v.sku ?? '', // Auto-generated — not editable
            barcode: v.barcode ?? '',
            price: parseFloat(v.price ?? 0),
            compare_at_price: parseFloat(v.compare_at_price ?? 0),
            cost: parseFloat(v.cost ?? 0),
            stock_quantity: parseInt(v.stock_quantity ?? 0),
            track_quantity: v.track_quantity ?? true,
            taxable: v.taxable ?? true,
            options: v.options ?? {},
            documents: v.documents ?? [],
            new_main_image: null,
            existing_main_document_id: v.documents?.find(d => d.document_type === 'main')?.id ?? null,
            gallery_remove_ids: [],
            existing_gallery_ids: v.documents
                ?.filter(d => d.document_type === 'gallery')
                ?.map(d => d.id) ?? [],
        })) : [],

        // 🖼 Product Media Reference
        productMedia: productMedia || [],
        bulkEdit: false,

        // ➕ Add a new option row (e.g., Size)
        addOption() {
            this.options.push({ name: '', values: '' });
            this.generateVariants();
        },

        // ➖ Remove an option and rebuild variants
        removeOption(index) {
            this.options.splice(index, 1);
            this.generateVariants();
        },

        // ⚙️ Generate all variant combinations based on current options
        generateVariants() {
            const validOptions = this.options
                .filter(o => o.name && o.values.trim())
                .map(o => ({
                    name: o.name.trim(),
                    values: o.values.split(',')
                        .map(v => v.trim())
                        .filter(Boolean)
                }));

            if (!validOptions.length) {
                this.variants = [];
                return;
            }

            const combinations = validOptions.reduce((acc, option) => {
                if (acc.length === 0) return option.values.map(v => [v]);
                return acc.flatMap(a => option.values.map(v => [...a, v]));
            }, []);

            // Build new variant list or reuse existing where possible
            const newVariants = combinations.map(values => {
                const title = values.join(' / ');
                const existing = this.variants.find(v => v.title === title);

                return existing || {
                    id: null,
                    title,
                    sku: '', // Will be auto-generated on save
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
                    documents: [],
                    new_main_image: null,
                    existing_main_document_id: null,
                    gallery_remove_ids: [],
                    existing_gallery_ids: [],
                };
            });

            this.variants = newVariants;
        },

        // 📸 Variant image upload
        handleImageUpload(event, variant) {
            const file = event.target.files[0];
            if (!file) return;
            variant.new_main_image = file;
            variant.preview = URL.createObjectURL(file);
        },

        // 🗑 Remove main image
        removeMainImage(variant) {
            if (variant.existing_main_document_id) {
                variant.gallery_remove_ids.push(variant.existing_main_document_id);
            }
            variant.existing_main_document_id = null;
            variant.new_main_image = null;
            variant.preview = null;
        },

        // 🧾 Serialize and attach variant/options data before submit/autosave
        prepareSubmission(form) {
            const sanitizedVariants = this.variants.map(v => ({
                id: v.id,
                title: v.title,
                sku: v.sku, // will be ignored or regenerated on backend
                barcode: v.barcode,
                price: parseFloat(v.price || 0),
                compare_at_price: parseFloat(v.compare_at_price || 0),
                cost: parseFloat(v.cost || 0),
                stock_quantity: parseInt(v.stock_quantity || 0),
                track_quantity: !!v.track_quantity,
                taxable: !!v.taxable,
                options: v.options || {},
                existing_main_document_id: v.existing_main_document_id,
                gallery_remove_ids: v.gallery_remove_ids,
                existing_gallery_ids: v.existing_gallery_ids,
            }));

            const sanitizedOptions = this.options.map(o => ({
                name: o.name.trim(),
                values: o.values.split(',').map(v => v.trim()).filter(Boolean),
            }));

            form.querySelector('[x-ref="variantsJson"]').value = JSON.stringify(sanitizedVariants);
            form.querySelector('[x-ref="optionsJson"]').value = JSON.stringify(sanitizedOptions);
        },
    };
}

</script>