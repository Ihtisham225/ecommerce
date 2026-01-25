<!-- Purchase Items Section -->
<div id="purchase-items-section" class="{{ old('type', $expense?->type ?? '') != 'purchase' ? 'hidden' : '' }} space-y-4">
    <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Purchase Items') }}</h4>
    
    <!-- Selected Products Table -->
    <div id="products-container" class="space-y-4">
        @if(old('products') || (isset($expense) && $expense->purchaseItems->count() > 0))
            @php
                $productsData = old('products', isset($expense) ? $expense->purchaseItems->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'description' => $item->description
                    ];
                })->toArray() : []);
            @endphp
            
            @foreach($productsData as $index => $product)
                @php
                    $productModel = App\Models\Product::find($product['product_id']);
                    $variantModel = $product['variant_id'] ? App\Models\ProductVariant::find($product['variant_id']) : null;
                @endphp
                <div class="selected-product-item border rounded-lg p-4 bg-gray-50 dark:bg-gray-700" data-index="{{ $index }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product['product_id'] }}">
                        <input type="hidden" name="products[{{ $index }}][variant_id]" value="{{ $product['variant_id'] }}">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                            <p class="mt-1 text-sm">
                                {{ $productModel->translate('title') ?? $productModel->sku }}
                                @if($variantModel)
                                    <br><span class="text-xs text-gray-500">Variant: {{ $variantModel->title }}</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                            <input type="number" name="products[{{ $index }}][quantity]" min="1" 
                                value="{{ $product['quantity'] ?? '' }}" required 
                                class="product-quantity w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                            <input type="number" step="0.01" name="products[{{ $index }}][unit_price]" min="0" 
                                value="{{ $product['unit_price'] ?? '' }}" required 
                                class="product-unit-price w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
                            <p class="mt-1 text-sm font-semibold product-total">
                                {{ format_currency(($product['quantity'] ?? 0) * ($product['unit_price'] ?? 0)) }}
                            </p>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" class="remove-product px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-4 text-gray-500">
                {{ __('No products added yet. Click "Add Product" to add items.') }}
            </div>
        @endif
    </div>
    
    <!-- Totals Summary -->
    <div id="purchase-totals" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-2 gap-4">
            <div class="text-right">
                <span class="font-medium">Subtotal:</span>
            </div>
            <div>
                <span class="font-semibold" id="purchase-subtotal">$0.00</span>
            </div>
            <div class="text-right">
                <span class="font-medium">Grand Total:</span>
            </div>
            <div>
                <span class="font-semibold text-lg" id="purchase-grand-total">$0.00</span>
            </div>
        </div>
    </div>
    
    <div>
        <button type="button" id="open-product-modal" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            {{ __('Add Product') }}
        </button>
    </div>
</div>

