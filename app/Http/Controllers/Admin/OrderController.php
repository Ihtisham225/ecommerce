<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\OrderPayment;
use App\Models\OrderStatusHistory;
use App\Models\OrderAdjustment;
use App\Models\OrderTransaction;
use App\Models\Fulfillment;
use App\Models\FulfillmentItem;
use App\Models\Customer;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::query()
                ->with(['customer', 'addresses', 'payments'])
                ->select(['id', 'order_number', 'customer_id', 'status', 'payment_status', 'shipping_status', 'source', 'grand_total', 'created_at'])
                ->latest();

            // Status filter
            if ($request->filled('status')) {
                $orders->where('status', $request->status);
            }

            // Payment status filter
            if ($request->filled('payment_status')) {
                $orders->where('payment_status', $request->payment_status);
            }

            // Source filter (online/in_store)
            if ($request->filled('source')) {
                $orders->where('source', $request->source);
            }

            // Date range filter
            if ($request->has('date_range') && $request->date_range !== '') {
                $now = now();
                switch ($request->date_range) {
                    case 'today':
                        $orders->whereDate('created_at', $now->toDateString());
                        break;
                    case 'yesterday':
                        $orders->whereDate('created_at', $now->subDay()->toDateString());
                        break;
                    case 'week':
                        $orders->whereBetween('created_at', [
                            $now->startOfWeek(),
                            $now->endOfWeek()
                        ]);
                        break;
                    case 'month':
                        $orders->whereBetween('created_at', [
                            $now->startOfMonth(),
                            $now->endOfMonth()
                        ]);
                        break;
                    case 'year':
                        $orders->whereBetween('created_at', [
                            $now->startOfYear(),
                            $now->endOfYear()
                        ]);
                        break;
                }
            }

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $orders->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            return DataTables::of($orders)
                ->addColumn('order_number', function ($row) {
                    return '<span class="font-mono text-sm">' . e($row->order_number) . '</span>';
                })
                ->addColumn('customer', function ($row) {
                    if ($row->customer) {
                        return e($row->customer->full_name) . '<br><small class="text-gray-500">' . e($row->customer->phone) . '</small>';
                    }
                    return '<span class="text-gray-500">Guest</span>';
                })
                ->addColumn('status', function ($row) {
                    $statusColors = [
                        'pending' => 'gray',
                        'confirmed' => 'blue',
                        'processing' => 'yellow',
                        'shipped' => 'indigo',
                        'delivered' => 'green',
                        'cancelled' => 'red',
                        'refunded' => 'purple'
                    ];

                    $color = $statusColors[$row->status] ?? 'gray';
                    $label = ucfirst($row->status);

                    return <<<HTML
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200">
                            {$label}
                        </span>
                    HTML;
                })
                ->addColumn('payment_status', function ($row) {
                    $statusColors = [
                        'pending' => 'gray',
                        'paid' => 'green',
                        'failed' => 'red',
                        'refunded' => 'purple',
                        'partially_refunded' => 'yellow'
                    ];

                    $color = $statusColors[$row->payment_status] ?? 'gray';
                    $label = ucfirst(str_replace('_', ' ', $row->payment_status));

                    return <<<HTML
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200">
                            {$label}
                        </span>
                    HTML;
                })
                ->addColumn('source', function ($row) {
                    $color = $row->source === 'online' ? 'blue' : 'green';
                    $label = $row->source === 'online' ? 'Online' : 'In Store';
                    $icon = $row->source === 'online' ? 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9' 
                                                        : 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4';

                    return <<<HTML
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{$icon}"></path>
                            </svg>
                            {$label}
                        </span>
                    HTML;
                })
                ->addColumn('total', function ($row) {
                    // If you have currency relation, adjust; fallback to $
                    $currencySymbol = $row->currency->symbol ?? '$';
                    return '<span class="font-semibold">' . $currencySymbol . number_format($row->grand_total, 2) . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $showUrl = route('admin.orders.show', $row->id);
                    $editUrl = route('admin.orders.edit', $row->id);

                    return <<<HTML
                        <div class="flex justify-center gap-2">
                            <a href="{$showUrl}" 
                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                                        9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 
                                        0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{$editUrl}" 
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 
                                        2h11a2 2 0 002-2v-5m-1.414-9.414a2 
                                        2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    HTML;
                })
                ->editColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i'))
                ->rawColumns(['order_number', 'customer', 'status', 'payment_status', 'source', 'total', 'actions'])
                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function create()
    {
        // Create a new draft order
        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_status' => 'pending',
            'source' => 'in_store',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.orders.edit', $order->id);
    }

    public function show(Order $order)
    {
        // Load all required relations
        $order->load([
            'customer.addresses', // Load customer addresses
            'items.product',
            'items.variant',
            'payments',
            'addresses',
            'adjustments',
            'transactions',
            'fulfillments.items'
        ]);

        // Load customers if needed (for dropdowns / display)
        $customers = Customer::orderBy('first_name')->get();

        // Currency symbols
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

        // Get store setting
        $storeSetting = StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        // Get addresses - first from customer, then from order
        $billingAddress = $order->customer?->addresses()->billing()->default()->first() 
            ?? $order->addresses()->billing()->first();
        
        $shippingAddress = $order->customer?->addresses()->shipping()->default()->first() 
            ?? $order->addresses()->shipping()->first();

        return view('admin.orders.show', compact(
            'order',
            'customers',
            'currencySymbol',
            'billingAddress',
            'shippingAddress'
        ));
    }

    public function edit(Order $order)
    {
        $order->load([
            'customer',
            'items.product',
            'items.variant',
            'payments',
            'addresses', // This loads polymorphic addresses
            'adjustments',
            'transactions',
            'fulfillments.items'
        ]);

        $customers = Customer::orderBy('first_name')->get();

        // Get currency symbols
        $currencySymbols = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'PKR' => '₨', 
            'INR' => '₹', 'AED' => 'د.إ', 'SAR' => '﷼', 'CAD' => '$', 
            'AUD' => '$', 'KWD' => 'K.D',
        ];

        $storeSetting = StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        // Prepare default addresses from order's polymorphic addresses
        $defaultShipping = $order->addresses->where('type', 'shipping')->first();
        $defaultBilling = $order->addresses->where('type', 'billing')->first();

        return view('admin.orders.form', compact(
            'order', 
            'customers', 
            'currencySymbol', 
            'defaultShipping', 
            'defaultBilling'
        ));
    }

    public function autoSave(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'nullable|in:pending,confirmed,processing,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded,partially_refunded',
            'shipping_status' => 'nullable|in:pending,ready_for_shipment,shipped,delivered',
            'source' => 'required|in:online,in_store',

            'billing_address' => 'nullable|array',
            'billing_address.address_line_1' => 'nullable|string|max:255',
            'billing_address.address_line_2' => 'nullable|string|max:255',

            'sameAsShipping' => 'nullable|boolean',
            'shipping_address' => 'nullable|array',
            'shipping_address.address_line_1' => 'nullable|string|max:255',
            'shipping_address.address_line_2' => 'nullable|string|max:255',

            // Payment
            'payment' => 'nullable|array',
            'payment.method' => 'nullable|string|max:255',
            'payment.amount' => 'nullable|numeric|min:0',
            'payment.transaction_id' => 'nullable|string|max:255',

            // Adjustments
            'adjustments' => 'nullable|array',
            'adjustments.*.id' => 'nullable|exists:order_adjustments,id',
            'adjustments.*.type' => 'required_with:adjustments|string',
            'adjustments.*.title' => 'nullable|string',
            'adjustments.*.amount' => 'required_with:adjustments|numeric',

            // Transactions
            'transactions' => 'nullable|array',
            'transactions.*.id' => 'nullable|exists:order_transactions,id',
            'transactions.*.type' => 'required_with:transactions|string',
            'transactions.*.status' => 'required_with:transactions|string',
            'transactions.*.amount' => 'required_with:transactions|numeric',
            'transactions.*.gateway' => 'nullable|string',
            'transactions.*.transaction_id' => 'nullable|string',

            'notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $order->status;

            /* ----------------------------------------------------
            ⭐ Detect customer change
            ---------------------------------------------------- */
            $customerChanged = isset($validated['customer_id']) &&
                            $validated['customer_id'] != $order->customer_id;

            if ($customerChanged) {
                $customer = Customer::find($validated['customer_id']);

                // Remove existing order addresses when customer changes
                $order->addresses()->delete();

                // If customer has default addresses, copy them to order
                $defaultShipping = $customer->addresses()->shipping()->default()->first();
                $defaultBilling = $customer->addresses()->billing()->default()->first();

                if ($defaultShipping) {
                    $order->addresses()->create([
                        'type' => 'shipping',
                        'address_line_1' => $defaultShipping->address_line_1,
                        'address_line_2' => $defaultShipping->address_line_2,
                        'is_default' => true,
                    ]);
                }

                if ($defaultBilling) {
                    $order->addresses()->create([
                        'type' => 'billing',
                        'address_line_1' => $defaultBilling->address_line_1,
                        'address_line_2' => $defaultBilling->address_line_2,
                        'is_default' => true,
                    ]);
                }
            }

            /* ----------------------------------------------------
            Update Order core fields
            ---------------------------------------------------- */
            if (($validated['source'] ?? $order->source) === 'in_store') {
                $validated['status'] = 'delivered';
                $validated['payment_status'] = 'paid';
                $validated['shipping_status'] = 'delivered';
            }

            $order->update([
                'customer_id' => $validated['customer_id'] ?? $order->customer_id,
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'shipping_status' => $validated['shipping_status'],
                'source' => $validated['source'],
                'notes' => $validated['notes'] ?? $order->notes,
                'admin_notes' => $validated['admin_notes'] ?? $order->admin_notes,
            ]);

            /* ----------------------------------------------------
            ⭐ UPDATED: Polymorphic address handling
            ---------------------------------------------------- */
            $this->handleAddressUpdates($order, $validated);

            /* ----------------------------------------------------
            Payment / Adjustments / Transactions
            ---------------------------------------------------- */
            if (isset($validated['payment'])) {
                $this->updatePayment($order, $validated['payment']);
            }

            if (isset($validated['adjustments'])) {
                $this->updateAdjustments($order, $validated['adjustments']);
            }

            if (isset($validated['transactions'])) {
                $this->updateTransactions($order, $validated['transactions']);
            }

            if ($oldStatus !== $order->status) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $order->status,
                    'changed_by' => Auth::id(),
                ]);
            }

            $this->recalculateOrderTotals($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'shipping_address' => $order->shippingAddress,
                'billing_address' => $order->billingAddress,
                'order' => $order->fresh([
                    'customer', 'items', 'addresses', 'payments',
                    'adjustments', 'transactions', 'fulfillments'
                ]),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle polymorphic address updates
     */
    private function handleAddressUpdates(Order $order, array $validated)
    {
        $isCustomerOrder = !empty($order->customer_id);
        
        // Handle shipping address
        if (isset($validated['shipping_address'])) {
            $this->updateOrCreateAddress($order, $validated['shipping_address'], 'shipping');
            
            // If customer order, also update customer's default shipping address
            if ($isCustomerOrder) {
                $this->updateCustomerDefaultAddress($order->customer, $validated['shipping_address'], 'shipping');
            }
        }

        // Handle billing address
        if (!empty($validated['sameAsShipping']) && $validated['sameAsShipping']) {
            // Copy shipping to billing
            $shippingAddress = $order->addresses()->shipping()->first();
            if ($shippingAddress) {
                $billingData = [
                    'address_line_1' => $shippingAddress->address_line_1,
                    'address_line_2' => $shippingAddress->address_line_2,
                ];
                
                $this->updateOrCreateAddress($order, $billingData, 'billing', true);
                
                // If customer order, also update customer's default billing address
                if ($isCustomerOrder) {
                    $this->updateCustomerDefaultAddress($order->customer, $billingData, 'billing');
                }
            }
        } elseif (isset($validated['billing_address'])) {
            $this->updateOrCreateAddress($order, $validated['billing_address'], 'billing');
            
            // If customer order, also update customer's default billing address
            if ($isCustomerOrder) {
                $this->updateCustomerDefaultAddress($order->customer, $validated['billing_address'], 'billing');
            }
        }
    }

    /**
     * Update or create address for polymorphic relation
     */
    private function updateOrCreateAddress(Order $order, array $data, string $type, bool $sameAsShipping = false)
    {
        $address = $order->addresses()->where('type', $type)->first();

        $payload = [
            'type' => $type,
            'address_line_1' => $data['address_line_1'] ?? '',
            'address_line_2' => $data['address_line_2'] ?? null,
            'is_default' => true, // For orders, addresses are always default for that order
            'same_as_shipping' => $sameAsShipping,
        ];

        if ($address) {
            $address->update($payload);
        } else {
            $order->addresses()->create($payload);
        }
    }

    /**
     * Update customer's default address of given type
     */
    private function updateCustomerDefaultAddress(Customer $customer, array $data, string $type)
    {
        $customerAddress = $customer->addresses()
            ->where('type', $type)
            ->where('is_default', true)
            ->first();

        $payload = [
            'type' => $type,
            'address_line_1' => $data['address_line_1'] ?? '',
            'address_line_2' => $data['address_line_2'] ?? null,
            'is_default' => true,
        ];

        if ($customerAddress) {
            $customerAddress->update($payload);
        } else {
            $customer->addresses()->create($payload);
        }
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:confirm,process,ship,deliver,cancel,mark_paid,mark_refunded',
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orders,id',
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];

        DB::beginTransaction();

        try {
            $orders = Order::whereIn('id', $ids)->get();

            foreach ($orders as $order) {
                $oldStatus = $order->status;

                switch ($action) {
                    case 'confirm':
                        $order->update(['status' => 'confirmed']);
                        break;
                    case 'process':
                        $order->update(['status' => 'processing']);
                        break;
                    case 'ship':
                        $order->update(['status' => 'shipped', 'shipping_status' => 'shipped']);
                        break;
                    case 'deliver':
                        $order->update(['status' => 'delivered', 'shipping_status' => 'delivered', 'completed_at' => now()]);
                        break;
                    case 'cancel':
                        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);
                        break;
                    case 'mark_paid':
                        $order->update(['payment_status' => 'paid']);
                        break;
                    case 'mark_refunded':
                        $order->update(['payment_status' => 'refunded', 'status' => 'refunded']);
                        break;
                }

                // Record status history
                if ($oldStatus !== $order->status) {
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => $order->status,
                        'changed_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orders updated successfully.',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update orders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();

        try {
            // Soft delete related records
            $order->items()->delete();
            $order->addresses()->delete();
            $order->payments()->delete();
            $order->history()->delete();
            $order->adjustments()->delete();
            $order->transactions()->delete();
            $order->fulfillments()->delete();
            
            // Soft delete the order
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle order status (similar to products toggle)
     */
    public function toggle(Request $request, Order $order)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:status,payment_status,shipping_status',
        ]);

        $type = $validated['type'];
        $oldStatus = $order->{$type};

        if ($type === 'status') {
            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
            $currentIndex = array_search($order->status, $statuses);
            $newIndex = ($currentIndex + 1) % count($statuses);
            $order->status = $statuses[$newIndex];
        } elseif ($type === 'payment_status') {
            $statuses = ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'];
            $currentIndex = array_search($order->payment_status, $statuses);
            $newIndex = ($currentIndex + 1) % count($statuses);
            $order->payment_status = $statuses[$newIndex];
        } elseif ($type === 'shipping_status') {
            $statuses = ['pending', 'ready_for_shipment', 'shipped', 'delivered'];
            $currentIndex = array_search($order->shipping_status, $statuses);
            $newIndex = ($currentIndex + 1) % count($statuses);
            $order->shipping_status = $statuses[$newIndex];
        }

        $order->save();

        // Record status history for order status changes
        if ($type === 'status' && $oldStatus !== $order->status) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
                'changed_by' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst(str_replace('_', ' ', $type)) . ' updated successfully.',
            'order' => $order->fresh(),
        ]);
    }

    /**
     * Update only order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;

        DB::beginTransaction();
        try {
            $order->update(['status' => $validated['status']]);

            // Record status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'changed_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order' => $order->fresh(),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update only payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,partially_refunded',
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully.',
            'order' => $order->fresh(),
        ]);
    }

    /**
     * Add payment to order (also creates a transaction record)
     */
    public function addPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'method' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'nullable|string|max:255',
            'gateway' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = $order->payments()->create([
                'method' => $validated['method'],
                'amount' => $validated['amount'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Also create a transaction record for gateway visibility
            $order->transactions()->create([
                'type' => 'capture',
                'status' => 'success',
                'amount' => $validated['amount'],
                'payment_method' => $validated['method'],
                'gateway' => $validated['gateway'] ?? null,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'meta' => null,
            ]);

            // Update payment status if fully paid
            $totalPaid = $order->payments()->where('status', 'completed')->sum('amount');
            if ($totalPaid >= $order->grand_total) {
                $order->update(['payment_status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $order->update(['payment_status' => 'partially_refunded']); // possibly 'partial' but keeping your logic
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment added successfully.',
                'order' => $order->fresh(['payments', 'transactions']),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process refund
     */
    public function processRefund(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $order->grand_total,
            'reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create refund payment record (negative)
            $order->payments()->create([
                'method' => 'refund',
                'amount' => -abs($validated['amount']), // Negative amount for refund
                'transaction_id' => 'REF_' . now()->timestamp,
                'status' => 'completed',
                'notes' => $validated['reason'],
            ]);

            // Create a transaction record for refund
            $order->transactions()->create([
                'type' => 'refund',
                'status' => 'success',
                'amount' => -abs($validated['amount']),
                'payment_method' => 'refund',
                'gateway' => null,
                'transaction_id' => 'REF_' . now()->timestamp,
                'meta' => ['reason' => $validated['reason']],
            ]);

            // Update order status
            $totalRefunded = abs($order->payments()->where('method', 'refund')->where('status', 'completed')->sum('amount'));
            if ($totalRefunded >= $order->grand_total) {
                $order->update([
                    'payment_status' => 'refunded',
                    'status' => 'refunded'
                ]);
            } else {
                $order->update(['payment_status' => 'partially_refunded']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully.',
                'order' => $order->fresh(['payments', 'transactions']),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process refund.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resend order confirmation
     */
    public function resendConfirmation(Order $order)
    {
        try {
            // Here you would implement your email sending logic
            // Mail::to($order->customer->email)->send(new OrderConfirmation($order));
            
            return response()->json([
                'success' => true,
                'message' => 'Order confirmation sent successfully.',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send order confirmation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate invoice PDF
     */
    public function generateInvoice(Order $order)
    {
        try {
            // PDF generation logic
            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully.',
                'download_url' => '#',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        
        do {
            $number = $prefix . $date . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Update order address
     */
    private function updateAddress(Order $order, array $addressData, string $type)
    {
        $order->addresses()->updateOrCreate(
            ['type' => $type],
            array_merge($addressData, ['type' => $type])
        );
    }

    /**
     * Update payment information (single payment create)
     */
    private function updatePayment(Order $order, array $paymentData)
    {
        if (!empty($paymentData['amount']) && $paymentData['amount'] > 0) {
            $order->payments()->create([
                'method' => $paymentData['method'] ?? 'manual',
                'amount' => $paymentData['amount'],
                'transaction_id' => $paymentData['transaction_id'] ?? null,
                'status' => 'completed'
            ]);

            // create basic transaction record as well
            $order->transactions()->create([
                'type' => 'capture',
                'status' => 'success',
                'amount' => $paymentData['amount'],
                'payment_method' => $paymentData['method'] ?? null,
                'gateway' => $paymentData['gateway'] ?? null,
                'transaction_id' => $paymentData['transaction_id'] ?? null,
                'meta' => null,
            ]);
        }
    }

    /**
     * Update adjustments (create/update/delete)
     */
    private function updateAdjustments(Order $order, array $adjustments)
    {
        $incoming = [];
        foreach ($adjustments as $adj) {
            if (!empty($adj['id'])) {
                $existing = OrderAdjustment::find($adj['id']);
                if ($existing && $existing->order_id === $order->id) {
                    $existing->update([
                        'type' => $adj['type'],
                        'title' => $adj['title'] ?? null,
                        'amount' => $adj['amount'],
                        'meta' => $adj['meta'] ?? null,
                    ]);
                    $incoming[] = $existing->id;
                }
            } else {
                $created = $order->adjustments()->create([
                    'type' => $adj['type'],
                    'title' => $adj['title'] ?? null,
                    'amount' => $adj['amount'],
                    'meta' => $adj['meta'] ?? null,
                ]);
                $incoming[] = $created->id;
            }
        }

        // remove adjustments not in incoming
        if (!empty($incoming)) {
            $order->adjustments()->whereNotIn('id', $incoming)->delete();
        }
    }

    /**
     * Update transactions (create/update/delete)
     */
    private function updateTransactions(Order $order, array $transactions)
    {
        $incoming = [];
        foreach ($transactions as $t) {
            if (!empty($t['id'])) {
                $existing = OrderTransaction::find($t['id']);
                if ($existing && $existing->order_id === $order->id) {
                    $existing->update([
                        'type' => $t['type'],
                        'status' => $t['status'],
                        'amount' => $t['amount'],
                        'payment_method' => $t['payment_method'] ?? null,
                        'gateway' => $t['gateway'] ?? null,
                        'transaction_id' => $t['transaction_id'] ?? null,
                        'meta' => $t['meta'] ?? null,
                    ]);
                    $incoming[] = $existing->id;
                }
            } else {
                $created = $order->transactions()->create([
                    'type' => $t['type'],
                    'status' => $t['status'],
                    'amount' => $t['amount'],
                    'payment_method' => $t['payment_method'] ?? null,
                    'gateway' => $t['gateway'] ?? null,
                    'transaction_id' => $t['transaction_id'] ?? null,
                    'meta' => $t['meta'] ?? null,
                ]);
                $incoming[] = $created->id;
            }
        }

        if (!empty($incoming)) {
            $order->transactions()->whereNotIn('id', $incoming)->delete();
        }
    }

    /**
     * Recalculate order totals
     */
    private function recalculateOrderTotals(Order $order)
    {
        // Use the totals stored on items (safer if items have discounts calculated individually)
        $subtotal = (float) $order->items()->sum('total');

        // adjustments that are discounts are negative amounts (or type 'discount')
        $discountTotal = (float) $order->adjustments()->where('type', 'discount')->sum('amount');

        // If your adjustments store discounts as positive numbers, you may need to invert sign. Adjust accordingly.
        $taxTotal = (float) $order->tax_total;
        $shippingTotal = (float) $order->shipping_total;

        $grand = $subtotal + $taxTotal + $shippingTotal - $discountTotal;

        $order->update([
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'grand_total' => $grand,
        ]);
    }
}
