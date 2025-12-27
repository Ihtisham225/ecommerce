<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $summary = Cart::getCartSummary();
        
        // Get currency information from cart items
        $baseCurrency = 'KWD';
        $currencySymbol = 'K.D';
        
        if (!empty($summary['items'])) {
            // Get all vendor currencies from cart items
            $vendorCurrencies = [];
            
            foreach ($summary['items'] as $item) {
                if (isset($item['product']) && $item['product']->vendor) {
                    $vendor = $item['product']->vendor;
                    $vendorCurrencies[$vendor->id] = $vendor->currency_code ?? 'KWD';
                }
            }
            
            // If all items are from the same vendor with same currency, use that
            if (count(array_unique($vendorCurrencies)) === 1) {
                $baseCurrency = reset($vendorCurrencies);
            }
            
            // Get currency symbol
            $currencySymbol = $this->getCurrencySymbol($baseCurrency);
        }
        
        return view('frontend.cart.index', [
            'items' => $summary['items'],
            'total' => $summary['total'],
            'subtotal' => $summary['subtotal'],
            'tax' => $summary['tax'],
            'shipping' => $summary['shipping'],
            'discount' => $summary['discount'],
            'item_count' => $summary['item_count'],
            'baseCurrency' => $baseCurrency,
            'currencySymbol' => $currencySymbol
        ]);
    }

    private function getCurrencySymbol($currencyCode)
    {
        $symbols = [
            'KWD' => 'K.D',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'PKR' => '₨',
            'INR' => '₹',
            'CAD' => '$',
            'AUD' => '$',
        ];
        
        return $symbols[$currencyCode] ?? $currencyCode;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        try {
            $cart = Cart::addItem(
                $request->product_id,
                $request->quantity,
                $request->variant_id,
                $request->options ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => Cart::totalQuantity(),
                'cart_total' => $cart->grand_total ?? $cart->total ?? 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        if ($request->quantity == 0) {
            return $this->remove($request);
        }

        try {
            $cart = Cart::updateItem($request->item_id, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'cart_count' => Cart::totalQuantity(),
                'cart_total' => $cart->grand_total ?? $cart->total ?? 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required'
        ]);

        try {
            $cart = Cart::removeItem($request->item_id);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => Cart::totalQuantity(),
                'cart_total' => $cart->grand_total ?? $cart->total ?? 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function clear()
    {
        Cart::clear();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }

    public function getSummary()
    {
        $summary = Cart::getCartSummary();

        return response()->json([
            'success' => true,
            'summary' => $summary
        ]);
    }
}