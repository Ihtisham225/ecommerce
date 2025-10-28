{{-- resources/views/admin/products/sub-tabs/variants.blade.php --}}
<div id="product-variants-tab" class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4">Variants</h3>

    <div class="mb-4">
        <button id="add-variant-btn" class="px-3 py-2 bg-indigo-600 text-white rounded">+ Add Variant</button>
    </div>

    <div id="variants-list" class="space-y-3">
        <div class="text-gray-500">Loading variants...</div>
    </div>

    <!-- Variant Modal (simple inline form) -->
    <div id="variant-form-wrap" class="hidden mt-6 p-4 border rounded">
        <form id="variant-form" class="space-y-3">
            <input type="hidden" name="variant_id" id="variant_id" value="">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm">SKU</label>
                    <input name="sku" id="variant-sku" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm">Price</label>
                    <input name="price" id="variant-price" type="number" step="0.01" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm">Compare Price</label>
                    <input name="compare_price" id="variant-compare" type="number" step="0.01" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm">Stock Quantity</label>
                    <input name="stock_quantity" id="variant-stock" type="number" class="w-full border rounded p-2" />
                </div>
                <div class="col-span-2">
                    <label class="text-sm">Options (JSON)</label>
                    <input name="options" id="variant-options" class="w-full border rounded p-2" placeholder='{"Color":"Red","Size":"M"}' />
                    <p class="text-xs text-gray-500 mt-1">You can send options as JSON (key:value) — will be stored as array.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
                <button type="button" id="variant-cancel" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const productId = "{{ $product->id }}";
    const listUrl = "{{ route('admin.products.variants.index', $product) }}";
    const storeUrl = "{{ route('admin.products.variants.store', $product) }}";
    const updateBase = "{{ url('admin/products') }}"; // will be /admin/products/{product}/variants/{variant}
    const variantsList = document.getElementById('variants-list');
    const addBtn = document.getElementById('add-variant-btn');
    const formWrap = document.getElementById('variant-form-wrap');
    const form = document.getElementById('variant-form');
    const cancelBtn = document.getElementById('variant-cancel');

    addBtn.addEventListener('click', () => {
        openForm();
    });

    cancelBtn.addEventListener('click', () => {
        closeForm();
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('variant_id').value;
        const payload = {
            sku: document.getElementById('variant-sku').value,
            price: document.getElementById('variant-price').value,
            compare_price: document.getElementById('variant-compare').value,
            stock_quantity: document.getElementById('variant-stock').value,
            options: JSON.parse(document.getElementById('variant-options').value || '{}')
        };

        let url = storeUrl;
        let method = 'POST';
        if (id) {
            url = `${updateBase}/${productId}/variants/${id}`;
            method = 'PUT';
        }

        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (res.ok) {
            await loadVariants();
            closeForm();
        } else {
            alert(data.message || 'Error saving variant');
        }
    });

    async function loadVariants() {
        variantsList.innerHTML = '<div class="text-gray-500">Loading variants...</div>';
        const res = await fetch(listUrl);
        const data = await res.json();
        variantsList.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            variantsList.innerHTML = '<div class="text-gray-500">No variants yet</div>';
            return;
        }
        data.forEach(v => {
            const row = document.createElement('div');
            row.className = 'p-3 border rounded flex items-center justify-between';
            const left = document.createElement('div');
            left.innerHTML = `<div class="font-medium">${v.sku || '—'}</div>
                              <div class="text-sm text-gray-500">Price: ${v.price}</div>
                              <div class="text-xs text-gray-500">Options: ${JSON.stringify(v.options || {})}</div>`;
            const actions = document.createElement('div');
            actions.className = 'flex gap-2';
            const edit = document.createElement('button');
            edit.className = 'px-2 py-1 bg-yellow-50 text-yellow-700 rounded';
            edit.textContent = 'Edit';
            edit.addEventListener('click', () => openForm(v));
            const del = document.createElement('button');
            del.className = 'px-2 py-1 bg-red-50 text-red-600 rounded';
            del.textContent = 'Delete';
            del.addEventListener('click', () => deleteVariant(v.id));
            actions.appendChild(edit);
            actions.appendChild(del);
            row.appendChild(left);
            row.appendChild(actions);
            variantsList.appendChild(row);
        });
    }

    async function deleteVariant(id) {
        if (!confirm('Delete variant?')) return;
        const res = await fetch(`${updateBase}/${productId}/variants/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (res.ok) await loadVariants();
    }

    function openForm(variant = null) {
        formWrap.classList.remove('hidden');
        document.getElementById('variant_id').value = variant?.id || '';
        document.getElementById('variant-sku').value = variant?.sku || '';
        document.getElementById('variant-price').value = variant?.price ?? '';
        document.getElementById('variant-compare').value = variant?.compare_price ?? '';
        document.getElementById('variant-stock').value = variant?.stock_quantity ?? '';
        document.getElementById('variant-options').value = variant ? JSON.stringify(variant.options || {}) : '';
        window.scrollTo({ top: formWrap.offsetTop - 60, behavior: 'smooth' });
    }

    function closeForm() {
        formWrap.classList.add('hidden');
        form.reset();
        document.getElementById('variant_id').value = '';
    }

    // initial
    loadVariants();
})();
</script>
