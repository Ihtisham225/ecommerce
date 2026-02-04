<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'currency_code',
        'subtotal',
        'tax_total',
        'shipping_total',
        'discount_total',
        'grand_total',
        'notes',
        'is_guest'
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'tax_total' => 'decimal:3',
        'shipping_total' => 'decimal:3',
        'discount_total' => 'decimal:3',
        'grand_total' => 'decimal:3',
        'is_guest' => 'boolean'
    ];

    /**
     * Get the cart items
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get cart data - Hybrid approach
     */
    public static function getCart()
    {
        if (auth()->check()) {
            return self::getDatabaseCart();
        } else {
            return self::getSessionCart();
        }
    }

    /**
     * Get cart for registered users (database-based)
     */
    private static function getDatabaseCart()
    {
        $cart = self::getCurrentCart();
        
        if (!$cart) {
            return (object) [
                'items' => [],
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 0,
                'discount' => 0,
                'total' => 0,
                'source' => 'database'
            ];
        }

        // IMPORTANT: Load the product AND variant relationships
        $cart->load([
            'items.product', 
            'items.variant' // Add this line to load the variant
        ]);

        // Recalculate totals
        $cart->calculateTotals();
        $cart->refresh();

        return (object) [
            'items' => $cart->items->map(function ($item) {
                // Use variant price if variant exists
                $price = $item->price; // This should already be set correctly from addItem
                
                // But double-check: if we have a variant, use variant price
                if ($item->variant && $item->variant->price && $item->variant->price > 0) {
                    $price = $item->variant->price;
                }
                
                return [
                    'id' => $item->id,
                    'item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product' => $item->product,  // Eloquent model
                    'variant_id' => $item->variant_id,
                    'variant' => $item->variant,  // Add variant to the array
                    'quantity' => $item->quantity,
                    'options' => $item->options ? json_decode($item->options, true) : [],
                    'price' => $price,  // Use the correct price
                    'total' => $price * $item->quantity,
                ];
            })->values()->toArray(),
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax_total,
            'shipping' => $cart->shipping_total,
            'discount' => $cart->discount_total,
            'total' => $cart->grand_total,
            'source' => 'database',
            'cart_model' => $cart
        ];
    }

    private static function getSessionCart()
    {
        $cart = Session::get('cart', (object) [
            'items' => [],
            'subtotal' => 0,
            'tax' => 0,
            'shipping' => 0,
            'discount' => 0,
            'total' => 0,
            'source' => 'session'
        ]);

        // Recalculate session totals
        self::calculateSessionTotals($cart);
        
        return $cart;
    }

    /**
     * Add item to cart
     */
    public static function addItem($productId, $quantity = 1, $variantId = null, $options = [])
    {
        if (auth()->check()) {
            return self::addItemToDatabase($productId, $quantity, $variantId, $options);
        } else {
            return self::addItemToSession($productId, $quantity, $variantId, $options);
        }
    }

    /**
     * Add item to database cart
     */
    private static function addItemToDatabase($productId, $quantity, $variantId, $options)
    {
        $cart = self::getCurrentCart();
        
        if (!$cart) {
            $cart = self::create([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'currency_code' => 'KWD',
                'is_guest' => false
            ]);
        }

        // Check if item already exists
        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->where('options', json_encode($options))
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity
            ]);
        } else {
            $product = Product::findOrFail($productId);

            $price = $product->price; // Default to product price

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant && $variant->price) {
                    $price = $variant->price; // Use variant price
                }
            }
            
            $cart->items()->create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $price, // Use variant price here
                'options' => $options
            ]);
        }

        $cart->calculateTotals();
        return $cart;
    }

    /**
     * Add item to session cart
     */
    private static function addItemToSession($productId, $quantity, $variantId, $options)
    {
        $cart = Session::get('cart', (object) [
            'items' => [],
            'subtotal' => 0,
            'tax' => 0,
            'shipping' => 0,
            'discount' => 0,
            'total' => 0,
            'source' => 'session'
        ]);

        $product = Product::findOrFail($productId);

        // FIX: Get variant price if variant_id exists
        $price = $product->price; // Default to product price
        
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->price) {
                $price = $variant->price; // Use variant price if available
            }
        }
        
        // Check stock
        if ($product->track_stock && $product->stock_quantity < $quantity) {
            throw new \Exception('Insufficient stock available');
        }
        
        $itemKey = self::generateItemKey($productId, $variantId, $options);
        
        if (isset($cart->items[$itemKey])) {
            $newQuantity = $cart->items[$itemKey]['quantity'] + $quantity;
            
            if ($product->track_stock && $product->stock_quantity < $newQuantity) {
                throw new \Exception('Insufficient stock available');
            }
            
            $cart->items[$itemKey]['quantity'] = $newQuantity;
            $cart->items[$itemKey]['total'] = $cart->items[$itemKey]['price'] * $newQuantity;
        } else {
            $cart->items[$itemKey] = [
                'id' => $itemKey,
                'item_id' => $itemKey,
                'product_id' => $productId,
                'product' => $product,
                'quantity' => $quantity,
                'variant_id' => $variantId,
                'options' => $options,
                'price' => $price, // Use variant price here
                'total' => $price * $quantity
            ];
        }

        self::calculateSessionTotals($cart);
        Session::put('cart', $cart);
        
        return $cart;
    }

    /**
     * Update item quantity
     */
    public static function updateItem($itemKey, $quantity)
    {
        if (auth()->check()) {
            return self::updateItemInDatabase($itemKey, $quantity);
        } else {
            return self::updateItemInSession($itemKey, $quantity);
        }
    }

    /**
     * Update item in database cart
     */
    private static function updateItemInDatabase($itemId, $quantity)
    {
        $cart = self::getCurrentCart();
        
        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        $item = $cart->items()->find($itemId);
        
        if (!$item) {
            throw new \Exception('Item not found in cart');
        }

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $quantity]);
        }

        $cart->calculateTotals();
        return $cart;
    }

    /**
     * Update item in session cart
     */
    private static function updateItemInSession($itemKey, $quantity)
    {
        $cart = Session::get('cart');
        
        if (!isset($cart->items[$itemKey])) {
            throw new \Exception('Item not found in cart');
        }

        if ($quantity <= 0) {
            unset($cart->items[$itemKey]);
        } else {
            $cart->items[$itemKey]['quantity'] = $quantity;
            $cart->items[$itemKey]['total'] = $cart->items[$itemKey]['price'] * $quantity;
        }

        self::calculateSessionTotals($cart);
        Session::put('cart', $cart);
        
        return $cart;
    }

    /**
     * Remove item from cart
     */
    public static function removeItem($itemKey)
    {
        if (auth()->check()) {
            return self::removeItemFromDatabase($itemKey);
        } else {
            return self::removeItemFromSession($itemKey);
        }
    }

    /**
     * Remove item from database cart
     */
    private static function removeItemFromDatabase($itemId)
    {
        $cart = self::getCurrentCart();
        
        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        $cart->items()->where('id', $itemId)->delete();
        $cart->calculateTotals();
        
        return $cart;
    }

    /**
     * Remove item from session cart
     */
    private static function removeItemFromSession($itemKey)
    {
        $cart = Session::get('cart');
        
        if (isset($cart->items[$itemKey])) {
            unset($cart->items[$itemKey]);
        }

        self::calculateSessionTotals($cart);
        Session::put('cart', $cart);
        
        return $cart;
    }

    /**
     * Clear cart
     */
    public static function clear()
    {
        if (auth()->check()) {
            return self::clearDatabaseCart();
        } else {
            return self::clearSessionCart();
        }
    }

    /**
     * Clear database cart
     */
    private static function clearDatabaseCart()
    {
        $cart = self::getCurrentCart();
        
        if ($cart) {
            $cart->items()->delete();
            $cart->calculateTotals();
        }

        return $cart;
    }

    /**
     * Clear session cart
     */
    private static function clearSessionCart()
    {
        Session::forget('cart');
        return self::getSessionCart();
    }

    /**
     * Calculate totals
     */
    public function calculateTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            // If item has a variant with price, use variant price
            $price = $item->price;
            
            // Double check variant price
            if ($item->variant_id && $item->variant && $item->variant->price) {
                $price = $item->variant->price;
            }
            
            return $price * $item->quantity;
        });

        // Get store settings
        $storeSettings = StoreSetting::first();
        $settings = $storeSettings->settings ?? [];

        // Get tax settings
        $taxSettings = $settings['tax_settings'] ?? [
            'tax_enabled' => false,
            'tax_rate' => 0,
            'tax_inclusive' => false
        ];

        // Calculate tax
        $taxAmount = 0;
        if ($taxSettings['tax_enabled'] ?? false) {
            $taxRate = ($taxSettings['tax_rate'] ?? 0) / 100;
            if ($taxSettings['tax_inclusive'] ?? false) {
                // Tax is included in price
                $taxAmount = $subtotal * $taxRate / (1 + $taxRate);
            } else {
                // Tax is added on top
                $taxAmount = $subtotal * $taxRate;
            }
        }

        // Get shipping methods
        $shippingMethods = $settings['shipping_methods'] ?? [
            [
                'name' => 'Standard',
                'cost' => 2.000,
                'description' => '3-5 business days',
                'is_active' => true
            ]
        ];

        // Use default shipping cost (first active method)
        $shippingCost = 0;
        foreach ($shippingMethods as $method) {
            if ($method['is_active'] ?? true) {
                $shippingCost = $method['cost'];
                break;
            }
        }

        $this->subtotal = $subtotal;
        $this->tax_total = $taxAmount;
        $this->shipping_total = $shippingCost;
        $this->discount_total = 0;
        $this->grand_total = $subtotal + $taxAmount + $shippingCost;
        $this->currency_code = $storeSettings->currency_code ?? 'KWD';
        
        $this->save();
    }

    /**
     * Calculate totals for session cart
     */
    private static function calculateSessionTotals(&$cart)
    {
        $subtotal = collect($cart->items)->sum('total');

        // Get store settings
        $storeSettings = StoreSetting::first();
        $settings = $storeSettings->settings ?? [];

        // Get tax settings
        $taxSettings = $settings['tax_settings'] ?? [
            'tax_enabled' => false,
            'tax_rate' => 0,
            'tax_inclusive' => false
        ];

        // Calculate tax
        $taxAmount = 0;
        if ($taxSettings['tax_enabled'] ?? false) {
            $taxRate = ($taxSettings['tax_rate'] ?? 0) / 100;
            if ($taxSettings['tax_inclusive'] ?? false) {
                // Tax is included in price
                $taxAmount = $subtotal * $taxRate / (1 + $taxRate);
            } else {
                // Tax is added on top
                $taxAmount = $subtotal * $taxRate;
            }
        }

        // Get shipping methods
        $shippingMethods = $settings['shipping_methods'] ?? [
            [
                'name' => 'Standard',
                'cost' => 2.000,
                'description' => '3-5 business days',
                'is_active' => true
            ]
        ];

        // Use default shipping cost (first active method)
        $shippingCost = 0;
        foreach ($shippingMethods as $method) {
            if ($method['is_active'] ?? true) {
                $shippingCost = $method['cost'];
                break;
            }
        }

        $cart->subtotal = $subtotal;
        $cart->tax = $taxAmount;
        $cart->shipping = $shippingCost;
        $cart->discount = 0;
        $cart->total = $subtotal + $taxAmount + $shippingCost;
    }

    /**
     * Get store settings for cart
     */
    public static function getStoreSettingsForCart(): array
    {
        $storeSettings = StoreSetting::first();
        
        if (!$storeSettings) {
            return [
                'base_currency' => 'KWD',
                'store_name' => 'Store',
                'shipping_methods' => [
                    [
                        'name' => 'Standard',
                        'cost' => 2.000,
                        'description' => '3-5 business days',
                        'is_active' => true
                    ]
                ],
                'tax_settings' => [
                    'tax_enabled' => false,
                    'tax_rate' => 0,
                    'tax_inclusive' => false
                ]
            ];
        }

        $settings = $storeSettings->settings ?? [];
        
        return [
            'base_currency' => $storeSettings->currency_code ?? 'KWD',
            'store_name' => $storeSettings->store_name ?? 'Store',
            'shipping_methods' => $settings['shipping_methods'] ?? [
                [
                    'name' => 'Standard',
                    'cost' => 2.000,
                    'description' => '3-5 business days',
                    'is_active' => true
                ]
            ],
            'tax_settings' => $settings['tax_settings'] ?? [
                'tax_enabled' => false,
                'tax_rate' => 0,
                'tax_inclusive' => false
            ]
        ];
    }

    /**
     * Get total quantity
     */
    public static function totalQuantity()
    {
        $cart = self::getCart();
        
        if ($cart->source === 'database') {
            return $cart->cart_model->items->sum('quantity') ?? 0;
        } else {
            return collect($cart->items)->sum('quantity');
        }
    }

    /**
     * Get cart summary for display
     */
    public static function getCartSummary()
    {
        $cart = self::getCart();
        
        \Log::info('Cart summary called:', [
            'cart_source' => $cart->source,
            'cart_items_count' => $cart->source === 'database' 
                ? ($cart->cart_model->items->count() ?? 0) 
                : count($cart->items ?? []),
            'cart_subtotal' => $cart->subtotal,
            'cart_total' => $cart->total
        ]);
        
        $items = [];
        
        if ($cart->source === 'database') {
            // Database cart logic
            $items = $cart->cart_model->items->map(function ($item) {
                \Log::info('Database cart item:', [
                    'item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity
                ]);
                
                return [
                    'id' => $item->id,
                    'item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->translate('title') ?? $item->product->title ?? 'Untitled',
                    'product_slug' => $item->product->slug,
                    'product_image' => $item->product->mainImage() ? 
                        asset('storage/' . $item->product->mainImage()->first()->file_path) : null,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                    'options' => $item->options ?? []
                ];
            })->toArray();
        } else {
            // Session cart logic
            $items = collect($cart->items)->map(function ($item, $key) {
                \Log::info('Session cart item:', [
                    'item_key' => $key,
                    'product_id' => $item['product']->id ?? 'N/A',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 0
                ]);
                
                return [
                    'id' => $key,
                    'item_id' => $key,
                    'product_id' => $item['product']->id ?? null,
                    'product_name' => $item['product']->translate('title') ?? $item['product']->title ?? 'Untitled',
                    'product_slug' => $item['product']->slug ?? null,
                    'product_image' => isset($item['product']->mainImage) && $item['product']->mainImage() ? 
                        asset('storage/' . $item['product']->mainImage()->first()->file_path) : null,
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'] ?? 0,
                    'price' => $item['price'] ?? 0,
                    'total' => $item['total'] ?? 0,
                    'options' => $item['options'] ?? []
                ];
            })->values()->toArray();
        }

        \Log::info('Final cart summary items:', [
            'items_count' => count($items),
            'items_structure' => $items
        ]);

        return [
            'items' => $items,
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'shipping' => $cart->shipping,
            'discount' => $cart->discount,
            'total' => $cart->total,
            'item_count' => self::totalQuantity(),
            'source' => $cart->source
        ];
    }

    /**
     * Generate item key for session cart
     */
    private static function generateItemKey($productId, $variantId, $options)
    {
        $key = "product_{$productId}";
        
        if ($variantId) {
            $key .= "_variant_{$variantId}";
        }
        
        if (!empty($options)) {
            ksort($options);
            $key .= "_" . md5(json_encode($options));
        }
        
        return $key;
    }

    /**
     * Migrate session cart to authenticated user's database cart
     */
    public static function migrateSessionToDatabase(): void
    {
        if (!auth()->check()) {
            return;
        }

        $sessionCart = Session::get('cart');

        if (!$sessionCart || empty($sessionCart->items)) {
            return;
        }

        // Get or create user cart
        $cart = self::getCurrentCart();

        foreach ($sessionCart->items as $item) {
            $existingItem = $cart->items()
                ->where('product_id', $item['product_id'])
                ->where('variant_id', $item['variant_id'])
                ->where('options', json_encode($item['options']))
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item['quantity'],
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'options' => json_encode($item['options']),
                ]);
            }
        }

        // Recalculate totals
        $cart->calculateTotals();

        // Clear session cart
        Session::forget('cart');
    }

    /**
     * Find or create cart for current user/session
     */
    public static function getCurrentCart()
    {
        if (!auth()->check()) {
            return null;
        }

        $cart = self::where('user_id', auth()->id())->first();
        
        if (!$cart) {
            $cart = self::create([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'currency_code' => 'KWD',
                'is_guest' => false
            ]);
        }
        
        return $cart;
    }

    /**
     * Merge guest cart into user cart
     */
    public function mergeGuestCart($guestCart)
    {
        foreach ($guestCart->items as $item) {
            $existingItem = $this->items()
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->where('options', $item->options)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item->quantity
                ]);
            } else {
                $this->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'options' => $item->options
                ]);
            }
        }
        
        $this->calculateTotals();
    }

    /**
     * Get cart count for display
     */
    public static function getCartCount()
    {
        return self::totalQuantity();
    }
}