<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Editor') }}
            </h2>

            <button id="publish-product-btn"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md">
                {{ __('Save & Publish') }}
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form id="product-form" data-id="{{ $product->id }}" class="space-y-6">
                @csrf

                {{-- Title & Description --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">{{ __('Basic Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Title (English)</label>
                            <input type="text" name="title[en]" value="{{ $product->title['en'] ?? '' }}"
                                class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Title (Arabic)</label>
                            <input type="text" name="title[ar]" dir="rtl" value="{{ $product->title['ar'] ?? '' }}"
                                class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Description (English)</label>
                            <textarea name="description[en]" rows="4" class="mt-1 w-full border-gray-300 rounded-md">{{ $product->description['en'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Description (Arabic)</label>
                            <textarea name="description[ar]" dir="rtl" rows="4" class="mt-1 w-full border-gray-300 rounded-md">{{ $product->description['ar'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">{{ __('Pricing') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Price</label>
                            <input type="number" step="0.01" name="price" value="{{ $product->price ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Compare at Price</label>
                            <input type="number" step="0.01" name="compare_at_price" value="{{ $product->compare_at_price ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">SKU</label>
                            <input type="text" name="sku" value="{{ $product->sku ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">{{ __('Inventory') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Stock Quantity</label>
                            <input type="number" name="stock_quantity" value="{{ $product->stock_quantity ?? 0 }}"
                                class="mt-1 w-full border-gray-300 rounded-md">
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <input type="checkbox" name="track_stock" value="1" {{ $product->track_stock ? 'checked' : '' }}>
                            <label>Track Stock</label>
                        </div>
                    </div>
                </div>

                {{-- Organization --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">{{ __('Organization') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Category</label>
                            <select name="category_id" class="mt-1 w-full border-gray-300 rounded-md">
                                <option value="">-- Select Category --</option>
                                @foreach(\App\Models\Category::all() as $c)
                                    <option value="{{ $c->id }}" {{ $product->categories->first()->id ?? '' == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Brand</label>
                            <select name="brand_id" class="mt-1 w-full border-gray-300 rounded-md">
                                <option value="">-- Select Brand --</option>
                                @foreach(\App\Models\Brand::all() as $b)
                                    <option value="{{ $b->id }}" {{ $product->brand_id == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label><input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}> Active</label>
                            <label><input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}> Featured</label>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">{{ __('Images') }}</h3>
                    @include('admin.products.partials._images', ['product' => $product])
                </div>

                {{-- Variants --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">{{ __('Variants') }}</h3>
                    @include('admin.products.partials._variants', ['product' => $product])
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('product-form');
            const publishBtn = document.getElementById('publish-product-btn');
            const productId = form.dataset.id;
            let autosaveTimer = null;
            let isSaving = false;

            const autosave = async () => {
                if (isSaving) return;
                isSaving = true;
                const fd = new FormData();

                // collect basic text inputs
                const formData = new FormData(form);
                // We will send minimal data for autosave (no images)
                // Append title[] & description[] correctly
                for (const [k,v] of formData.entries()) {
                    if (k.startsWith('new_main_image') || k.startsWith('new_gallery')) continue;
                    fd.append(k, v);
                }

                try {
                    const res = await fetch(`/admin/products/${productId}/autosave`, {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value},
                        body: fd
                    });
                    // optional: show saved indicator
                    console.log('autosave response', await res.json());
                } catch (err) {
                    console.error('Autosave failed', err);
                } finally {
                    isSaving = false;
                }
            };

            // bind inputs for autosave (debounced)
            form.querySelectorAll('input, textarea, select').forEach(el => {
                el.addEventListener('input', () => {
                    clearTimeout(autosaveTimer);
                    autosaveTimer = setTimeout(autosave, 1200);
                });
            });

            // Manual save (full save including images + variants)
            publishBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                publishBtn.disabled = true;
                const fd = new FormData(form);

                try {
                    const res = await fetch(`/admin/products/${productId}/publish`, {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value},
                        body: fd
                    });
                    const json = await res.json();
                    if (res.ok) {
                        alert(json.message);
                        // Optionally reload to show published state
                        window.location.reload();
                    } else {
                        alert(json.message || 'Failed to publish');
                        console.error(json);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Publish failed. Check console.');
                } finally {
                    publishBtn.disabled = false;
                }
            });

            // Save button behavior (if you keep Save Product separate from Publish)
            document.getElementById('save-product-btn')?.addEventListener('click', async (e) => {
                e.preventDefault();
                // full update without publishing
                const fd = new FormData(form);
                try {
                    const res = await fetch(`/admin/products/${productId}`, {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value},
                        body: fd
                    });
                    const json = await res.json();
                    alert(json.message || 'Saved');
                } catch (err) {
                    console.error(err);
                    alert('Save failed.');
                }
            });
        });
    </script>

</x-app-layout>
