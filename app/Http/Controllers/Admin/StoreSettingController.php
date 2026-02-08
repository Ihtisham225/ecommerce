<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreSettingController extends Controller
{
    private $user;
    private $storeSetting;
    private $currencies;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->storeSetting = StoreSetting::where('user_id', $this->user->id)->first();
            
            if (!$this->storeSetting) {
                $this->storeSetting = StoreSetting::create([
                    'user_id' => $this->user->id,
                    'store_name' => $this->user->name . "'s Store",
                    'store_email' => $this->user->email,
                    'currency_code' => 'KWD',
                    'timezone' => config('app.timezone'),
                    'settings' => $this->getDefaultSettings()
                ]);
            }
            
            $this->currencies = $this->getCurrencies();
            
            return $next($request);
        });
    }

    // Main index page
    public function index()
    {
        return redirect()->route('admin.store-settings.store-info');
    }

    // ==================== STORE INFO ====================
    public function showStoreInfo()
    {
        if (request()->ajax()) {
            return response()->json([
                'store_name' => $this->storeSetting->store_name,
                'store_email' => $this->storeSetting->store_email,
                'store_phone' => $this->storeSetting->store_phone,
                'currency_code' => $this->storeSetting->currency_code,
                'timezone' => $this->storeSetting->timezone,
                'logo' => $this->storeSetting->logo
            ]);
        }

        return view('admin.store-settings.sections.store-info', [
            'storeSetting' => $this->storeSetting,
            'currencies' => $this->currencies
        ]);
    }

    public function updateStoreInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|string|max:255',
            'store_email' => 'required|email|max:255',
            'store_phone' => 'nullable|string|max:20',
            'currency_code' => 'required|string|size:3',
            'timezone' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($this->storeSetting->logo && Storage::disk('public')->exists($this->storeSetting->logo)) {
                    Storage::disk('public')->delete($this->storeSetting->logo);
                }
                
                $data['logo'] = $request->file('logo')->store('store-logos', 'public');
            } else {
                $data['logo'] = $this->storeSetting->logo;
            }

            // Update store settings
            $this->storeSetting->update([
                'store_name' => $data['store_name'],
                'store_email' => $data['store_email'],
                'store_phone' => $data['store_phone'] ?? null,
                'currency_code' => $data['currency_code'],
                'timezone' => $data['timezone'],
                'logo' => $data['logo']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Store information updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update store info: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteLogo()
    {
        try {
            if ($this->storeSetting->logo && Storage::disk('public')->exists($this->storeSetting->logo)) {
                Storage::disk('public')->delete($this->storeSetting->logo);
                $this->storeSetting->update(['logo' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Logo deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete logo: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== STORE ADDRESS ====================
    public function showStoreAddress()
    {
        if (request()->ajax()) {
            $address = $this->storeSetting->settings['store_address'] ?? [];
            
            return response()->json([
                'address_line1' => $address['address_line1'] ?? '',
                'address_line2' => $address['address_line2'] ?? '',
                'city' => $address['city'] ?? '',
                'state' => $address['state'] ?? '',
                'country' => $address['country'] ?? '',
                'postal_code' => $address['postal_code'] ?? '',
            ]);
        }

        return view('admin.store-settings.sections.store-address', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updateStoreAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['store_address'] = $data;
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Store address updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update store address: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== SHIPPING METHODS ====================
    public function showShippingMethods()
    {
        if (request()->ajax()) {
            $shippingMethods = $this->storeSetting->settings['shipping_methods'] ?? [];
            
            return response()->json($shippingMethods);
        }

        return view('admin.store-settings.sections.shipping-methods', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updateShippingMethods(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_methods' => 'nullable|array',
            'shipping_methods.*.name' => 'required|string|max:100',
            'shipping_methods.*.cost' => 'required|numeric|min:0',
            'shipping_methods.*.description' => 'nullable|string|max:255',
            'shipping_methods.*.is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $shippingMethods = $request->input('shipping_methods', []);
            
            // Process shipping methods to ensure boolean values
            foreach ($shippingMethods as &$method) {
                $method['is_active'] = isset($method['is_active']) && $method['is_active'] == '1';
            }
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['shipping_methods'] = $shippingMethods;
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Shipping methods updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shipping methods: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== PAYMENT METHODS ====================
    public function showPaymentMethods()
    {
        if (request()->ajax()) {
            $paymentMethods = $this->storeSetting->settings['payment_methods'] ?? [];
            
            return response()->json([
                'payment_methods' => $paymentMethods
            ]);
        }

        return view('admin.store-settings.sections.payment-methods', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updatePaymentMethods(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $selectedMethods = $request->input('payment_methods', []);
            $paymentMethods = [];
            
            // Format payment methods
            foreach ($selectedMethods as $method) {
                $paymentMethods[] = [
                    'name' => $method,
                    'is_active' => true
                ];
            }
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['payment_methods'] = $paymentMethods;
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment methods updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment methods: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== BANK DETAILS ====================
    public function showBankDetails()
    {
        if (request()->ajax()) {
            $bankDetails = $this->storeSetting->settings['bank_details'] ?? [];
            
            return response()->json($bankDetails);
        }

        return view('admin.store-settings.sections.bank-details', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updateBankDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:34',
            'swift_code' => 'nullable|string|max:11',
            'branch_name' => 'nullable|string|max:100',
            'branch_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['bank_details'] = $data;
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank details: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== TAX SETTINGS ====================
    public function showTaxSettings()
    {
        if (request()->ajax()) {
            $taxSettings = $this->storeSetting->settings['tax_settings'] ?? [
                'tax_enabled' => false,
                'tax_rate' => 0,
                'tax_inclusive' => false
            ];
            
            return response()->json($taxSettings);
        }

        return view('admin.store-settings.sections.tax-settings', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updateTaxSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_enabled' => 'nullable|boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_inclusive' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['tax_settings'] = [
                'tax_enabled' => isset($data['tax_enabled']) && $data['tax_enabled'] == '1',
                'tax_rate' => $data['tax_rate'] ?? 0,
                'tax_inclusive' => isset($data['tax_inclusive']) && $data['tax_inclusive'] == '1',
            ];
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Tax settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax settings: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== NOTIFICATION SETTINGS ====================
    public function showNotificationSettings()
    {
        if (request()->ajax()) {
            $notificationSettings = $this->storeSetting->settings['notification_settings'] ?? [
                'email_notifications' => true,
                'order_confirmations' => true,
                'low_stock_alerts' => true
            ];
            
            return response()->json($notificationSettings);
        }

        return view('admin.store-settings.sections.notification-settings', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updateNotificationSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'nullable|boolean',
            'order_confirmations' => 'nullable|boolean',
            'low_stock_alerts' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['notification_settings'] = [
                'email_notifications' => isset($data['email_notifications']) && $data['email_notifications'] == '1',
                'order_confirmations' => isset($data['order_confirmations']) && $data['order_confirmations'] == '1',
                'low_stock_alerts' => isset($data['low_stock_alerts']) && $data['low_stock_alerts'] == '1',
            ];
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Notification settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification settings: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== STORE HOURS ====================
    public function showStoreHours()
    {
        if (request()->ajax()) {
            $storeHours = $this->storeSetting->settings['store_hours'] ?? $this->getDefaultStoreHours();
            
            return response()->json($storeHours);
        }

        return view('admin.store-settings.sections.store-hours', [
            'storeSetting' => $this->storeSetting
        ]);
    }

    public function updateStoreHours(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_hours' => 'nullable|array',
            'store_hours.*.day' => 'nullable|string',
            'store_hours.*.open' => 'nullable|string',
            'store_hours.*.close' => 'nullable|string',
            'store_hours.*.is_closed' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $storeHours = $request->input('store_hours', []);
            
            // Process store hours to ensure boolean values
            foreach ($storeHours as &$day) {
                $day['is_closed'] = isset($day['is_closed']) && $day['is_closed'] == '1';
            }
            
            // Get current settings
            $settings = $this->storeSetting->settings ?? [];
            $settings['store_hours'] = $storeHours;
            
            // Update store settings
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => 'Store hours updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update store hours: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== HELPER METHODS ====================
    private function getDefaultSettings()
    {
        return [
            'shipping_methods' => [
                [
                    'name' => 'Standard Shipping',
                    'cost' => 5.00,
                    'description' => 'Delivery within 5-7 business days',
                    'is_active' => true
                ],
                [
                    'name' => 'Express Shipping',
                    'cost' => 10.00,
                    'description' => 'Delivery within 1-2 business days',
                    'is_active' => true
                ]
            ],
            'payment_methods' => [
                ['name' => 'cash_on_delivery', 'is_active' => true],
                ['name' => 'credit_card', 'is_active' => true],
                ['name' => 'bank_transfer', 'is_active' => true]
            ],
            'bank_details' => [],
            'store_address' => [],
            'tax_settings' => [
                'tax_enabled' => false,
                'tax_rate' => 0,
                'tax_inclusive' => false
            ],
            'notification_settings' => [
                'email_notifications' => true,
                'order_confirmations' => true,
                'low_stock_alerts' => true
            ],
            'store_hours' => $this->getDefaultStoreHours()
        ];
    }

    private function getDefaultStoreHours()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $storeHours = [];
        
        foreach ($days as $day) {
            $storeHours[$day] = [
                'day' => $day,
                'open' => $day === 'friday' ? '10:00' : '09:00',
                'close' => $day === 'friday' ? '18:00' : '17:00',
                'is_closed' => $day === 'sunday'
            ];
        }
        
        return $storeHours;
    }

    private function getCurrencies()
    {
        return [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'KWD' => ['name' => 'Kuwaiti Dinar', 'symbol' => 'KD'],
            'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            'SAR' => ['name' => 'Saudi Riyal', 'symbol' => 'ر.س'],
            'QAR' => ['name' => 'Qatari Riyal', 'symbol' => 'ر.ق'],
            'OMR' => ['name' => 'Omani Rial', 'symbol' => 'ر.ع.'],
            'BHD' => ['name' => 'Bahraini Dinar', 'symbol' => '.د.ب'],
            'JOD' => ['name' => 'Jordanian Dinar', 'symbol' => 'د.ا'],
            'PKR' => ['name' => 'Pakistani Rupee', 'symbol' => '₨'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
        ];
    }
}