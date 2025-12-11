<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
            
            // Shipping status filter
            if ($request->filled('shipping_status')) {
                $orders->where('shipping_status', $request->shipping_status);
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
                        'completed' => 'green',
                        'cancelled' => 'red',
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
                ->addColumn('shipping_status', function ($row) {
                    $statusColors = [
                        'pending' => 'gray',
                        'delivered' => 'green',
                        'shipped' => 'purple',
                        'ready_for_shipment' => 'yellow'
                    ];

                    $color = $statusColors[$row->shipping_status] ?? 'gray';
                    $label = ucfirst(str_replace('_', ' ', $row->shipping_status));

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
                ->rawColumns(['order_number', 'customer', 'status', 'payment_status', 'shipping_status', 'source', 'total', 'actions'])
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
            'fulfillments.items',
            'history', // Load status history with user
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

        // Calculate additional metrics
        $remainingBalance = max(0, $order->grand_total - $order->paid_amount);
        $paymentPercentage = $order->grand_total > 0 ? ($order->paid_amount / $order->grand_total) * 100 : 0;

        // Get all related data counts
        $dataCounts = [
            'items' => $order->items->count(),
            'payments' => $order->payments->count(),
            'transactions' => $order->transactions->count(),
            'adjustments' => $order->adjustments->count(),
            'fulfillments' => $order->fulfillments->count(),
            'returns' => $order->orderReturns ? $order->orderReturns->count() : 0,
            'history' => $order->history->count(),
        ];

        // Get timeline events
        $timelineEvents = collect();
        
        // Add order creation
        $timelineEvents->push([
            'type' => 'created',
            'title' => 'Order Created',
            'description' => 'Order was placed',
            'date' => $order->created_at,
            'icon' => 'shopping-cart',
            'color' => 'blue'
        ]);

        // Add status changes
        foreach ($order->history as $history) {
            $timelineEvents->push([
                'type' => 'status_change',
                'title' => 'Status Updated',
                'description' => "Changed from {$history->old_status} to {$history->new_status}",
                'date' => $history->created_at,
                'user' => $history->user,
                'icon' => 'refresh',
                'color' => 'indigo'
            ]);
        }

        // Add payment events
        foreach ($order->payments as $payment) {
            $timelineEvents->push([
                'type' => 'payment',
                'title' => 'Payment Received',
                'description' => "{$currencySymbol}" . number_format($payment->amount, 2) . " via " . ucfirst($payment->method),
                'date' => $payment->created_at,
                'icon' => 'credit-card',
                'color' => 'green'
            ]);
        }

        // Add fulfillment events
        foreach ($order->fulfillments as $fulfillment) {
            $timelineEvents->push([
                'type' => 'fulfillment',
                'title' => 'Fulfillment ' . ucfirst($fulfillment->status),
                'description' => $fulfillment->tracking_number ? "Tracking: {$fulfillment->tracking_number}" : "Fulfillment processed",
                'date' => $fulfillment->created_at,
                'icon' => 'truck',
                'color' => 'purple'
            ]);
        }

        // Sort timeline by date
        $timelineEvents = $timelineEvents->sortByDesc('date');

        return view('admin.orders.show', compact(
            'order',
            'customers',
            'currencySymbol',
            'billingAddress',
            'shippingAddress',
            'remainingBalance',
            'paymentPercentage',
            'dataCounts',
            'timelineEvents'
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
            'payment_status' => 'nullable|in:pending,paid,failed,refunded,partially_refunded,partially_paid',
            'shipping_status' => 'nullable|in:pending,ready_for_shipment,shipped,delivered',
            'source' => 'required|in:online,in_store',

            'billing_address' => 'nullable|array',
            'billing_address.address_line_1' => 'nullable|string|max:255',
            'billing_address.address_line_2' => 'nullable|string|max:255',

            'sameAsShipping' => 'nullable|boolean',
            'shipping_address' => 'nullable|array',
            'shipping_address.address_line_1' => 'nullable|string|max:255',
            'shipping_address.address_line_2' => 'nullable|string|max:255',
            
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $order->status;
            $oldPaymentStatus = $order->payment_status;

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
            PAYMENT STATUS LOGIC BASED ON PAYMENTS
            ---------------------------------------------------- */
            $totalPaid = $order->payments()->sum('amount');
            $grandTotal = (float) $order->grand_total;

            // Calculate payment status based on actual payments
            if ($totalPaid >= $grandTotal) {
                $paymentStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $paymentStatus = 'partially_paid';
            } else {
                $paymentStatus = 'pending';
            }

            /* ----------------------------------------------------
            ORDER STATUS LOGIC
            ---------------------------------------------------- */
            $status = $validated['status'] ?? $order->status;

            // For in-store orders, auto-complete when fully paid
            if (($validated['source'] ?? $order->source) === 'in_store') {
                if ($paymentStatus === 'paid') {
                    $status = 'completed';
                } elseif ($paymentStatus === 'partially_paid' && $status !== 'cancelled') {
                    $status = $status !== 'processing' ? 'processing' : $status;
                }
            }

            /* ----------------------------------------------------
            UPDATE ORDER
            ---------------------------------------------------- */
            $order->update([
                'customer_id' => $validated['customer_id'] ?? $order->customer_id,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'shipping_status' => $validated['shipping_status'] ?? $order->shipping_status,
                'source' => $validated['source'],
            ]);

            /* ----------------------------------------------------
            ⭐ UPDATED: Polymorphic address handling
            ---------------------------------------------------- */
            $this->handleAddressUpdates($order, $validated);

            /* ----------------------------------------------------
            REMOVED: Handle Transactions and Payments based on paid_amount
            (Now handled by OrderPaymentController)
            ---------------------------------------------------- */

            /* ----------------------------------------------------
            Track Status Changes
            ---------------------------------------------------- */
            if ($oldStatus !== $order->status) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $order->status,
                    'changed_by' => Auth::id(),
                ]);
            }

            // Recalculate totals
            $this->recalculateOrderTotals($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'shipping_address' => $order->shippingAddress,
                'billing_address' => $order->billingAddress,
                'total_paid' => $totalPaid,
                'balance_due' => $grandTotal - $totalPaid,
                'payment_status' => $paymentStatus,
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

        // Keep existing paid_amount if set, otherwise calculate from payments
        $paidAmount = $order->paid_amount;
        if ($paidAmount <= 0) {
            $paidAmount = (float) $order->payments()
                ->where('status', 'completed')
                ->where('amount', '>', 0)
                ->sum('amount');
        }

        $order->update([
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'grand_total' => $grand,
            'paid_amount' => $paidAmount,
        ]);
    }
}
