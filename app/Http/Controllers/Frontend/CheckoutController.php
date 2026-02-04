<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\Cart;
use App\Models\StoreSetting;
use App\Traits\EmailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    use EmailHelper;

    public function index()
    {
        $cart = Cart::getCart();
        
        \Log::info('Checkout index - Cart state:', [
            'items_count' => count($cart->items ?? []),
            'subtotal' => $cart->subtotal ?? 0,
            'total' => $cart->total ?? 0,
            'source' => $cart->source ?? 'unknown',
            'user_id' => auth()->id(),
            'session_id' => session()->getId()
        ]);

        if (empty($cart->items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Get cart summary for display
        $summary = Cart::getCartSummary();
        $items = $summary['items'];
        $total = $summary['total'];
        $subtotal = $summary['subtotal'];
        $tax = $summary['tax'];
        $shipping = $summary['shipping'];

        \Log::info('Checkout summary:', [
            'summary_items_count' => count($items),
            'summary_subtotal' => $subtotal,
            'summary_total' => $total
        ]);

        // Get store settings for checkout
        $storeSettings = $this->getStoreCheckoutSettings();

        // Get base currency from store settings
        $baseCurrency = $storeSettings['base_currency'] ?? 'KWD';
        $currencySymbol = $this->getCurrencySymbol($baseCurrency);
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;

        // Get addresses if user is logged in
        $addresses = Auth::check() && Auth::user()->customer ? Auth::user()->customer->addresses : collect();

        return view('frontend.checkout.index', array_merge(compact(
            'items', 'total', 'subtotal', 'tax', 'shipping', 
            'addresses', 'baseCurrency', 'currencySymbol', 'decimals'
        ), $storeSettings));
    }

    /**
     * Get store settings for checkout
     */
    private function getStoreCheckoutSettings(): array
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
                ],
                'payment_methods' => [
                    ['name' => 'cash_on_delivery', 'is_active' => true],
                    ['name' => 'credit_card', 'is_active' => true],
                    ['name' => 'bank_transfer', 'is_active' => false]
                ],
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
        
        // Get payment methods
        $paymentMethods = $settings['payment_methods'] ?? [
            ['name' => 'cash_on_delivery', 'is_active' => true],
            ['name' => 'credit_card', 'is_active' => true],
            ['name' => 'bank_transfer', 'is_active' => false]
        ];
        
        // Filter active payment methods
        $activePaymentMethods = collect($paymentMethods)
            ->where('is_active', true)
            ->map(function($method) {
                return [
                    'code' => $method['name'],
                    'name' => $this->formatPaymentMethodName($method['name']),
                    'description' => $this->getPaymentMethodDescription($method['name'])
                ];
            })
            ->toArray();
        
        // Get tax settings
        $taxSettings = $settings['tax_settings'] ?? [
            'tax_enabled' => false,
            'tax_rate' => 0,
            'tax_inclusive' => false
        ];
        
        // Get bank details if bank transfer is enabled
        $bankDetails = [];
        $bankTransferEnabled = collect($paymentMethods)
            ->where('name', 'bank_transfer')
            ->where('is_active', true)
            ->isNotEmpty();
        
        if ($bankTransferEnabled) {
            $bankDetails = $settings['bank_details'] ?? [];
        }
        
        return [
            'base_currency' => $storeSettings->currency_code ?? 'KWD',
            'store_name' => $storeSettings->store_name ?? 'Store',
            'shipping_methods' => $shippingMethods,
            'payment_methods' => $activePaymentMethods,
            'tax_settings' => $taxSettings,
            'bank_details' => $bankDetails,
            'bank_transfer_enabled' => $bankTransferEnabled
        ];
    }
    
    /**
     * Format payment method name for display
     */
    private function formatPaymentMethodName(?string $methodCode): string
    {
        if (empty($methodCode)) {
            return 'Unknown';
        }
        
        $names = [
            'cash_on_delivery' => 'Cash on Delivery',
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'digital_wallet' => 'Digital Wallet',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe'
        ];
        
        return $names[$methodCode] ?? ucwords(str_replace('_', ' ', $methodCode));
    }
    
    /**
     * Get payment method description
     */
    private function getPaymentMethodDescription(string $methodCode): string
    {
        $descriptions = [
            'cash_on_delivery' => 'Pay when you receive',
            'credit_card' => 'Secure credit card payment',
            'bank_transfer' => 'Direct bank transfer',
            'digital_wallet' => 'Fast & secure digital payment',
            'paypal' => 'Pay with PayPal',
            'stripe' => 'Secure online payment'
        ];
        
        return $descriptions[$methodCode] ?? 'Secure payment method';
    }

    /**
     * Get currency symbol
     */
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

    public function process(Request $request)
    {
        \Log::info('Checkout process started:', [
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'request_data' => $request->except(['_token'])
        ]);

        // Get cart and validate it's not empty
        $cart = Cart::getCart();
        if (empty($cart->items)) {
            \Log::error('Checkout failed: Cart is empty');
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        \Log::info('Cart before checkout:', [
            'cart_items_count' => count($cart->items),
            'cart_subtotal' => $cart->subtotal,
            'cart_total' => $cart->total,
            'cart_source' => $cart->source
        ]);
        
        $storeSettings = $this->getStoreCheckoutSettings();
        
        // Get available shipping method names
        $availableShippingMethods = collect($storeSettings['shipping_methods'])
            ->where('is_active', true)
            ->pluck('name')
            ->map(function($name) {
                return strtolower(str_replace(' ', '_', $name));
            })
            ->toArray();
            
        // Get available payment method codes
        $availablePaymentMethods = collect($storeSettings['payment_methods'])
            ->pluck('code')
            ->toArray();
        
        // Custom validation for address
        $addressValidator = Validator::make($request->all(), [
            // Customer information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            
            // Account creation - handle guests differently
            'create_account' => 'nullable|boolean',
            
            // For logged-in users only
            'selected_address_id' => [
                'nullable',
                'exists:addresses,id',
                function ($attribute, $value, $fail) {
                    if (Auth::check() && $value) {
                        $customerId = Auth::user()->customer?->id;
                        if ($customerId) {
                            $address = Address::where('id', $value)
                                ->where('customer_id', $customerId)
                                ->first();
                            if (!$address) {
                                $fail('The selected address does not belong to you.');
                            }
                        }
                    }
                }
            ],
            
            // Hidden fields for address
            'shipping_address_line1' => 'nullable|string|max:255',
            'shipping_address_line2' => 'nullable|string|max:255',
            
            // Custom address fields
            'custom_shipping_address_line1' => 'nullable|string|max:255',
            'custom_shipping_address_line2' => 'nullable|string|max:255',
            
            // Billing address
            'same_as_shipping' => 'nullable|boolean',
            'billing_address_line1' => 'nullable|string|max:255',
            'billing_address_line2' => 'nullable|string|max:255',
            
            // Shipping method
            'shipping_method' => [
                'required',
                function ($attribute, $value, $fail) use ($availableShippingMethods) {
                    if (!in_array($value, $availableShippingMethods)) {
                        $fail('The selected shipping method is not available.');
                    }
                },
            ],
            
            // Payment method
            'payment_method' => [
                'required',
                function ($attribute, $value, $fail) use ($availablePaymentMethods) {
                    if (!in_array($value, $availablePaymentMethods)) {
                        $fail('The selected payment method is not available.');
                    }
                },
            ],
            
            // Order notes
            'notes' => 'nullable|string',
            
            // Terms agreement
            'terms' => 'accepted',
        ]);
        
        // Custom validation logic for addresses
        $addressValidator->after(function ($validator) use ($request) {
            $isGuest = !Auth::check();
            $hasSelectedAddress = $request->filled('selected_address_id');
            $hasHiddenAddress = $request->filled('shipping_address_line1') && 
                               trim($request->shipping_address_line1) !== '';
            $hasCustomAddress = $request->filled('custom_shipping_address_line1') && 
                               trim($request->custom_shipping_address_line1) !== '';
            
            if ($isGuest) {
                // Guests must provide address
                if (!$hasHiddenAddress && !$hasCustomAddress) {
                    $validator->errors()->add(
                        'custom_shipping_address_line1', 
                        'Please enter your shipping address.'
                    );
                }
            } else {
                // Logged-in users must either select saved address OR provide address
                if (!$hasSelectedAddress && !$hasHiddenAddress && !$hasCustomAddress) {
                    $validator->errors()->add(
                        'custom_shipping_address_line1', 
                        'Please select a saved address or enter a shipping address.'
                    );
                }
            }
        });
        
        if ($addressValidator->fails()) {
            \Log::error('Checkout validation failed:', $addressValidator->errors()->toArray());
            return redirect()->back()
                ->withErrors($addressValidator)
                ->withInput();
        }
        
        $validated = $addressValidator->validated();

        DB::beginTransaction();

        try {
            // Get cart again to ensure we have fresh data
            $cart = Cart::getCart();
            
            if (empty($cart->items)) {
                throw new \Exception('Cart is empty');
            }

            \Log::info('Processing order with cart:', [
                'items_count' => count($cart->items),
                'cart_subtotal' => $cart->subtotal,
                'cart_total' => $cart->total
            ]);

            // Use store currency
            $baseCurrency = $storeSettings['base_currency'];
            
            // Find or create customer
            $customer = $this->findOrCreateCustomer($validated);

            // Create user account if requested
            if ($validated['create_account'] ?? false) {
                $this->createUserAccount($validated, $customer);
            } elseif (Auth::check()) {
                if (!$customer->user_id) {
                    $customer->update(['user_id' => Auth::id()]);
                }
            }

            // Get selected shipping method cost
            $selectedShippingName = str_replace('_', ' ', ucwords($validated['shipping_method']));
            $shippingCost = 0;
            
            foreach ($storeSettings['shipping_methods'] as $method) {
                if (strtolower($method['name']) === strtolower($selectedShippingName)) {
                    $shippingCost = $method['cost'];
                    break;
                }
            }

            // Get shipping address
            $shippingAddressLine1 = null;
            $shippingAddressLine2 = null;
            
            if ($request->filled('selected_address_id')) {
                // Use saved address
                $address = Address::find($request->selected_address_id);
                if ($address) {
                    $shippingAddressLine1 = $address->address_line_1;
                    $shippingAddressLine2 = $address->address_line_2;
                }
            } elseif ($request->filled('shipping_address_line1')) {
                // Use hidden fields
                $shippingAddressLine1 = $request->shipping_address_line1;
                $shippingAddressLine2 = $request->shipping_address_line2;
            } elseif ($request->filled('custom_shipping_address_line1')) {
                // Use custom fields
                $shippingAddressLine1 = $request->custom_shipping_address_line1;
                $shippingAddressLine2 = $request->custom_shipping_address_line2;
            }

            // Validate that we have a shipping address
            if (empty($shippingAddressLine1)) {
                throw new \Exception('Shipping address is required');
            }

            // Prepare order data
            $orderData = array_merge($validated, [
                'shipping_address_line1' => $shippingAddressLine1,
                'shipping_address_line2' => $shippingAddressLine2,
            ]);

            // Create order - FIXED: Use cart data properly
            $order = $this->createOrder(
                $orderData, 
                $cart, 
                $customer, 
                $baseCurrency, 
                $storeSettings, 
                $shippingCost
            );

            // Add order items
            $this->addOrderItems($order, $cart->items, $baseCurrency, $storeSettings);

            // Create addresses
            $this->createOrderAddresses($order, $orderData);

            // Update stock for products
            $this->updateStock($cart->items);

            // Clear cart
            Cart::clear();

            DB::commit();

            \Log::info('Order created successfully:', [
                'order_number' => $order->order_number,
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'total' => $order->grand_total
            ]);

            // Store order number in session for guest access
            if (!Auth::check()) {
                session()->put('guest_order_' . $order->order_number, true);
            }

            // Send order confirmation email
            $this->sendOrderConfirmation($order, $customer);

            // Redirect to success page - FIXED ROUTE
            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Order placed successfully! Your order is being processed.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout process failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    /**
     * Create order
     */
    private function createOrder(
        array $data, 
        $cart, 
        Customer $customer, 
        string $baseCurrency, 
        array $storeSettings, 
        float $shippingCost
        ): Order {
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));

        // Use the cart's subtotal - FIX: Convert to float
        $subtotal = (float) $cart->subtotal;
        
        // If subtotal is 0, calculate manually from cart items
        if ($subtotal == 0) {
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $price = is_array($item) ? (float) ($item['price'] ?? 0) : (float) ($item->price ?? 0);
                $quantity = is_array($item) ? (int) ($item['quantity'] ?? 1) : (int) ($item->quantity ?? 1);
                $subtotal += $price * $quantity;
            }
        }

        // Apply tax based on store settings
        $taxSettings = $storeSettings['tax_settings'];
        $taxRate = $taxSettings['tax_enabled'] ? ((float) $taxSettings['tax_rate'] / 100) : 0;
        
        if ($taxSettings['tax_inclusive']) {
            // Tax is already included in price
            $taxAmount = $subtotal * $taxRate / (1 + $taxRate);
            $subtotalBeforeTax = $subtotal - $taxAmount;
        } else {
            // Tax is added on top
            $subtotalBeforeTax = $subtotal;
            $taxAmount = $subtotal * $taxRate;
        }
        
        $grandTotal = $subtotalBeforeTax + $shippingCost + $taxAmount;

        // Store currency information
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;

        // Determine order status
        $orderStatus = 'pending';
        $paymentStatus = 'pending';

        // Create the order - FIX: Ensure all values are properly cast
        $order = new Order([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'shipping_status' => 'pending',
            'subtotal' => round((float) $subtotalBeforeTax, $decimals),
            'discount_total' => 0,
            'tax_total' => round((float) $taxAmount, $decimals),
            'shipping_total' => round((float) $shippingCost, $decimals),
            'grand_total' => round((float) $grandTotal, $decimals),
            'payment_method' => $data['payment_method'],
            'shipping_method' => $data['shipping_method'],
            'notes' => $data['notes'] ?? null,
            'source' => 'online',
            'currency_code' => $baseCurrency,
            'currency_symbol' => $this->getCurrencySymbol($baseCurrency),
            'created_by' => Auth::id(),
            'tax_rate' => (float) $taxSettings['tax_rate'],
            'tax_inclusive' => $taxSettings['tax_inclusive'] ? 1 : 0,
        ]);
        
        $order->save();

        return $order;
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
                'is_guest' => !Auth::check(),
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
            // Generate a random password
            $password = Str::random(12);
            $name = $data['first_name'] . ' ' . $data['last_name'];
            
            $user = User::create([
                'name' => ucfirst($name),
                'email' => $data['email'],
                'password' => Hash::make($password),
                'user_password' => $password,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('customer');

            $customer->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'is_guest' => false,
                'user_id' => $user->id,
            ]);

            Cart::migrateSessionToDatabase();

            $this->sendAccountCredentials($user, $password);
        } else {
            $customer->update([
                'user_id' => $user->id,
                'is_guest' => false,
            ]);
        }

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
            \Illuminate\Support\Facades\Mail::send('emails.account-credentials', [
                'email' => $user->email,
                'password' => $password,
                'name' => $user->name,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Account Credentials');
            });
            
            \Log::info("Account credentials email sent to: {$user->email}");
        } catch (\Exception $e) {
            \Log::error('Failed to send account credentials email: ' . $e->getMessage());
        }
    }

    private function addOrderItems(Order $order, $items, string $baseCurrency, array $storeSettings): void
    {
        $decimals = $baseCurrency === 'KWD' ? 3 : 2;
        $taxSettings = $storeSettings['tax_settings'];
        $taxRate = $taxSettings['tax_enabled'] ? ($taxSettings['tax_rate'] / 100) : 0;
        
        $totalItems = 0;
        
        foreach ($items as $item) {
            // Handle both array and object access
            $product = is_array($item) ? ($item['product'] ?? null) : ($item->product ?? null);
            $variant = is_array($item) ? ($item['variant'] ?? null) : ($item->variant ?? null);
            
            // Get the correct price - prioritize variant price
            $price = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
            
            // If we have variant data, use variant price
            if ($variant) {
                $variantPrice = is_object($variant) ? $variant->price : ($variant['price'] ?? 0);
                if ($variantPrice > 0) {
                    $price = $variantPrice;
                }
            }
            
            $quantity = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
            
            if (!$product) {
                \Log::error('Product not found in cart item:', ['item' => $item]);
                continue;
            }
            
            // Get variant title if available
            $variantTitle = null;
            if ($variant) {
                $variantTitle = is_object($variant) ? $variant->title : ($variant['title'] ?? null);
            }
            
            // Get product title with variant if applicable
            $productTitle = is_object($product) ? 
                ($product->translate('title') ?? $product->title) : 
                'Unknown Product';
                
            if ($variantTitle) {
                $productTitle .= ' - ' . $variantTitle;
            }
            
            // Calculate item totals
            $itemSubtotal = $price * $quantity;
            
            if ($taxSettings['tax_inclusive']) {
                // Tax is already included in price
                $taxAmount = $itemSubtotal * $taxRate / (1 + $taxRate);
                $subtotalBeforeTax = $itemSubtotal - $taxAmount;
            } else {
                // Tax is added on top
                $subtotalBeforeTax = $itemSubtotal;
                $taxAmount = $itemSubtotal * $taxRate;
            }
            
            $total = $subtotalBeforeTax + $taxAmount;
            
            $orderItem = new OrderItem([
                'product_id'         => is_object($product) ? $product->id : ($item['product_id'] ?? null),
                'product_variant_id' => is_array($item) ? ($item['variant_id'] ?? null) : ($item->variant_id ?? null),
                'sku'                => is_object($product) ? ($product->sku ?? null) : null,
                'title'              => $productTitle,
                'price'              => round($price, $decimals),
                'quantity'           => $quantity,
                'subtotal'           => round($subtotalBeforeTax, $decimals),
                'tax'                => round($taxAmount, $decimals),
                'total'              => round($total, $decimals),
                'currency_code'      => $baseCurrency,
                'currency_symbol'    => $this->getCurrencySymbol($baseCurrency),
                'tax_rate'           => $taxSettings['tax_rate'],
                'order_id'           => $order->id,
            ]);
            
            $orderItem->save();
            $totalItems++;
            
            \Log::info('Order item added:', [
                'order_id' => $order->id,
                'product_id' => is_object($product) ? $product->id : 'N/A',
                'variant_id' => is_array($item) ? ($item['variant_id'] ?? null) : ($item->variant_id ?? null),
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotalBeforeTax
            ]);
        }
        
        \Log::info("Total items added to order: {$totalItems}");
    }

    private function createOrderAddresses(Order $order, array $data): void
    {
        // Create shipping address
        $shippingAddress = new Address([
            'type' => 'shipping',
            'address_line_1' => $data['shipping_address_line1'],
            'address_line_2' => $data['shipping_address_line2'] ?? null,
            'is_default' => true,
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'addressable_type' => 'App\Models\Order', // Add this for polymorphic relation
            'addressable_id' => $order->id, // Add this for polymorphic relation
        ]);
        $shippingAddress->save();

        // Create billing address
        if ($data['same_as_shipping'] ?? false) {
            $billingAddress = new Address([
                'type' => 'billing',
                'address_line_1' => $data['shipping_address_line1'],
                'address_line_2' => $data['shipping_address_line2'] ?? null,
                'is_default' => true,
                'same_as_shipping' => true,
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'addressable_type' => 'App\Models\Order', // Add this
                'addressable_id' => $order->id, // Add this
            ]);
            $billingAddress->save();
        } else {
            $billingAddress = new Address([
                'type' => 'billing',
                'address_line_1' => $data['billing_address_line1'],
                'address_line_2' => $data['billing_address_line2'] ?? null,
                'is_default' => true,
                'same_as_shipping' => false,
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'addressable_type' => 'App\Models\Order', // Add this
                'addressable_id' => $order->id, // Add this
            ]);
            $billingAddress->save();
        }

        \Log::info('Order addresses created for order: ' . $order->id);
    }

    private function updateStock(array $items): void
    {
        foreach ($items as $item) {
            if ($item['product']->track_stock) {
                $quantity = $item['quantity'] ?? 1;
                $item['product']->decrement('stock_quantity', $quantity);
                \Log::info('Stock updated:', [
                    'product_id' => $item['product']->id,
                    'quantity_removed' => $quantity,
                    'new_stock' => $item['product']->stock_quantity
                ]);
            }
        }
    }

    private function sendOrderConfirmation(Order $order, Customer $customer): void
    {
        try {
            // Get store email from store settings
            $storeSettings = StoreSetting::first();
            $storeEmail = $storeSettings->email ?? config('mail.from.address');
            $storeName = $storeSettings->store_name ?? 'Store';
            
            // Safely get payment method name
            $paymentMethod = $order->payment_method ?? 'Unknown';
            $paymentMethodName = $this->formatPaymentMethodName($paymentMethod);

            // Send email to customer
            $this->sendEmail(
                $customer->email,
                'Order Placed: ' . $order->order_number,
                "Dear {$customer->first_name},<br><br>
                Your order <b>{$order->order_number}</b> has been placed successfully.<br><br>
                Order Total: {$order->currency_symbol} {$order->grand_total}<br>
                Payment Method: {$paymentMethodName}<br><br>
                You can track your order by visiting our website.<br><br>
                Best regards,<br>
                {$storeName}"
            );

            // Send email to store
            $this->sendEmail(
                $storeEmail,
                'New Order: ' . $order->order_number,
                "New order has been placed by {$customer->first_name} {$customer->last_name}, order number {$order->order_number}.<br><br>
                Order total: {$order->currency_symbol} {$order->grand_total}<br>
                Payment method: {$paymentMethodName}<br><br>
                Please process this order."
            );
            
            \Log::info('Order confirmation emails sent for order: ' . $order->order_number);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Direct purchase functionality
     */
    public function directPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid product information');
        }

        $validated = $validator->validated();
        $productId = $validated['product_id'];
        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['variant_id'] ?? null;

        try {
            // Clear existing cart to ensure only this product is in cart
            Cart::clear();
            
            // Add the product to cart
            Cart::addItem($productId, $quantity, $variantId);
            
            // Redirect directly to checkout
            return redirect()->route('checkout.index')
                ->with('success', 'Product added to cart. Please complete your order.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add product to cart: ' . $e->getMessage());
        }
    }

    public function success($orderNumber)
    {
        \Log::info('Accessing success page for order: ' . $orderNumber);
        
        $order = Order::with(['items.product', 'customer', 'addresses'])
            ->where('order_number', $orderNumber)
            ->first();
        
        if (!$order) {
            \Log::error('Order not found: ' . $orderNumber);
            return redirect()->route('home')->with('error', 'Order not found');
        }

        // Verify this order belongs to current user or guest has session
        if (Auth::check()) {
            if ($order->created_by !== Auth::id()) {
                \Log::warning('Unauthorized access attempt to order: ' . $orderNumber);
                abort(403, 'Unauthorized');
            }
        } else {
            // Check if guest has session for this order
            if (!session()->has('guest_order_' . $orderNumber)) {
                \Log::warning('Guest without session trying to access order: ' . $orderNumber);
                return redirect()->route('home')->with('error', 'Order not found or session expired');
            }
        }

        // Get store settings for this order
        $storeSettings = $this->getStoreCheckoutSettings();

        \Log::info('Success page accessed successfully for order: ' . $orderNumber);

        return view('frontend.checkout.success', compact('order', 'storeSettings'));
    }

    /**
     * Debug cart state
     */
    public function debugCart()
    {
        $cart = Cart::getCart();
        
        $debugInfo = [
            'cart_source' => $cart->source,
            'cart_items_count' => $cart->source === 'database' 
                ? count($cart->cart_model->items ?? []) 
                : count($cart->items ?? []),
            'cart_subtotal' => $cart->subtotal,
            'cart_total' => $cart->total,
            'is_logged_in' => auth()->check(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId()
        ];
        
        if ($cart->source === 'database') {
            $debugInfo['database_cart_id'] = $cart->cart_model->id ?? null;
            $debugInfo['database_items'] = $cart->cart_model->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'variant_id' => $item->variant_id,
                    'product_price' => $item->product->price ?? null
                ];
            })->toArray();
        } else {
            $debugInfo['session_items'] = $cart->items;
        }
        
        return response()->json($debugInfo);
    }
}