<div class="bg-white p-6 rounded-lg shadow-sm border mb-6" x-data="orderSummary()">
    <h3 class="text-lg font-semibold mb-4">Order Totals</h3>
    <div class="space-y-2">
        <div class="flex justify-between">
            <span>Subtotal:</span>
            <span x-text="currencySymbol + subtotal.toFixed(2)"></span>
        </div>
        <div class="flex justify-between">
            <span>Discount:</span>
            <span x-text="currencySymbol + discount.toFixed(2)"></span>
        </div>
        <div class="flex justify-between">
            <span>Tax:</span>
            <span x-text="currencySymbol + tax.toFixed(2)"></span>
        </div>
        <div class="flex justify-between">
            <span>Shipping:</span>
            <span x-text="currencySymbol + shipping.toFixed(2)"></span>
        </div>
        <div class="flex justify-between font-bold text-lg mt-2">
            <span>Grand Total:</span>
            <span x-text="currencySymbol + grandTotal.toFixed(2)"></span>
        </div>
    </div>
</div>
