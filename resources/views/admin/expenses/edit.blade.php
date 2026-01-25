<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Expense') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="bg-indigo-600 px-6 py-4 rounded-t-lg">
                    <h1 class="text-2xl font-bold text-white">{{ __('Edit Expense') }}</h1>
                </div>
                <div class="p-6 border border-gray-200 border-t-0 rounded-b-lg">
                    <form action="{{ route('admin.expenses.update', $expense) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        @include('admin.expenses.partials.form', ['expense' => $expense])

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                ← {{ __('Back to Expenses') }}
                            </a>

                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700">
                                {{ __('Update Expense') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('select[name="type"]');
            const purchaseItemsSection = document.getElementById('purchase-items-section');
            const productsContainer = document.getElementById('products-container');
            const addProductBtn = document.getElementById('add-product');
            const supplierSelect = document.querySelector('select[name="supplier_id"]');
            
            // Toggle purchase items section based on current type
            function togglePurchaseItems() {
                if (typeSelect.value === 'purchase') {
                    purchaseItemsSection.classList.remove('hidden');
                } else {
                    purchaseItemsSection.classList.add('hidden');
                }
            }
            
            typeSelect.addEventListener('change', togglePurchaseItems);
            togglePurchaseItems(); // Initial check
            
            // Add product row
            addProductBtn.addEventListener('click', function() {
                const existingRows = productsContainer.querySelectorAll('.product-row');
                const index = existingRows.length;
                
                // Find the next available index
                let maxIndex = -1;
                existingRows.forEach(row => {
                    const input = row.querySelector('input[name^="products["]');
                    if (input) {
                        const match = input.name.match(/\[(\d+)\]/);
                        if (match) {
                            const idx = parseInt(match[1]);
                            if (idx > maxIndex) maxIndex = idx;
                        }
                    }
                });
                const newIndex = maxIndex + 1;
                
                const template = `
                    <div class="product-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
                        <div>
                            <label class="block text-sm font-medium">Product *</label>
                            <select name="products[${newIndex}][product_id]" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Quantity *</label>
                            <input type="number" name="products[${newIndex}][quantity]" min="1" required 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Unit Price *</label>
                            <input type="number" step="0.01" name="products[${newIndex}][unit_price]" min="0" required 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-product px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Remove
                            </button>
                        </div>
                    </div>
                `;
                
                productsContainer.insertAdjacentHTML('beforeend', template);
                
                // Add event listener to remove button
                const newRow = productsContainer.lastElementChild;
                newRow.querySelector('.remove-product').addEventListener('click', function() {
                    newRow.remove();
                    reindexProducts();
                });
            });
            
            // Remove product row and reindex
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product')) {
                    const row = e.target.closest('.product-row');
                    if (row) {
                        row.remove();
                        reindexProducts();
                    }
                }
            });
            
            // Reindex product rows to maintain proper array indexing
            function reindexProducts() {
                const rows = productsContainer.querySelectorAll('.product-row');
                rows.forEach((row, index) => {
                    // Update select name
                    const select = row.querySelector('select[name^="products["]');
                    if (select) {
                        select.name = select.name.replace(/\[\d+\]/, `[${index}]`);
                    }
                    
                    // Update quantity input name
                    const quantityInput = row.querySelector('input[name^="products["][name*="quantity"]');
                    if (quantityInput) {
                        quantityInput.name = quantityInput.name.replace(/\[\d+\]/, `[${index}]`);
                    }
                    
                    // Update unit price input name
                    const priceInput = row.querySelector('input[name^="products["][name*="unit_price"]');
                    if (priceInput) {
                        priceInput.name = priceInput.name.replace(/\[\d+\]/, `[${index}]`);
                    }
                });
            }
            
            // Auto-calculate total amount when amount or tax changes
            const amountInput = document.querySelector('input[name="amount"]');
            const taxInput = document.querySelector('input[name="tax_amount"]');
            
            function calculateTotal() {
                const amount = parseFloat(amountInput.value) || 0;
                const tax = parseFloat(taxInput.value) || 0;
                const total = amount + tax;
                
                // Optionally update a total display if you have one
                const totalDisplay = document.getElementById('total-display');
                if (totalDisplay) {
                    totalDisplay.textContent = 'Total: ₹' + total.toFixed(2);
                }
            }
            
            if (amountInput && taxInput) {
                amountInput.addEventListener('input', calculateTotal);
                taxInput.addEventListener('input', calculateTotal);
                calculateTotal(); // Initial calculation
            }
            
            // Auto-fill supplier if in URL
            @if(request()->has('supplier_id'))
                supplierSelect.value = {{ request('supplier_id') }};
            @endif
            
            // Handle product auto-calculation for purchase items
            function setupProductCalculations() {
                const rows = productsContainer.querySelectorAll('.product-row');
                rows.forEach(row => {
                    const quantityInput = row.querySelector('input[name*="quantity"]');
                    const priceInput = row.querySelector('input[name*="unit_price"]');
                    
                    if (quantityInput && priceInput) {
                        function calculateProductTotal() {
                            const quantity = parseFloat(quantityInput.value) || 0;
                            const price = parseFloat(priceInput.value) || 0;
                            const total = quantity * price;
                            
                            // Optionally update a total display per product
                            const totalDisplay = row.querySelector('.product-total');
                            if (!totalDisplay) {
                                const totalDiv = document.createElement('div');
                                totalDiv.className = 'product-total text-sm text-gray-600 mt-1';
                                row.querySelector('div:has(input[name*="unit_price"])').appendChild(totalDiv);
                            }
                            row.querySelector('.product-total').textContent = 'Total: ₹' + total.toFixed(2);
                        }
                        
                        quantityInput.addEventListener('input', calculateProductTotal);
                        priceInput.addEventListener('input', calculateProductTotal);
                        calculateProductTotal(); // Initial calculation
                    }
                });
            }
            
            // Setup calculations for existing product rows
            setupProductCalculations();
            
            // Re-run setup when new rows are added
            const originalAddProduct = addProductBtn.onclick;
            addProductBtn.onclick = function() {
                if (originalAddProduct) originalAddProduct.call(this);
                setTimeout(setupProductCalculations, 100);
            };
        });
    </script>
    @endpush
</x-app-layout>