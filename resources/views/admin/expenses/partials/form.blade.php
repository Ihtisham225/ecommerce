<div class="space-y-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    
    {{-- Validation Errors --}}
    @if ($errors->any())
        <x-alert type="error" title="Validation Error" :message="$errors->all()" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Basic Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }} *</label>
                <input type="text" name="title" value="{{ old('title', $expense?->title ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                <textarea name="description" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('description', $expense?->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }} *</label>
                <select name="type" id="expense-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                    <option value="purchase" {{ old('type', $expense?->type ?? '') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                    <option value="operational" {{ old('type', $expense?->type ?? '') == 'operational' ? 'selected' : '' }}>Operational</option>
                    <option value="salary" {{ old('type', $expense?->type ?? '') == 'salary' ? 'selected' : '' }}>Salary</option>
                    <option value="utility" {{ old('type', $expense?->type ?? '') == 'utility' ? 'selected' : '' }}>Utility</option>
                    <option value="other" {{ old('type', $expense?->type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category') }} *</label>
                <input type="text" name="category" value="{{ old('category', $expense?->category ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Supplier') }}</label>
                <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                    <option value="">{{ __('Select Supplier') }}</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $expense?->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Financial Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount') }} *</label>
                <input type="number" step="0.01" name="amount" id="amount-field"
                    value="{{ old('amount', $expense?->amount ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required readonly>
                <p id="amount-note" class="text-xs text-gray-500 mt-1 hidden">
                    Amount will be auto-calculated based on purchase items
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tax Amount') }}</label>
                <input type="number" step="0.01" name="tax_amount" 
                    value="{{ old('tax_amount', $expense?->tax_amount ?? 0) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date') }} *</label>
                    <input type="date" name="date" 
                        value="{{ old('date', $expense?->date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Due Date') }}</label>
                    <input type="date" name="due_date" 
                        value="{{ old('due_date', $expense?->due_date?->format('Y-m-d') ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Method') }} *</label>
                <select name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                    <option value="cash" {{ old('payment_method', $expense?->payment_method ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ old('payment_method', $expense?->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="check" {{ old('payment_method', $expense?->payment_method ?? '') == 'check' ? 'selected' : '' }}>Check</option>
                    <option value="credit_card" {{ old('payment_method', $expense?->payment_method ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="digital_wallet" {{ old('payment_method', $expense?->payment_method ?? '') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                    <option value="other" {{ old('payment_method', $expense?->payment_method ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Reference') }}</label>
                <input type="text" name="payment_reference" 
                    value="{{ old('payment_reference', $expense?->payment_reference ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>
        </div>
    </div>

    <!-- Purchase Items Section -->
    @include('admin.expenses.sections.purchase-items-section')

    <!-- Product Selection Modal -->
    <div id="product-selection-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[80vh] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Select Product</h3>
            </div>
            
            <div class="p-6 overflow-auto" style="max-height: 60vh;">
                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="product-search" 
                        placeholder="Search products..." 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded p-2">
                </div>
                
                <!-- Products Grid -->
                <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($products as $product)
                        @php
                            $hasVariants = $product->variants()->exists();
                            $productTitle = $product->translate('title') ?? $product->sku;
                        @endphp
                        <div class="product-card border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                            data-product-id="{{ $product->id }}"
                            data-product-title="{{ $productTitle }}"
                            data-has-variants="{{ $hasVariants ? '1' : '0' }}"
                            data-price="{{ $product->price }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium">{{ $productTitle }}</h4>
                                    <p class="text-sm text-gray-500">{{ $product->sku }}</p>
                                    @if($hasVariants)
                                        <span class="inline-block mt-1 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                            Has Variants
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">{{ format_currency($product->price) }}</p>
                                    @if($product->cost)
                                        <p class="text-xs text-gray-500">Cost: {{ format_currency($product->cost) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Variants Selection (Initially Hidden) -->
                <div id="variants-section" class="hidden mt-6">
                    <h4 class="font-medium text-lg mb-4">Select Variant for <span id="selected-product-title"></span></h4>
                    <div id="variants-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button type="button" id="close-modal" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </button>
                <button type="button" id="select-variant" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 hidden">
                    Select Variant
                </button>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="space-y-4">
        <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Additional Information') }}</h4>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
            <textarea name="notes" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('notes', $expense?->notes ?? '') }}</textarea>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const expenseType = document.getElementById('expense-type');
    const amountField = document.getElementById('amount-field');
    const amountNote = document.getElementById('amount-note');
    
    // Initialize based on current selection
    toggleAmountField();
    
    // Listen for type changes
    expenseType.addEventListener('change', toggleAmountField);
    
    function toggleAmountField() {
        if (expenseType.value === 'purchase') {
            // Make it read-only instead of disabled
            amountField.readOnly = true;
            amountField.classList.add('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
            amountNote.classList.remove('hidden');
            calculateAndSetTotalAmount();
        } else {
            amountField.readOnly = false;
            amountField.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'cursor-not-allowed');
            amountNote.classList.add('hidden');
        }
    }
    
    function calculateAndSetTotalAmount() {
        let total = 0;
        
        // Get all purchase item rows - they have class "selected-product-item"
        const itemRows = document.querySelectorAll('.selected-product-item');
        
        console.log('Found', itemRows.length, 'purchase items');
        
        itemRows.forEach((row, index) => {
            // Look for quantity input - it has name like "products[0][quantity]"
            const quantityInput = row.querySelector('input[name*="[quantity]"]');
            // Look for unit price input - it has name like "products[0][unit_price]"
            const priceInput = row.querySelector('input[name*="[unit_price]"]');
            
            if (quantityInput && priceInput) {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const rowTotal = quantity * price;
                total += rowTotal;
                
                console.log(`Item ${index}: ${quantity} × ${price} = ${rowTotal}`);
            }
        });
        
        console.log('Total amount:', total);
        
        // Set the total amount
        amountField.value = total.toFixed(2);
        
        // Trigger change event for any other listeners
        amountField.dispatchEvent(new Event('change', { bubbles: true }));
        
        // Also trigger input event to ensure form validation sees the value
        amountField.dispatchEvent(new Event('input', { bubbles: true }));
    }
    
    // Recalculate total when purchase items change
    function setupPurchaseItemsListener() {
        const purchaseItemsContainer = document.getElementById('products-container');
        
        if (purchaseItemsContainer) {
            // Listen for input changes in quantity and unit_price fields
            purchaseItemsContainer.addEventListener('input', function(e) {
                if (e.target.name && (e.target.name.includes('quantity') || e.target.name.includes('unit_price'))) {
                    if (expenseType.value === 'purchase') {
                        calculateAndSetTotalAmount();
                    }
                }
            });
            
            // Listen for item removal - using event delegation
            purchaseItemsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product')) {
                    setTimeout(() => {
                        if (expenseType.value === 'purchase') {
                            calculateAndSetTotalAmount();
                        }
                    }, 100);
                }
            });
        }
        
        // Also listen to the purchase totals calculation in the PurchaseItems class
        if (window.purchaseItems && typeof window.purchaseItems.calculateTotals === 'function') {
            // Override or extend the calculateTotals method to also update the amount field
            const originalCalculateTotals = window.purchaseItems.calculateTotals;
            window.purchaseItems.calculateTotals = function() {
                // Call the original method
                originalCalculateTotals.call(this);
                
                // Also update the amount field if expense type is purchase
                if (expenseType.value === 'purchase') {
                    calculateAndSetTotalAmount();
                }
            };
        }
    }
    
    // Set up purchase items listener
    setupPurchaseItemsListener();
    
    // Also listen for dynamic addition of purchase items
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                // Check if any purchase items were added
                const addedPurchaseItems = Array.from(mutation.addedNodes).some(node => {
                    return node.classList && node.classList.contains('selected-product-item');
                });
                
                if (addedPurchaseItems) {
                    // Re-setup listeners for new items
                    setupPurchaseItemsListener();
                    if (expenseType.value === 'purchase') {
                        calculateAndSetTotalAmount();
                    }
                }
            }
        });
    });
    
    // Observe the products container for changes
    const productsContainer = document.getElementById('products-container');
    if (productsContainer) {
        observer.observe(productsContainer, { childList: true, subtree: true });
    }
    
    // Also recalculate when form is loaded with existing purchase items
    window.addEventListener('load', function() {
        if (expenseType.value === 'purchase') {
            calculateAndSetTotalAmount();
        }
    });
    
    // Add a global function to trigger calculation (for use by other scripts)
    window.recalculateExpenseAmount = calculateAndSetTotalAmount;
    
    // Ensure form submits properly - add a hidden field if needed
    const form = amountField.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure amount field has a value when submitting
            if (expenseType.value === 'purchase' && (!amountField.value || amountField.value === '0')) {
                // Recalculate to make sure it has a value
                calculateAndSetTotalAmount();
                
                // If still empty or zero, prevent submission
                if (!amountField.value || parseFloat(amountField.value) <= 0) {
                    e.preventDefault();
                    alert('Please add at least one purchase item with a valid quantity and price.');
                    return false;
                }
            }
        });
    }
});
</script>