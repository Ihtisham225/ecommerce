<x-app-layout>
    <div x-data="orderForm()" 
         @customer-selected.window="onCustomerSelected($event.detail)"
         @customer-removed.window="onCustomerRemoved()"
         @autosave-trigger.window="triggerAutosave()">
        
        {{-- ✅ Sticky Header --}}
        @include('admin.orders.partials._header', ['order' => $order])

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-6">

                {{-- LEFT SIDE --}}
                <div class="flex-1 space-y-6">
                    {{-- Source --}}
                    @include('admin.orders.partials._source', ['order' => $order])

                    {{-- Order Status Toggles --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200" x-show="!isInStore()">
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
                                                    ? status.classes 
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

                    {{-- Customer Info --}}
                    @include('admin.orders.partials._customer', ['order' => $order, 'customers' => $customers])

                    {{-- Order Items --}}
                    @include('admin.orders.partials._items', ['order' => $order])
                </div>

                {{-- RIGHT SIDE --}}
                <div class="lg:w-80 space-y-6">
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

                    {{-- Shipping Address (Only for Online Orders) --}}
                    <div x-show="!isInStore()" x-transition>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h4 class="font-semibold text-base text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Shipping Address
                                <span x-show="form.customer_id" class="text-xs font-normal text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    Also saving to customer profile
                                </span>
                                <span x-show="!form.customer_id" class="text-xs font-normal text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                    Guest order only
                                </span>
                            </h4>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                                    <input type="text"
                                        placeholder="Street address, P.O. box, company name"
                                        x-model="form.shipping_address.address_line_1"
                                        @input.debounce.600ms="if(form.sameAsShipping) applySameAsShipping(); triggerAutosave()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                                    <input type="text"
                                        placeholder="Apartment, suite, unit, building, floor, etc."
                                        x-model="form.shipping_address.address_line_2"
                                        @input.debounce.600ms="if(form.sameAsShipping) applySameAsShipping(); triggerAutosave()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        {{-- Billing Address (Only for Online Orders) --}}
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mt-6">
                            <h4 class="font-semibold text-base text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Billing Address
                                <span x-show="form.customer_id" class="text-xs font-normal text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    Also saving to customer profile
                                </span>
                                <span x-show="!form.customer_id" class="text-xs font-normal text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                    Guest order only
                                </span>
                            </h4>

                            <div class="space-y-3">
                                <label class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200 cursor-pointer hover:bg-blue-100 transition-colors">
                                    <input type="checkbox"
                                        x-model="form.sameAsShipping"
                                        @change="applySameAsShipping(); triggerAutosave()"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-blue-900">Use shipping address as billing address</span>
                                </label>

                                <div x-show="!form.sameAsShipping" x-transition>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                                        <input type="text"
                                            placeholder="Street address, P.O. box, company name"
                                            x-model="form.billing_address.address_line_1"
                                            @input.debounce.600ms="triggerAutosave()"
                                            :disabled="form.sameAsShipping"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    </div>

                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                                        <input type="text"
                                            placeholder="Apartment, suite, unit, building, floor, etc."
                                            x-model="form.billing_address.address_line_2"
                                            @input.debounce.600ms="triggerAutosave()"
                                            :disabled="form.sameAsShipping"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                    </div>
                                </div>

                                <div x-show="form.sameAsShipping" x-transition class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-800 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Billing address will be the same as shipping address
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Component --}}
                    <div x-transition>
                        @include('admin.orders.partials._payments', ['order' => $order])
                    </div>
                </div>
            </div>
        </div>

        {{-- Alpine.js Logic --}}
        <script>
        function orderForm() {
            return {
                /* --------------------------------
                Basic Order Info
                -------------------------------- */
                orderId: {{ $order->id }},
                orderDate: @js($order->created_at->format('Y-m-d H:i:s')),
                customerName: @js($order->customer?->full_name ?? 'Guest'),
                totalItems: {{ $order->items->sum('quantity') }},

                /* --------------------------------
                Main Form Data
                -------------------------------- */
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
                            'quantity' => $i->quantity,
                            'price' => $i->price,
                            'total' => $i->total,
                        ])->values()
                    ),

                    payments: @js(
                        $order->payments->map(fn($p) => [
                            'id' => $p->id,
                            'method' => $p->method,
                            'amount' => $p->amount,
                            'status' => $p->status,
                            'created_at' => $p->created_at->format('Y-m-d H:i'),
                        ])->values()
                    ),

                    shipping_address: {
                        address_line_1: @js(optional($defaultShipping)?->address_line_1),
                        address_line_2: @js(optional($defaultShipping)?->address_line_2),
                    },

                    billing_address: {
                        address_line_1: @js(optional($defaultBilling)?->address_line_1),
                        address_line_2: @js(optional($defaultBilling)?->address_line_2),
                    },

                    sameAsShipping: @js(optional($defaultBilling)?->same_as_shipping ?? false),

                    notes: @js($order->notes),
                    admin_notes: @js($order->admin_notes),

                    subtotal: @js($order->subtotal ?? 0),
                    discount_total: @js($order->discount_total ?? 0),
                    tax_total: @js($order->tax_total ?? 0),
                    shipping_total: @js($order->shipping_total ?? 0),
                    grand_total: @js($order->grand_total ?? 0),
                    paid_amount: @js($order->paid_amount ?? 0),
                },

                /* --------------------------------
                Customer Event Handlers
                -------------------------------- */
                onCustomerSelected(detail) {
                    this.form.customer_id = detail.customer_id;
                    this.customerName = detail.customer.first_name + ' ' + detail.customer.last_name;
                    
                    if (detail.shipping_address) {
                        this.form.shipping_address.address_line_1 = detail.shipping_address.address_line_1 || '';
                        this.form.shipping_address.address_line_2 = detail.shipping_address.address_line_2 || '';
                    }
                    
                    if (detail.billing_address) {
                        this.form.billing_address.address_line_1 = detail.billing_address.address_line_1 || '';
                        this.form.billing_address.address_line_2 = detail.billing_address.address_line_2 || '';
                        this.form.sameAsShipping = detail.billing_address.same_as_shipping || false;
                    } else {
                        this.form.sameAsShipping = true;
                        this.applySameAsShipping();
                    }
                    
                    this.showNotification('✅ Customer address loaded!', 'success');
                    this.triggerAutosave();
                },

                onCustomerRemoved() {
                    this.form.customer_id = null;
                    this.customerName = 'Guest';
                    
                    this.form.shipping_address = { address_line_1: '', address_line_2: '' };
                    this.form.billing_address = { address_line_1: '', address_line_2: '' };
                    this.form.sameAsShipping = false;
                    
                    this.$nextTick(() => {
                        const allInputs = this.$el.querySelectorAll('input');
                        allInputs.forEach((input, index) => {
                            const xModel = input.getAttribute('x-model');
                            if (xModel && xModel.includes('address')) {
                                input.value = '';
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        });
                    });
                    
                    this.showNotification('✅ Customer removed from order!', 'success');
                    this.triggerAutosave();
                },

                /* --------------------------------
                Helper - Check if In-Store
                -------------------------------- */
                isInStore() {
                    return this.form.source === 'in_store';
                },

                /* --------------------------------
                Status Options (for online orders)
                -------------------------------- */
                orderStatuses: [
                    { value: 'pending', label: 'Pending', classes: 'border-yellow-500 bg-yellow-50 text-yellow-700'  },
                    { value: 'processing', label: 'Processing', classes: 'border-blue-500 bg-blue-50 text-blue-700' },
                    { value: 'completed', label: 'Completed', classes: 'border-green-500 bg-green-50 text-green-700' },
                    { value: 'cancelled', label: 'Cancelled', classes: 'border-red-500 bg-red-50 text-red-700' }
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

                /* --------------------------------
                Address Helper
                -------------------------------- */
                applySameAsShipping() {
                    if (this.form.sameAsShipping) {
                        this.form.billing_address.address_line_1 = this.form.shipping_address.address_line_1;
                        this.form.billing_address.address_line_2 = this.form.shipping_address.address_line_2;
                    }
                },

                /* --------------------------------
                Payment Helpers (for in-store orders)
                -------------------------------- */
                calculatePaymentStatus() {
                    const paid = parseFloat(this.form.paid_amount) || 0;
                    const total = parseFloat(this.form.grand_total) || 0;
                    
                    if (paid <= 0) {
                        this.form.payment_status = 'pending';
                        return 'Pending';
                    } else if (paid >= total) {
                        this.form.payment_status = 'paid';
                        return 'Paid';
                    } else {
                        this.form.payment_status = 'partially_paid';
                        return 'Partially Paid';
                    }
                },

                get paymentStatusClass() {
                    const status = this.form.payment_status;
                    
                    const classes = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'paid': 'bg-green-100 text-green-800',
                        'partially_paid': 'bg-blue-100 text-blue-800',
                        'failed': 'bg-red-100 text-red-800',
                        'refunded': 'bg-gray-100 text-gray-800',
                    };
                    
                    return classes[status] || 'bg-gray-100 text-gray-800';
                },

                /* --------------------------------
                Online Order Validation
                -------------------------------- */
                validateOnlineOrder() {
                    if (!this.isInStore()) {
                        // For online orders, ensure they are marked as paid
                        if (this.form.payment_status !== 'paid') {
                            this.form.payment_status = 'paid';
                            this.showNotification('Online orders must be marked as paid', 'info');
                        }
                        
                        // Ensure paid amount equals grand total for online orders
                        if (parseFloat(this.form.paid_amount) !== parseFloat(this.form.grand_total)) {
                            this.form.paid_amount = this.form.grand_total;
                            this.showNotification('Online orders must be fully paid', 'info');
                        }
                    }
                },

                /* --------------------------------
                Autosave System
                -------------------------------- */
                autosaveTimer: null,
                saving: false,
                currencySymbol: '{{ $currencySymbol }}',

                triggerAutosave() {
                    // Validate online orders before saving
                    if (!this.isInStore()) {
                        this.validateOnlineOrder();
                    }
                    
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

                /* --------------------------------
                Helpers
                -------------------------------- */
                formatCurrency(amount) {
                    const decimals = this.currencySymbol === 'KD' ? 3 : 2;
                    return `${this.currencySymbol}${parseFloat(amount).toFixed(decimals)}`;
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString() + ' ' +
                        date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                },

                showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className =
                        `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                            type === 'success'
                                ? 'bg-green-600 text-white'
                                : type === 'error'
                                    ? 'bg-red-600 text-white'
                                    : 'bg-blue-600 text-white'
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
    </div>
</x-app-layout>