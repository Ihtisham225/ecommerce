<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderInvoiceController extends Controller
{
    /**
     * Generate and download PDF invoice
     */
    public function pdf(Order $order)
    {
        $currencySymbol = $this->getCurrencySymbol($order);
        $decimals = $this->getCurrencyDecimals($order);
        
        $pdf = Pdf::loadView('admin.orders.invoices.invoice-pdf', [
            'order' => $order,
            'currencySymbol' => $currencySymbol,
            'decimals' => $decimals,
        ]);
        
        $filename = "invoice-{$order->order_number}.pdf";
        
        return $pdf->stream($filename);
    }

    /**
     * Generate thermal printer version (80mm width)
     */
    public function thermal(Order $order)
    {
        $currencySymbol = $this->getCurrencySymbol($order);
        $decimals = $this->getCurrencyDecimals($order);
        
        $pdf = Pdf::loadView('admin.orders.invoices.invoice-thermal', [
            'order' => $order,
            'currencySymbol' => $currencySymbol,
            'decimals' => $decimals,
        ])->setPaper([0, 0, 226.77, 1000]); // 80mm width
        
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="receipt-' . $order->order_number . '.pdf"');
    }

    /**
     * Get currency symbol for order
     */
    private function getCurrencySymbol($order)
    {
        $currencyCode = $order->currency->code ?? 'KWD';
        $currencySymbols = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'KWD' => 'KD',
        ];
        
        return $currencySymbols[$currencyCode] ?? $currencyCode;
    }

    /**
     * Get decimal places for currency
     */
    private function getCurrencyDecimals($order)
    {
        $currencyCode = $order->currency->code ?? 'KWD';
        return $currencyCode === 'KWD' ? 3 : 2;
    }
}