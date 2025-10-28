{{-- resources/views/admin/products/sub-tabs/images.blade.php --}}
<div id="product-images-tab" class="mt-6 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4">Images & Media</h3>

    <!-- Upload -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Upload images / files</label>
        <div class="mt-2 flex items-center space-x-3">
            <input id="product-media-input" type="file" multiple class="hidden" />
            <button id="media-upload-btn" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded">Select files</button>
            <button id="media-upload-start" type="button" class="px-4 py-2 bg-green-600 text-white rounded">Upload</button>
            <span id="media-upload-note" class="text-sm text-gray-500 ml-3">You can upload multiple files</span>
        </div>
        <div id="media-preview" class="mt-3 grid grid-cols-3 gap-3"></div>
    </div>

    <!-- Existing -->
    <div class="mt-6">
        <h4 class="font-medium mb-3">Existing Media</h4>
        <div id="existing-media" class="grid grid-cols-4 gap-4">
            <div class="col-span-4 text-gray-500">Loading...</div>
        </div>
    </div>
</div>

<script>
(function () {
    const productId = "{{ $product->id }}";
    const indexUrl = "{{ route('admin.products.media.index', $product) }}";
    const storeUrl = "{{ route('admin.products.media.store', $product) }}";
    const destroyBase = "{{ url('admin/products') }}"; // will append /{product}/media/{media}
    const input = document.getElementById('product-media-input');
    const selectBtn = document.getElementById('media-upload-btn');
    const startBtn = document.getElementById('media-upload-start');
    const preview = document.getElementById('media-preview');
    const existing = document.getElementById('existing-media');

    selectBtn.addEventListener('click', () => input.click());

    input.addEventListener('change', () => {
        preview.innerHTML = '';
        Array.from(input.files).forEach(file => {
            const el = document.createElement('div');
            el.className = 'p-3 border rounded flex flex-col items-center text-sm';
            const name = document.createElement('div');
            name.textContent = file.name;
            name.className = 'truncate w-36';
            el.appendChild(name);
            preview.appendChild(el);
        });
    });

    startBtn.addEventListener('click', async () => {
        if (!input.files.length) {
            alert('Please select files first');
            return;
        }
        startBtn.disabled = true;
        for (const file of input.files) {
            const fd = new FormData();
            fd.append('file', file);
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            });
            if (!res.ok) {
                const err = await res.json().catch(()=>({}));
                console.error('Upload error', err);
            }
        }
        input.value = '';
        preview.innerHTML = '';
        await loadExisting();
        startBtn.disabled = false;
    });

    async function loadExisting() {
        existing.innerHTML = '<div class="col-span-4 text-gray-500">Loading...</div>';
        const res = await fetch(indexUrl);
        const data = await res.json();
        existing.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            existing.innerHTML = '<div class="col-span-4 text-gray-500">No media found</div>';
            return;
        }
        data.forEach(item => {
            const wrapper = document.createElement('div');
            wrapper.className = 'p-3 border rounded flex flex-col items-start relative';
            const thumb = document.createElement('div');
            thumb.className = 'w-full h-40 mb-2 overflow-hidden rounded bg-gray-100 flex items-center justify-center';
            // Try image preview if image mime
            if (item.mime_type && item.mime_type.startsWith('image')) {
                const img = document.createElement('img');
                img.src = '/storage/' + item.file_path;
                img.alt = item.name || '';
                img.className = 'object-cover w-full h-full';
                thumb.appendChild(img);
            } else {
                thumb.innerHTML = '<div class="text-xs text-gray-600 p-2">' + (item.name || 'File') + '</div>';
            }
            const name = document.createElement('div');
            name.className = 'text-sm font-medium truncate w-full';
            name.textContent = item.name || '—';
            const actions = document.createElement('div');
            actions.className = 'flex gap-2 mt-2';
            const view = document.createElement('a');
            view.href = '/storage/' + item.file_path;
            view.target = '_blank';
            view.className = 'px-2 py-1 bg-blue-50 text-blue-600 rounded text-sm';
            view.textContent = 'View';
            const del = document.createElement('button');
            del.className = 'px-2 py-1 bg-red-50 text-red-600 rounded text-sm';
            del.textContent = 'Delete';
            del.addEventListener('click', () => deleteMedia(item.id));
            actions.appendChild(view);
            actions.appendChild(del);

            wrapper.appendChild(thumb);
            wrapper.appendChild(name);
            wrapper.appendChild(actions);
            existing.appendChild(wrapper);
        });
    }

    async function deleteMedia(mediaId) {
        if (!confirm('Delete media?')) return;
        const res = await fetch(`${destroyBase}/${productId}/media/${mediaId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (res.ok) {
            await loadExisting();
        } else {
            alert('Delete failed');
        }
    }

    // initial
    loadExisting();
})();
</script>
