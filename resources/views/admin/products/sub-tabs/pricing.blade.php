{{-- resources/views/admin/products/sub-tabs/pricing.blade.php --}}
<div id="product-pricing-tab" class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4">Pricing Rules</h3>

    <form id="pricing-add-form" class="grid grid-cols-3 gap-3 mb-4">
        <input name="title" placeholder="Title" class="p-2 border rounded" required />
        <select name="type" class="p-2 border rounded" required>
            <option value="discount">Discount (fixed)</option>
            <option value="bundle">Bundle</option>
            <option value="tier">Tier</option>
        </select>
        <input name="value" placeholder="Value" class="p-2 border rounded" required />
        <input name="start_at" type="date" class="p-2 border rounded" />
        <input name="end_at" type="date" class="p-2 border rounded" />
        <div class="col-span-3 flex items-center gap-2">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" class="mr-2">
                <span>Active</span>
            </label>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Add Rule</button>
        </div>
    </form>

    <div id="pricing-list" class="space-y-2">
        <div class="text-gray-500">Loading pricing rules...</div>
    </div>
</div>

<script>
(function () {
    const productId = "{{ $product->id }}";
    const listUrl = "{{ route('admin.products.pricing-rules.index', $product) }}";
    const storeUrl = "{{ route('admin.products.pricing-rules.store', $product) }}";
    const updateBase = "{{ url('admin/products') }}"; // /admin/products/{product}/pricing-rules/{rule}
    const form = document.getElementById('pricing-add-form');
    const listWrap = document.getElementById('pricing-list');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        const payload = {};
        for (const [k,v] of fd.entries()) payload[k] = v;
        payload.is_active = fd.get('is_active') ? 1 : 0;

        const res = await fetch(storeUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        if (res.ok) {
            form.reset();
            await loadPricing();
        } else {
            alert('Error creating pricing rule');
        }
    });

    async function loadPricing() {
        listWrap.innerHTML = '<div class="text-gray-500">Loading pricing rules...</div>';
        const res = await fetch(listUrl);
        const data = await res.json();
        listWrap.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            listWrap.innerHTML = '<div class="text-gray-500">No pricing rules</div>';
            return;
        }
        data.forEach(r => {
            const row = document.createElement('div');
            row.className = 'p-3 border rounded flex items-center justify-between';
            const left = document.createElement('div');
            left.innerHTML = `<div class="font-medium">${r.title} (${r.type})</div>
                              <div class="text-sm text-gray-600">Value: ${r.value} • ${r.start_at ?? 'Any'} → ${r.end_at ?? 'Any'}</div>`;
            const btns = document.createElement('div');
            btns.className = 'flex gap-2';
            const del = document.createElement('button');
            del.className = 'px-2 py-1 text-sm bg-red-50 text-red-600 rounded';
            del.textContent = 'Delete';
            del.addEventListener('click', () => deleteRule(r.id));
            btns.appendChild(del);
            row.appendChild(left);
            row.appendChild(btns);
            listWrap.appendChild(row);
        });
    }

    async function deleteRule(id) {
        if (!confirm('Delete pricing rule?')) return;
        const res = await fetch(`${updateBase}/${productId}/pricing-rules/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (res.ok) loadPricing();
    }

    loadPricing();
})();
</script>
