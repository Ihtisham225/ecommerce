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

        $customer = Customer::find($request->customer_id);

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
                'same_as_shipping' => false, // Shipping address doesn't use this field
            ]);
        }

        if ($defaultBilling) {
            $order->addresses()->create([
                'type' => 'billing',
                'address_line_1' => $defaultBilling->address_line_1,
                'address_line_2' => $defaultBilling->address_line_2,
                'is_default' => true,
                'same_as_shipping' => $defaultBilling->same_as_shipping, // Include the same_as_shipping field
            ]);
        } else {
            // If no billing address exists, create one same as shipping
            $order->addresses()->create([
                'type' => 'billing',
                'address_line_1' => $defaultShipping ? $defaultShipping->address_line_1 : '',
                'address_line_2' => $defaultShipping ? $defaultShipping->address_line_2 : '',
                'is_default' => true,
                'same_as_shipping' => true, // Auto-enable same as shipping when no billing address exists
            ]);
        }

        $order->customer_id = $request->customer_id;
        $order->save();

        // Reload the order with addresses
        $order->load('addresses');

        // Get the updated addresses
        $shippingAddress = $order->addresses->where('type', 'shipping')->first();
        $billingAddress = $order->addresses->where('type', 'billing')->first();

        return response()->json([
            'success' => true,
            'shipping_address' => $shippingAddress ? [
                'address_line_1' => $shippingAddress->address_line_1,
                'address_line_2' => $shippingAddress->address_line_2,
            ] : null,
            'billing_address' => $billingAddress ? [
                'address_line_1' => $billingAddress->address_line_1,
                'address_line_2' => $billingAddress->address_line_2,
                'same_as_shipping' => $billingAddress->same_as_shipping, // Make sure this is included
            ] : null
        ]);
    }

    // Remove customer from order via AJAX
    public function remove(Order $order)
    {
        // Just remove the customer from the order without affecting address records
        $order->customer_id = null;
        $order->save();

        // Note: We're NOT deleting any address records here
        // The customer's addresses remain in the database for their profile

        return response()->json([
            'success' => true,
            'message' => 'Customer removed from order',
            'shipping_address' => null,
            'billing_address' => null
        ]);
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