<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['variants', 'options', 'documents']);
        
        return response()->json([
            'variants' => $product->variants,
            'options' => $product->options,
            'media' => $product->documents,
        ]);
    }

    public function storeOptions(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'values' => 'required|array',
            'values.*' => 'string',
        ]);

        // Check if option with same name already exists for this product
        $existingOption = $product->options()
            ->where('name', $validated['name'])
            ->first();

        if ($existingOption) {
            // Update existing option
            $existingOption->update([
                'values' => $validated['values']
            ]);
            
            return response()->json([
                'success' => true,
                'option' => $existingOption,
                'message' => 'Option updated successfully'
            ]);
        } else {
            // Create new option
            $option = $product->options()->create($validated);
            
            return response()->json([
                'success' => true,
                'option' => $option,
                'message' => 'Option created successfully'
            ]);
        }
    }

    public function updateOption(Request $request, Product $product, ProductOption $option)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'values' => 'required|array',
            'values.*' => 'string',
        ]);

        $option->update($validated);
        
        return response()->json([
            'success' => true,
            'option' => $option,
            'message' => 'Option updated successfully'
        ]);
    }

    public function destroyOptions(Request $request, Product $product)
    {
        $request->validate([
            'option_ids' => 'required|array',
            'option_ids.*' => 'exists:product_options,id',
        ]);

        $product->options()->whereIn('id', $request->option_ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Options deleted successfully'
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'options' => 'required|array',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'track_quantity' => 'boolean',
            'taxable' => 'boolean',
            'barcode' => 'nullable|string',
            'image_id' => 'nullable|exists:documents,id',
        ]);

        // Generate SKU if not provided
        if (empty($validated['sku'])) {
            $skuData = ProductVariant::generateUniqueSkuFromParent($product, $validated['title']);
            $validated['sku'] = $skuData['sku'];
        }

        $variant = $product->variants()->create($validated);
        
        return response()->json([
            'success' => true,
            'variant' => $variant->load('image'),
            'message' => 'Variant created successfully'
        ]);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'sku' => 'sometimes|string|unique:product_variants,sku,' . $variant->id,
            'options' => 'sometimes|array',
            'price' => 'sometimes|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'track_quantity' => 'boolean',
            'taxable' => 'boolean',
            'barcode' => 'nullable|string',
            'image_id' => 'nullable|exists:documents,id',
        ]);

        $variant->update($validated);
        
        return response()->json([
            'success' => true,
            'variant' => $variant->fresh('image'),
            'message' => 'Variant updated successfully'
        ]);
    }

    public function updateBatch(Request $request, Product $product)
    {
        $request->validate([
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:product_variants,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.compare_at_price' => 'nullable|numeric|min:0',
            'variants.*.cost' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.track_quantity' => 'boolean',
            'variants.*.taxable' => 'boolean',
            'variants.*.image_id' => 'nullable|exists:documents,id',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->variants as $variantData) {
                $variant = $product->variants()->find($variantData['id']);
                if ($variant) {
                    $variant->update([
                        'price' => $variantData['price'],
                        'compare_at_price' => $variantData['compare_at_price'] ?? 0,
                        'cost' => $variantData['cost'] ?? 0,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'track_quantity' => $variantData['track_quantity'] ?? true,
                        'taxable' => $variantData['taxable'] ?? true,
                        'image_id' => $variantData['image_id'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Variants updated successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update variants'
            ], 500);
        }
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully'
        ]);
    }
}