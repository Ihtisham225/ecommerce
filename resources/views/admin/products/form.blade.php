<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Product Editor') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $product->exists ? 'Editing: ' . ($product->title['en'] ?? 'Untitled') : 'Create New Product' }}</p>
            </div>

            <div class="flex gap-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    {{ __('Save Draft') }}
                </button>
                <button id="publish-product-btn"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Save & Publish') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">

            {{-- LEFT SIDE – Main Form --}}
            <div class="flex-1 space-y-6" x-data="productForm()">
                {{-- Progress Indicator --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-indigo-600 font-medium">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center">1</div>
                            <span>Basic Info</span>
                        </div>
                        <div class="h-1 w-8 bg-gray-200"></div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">2</div>
                            <span>Media</span>
                        </div>
                        <div class="h-1 w-8 bg-gray-200"></div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">3</div>
                            <span>Variants</span>
                        </div>
                        <div class="h-1 w-8 bg-gray-200"></div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">4</div>
                            <span>Pricing</span>
                        </div>
                    </div>
                </div>

                {{-- 1️⃣ BASIC INFO --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Basic Information') }}</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Title *</label>
                            <input type="text" name="title[en]" x-model="title" placeholder="Enter product title"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors py-3 px-4 border"
                                :class="title ? 'border-green-300 bg-green-50' : 'border-gray-300'">
                            <p class="text-xs text-gray-500 mt-1" x-text="`${title.length}/120 characters`" :class="title.length > 100 ? 'text-amber-600' : ''"></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <div class="relative">
                                <textarea name="description[en]" rows="6" x-model="description" placeholder="Describe your product..."
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors py-3 px-4 border resize-none"></textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-gray-400" x-text="`${description.length}/2000`"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2️⃣ MEDIA --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Media Gallery') }}</h3>
                    </div>
                    @include('admin.products.partials._images', ['product' => $product])
                </div>

                {{-- 3️⃣ OPTIONS + VARIANTS --}}
                @include('admin.products.partials._variants', ['product' => $product])

                {{-- 4️⃣ PRICING --}}
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

                {{-- 5️⃣ INVENTORY --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100" x-data="{ track: {{ $product->track_stock ? 'true' : 'false' }} }">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Inventory') }}</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU (Stock Keeping Unit)</label>
                            <input type="text" name="sku" value="{{ $product->sku ?? '' }}" placeholder="PROD-001"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" x-model="track" 
                                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <div>
                                    <span class="font-medium text-gray-900">Track quantity</span>
                                    <p class="text-sm text-gray-500">Enable stock management for this product</p>
                                </div>
                            </label>
                        </div>

                        <template x-if="track">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                                <input type="number" name="stock_quantity" value="{{ $product->stock_quantity ?? 0 }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                                <p class="text-xs text-gray-500 mt-1">Current quantity available for sale</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- 6️⃣ SHIPPING --}}
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

                {{-- 7️⃣ SEO --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Search Engine Optimization') }}</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ $product->meta_title ?? '' }}" placeholder="Optimized page title for search engines"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" placeholder="Brief description for search engine results"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">{{ $product->meta_description ?? '' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                        </div>
                        
                        {{-- SEO Preview --}}
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Search Preview</h4>
                            <div class="space-y-1 text-sm">
                                <div class="text-blue-600 font-medium truncate" id="seo-preview-title">
                                    {{ $product->meta_title ?: ($product->title['en'] ?? 'Product Title') }}
                                </div>
                                <div class="text-green-600 text-xs" id="seo-preview-url">
                                    {{ config('app.url') }}/products/{{ $product->slug ?? 'product-slug' }}
                                </div>
                                <div class="text-gray-600 truncate" id="seo-preview-description">
                                    {{ $product->meta_description ?: ($product->description['en'] ?? 'Product description will appear here...') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE – Organization Sidebar --}}
            @include('admin.products.partials._organization', ['product' => $product])
        </div>
    </div>

    {{-- Enhanced Alpine.js Logic --}}
    <script>
        function productForm() {
            return {
                price: {{ $product->price ?? 0 }},
                cost: {{ $product->cost ?? 0 }},
                compare_at_price: {{ $product->compare_at_price ?? 0 }},
                charge_tax: {{ $product->charge_tax ? 'true' : 'false' }},
                title: @js($product->title['en'] ?? ''),
                description: @js($product->description['en'] ?? ''),

                get profit() {
                    return (this.price && this.cost) ? (this.price - this.cost) : 0;
                },
                get margin() {
                    return (this.price && this.cost) ? ((this.price - this.cost) / this.price * 100) : 0;
                },
                get formattedProfit() {
                    return this.price && this.cost ? `$${this.profit.toFixed(2)}` : '$0.00';
                },
                get formattedMargin() {
                    return this.price && this.cost ? `${this.margin.toFixed(1)}%` : '0%';
                },

                async quickAdd(type) {
                    const name = prompt(`Enter new ${type} name:`);
                    if (!name) return;
                    
                    // Show loading state
                    const originalText = event.target.innerHTML;
                    event.target.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Adding...';
                    event.target.disabled = true;
                    
                    try {
                        const res = await fetch(`/admin/${type}s/quick-add`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            },
                            body: JSON.stringify({ name })
                        });
                        
                        if (res.ok) {
                            const data = await res.json();
                            // Show success message
                            this.showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} "${data.name}" created successfully!`, 'success');
                            window.location.reload();
                        } else {
                            throw new Error('Failed to create');
                        }
                    } catch (err) {
                        console.error(err);
                        this.showNotification(`Failed to create new ${type}`, 'error');
                    } finally {
                        event.target.innerHTML = originalText;
                        event.target.disabled = false;
                    }
                },

                showNotification(message, type = 'info') {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
                        type === 'success' ? 'bg-green-500 text-white' : 
                        type === 'error' ? 'bg-red-500 text-white' : 
                        'bg-blue-500 text-white'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    // Remove after 5 seconds
                    setTimeout(() => {
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => notification.remove(), 300);
                    }, 5000);
                }
            };
        }

        function variantManager(initialOptions = [], initialVariants = [], media = []) {
            return {
                hasOptions: initialOptions.length > 0,
                options: initialOptions.length ? initialOptions : [{ name: '', values: '' }],
                variants: initialVariants.length ? initialVariants : [],
                media: media || [],
                bulkEdit: false,

                addOption() {
                    this.options.push({ name: '', values: '' });
                    this.generateVariants();
                },

                removeOption(index) {
                    if (this.options.length > 1) {
                        this.options.splice(index, 1);
                        this.generateVariants();
                    } else {
                        this.hasOptions = false;
                        this.variants = [];
                    }
                },

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

                    const newVariants = combinations.map(values => {
                        const title = values.join(' / ');
                        const existing = this.variants.find(v => v.title === title);

                        return existing || {
                            title,
                            sku: '',
                            barcode: '',
                            price: 0,
                            compare_at_price: 0,
                            cost: 0,
                            quantity: 0,
                            weight: 0,
                            taxable: true,
                            track_quantity: true,
                            image_id: '',
                        };
                    });

                    this.variants = newVariants;
                },

                // Auto-generate SKUs for variants
                generateSKUs(baseSKU) {
                    if (!baseSKU) return;
                    
                    this.variants.forEach((variant, index) => {
                        if (!variant.sku) {
                            variant.sku = `${baseSKU}-${index + 1}`;
                        }
                    });
                }
            };
        }

        // Initialize SEO preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.querySelector('input[name="meta_title"]');
            const descInput = document.querySelector('textarea[name="meta_description"]');
            
            if (titleInput) {
                titleInput.addEventListener('input', function() {
                    document.getElementById('seo-preview-title').textContent = this.value || document.querySelector('input[name="title[en]"]').value;
                });
            }
            
            if (descInput) {
                descInput.addEventListener('input', function() {
                    document.getElementById('seo-preview-description').textContent = this.value || document.querySelector('textarea[name="description[en]"]').value;
                });
            }
        });
    </script>

</x-app-layout>