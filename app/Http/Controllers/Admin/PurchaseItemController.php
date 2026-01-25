<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PurchaseItemController extends Controller
{
    /**
     * Search products for purchase items
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        
        $products = Product::with(['variants'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sku', 'LIKE', "%{$search}%")
                      ->orWhere('title', 'LIKE', "%{$search}%")
                      ->orWhereHas('translations', function ($translation) use ($search) {
                          $translation->where('title', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->where('is_active', true)
            ->orderBy('title')
            ->limit(50)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->translate('title') ?? $product->title,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'cost' => $product->cost,
                    'has_variants' => $product->variants->isNotEmpty(),
                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'title' => $variant->title,
                            'sku' => $variant->sku,
                            'price' => $variant->price,
                            'cost' => $variant->cost,
                            'options' => $variant->options,
                        ];
                    }),
                ];
            });
        
        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }
    
    /**
     * Get product variants
     */
    public function getVariants($productId): JsonResponse
    {
        $variants = ProductVariant::where('product_id', $productId)
            ->where('is_active', true)
            ->orderBy('title')
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'title' => $variant->title,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'cost' => $variant->cost,
                    'options' => $variant->options,
                ];
            });
        
        return response()->json([
            'success' => true,
            'variants' => $variants,
        ]);
    }
    
    /**
     * Get product details
     */
    public function getProduct($id): JsonResponse
    {
        $product = Product::with(['variants'])
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'title' => $product->translate('title') ?? $product->title,
                'sku' => $product->sku,
                'price' => $product->price,
                'cost' => $product->cost,
                'has_variants' => $product->variants->isNotEmpty(),
            ],
        ]);
    }
    
    /**
     * Get variant details
     */
    public function getVariant($id): JsonResponse
    {
        $variant = ProductVariant::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'variant' => [
                'id' => $variant->id,
                'title' => $variant->title,
                'sku' => $variant->sku,
                'price' => $variant->price,
                'cost' => $variant->cost,
                'options' => $variant->options,
                'product' => [
                    'id' => $variant->product_id,
                    'title' => $variant->product->translate('title') ?? $variant->product->title,
                ],
            ],
        ]);
    }
    
    /**
     * Calculate purchase item totals
     */
    public function calculateTotals(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
        
        $items = collect($request->input('items'))->map(function ($item) {
            $quantity = floatval($item['quantity']);
            $unitPrice = floatval($item['unit_price']);
            
            return [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $quantity * $unitPrice,
            ];
        });
        
        $subtotal = $items->sum('total');
        
        return response()->json([
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'grand_total' => $subtotal, // Add tax/discount calculations here if needed
        ]);
    }
}