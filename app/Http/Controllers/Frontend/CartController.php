<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\StoreSetting;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $summary = Cart::getCartSummary();
        
        // Get store settings for cart
        $storeSettings = $this->getStoreSettings();
        
        // Get currency information from store settings
        $baseCurrency = $storeSettings['base_currency'] ?? 'KWD';
        $currencySymbol = $this->getCurrencySymbol($baseCurrency);
        
        // Get recommended products (you might also like)
        $recommendedProducts = $this->getRecommendedProducts($summary['items']);
        
        return view('frontend.cart.index', array_merge([
            'items' => $summary['items'],
            'total' => $summary['total'],
            'subtotal' => $summary['subtotal'],
            'tax' => $summary['tax'],
            'shipping' => $summary['shipping'],
            'discount' => $summary['discount'],
            'item_count' => $summary['item_count'],
            'baseCurrency' => $baseCurrency,
            'currencySymbol' => $currencySymbol,
            'recommendedProducts' => $recommendedProducts // Add this
        ], $storeSettings));
    }

    /**
     * Get recommended products based on cart items
     */
    private function getRecommendedProducts(array $cartItems): array
    {
        if (empty($cartItems)) {
            return [];
        }

        // Extract product IDs from cart
        $productIds = [];
        foreach ($cartItems as $item) {
            if (isset($item['product_id'])) {
                $productIds[] = $item['product_id'];
            }
        }

        if (empty($productIds)) {
            return [];
        }

        // Get products from same categories as cart items
        $recommendedProducts = Product::with(['mainImage'])
            ->where('is_active', true)
            ->where('is_published', true)
            ->whereHas('categories', function($query) use ($productIds) {
                $query->whereHas('products', function($q) use ($productIds) {
                    $q->whereIn('products.id', $productIds);
                });
            })
            ->whereNotIn('id', $productIds) // Exclude items already in cart
            ->inRandomOrder()
            ->limit(8) // Get 8 random products
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->translate('title'),
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'compare_at_price' => $product->compare_at_price,
                    'main_image' => $product->mainImage ? asset('storage/' . $product->mainImage->first()->file_path) : null,
                    'stock_status' => $product->stock_status,
                    'in_stock' => $product->stock_status === 'in_stock',
                    'discount_percentage' => $product->compare_at_price && $product->compare_at_price > $product->price 
                        ? round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100)
                        : 0,
                ];
            })
            ->toArray();

        // If we don't have enough recommended products, add some best sellers
        if (count($recommendedProducts) < 8) {
            $additionalProducts = Product::with(['mainImage'])
                ->where('is_active', true)
                ->where('is_published', true)
                ->where('is_featured', true)
                ->whereNotIn('id', $productIds)
                ->inRandomOrder()
                ->limit(8 - count($recommendedProducts))
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->translate('title'),
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'compare_at_price' => $product->compare_at_price,
                        'main_image' => $product->mainImage ? asset('storage/' . $product->mainImage->first()->file_path) : null,
                        'stock_status' => $product->stock_status,
                        'in_stock' => $product->stock_status === 'in_stock',
                        'discount_percentage' => $product->compare_at_price && $product->compare_at_price > $product->price 
                            ? round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100)
                            : 0,
                    ];
                })
                ->toArray();

            $recommendedProducts = array_merge($recommendedProducts, $additionalProducts);
        }

        return array_slice($recommendedProducts, 0, 4); // Return only 4 products for display
    }

    /**
     * Get store settings for cart
     */
    private function getStoreSettings(): array
    {
        $storeSettings = StoreSetting::first();
        
        if (!$storeSettings) {
            return [
                'base_currency' => 'KWD',
                'store_name' => 'Store',
                'shipping_methods' => [],
                'tax_settings' => [
                    'tax_enabled' => false,
                    'tax_rate' => 0,
                    'tax_inclusive' => false
                ]
            ];
        }

        $settings = $storeSettings->settings ?? [];
        
        // Get shipping methods
        $shippingMethods = $settings['shipping_methods'] ?? [
            [
                'name' => 'Standard',
                'cost' => 2.000,
                'description' => '3-5 business days',
                'is_active' => true
            ],
            [
                'name' => 'Express',
                'cost' => 5.000,
                'description' => '1-2 business days',
                'is_active' => true
            ],
            [
                'name' => 'Pickup',
                'cost' => 0,
                'description' => 'Pick up at store location',
                'is_active' => true
            ]
        ];
        
        // Get tax settings
        $taxSettings = $settings['tax_settings'] ?? [
            'tax_enabled' => false,
            'tax_rate' => 0,
            'tax_inclusive' => false
        ];
        
        return [
            'base_currency' => $storeSettings->currency_code ?? 'KWD',
            'store_name' => $storeSettings->store_name ?? 'Store',
            'shipping_methods' => $shippingMethods,
            'tax_settings' => $taxSettings
        ];
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
        
        // Get store settings for the summary
        $storeSettings = $this->getStoreSettings();
        
        return response()->json([
            'success' => true,
            'summary' => $summary,
            'store_settings' => $storeSettings
        ]);
    }
}