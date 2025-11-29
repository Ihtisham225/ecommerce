<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    // Fetch order items
    public function index(Order $order)
    {
        return response()->json([
            'success' => true,
            'items' => $order->items()->with('product', 'variant')->get()
        ]);
    }

    // Add item
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($validated['product_id']);

            $item = $order->items()->create([
                'product_id' => $validated['product_id'],
                'product_variant_id' => $validated['product_variant_id'] ?? null,
                'sku' => $validated['product_variant_id'] ? $product->variants()->find($validated['product_variant_id'])->sku : $product->sku,
                'title' => $product->title['en'] ?? 'Untitled Product',
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'total' => $validated['quantity'] * $validated['price'],
            ]);

            $this->recalculateTotals($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'item' => $item
            ]);

        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Update quantity or price
    public function update(Request $request, Order $order, OrderItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $item->update([
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'total' => $validated['quantity'] * $validated['price'],
            ]);

            $this->recalculateTotals($order);

            DB::commit();

            return response()->json(['success' => true, 'item' => $item]);

        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Delete item
    public function destroy(Order $order, OrderItem $item)
    {
        DB::beginTransaction();

        try {
            $item->delete();

            $this->recalculateTotals($order);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function recalculateTotals(Order $order)
    {
        $subtotal = $order->items()->sum('total');

        $order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal, // You can extend this to taxes/discounts
        ]);
    }
}
