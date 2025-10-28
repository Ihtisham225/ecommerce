{{-- resources/views/admin/products/sub-tabs/meta.blade.php --}}
<div id="product-meta-tab" class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4">Meta Fields</h3>

    <form id="meta-add-form" class="grid grid-cols-3 gap-3 mb-4">
        <input name="namespace" placeholder="namespace (optional)" class="p-2 border rounded" />
        <input name="key" placeholder="key" class="p-2 border rounded" required />
        <input name="value" placeholder="value" class="p-2 border rounded" />
        <select name="type" class="p-2 border rounded">
            <option value="">type (auto)</option>
            <option value="string">string</option>
            <option value="number">number</option>
            <option value="json">json</option>
            <option value="boolean">boolean</option>
        </select>
        <div class="col-span-2 flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Add Meta</button>
            <span class="text-sm text-gray-500">Meta fields are useful for custom storage & integrations</span>
        </div>
    </form>

    <div id="meta-list" class="space-y-2">
        <div class="text-gray-500">Loading meta fields...</div>
    </div>
</div>

<script>
(function () {
    const productId = "{{ $product->id }}";
    const listUrl = "{{ route('admin.products.meta.index', $product) }}";
    const storeUrl = "{{ route('admin.products.meta.store', $product) }}";
    const updateBase = "{{ url('admin/products') }}"; // /admin/products/{product}/meta/{meta}
    const form = document.getElementById('meta-add-form');
    const listWrap = document.getElementById('meta-list');

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
            await loadMeta();
        } else {
            alert('Error adding meta');
        }
    });

    async function loadMeta() {
        listWrap.innerHTML = '<div class="text-gray-500">Loading meta fields...</div>';
        const res = await fetch(listUrl);
        const data = await res.json();
        listWrap.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            listWrap.innerHTML = '<div class="text-gray-500">No meta fields</div>';
            return;
        }
        data.forEach(m => {
            const row = document.createElement('div');
            row.className = 'p-3 border rounded flex items-center justify-between';
            const left = document.createElement('div');
            left.innerHTML = `<div class="font-medium">${m.namespace ? m.namespace + ' / ' : ''}${m.key}</div>
                              <div class="text-sm text-gray-600">${m.type || 'auto'} — ${JSON.stringify(m.value)}</div>`;
            const btns = document.createElement('div');
            btns.className = 'flex gap-2';
            const del = document.createElement('button');
            del.className = 'px-2 py-1 text-sm bg-red-50 text-red-600 rounded';
            del.textContent = 'Delete';
            del.addEventListener('click', () => deleteMeta(m.id));
            btns.appendChild(del);
            row.appendChild(left);
            row.appendChild(btns);
            listWrap.appendChild(row);
        });
    }

    async function deleteMeta(id) {
        if (!confirm('Delete meta field?')) return;
        const res = await fetch(`${updateBase}/${productId}/meta/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (res.ok) loadMeta();
    }

    loadMeta();
})();
</script>
