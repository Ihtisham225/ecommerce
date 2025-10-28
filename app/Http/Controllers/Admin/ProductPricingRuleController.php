<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPricingRule;
use Illuminate\Http\Request;

class ProductPricingRuleController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->pricingRules()->get());
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:discount,bundle,tier',
            'value' => 'required|numeric',
            'min_qty' => 'nullable|integer|min:1',
            'max_qty' => 'nullable|integer|min:1',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'boolean',
        ]);

        $rule = $product->pricingRules()->create($validated);
        return response()->json(['rule' => $rule, 'message' => 'Pricing rule added']);
    }

    public function update(Request $request, Product $product, ProductPricingRule $rule)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'value' => 'sometimes|numeric',
            'is_active' => 'boolean',
        ]);

        $rule->update($validated);
        return response()->json(['rule' => $rule, 'message' => 'Pricing rule updated']);
    }

    public function destroy(Product $product, ProductPricingRule $rule)
    {
        $rule->delete();
        return response()->json(['message' => 'Pricing rule deleted']);
    }
}
