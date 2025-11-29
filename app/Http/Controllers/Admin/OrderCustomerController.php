<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderCustomerController extends Controller
{
    // Assign normal customer via AJAX
    public function select(Order $order, Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id'
        ]);

        $order->customer_id = $request->customer_id;
        $order->save();

        return response()->json(['success' => true]);
    }

    // Remove customer from order via AJAX
    public function remove(Order $order)
    {
        $order->customer_id = null;
        $order->save();

        return response()->json(['success' => true]);
    }

    // Update in-store guest customer info (optional, not used in current Blade)
    public function updateInStore(Order $order, Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string',
            'phone' => 'nullable|string',
        ]);

        // Split name safely
        $parts = explode(' ', $validated['name'], 2);
        $first = $parts[0];
        $last = $parts[1] ?? '';

        $order->update([
            'first_name' => $first,
            'last_name'  => $last,
            'phone'      => $validated['phone'] ?? null,
            'is_guest'   => true,
        ]);

        return response()->json(['success' => true]);
    }
}
