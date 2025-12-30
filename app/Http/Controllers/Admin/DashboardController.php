<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Last 30 days labels
        $labels = collect(range(29, 0))->map(fn ($i) =>
            Carbon::now()->subDays($i)->format('M d')
        );

        // Revenue grouped by date
        $revenueByDate = Order::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // Align revenue data with labels
        $revenueData = collect(range(29, 0))->map(function ($i) use ($revenueByDate) {
            $date = now()->subDays($i)->toDateString();
            return (float) ($revenueByDate[$date] ?? 0);
        });

        // Order status counts
        $orderStatusCounts = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.dashboard', compact(
            'labels',
            'revenueData',
            'orderStatusCounts'
        ));
    }
}
