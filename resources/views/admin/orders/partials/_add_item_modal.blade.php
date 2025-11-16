<div
    x-data="addItemModal()"
    @open-add-item.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @keydown.escape="reset()"
>
    <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] flex flex-col">
        
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add Product to Order</h2>
            <button 
                @click="reset()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Search Section --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    x-model="search"
                    @input.debounce.300ms="searchProducts()"
                    placeholder="Search by product name, SKU, or variant..."
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    :disabled="loading"
                >
                <template x-if="loading">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </template>
            </div>

            {{-- Search Stats --}}
            <div class="mt-3 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <span x-text="searchStats"></span>
                <span x-show="results.length > 0" x-text="`${results.length} products found`"></span>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto">
            {{-- Search Results --}}
            <template x-if="results.length > 0 && !selectedProduct">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="product in results" :key="product.id">
                        <div
                            class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                            @click="selectProduct(product)"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white" x-text="product.title['en']"></div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span class="font-mono" x-text="'SKU: ' + product.sku"></span>
                                        <span class="mx-2">•</span>
                                        <span x-text="formatCurrency(product.price)"></span>
                                        <template x-if="product.stock_quantity !== null">
                                            <span class="mx-2">•</span>
                                            <span :class="product.stock_quantity > 0 ? 'text-green-600' : 'text-red-600'" 
                                                  x-text="`Stock: ${product.stock_quantity}`"></span>
                                        </template>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Variant Selection --}}
            <template x-if="selectedProduct && selectedProduct.variants?.length">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Select Variant</h3>
                    <template x-for="variant in selectedProduct.variants" :key="variant.id">
                        <button @click="selectVariant(variant)" 
                                :class="selectedVariant?.id === variant.id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'"
                                class="w-full p-3 border rounded mb-2 text-left">
                            <div class="flex justify-between">
                                <span x-text="variant.name"></span>
                                <span x-text="formatCurrency(variant.price)"></span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1" x-text="`SKU: ${variant.sku} | Stock: ${variant.stock_quantity}`"></div>
                        </button>
                    </template>
                </div>
            </template>


            {{-- No Results --}}
            <template x-if="results.length === 0 && search.length >= 2 && !loading">
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-sm">No products found matching "<span x-text="search"></span>"</p>
                    <p class="text-xs mt-1">Try searching by product name, SKU, or variant name</p>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-b-xl">
            <div class="flex items-center justify-between">
                <div x-show="selectedProduct" class="text-sm text-gray-600 dark:text-gray-400">
                    <template x-if="selectedVariant">
                        <span x-text="`Selected: ${selectedVariant.name}`"></span>
                    </template>
                    <template x-if="!selectedVariant && selectedProduct">
                        <span x-text="`Selected: ${selectedProduct.title}`"></span>
                    </template>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        @click="reset()"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="addToOrder()"
                        :disabled="!selectedProduct || (selectedProduct.variants?.length && !selectedVariant)"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        Add to Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addItemModal() {
    return {
        open: false,
        search: '',
        results: [],
        selectedProduct: null,
        selectedVariant: null,
        loading: false,
        searchCount: 0,

        get searchStats() {
            if (this.search.length === 0) return 'Start typing to search products...';
            if (this.search.length < 2) return 'Type at least 2 characters to search';
            if (this.loading) return 'Searching...';
            return 'Search by name, SKU, or variant';
        },

        async searchProducts() {
            if (this.search.length < 2) {
                this.results = [];
                return;
            }

            this.loading = true;
            this.searchCount++;

            try {
                const response = await fetch(`/admin/products/search?q=${encodeURIComponent(this.search)}&t=${Date.now()}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                // Only update results if this is the most recent search
                if (this.searchCount === this.searchCount) {
                    this.results = Array.isArray(data) ? data : [];
                }
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
                // You could show a user-friendly error message here
            } finally {
                this.loading = false;
            }
        },

        selectProduct(product) {
            this.selectedProduct = product;
            this.selectedVariant = null;
            
            // Auto-select if only one variant
            if (product.variants?.length === 1) {
                this.selectedVariant = product.variants[0];
            }
        },

        selectVariant(variant) {
            this.selectedVariant = variant;
        },

        addToOrder() {
            const product = this.selectedProduct;
            const variant = this.selectedVariant;

            if (!product) return;

            const item = {
                product_id: product.id,
                product_variant_id: variant ? variant.id : null,
                sku: variant ? variant.sku : product.sku,
                title: product.title['en'] || 'Untitled',
                variant_name: variant ? variant.name : null,
                price: variant ? parseFloat(variant.price) : parseFloat(product.price),
                qty: 1,
                total: variant ? parseFloat(variant.price) : parseFloat(product.price),
            };

            // Add to parent component's items array
            const parentComponent = this.$root._x_dataStack[0];
            parentComponent.items.push(item);
            parentComponent.triggerAutosave();
            
            this.reset();
            this.showNotification('✅ Item added to order');
        },

        formatCurrency(amount) {
            const decimals = '{{ $currencySymbol }}' === 'KD' ? 3 : 2;
            return '{{ $currencySymbol }}' + parseFloat(amount || 0).toFixed(decimals);
        },

        reset() {
            this.open = false;
            this.search = '';
            this.results = [];
            this.selectedProduct = null;
            this.selectedVariant = null;
            this.loading = false;
        },

        showNotification(message) {
            // You can implement a notification system here
            console.log(message);
        }
    }
}
</script>