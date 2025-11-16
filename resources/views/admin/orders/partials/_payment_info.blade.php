<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Payment Information') }}</h3>
    </div>

    <div class="space-y-4">
        {{-- Payment Method --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
            <select x-model="paymentMethod" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                <option value="">Select Payment Method</option>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cash">Cash</option>
                <option value="cod">Cash on Delivery</option>
                <option value="other">Other</option>
            </select>
        </div>

        {{-- Payment Amount --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Amount</label>
            <input type="number" 
                   x-model.number="paymentAmount" 
                   step="0.01" 
                   min="0"
                   :max="grandTotal"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
        </div>

        {{-- Transaction ID --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
            <input type="text" 
                   x-model="transactionId" 
                   placeholder="e.g., tr_1A2b3C4d5E6f7G8h"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
        </div>

        {{-- Payment Status --}}
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium"
                      :class="{
                          'bg-yellow-100 text-yellow-800': paymentStatus === 'pending',
                          'bg-green-100 text-green-800': paymentStatus === 'paid',
                          'bg-red-100 text-red-800': paymentStatus === 'failed',
                          'bg-purple-100 text-purple-800': paymentStatus === 'refunded',
                          'bg-blue-100 text-blue-800': paymentStatus === 'partially_refunded'
                      }"
                      x-text="paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1).replace('_', ' ')">
                </span>
            </div>
        </div>
    </div>
</div>