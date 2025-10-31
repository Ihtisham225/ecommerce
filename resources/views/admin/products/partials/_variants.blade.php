<div>
    <div id="variants-list" class="space-y-3">
        @foreach($product->variants as $variant)
            <div class="variant-row border p-3 rounded" data-variant-id="{{ $variant->id }}">
                <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">
                <div class="grid grid-cols-4 gap-3">
                    <input class="p-2 border rounded" placeholder="SKU" name="variants[{{ $loop->index }}][sku]" value="{{ $variant->sku }}">
                    <input class="p-2 border rounded" placeholder="Price" name="variants[{{ $loop->index }}][price]" value="{{ $variant->price }}">
                    <input class="p-2 border rounded" placeholder="Stock" name="variants[{{ $loop->index }}][stock_quantity]" value="{{ $variant->stock_quantity }}">
                    <button type="button" class="remove-variant-btn text-red-600">Remove</button>
                </div>
            </div>
        @endforeach
    </div>

    <button id="add-variant-btn" type="button" class="mt-3 px-4 py-2 bg-gray-200 rounded">Add Variant</button>

    <template id="variant-template">
        <div class="variant-row border p-3 rounded">
            <div class="grid grid-cols-4 gap-3">
                <input class="p-2 border rounded variant-sku" placeholder="SKU" name="__SKU__">
                <input class="p-2 border rounded variant-price" placeholder="Price" name="__PRICE__">
                <input class="p-2 border rounded variant-stock" placeholder="Stock" name="__STOCK__">
                <button type="button" class="remove-variant-btn text-red-600">Remove</button>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('add-variant-btn');
    const list = document.getElementById('variants-list');
    addBtn.addEventListener('click', () => {
        const idx = list.children.length;
        const tpl = document.getElementById('variant-template').innerHTML;
        // replace placeholder names
        let html = tpl.replace(/__SKU__/g, `variants[${idx}][sku]`)
                      .replace(/__PRICE__/g, `variants[${idx}][price]`)
                      .replace(/__STOCK__/g, `variants[${idx}][stock_quantity]`);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        list.appendChild(wrapper.firstElementChild);

        // attach remove handler
        list.querySelectorAll('.remove-variant-btn').forEach(btn => {
            btn.onclick = (e) => {
                e.target.closest('.variant-row').remove();
                // re-index names
                Array.from(list.children).forEach((row, i) => {
                    row.querySelectorAll('input').forEach(input => {
                        if (input.name.includes('sku')) input.name = `variants[${i}][sku]`;
                        if (input.name.includes('price')) input.name = `variants[${i}][price]`;
                        if (input.name.includes('stock_quantity')) input.name = `variants[${i}][stock_quantity]`;
                        if (input.name.includes('[id]')) input.name = `variants[${i}][id]`;
                    });
                });
            };
        });
    });

    // initial remove handlers
    document.querySelectorAll('.remove-variant-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('.variant-row').remove();
        });
    });
});
</script>
