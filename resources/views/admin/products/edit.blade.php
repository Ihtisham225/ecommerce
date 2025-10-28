<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">

                <div class="bg-indigo-600 px-6 py-4 rounded-t-lg">
                    <h1 class="text-2xl font-bold text-white">{{ __('Edit Product') }}</h1>
                </div>

                <div class="p-6 border border-gray-200 border-t-0 rounded-b-lg">
                    <form id="product-update-form" data-id="{{ $product->id }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @include('admin.products.partials.form', ['product' => $product])

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                ← {{ __('Back to Products') }}
                            </a>

                            <div class="flex items-center gap-3">
                                <button id="save-product-btn" type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md">
                                    {{ __('Save Product') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Horizontal tabs (Shopify-style) -->
                    <div class="mt-8">
                        <div class="border-b border-gray-200">
                            <nav class="flex -mb-px space-x-6" aria-label="Tabs">
                                <button class="tab-link py-4 px-1 text-sm font-medium text-indigo-600 border-b-2 border-indigo-600" data-tab="general">General</button>
                                <button class="tab-link py-4 px-1 text-sm font-medium text-gray-600" data-tab="variants">Variants</button>
                                <button class="tab-link py-4 px-1 text-sm font-medium text-gray-600" data-tab="meta">Meta Fields</button>
                                <button class="tab-link py-4 px-1 text-sm font-medium text-gray-600" data-tab="images">Images</button>
                                <button class="tab-link py-4 px-1 text-sm font-medium text-gray-600" data-tab="pricing">Pricing Rules</button>
                                <button class="tab-link py-4 px-1 text-sm font-medium text-gray-600" data-tab="relations">Relations</button>
                            </nav>
                        </div>

                        <div id="tab-contents" class="mt-6">
                            <div id="tab-general" class="tab-panel"> <!-- general content already above --> <div class="text-sm text-gray-500">General product info is at the top (save there).</div></div>
                            <div id="tab-variants" class="tab-panel hidden"></div>
                            <div id="tab-meta" class="tab-panel hidden"></div>
                            <div id="tab-images" class="tab-panel hidden"></div>
                            <div id="tab-pricing" class="tab-panel hidden"></div>
                            <div id="tab-relations" class="tab-panel hidden"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- load sub-tabs as needed; each partial contains JS that will call APIs --}}
    @include('admin.products.sub-tabs.variants', ['product' => $product])
    @include('admin.products.sub-tabs.meta', ['product' => $product])
    @include('admin.products.sub-tabs.images', ['product' => $product])
    @include('admin.products.sub-tabs.pricing', ['product' => $product])
    @include('admin.products.sub-tabs.relations', ['product' => $product])

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('product-update-form');
        const productId = form.dataset.id;
        const saveBtn = document.getElementById('save-product-btn');
        let autosaveTimer;
        let isSaving = false;

        // --- Auto Save Function ---
        const autoSave = () => {
            if (isSaving) return; // prevent overlapping saves
            isSaving = true;

            const fd = new FormData(form);
            fd.append('_method', 'PUT');

            fetch(`{{ route('admin.products.autosave', $product->id) }}`, {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            })
            .then(res => res.json())
            .then(json => {
                if (json.status === 'autosaved') {
                    console.log('✅ Autosaved at', json.updated_at);
                    document.getElementById('autosave-status').innerText = `Saved at ${json.updated_at}`;
                }
            })
            .catch(err => console.error('Autosave failed:', err))
            .finally(() => isSaving = false);
        };

        // --- Listen to any input changes ---
        form.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('input', () => {
                clearTimeout(autosaveTimer);
                autosaveTimer = setTimeout(autoSave, 1000); // debounce 1s
            });
        });

        // --- Manual Save / Publish ---
        saveBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                const res = await fetch(`{{ url('admin/products') }}/${productId}/publish`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const json = await res.json();
                if (res.ok) {
                    alert(json.message);
                    document.getElementById('autosave-status').innerText = 'Published ✅';
                }
            } catch (err) {
                alert('Publish failed!');
            }
        });

        // --- Tab Handling ---
        const tabLinks = document.querySelectorAll('.tab-link');
        tabLinks.forEach(btn => {
            btn.addEventListener('click', () => {
                tabLinks.forEach(b => { 
                    b.classList.remove('text-indigo-600','border-indigo-600');
                    b.classList.add('text-gray-600'); 
                });
                btn.classList.add('text-indigo-600','border-indigo-600');
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
                document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
                window.scrollTo({ top: document.getElementById('tab-' + btn.dataset.tab).offsetTop - 80, behavior: 'smooth' });
            });
        });
    });
    </script>

    <div id="autosave-status" class="text-xs text-gray-500 mt-3">Not saved yet</div>
</x-app-layout>
