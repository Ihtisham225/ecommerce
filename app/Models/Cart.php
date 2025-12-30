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

        $cart->calculateTotals();

        return (object) [
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id, // Add this for consistency
                    'item_id' => $item->id, // For JavaScript compatibility
                    'product_id' => $item->product_id,
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'variant_id' => $item->variant_id,
                    'options' => json_decode($item->options, true) ?? [],
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ];
            })->values()->toArray(), // Use values() to reset keys
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax_total,
            'shipping' => $cart->shipping_total,
            'discount' => $cart->discount_total,
            'total' => $cart->grand_total,
            'source' => 'database',
            'cart_model' => $cart
        ];
    }

    /**
     * Get cart for guests (session-based)
     */
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
            
            $cart->items()->create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $product->price,
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
                'id' => $itemKey, // Use same key for consistency
                'item_id' => $itemKey,
                'product_id' => $productId,
                'product' => $product,
                'quantity' => $quantity,
                'variant_id' => $variantId,
                'options' => $options,
                'price' => $product->price,
                'total' => $product->price * $quantity
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
        $this->subtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $this->tax_total = $this->subtotal * 0.00;
        $this->shipping_total = 0.000;
        $this->discount_total = 0;
        $this->grand_total = $this->subtotal + $this->tax_total + $this->shipping_total - $this->discount_total;
        
        $this->save();
    }

    /**
     * Calculate totals for session cart
     */
    private static function calculateSessionTotals(&$cart)
    {
        $cart->subtotal = collect($cart->items)->sum('total');
        $cart->tax = $cart->subtotal * 0.00;
        $cart->shipping = 0.000;
        $cart->discount = 0;
        $cart->total = $cart->subtotal + $cart->tax + $cart->shipping - $cart->discount;
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
        
        $items = [];
        
        if ($cart->source === 'database') {
            // Get the cart model and load relationships
            $cartModel = $cart->cart_model ?? null;
            
            if ($cartModel && $cartModel->items->count() > 0) {
                $items = $cartModel->items->map(function ($item) {
                    // Load relationships
                    $item->load('product.mainImage', 'product.brand', 'variant.options');
                    
                    return (object) [
                        'id' => $item->id,
                        'item_id' => $item->id,
                        'product_id' => $item->product_id,
                        'product' => $item->product,
                        'variant' => $item->variant,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->price * $item->quantity,
                        'options' => $item->options ?? []
                    ];
                })->toArray();
                
                // Convert arrays to objects
                $items = array_map(function($item) {
                    return (object) $item;
                }, $items);
            }
        } else {
            // For session cart
            if (!empty($cart->items)) {
                foreach ($cart->items as $key => $itemData) {
                    if (isset($itemData['product'])) {
                        $itemData['product']->load('mainImage', 'brand');
                        
                        // If you have variant data in session, load it
                        if (isset($itemData['variant_id']) && $itemData['variant_id']) {
                            $variant = \App\Models\ProductVariant::with('options')->find($itemData['variant_id']);
                            $itemData['variant'] = $variant;
                        }
                        
                        $items[] = (object) [
                            'id' => $itemData['id'] ?? $key,
                            'item_id' => $itemData['item_id'] ?? $key,
                            'product_id' => $itemData['product_id'],
                            'product' => $itemData['product'],
                            'variant' => $itemData['variant'] ?? null,
                            'quantity' => $itemData['quantity'],
                            'price' => $itemData['price'],
                            'total' => $itemData['total'],
                            'options' => $itemData['options'] ?? []
                        ];
                    }
                }
            }
        }

        return [
            'items' => $items,
            'subtotal' => $cart->subtotal ?? 0,
            'tax' => $cart->tax ?? 0,
            'shipping' => $cart->shipping ?? 0,
            'discount' => $cart->discount ?? 0,
            'total' => $cart->total ?? 0,
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
            ksort($options); // Sort to ensure consistent keys
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