<div class="space-y-6">
    {{-- Validation errors (server-rendered fallback) --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Title (English)</label>
            <input type="text" name="title[en]" value="{{ old('title.en', $product->title['en'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Title (Arabic)</label>
            <input type="text" name="title[ar]" dir="rtl" value="{{ old('title.ar', $product->title['ar'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" name="price" step="0.01" value="{{ old('price', $product->price ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 p-2">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description (English)</label>
            <textarea name="description[en]" rows="4" class="mt-1 block w-full rounded-md border-gray-300 p-2">{{ old('description.en', $product->description['en'] ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description (Arabic)</label>
            <textarea name="description[ar]" dir="rtl" rows="4" class="mt-1 block w-full rounded-md border-gray-300 p-2">{{ old('description.ar', $product->description['ar'] ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 p-2">
                <option value="">{{ __('-- Select Category --') }}</option>
                @foreach(\App\Models\Category::all() as $c)
                    <option value="{{ $c->id }}" {{ (old('category_id', $product->categories->first()->id ?? null) == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Brand</label>
            <select name="brand_id" class="mt-1 block w-full rounded-md border-gray-300 p-2">
                <option value="">{{ __('-- Select Brand --') }}</option>
                @foreach(\App\Models\Brand::all() as $b)
                    <option value="{{ $b->id }}" {{ (old('brand_id', $product->brand_id ?? null) == $b->id) ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="h-4 w-4">
            <label for="is_active" class="text-sm text-gray-700">Active</label>
        </div>

        <div class="flex items-center gap-3">
            <label class="block text-sm font-medium text-gray-700">Product Type</label>
            <select name="type" class="mt-1 block rounded-md border-gray-300 p-2">
                <option value="simple" {{ (old('type', $product->type ?? '') == 'simple') ? 'selected' : '' }}>Simple</option>
                <option value="variable" {{ (old('type', $product->type ?? '') == 'variable') ? 'selected' : '' }}>Variable</option>
            </select>
        </div>

    </div>
</div>
