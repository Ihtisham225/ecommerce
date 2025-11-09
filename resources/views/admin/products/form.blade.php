<x-app-layout>
    {{-- Single Alpine wrapper --}}
    <div x-data="productForm()">
        {{-- ✅ Sticky Header --}}
        <div class="sticky top-0 z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center px-6 py-3 shadow-sm backdrop-blur-md bg-opacity-90 dark:bg-opacity-90">
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

        {{-- ✅ Main Form --}}
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">

                {{-- LEFT SIDE --}}
                <div class="flex-1 space-y-6">
                    {{-- BASIC INFO --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('Basic Information') }}</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Product Title *</label>
                                <input type="text" name="title[en]" x-model="title"
                                    @input.debounce.1000ms="triggerAutosave()"
                                    placeholder="Enter product title"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4 border">
                                <p class="text-xs text-gray-500 mt-1"
                                x-text="`${title.length}/120 characters`"
                                :class="title.length > 100 ? 'text-amber-600' : ''"></p>
                            </div>
                            
                            <div
                                x-data
                                x-init="
                                    ClassicEditor
                                        .create($refs.editor, {
                                            toolbar: [
                                                'heading', '|', 'bold', 'italic', 'link',
                                                'bulletedList', 'numberedList', 'blockQuote', '|',
                                                'undo', 'redo'
                                            ],
                                            placeholder: 'Describe your product...',
                                        })
                                        .then(editor => {
                                            // Set initial content
                                            editor.setData(description || '');

                                            // Watch CKEditor content -> update Alpine + autosave
                                            let typingTimer;
                                            editor.model.document.on('change:data', () => {
                                                clearTimeout(typingTimer);
                                                typingTimer = setTimeout(() => {
                                                    description = editor.getData();
                                                    triggerAutosave();
                                                }, 1500); // same debounce as before
                                            });

                                            // Watch Alpine updates -> reflect in CKEditor (if changed externally)
                                            $watch('description', value => {
                                                if (value !== editor.getData()) editor.setData(value);
                                            });
                                        })
                                        .catch(error => console.error(error));
                                "
                            >
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>

                                <div class="relative">
                                    <div x-ref="editor" class="border border-gray-300 rounded-md shadow-sm"></div>
                                    <div class="absolute bottom-2 right-3 text-xs text-gray-400" x-text="`${description?.length || 0}/2000`"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MEDIA --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('Media Gallery') }}</h3>
                        </div>
                        @include('admin.products.partials._images', ['product' => $product])
                    </div>

                    {{-- VARIANTS --}}
                    @include('admin.products.partials._variants', ['product' => $product])

                    {{-- PRICING --}}
                    @include('admin.products.partials._pricing', ['product' => $product])

                    {{-- INVENTORY --}}
                    @include('admin.products.partials._inventory', ['product' => $product])

                    {{-- SHIPPING --}}
                    @include('admin.products.partials._shipping', ['product' => $product])

                    {{-- SEO --}}
                    @include('admin.products.partials._seo', ['product' => $product])
                </div>

                {{-- RIGHT SIDE --}}
                @include('admin.products.partials._organization', ['product' => $product])
            </div>
        </div>
    </div>

    {{-- ✅ Alpine.js Logic --}}
    <script>
        function productForm() {
            return {
                productId: {{ $product->id }},
                title: @js($product->title['en'] ?? ''),
                description: @js($product->description['en'] ?? ''),
                sku: @js($product->sku ?? ''),
                price: {{ $product->price ?? 0 }},
                cost: {{ $product->cost ?? 0 }},
                compare_at_price: {{ $product->compare_at_price ?? 0 }},
                hasOptions: {{ $product->has_options ? 'true' : 'false' }},
                charge_tax: {{ $product->charge_tax ? 'true' : 'false' }},
                shipping: {{ $product->requires_shipping ? 'true' : 'false' }},
                autosaveTimer: null,
                saving: false,

                // 🧮 Computed fields
                get profit() {
                    if (!this.price || !this.cost) return 0;
                    return this.price - this.cost;
                },
                get margin() {
                    if (!this.price || !this.cost || this.price == 0) return 0;
                    return ((this.price - this.cost) / this.price) * 100;
                },
                get formattedProfit() {
                    return this.profit ? `$${this.profit.toFixed(2)}` : '$0.00';
                },
                get formattedMargin() {
                    return this.margin ? `${this.margin.toFixed(1)}%` : '0%';
                },

                triggerAutosave() {
                    clearTimeout(this.autosaveTimer);
                    this.autosaveTimer = setTimeout(() => this.autosave(), 1500);
                },

                async autosave() {
                    this.saving = true;

                    // 🧩 Get data from the variant manager
                    const variantComponent = document.querySelector('[x-data^="variantManager"]')?._x_dataStack?.[0];
                    const variantsJson = JSON.stringify(variantComponent?.variants || []);
                    const optionsJson = JSON.stringify(variantComponent?.options || []);

                    const payload = {
                        title: { en: this.title },
                        description: { en: this.description },
                        price: this.price,
                        cost: this.cost,
                        compare_at_price: this.compare_at_price,
                        has_options: this.hasOptions ? 1 : 0,
                        charge_tax: this.charge_tax ? 1 : 0,
                        requires_shipping: this.shipping ? 1 : 0,
                        variants_json: variantsJson,
                        options_json: optionsJson,
                    };

                    try {
                        const response = await fetch(`/admin/products/${this.productId}/autosave`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            this.updateProductData(data.product);
                            this.showNotification('✅ Draft autosaved successfully!', 'success');
                        } else {
                            this.showNotification(data.message || 'Autosave failed', 'error');
                        }
                    } catch (e) {
                        this.showNotification('Autosave error: ' + e.message, 'error');
                    } finally {
                        this.saving = false;
                    }
                },

                // ✅ Update entire product form from backend response
                updateProductData(product) {
                    // ---- Basic Info ----
                    this.title = product.title?.en || '';
                    this.description = product.description?.en || '';
                    this.sku = product.sku || '';

                    // ---- Pricing ----
                    this.price = product.price || 0;
                    this.cost = product.cost || 0;
                    this.compare_at_price = product.compare_at_price || 0;
                    this.hasOptions = product.has_options ?? false;
                    this.shipping = product.requires_shipping ?? false;
                    this.charge_tax = product.charge_tax ?? false;

                    // ---- Update "Editing / Create" title ----
                    if (this.$refs.productTitle) {
                        this.$refs.productTitle.textContent = product?.id
                            ? `Editing: ${product.title?.en || 'Untitled'}`
                            : 'Create New Product';
                    }

                    // ---- Inventory ----
                    const skuInput = document.querySelector('input[name="sku"]');
                    if (skuInput) skuInput.value = product.sku || '';

                    const stockInput = document.querySelector('input[name="stock_quantity"]');
                    if (stockInput) stockInput.value = product.stock_quantity || 0;

                    const trackCheckbox = document.querySelector('input[x-model="track"]');
                    if (trackCheckbox) trackCheckbox.checked = !!product.track_stock;

                    // ---- Brand ----
                    const brandSelect = document.querySelector('select[name="brand_id"]');
                    if (brandSelect && product.brand) {
                        brandSelect.value = product.brand.id;
                    }

                    // ---- Categories ----
                    const categorySelect = document.querySelector('select[name="category_id"]');
                    if (categorySelect && product.categories?.length) {
                        categorySelect.value = product.categories[0].id;
                    }

                    // ---- Images (main + gallery) ----
                    if (product.documents?.length) {
                        const galleryContainer = document.querySelector('#gallery-container');
                        if (galleryContainer) {
                            galleryContainer.innerHTML = '';
                            product.documents
                                .filter(d => d.document_type === 'gallery')
                                .forEach(doc => {
                                    const img = document.createElement('img');
                                    img.src = doc.url;
                                    img.className = 'w-24 h-24 object-cover rounded-md border';
                                    galleryContainer.appendChild(img);
                                });
                        }

                        const mainImageContainer = document.querySelector('#main-image');
                        if (mainImageContainer) {
                            const main = product.documents.find(d => d.document_type === 'main');
                            if (main) {
                                mainImageContainer.src = main.url;
                            }
                        }
                    }

                    // ---- Variants ----
                    const variantsContainer = document.querySelector('#variants-container');
                    if (variantsContainer) {
                        variantsContainer.innerHTML = '';
                        (product.variants || []).forEach(variant => {
                            const div = document.createElement('div');
                            div.className = 'p-3 border rounded-lg flex justify-between items-center';
                            div.innerHTML = `
                                <div>
                                    <p class="font-semibold">${variant.title || 'Untitled Variant'}</p>
                                    <p class="text-sm text-gray-500">SKU: ${variant.sku || 'N/A'}</p>
                                </div>
                                <p class="text-sm">$${variant.price ?? 0}</p>
                            `;
                            variantsContainer.appendChild(div);
                        });
                    }

                    // ---- SEO ----
                    const metaTitle = document.querySelector('input[name="meta_title"]');
                    const metaDesc = document.querySelector('textarea[name="meta_description"]');
                    if (metaTitle) metaTitle.value = product.meta_title || '';
                    if (metaDesc) metaDesc.value = product.meta_description || '';

                    const titlePreview = document.getElementById('seo-preview-title');
                    const descPreview = document.getElementById('seo-preview-description');
                    if (titlePreview) titlePreview.textContent = product.meta_title || this.title;
                    if (descPreview) descPreview.textContent = product.meta_description || this.description;
                },

                async saveDraft() {
                    await this.autosave();
                },

                showNotification(message, type = 'info') {
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
                    }, 4000);
                }
            };
        }
    </script>

</x-app-layout>
