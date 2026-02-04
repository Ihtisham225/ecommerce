{{-- Order Items --}}
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200" 
     x-data="orderItemsManager({{ $order->id }})"
     @add-item-to-order.window="addItem($event.detail.item)">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Order Items</h3>
            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full" 
                  x-text="`${items.length} items`"></span>
        </div>

        <button
            @click="openModal()"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Item
        </button>
    </div>

    {{-- Items Table --}}
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Product</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">SKU</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300 w-24">quantity</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300 w-32">Price</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300 w-32">Total</th>
                    <th class="px-4 py-3 w-12"></th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                <template x-if="items.length === 0">
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center">
                            <div class="text-gray-400 dark:text-gray-500">
                                <p class="text-sm">No items added yet</p>
                                <button @click="openModal()" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium mt-2">
                                    Add your first item
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                <template x-for="(item, index) in items" :key="item.id || index">
                    <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">

                        {{-- Product Title --}}
                        <td class="px-4 py-3 align-top">
                            <div class="font-medium text-gray-900 dark:text-white" x-text="item.title"></div>
                            <template x-if="item.variant_name">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    <span x-text="item.variant_name"></span>
                                </div>
                            </template>
                        </td>

                        {{-- SKU --}}
                        <td class="px-4 py-3 align-top">
                            <span class="text-gray-600 dark:text-gray-300 font-mono text-sm" x-text="item.sku"></span>
                        </td>

                        {{-- quantity --}}
                        <td class="px-4 py-3">
                            <input
                                type="number"
                                step="1"
                                min="1"
                                class="w-20 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                x-model.number="item.quantity"
                                @input.debounce.500ms="updateItemTotals()"
                            >
                        </td>

                        {{-- Price --}}
                        <td class="px-4 py-3">
                            <div class="relative">
                                <input
                                    type="number"
                                    step="0.001"
                                    class="w-28 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    x-model.number="item.price"
                                    @input.debounce.500ms="updateItemTotals()"
                                >
                                <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs text-gray-400">
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                        </td>

                        {{-- Total --}}
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">
                                <span x-text="(parseFloat(item.quantity || 0) * parseFloat(item.price || 0)).toFixed(3)"></span>
                                <span class="text-gray-500 text-sm ml-1">{{ $currencySymbol }}</span>
                            </div>
                        </td>

                        {{-- Remove --}}
                        <td class="px-4 py-3 text-center">
                            <button
                                @click="removeItem(index)"
                                class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                                title="Remove item"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                </template>

            </tbody>
        </table>
    </div>

    {{-- Totals Summary --}}
    <div class="mt-6 flex justify-end">
        <div class="w-64 space-y-2">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                <span class="font-medium text-gray-900 dark:text-white" x-text="formatCurrency(calculateSubtotal())"></span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 dark:text-gray-400">Items</span>
                <span class="font-medium text-gray-900 dark:text-white" x-text="items.length"></span>
            </div>
        </div>
    </div>

    {{-- Add Item Modal --}}
    @include('admin.orders.partials._add_item_modal')
</div>

<script>
function orderItemsManager(orderId) {
    return {
        orderId,
        items: @js($order->items->map(fn($i) => [
            'id' => $i->id,
            'product_id' => $i->product_id,
            'title' => $i->product->title['en'] ?? 'Untitled',
            'sku' => $i->sku,
            'quantity' => $i->quantity,
            'price' => $i->price,
            'total' => $i->total,
            'variant_name' => $i->variant_name ?? null,
        ])),

        async addItem(item) {
            try {
                // ✅ Ensure we have the correct structure
                const payload = {
                    product_id: item.product_id,
                    product_variant_id: item.product_variant_id,
                    sku: item.sku,
                    title: item.title,
                    variant_name: item.variant_name,
                    price: parseFloat(item.price),
                    quantity: parseInt(item.quantity) || 1,
                };

                console.log('Adding item:', payload); // Debug log

                const res = await fetch(`/admin/orders/${this.orderId}/items`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                console.log('Response:', data); // Debug log

                if (data.success) {
                    // Add the new item to the local items array
                    this.items.push({
                        id: data.item.id,
                        product_id: data.item.product_id,
                        title: data.item.title,
                        sku: data.item.sku,
                        quantity: data.item.quantity,
                        price: data.item.price,
                        total: data.item.total,
                        variant_name: data.item.variant_name
                    });
                    
                    // Trigger autosave to update totals
                    this.triggerAutosave();
                } else {
                    console.error('Failed to add item:', data.message);
                    this.showNotification(data.message || 'Failed to add item', 'error');
                }

            } catch (e) {
                console.error("Add item error:", e);
                this.showNotification('Network error adding item', 'error');
            }
        },

        async removeItem(index) {
            const item = this.items[index];

            if (!item.id) {
                this.items.splice(index, 1);
                this.triggerAutosave();
                return;
            }

            try {
                await fetch(`/admin/orders/${this.orderId}/items/${item.id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                this.items.splice(index, 1);
                this.triggerAutosave();
                
            } catch (e) {
                console.error("Remove item error:", e);
                this.showNotification('Failed to remove item', 'error');
            }
        },

        async updateItem(item) {
            try {
                await fetch(`/admin/orders/${this.orderId}/items/${item.id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity: item.quantity,
                        price: item.price,
                    })
                });
                
                this.triggerAutosave();
                
            } catch (e) {
                console.error("Update item error:", e);
                this.showNotification('Failed to update item', 'error');
            }
        },

        updateItemTotals() {
            this.items.forEach(item => {
                item.total = (parseFloat(item.quantity || 0) * parseFloat(item.price || 0)).toFixed(3);

                if (item.id) {
                    this.updateItem(item);
                }
            });
            
            this.triggerAutosave();
        },

        calculateSubtotal() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.total || 0), 0);
        },

        formatCurrency(amount) {
            const decimals = '{{ $currencySymbol }}' === 'KD' ? 3 : 2;
            return '{{ $currencySymbol }}' + parseFloat(amount || 0).toFixed(decimals);
        },

        triggerAutosave() {
            // Dispatch event to trigger autosave in parent component
            window.dispatchEvent(new CustomEvent('autosave-trigger'));
        },

        openModal() {
            window.dispatchEvent(new CustomEvent('open-add-item'));
        },

        showNotification(message, type = 'info') {
            // Your notification code...
        }
    }
}
</script>
