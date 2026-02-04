<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('customer_id', Auth::user()->customer->id)
            ->with(['items.product', 'payments'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the customer can only view their own orders
        if ($order->customer_id !== Auth::user()->customer->id) {
            abort(403, 'Unauthorized access');
        }

        $order->load([
            'items.product.mainImage',
            'items.variant',
            'payments',
            'addresses',
            'fulfillments.items',
            'history'
        ]);

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
            'KWD' => 'KD',
        ];

        // Get store setting from vendor
        $vendor = $order->vendor;
        $currencyCode = $vendor?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;
        $decimals = $currencyCode === 'KWD' ? 3 : 2;

        // Calculate payment stats
        $totalPaid = $order->payments()->sum('amount');
        $balanceDue = max(0, $order->grand_total - $totalPaid);
        $paymentPercentage = $order->grand_total > 0 ? ($totalPaid / $order->grand_total) * 100 : 0;

        // Get addresses
        $billingAddress = $order->addresses()->billing()->first();
        $shippingAddress = $order->addresses()->shipping()->first();

        // Status colors for customer view
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        $paymentStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            'partially_refunded' => 'bg-orange-100 text-orange-800',
            'partially_paid' => 'bg-blue-100 text-blue-800',
        ];

        $shippingStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'ready_for_shipment' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
        ];

        return view('customer.orders.show', compact(
            'order',
            'currencySymbol',
            'decimals',
            'totalPaid',
            'balanceDue',
            'paymentPercentage',
            'billingAddress',
            'shippingAddress',
            'statusColors',
            'paymentStatusColors',
            'shippingStatusColors'
        ));
    }

    public function invoice(Order $order)
    {
        // Ensure the customer can only download their own invoices
        if ($order->customer_id !== Auth::user()->customer->id) {
            abort(403, 'Unauthorized access');
        }

        // Generate or return invoice PDF
        // You can reuse your existing invoice generation logic here
        return response()->json([
            'success' => true,
            'download_url' => route('admin.orders.invoice.pdf', $order),
        ]);
    }
}
