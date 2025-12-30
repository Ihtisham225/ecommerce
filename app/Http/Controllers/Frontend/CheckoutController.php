<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\Cart;
use App\Models\StoreSetting;
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

        $storeSetting = StoreSetting::first();

        $shippingMethods = $storeSetting?->shipping_methods ?? [];
        $paymentMethods  = $storeSetting?->payment_methods ?? [];

        // Defaults
        $defaultShippingMethod = collect($shippingMethods)
            ->firstWhere('code', 'free') ?? null;

        $defaultPaymentMethod = collect($paymentMethods)
            ->firstWhere('code', 'cod') ?? null;

        $baseCurrency = $storeSetting?->currency_code ?? 'KWD';
        $currencySymbol = $this->getCurrencySymbol($baseCurrency);
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;

        return view('frontend.checkout.index', [
            'items' => $cart->items,
            'total' => $cart->total,
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'shipping' => $cart->shipping,
            'shippingMethods' => $shippingMethods,
            'paymentMethods' => $paymentMethods,
            'defaultShippingMethod' => $defaultShippingMethod,
            'defaultPaymentMethod' => $defaultPaymentMethod,
            'baseCurrency' => $baseCurrency,
            'currencySymbol' => $currencySymbol,
            'decimals' => $decimals,
        ]);
    }


    public function getCurrencySymbol(string $currencyCode): string
    {
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'PKR' => '₨',
            'INR' => '₹',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'CAD' => '$',
            'AUD' => '$',
            'KWD' => 'K.D',
        ];

        return $currencySymbols[$currencyCode] ?? $currencyCode;
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
        $storeSetting = StoreSetting::first();

        $shippingCodes = collect($storeSetting?->shipping_methods ?? [])
            ->pluck('code')
            ->implode(',');

        $paymentCodes = collect($storeSetting?->payment_methods ?? [])
            ->pluck('code')
            ->implode(',');
            
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
            
            'shipping_method' => 'required|in:' . $shippingCodes,
            'payment_method'  => 'required|in:' . $paymentCodes,
            
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

            // Get store setting
            $storeSetting = StoreSetting::first();
            $currencyCode = $storeSetting?->currency_code ?? 'KWD';

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
            $order = $this->createOrder($validated, $cart, $customer, $currencyCode);

            // Add order items
            $this->addOrderItems($order, $cart->items, $currencyCode);

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

    private function createOrder(array $data, $cart, Customer $customer, string $baseCurrency): Order
    {
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));

        // Calculate totals
        $grandTotal = 0;
        foreach ($cart->items as $item) {
            $grandTotal += $item['quantity'] * $item['product']->price;
        }

        $shippingMethods = $storeSetting?->shipping_methods ?? [];

        $selectedShipping = collect($shippingMethods)
            ->firstWhere('code', $data['shipping_method']);
            
        // Add shipping and tax
        $shippingCost = $selectedShipping['price'] ?? 0;
            
        $taxRate = 0.00; // 5%
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
            $storeSetting = StoreSetting::first();
            $itemCurrency = $storeSetting?->currency_code ?? 'KWD';
            $itemSymbol = $this->getCurrencySymbol($itemCurrency);
            
            $subtotal = $item['quantity'] * $item['product']->price;
            $taxAmount = $subtotal * 0.00; // 5% tax
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