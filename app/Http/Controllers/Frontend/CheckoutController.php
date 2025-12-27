<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\Cart;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::getCart();
        
        if (empty($cart->items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $items = $cart->items;
        $total = $cart->total;
        $subtotal = $cart->subtotal;
        $tax = $cart->tax;
        $shipping = $cart->shipping;

        // Get all vendors from cart items
        $vendorCurrencies = [];
        $vendorSymbols = [];
        
        foreach ($items as $item) {
            if ($item['product']->vendor) {
                $vendor = $item['product']->vendor;
                $vendorCurrencies[$vendor->id] = $vendor->currency_code ?? 'KWD';
            }
        }
        
        // If all items are from the same vendor, use that vendor's currency
        // Otherwise, use KWD as default
        $baseCurrency = count(array_unique($vendorCurrencies)) === 1 
            ? reset($vendorCurrencies) 
            : 'KWD';
            
        // Get currency symbol for display
        $currencySymbol = $this->getCurrencySymbol($baseCurrency);
        
        // Format prices with correct decimals
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;

        // Get addresses if user is logged in
        $addresses = Auth::check() && Auth::user()->customer ? Auth::user()->customer->addresses : collect();

        return view('frontend.checkout.index', compact(
            'items', 'total', 'subtotal', 'tax', 'shipping', 
            'addresses', 'baseCurrency', 'currencySymbol', 'decimals'
        ));
    }

    // Simple currency symbol helper
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
    
    // Simple currency formatting
    private function formatCurrency($amount, $currencyCode)
    {
        $decimals = $currencyCode === 'KWD' ? 3 : 2;
        return number_format($amount, $decimals);
    }

    public function directPurchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        // Clear existing cart
        Cart::clear();

        // Add single product to cart
        $product = Product::findOrFail($request->product_id);
        
        // Check stock
        if ($product->track_stock && $product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available');
        }

        Cart::addItem(
            $product->id,
            $request->quantity,
            $request->variant_id,
            $request->options ?? []
        );

        return redirect()->route('frontend.checkout.index');
    }

    public function process(Request $request)
    {
        // Validate all required fields
        $validated = $request->validate([
            // Customer information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            
            // Account creation - handle guests differently
            'create_account' => 'nullable|boolean',
            
            // Shipping address
            'shipping_address_line1' => 'required|string|max:255',
            'shipping_address_line2' => 'nullable|string|max:255',
            
            // Billing address
            'same_as_shipping' => 'nullable|boolean',
            'billing_address_line1' => 'nullable|string|max:255',
            'billing_address_line2' => 'nullable|string|max:255',
            
            // Shipping method
            'shipping_method' => 'required|in:standard,express,pickup',
            
            // Payment method
            'payment_method' => 'required|in:cod,card,wallet',
            
            // Order notes
            'notes' => 'nullable|string',
            
            // Terms agreement
            'terms' => 'accepted',
        ]);

        DB::beginTransaction();

        try {
            $cart = Cart::getCart();
            
            if (empty($cart->items)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Determine base currency from vendors in cart
            $baseCurrency = 'KWD';
            $vendorIds = [];
            $vendorCurrencies = [];
            
            foreach ($cart->items as $item) {
                if ($item['product']->vendor) {
                    $vendor = $item['product']->vendor;
                    $vendorIds[$vendor->id] = $vendor;
                    $vendorCurrencies[$vendor->id] = $vendor->currency_code ?? 'KWD';
                    
                    // If all items are from same vendor with same currency, use it
                    if (count(array_unique($vendorCurrencies)) === 1) {
                        $baseCurrency = reset($vendorCurrencies);
                    }
                }
            }

            // Find or create customer
            $customer = $this->findOrCreateCustomer($validated);

            // Create user account if requested
            if ($validated['create_account'] ?? false) {
                $this->createUserAccount($validated, $customer);
            } elseif (Auth::check()) {
                // If user is logged in but customer wasn't associated, associate them
                if (!$customer->user_id && Auth::check()) {
                    $customer->update(['user_id' => Auth::id()]);
                }
            }

            // Create order
            $order = $this->createOrder($validated, $cart, $customer, $baseCurrency, $vendorIds);

            // Add order items
            $this->addOrderItems($order, $cart->items, $baseCurrency);

            // Create addresses
            $this->createOrderAddresses($order, $validated);

            // Update stock
            $this->updateStock($cart->items);

            // Clear cart
            Cart::clear();

            DB::commit();

            // Store order number in session for guest access
            if (!Auth::check()) {
                session()->put('guest_order_' . $order->order_number, true);
            }

            // Send confirmation email
            $this->sendOrderConfirmation($order, $customer);

            // Redirect to success page
            return redirect()->route('frontend.checkout.success', $order->order_number)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout process failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    private function findOrCreateCustomer(array $data): Customer
    {
        // Check if customer exists by email
        $customer = Customer::where('email', $data['email'])->first();

        if (!$customer) {
            // Create new customer
            $customer = Customer::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'is_guest' => !Auth::check(), // Guest if not logged in
                'status' => 'active',
            ]);

            // If user is logged in, associate with user account
            if (Auth::check()) {
                $customer->update(['user_id' => Auth::id()]);
            }
        } else {
            // Update existing customer info
            $customer->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
            ]);
        }

        return $customer;
    }

    private function createUserAccount(array $data, Customer $customer): void
    {
        // Check if user already exists with this email
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            // Generate a random password if not provided
            $password = Str::random(12);
            $name = $data['first_name'] . ' ' . $data['last_name'];
            
            /** -------------------------------------------------
             * CREATE USER
             * -------------------------------------------------*/
            $user = User::create([
                'name' => ucfirst($name),
                'email' => $data['email'],
                'password' => Hash::make($password),
                'user_password' => $password, // Store plain password for reference
                'email_verified_at' => now(),
            ]);

            /** -------------------------------------------------
             * ASSIGN CUSTOMER ROLE
             * -------------------------------------------------*/
            $user->assignRole('customer');

            /** -------------------------------------------------
             * UPDATE CUSTOMER RECORD
             * -------------------------------------------------*/
            $customer->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'is_guest' => false,
                'user_id' => $user->id,
            ]);

            /** -------------------------------------------------
             * MIGRATE SESSION CART TO DATABASE
             * -------------------------------------------------*/
            Cart::migrateSessionToDatabase();

            /** -------------------------------------------------
             * EMAIL CREDENTIALS
             * -------------------------------------------------*/
            $this->sendAccountCredentials($user, $password);
        } else {
            // User already exists, just update customer association
            $customer->update([
                'user_id' => $user->id,
                'is_guest' => false,
            ]);
        }

        /** -------------------------------------------------
         * AUTO-LOGIN
         * -------------------------------------------------*/
        if (!Auth::check()) {
            Auth::login($user);
        }
    }

    /**
     * Send account credentials email
     */
    private function sendAccountCredentials(User $user, string $password): void
    {
        try {
            // You can use Laravel Mail or your preferred email method
            \Illuminate\Support\Facades\Mail::send('emails.account-credentials', [
                'email' => $user->email,
                'password' => $password,
                'name' => $user->name,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Account Credentials');
            });
            
            // Or log it for now if email is not configured
            \Log::info("Account credentials email should be sent to: {$user->email}");
            \Log::info("Temporary password: {$password}");
            
        } catch (\Exception $e) {
            \Log::error('Failed to send account credentials email: ' . $e->getMessage());
        }
    }

    private function createOrder(array $data, $cart, Customer $customer, string $baseCurrency, array $vendorIds): Order
    {
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));

        // Determine if this is a multi-vendor order
        $vendorCount = count($vendorIds);
        $isMultiVendor = $vendorCount > 1;

        // Calculate totals
        $grandTotal = 0;
        foreach ($cart->items as $item) {
            $grandTotal += $item['quantity'] * $item['product']->price;
        }

        // Add shipping and tax
        $shippingCost = $data['shipping_method'] === 'express' ? 
            ($baseCurrency === 'KWD' ? 5.000 : 5.00) : 
            ($data['shipping_method'] === 'pickup' ? 0 : 
            ($baseCurrency === 'KWD' ? 2.000 : 2.00));
            
        $taxRate = 0.05; // 5%
        $taxAmount = $grandTotal * $taxRate;
        $finalTotal = $grandTotal + $shippingCost + $taxAmount;

        // Store currency information
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;

        // Create the order
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'subtotal' => round($grandTotal, $decimals),
            'discount_total' => 0,
            'tax_total' => round($taxAmount, $decimals),
            'shipping_total' => round($shippingCost, $decimals),
            'grand_total' => round($finalTotal, $decimals),
            'payment_method' => $data['payment_method'],
            'shipping_method' => $data['shipping_method'],
            'notes' => $data['notes'] ?? null,
            'source' => 'online',
            'vendor_id' => $vendorCount === 1 ? reset($vendorIds)->id : null,
            'currency_code' => $baseCurrency, // Add currency code to order
            'currency_symbol' => $this->getCurrencySymbol($baseCurrency), // Add symbol to order
            'created_by' => Auth::id(),
        ]);

        return $order;
    }

    private function addOrderItems(Order $order, array $items, string $baseCurrency): void
    {
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;
        
        foreach ($items as $item) {
            // Get translated product title
            $productTitle = $item['product']->translate('title') ?? $item['product']->title;
            
            // Get vendor currency for this item
            $itemCurrency = $item['product']->vendor->currency_code ?? 'KWD';
            $itemSymbol = $this->getCurrencySymbol($itemCurrency);
            
            $subtotal = $item['quantity'] * $item['product']->price;
            $taxAmount = $subtotal * 0.05;
            $total = $subtotal + $taxAmount;
            
            $order->items()->create([
                'product_id'         => $item['product']->id,
                'product_variant_id' => $item['variant_id'] ?? null,
                'sku'                => $item['product']->sku ?? null,
                'title'              => $productTitle,
                'price'              => round($item['product']->price, $decimals),
                'quantity'           => $item['quantity'],
                'subtotal'           => round($subtotal, $decimals),
                'tax'                => round($taxAmount, $decimals),
                'total'              => round($total, $decimals),
                'currency_code'      => $itemCurrency,
                'currency_symbol'    => $itemSymbol,
            ]);
        }
    }

    private function createOrderAddresses(Order $order, array $data): void
    {
        // Create shipping address - FIXED: Your Address model doesn't have all these fields
        $order->addresses()->create([
            'type' => 'shipping',
            'address_line_1' => $data['shipping_address_line1'],
            'address_line_2' => $data['shipping_address_line2'] ?? null,
            'is_default' => true,
            // Add additional fields if your Address model supports them
            // 'city' => $data['shipping_city'],
            // 'state' => $data['shipping_state'],
            // 'postal_code' => $data['shipping_postal_code'],
            // 'country' => $data['shipping_country'],
            // 'phone' => $data['phone'],
            // 'email' => $data['email'],
        ]);

        // Create billing address
        if ($data['same_as_shipping'] ?? false) {
            $order->addresses()->create([
                'type' => 'billing',
                'address_line_1' => $data['shipping_address_line1'],
                'address_line_2' => $data['shipping_address_line2'] ?? null,
                'is_default' => true,
                'same_as_shipping' => true,
            ]);
        } else {
            $order->addresses()->create([
                'type' => 'billing',
                'address_line_1' => $data['billing_address_line1'],
                'address_line_2' => $data['billing_address_line2'] ?? null,
                'is_default' => true,
                'same_as_shipping' => false,
            ]);
        }

        // Also update customer's default addresses if customer exists
        $customer = $order->customer;
        if ($customer) {
            $this->updateCustomerAddresses($customer, $data);
        }
    }

    private function updateCustomerAddresses(Customer $customer, array $data): void
    {
        // Update or create shipping address
        $customer->addresses()->updateOrCreate(
            [
                'type' => 'shipping',
                'is_default' => true,
            ],
            [
                'address_line_1' => $data['shipping_address_line1'],
                'address_line_2' => $data['shipping_address_line2'] ?? null,
            ]
        );

        // Update or create billing address
        if ($data['same_as_shipping'] ?? false) {
            $customer->addresses()->updateOrCreate(
                [
                    'type' => 'billing',
                    'is_default' => true,
                ],
                [
                    'address_line_1' => $data['shipping_address_line1'],
                    'address_line_2' => $data['shipping_address_line2'] ?? null,
                    'same_as_shipping' => true,
                ]
            );
        } else {
            $customer->addresses()->updateOrCreate(
                [
                    'type' => 'billing',
                    'is_default' => true,
                ],
                [
                    'address_line_1' => $data['billing_address_line1'],
                    'address_line_2' => $data['billing_address_line2'] ?? null,
                    'same_as_shipping' => false,
                ]
            );
        }
    }

    private function updateStock(array $items): void
    {
        foreach ($items as $item) {
            if ($item['product']->track_stock) {
                $item['product']->decrement('stock_quantity', $item['quantity']);
            }
        }
    }

    private function sendOrderConfirmation(Order $order, Customer $customer): void
    {
        // TODO: Implement email sending
        \Log::info('Order confirmation email should be sent for order: ' . $order->order_number);
    }

    public function success($orderNumber)
    {
        $order = Order::with(['items.product', 'customer', 'addresses'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
        
        // Verify this order belongs to current user
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // If guest, verify via session
        if (!Auth::check() && !session()->has('guest_order_' . $orderNumber)) {
            return redirect()->route('home');
        }

        return view('frontend.checkout.success', compact('order'));
    }
}