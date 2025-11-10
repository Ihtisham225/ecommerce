<x-app-layout>
    {{-- Single Alpine wrapper --}}
    <div x-data="productForm()">
        {{-- ✅ Sticky Header --}}
        @include('admin.products.partials._sticky_header', ['product' => $product])

        {{-- ✅ Main Form --}}
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">

                {{-- LEFT SIDE --}}
                <div class="flex-1 space-y-6">
                    {{-- BASIC INFO --}}
                    @include('admin.products.partials._basic_info', ['product' => $product])

                    {{-- MEDIA --}}
                    @include('admin.products.partials._media', ['product' => $product])

                    {{-- ✅ HAS OPTIONS TOGGLE (moved here) --}}
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" x-model="hasOptions" class="sr-only">
                                <div class="w-12 h-6 bg-gray-200 rounded-full transition-colors group-hover:bg-gray-300"
                                    :class="hasOptions ? 'bg-indigo-600' : 'bg-gray-200'"></div>
                                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"
                                    :class="hasOptions ? 'translate-x-6' : ''"></div>
                            </div>
                            <div>
                                <span class="font-medium text-gray-900">This product has options</span>
                                <p class="text-sm text-gray-500">Enable to add size, color, or other variations</p>
                            </div>
                        </label>
                    </div>

                    {{-- VARIANTS (only shown if hasOptions) --}}
                    <div x-show="hasOptions" x-cloak>
                        @include('admin.products.partials._variants', ['product' => $product])
                    </div>

                    {{-- PRICING (only shown if no options) --}}
                    <div x-show="!hasOptions" x-cloak>
                        @include('admin.products.partials._pricing', ['product' => $product])
                    </div>

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
                    const variantComponent = document.querySelector('[x-data^="variantManager"]')?._x_dataStack?.[0];
                    if (variantComponent && product.variants) {
                        // Replace variant data entirely with backend-updated versions
                        variantComponent.variants = product.variants.map(v => ({
                            id: v.id ?? null,
                            title: v.title ?? '',
                            sku: v.sku ?? '',
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
                        }));
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
