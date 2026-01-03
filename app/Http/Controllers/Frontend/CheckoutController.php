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
    private $currencies;
    private $user;
    private $storeSetting;

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
        return view('admin.store-settings.index', [
            'storeSetting' => $this->storeSetting,
            'currencies' => $this->currencies
        ]);
    }

    // Show specific section
    public function showSection($section)
    {
        $validSections = [
            'store-info', 'store-address', 'shipping-methods', 
            'payment-methods', 'bank-details', 'tax-settings',
            'notification-settings', 'store-hours'
        ];

        if (!in_array($section, $validSections)) {
            abort(404);
        }

        $view = "admin.store-settings.partials.{$section}";

        return view($view, [
            'storeSetting' => $this->storeSetting,
            'currencies' => $this->currencies
        ]);
    }

    // Update specific section
    public function updateSection(Request $request, $section)
    {
        try {
            $validationRules = $this->getValidationRules($section);
            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $settings = $this->storeSetting->settings ?? [];

            switch ($section) {
                case 'store-info':
                    $this->updateStoreInfo($data);
                    break;
                    
                case 'store-address':
                    $settings['store_address'] = $request->input('store_address', []);
                    break;
                    
                case 'shipping-methods':
                    $settings['shipping_methods'] = $request->input('shipping_methods', []);
                    break;
                    
                case 'payment-methods':
                    $paymentMethods = [];
                    foreach ($request->input('payment_methods', []) as $method) {
                        $paymentMethods[] = [
                            'name' => $method,
                            'is_active' => true
                        ];
                    }
                    $settings['payment_methods'] = $paymentMethods;
                    break;
                    
                case 'bank-details':
                    $settings['bank_details'] = $request->input('bank_details', []);
                    break;
                    
                case 'tax-settings':
                    $settings['tax_settings'] = $request->input('tax_settings', []);
                    break;
                    
                case 'notification-settings':
                    $settings['notification_settings'] = $request->input('notification_settings', []);
                    break;
                    
                case 'store-hours':
                    $settings['store_hours'] = $request->input('store_hours', []);
                    break;
            }

            // Update settings in database
            $this->storeSetting->settings = $settings;
            $this->storeSetting->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('-', ' ', $section)) . ' updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete logo
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

    // Private helper methods
    private function updateStoreInfo($data)
    {
        // Handle logo upload
        if (request()->hasFile('logo')) {
            // Delete old logo if exists
            if ($this->storeSetting->logo && Storage::disk('public')->exists($this->storeSetting->logo)) {
                Storage::disk('public')->delete($this->storeSetting->logo);
            }
            
            $data['logo'] = request()->file('logo')->store('store-logos', 'public');
        } else {
            $data['logo'] = $this->storeSetting->logo;
        }

        $this->storeSetting->update([
            'store_name' => $data['store_name'],
            'store_email' => $data['store_email'],
            'store_phone' => $data['store_phone'] ?? null,
            'currency_code' => $data['currency_code'],
            'timezone' => $data['timezone'],
            'logo' => $data['logo']
        ]);
    }

    private function getValidationRules($section)
    {
        $rules = [];

        switch ($section) {
            case 'store-info':
                $rules = [
                    'store_name' => 'required|string|max:255',
                    'store_email' => 'required|email|max:255',
                    'store_phone' => 'nullable|string|max:20',
                    'currency_code' => 'required|string|size:3',
                    'timezone' => 'required|string|max:100',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ];
                break;
                
            case 'store-address':
                $rules = [
                    'store_address' => 'nullable|array',
                    'store_address.address_line1' => 'nullable|string|max:255',
                    'store_address.address_line2' => 'nullable|string|max:255',
                    'store_address.city' => 'nullable|string|max:100',
                    'store_address.state' => 'nullable|string|max:100',
                    'store_address.country' => 'nullable|string|max:100',
                    'store_address.postal_code' => 'nullable|string|max:20',
                ];
                break;
                
            case 'shipping-methods':
                $rules = [
                    'shipping_methods' => 'nullable|array',
                    'shipping_methods.*.name' => 'required|string|max:100',
                    'shipping_methods.*.cost' => 'required|numeric|min:0',
                    'shipping_methods.*.description' => 'nullable|string|max:255',
                    'shipping_methods.*.is_active' => 'nullable|boolean',
                ];
                break;
                
            case 'payment-methods':
                $rules = [
                    'payment_methods' => 'nullable|array',
                    'payment_methods.*' => 'string|max:50',
                ];
                break;
                
            case 'bank-details':
                $rules = [
                    'bank_details' => 'nullable|array',
                    'bank_details.bank_name' => 'nullable|string|max:100',
                    'bank_details.account_name' => 'nullable|string|max:100',
                    'bank_details.account_number' => 'nullable|string|max:50',
                    'bank_details.iban' => 'nullable|string|max:34',
                    'bank_details.swift_code' => 'nullable|string|max:11',
                    'bank_details.branch_name' => 'nullable|string|max:100',
                    'bank_details.branch_code' => 'nullable|string|max:20',
                ];
                break;
                
            case 'tax-settings':
                $rules = [
                    'tax_settings' => 'nullable|array',
                    'tax_settings.tax_enabled' => 'nullable|boolean',
                    'tax_settings.tax_rate' => 'nullable|numeric|min:0|max:100',
                    'tax_settings.tax_inclusive' => 'nullable|boolean',
                ];
                break;
                
            case 'notification-settings':
                $rules = [
                    'notification_settings' => 'nullable|array',
                    'notification_settings.email_notifications' => 'nullable|boolean',
                    'notification_settings.order_confirmations' => 'nullable|boolean',
                    'notification_settings.low_stock_alerts' => 'nullable|boolean',
                ];
                break;
                
            case 'store-hours':
                $rules = [
                    'store_hours' => 'nullable|array',
                    'store_hours.*.day' => 'nullable|string',
                    'store_hours.*.open' => 'nullable|string',
                    'store_hours.*.close' => 'nullable|string',
                    'store_hours.*.is_closed' => 'nullable|boolean',
                ];
                break;
        }

        return $rules;
    }

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
            'LBP' => ['name' => 'Lebanese Pound', 'symbol' => 'ل.ل'],
            'EGP' => ['name' => 'Egyptian Pound', 'symbol' => 'ج.م'],
            'PKR' => ['name' => 'Pakistani Rupee', 'symbol' => '₨'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'BDT' => ['name' => 'Bangladeshi Taka', 'symbol' => '৳'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => '$'],
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => '$'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
            'KRW' => ['name' => 'South Korean Won', 'symbol' => '₩'],
        ];
    }
}