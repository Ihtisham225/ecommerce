<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * List customers + DataTable AJAX
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $customers = Customer::query()
                ->withCount('orders')
                ->select(['id', 'first_name', 'last_name', 'email', 'phone', 'is_guest', 'created_at'])
                ->latest();

            /** -------------------------------------------------
             *  SEARCH FILTER
             * -------------------------------------------------*/
            if ($request->filled('search')) {
                $search = $request->search;
                $customers->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            /** -------------------------------------------------
             *  TYPE FILTER (registered / guest)
             * -------------------------------------------------*/
            if ($request->filled('type')) {
                if ($request->type === 'registered') {
                    $customers->where('is_guest', 0);
                }
                if ($request->type === 'guest') {
                    $customers->where('is_guest', 1);
                }
            }

            /** -------------------------------------------------
             *  DATE FILTER
             * -------------------------------------------------*/
            if ($request->filled('date_range')) {
                switch ($request->date_range) {
                    case 'today':
                        $customers->whereDate('created_at', today());
                        break;

                    case 'yesterday':
                        $customers->whereDate('created_at', today()->subDay());
                        break;

                    case 'week':
                        $customers->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;

                    case 'month':
                        $customers->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year);
                        break;

                    case 'year':
                        $customers->whereYear('created_at', now()->year);
                        break;
                }
            }

            /** -------------------------------------------------
             *  RENDER DATATABLE
             * -------------------------------------------------*/
            return DataTables::of($customers)
                ->addColumn('full_name', fn($row) => e($row->full_name))
                ->addColumn('guest', fn($row) => 
                    $row->is_guest
                        ? '<span class="text-red-600">Guest</span>'
                        : '<span class="text-green-600">Registered</span>'
                )
                ->addColumn('orders_count', fn($row) => $row->orders_count)
                ->addColumn('actions', function ($row) {
                    $showUrl = route('admin.customers.show', $row);
                    $editUrl = route('admin.customers.edit', $row);

                    return <<<HTML
                        <div class="flex gap-2 justify-center">
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
                ->editColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d'))
                ->rawColumns(['guest', 'actions'])
                ->make(true);
        }

        return view('admin.customers.index');
    }

    /**
     * Create new customer
     */
    public function create()
    {
        // Create a new customer with default values
        $customer = Customer::create([
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'is_guest' => false
        ]);

        return redirect()->route('admin.customers.edit', $customer->id);
    }

    /**
     * Show single customer details
     */
    public function show(Customer $customer)
    {
        // Load all required relations
        $customer->load([
            'addresses',
            'orders.items.product',
            'orders.payments',
            'orders.addresses',
            'user'
        ]);

        // Get store setting for currency
        $storeSetting = \App\Models\StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        
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
        
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        return view('admin.customers.show', compact(
            'customer',
            'currencySymbol'
        ));
    }

    /**
     * Edit customer
     */
    public function edit(Customer $customer)
    {
        $customer->load([
            'addresses',
            'orders.items.product',
            'orders.payments',
            'user'
        ]);

        // Get countries for dropdown
        $countries = config('countries', []); // Assuming you have a countries config

        // Get currency for order totals display
        $storeSetting = \App\Models\StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        
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
        
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        return view('admin.customers.form', compact(
            'customer',
            'countries',
            'currencySymbol'
        ));
    }

    /**
     * Autosave customer with comprehensive validation and address management
     */
    public function autoSave(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            // Customer basic info
            'first_name' => 'required|string|max:120',
            'last_name'  => 'required|string|max:120',
            'phone'      => 'nullable|string|max:30',
            'email'      => 'nullable|email|max:255',
            'is_guest'   => 'boolean',
            
            // Addresses - simplified for minimal schema
            'addresses' => 'nullable|array',
            'addresses.*.id' => 'nullable|exists:addresses,id',
            'addresses.*.type' => 'required|in:shipping,billing',
            'addresses.*.address_line_1' => 'required|string|max:255',
            'addresses.*.address_line_2' => 'nullable|string|max:255',
            'addresses.*.is_default' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $oldEmail = $customer->email;
            $oldPhone = $customer->phone;

            // Duplicate check but ignore current customer
            $existing = Customer::where('first_name', $validated['first_name'])
                ->where('last_name', $validated['last_name'])
                ->where('phone', $validated['phone'])
                ->where('id', '!=', $customer->id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'duplicate' => true,
                    'message' => 'Another customer with same details already exists.',
                    'customer' => $existing,
                ], 422);
            }

            // Update customer basic info
            $customer->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_guest' => $validated['is_guest'] ?? false,
            ]);

            // Update addresses
            if (isset($validated['addresses'])) {
                $this->updateAddresses($customer, $validated['addresses']);
            }

            // If customer was converted from guest to registered, handle user creation
            if (!$oldEmail && $validated['email'] && !$customer->is_guest) {
                $this->handleUserCreation($customer);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.',
                'customer' => $customer->fresh(['addresses', 'orders', 'user'])
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     /**
     * Update customer addresses for minimal schema
     */
    private function updateAddresses(Customer $customer, array $addresses)
    {
        $existingIds = [];

        foreach ($addresses as $addr) {

            $clean = [
                'type' => $addr['type'],
                'address_line_1' => $addr['address_line_1'],
                'address_line_2' => $addr['address_line_2'] ?? null,
                'is_default' => $addr['is_default'] ?? false,
            ];

            if (!empty($addr['id'])) {
                // update existing
                $address = Address::where('id', $addr['id'])
                    ->where('addressable_id', $customer->id)
                    ->where('addressable_type', Customer::class)
                    ->first();

                if ($address) {
                    $address->update($clean);
                    $existingIds[] = $address->id;
                }
            } else {
                // create new
                $address = new Address($clean);
                $customer->addresses()->save($address);   // <--- important fix
                $existingIds[] = $address->id;
            }
        }

        // delete addresses not included
        $customer->addresses()
            ->whereNotIn('id', $existingIds)
            ->delete();

        $this->normalizeDefaultAddresses($customer);
    }

    /**
     * Ensure only one default address per type (shipping/billing)
     */
    private function normalizeDefaultAddresses(Customer $customer)
    {
        $addresses = $customer->addresses;
        
        // Handle shipping addresses
        $defaultShippingCount = $addresses->where('type', 'shipping')
                                         ->where('is_default', true)
                                         ->count();
        
        if ($defaultShippingCount > 1) {
            // Keep only the first shipping address as default
            $firstDefaultShipping = $addresses->where('type', 'shipping')
                                             ->where('is_default', true)
                                             ->first();
            
            $customer->addresses()
                ->where('type', 'shipping')
                ->where('is_default', true)
                ->where('id', '!=', $firstDefaultShipping->id)
                ->update(['is_default' => false]);
        } elseif ($defaultShippingCount === 0 && $addresses->where('type', 'shipping')->count() > 0) {
            // Set first shipping address as default if none exists
            $customer->addresses()
                ->where('type', 'shipping')
                ->first()
                ->update(['is_default' => true]);
        }
        
        // Handle billing addresses
        $defaultBillingCount = $addresses->where('type', 'billing')
                                        ->where('is_default', true)
                                        ->count();
        
        if ($defaultBillingCount > 1) {
            // Keep only the first billing address as default
            $firstDefaultBilling = $addresses->where('type', 'billing')
                                            ->where('is_default', true)
                                            ->first();
            
            $customer->addresses()
                ->where('type', 'billing')
                ->where('is_default', true)
                ->where('id', '!=', $firstDefaultBilling->id)
                ->update(['is_default' => false]);
        } elseif ($defaultBillingCount === 0 && $addresses->where('type', 'billing')->count() > 0) {
            // Set first billing address as default if none exists
            $customer->addresses()
                ->where('type', 'billing')
                ->first()
                ->update(['is_default' => true]);
        }
    }

    /**
     * Set default shipping address
     */
    public function setDefaultShipping(Customer $customer, Address $address)
    {
        // Reset all other shipping defaults
        $customer->addresses()->update(['is_default_shipping' => false]);
        
        // Set this address as default shipping
        $address->update(['is_default_shipping' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Default shipping address updated.'
        ]);
    }

    /**
     * Set default billing address
     */
    public function setDefaultBilling(Customer $customer, Address $address)
    {
        // Reset all other billing defaults
        $customer->addresses()->update(['is_default_billing' => false]);
        
        // Set this address as default billing
        $address->update(['is_default_billing' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Default billing address updated.'
        ]);
    }

    /**
     * Get customer addresses with defaults
     */
    public function getAddresses(Customer $customer)
    {
        $addresses = $customer->addresses;
        $defaultShipping = $customer->defaultShipping();
        $defaultBilling = $customer->defaultBilling();
        
        return response()->json([
            'addresses' => $addresses,
            'default_shipping' => $defaultShipping,
            'default_billing' => $defaultBilling
        ]);
    }

    /**
     * Handle user creation when guest becomes registered customer
     */
    private function handleUserCreation(Customer $customer)
    {
        // Check if user already exists with this email
        $existingUser = User::where('email', $customer->email)->first();
        
        if ($existingUser) {
            // Link existing user to customer
            $customer->update(['user_id' => $existingUser->id]);
        } else {
            $password = bcrypt(Str::random(12));
            // Create new user account
            $user = User::create([
                'name' => $customer->full_name,
                'email' => $customer->email,
                'password' => $password, // Random password, user can reset
                'user_password' => $password, // Random password, user can reset
                'email_verified_at' => now(),
            ]);
            
            $customer->update(['user_id' => $user->id]);
        }
    }

    /**
     * Bulk actions (delete)
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:delete',
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:customers,id'
        ]);

        if ($validated['action'] === 'delete') {
            Customer::whereIn('id', $validated['ids'])->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Customers updated successfully.'
        ]);
    }

    /**
     * Delete customer (soft delete)
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->back()->with('success', 'Customer Deleted Successfully');
    }

    // AJAX search endpoint for Alpine component
    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $customers = Customer::query()
            ->where('first_name', 'like', "%{$q}%")
            ->orWhere('last_name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->orderBy('first_name')
            ->limit(15)
            ->get();

        return response()->json(['customers' => $customers]);
    }

    // Create customer (normal or guest)
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name'  => 'required|string|max:120',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'is_guest'   => 'boolean'
        ]);

        // Check if customer already exists
        $customer = Customer::where('first_name', $validated['first_name'])
                            ->where('last_name', $validated['last_name'])
                            ->where('phone', $validated['phone'] ?? null)
                            ->first();

        // If not exists, create new
        if (!$customer) {
            $customer = Customer::create($validated);
        }

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

}