<!-- Product Selection Modal -->
<div id="product-selection-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Select Product') }}</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Product Search -->
        <div class="mb-6">
            <input type="text" id="product-search" 
                   placeholder="{{ __('Search products by name or SKU...') }}"
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3">
        </div>
        
        <!-- Products Grid -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Products will be loaded here via AJAX -->
        </div>
        
        <!-- Loading State -->
        <div id="products-loading" class="hidden text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">{{ __('Loading products...') }}</p>
        </div>
        
        <!-- No Products State -->
        <div id="no-products" class="hidden text-center py-8 text-gray-500">
            {{ __('No products found.') }}
        </div>
        
        <!-- Variants Section -->
        <div id="variants-section" class="hidden mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h4 class="font-semibold text-lg mb-4">
                {{ __('Select Variant for:') }}
                <span id="selected-product-title" class="text-indigo-600"></span>
            </h4>
            
            <div id="variants-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Variants will be loaded here -->
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-variant" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-400">
                    {{ __('Cancel') }}
                </button>
                <button type="button" id="select-variant" class="hidden px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    {{ __('Select Variant') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.storeCurrency = {
        code: "{{ $currencyCode }}",
        symbol: "{{ $currencySymbol }}"
    };

    class PurchaseItems {
    constructor() {
        this.productsContainer = document.getElementById('products-container');
        this.modal = document.getElementById('product-selection-modal');
        this.productsGrid = document.getElementById('products-grid');
        this.variantsSection = document.getElementById('variants-section');
        this.variantsGrid = document.getElementById('variants-grid');
        this.selectedProductTitle = document.getElementById('selected-product-title');
        this.productsLoading = document.getElementById('products-loading');
        this.noProducts = document.getElementById('no-products');
        
        // Add this line to reference the select variant button
        this.selectVariantBtn = document.getElementById('select-variant');
        
        this.selectedProduct = null;
        this.selectedVariant = null;
        this.productRowIndex = this.getProductCount();
        
        this.initialize();
    }
    
    initialize() {
        this.bindEvents();
        this.loadProducts();
        this.initializeExistingRows();
        this.calculateTotals();
    }
    
    bindEvents() {
        // Open/Close Modal
        document.getElementById('open-product-modal')?.addEventListener('click', () => this.openModal());
        document.getElementById('close-modal')?.addEventListener('click', () => this.closeModal());
        document.getElementById('cancel-variant')?.addEventListener('click', () => this.hideVariants());
        
        // Modal close on background click
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) this.closeModal();
        });
        
        // Product search
        document.getElementById('product-search')?.addEventListener('input', (e) => this.searchProducts(e.target.value));
        
        // Variant selection
        this.selectVariantBtn?.addEventListener('click', () => this.addSelectedVariant());
        
        // Remove product
        this.productsContainer?.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-product')) {
                this.removeProduct(e.target.closest('.selected-product-item'));
            }
        });
    }
    
    async loadProducts(search = '') {
        this.showLoading();
        
        try {
            const response = await fetch(`/admin/purchase-items/search-products?search=${encodeURIComponent(search)}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderProducts(data.products);
            }
        } catch (error) {
            console.error('Error loading products:', error);
            this.showError();
        } finally {
            this.hideLoading();
        }
    }
    
    renderProducts(products) {
        this.productsGrid.innerHTML = '';
        
        if (products.length === 0) {
            this.noProducts.classList.remove('hidden');
            return;
        }
        
        this.noProducts.classList.add('hidden');
        
        products.forEach(product => {
            const productCard = this.createProductCard(product);
            this.productsGrid.appendChild(productCard);
        });
        
        // Add click event to each card
        this.productsGrid.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', () => this.selectProduct(card.dataset));
        });
    }
    
    createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer';
        card.dataset.productId = product.id;
        card.dataset.productTitle = product.title;
        card.dataset.hasVariants = product.has_variants ? '1' : '0';
        card.dataset.price = product.price;
        
        card.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-medium">${product.title}</h4>
                    <p class="text-sm text-gray-500">${product.sku}</p>
                    ${product.cost ? `<p class="text-xs text-gray-500 mt-1">Cost: ${this.formatCurrency(product.cost)}</p>` : ''}
                </div>
                <div class="text-right">
                    ${product.has_variants ? '' : `<p class="font-semibold">${this.formatCurrency(product.price)}</p>`}
                    ${product.has_variants ? `<p class="text-xs text-indigo-500">Has variants</p>` : ''}
                </div>
            </div>
        `;
        
        return card;
    }
    
    async selectProduct(productData) {
        this.selectedProduct = productData;
        
        if (productData.hasVariants === '1') {
            await this.loadVariants(productData.productId);
            this.selectedProductTitle.textContent = productData.productTitle;
            this.variantsSection.classList.remove('hidden');
            document.getElementById('select-variant')?.classList.add('hidden');
        } else {
            await this.addProduct(productData.productId, null);
            this.closeModal();
        }
    }
    
    async loadVariants(productId) {
        try {
            const response = await fetch(`/admin/purchase-items/${productId}/variants`);
            const data = await response.json();
            
            if (data.success) {
                this.renderVariants(data.variants);
            }
        } catch (error) {
            console.error('Error loading variants:', error);
            this.variantsGrid.innerHTML = '<p class="text-red-500">Error loading variants.</p>';
        }
    }
    
    renderVariants(variants) {
        this.variantsGrid.innerHTML = '';
        
        if (variants.length === 0) {
            this.variantsGrid.innerHTML = '<p class="text-gray-500">No variants available for this product.</p>';
            return;
        }
        
        variants.forEach(variant => {
            const variantCard = this.createVariantCard(variant);
            this.variantsGrid.appendChild(variantCard);
        });
        
        // Add click event to each variant card
        this.variantsGrid.querySelectorAll('.variant-card').forEach(card => {
            card.addEventListener('click', () => this.selectVariant(card));
        });
    }
    
    createVariantCard(variant) {
        const card = document.createElement('div');
        card.className = 'variant-card border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer';
        card.dataset.variantId = variant.id;
        card.dataset.variantTitle = variant.title;
        card.dataset.variantSku = variant.sku;
        card.dataset.variantPrice = variant.price;
        
        card.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-medium">${variant.title}</h4>
                    <p class="text-sm text-gray-500">${variant.sku}</p>
                    ${variant.options ? `<p class="text-xs text-gray-500 mt-1">${JSON.stringify(variant.options)}</p>` : ''}
                </div>
                <div class="text-right">
                    <p class="font-semibold">${this.formatCurrency(variant.price)}</p>
                    ${variant.cost ? `<p class="text-xs text-gray-500">Cost: ${this.formatCurrency(variant.cost)}</p>` : ''}
                </div>
            </div>
        `;
        
        return card;
    }
    
    selectVariant(variantCard) {
        // Remove previous selection
        this.variantsGrid.querySelectorAll('.variant-card').forEach(card => {
            card.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
        });
        
        // Highlight selected variant
        variantCard.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
        
        this.selectedVariant = {
            id: variantCard.dataset.variantId,
            title: variantCard.dataset.variantTitle,
            sku: variantCard.dataset.variantSku,
            price: variantCard.dataset.variantPrice
        };
        
        document.getElementById('select-variant')?.classList.remove('hidden');
    }
    
    async addSelectedVariant() {
        if (this.selectedProduct && this.selectedVariant) {
            await this.addProduct(this.selectedProduct.productId, this.selectedVariant.id);
            this.closeModal();
        }
    }
    
    async addProduct(productId, variantId) {
        try {
            let productDetails, price;
            
            if (variantId) {
                const variantResponse = await fetch(`/admin/purchase-items/variant/${variantId}`);
                const variantData = await variantResponse.json();
                productDetails = {
                    title: variantData.variant.product.title,
                    variantTitle: variantData.variant.title,
                    sku: variantData.variant.sku
                };
                price = variantData.variant.price;
            } else {
                const productResponse = await fetch(`/admin/purchase-items/product/${productId}`);
                const productData = await productResponse.json();
                productDetails = {
                    title: productData.product.title,
                    sku: productData.product.sku
                };
                price = productData.product.price;
            }
            
            this.addProductToForm(productId, variantId, price, productDetails);
            
        } catch (error) {
            console.error('Error getting product details:', error);
            // Fallback to basic product info
            this.addProductToForm(productId, variantId, price || 0, {
                title: 'Product',
                sku: 'N/A'
            });
        }
    }
    
    addProductToForm(productId, variantId, price, productDetails) {
        const index = this.productRowIndex++;
        
        const template = `
            <div class="selected-product-item border rounded-lg p-4 bg-gray-50 dark:bg-gray-700" data-index="${index}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="products[${index}][product_id]" value="${productId}">
                    <input type="hidden" name="products[${index}][variant_id]" value="${variantId || ''}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                        <p class="mt-1 text-sm">
                            ${productDetails.title}
                            ${productDetails.variantTitle ? `<br><span class="text-xs text-gray-500">Variant: ${productDetails.variantTitle}</span>` : ''}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                        <input type="number" name="products[${index}][quantity]" min="1" 
                               value="1" required 
                               class="product-quantity w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2"
                               data-index="${index}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                        <input type="number" step="0.01" name="products[${index}][unit_price]" min="0" 
                               value="${price}" required 
                               class="product-unit-price w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2"
                               data-index="${index}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
                        <p class="mt-1 text-sm font-semibold product-total" data-index="${index}">
                            ${this.formatCurrency(price)}
                        </p>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="button" class="remove-product px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        this.productsContainer.insertAdjacentHTML('beforeend', template);
        
        // Update the "no products" message if it exists
        const noProductsMessage = this.productsContainer.querySelector('.text-center');
        if (noProductsMessage) {
            noProductsMessage.remove();
        }
        
        // Add event listeners for price calculation
        this.initializeRow(this.productsContainer.lastElementChild);
        this.calculateTotals();
    }
    
    initializeRow(row) {
        const quantityInput = row.querySelector('.product-quantity');
        const priceInput = row.querySelector('.product-unit-price');
        const totalElement = row.querySelector('.product-total');
        
        const updateTotal = () => {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = quantity * price;
            totalElement.textContent = this.formatCurrency(total);
            this.calculateTotals();
        };
        
        quantityInput.addEventListener('input', updateTotal);
        priceInput.addEventListener('input', updateTotal);
        updateTotal();
    }
    
    initializeExistingRows() {
        this.productsContainer.querySelectorAll('.selected-product-item').forEach(row => {
            this.initializeRow(row);
        });
    }
    
    removeProduct(row) {
        row.remove();
        this.reindexProducts();
        this.calculateTotals();
    }
    
    reindexProducts() {
        const rows = this.productsContainer.querySelectorAll('.selected-product-item');
        
        rows.forEach((row, index) => {
            row.dataset.index = index;
            
            // Update hidden inputs
            const inputs = row.querySelectorAll('input[name*="products"]');
            inputs.forEach(input => {
                input.name = input.name.replace(/products\[\d+\]/, `products[${index}]`);
            });
        });
        
        this.productRowIndex = rows.length;
        
        // Show "no products" message if empty
        if (rows.length === 0) {
            this.productsContainer.innerHTML = `
                <div class="text-center py-4 text-gray-500">
                    ${document.getElementById('products-container').dataset.noProductsMessage || 'No products added yet. Click "Add Product" to add items.'}
                </div>
            `;
        }
    }
    
    async calculateTotals() {
        const items = [];
        const rows = this.productsContainer.querySelectorAll('.selected-product-item');
        
        rows.forEach(row => {
            const quantity = parseFloat(row.querySelector('.product-quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.product-unit-price').value) || 0;
            
            items.push({
                quantity: quantity,
                unit_price: unitPrice
            });
        });
        
        const subtotal = items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
        
        // Update UI
        document.getElementById('purchase-subtotal').textContent = this.formatCurrency(subtotal);
        document.getElementById('purchase-grand-total').textContent = this.formatCurrency(subtotal);
        
        // If you need to send to server for more complex calculations:
        /*
        try {
            const response = await fetch('/purchase-items/calculate-totals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ items: items })
            });
            
            const data = await response.json();
            if (data.success) {
                document.getElementById('purchase-subtotal').textContent = this.formatCurrency(data.subtotal);
                document.getElementById('purchase-grand-total').textContent = this.formatCurrency(data.grand_total);
            }
        } catch (error) {
            console.error('Error calculating totals:', error);
        }
        */
    }
    
    openModal() {
        this.modal.classList.remove('hidden');
        this.resetModal();
        this.loadProducts();
    }
    
    closeModal() {
        this.modal.classList.add('hidden');
        this.resetModal();
    }
    
    hideVariants() {
        this.variantsSection.classList.add('hidden');
        this.selectedProduct = null;
        this.selectedVariant = null;
    }
    
    resetModal() {
        this.selectedProduct = null;
        this.selectedVariant = null;
        document.getElementById('product-search').value = '';
        this.variantsSection.classList.add('hidden');
        document.getElementById('select-variant')?.classList.add('hidden');
    }
    
    searchProducts(term) {
        this.loadProducts(term);
    }
    
    showLoading() {
        this.productsLoading.classList.remove('hidden');
        this.productsGrid.classList.add('hidden');
        this.noProducts.classList.add('hidden');
    }
    
    hideLoading() {
        this.productsLoading.classList.add('hidden');
        this.productsGrid.classList.remove('hidden');
    }
    
    showError() {
        this.noProducts.textContent = 'Error loading products';
        this.noProducts.classList.remove('hidden');
        this.productsGrid.classList.add('hidden');
    }
    
    getProductCount() {
        return document.querySelectorAll('.selected-product-item').length;
    }
    
    formatCurrency(amount) {
    amount = parseFloat(amount) || 0;

        const currencyCode = window.storeCurrency?.code || 'USD';
        const currencySymbol = window.storeCurrency?.symbol || '$';

        // Some currencies (PKR, INR, etc.) look better without Intl currency style
        const formatter = new Intl.NumberFormat(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        return `${currencySymbol}${formatter.format(amount)}`;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.purchaseItems = new PurchaseItems();
    
    // Toggle purchase items section based on type select
    const typeSelect = document.querySelector('select[name="type"]');
    const purchaseItemsSection = document.getElementById('purchase-items-section');
    
    if (typeSelect && purchaseItemsSection) {
        const togglePurchaseItems = () => {
            if (typeSelect.value === 'purchase') {
                purchaseItemsSection.classList.remove('hidden');
            } else {
                purchaseItemsSection.classList.add('hidden');
            }
        };
        
        typeSelect.addEventListener('change', togglePurchaseItems);
        togglePurchaseItems(); // Initial check
    }
});
</script>