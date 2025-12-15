<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Order #{{ $order->order_number }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Placed: {{ $order->created_at->format('M j, Y \\a\\t g:i A') }} • 
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        {{ $order->source === 'online' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                        {{ $order->source === 'online' ? '🌐 Online' : '🏪 In Store' }}
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.edit', $order) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Order
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>
        </div>

        <!-- Replace the complex invoice section with just these two buttons -->
        <div class="flex flex-wrap gap-2 mt-4">
            <a href="{{ route('admin.orders.invoice.pdf', $order) }}" 
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
            
            <a href="{{ route('admin.orders.invoice.thermal', $order) }}" target="_blank"
            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Thermal Print
            </a>
        </div>
    </x-slot>

    @php
        $storeSetting = \App\Models\StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $order->currency->code ?? 'USD';
        $currencySymbols = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥', 
            'CAD' => 'C$', 'AUD' => 'A$', 'CHF' => 'CHF', 'CNY' => '¥',
            'INR' => '₹', 'KWD' => 'KD', 'SAR' => 'SR', 'AED' => 'AED'
        ];
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;
        $decimals = $currencyCode === 'KWD' ? 3 : 2;
        
        // Status colors
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'processing' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        ];
        
        $paymentStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'partially_refunded' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'partially_paid' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        ];
        
        $shippingStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'ready_for_shipment' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        ];
        
        $isInStore = $order->source === 'in_store';
        
        // Transaction status colors
        $transactionStatusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
        
        // Calculate payment stats from payments
        $totalPaid = $order->payments()->sum('amount');
        $balanceDue = $order->grand_total - $totalPaid;
        $paymentPercentage = $order->grand_total > 0 ? round(($totalPaid / $order->grand_total) * 100, 2) : 0;
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Value</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paid</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $currencySymbol }}{{ number_format($totalPaid, $decimals) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg 
                            {{ $balanceDue > 0 ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400' : 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Balance Due</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $currencySymbol }}{{ number_format($balanceDue, $decimals) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Count</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $order->payments()->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Progress -->
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Progress</h3>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatusColors[$order->payment_status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                                @if($order->is_fully_paid)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Fully Paid
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $currencySymbol }}{{ number_format($totalPaid, $decimals) }} of {{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ round($paymentPercentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="
                                @if($paymentPercentage == 100) bg-green-600
                                @elseif($paymentPercentage > 0) bg-blue-600
                                @else bg-yellow-600
                                @endif
                                h-3 rounded-full transition-all duration-500" 
                                style="width: {{ min($paymentPercentage, 100) }}%"></div>
                        </div>
                        <div class="mt-6 grid grid-cols-4 gap-4 text-center">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tax</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($order->tax_total, $decimals) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                                <p class="font-semibold 
                                    @if($totalPaid >= $order->grand_total) text-green-600 dark:text-green-400
                                    @elseif($totalPaid > 0) text-blue-600 dark:text-blue-400
                                    @else text-gray-900 dark:text-white
                                    @endif">
                                    {{ $currencySymbol }}{{ number_format($totalPaid, $decimals) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden" x-data="{ activeTab: 'items' }">
                {{-- Header: Order Summary --}}
                <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row gap-8">
                        {{-- Order Status & Info --}}
                        <div class="w-full lg:w-2/3 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
                                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-wrap gap-3">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$order->status] }} shadow-sm">
                                        <span class="w-2 h-2 rounded-full mr-2 
                                            {{ $order->status === 'pending' ? 'bg-yellow-500' : '' }}
                                            {{ $order->status === 'confirmed' ? 'bg-blue-500' : '' }}
                                            {{ $order->status === 'processing' ? 'bg-indigo-500' : '' }}
                                            {{ $order->status === 'completed' ? 'bg-green-500' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-500' : '' }}
                                        "></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    
                                    @if(!$isInStore)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $paymentStatusColors[$order->payment_status] }} shadow-sm">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                    
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $shippingStatusColors[$order->shipping_status] }} shadow-sm">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $order->shipping_status)) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Customer Information --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Customer Information
                                    </h3>
                                    @if($order->customer)
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->customer->full_name }}</p>
                                                <p class="text-gray-600 dark:text-gray-400">{{ $order->customer->email }}</p>
                                                @if($order->customer->phone)
                                                    <p class="text-gray-600 dark:text-gray-400">{{ $order->customer->phone }}</p>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <p class="text-gray-500 dark:text-gray-400">Total Orders:</p>
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $order->customer->orders->count() ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500 dark:text-gray-400">Total Spent:</p>
                                                    <p class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $currencySymbol }}{{ number_format($order->customer->total_spent ?? 0, $decimals) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.customers.show', $order->customer) }}" 
                                               class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors font-medium">
                                                View Customer Profile
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            Guest Customer
                                        </div>
                                    @endif
                                </div>

                                {{-- Order Details --}}
                                <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Order Details
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Source:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $isInStore ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                {{ $isInStore ? '🏪 In Store' : '🌐 Online' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Currency:</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $order->currency->code ?? 'USD' }}</span>
                                        </div>
                                        @if($order->completed_at)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Completed:</span>
                                                <span class="font-medium text-green-600 dark:text-green-400">{{ $order->completed_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                        @endif
                                        @if($order->cancelled_at)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Cancelled:</span>
                                                <span class="font-medium text-red-600 dark:text-red-400">{{ $order->cancelled_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Updated:</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $order->updated_at->format('M j, Y g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Order Total --}}
                        <div class="w-full lg:w-1/3">
                            <div class="p-6 
                                @if($order->is_fully_paid) bg-gradient-to-br from-green-500 to-emerald-600
                                @elseif($totalPaid > 0) bg-gradient-to-br from-blue-500 to-indigo-600
                                @else bg-gradient-to-br from-indigo-500 to-purple-600
                                @endif
                                rounded-2xl text-white shadow-lg">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Order Total
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-white/90">Subtotal:</span>
                                        <span class="font-medium">{{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}</span>
                                    </div>
                                    @if($order->discount_total > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-white/90">Discount:</span>
                                            <span class="font-medium text-red-200">-{{ $currencySymbol }}{{ number_format($order->discount_total, $decimals) }}</span>
                                        </div>
                                    @endif
                                    @if($order->tax_total > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-white/90">Tax:</span>
                                            <span class="font-medium">{{ $currencySymbol }}{{ number_format($order->tax_total, $decimals) }}</span>
                                        </div>
                                    @endif
                                    @if($order->shipping_total > 0 && !$isInStore)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-white/90">Shipping:</span>
                                            <span class="font-medium">{{ $currencySymbol }}{{ number_format($order->shipping_total, $decimals) }}</span>
                                        </div>
                                    @endif
                                    <div class="border-t border-white/20 pt-3 mt-3">
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Total:</span>
                                            <span class="text-xl">{{ $currencySymbol }}{{ number_format($order->grand_total, $decimals) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <span class="text-white/90">Total Paid:</span>
                                            <span class="font-medium">{{ $currencySymbol }}{{ number_format($totalPaid, $decimals) }}</span>
                                        </div>
                                        @if($balanceDue > 0)
                                            <div class="flex justify-between text-sm mt-1">
                                                <span class="text-white/90">Balance Due:</span>
                                                <span class="font-medium text-yellow-200">{{ $currencySymbol }}{{ number_format($balanceDue, $decimals) }}</span>
                                            </div>
                                        @elseif($order->is_fully_paid)
                                            <div class="flex items-center justify-center mt-3 text-green-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Order Fully Paid
                                            </div>
                                        @endif
                                        <div class="mt-2 text-xs text-white/80">
                                            {{ $order->payments()->count() }} payment(s) recorded
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs Navigation --}}
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-8" aria-label="Tabs">
                        <button @click="activeTab = 'items'" 
                                :class="activeTab === 'items' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Items ({{ $order->items->count() }})
                        </button>
                        
                        @if(!$isInStore)
                        <button @click="activeTab = 'addresses'" 
                                :class="activeTab === 'addresses' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            Addresses
                        </button>
                        @endif
                        
                        <button @click="activeTab = 'payments'" 
                                :class="activeTab === 'payments' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Payments ({{ $order->payments()->count() }})
                        </button>
                        
                        <button @click="activeTab = 'transactions'" 
                                :class="activeTab === 'transactions' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Transactions ({{ $order->transactions->count() }})
                        </button>
                        
                        <button @click="activeTab = 'history'" 
                                :class="activeTab === 'history' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-1 inline-flex items-center border-b-2 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            History ({{ $order->history->count() }})
                        </button>
                    </nav>
                </div>

                {{-- Tab Content --}}
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    {{-- Items Tab --}}
                    <div x-show="activeTab === 'items'" x-cloak class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Order Items ({{ $order->items->count() }})
                        </h3>
                        
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-600 rounded-2xl shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($order->items as $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-3">
                                                        @if($item->product && $item->product->mainImage)
                                                            <img src="{{ $item->product->mainImage->first()->url }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</div>
                                                            @if($item->product)
                                                                <a href="{{ route('admin.products.show', $item->product) }}" 
                                                                   class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors font-medium">
                                                                    View Product
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono bg-gray-50 dark:bg-gray-700/50">{{ $item->sku }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $currencySymbol }}{{ number_format($item->price, $decimals) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $currencySymbol }}{{ number_format($item->total, $decimals) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-white">Subtotal:</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">{{ $order->items->sum('quantity') }}</span> items
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $currencySymbol }}{{ number_format($order->subtotal, $decimals) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Addresses Tab --}}
                    @if(!$isInStore)
                    <div x-show="activeTab === 'addresses'" x-cloak class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Billing Address --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Billing Address
                                    @if($billingAddress && $billingAddress->getTable() === 'customers')
                                        <span class="text-xs font-normal text-green-600 bg-green-100 px-2 py-1 rounded-full ml-2">
                                            From Customer Profile
                                        </span>
                                    @elseif($billingAddress)
                                        <span class="text-xs font-normal text-blue-600 bg-blue-100 px-2 py-1 rounded-full ml-2">
                                            From Order
                                        </span>
                                    @endif
                                </h3>
                                @if($billingAddress)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm space-y-3">
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $billingAddress->first_name ?? $order->customer?->first_name }} {{ $billingAddress->last_name ?? $order->customer?->last_name }}
                                        </p>
                                        @if($billingAddress->email ?? $order->customer?->email)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $billingAddress->email ?? $order->customer?->email }}</p>
                                        @endif
                                        @if($billingAddress->phone ?? $order->customer?->phone)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $billingAddress->phone ?? $order->customer?->phone }}</p>
                                        @endif
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $billingAddress->address_line_1 ?? $billingAddress->address1 }}<br>
                                            @if($billingAddress->address_line_2 ?? $billingAddress->address2)
                                                {{ $billingAddress->address_line_2 ?? $billingAddress->address2 }}<br>
                                            @endif
                                            @if($billingAddress->city)
                                                {{ $billingAddress->city }},
                                            @endif
                                            @if($billingAddress->state)
                                                {{ $billingAddress->state }}
                                            @endif
                                            @if($billingAddress->postal_code)
                                                {{ $billingAddress->postal_code }}<br>
                                            @endif
                                            @if($billingAddress->country)
                                                {{ $billingAddress->country }}
                                            @endif
                                        </p>
                                        @if($billingAddress->same_as_shipping)
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Same as Shipping Address
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        No billing address provided
                                    </div>
                                @endif
                            </div>

                            {{-- Shipping Address --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Shipping Address
                                    @if($shippingAddress && $shippingAddress->getTable() === 'customers')
                                        <span class="text-xs font-normal text-green-600 bg-green-100 px-2 py-1 rounded-full ml-2">
                                            From Customer Profile
                                        </span>
                                    @elseif($shippingAddress)
                                        <span class="text-xs font-normal text-blue-600 bg-blue-100 px-2 py-1 rounded-full ml-2">
                                            From Order
                                        </span>
                                    @endif
                                </h3>
                                @if($shippingAddress)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm space-y-3">
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $shippingAddress->first_name ?? $order->customer?->first_name }} {{ $shippingAddress->last_name ?? $order->customer?->last_name }}
                                        </p>
                                        @if($billingAddress->email ?? $order->customer?->email)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $billingAddress->email ?? $order->customer?->email }}</p>
                                        @endif
                                        @if($shippingAddress->phone ?? $order->customer?->phone)
                                            <p class="text-gray-600 dark:text-gray-400">{{ $shippingAddress->phone ?? $order->customer?->phone }}</p>
                                        @endif
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $shippingAddress->address_line_1 ?? $shippingAddress->address1 }}<br>
                                            @if($shippingAddress->address_line_2 ?? $shippingAddress->address2)
                                                {{ $shippingAddress->address_line_2 ?? $shippingAddress->address2 }}<br>
                                            @endif
                                            @if($shippingAddress->city)
                                                {{ $shippingAddress->city }},
                                            @endif
                                            @if($shippingAddress->state)
                                                {{ $shippingAddress->state }}
                                            @endif
                                            @if($shippingAddress->postal_code)
                                                {{ $shippingAddress->postal_code }}<br>
                                            @endif
                                            @if($shippingAddress->country)
                                                {{ $shippingAddress->country }}
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        No shipping address provided
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Payments Tab --}}
                    <div x-show="activeTab === 'payments'" x-cloak class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center justify-between">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Order Payments ({{ $order->payments()->count() }})
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatusColors[$order->payment_status] }}">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>
                        </h3>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm overflow-hidden">
                            @if($order->payments()->count())
                                <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($order->payments()->orderBy('order_payment_order.created_at', 'desc')->get() as $payment)
                                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                            {{ $payment->method === 'direct' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                            {{ $payment->method === 'direct' ? 'Cash/Direct' : 'Bank Transfer' }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $transactionStatusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $payment->pivot->created_at->format('M j, Y g:i A') }}
                                                        </span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Amount:</span>
                                                            <p class="font-semibold text-lg text-gray-900 dark:text-white">
                                                                {{ $currencySymbol }}{{ number_format($payment->amount, $decimals) }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Added By:</span>
                                                            <p class="font-medium text-gray-900 dark:text-white">
                                                                {{ $payment->pivot->created_by ? \App\Models\User::find($payment->pivot->created_by)->name ?? 'System' : 'System' }}
                                                            </p>
                                                        </div>
                                                        @if($payment->transaction_id)
                                                        <div class="col-span-2">
                                                            <span class="text-gray-500 dark:text-gray-400">Transaction ID:</span>
                                                            <p class="font-mono text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-600 p-2 rounded border truncate">{{ $payment->transaction_id }}</p>
                                                        </div>
                                                        @endif
                                                        @if($payment->pivot->notes)
                                                        <div class="col-span-2 mt-2">
                                                            <span class="text-gray-500 dark:text-gray-400">Notes:</span>
                                                            <p class="text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-600 p-3 rounded border">{{ $payment->pivot->notes }}</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="border-t border-gray-200 dark:border-gray-600 p-6 bg-gray-50 dark:bg-gray-700/50">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Payments</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $order->payments()->count() }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                                            <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $currencySymbol }}{{ number_format($totalPaid, $decimals) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Balance Due</p>
                                            <p class="text-lg font-bold 
                                                {{ $balanceDue > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $currencySymbol }}{{ number_format($balanceDue, $decimals) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <p class="text-lg">No payment records found</p>
                                    <p class="text-sm mt-1">
                                        @if($isInStore)
                                            Payments can be added in the order edit view
                                        @else
                                            Online order payments are processed through payment gateway
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Transactions Tab --}}
                    <div x-show="activeTab === 'transactions'" x-cloak class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Order Transactions ({{ $order->transactions->count() }})
                        </h3>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm overflow-hidden">
                            @if($order->transactions->count())
                                <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($order->transactions as $transaction)
                                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            {{ ucfirst($transaction->type) }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $transactionStatusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                            {{ ucfirst($transaction->status) }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $transaction->created_at->format('M j, Y g:i A') }}
                                                        </span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Amount:</span>
                                                            <p class="font-semibold text-lg text-gray-900 dark:text-white">
                                                                {{ $currencySymbol }}{{ number_format($transaction->amount, $decimals) }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Method:</span>
                                                            <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $transaction->payment_method ?? 'N/A' }}</p>
                                                        </div>
                                                        @if($transaction->gateway)
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Gateway:</span>
                                                            <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $transaction->gateway }}</p>
                                                        </div>
                                                        @endif
                                                        @if($transaction->transaction_id)
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Transaction ID:</span>
                                                            <p class="font-mono text-xs text-gray-600 dark:text-gray-400 truncate">{{ $transaction->transaction_id }}</p>
                                                        </div>
                                                        @endif
                                                        @if($transaction->meta)
                                                        <div class="col-span-2 mt-2">
                                                            <span class="text-gray-500 dark:text-gray-400">Details:</span>
                                                            <pre class="text-xs text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-600 p-3 rounded border max-h-40 overflow-y-auto">{{ json_encode($transaction->meta, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    <p class="text-lg">No transaction records found</p>
                                    <p class="text-sm mt-1">Transactions will appear here when processed</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- History Tab --}}
                    <div x-show="activeTab === 'history'" x-cloak class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Order Status History --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Status History ({{ $order->history->count() }})
                                </h3>
                                
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm overflow-hidden">
                                    @if($order->history->count())
                                        <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach($order->history->sortByDesc('created_at') as $history)
                                                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                                    <div class="flex items-start space-x-4">
                                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                                                <div class="flex-1">
                                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                                        Status changed from 
                                                                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ $history->old_status ?? 'N/A' }}</span> 
                                                                        to 
                                                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $history->new_status }}</span>
                                                                    </p>
                                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                                        {{ $history->created_at->format('M j, Y \\a\\t g:i A') }}
                                                                    </p>
                                                                </div>
                                                                @if($history->user)
                                                                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-600 px-2 py-1 rounded-full border">
                                                                        by {{ $history->user->name }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-lg">No status history available</p>
                                            <p class="text-sm mt-1">Status changes will appear here</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        function generateInvoice(orderId) {
            if (confirm('Generate and store invoice for future access?')) {
                fetch(`/admin/orders/${orderId}/invoice/generate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Invoice generated successfully!');
                        if (data.download_url) {
                            window.open(data.download_url, '_blank');
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error generating invoice');
                });
            }
        }
    </script>
</x-app-layout>