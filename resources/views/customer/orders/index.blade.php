<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Orders') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    View and track your order history
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('customer.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $orders->where('status', 'completed')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $orders->whereIn('status', ['pending', 'confirmed', 'processing'])->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order History</h3>
                </div>

                @if($orders->count())
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($orders as $order)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <!-- Order Info -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <a href="{{ route('customer.orders.show', $order) }}"
                                            class="text-lg font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Order #{{ $order->order_number }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col items-end gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $order->status === 'processing' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                        @if($order->source === 'online')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                            </svg>
                                            Online
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            In Store
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Order Items Summary -->
                                <div class="mt-4">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="flex -space-x-2">
                                            @foreach($order->items->take(3) as $item)
                                            @if($item->product && $item->product->mainImage)
                                            <img src="{{ $item->product->mainImage->first()->url }}"
                                                class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 object-contain">
                                            @else
                                            <div class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                            @endif
                                            @endforeach
                                            @if($order->items->count() > 3)
                                            <div class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-gray-800 text-white text-xs flex items-center justify-center">
                                                +{{ $order->items->count() - 3 }}
                                            </div>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Total Amount</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ format_currency($order->grand_total, 2) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Payment Status</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                        {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                            </span>
                                        </div>
                                        @if($order->shipping_status && $order->source === 'online')
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Shipping</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                        {{ $order->shipping_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $order->shipping_status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                                        {{ $order->shipping_status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->shipping_status)) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row gap-2 lg:flex-col lg:items-end">
                                <a href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>

                                @if($order->status === 'completed')
                                <button onclick="downloadInvoice({{ $order->id }})"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Invoice
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $orders->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No orders yet</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        You haven't placed any orders yet. Start shopping to see your orders here.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Start Shopping
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function downloadInvoice(orderId) {
            fetch(`/customer/orders/${orderId}/invoice`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.download_url) {
                        window.open(data.download_url, '_blank');
                    } else {
                        alert('Unable to download invoice. Please try again.');
                    }
                })
                .catch(error => {
                    alert('Error downloading invoice');
                });
        }
    </script>
</x-app-layout>