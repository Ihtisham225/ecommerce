{{-- resources/views/admin/products/sub-tabs/relations.blade.php --}}
<div id="product-relations-tab" class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4">Product Relations (Upsell / Cross-sell / Related)</h3>

    <form id="relation-add-form" class="grid grid-cols-3 gap-3 mb-4">
        <select id="related-product-select" name="related_product_id" class="p-2 border rounded" required>
            <option value="">Select product to relate</option>
            @foreach(\App\Models\Product::where('id', '!=', $product->id)->limit(50)->get() as $p)
                <option value="{{ $p->id }}">{{ $p->title['en'] ?? $p->sku ?? 'Product #' . $p->id }}</option>
            @endforeach
        </select>

        <select name="relation_type" class="p-2 border rounded">
            <option value="related">Related</option>
            <option value="upsell">Upsell</option>
            <option value="crosssell">Cross-sell</option>
        </select>

        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Add Relation</button>
            <span class="text-sm text-gray-500">Show related products on product page</span>
        </div>
    </form>

    <div id="relations-list" class="space-y-2">
        <div class="text-gray-500">Loading relations...</div>
    </div>
</div>

<script>
(function () {
    const productId = "{{ $product->id }}";
    const listUrl = "{{ route('admin.products.relations.index', $product) }}";
    const storeUrl = "{{ route('admin.products.relations.store', $product) }}";
    const deleteBase = "{{ url('admin/products') }}"; // /admin/products/{product}/relations/{relation}
    const form = document.getElementById('relation-add-form');
    const listWrap = document.getElementById('relations-list');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        const payload = {};
        for (const [k,v] of fd.entries()) payload[k] = v;
        const res = await fetch(storeUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        if (res.ok) {
            form.reset();
            await loadRelations();
        } else {
            alert('Error adding relation');
        }
    });

    async function loadRelations() {
        listWrap.innerHTML = '<div class="text-gray-500">Loading relations...</div>';
        const res = await fetch(listUrl);
        const data = await res.json();
        listWrap.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            listWrap.innerHTML = '<div class="text-gray-500">No relations</div>';
            return;
        }
        data.forEach(r => {
            const row = document.createElement('div');
            row.className = 'p-3 border rounded flex items-center justify-between';
            const left = document.createElement('div');
            left.innerHTML = `<div class="font-medium">${r.related?.title?.en ?? r.related?.sku ?? 'Product #' + r.related_product_id}</div>
                              <div class="text-sm text-gray-600">${r.type || r.relation_type}</div>`;
            const btns = document.createElement('div');
            btns.className = 'flex gap-2';
            const del = document.createElement('button');
            del.className = 'px-2 py-1 text-sm bg-red-50 text-red-600 rounded';
            del.textContent = 'Delete';
            del.addEventListener('click', () => deleteRelation(r.id));
            btns.appendChild(del);
            row.appendChild(left);
            row.appendChild(btns);
            listWrap.appendChild(row);
        });
    }

    async function deleteRelation(id) {
        if (!confirm('Delete relation?')) return;
        const res = await fetch(`${deleteBase}/${productId}/relations/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (res.ok) loadRelations();
    }

    loadRelations();
})();
</script>
