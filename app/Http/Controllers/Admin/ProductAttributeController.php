<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->attributes()->get());
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100',
            'value' => 'nullable|string|max:255',
        ]);

        $attribute = $product->attributes()->create($validated);
        return response()->json(['attribute' => $attribute, 'message' => 'Attribute added']);
    }

    public function update(Request $request, Product $product, ProductAttribute $attribute)
    {
        $validated = $request->validate([
            'key' => 'sometimes|string|max:100',
            'value' => 'sometimes|string|max:255',
        ]);

        $attribute->update($validated);
        return response()->json(['attribute' => $attribute, 'message' => 'Attribute updated']);
    }

    public function destroy(Product $product, ProductAttribute $attribute)
    {
        $attribute->delete();
        return response()->json(['message' => 'Attribute deleted']);
    }
}
