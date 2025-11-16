<div class="w-full lg:w-80 space-y-6">
    {{-- Order Summary --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Order Summary') }}</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal:</span>
                <span x-text="formattedSubtotal" class="font-medium"></span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Discount:</span>
                <span x-text="formattedDiscount" class="font-medium text-red-600">-</span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax:</span>
                <span x-text="formattedTax" class="font-medium"></span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Shipping:</span>
                <span x-text="formattedShipping" class="font-medium"></span>
            </div>
            
            <div class="border-t border-gray-200 pt-3 mt-3">
                <div class="flex justify-between text-base font-bold">
                    <span>Total:</span>
                    <span x-text="formattedGrandTotal"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Order Status') }}</h3>
        
        <div class="space-y-4">
            {{-- Order Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                <select x-model="status" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>

            {{-- Payment Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select x-model="paymentStatus" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                    <option value="partially_refunded">Partially Refunded</option>
                </select>
            </div>

            {{-- Shipping Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Status</label>
                <select x-model="shippingStatus" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    <option value="pending">Pending</option>
                    <option value="ready_for_shipment">Ready for Shipment</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>

            {{-- Order Source --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Source</label>
                <select x-model="source" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border">
                    <option value="online">Online</option>
                    <option value="in_store">In Store</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Notes') }}</h3>
        
        <div class="space-y-4">
            {{-- Customer Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Notes</label>
                <textarea x-model="notes" 
                          rows="3"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                          placeholder="Any notes from the customer..."></textarea>
            </div>

            {{-- Admin Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                <textarea x-model="adminNotes" 
                          rows="3"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 border"
                          placeholder="Internal notes for this order..."></textarea>
            </div>
        </div>
    </div>
</div>