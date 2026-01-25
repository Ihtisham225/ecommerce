<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Expense;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\HttpCache\Store;

class SupplierPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierPayment::with(['supplier', 'user']);

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);
        $suppliers = Supplier::all();

        // Calculate summary statistics
        $totalCompleted = SupplierPayment::where('status', 'completed');
        $totalPending = SupplierPayment::where('status', 'pending');
        $thisMonthCompleted = SupplierPayment::where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year);

        // Apply filters to summary queries
        if ($request->has('supplier_id')) {
            $totalCompleted->where('supplier_id', $request->supplier_id);
            $totalPending->where('supplier_id', $request->supplier_id);
            $thisMonthCompleted->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('date_from')) {
            $totalCompleted->where('payment_date', '>=', $request->date_from);
            $totalPending->where('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $totalCompleted->where('payment_date', '<=', $request->date_to);
            $totalPending->where('payment_date', '<=', $request->date_to);
        }

        $summary = [
            'total_completed' => $totalCompleted->sum('amount'),
            'total_pending' => $totalPending->sum('amount'),
            'this_month' => $thisMonthCompleted->sum('amount'),
        ];

        return view('admin.supplier-payments.index', compact('payments', 'suppliers', 'summary'));
    }

    public function create(Request $request)
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

        $storeSetting = StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        $suppliers = Supplier::withBalance()->get();
        $supplier = null;
        $pendingExpenses = collect();
        $totalPendingAmount = 0;
        $availableBalance = 0;

        if ($request->has('supplier_id')) {
            $supplier = Supplier::find($request->supplier_id);
            $pendingExpenses = $supplier->expenses()
                ->where('status', '!=', 'paid')
                ->get();
            
            // Calculate total pending amount (what supplier owes)
            $totalPendingAmount = $supplier->current_balance;
            
            // Calculate available balance (how much can be paid)
            $availableBalance = $supplier->current_balance;
        }

        return view('admin.supplier-payments.create', compact(
            'suppliers', 
            'supplier', 
            'pendingExpenses',
            'totalPendingAmount',
            'availableBalance',
            'currencySymbol',
            'currencyCode'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expense_id' => 'nullable|exists:expenses,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,digital_wallet,other',
            'payment_reference' => 'nullable|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
            'payment_type' => 'required|in:partial,full',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max per file
        ]);

        DB::beginTransaction();
        try {
            $supplier = Supplier::find($validated['supplier_id']);

            // Check if payment amount is valid
            if ($validated['amount'] <= 0) {
                throw new \Exception('Payment amount must be greater than zero.');
            }

            if ($validated['amount'] > $supplier->current_balance) {
                throw new \Exception('Payment amount cannot exceed current balance of ' . number_format($supplier->current_balance, 2));
            }

            // Handle file uploads
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('supplier-payments/attachments', 'public');
                    $attachmentPaths[] = $path;
                }
            }

            $newBalance = $supplier->current_balance - $validated['amount'];

            // Create payment
            $payment = SupplierPayment::create([
                'supplier_id' => $validated['supplier_id'],
                'expense_id' => $validated['expense_id'] ?? null,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'payment_date' => $validated['payment_date'],
                'status' => $validated['status'],
                'payment_type' => $validated['payment_type'], // Store payment type
                'notes' => $validated['notes'] ?? null,
                'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
                'user_id' => auth()->id(),
                'previous_balance' => $supplier->current_balance,
                'new_balance' => $newBalance,
            ]);
            
            // Update supplier balance if payment is completed
            if ($validated['status'] === 'completed') {
                $supplier->updateBalance($validated['amount'], 'subtract');
                $payment->new_balance = $supplier->current_balance;
                $payment->save();
                
                // If payment is against a specific expense, update its status
                if ($validated['expense_id']) {
                    $expense = Expense::find($validated['expense_id']);
                    $this->updateExpensePaymentStatus($expense);
                } else {
                    // If no specific expense, check if any expenses are now fully paid
                    $this->checkAndUpdateSupplierExpenses($supplier);
                }
            }

            DB::commit();

            return redirect()->route('admin.supplier-payments.show', $payment)
                ->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function edit(SupplierPayment $supplierPayment)
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

        $storeSetting = StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        $suppliers = Supplier::withBalance()->get();
        
        // Get pending expenses for the current supplier
        $pendingExpenses = $supplierPayment->supplier->expenses()
            ->where('status', '!=', 'paid')
            ->get();
        
        // Also include the current expense even if it's paid
        $currentExpense = $supplierPayment->expense;
        if ($currentExpense && !$pendingExpenses->contains('id', $currentExpense->id)) {
            $pendingExpenses->push($currentExpense);
        }
        
        return view('admin.supplier-payments.edit', compact(
            'supplierPayment',
            'suppliers',
            'pendingExpenses',
            'currencySymbol',
            'currencyCode'
        ));
    }

    public function update(Request $request, SupplierPayment $payment)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expense_id' => 'nullable|exists:expenses,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,digital_wallet,other',
            'payment_reference' => 'nullable|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
            'payment_type' => 'required|in:partial,full',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);
        
        DB::beginTransaction();
        try {
            $supplier = Supplier::find($validated['supplier_id']);
            
            // Check if payment amount is valid
            if ($validated['amount'] <= 0) {
                throw new \Exception('Payment amount must be greater than zero.');
            }
            
            // Store old values for reversal if needed
            $oldAmount = $payment->amount;
            $oldStatus = $payment->status;
            $oldSupplierId = $payment->supplier_id;
            $oldExpenseId = $payment->expense_id; // Fixed: define this variable
            
            // Get old supplier if different
            $oldSupplier = null;
            if ($oldSupplierId != $supplier->id) {
                $oldSupplier = Supplier::find($oldSupplierId);
            }
            
            // 1. Reverse old payment if it was completed
            if ($oldStatus === 'completed') {
                if ($oldSupplier) {
                    // Supplier changed - reverse from old supplier
                    $oldSupplier->updateBalance($oldAmount, 'add');
                } else {
                    // Same supplier - reverse from current supplier
                    $supplier->updateBalance($oldAmount, 'add');
                }
            }
            
            // 2. Check if new amount exceeds current balance for completed payments
            if ($validated['status'] === 'completed') {
                if ($validated['amount'] > $supplier->current_balance) {
                    // Re-apply the old payment if we reversed it
                    if ($oldStatus === 'completed') {
                        if ($oldSupplier) {
                            $oldSupplier->updateBalance($oldAmount, 'subtract');
                        } else {
                            $supplier->updateBalance($oldAmount, 'subtract');
                        }
                    }
                    throw new \Exception('Payment amount cannot exceed current balance of ' . number_format($supplier->current_balance, 2));
                }
                
                // 3. Apply new payment
                $supplier->updateBalance($validated['amount'], 'subtract');
            }
            
            // 4. Handle file uploads - merge with existing attachments
            $attachmentPaths = $payment->attachments ?? [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('supplier-payments/attachments', 'public');
                    $attachmentPaths[] = $path;
                }
            }
            
            // Handle removed attachments
            if ($request->has('removed_attachments') && !empty($request->removed_attachments)) {
                $removedIndices = explode(',', $request->removed_attachments);
                foreach ($removedIndices as $index) {
                    if (isset($attachmentPaths[$index])) {
                        // Delete file from storage
                        Storage::disk('public')->delete($attachmentPaths[$index]);
                        // Remove from array
                        unset($attachmentPaths[$index]);
                    }
                }
                // Re-index array
                $attachmentPaths = array_values($attachmentPaths);
            }

            // 5. Update payment
            $payment->update([
                'supplier_id' => $validated['supplier_id'],
                'expense_id' => $validated['expense_id'] ?? null,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'payment_date' => $validated['payment_date'],
                'status' => $validated['status'],
                'payment_type' => $validated['payment_type'],
                'notes' => $validated['notes'] ?? null,
                'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
                'previous_balance' => $supplier->current_balance + ($validated['status'] === 'completed' ? $validated['amount'] : 0),
                'new_balance' => $supplier->current_balance,
            ]);
            
            // 6. Update expense statuses
            // Update old expense if changed
            if ($oldExpenseId && $oldExpenseId != $validated['expense_id']) {
                $oldExpense = Expense::find($oldExpenseId);
                if ($oldExpense) {
                    $this->updateExpensePaymentStatus($oldExpense);
                }
            }
            
            // Update new expense if specified
            if ($validated['expense_id']) {
                $expense = Expense::find($validated['expense_id']);
                $this->updateExpensePaymentStatus($expense);
            } else {
                // If no specific expense, check all expenses for the supplier
                $this->checkAndUpdateSupplierExpenses($supplier);
                
                // Also check old supplier's expenses if supplier changed
                if ($oldSupplier && $oldSupplier->id != $supplier->id) {
                    $this->checkAndUpdateSupplierExpenses($oldSupplier);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.supplier-payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    /**
     * Update expense payment status based on total payments
     */
    private function updateExpensePaymentStatus(Expense $expense)
    {
        $totalPaid = SupplierPayment::where('expense_id', $expense->id)
            ->where('status', 'completed')
            ->sum('amount');

        if ($totalPaid >= $expense->total_amount) {
            $expense->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $expense->update(['status' => 'partial']);
        } else {
            $expense->update(['status' => 'pending']);
        }
    }

    /**
     * Check and update all supplier expenses after a general payment
     */
    private function checkAndUpdateSupplierExpenses(Supplier $supplier)
    {
        // Get all unpaid/partially paid expenses for the supplier
        $expenses = $supplier->expenses()
            ->whereIn('status', ['pending', 'partial'])
            ->get();
        
        foreach ($expenses as $expense) {
            $this->updateExpensePaymentStatus($expense);
        }
    }

    public function show(SupplierPayment $supplierPayment)
    {
        $supplierPayment->load(['supplier', 'expense', 'user']);
        
        return view('admin.supplier-payments.show', compact('supplierPayment'));
    }

    public function updateStatus(SupplierPayment $payment, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $payment->status;
            $newStatus = $request->status;

            $payment->status = $newStatus;
            
            // Handle balance updates based on status change
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                // Reverse the balance if moving from completed to other status
                $payment->supplier->updateBalance($payment->amount, 'add');
            } elseif ($oldStatus !== 'completed' && $newStatus === 'completed') {
                // Apply the balance if moving to completed
                $payment->supplier->updateBalance($payment->amount, 'subtract');
            }
            
            $payment->new_balance = $payment->supplier->current_balance;
            $payment->save();

            // Update expense status if applicable
            if ($payment->expense) {
                $this->updateExpensePaymentStatus($payment->expense);
            } else {
                $this->checkAndUpdateSupplierExpenses($payment->supplier);
            }

            DB::commit();

            return back()->with('success', 'Payment status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }

    /**
     * Get pending expenses for a supplier (for the form)
     */
    public function getPendingExpenses(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $supplier = Supplier::find($request->supplier_id);
        
        // Get pending expenses for this supplier
        $pendingExpenses = Expense::where('supplier_id', $supplier->id)
            ->whereIn('status', ['pending', 'partial'])
            ->with(['payments' => function($q) {
                $q->where('status', 'completed');
            }])
            ->get()
            ->map(function($expense) {
                $totalPaid = $expense->payments->sum('amount');
                $remainingAmount = max(0, $expense->total_amount - $totalPaid);
                
                return [
                    'id' => $expense->id,
                    'reference_number' => $expense->reference_number,
                    'title' => $expense->title,
                    'total_amount' => (float) $expense->total_amount,
                    'remaining_amount' => (float) $remainingAmount,
                    'status' => $expense->status,
                    'due_date' => $expense->due_date ? $expense->due_date->format('Y-m-d') : null,
                    'date' => $expense->date->format('Y-m-d'),
                ];
            })
            ->filter(function($expense) {
                // Only return expenses that have remaining amount
                return $expense['remaining_amount'] > 0;
            })
            ->values(); // Reset array keys

        return response()->json([
            'success' => true,
            'expenses' => $pendingExpenses
        ]);
    }

    /**
     * Get supplier balance (API endpoint)
     */
    public function getSupplierBalance(Supplier $supplier)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'current_balance' => $supplier->current_balance,
                'formatted_balance' => format_currency($supplier->current_balance)
            ]
        ]);
    }

    /**
     * Get supplier pending expenses (API endpoint)
     */
    public function getSupplierPendingExpenses(Supplier $supplier)
    {
        $pendingExpenses = Expense::where('supplier_id', $supplier->id)
            ->whereIn('status', ['pending', 'partial'])
            ->with(['payments' => function($q) {
                $q->where('status', 'completed');
            }])
            ->get()
            ->map(function($expense) {
                $totalPaid = $expense->payments->sum('amount');
                $remainingAmount = max(0, $expense->total_amount - $totalPaid);
                
                return [
                    'id' => $expense->id,
                    'reference_number' => $expense->reference_number,
                    'title' => $expense->title,
                    'total_amount' => $expense->total_amount,
                    'remaining_amount' => $remainingAmount,
                    'formatted_total_amount' => format_currency($expense->total_amount),
                    'formatted_remaining_amount' => format_currency($remainingAmount),
                    'status' => $expense->status,
                    'due_date' => $expense->due_date ? $expense->due_date->format('Y-m-d') : null,
                    'formatted_due_date' => $expense->due_date ? $expense->due_date->format('M d, Y') : 'No due date',
                    'date' => $expense->date->format('Y-m-d'),
                    'payment_status' => $remainingAmount == 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'pending'),
                    'is_selectable' => $remainingAmount > 0
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name
                ],
                'expenses' => $pendingExpenses,
                'count' => $pendingExpenses->count(),
                'total_remaining' => $pendingExpenses->sum('remaining_amount'),
                'formatted_total_remaining' => format_currency($pendingExpenses->sum('remaining_amount'))
            ]
        ]);
    }
}