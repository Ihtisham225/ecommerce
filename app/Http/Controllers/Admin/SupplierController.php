<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount(['expenses', 'payments']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->paginate(20);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:suppliers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string',
        ]);

        $validated['current_balance'] = $validated['opening_balance'] ?? 0;

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['expenses' => function($query) {
            $query->latest()->limit(10);
        }, 'payments' => function($query) {
            $query->latest()->limit(10);
        }]);

        $totalPurchases = $supplier->expenses()->where('type', 'purchase')->sum('total_amount');
        $totalPayments = $supplier->payments()->sum('amount');

        return view('admin.suppliers.show', compact('supplier', 'totalPurchases', 'totalPayments'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.show', $supplier)
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    public function balanceSheet(Supplier $supplier)
    {
        $transactions = collect();

        // Get expenses
        $expenses = $supplier->expenses()
            ->select('id', 'reference_number', 'total_amount as amount', 'created_at', DB::raw("'purchase' as type"))
            ->get();

        // Get payments
        $payments = $supplier->payments()
            ->select('id', 'reference_number', 'amount', 'created_at', DB::raw("'payment' as type"))
            ->get();

        // Merge and sort by date
        $transactions = $expenses->merge($payments)->sortByDesc('created_at');

        return view('admin.suppliers.balance-sheet', compact('supplier', 'transactions'));
    }

    public function paymentSummary(Supplier $supplier)
    {
        $totalExpenses = $supplier->expenses()->where('type', 'purchase')->sum('total_amount');
        $totalPayments = $supplier->payments()->where('status', 'completed')->sum('amount');
        $balance = $supplier->current_balance;
        
        // Get payment history with details
        $payments = $supplier->payments()
            ->with('expense')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.suppliers.payment-summary', compact(
            'supplier',
            'totalExpenses',
            'totalPayments',
            'balance',
            'payments'
        ));
    }
}