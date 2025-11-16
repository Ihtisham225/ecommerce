<x-app-layout>
    <div x-data="orderForm()" @autosave-trigger.window="triggerAutosave()">
        
        {{-- ✅ Sticky Header --}}
        @include('admin.orders.partials._header', ['order' => $order])

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">

                {{-- LEFT SIDE --}}
                <div class="flex-1 space-y-6">
                    {{-- Order Status Toggles --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Order Status</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Order Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Order Status</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <template x-for="status in orderStatuses" :key="status.value">
                                        <label class="relative flex cursor-pointer">
                                            <input type="radio" name="status" :value="status.value" x-model="form.status" 
                                                   class="sr-only" @change.debounce.1000ms="triggerAutosave()">
                                            <div class="flex items-center justify-center w-full px-4 py-3 border rounded-lg text-sm font-medium transition-all"
                                                 :class="form.status === status.value 
                                                    ? 'border-indigo-500 bg-indigo-50 text-indigo-700 shadow-sm' 
                                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'">
                                                <span x-text="status.label"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            {{-- Payment Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Payment Status</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <template x-for="status in paymentStatuses" :key="status.value">
                                        <label class="relative flex cursor-pointer">
                                            <input type="radio" name="payment_status" :value="status.value" x-model="form.payment_status" 
                                                   class="sr-only" @change.debounce.1000ms="triggerAutosave()">
                                            <div class="flex items-center justify-center w-full px-4 py-3 border rounded-lg text-sm font-medium transition-all"
                                                 :class="form.payment_status === status.value 
                                                    ? status.classes 
                                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'">
                                                <span x-text="status.label"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            {{-- Shipping Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Shipping Status</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <template x-for="status in shippingStatuses" :key="status.value">
                                        <label class="relative flex cursor-pointer">
                                            <input type="radio" name="shipping_status" :value="status.value" x-model="form.shipping_status" 
                                                   class="sr-only" @change.debounce.1000ms="triggerAutosave()">
                                            <div class="flex items-center justify-center w-full px-4 py-3 border rounded-lg text-sm font-medium transition-all"
                                                 :class="form.shipping_status === status.value 
                                                    ? status.classes 
                                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'">
                                                <span x-text="status.label"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Source --}}
                    @include('admin.orders.partials._source', ['order' => $order])

                    {{-- Customer Info --}}
                    @include('admin.orders.partials._customer', ['order' => $order, 'customers' => $customers])

                    {{-- Order Items --}}
                    @include('admin.orders.partials._items', ['order' => $order])

                    {{-- Payment --}}
                    @include('admin.orders.partials._payment', ['order' => $order])

                    {{-- Notes --}}
                    @include('admin.orders.partials._notes', ['order' => $order])

                    {{-- Totals --}}
                    @include('admin.orders.partials._totals', ['order' => $order])

                    {{-- Order History --}}
                    @include('admin.orders.partials._history', ['order' => $order])

                    {{-- Summary --}}
                    @include('admin.orders.partials._summary', ['order' => $order])
                </div>

                {{-- RIGHT SIDE --}}
                <div class="lg:w-80 space-y-6">
                    {{-- Order Actions --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mark as Fulfilled
                            </button>
                            
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Add Note
                            </button>
                            
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Capture Payment
                            </button>
                            
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Cancel Order
                            </button>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Order Summary</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Order ID</span>
                                <span class="text-sm font-medium" x-text="'#' + orderId"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Date Created</span>
                                <span class="text-sm font-medium" x-text="formatDate(orderDate)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Customer</span>
                                <span class="text-sm font-medium text-right" x-text="customerName"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Items</span>
                                <span class="text-sm font-medium" x-text="totalItems"></span>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-gray-900" x-text="formatCurrency(form.grand_total)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping & Billing --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Addresses</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            {{-- Shipping Address --}}
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Shipping Address</h4>
                                <div class="text-sm text-gray-600 space-y-1" x-html="shippingAddressDisplay"></div>
                                <button type="button" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                    Edit Shipping
                                </button>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Billing Address</h4>
                                <div class="text-sm text-gray-600 space-y-1" x-html="billingAddressDisplay"></div>
                                <button type="button" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                    Edit Billing
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                        </div>
                        <div class="p-4">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <template x-for="(event, index) in recentActivity" :key="index">
                                        <li>
                                            <div class="relative pb-8">
                                                <template x-if="index !== recentActivity.length - 1">
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                </template>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white"
                                                              :class="event.iconBackground">
                                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path :d="event.icon" fill-rule="evenodd" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500" x-html="event.content"></p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500" x-text="event.time"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js Logic --}}
    <script>
        function orderForm() {
            return {
                orderId: {{ $order->id }},
                orderDate: @js($order->created_at->format('Y-m-d H:i:s')),
                customerName: @js($order->customer?->name ?? 'Guest'),
                totalItems: {{ $order->items->sum('qty') }},
                
                form: {
                    customer_id: {{ $order->customer_id ?? 'null' }},
                    source: @js($order->source),
                    status: @js($order->status),
                    payment_status: @js($order->payment_status),
                    shipping_status: @js($order->shipping_status),
                    items: @js(
                        $order->items->map(fn($i) => [
                            'id' => $i->id,
                            'product_id' => $i->product_id,
                            'title' => $i->title['en'] ?? $i->title,
                            'sku' => $i->sku,
                            'qty' => $i->qty,
                            'price' => $i->price,
                            'total' => $i->total,
                        ])->values()
                    ),
                    billing_address_raw: @js(optional($order->billingAddress)?->address_line_1),
                    shipping_address_raw: @js(optional($order->shippingAddress)?->address_line_1),
                    payment: {
                        method: @js(optional($order->payments->first())->method),
                        amount: @js(optional($order->payments->first())->amount),
                        transaction_id: @js(optional($order->payments->first())->transaction_id),
                    },
                    notes: @js($order->notes),
                    admin_notes: @js($order->admin_notes),
                    subtotal: @js($order->subtotal ?? 0),
                    discount_total: @js($order->discount_total ?? 0),
                    tax_total: @js($order->tax_total ?? 0),
                    shipping_total: @js($order->shipping_total ?? 0),
                    grand_total: @js($order->grand_total ?? 0),
                    history: @js($order->history->map(fn($h) => [
                        'id' => $h->id,
                        'old_status' => $h->old_status,
                        'new_status' => $h->new_status,
                        'changed_by_name' => $h->changedBy?->name ?? 'System',
                        'created_at' => $h->created_at->format('Y-m-d H:i'),
                    ])->values()),
                },

                // Status options with styling
                orderStatuses: [
                    { value: 'pending', label: 'Pending' },
                    { value: 'processing', label: 'Processing' },
                    { value: 'completed', label: 'Completed' },
                    { value: 'cancelled', label: 'Cancelled' }
                ],
                
                paymentStatuses: [
                    { value: 'pending', label: 'Pending', classes: 'border-yellow-500 bg-yellow-50 text-yellow-700' },
                    { value: 'paid', label: 'Paid', classes: 'border-green-500 bg-green-50 text-green-700' },
                    { value: 'failed', label: 'Failed', classes: 'border-red-500 bg-red-50 text-red-700' },
                    { value: 'refunded', label: 'Refunded', classes: 'border-gray-500 bg-gray-50 text-gray-700' }
                ],
                
                shippingStatuses: [
                    { value: 'pending', label: 'Pending', classes: 'border-yellow-500 bg-yellow-50 text-yellow-700' },
                    { value: 'shipped', label: 'Shipped', classes: 'border-blue-500 bg-blue-50 text-blue-700' },
                    { value: 'delivered', label: 'Delivered', classes: 'border-green-500 bg-green-50 text-green-700' },
                    { value: 'cancelled', label: 'Cancelled', classes: 'border-red-500 bg-red-50 text-red-700' }
                ],

                // Recent activity for timeline
                recentActivity: [
                    {
                        id: 1,
                        content: 'Order status changed to <span class="font-medium text-gray-900">Processing</span>',
                        icon: 'M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092...',
                        iconBackground: 'bg-green-500',
                        time: '10m ago'
                    },
                    {
                        id: 2,
                        content: 'Payment received via <span class="font-medium text-gray-900">Credit Card</span>',
                        icon: 'M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z...',
                        iconBackground: 'bg-blue-500',
                        time: '1h ago'
                    },
                    {
                        id: 3,
                        content: @js("Order <span class='font-medium text-gray-900'>#{$order->id}</span> was created"),
                        icon: 'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12...',
                        iconBackground: 'bg-gray-400',
                        time: '{{ $order->created_at->diffForHumans() }}'
                    },
                ],

                autosaveTimer: null,
                saving: false,
                currencySymbol: '{{ $currencySymbol }}',

                // Computed properties
                get shippingAddressDisplay() {
                    if (!this.form.shipping_address_raw) return '<p class="text-gray-400">No shipping address</p>';
                    
                    const address = JSON.parse(this.form.shipping_address_raw);
                    return `
                        <p class="font-medium">${address.first_name} ${address.last_name}</p>
                        <p>${address.address_line_1}</p>
                        ${address.address_line_2 ? `<p>${address.address_line_2}</p>` : ''}
                        <p>${address.city}, ${address.state} ${address.zip_code}</p>
                        <p>${address.country}</p>
                        ${address.phone ? `<p class="mt-1">📞 ${address.phone}</p>` : ''}
                    `;
                },

                get billingAddressDisplay() {
                    if (!this.form.billing_address_raw) return '<p class="text-gray-400">No billing address</p>';
                    
                    const address = JSON.parse(this.form.billing_address_raw);
                    return `
                        <p class="font-medium">${address.first_name} ${address.last_name}</p>
                        <p>${address.address_line_1}</p>
                        ${address.address_line_2 ? `<p>${address.address_line_2}</p>` : ''}
                        <p>${address.city}, ${address.state} ${address.zip_code}</p>
                        <p>${address.country}</p>
                        ${address.phone ? `<p class="mt-1">📞 ${address.phone}</p>` : ''}
                    `;
                },

                triggerAutosave() {
                    clearTimeout(this.autosaveTimer);
                    this.autosaveTimer = setTimeout(() => this.autosave(), 1500);
                },

                async autosave() {
                    this.saving = true;
                    try {
                        const payload = JSON.stringify(this.form);
                        const response = await fetch(`/admin/orders/${this.orderId}`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: payload,
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.showNotification('✅ Order autosaved!', 'success');
                        } else {
                            this.showNotification(data.message || 'Autosave failed', 'error');
                        }
                    } catch (e) {
                        this.showNotification('Autosave error: ' + e.message, 'error');
                    } finally {
                        this.saving = false;
                    }
                },

                formatCurrency(amount) {
                    const decimals = this.currencySymbol === 'KD' ? 3 : 2;
                    return `${this.currencySymbol}${parseFloat(amount).toFixed(decimals)}`;
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
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
            }
        }
    </script>
</x-app-layout>