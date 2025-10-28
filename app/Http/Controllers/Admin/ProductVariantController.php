<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->variants()->latest()->get());
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:product_variants,sku',
            'options' => 'required|array',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        $variant = $product->variants()->create($validated);
        return response()->json(['variant' => $variant, 'message' => 'Variant created successfully']);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'sku' => 'sometimes|string|unique:product_variants,sku,' . $variant->id,
            'options' => 'sometimes|array',
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
        ]);

        $variant->update($validated);
        return response()->json(['variant' => $variant, 'message' => 'Variant updated successfully']);
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return response()->json(['message' => 'Variant deleted successfully']);
    }
}
