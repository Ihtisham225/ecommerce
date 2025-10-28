<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRelation;
use Illuminate\Http\Request;

class ProductRelationController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->relations()->with('relatedProduct')->get());
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'related_product_id' => 'required|exists:products,id',
            'relation_type' => 'required|string|in:upsell,crosssell,related',
        ]);

        $relation = $product->relations()->create($validated);
        return response()->json(['relation' => $relation, 'message' => 'Relation added']);
    }

    public function destroy(Product $product, ProductRelation $relation)
    {
        $relation->delete();
        return response()->json(['message' => 'Relation deleted']);
    }
}
