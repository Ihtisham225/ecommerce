<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['supplier', 'user']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $expenses = $query->latest()->paginate(20);
        $suppliers = Supplier::active()->get();

        return view('admin.expenses.index', compact('expenses', 'suppliers'));
    }

    public function create(Request $request)
    {
        $suppliers = Supplier::active()->get();
        $products = Product::with(['variants'])->active()->get();


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

        $storeSetting = StoreSetting::first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        // Prepare products data for JavaScript
        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->translate('title'),
                'sku' => $product->sku,
                'price' => $product->price,
                'cost' => $product->cost,
                'has_variants' => $product->variants()->exists(),
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'title' => $variant->title,
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                        'cost' => $variant->cost,
                        'options' => $variant->options
                    ];
                })->toArray()
            ];
        })->toArray();

        return view('admin.expenses.create', compact('suppliers', 'products', 'productsData', 'currencySymbol', 'currencyCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'type' => 'required|in:purchase,operational,salary,utility,other',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,digital_wallet,other',
            'payment_reference' => 'nullable|string',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string',
            'products' => 'required_if:type,purchase|array',
            'products.*.product_id' => 'required_if:type,purchase|exists:products,id',
            'products.*.variant_id' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required_if:type,purchase|integer|min:1',
            'products.*.unit_price' => 'required_if:type,purchase|numeric|min:0',
        ]);

        // Additional validation for products with variants
        if ($validated['type'] === 'purchase' && isset($validated['products'])) {
            foreach ($validated['products'] as $index => $productData) {
                $product = Product::find($productData['product_id']);

                // If product has variants, variant_id is required
                if ($product->variants()->exists() && empty($productData['variant_id'])) {
                    return back()->with('error', 'Please select a variant for ' . $product->translate('title'))
                        ->withInput();
                }

                // If product doesn't have variants, ensure variant_id is null
                if (!$product->variants()->exists() && !empty($productData['variant_id'])) {
                    return back()->with('error', 'This product doesn\'t have variants')
                        ->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            $totalAmount = $validated['amount'] + ($validated['tax_amount'] ?? 0);

            $expense = Expense::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'date' => $validated['date'],
                'due_date' => $validated['due_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Save purchase items if type is purchase
            if ($validated['type'] === 'purchase' && isset($validated['products'])) {
                foreach ($validated['products'] as $productData) {
                    $product = Product::find($productData['product_id']);

                    // If product has variants, variant_id is required
                    if ($product->variants()->exists() && empty($productData['variant_id'])) {
                        DB::rollBack();
                        return back()->with('error', 'Please select a variant for ' . $product->translate('title'));
                    }

                    // If product doesn't have variants, don't save variant_id
                    $variantId = $product->variants()->exists() ? $productData['variant_id'] : null;

                    // Create purchase item
                    PurchaseItem::create([
                        'expense_id' => $expense->id,
                        'product_id' => $productData['product_id'],
                        'variant_id' => $variantId,
                        'quantity' => $productData['quantity'],
                        'unit_price' => $productData['unit_price'],
                        'description' => $productData['description'] ?? null,
                        'total_price' => $productData['quantity'] * $productData['unit_price'],
                    ]);

                    // UPDATE STOCK QUANTITY
                    $this->updateProductStock($product, $variantId, $productData['quantity'], 'add');
                }
            }

            DB::commit();

            return redirect()->route('admin.expenses.show', $expense)
                ->with('success', 'Expense recorded successfully and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record expense: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        $expense->load(['supplier', 'user', 'purchaseItems.product', 'purchaseItems.variant']);

        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $expense->load(['purchaseItems.product', 'purchaseItems.variant']);
        $suppliers = Supplier::active()->get();
        $products = Product::with(['variants'])->active()->get();

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

        $storeSetting = StoreSetting::first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        // Prepare products data for JavaScript
        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->translate('title'),
                'sku' => $product->sku,
                'price' => $product->price,
                'cost' => $product->cost,
                'has_variants' => $product->variants()->exists(),
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'title' => $variant->title,
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                        'cost' => $variant->cost,
                        'options' => $variant->options
                    ];
                })->toArray()
            ];
        })->toArray();

        return view('admin.expenses.edit', compact('expense', 'suppliers', 'products', 'productsData', 'currencySymbol', 'currencyCode'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'type' => 'required|in:purchase,operational,salary,utility,other',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,digital_wallet,other',
            'payment_reference' => 'nullable|string',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string',
            'products' => 'required_if:type,purchase|array',
            'products.*.product_id' => 'required_if:type,purchase|exists:products,id',
            'products.*.variant_id' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required_if:type,purchase|integer|min:1',
            'products.*.unit_price' => 'required_if:type,purchase|numeric|min:0',
            'products.*.description' => 'nullable|string',
        ]);

        // Additional validation for products with variants
        if ($validated['type'] === 'purchase' && isset($validated['products'])) {
            foreach ($validated['products'] as $index => $productData) {
                $product = Product::find($productData['product_id']);

                // If product has variants, variant_id is required
                if ($product->variants()->exists() && empty($productData['variant_id'])) {
                    return back()->with('error', 'Please select a variant for ' . $product->translate('title'))
                        ->withInput();
                }

                // If product doesn't have variants, ensure variant_id is null
                if (!$product->variants()->exists() && !empty($productData['variant_id'])) {
                    return back()->with('error', 'This product doesn\'t have variants')
                        ->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            $totalAmount = $validated['amount'] + ($validated['tax_amount'] ?? 0);

            // Store old values for balance calculation BEFORE updating
            $oldSupplierId = $expense->supplier_id;
            $oldTotalAmount = $expense->total_amount;
            $oldType = $expense->type;

            // Store old purchase items for stock reversal BEFORE updating
            $oldPurchaseItems = $expense->type === 'purchase'
                ? $expense->purchaseItems()->with(['product', 'variant'])->get()
                : collect();

            // Update the expense
            $expense->update([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'date' => $validated['date'],
                'due_date' => $validated['due_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Handle supplier balance updates
            $this->updateSupplierBalances($expense, $oldSupplierId, $oldTotalAmount, $oldType);

            // Handle stock changes
            if ($oldType === 'purchase') {
                // Reverse old stock quantities
                foreach ($oldPurchaseItems as $oldItem) {
                    $this->updateProductStock($oldItem->product, $oldItem->variant_id, $oldItem->quantity, 'subtract');
                }
            }

            // Handle purchase items if type is purchase
            if ($validated['type'] === 'purchase') {
                $this->updatePurchaseItems($expense, $validated['products'] ?? []);

                // Add new stock quantities
                foreach ($validated['products'] as $productData) {
                    $product = Product::find($productData['product_id']);
                    $variantId = $product->variants()->exists() ? $productData['variant_id'] : null;
                    $this->updateProductStock($product, $variantId, $productData['quantity'], 'add');
                }
            } else {
                // Delete all purchase items if type changed from purchase to something else
                if ($oldType === 'purchase') {
                    $expense->purchaseItems()->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.expenses.show', $expense)
                ->with('success', 'Expense updated successfully and stock adjusted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update expense: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update supplier balances when expense is updated
     */
    private function updateSupplierBalances(Expense $expense, $oldSupplierId, $oldTotalAmount, $oldType)
    {
        // If type is not purchase, no balance updates needed
        if ($expense->type !== 'purchase' && $oldType !== 'purchase') {
            return;
        }

        // Handle old supplier balance reversal if needed
        if ($oldType === 'purchase' && $oldSupplierId) {
            $oldSupplier = Supplier::find($oldSupplierId);
            if ($oldSupplier) {
                // Only reverse if expense was pending
                if ($expense->status === 'pending') {
                    $oldSupplier->updateBalance($oldTotalAmount, 'subtract');
                }

                // If supplier changed, also need to handle the old supplier's status
                if ($oldSupplierId != $expense->supplier_id && $expense->status === 'paid') {
                    // Add back to old supplier since it was already marked as paid
                    $oldSupplier->updateBalance($oldTotalAmount, 'add');
                }
            }
        }

        // Handle new supplier balance addition if needed
        if ($expense->type === 'purchase' && $expense->supplier_id) {
            $newSupplier = Supplier::find($expense->supplier_id);
            if ($newSupplier) {
                // Only add if expense is pending
                if ($expense->status === 'pending') {
                    $newSupplier->updateBalance($expense->total_amount, 'add');
                }

                // If supplier changed from another supplier and expense was paid,
                // need to subtract from new supplier
                if ($oldSupplierId && $oldSupplierId != $expense->supplier_id && $expense->status === 'paid') {
                    $newSupplier->updateBalance($expense->total_amount, 'subtract');
                }
            }
        }

        // Handle amount changes for same supplier
        if (
            $expense->type === 'purchase' && $expense->supplier_id &&
            $oldSupplierId == $expense->supplier_id &&
            $oldTotalAmount != $expense->total_amount &&
            $expense->status === 'pending'
        ) {

            $supplier = Supplier::find($expense->supplier_id);
            if ($supplier) {
                $difference = $expense->total_amount - $oldTotalAmount;
                if ($difference != 0) {
                    $supplier->updateBalance($difference, 'add');
                }
            }
        }

        // Handle type change from purchase to non-purchase
        if ($oldType === 'purchase' && $expense->type !== 'purchase' && $oldSupplierId) {
            $oldSupplier = Supplier::find($oldSupplierId);
            if ($oldSupplier && $expense->status === 'pending') {
                $oldSupplier->updateBalance($oldTotalAmount, 'subtract');
            }
        }

        // Handle type change from non-purchase to purchase
        if ($oldType !== 'purchase' && $expense->type === 'purchase' && $expense->supplier_id) {
            $supplier = Supplier::find($expense->supplier_id);
            if ($supplier && $expense->status === 'pending') {
                $supplier->updateBalance($expense->total_amount, 'add');
            }
        }
    }

    /**
     * Update purchase items for the expense
     */
    private function updatePurchaseItems(Expense $expense, array $products)
    {
        // Get existing product IDs
        $existingItems = $expense->purchaseItems->keyBy('id');

        // Update or create items
        foreach ($products as $productData) {
            $product = Product::find($productData['product_id']);

            // Validate variant selection for products with variants
            if ($product->variants()->exists() && empty($productData['variant_id'])) {
                throw new \Exception('Please select a variant for ' . $product->translate('title'));
            }

            // Ensure variant_id is null if product doesn't have variants
            $variantId = $product->variants()->exists() ? $productData['variant_id'] : null;

            // Find if this item already exists (by product_id and variant_id)
            $existingItem = $expense->purchaseItems()
                ->where('product_id', $productData['product_id'])
                ->where('variant_id', $variantId)
                ->first();

            if ($existingItem) {
                // Calculate quantity difference for stock adjustment
                $quantityDifference = $productData['quantity'] - $existingItem->quantity;

                // Update stock based on difference
                if ($quantityDifference != 0) {
                    $operation = $quantityDifference > 0 ? 'add' : 'subtract';
                    $this->updateProductStock($product, $variantId, abs($quantityDifference), $operation);
                }

                // Update existing item
                $existingItem->update([
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'description' => $productData['description'] ?? null,
                    'total_price' => $productData['quantity'] * $productData['unit_price']
                ]);

                // Remove from existing items list
                $existingItems->forget($existingItem->id);
            } else {
                // Create new item
                PurchaseItem::create([
                    'expense_id' => $expense->id,
                    'product_id' => $productData['product_id'],
                    'variant_id' => $variantId,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'description' => $productData['description'] ?? null,
                    'total_price' => $productData['quantity'] * $productData['unit_price']
                ]);

                // New item, add stock
                $this->updateProductStock($product, $variantId, $productData['quantity'], 'add');
            }
        }

        // Delete items that are no longer in the list and reverse their stock
        if ($existingItems->isNotEmpty()) {
            foreach ($existingItems as $item) {
                // Reverse stock for deleted items
                $this->updateProductStock($item->product, $item->variant_id, $item->quantity, 'subtract');
            }

            $expense->purchaseItems()
                ->whereIn('id', $existingItems->keys())
                ->delete();
        }
    }

    public function destroy(Expense $expense)
    {
        DB::beginTransaction();
        try {
            // Return stock if it's a purchase
            if ($expense->type === 'purchase') {
                foreach ($expense->purchaseItems as $item) {
                    // Update product stock by subtracting the purchased quantity
                    $this->updateProductStock($item->product, $item->variant_id, $item->quantity, 'subtract');
                }

                // Update supplier balance
                if ($expense->supplier) {
                    $expense->supplier->updateBalance($expense->total_amount, 'subtract');
                }
            }

            $expense->delete();
            DB::commit();

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expense deleted successfully and stock adjusted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete expense: ' . $e->getMessage());
        }
    }

    public function markAsPaid(Expense $expense)
    {
        $expense->markAsPaid();

        return back()->with('success', 'Expense marked as paid.');
    }

    /**
     * Update product stock quantity
     */
    private function updateProductStock(Product $product, ?int $variantId, int $quantity, string $operation = 'add')
    {
        // Check if product has variants
        if ($product->variants()->exists()) {
            // Product has variants, update specific variant stock
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $newQuantity = $operation === 'add'
                        ? $variant->stock_quantity + $quantity
                        : $variant->stock_quantity - $quantity;

                    $variant->update([
                        'stock_quantity' => $newQuantity
                    ]);

                    // Also update product's total stock if tracking
                    if ($product->track_stock) {
                        $newTotalStock = $product->variants()->sum('stock_quantity');
                        $product->update([
                            'stock_quantity' => $newTotalStock,
                            'stock_status' => $newTotalStock > 0 ? 'in_stock' : 'out_of_stock'
                        ]);
                    }
                }
            } else {
                // Product has variants but no variant_id was provided
                // This shouldn't happen if validation is working, but just in case
                throw new \Exception('Product has variants but no variant was selected');
            }
        } else {
            // Product doesn't have variants, update main product stock
            if ($product->track_stock) {
                $newQuantity = $operation === 'add'
                    ? $product->stock_quantity + $quantity
                    : $product->stock_quantity - $quantity;

                $product->update([
                    'stock_quantity' => $newQuantity,
                    'stock_status' => $newQuantity > 0 ? 'in_stock' : 'out_of_stock'
                ]);
            }
        }
    }
}
