<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMeta;
use Illuminate\Http\Request;

class ProductMetaController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->meta()->get());
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'namespace' => 'nullable|string|max:50',
            'key' => 'required|string|max:100',
            'value' => 'nullable',
            'type' => 'nullable|string|max:20',
        ]);

        $meta = $product->meta()->create($validated);
        return response()->json(['meta' => $meta, 'message' => 'Meta field added']);
    }

    public function update(Request $request, Product $product, ProductMeta $meta)
    {
        $validated = $request->validate([
            'key' => 'sometimes|string|max:100',
            'value' => 'nullable',
        ]);

        $meta->update($validated);
        return response()->json(['meta' => $meta, 'message' => 'Meta field updated']);
    }

    public function destroy(Product $product, ProductMeta $meta)
    {
        $meta->delete();
        return response()->json(['message' => 'Meta field deleted']);
    }
}
