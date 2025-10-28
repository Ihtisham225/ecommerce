<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $product->title['en'] ?? __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-6">
                <div class="mb-6">
                    <h3 class="text-2xl font-semibold">{{ $product->title['en'] ?? '-' }}</h3>
                    <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium">Price</h4>
                        <p>{{ $product->price }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium">Stock</h4>
                        <p>{{ $product->stock_quantity ?? 0 }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <h4 class="font-medium">Description</h4>
                        <div class="prose mt-2 max-w-none dark:prose-invert">{!! $product->description['en'] ?? '-' !!}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Edit Product</a>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
