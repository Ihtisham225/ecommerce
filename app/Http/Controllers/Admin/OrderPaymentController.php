<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderPaymentController extends Controller
{
    /**
     * Store a new payment for an order
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.001',
            'method' => 'required|in:direct,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Calculate payment status based on new amount
            $newPaymentAmount = (float) $validated['amount'];
            $currentPaidAmount = $order->payments()->sum('amount');
            $newTotalPaid = $currentPaidAmount + $newPaymentAmount;
            $grandTotal = (float) $order->grand_total;

            // Determine payment status
            if ($newTotalPaid >= $grandTotal) {
                $paymentStatus = 'paid';
            } elseif ($newTotalPaid > 0) {
                $paymentStatus = 'partially_paid';
            } else {
                $paymentStatus = 'pending';
            }

            // Create OrderPayment
            $payment = OrderPayment::create([
                'method' => $validated['method'],
                'amount' => $newPaymentAmount,
                'transaction_id' => 'manual_' . time() . '_' . rand(1000, 9999),
                'notes' => $validated['notes'] ?? null,
                'status' => $paymentStatus,
            ]);

            // Attach payment to order
            $order->payments()->attach($payment->id);

            // Create OrderTransaction
            $transaction = OrderTransaction::create([
                'type' => 'payment',
                'status' => $paymentStatus,
                'amount' => $newPaymentAmount,
                'payment_method' => $validated['method'],
                'gateway' => 'manual',
                'transaction_id' => $payment->transaction_id,
                'meta' => [
                    'notes' => $validated['notes'] ?? null,
                    'created_by' => Auth::user()->name,
                    'order_id' => $order->id,
                ],
            ]);

            // Attach transaction to order
            $order->transactions()->attach($transaction->id);

            // Update order payment status
            $order->update([
                'payment_status' => $paymentStatus,
                'paid_amount' => $newTotalPaid,
            ]);

            // For in-store orders, auto-update status when fully paid
            if ($order->source === 'in_store' && $paymentStatus === 'paid') {
                $order->update(['status' => 'completed']);
            }

            // For online orders, ensure they're marked as paid
            if ($order->source === 'online') {
                $order->update(['payment_status' => 'paid']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment added successfully',
                'payment' => $payment,
                'transaction' => $transaction,
                'order' => $order->fresh(['payments', 'transactions']),
                'total_paid' => $newTotalPaid,
                'balance_due' => $grandTotal - $newTotalPaid,
                'payment_status' => $paymentStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a payment from an order
     */
    public function destroy(Request $request, Order $order, OrderPayment $payment)
    {
        DB::beginTransaction();

        try {
            // Get the payment amount before detaching
            $paymentAmount = (float) $payment->amount;
            
            // Detach payment from order
            $order->payments()->detach($payment->id);

            // Find and delete related transaction
            $transaction = OrderTransaction::where('transaction_id', $payment->transaction_id)->first();
            if ($transaction) {
                $order->transactions()->detach($transaction->id);
                $transaction->delete();
            }

            // Delete the payment
            $payment->delete();

            // Recalculate payment status
            $newTotalPaid = $order->payments()->sum('amount');
            $grandTotal = (float) $order->grand_total;

            if ($newTotalPaid >= $grandTotal) {
                $paymentStatus = 'paid';
            } elseif ($newTotalPaid > 0) {
                $paymentStatus = 'partially_paid';
            } else {
                $paymentStatus = 'pending';
            }

            // Update order
            $order->update([
                'payment_status' => $paymentStatus,
                'paid_amount' => $newTotalPaid,
            ]);

            // For in-store orders, revert status if not fully paid
            if ($order->source === 'in_store' && $paymentStatus !== 'paid') {
                $order->update(['status' => 'processing']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment removed successfully',
                'order' => $order->fresh(['payments', 'transactions']),
                'total_paid' => $newTotalPaid,
                'balance_due' => $grandTotal - $newTotalPaid,
                'payment_status' => $paymentStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payments for an order
     */
    public function index(Order $order)
    {
        $payments = $order->payments()
            ->orderBy('order_payment_order.created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => (float) $payment->amount,
                    'method' => $payment->method,
                    'notes' => $payment->notes,
                    'created_at' => $payment->pivot->created_at->format('M d, Y H:i'),
                    'created_by' => $payment->pivot->created_by,
                ];
            });

        return response()->json([
            'success' => true,
            'payments' => $payments,
            'total_paid' => $order->payments()->sum('amount'),
            'grand_total' => $order->grand_total,
            'balance_due' => $order->grand_total - $order->payments()->sum('amount'),
            'payment_status' => $order->payment_status,
        ]);
    }
}