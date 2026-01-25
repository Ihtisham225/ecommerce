<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('format_currency')) {
    /**
     * Format currency with store settings
     *
     * @param float $amount
     * @param int|null $decimals
     * @return string
     */
    function format_currency($amount, $decimals = null)
    {
        return CurrencyHelper::format($amount, $decimals);
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * Get currency symbol from store settings
     *
     * @return string
     */
    function get_currency_symbol()
    {
        return CurrencyHelper::getCurrencySymbol();
    }
}

if (!function_exists('get_currency_code')) {
    /**
     * Get currency code from store settings
     *
     * @return string
     */
    function get_currency_code()
    {
        return CurrencyHelper::getCurrencyCode();
    }
}