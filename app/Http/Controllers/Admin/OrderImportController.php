<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\OrderPayment;
use App\Models\OrderStatusHistory;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class OrderImportController extends Controller
{
    // Upload endpoint: save CSV and return metadata (total rows, path)
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        try {
            Storage::disk('public')->makeDirectory('imports');

            $path = $request->file('file')->store('imports', 'public');
            $fullPath = Storage::disk('public')->path($path);

            if (!file_exists($fullPath)) {
                return response()->json(['message' => 'Failed to store uploaded file.'], 500);
            }

            // Count rows (excluding header)
            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0);

            $total = 0;
            foreach ($csv as $row) {
                $total++;
            }

            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => $path,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            Log::error('Order upload failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process a chunk of order rows from the uploaded CSV.
     */
    public function processChunk(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'offset' => 'required|integer|min:0',
            'limit' => 'required|integer|min:1|max:200',
        ]);

        try {
            $path = $request->input('path');
            $offset = (int)$request->input('offset');
            $limit = (int)$request->input('limit');

            $fullPath = Storage::disk('public')->path($path);

            if (!file_exists($fullPath)) {
                return response()->json(['message' => 'CSV file not found on server.'], 404);
            }

            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0);

            $stmt = (new Statement())->offset($offset)->limit($limit);
            $records = $stmt->process($csv);

            $processed = 0;
            $errors = [];
            $currentRow = $offset + 1;

            foreach ($records as $index => $record) {
                try {
                    $this->processOrderRecord($record, $currentRow);
                    $processed++;
                    $currentRow++;
                } catch (\Exception $e) {
                    $errorMsg = "Row {$currentRow}: " . $e->getMessage();
                    Log::error('Order import row failed', ['error' => $e->getMessage(), 'record' => $record]);
                    $errors[] = $errorMsg;
                    $currentRow++;
                }
            }

            return response()->json([
                'processed' => $processed,
                'errors' => $errors,
                'message' => $processed > 0 ? "Processed {$processed} orders" : "No orders processed",
            ]);
        } catch (\Exception $e) {
            Log::error('Order chunk processing failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Chunk processing failed: ' . $e->getMessage(),
                'processed' => 0,
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Process a single order record from CSV
     */
    protected function processOrderRecord(array $record, int $currentRow): void
    {
        DB::beginTransaction();
        try {
            // Find or create customer
            $customer = $this->findOrCreateCustomer($record);

            // Generate or use existing order number
            $orderNumber = $this->generateOrderNumber($record);

            // Check if order already exists
            $existingOrder = Order::where('order_number', $orderNumber)->first();
            if ($existingOrder) {
                Log::info("Order {$orderNumber} already exists, skipping");
                DB::commit();
                return;
            }

            // Parse amounts
            $subtotal = $this->parseAmount($record['Order Subtotal Amount'] ?? $record['Cart Discount Amount'] ?? 0);
            $discountTotal = $this->parseAmount($record['Discount Amount'] ?? $record['Order Discount Amount'] ?? 0);
            $shippingTotal = $this->parseAmount($record['Order Shipping Amount'] ?? $record['Shipping Amount'] ?? 0);
            $taxTotal = $this->parseAmount($record['Order Tax Amount'] ?? $record['Tax Amount'] ?? 0);
            $grandTotal = $this->parseAmount($record['Order Total'] ?? $record['Total'] ?? 0);

            // Parse dates
            $createdAt = $this->parseDate($record['Order Date'] ?? $record['Date'] ?? now());
            $updatedAt = $this->parseDate($record['Order Modified Date'] ?? $record['Modified Date'] ?? $createdAt);

            // Get currency
            $currency = $this->getCurrency($record['Currency'] ?? 'USD');

            // Map WooCommerce status to our status system
            $status = $this->mapOrderStatus($record['Order Status'] ?? $record['Status'] ?? 'pending');
            $paymentStatus = $this->mapPaymentStatus($record['Payment Method'] ?? $record['Status'] ?? 'pending');

            // Create the order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer?->id,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'shipping_status' => $this->mapShippingStatus($status),
                'source' => 'online',
                'currency_id' => $currency->id,
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'shipping_total' => $shippingTotal,
                'grand_total' => $grandTotal,
                'notes' => $record['Customer Note'] ?? $record['Note'] ?? null,
                'admin_notes' => $this->generateAdminNotes($record),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'platform' => 'woocommerce',
                'external_id' => $record['Order ID'] ?? $record['ID'] ?? null,
            ]);

            // Process order items
            $this->processOrderItems($order, $record);

            // Process addresses
            $this->processAddresses($order, $record);

            // Process payments
            $this->processPayments($order, $record);

            // Record initial status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => null,
                'new_status' => $status,
                'changed_by' => 1, // Admin user
                'created_at' => $createdAt,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Find or create customer from order data
     */
    protected function findOrCreateCustomer(array $record): ?Customer
    {
        $email = $record['Billing Email'] ?? $record['Email'] ?? null;
        if (!$email) {
            return null;
        }

        // Try to find existing customer
        $customer = Customer::where('email', $email)->first();
        if ($customer) {
            return $customer;
        }

        // Create new customer
        $firstName = $record['Billing First Name'] ?? $record['First Name'] ?? '';
        $lastName = $record['Billing Last Name'] ?? $record['Last Name'] ?? '';

        return Customer::create([
            'name' => trim("{$firstName} {$lastName}"),
            'email' => $email,
            'phone' => $record['Billing Phone'] ?? $record['Phone'] ?? null,
            'platform' => 'woocommerce',
            'external_id' => $record['Customer ID'] ?? null,
        ]);
    }

    /**
     * Generate order number from record or create new one
     */
    protected function generateOrderNumber(array $record): string
    {
        if (!empty($record['Order Number'])) {
            return $record['Order Number'];
        }

        if (!empty($record['Order ID'])) {
            return 'WC-' . $record['Order ID'];
        }

        // Generate unique order number
        $prefix = 'ORD';
        $date = now()->format('Ymd');

        do {
            $number = $prefix . $date . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Process order items from CSV record
     */
    protected function processOrderItems(Order $order, array $record): void
    {
        // WooCommerce CSV often has multiple items in separate rows
        // For single-row per order format, we need to parse item data

        $itemName = $record['Item Name'] ?? $record['Product'] ?? null;
        if (!$itemName) {
            return;
        }

        $quantity = (int)($record['Quantity'] ?? 1);
        $price = $this->parseAmount($record['Item Price'] ?? $record['Price'] ?? 0);
        $total = $this->parseAmount($record['Item Total'] ?? $record['Total'] ?? $price * $quantity);

        // Try to find product by SKU or name
        $product = $this->findProduct($record);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product?->id,
            'sku' => $record['SKU'] ?? $product?->sku ?? 'N/A',
            'title' => $itemName,
            'price' => $price,
            'qty' => $quantity,
            'total' => $total,
            'raw_data' => $record,
        ]);
    }

    /**
     * Find product by SKU or name
     */
    protected function findProduct(array $record): ?Product
    {
        $sku = $record['SKU'] ?? null;
        if ($sku) {
            $product = Product::where('sku', $sku)->first();
            if ($product) {
                return $product;
            }
        }

        $productName = $record['Item Name'] ?? $record['Product'] ?? null;
        if ($productName) {
            return Product::where('title->en', 'like', "%{$productName}%")->first();
        }

        return null;
    }

    /**
     * Process order addresses
     */
    protected function processAddresses(Order $order, array $record): void
    {
        // Billing address
        if ($this->hasAddressData($record, 'billing')) {
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'billing',
                'first_name' => $record['Billing First Name'] ?? '',
                'last_name' => $record['Billing Last Name'] ?? '',
                'email' => $record['Billing Email'] ?? $record['Email'] ?? '',
                'phone' => $record['Billing Phone'] ?? '',
                'address1' => $record['Billing Address 1'] ?? '',
                'address2' => $record['Billing Address 2'] ?? '',
                'city' => $record['Billing City'] ?? '',
                'state' => $record['Billing State'] ?? '',
                'postal_code' => $record['Billing Postcode'] ?? $record['Billing Zip'] ?? '',
                'country' => $record['Billing Country'] ?? '',
            ]);
        }

        // Shipping address
        if ($this->hasAddressData($record, 'shipping')) {
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'first_name' => $record['Shipping First Name'] ?? $record['Billing First Name'] ?? '',
                'last_name' => $record['Shipping Last Name'] ?? $record['Billing Last Name'] ?? '',
                'phone' => $record['Shipping Phone'] ?? $record['Billing Phone'] ?? '',
                'address1' => $record['Shipping Address 1'] ?? '',
                'address2' => $record['Shipping Address 2'] ?? '',
                'city' => $record['Shipping City'] ?? '',
                'state' => $record['Shipping State'] ?? '',
                'postal_code' => $record['Shipping Postcode'] ?? $record['Shipping Zip'] ?? '',
                'country' => $record['Shipping Country'] ?? '',
            ]);
        }
    }

    /**
     * Check if address data exists in record
     */
    protected function hasAddressData(array $record, string $type): bool
    {
        $fields = ['First Name', 'Last Name', 'Address 1', 'City', 'Country'];
        foreach ($fields as $field) {
            $key = ucfirst($type) . ' ' . $field;
            if (!empty($record[$key])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Process order payments
     */
    protected function processPayments(Order $order, array $record): void
    {
        $paymentMethod = $record['Payment Method'] ?? $record['Payment Method Title'] ?? 'unknown';
        $transactionId = $record['Transaction ID'] ?? $record['Payment Transaction ID'] ?? null;

        if ($order->payment_status === 'paid' && $order->grand_total > 0) {
            OrderPayment::create([
                'order_id' => $order->id,
                'method' => $paymentMethod,
                'amount' => $order->grand_total,
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'created_at' => $order->created_at,
            ]);
        }
    }

    /**
     * Parse amount from string to decimal
     */
    protected function parseAmount($value): float
    {
        if (is_numeric($value)) {
            return (float)$value;
        }

        $value = (string)$value;
        $value = preg_replace('/[^\d.-]/', '', $value);

        return (float)$value;
    }

    /**
     * Parse date from various formats
     */
    protected function parseDate($dateString): Carbon
    {
        if (empty($dateString)) {
            return now();
        }

        try {
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: {$dateString}, using current time");
            return now();
        }
    }

    /**
     * Get currency by code
     */
    protected function getCurrency(string $currencyCode): Currency
    {
        return Currency::firstOrCreate(
            ['code' => strtoupper($currencyCode)],
            [
                'name' => $currencyCode,
                'symbol' => $this->getCurrencySymbol($currencyCode),
                'format' => '{symbol}{amount}',
                'exchange_rate' => 1.0,
                'is_active' => true,
            ]
        );
    }

    /**
     * Get currency symbol
     */
    protected function getCurrencySymbol(string $currencyCode): string
    {
        $symbols = [
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

        return $symbols[strtoupper($currencyCode)] ?? $currencyCode;
    }

    /**
     * Map WooCommerce order status to our status system
     */
    protected function mapOrderStatus(string $wcStatus): string
    {
        $statusMap = [
            'pending' => 'pending',
            'processing' => 'processing',
            'on-hold' => 'confirmed',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
            'failed' => 'cancelled',
            'trash' => 'cancelled',
        ];

        $wcStatus = strtolower($wcStatus);
        return $statusMap[$wcStatus] ?? 'pending';
    }

    /**
     * Map WooCommerce payment status
     */
    protected function mapPaymentStatus(string $paymentMethod): string
    {
        // This is simplified - you might want to map based on WooCommerce status
        $paidMethods = ['paid', 'completed', 'processing', 'cod', 'bacs', 'cheque'];

        if (in_array(strtolower($paymentMethod), $paidMethods)) {
            return 'paid';
        }

        return 'pending';
    }

    /**
     * Map shipping status based on order status
     */
    protected function mapShippingStatus(string $orderStatus): string
    {
        $shippingMap = [
            'processing' => 'ready_for_shipment',
            'shipped' => 'shipped',
            'delivered' => 'delivered',
            'completed' => 'delivered',
        ];

        return $shippingMap[$orderStatus] ?? 'pending';
    }

    /**
     * Generate admin notes from order data
     */
    protected function generateAdminNotes(array $record): string
    {
        $notes = [];

        if (!empty($record['Payment Method'])) {
            $notes[] = "Payment Method: " . $record['Payment Method'];
        }

        if (!empty($record['Shipping Method'])) {
            $notes[] = "Shipping Method: " . $record['Shipping Method'];
        }

        if (!empty($record['Customer Message'])) {
            $notes[] = "Customer Message: " . $record['Customer Message'];
        }

        return implode("\n", $notes);
    }

    /**
     * Cleanup import files after completion
     */
    public function cleanupImportFiles(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $csvPath = $request->input('path');

            // Delete the CSV file
            if (Storage::disk('public')->exists($csvPath)) {
                Storage::disk('public')->delete($csvPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Import files deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete import files: ' . $e->getMessage()
            ], 500);
        }
    }
}
