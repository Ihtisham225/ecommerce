<div class="bg-gray-50 p-4 rounded-lg mb-6" x-data="orderSummary()">
    <h3 class="text-lg font-semibold mb-2">Summary</h3>
    <div class="grid grid-cols-2 gap-2 text-sm">
        <div>Items: <span x-text="items.length"></span></div>
        <div>Subtotal: <span x-text="currencySymbol + subtotal.toFixed(2)"></span></div>
        <div>Discount: <span x-text="currencySymbol + discount.toFixed(2)"></span></div>
        <div>Tax: <span x-text="currencySymbol + tax.toFixed(2)"></span></div>
        <div>Shipping: <span x-text="currencySymbol + shipping.toFixed(2)"></span></div>
        <div class="font-bold">Grand Total: <span x-text="currencySymbol + grandTotal.toFixed(2)"></span></div>
    </div>
</div>
