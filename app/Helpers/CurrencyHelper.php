<?php

namespace App\Helpers;

use App\Models\StoreSetting;

class CurrencyHelper
{
    private static $currencySymbols = [
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

    /**
     * Get currency symbol from store settings
     */
    public static function getCurrencySymbol()
    {
        try {
            $storeSetting = StoreSetting::first();
            $currencyCode = $storeSetting?->currency_code ?? 'USD';
            return self::$currencySymbols[$currencyCode] ?? $currencyCode;
        } catch (\Exception $e) {
            return '$'; // Fallback
        }
    }

    /**
     * Get currency code from store settings
     */
    public static function getCurrencyCode()
    {
        try {
            $storeSetting = StoreSetting::first();
            return $storeSetting?->currency_code ?? 'USD';
        } catch (\Exception $e) {
            return 'USD'; // Fallback
        }
    }

    /**
     * Get decimal places based on currency
     */
    public static function getDecimalPlaces($currencyCode = null)
    {
        $currencyCode = $currencyCode ?? self::getCurrencyCode();
        return $currencyCode === 'KWD' ? 3 : 2;
    }

    /**
     * Format currency amount
     */
    public static function format($amount, $decimals = null)
    {
        $symbol = self::getCurrencySymbol();
        $currencyCode = self::getCurrencyCode();
        $decimals = $decimals ?? self::getDecimalPlaces($currencyCode);
        
        // Format number with proper thousands separator
        $formattedAmount = number_format((float) $amount, $decimals);
        
        // Return formatted string (symbol before amount for most currencies)
        return $symbol . ' ' . $formattedAmount;
    }

    /**
     * Format currency amount with code
     */
    public static function formatWithCode($amount)
    {
        $symbol = self::getCurrencySymbol();
        $currencyCode = self::getCurrencyCode();
        $decimals = self::getDecimalPlaces($currencyCode);
        
        $formattedAmount = number_format((float) $amount, $decimals);
        
        return $symbol . ' ' . $formattedAmount . ' (' . $currencyCode . ')';
    }
}