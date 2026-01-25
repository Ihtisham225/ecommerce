<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Expense') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="bg-indigo-600 px-6 py-4 rounded-t-lg">
                    <h1 class="text-2xl font-bold text-white">{{ __('Add New Expense') }}</h1>
                </div>
                <div class="p-6 border border-gray-200 border-t-0 rounded-b-lg">
                    <form action="{{ route('admin.expenses.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @include('admin.expenses.partials.form', ['expense' => null])

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                ← {{ __('Back to Expenses') }}
                            </a>

                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700">
                                {{ __('Create Expense') }}
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
            
            // Toggle purchase items section
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
                const index = productsContainer.querySelectorAll('.product-row').length;
                const template = `
                    <div class="product-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
                        <div>
                            <label class="block text-sm font-medium">Product *</label>
                            <select name="products[${index}][product_id]" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Quantity *</label>
                            <input type="number" name="products[${index}][quantity]" min="1" required 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Unit Price *</label>
                            <input type="number" step="0.01" name="products[${index}][unit_price]" min="0" required 
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
                });
            });
            
            // Remove product row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product')) {
                    e.target.closest('.product-row').remove();
                }
            });
            
            // Auto-fill supplier if in URL
            @if(request()->has('supplier_id'))
                supplierSelect.value = {{ request('supplier_id') }};
            @endif
        });
    </script>
    @endpush
</x-app-layout>