<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderInvoiceController extends Controller
{
    /**
     * Generate and download PDF invoice
     */
    public function pdf(Order $order)
    {
        $currencySymbol = $this->getCurrencySymbol();
        $decimals = $this->getCurrencyDecimals();
        $storeSetting = $this->getStoreSettings();
        
        $pdf = Pdf::loadView('admin.orders.invoices.invoice-pdf', [
            'order' => $order,
            'currencySymbol' => $currencySymbol,
            'decimals' => $decimals,
            'storeSetting' => $storeSetting,
        ]);
        
        $filename = "invoice-{$order->order_number}.pdf";
        
        return $pdf->stream($filename);
    }

    /**
     * Generate thermal printer version (80mm width)
     */
    public function thermal(Order $order)
    {
        $currencySymbol = $this->getCurrencySymbol();
        $decimals = $this->getCurrencyDecimals();
        $storeSetting = $this->getStoreSettings();
        
        $pdf = Pdf::loadView('admin.orders.invoices.invoice-thermal', [
            'order' => $order,
            'currencySymbol' => $currencySymbol,
            'decimals' => $decimals,
            'storeSetting' => $storeSetting,
        ])->setPaper([0, 0, 226.77, 1000]); // 80mm width
        
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="receipt-' . $order->order_number . '.pdf"');
    }

    /**
     * Get store settings from vendor
     */
    private function getStoreSettings()
    {
        $vendor = StoreSetting::first();
        
        if ($vendor) {
            // Get settings from vendor model
            $settings = $vendor->settings ?? [];
            
            return (object) [
                'store_name' => $vendor->store_name ?? 'My Store',
                'store_email' => $vendor->store_email ?? 'store@example.com',
                'store_phone' => $vendor->store_phone ?? '+1234567890',
                'settings' => $settings,
                // You can add more settings from vendor here
                'store_address' => $settings['store_address'] ?? null,
                'store_logo' => $vendor->logo,
                'store_banner' => $vendor->banner,
                'tax_number' => $settings['tax_number'] ?? null,
                'bank_details' => $settings['bank_details'] ?? [],
            ];
        }
        
        // Return default settings if no vendor found
        return (object) [
            'store_name' => 'My Store',
            'store_email' => 'store@example.com',
            'store_phone' => '+1234567890',
            'settings' => [],
            'store_address' => null,
            'store_logo' => null,
            'store_banner' => null,
            'tax_number' => null,
            'bank_details' => [],
        ];
    }

    /**
     * Get currency symbol for order
     */
    private function getCurrencySymbol()
    {
        $vendor = StoreSetting::first();
        
        if ($vendor && $vendor->currency) {
            return $vendor->currency_symbol;
        }
        
        // Fallback to order currency
        $currencyCode = $order->currency->code ?? 'KWD';
        $currencySymbols = [
            'USD' => '$', 
            'EUR' => '€', 
            'GBP' => '£', 
            'KWD' => 'KD',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'PKR' => '₨',
            'INR' => '₹',
            'CAD' => '$',
            'AUD' => '$',
        ];
        
        return $currencySymbols[$currencyCode] ?? $currencyCode;
    }

    /**
     * Get decimal places for currency
     */
    private function getCurrencyDecimals()
    {
         $vendor = StoreSetting::first();
        
        if ($vendor && $vendor->currency) {
            $currencyCode = $vendor->currency_code ?? 'KWD';
        } else {
            $currencyCode = $order->currency->code ?? 'KWD';
        }
        
        // Currencies with 3 decimal places
        $threeDecimalCurrencies = ['KWD', 'BHD', 'OMR', 'JOD'];
        
        return in_array($currencyCode, $threeDecimalCurrencies) ? 3 : 2;
    }
}