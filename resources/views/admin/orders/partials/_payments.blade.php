<div x-data="orderPayments({{ $order->id }})" x-init="loadPayments()">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Payment Information</h3>
            <p class="text-sm text-gray-500 mt-1">
                <template x-if="isInStore">
                    Record multiple payments for in-store order
                </template>
                <template x-if="!isInStore">
                    Online order - Pay in full
                </template>
            </p>
        </div>
        
        <div class="p-4 space-y-6">
            <!-- Payment Summary -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-lg font-bold text-gray-900" x-text="formatCurrency(grandTotal)"></p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-blue-500">Paid</p>
                    <p class="text-lg font-bold text-blue-600" x-text="formatCurrency(totalPaid)"></p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-500">Due</p>
                    <p class="text-lg font-bold" 
                       :class="balanceDue <= 0 ? 'text-green-600' : 'text-red-600'"
                       x-text="formatCurrency(balanceDue)"></p>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">Payment Status</span>
                <span :class="paymentStatusClass" 
                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                      x-text="paymentStatusLabel"></span>
            </div>

            <!-- Payment Form for Orders with Balance Due -->
            <template x-if="balanceDue > 0 && paymentStatus !== 'paid'">
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="font-semibold text-gray-900 mb-3">
                        <template x-if="isInStore">Add New Payment</template>
                        <template x-if="!isInStore">Record Full Payment</template>
                    </h4>
                    
                    <div class="space-y-4">
                        <!-- Payment Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">{{ $currencySymbol }}</span>
                                </div>
                                <input type="number"
                                    x-model="newPayment.amount"
                                    :max="balanceDue"
                                    :min="!isInStore ? balanceDue : 0"
                                    step="0.001"
                                    :placeholder="isInStore ? 'Enter amount' : formatCurrency(balanceDue)"
                                    @keyup.enter="addPayment"
                                    @input="validatePaymentAmount"
                                    :class="{
                                        'border-red-300': amountError,
                                        'border-gray-300': !amountError
                                    }"
                                    class="pl-8 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <template x-if="amountError">
                                <p class="mt-1 text-xs text-red-600" x-text="amountError"></p>
                            </template>
                            <template x-if="!amountError">
                                <p class="mt-1 text-xs text-gray-500">
                                    <template x-if="isInStore">
                                        Maximum: <span x-text="formatCurrency(balanceDue)"></span>
                                    </template>
                                    <template x-if="!isInStore">
                                        Enter full amount: <span class="font-semibold" x-text="formatCurrency(balanceDue)"></span>
                                    </template>
                                </p>
                            </template>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select x-model="newPayment.method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="direct">Direct/Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea x-model="newPayment.notes"
                                      rows="2"
                                      placeholder="Add any notes about this payment"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                        </div>

                        <!-- Add Payment Button -->
                        <div class="flex gap-3">
                            <button @click="addPayment"
                                    :disabled="!canAddPayment"
                                    class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="getButtonText()"></span>
                            </button>
                            
                            <button @click="clearPaymentForm"
                                    type="button"
                                    class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Online Order Information -->
            <template x-if="!isInStore">
                <div class="border-t border-gray-200 pt-4">
                    <div :class="paymentStatus === 'paid' ? 'bg-green-50 border border-green-200' : 'bg-blue-50 border border-blue-200'"
                         class="rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <svg :class="paymentStatus === 'paid' ? 'text-green-600' : 'text-blue-600'" 
                                 class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium" 
                                   :class="paymentStatus === 'paid' ? 'text-green-800' : 'text-blue-800'">
                                    Online Order Payment
                                </p>
                                <div class="text-sm mt-1" 
                                     :class="paymentStatus === 'paid' ? 'text-green-700' : 'text-blue-700'">
                                    <p>
                                        <template x-if="paymentStatus === 'paid'">
                                            ✅ This online order has been fully paid.
                                        </template>
                                        <template x-if="paymentStatus !== 'paid'">
                                            Online orders must be paid in full. Record the full payment using cash, direct payment, 
                                            or bank transfer.
                                        </template>
                                    </p>
                                    <p class="mt-2" x-show="paymentStatus === 'paid' && payments.length > 0">
                                        Paid through <span x-text="payments.length"></span> payment(s).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Fully Paid Message for In-Store Orders -->
            <template x-if="isInStore && paymentStatus === 'paid'">
                <div class="border-t border-gray-200 pt-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-800">
                                    In-Store Order Fully Paid
                                </p>
                                <p class="text-sm text-green-700 mt-1">
                                    <span x-text="`This in-store order has been fully paid through ${payments.length} payment(s).`"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Payment History -->
            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-gray-900">Payment History</h4>
                    <button @click="loadPayments"
                            :disabled="loading"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                        <svg :class="{'animate-spin': loading}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
                
                <!-- Payments List -->
                <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                    <template x-if="payments.length > 0">
                        <template x-for="payment in payments" :key="payment.id">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900" x-text="formatCurrency(payment.amount)"></span>
                                            <span class="text-xs px-2 py-0.5 rounded-full capitalize"
                                                  :class="payment.method === 'direct' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                                                  x-text="payment.method === 'direct' ? 'Cash' : 'Bank Transfer'"></span>
                                        </div>
                                        <span class="text-xs text-gray-500" x-text="payment.created_at"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600">
                                            <template x-if="payment.notes">
                                                <span x-text="payment.notes"></span>
                                            </template>
                                            <template x-if="!payment.notes">
                                                <span class="text-gray-400">No notes</span>
                                            </template>
                                        </div>
                                        <span class="text-xs text-gray-500" x-text="'Added by: ' + (payment.created_by_name || 'System')"></span>
                                    </div>
                                </div>
                                
                                <!-- Remove Button (Only for in-store orders and not fully paid) -->
                                <template x-if="isInStore && paymentStatus !== 'paid'">
                                    <button @click="removePayment(payment.id)"
                                            class="ml-3 text-red-600 hover:text-red-800 p-1 transition-colors"
                                            title="Remove payment">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </template>
                                
                                <!-- For online orders, show different icon -->
                                <template x-if="!isInStore">
                                    <div class="ml-3 text-gray-400 p-1" title="Online order payment">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>
                    
                    <!-- Empty State -->
                    <template x-if="payments.length === 0 && !loading">
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <p class="mt-2 text-gray-600">No payments recorded yet</p>
                            <p class="text-sm text-gray-500 mt-1" x-show="isInStore && balanceDue > 0">
                                Add a payment using the form above
                            </p>
                            <p class="text-sm text-gray-500 mt-1" x-show="!isInStore && balanceDue > 0">
                                Record the full payment amount for this online order
                            </p>
                        </div>
                    </template>

                    <!-- Loading State -->
                    <template x-if="loading">
                        <div class="text-center py-8">
                            <div class="inline-flex items-center gap-2 text-gray-600">
                                <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Loading payments...</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
    function orderPayments(orderId) {
        return {
            orderId: orderId,
            loading: false,
            payments: [],
            totalPaid: 0,
            grandTotal: 0,
            balanceDue: 0,
            paymentStatus: '',
            amountError: '',
            isInStore: @json($order->source === 'in_store'),
            currencySymbol: '{{ $currencySymbol }}',
            
            newPayment: {
                amount: '',
                method: 'direct',
                notes: ''
            },

            init() {
                // Listen for payment updates from other components
                window.addEventListener('payment-added', () => this.loadPayments());
                window.addEventListener('payment-removed', () => this.loadPayments());
                
                // Load initial data
                this.loadPayments();
            },

            async loadPayments() {
                this.loading = true;
                try {
                    const response = await fetch(`/admin/orders/${this.orderId}/payments`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.payments = data.payments;
                        this.totalPaid = parseFloat(data.total_paid) || 0;
                        this.grandTotal = parseFloat(data.grand_total) || 0;
                        this.balanceDue = parseFloat(data.balance_due) || 0;
                        this.paymentStatus = data.payment_status;
                        
                        // Dispatch event to update parent component
                        this.$dispatch('payments-updated', {
                            totalPaid: this.totalPaid,
                            balanceDue: this.balanceDue,
                            paymentStatus: this.paymentStatus
                        });
                    }
                } catch (error) {
                    console.error('Failed to load payments:', error);
                    this.showNotification('Failed to load payments', 'error');
                } finally {
                    this.loading = false;
                }
            },

            validatePaymentAmount() {
                this.amountError = '';
                
                if (!this.newPayment.amount) {
                    return;
                }
                
                const amount = parseFloat(this.newPayment.amount);
                
                if (isNaN(amount) || amount <= 0) {
                    this.amountError = 'Please enter a valid amount greater than 0';
                    return;
                }
                
                // Online orders must be paid in full
                if (!this.isInStore) {
                    if (amount !== this.balanceDue) {
                        this.amountError = `Online orders must be paid in full. Enter exactly ${this.formatCurrency(this.balanceDue)}`;
                        return;
                    }
                }
                
                // In-store orders cannot exceed balance due
                if (this.isInStore && amount > this.balanceDue) {
                    this.amountError = `Amount cannot exceed balance due of ${this.formatCurrency(this.balanceDue)}`;
                    return;
                }
                
                // Check if adding this payment would make the total exceed grand total
                const newTotalPaid = this.totalPaid + amount;
                if (newTotalPaid > this.grandTotal) {
                    this.amountError = `Adding this payment would exceed the order total of ${this.formatCurrency(this.grandTotal)}`;
                    return;
                }
            },

            async addPayment() {
                this.validatePaymentAmount();
                
                if (this.amountError) {
                    this.showNotification(this.amountError, 'error');
                    return;
                }

                if (this.paymentStatus === 'paid') {
                    this.showNotification('Cannot add payment: Order is already fully paid', 'error');
                    return;
                }

                if (!this.newPayment.amount || parseFloat(this.newPayment.amount) <= 0) {
                    this.showNotification('Please enter a valid amount', 'error');
                    return;
                }

                // For online orders, ensure full amount is entered
                if (!this.isInStore && parseFloat(this.newPayment.amount) !== this.balanceDue) {
                    this.showNotification(`Online orders must be paid in full. Enter exactly ${this.formatCurrency(this.balanceDue)}`, 'error');
                    return;
                }

                this.loading = true;
                
                try {
                    const response = await fetch(`/admin/orders/${this.orderId}/payments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.newPayment)
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification('✅ Payment recorded successfully!', 'success');
                        this.clearPaymentForm();
                        await this.loadPayments();
                        
                        // Dispatch global event
                        window.dispatchEvent(new CustomEvent('payment-added', {
                            detail: { payment: data.payment }
                        }));
                    } else {
                        this.showNotification(data.message || 'Failed to add payment', 'error');
                    }
                } catch (error) {
                    console.error('Failed to add payment:', error);
                    this.showNotification('Failed to add payment', 'error');
                } finally {
                    this.loading = false;
                }
            },

            async removePayment(paymentId) {
                // Online orders cannot have payments removed
                if (!this.isInStore) {
                    this.showNotification('Online order payments cannot be removed', 'error');
                    return;
                }

                if (this.paymentStatus === 'paid') {
                    this.showNotification('Cannot remove payment: Order is fully paid', 'error');
                    return;
                }

                if (!confirm('Are you sure you want to remove this payment? This action cannot be undone.')) {
                    return;
                }

                this.loading = true;
                
                try {
                    const response = await fetch(`/admin/orders/${this.orderId}/payments/${paymentId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification('✅ Payment removed successfully!', 'success');
                        await this.loadPayments();
                        
                        // Dispatch global event
                        window.dispatchEvent(new CustomEvent('payment-removed', {
                            detail: { paymentId }
                        }));
                    } else {
                        this.showNotification(data.message || 'Failed to remove payment', 'error');
                    }
                } catch (error) {
                    console.error('Failed to remove payment:', error);
                    this.showNotification('Failed to remove payment', 'error');
                } finally {
                    this.loading = false;
                }
            },

            clearPaymentForm() {
                this.newPayment = {
                    amount: '',
                    method: 'direct',
                    notes: ''
                };
                this.amountError = '';
            },

            get paymentStatusLabel() {
                const labels = {
                    'pending': 'Pending',
                    'paid': 'Paid',
                    'partially_paid': 'Partially Paid',
                    'failed': 'Failed',
                    'refunded': 'Refunded',
                };
                
                return labels[this.paymentStatus] || this.paymentStatus;
            },

            get paymentStatusClass() {
                const classes = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'paid': 'bg-green-100 text-green-800',
                    'partially_paid': 'bg-blue-100 text-blue-800',
                    'failed': 'bg-red-100 text-red-800',
                    'refunded': 'bg-gray-100 text-gray-800',
                };
                
                return classes[this.paymentStatus] || 'bg-gray-100 text-gray-800';
            },

            get canAddPayment() {
                // Check if payment can be added
                if (this.paymentStatus === 'paid') {
                    return false;
                }
                
                if (!this.newPayment.amount || parseFloat(this.newPayment.amount) <= 0) {
                    return false;
                }
                
                const amount = parseFloat(this.newPayment.amount);
                
                // Online orders require exact balance due
                if (!this.isInStore && amount !== this.balanceDue) {
                    return false;
                }
                
                // In-store orders cannot exceed balance due
                if (this.isInStore && amount > this.balanceDue) {
                    return false;
                }
                
                // Check if adding this payment would make total exceed grand total
                const newTotalPaid = this.totalPaid + amount;
                if (newTotalPaid > this.grandTotal) {
                    return false;
                }
                
                return true;
            },

            getButtonText() {
                if (this.isInStore) {
                    return this.newPayment.amount ? `Add Payment of ${this.formatCurrency(this.newPayment.amount)}` : 'Add Payment';
                } else {
                    return 'Record Full Payment';
                }
            },

            formatCurrency(amount) {
                const decimals = this.currencySymbol === 'KD' ? 3 : 2;
                return `${this.currencySymbol}${parseFloat(amount).toFixed(decimals)}`;
            },

            showNotification(message, type = 'info') {
                // Remove any existing notifications
                const existingNotifications = document.querySelectorAll('.payment-notification');
                existingNotifications.forEach(note => note.remove());
                
                const notification = document.createElement('div');
                notification.className = `payment-notification fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                    type === 'success' ? 'bg-green-600 text-white' :
                    type === 'error' ? 'bg-red-600 text-white' :
                    'bg-blue-600 text-white'
                }`;
                notification.textContent = message;
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';

                document.body.appendChild(notification);

                // Animate in
                setTimeout(() => {
                    notification.style.opacity = '1';
                    notification.style.transform = 'translateY(0)';
                }, 10);

                // Remove after delay
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(() => notification.remove(), 300);
                }, 4000);
            }
        };
    }
    </script>
</div>