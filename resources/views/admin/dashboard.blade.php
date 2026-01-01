<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard Overview') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: {{ now()->format('M j, Y \a\t g:i A') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- Quick Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Total Revenue Card --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format(App\Models\Order::sum('grand_total'), 2) }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-green-600 dark:text-green-400">
                        +{{ number_format(App\Models\Order::whereMonth('created_at', now()->month)->sum('grand_total'), 2) }} this month
                    </span>
                </div>
            </div>

            {{-- Total Orders Card --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ App\Models\Order::count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-green-600 dark:text-green-400">
                        {{ App\Models\Order::whereDate('created_at', today())->count() }} today
                    </span>
                </div>
            </div>

            {{-- Total Products Card --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ App\Models\Product::count() }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ App\Models\Product::where('is_active', true)->count() }} active
                    </span>
                </div>
            </div>

            {{-- Total Customers Card --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Customers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ App\Models\Customer::count() }}
                        </p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-green-600 dark:text-green-400">
                        {{ App\Models\Customer::whereDate('created_at', today())->count() }} new today
                    </span>
                </div>
            </div>
        </div>

        {{-- Second Row Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            {{-- Pending Orders --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Orders</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ App\Models\Order::where('status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Out of Stock --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.698-.833-2.464 0L4.338 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Out of Stock</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ App\Models\Product::where('stock_status', 'out_of_stock')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Total Blogs --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Published Blogs</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ App\Models\Blog::published()->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Average Order Value --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-teal-100 dark:bg-teal-900 p-3 rounded-full">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Order Value</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            ${{ number_format(App\Models\Order::avg('grand_total') ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Revenue Chart --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Overview (Last 30 Days)</h3>
                    <select id="revenuePeriod" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                    </select>
                </div>
                <div class="relative h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Order Status Distribution --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Order Status Distribution</h3>
                <div class="relative h-64">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Third Row: Additional Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Selling Products --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Top Selling Products</h3>
                <div class="space-y-4">
                    @php
                    $topProducts = App\Models\Product::withCount(['orderItems as total_sold' => function($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                    }])
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get();
                    @endphp

                    @foreach($topProducts as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                @if($product->mainImage()->first())
                                <img src="{{ Storage::url($product->mainImage()->first()->file_path) }}" alt="{{ $product->translate('title') }}" class="w-8 h-8 rounded object-contain">
                                @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->translate('title') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->total_sold ?? 0 }} sold</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Customers --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Customers</h3>
                <div class="space-y-4">
                    @foreach(App\Models\Customer::latest()->take(5)->get() as $customer)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->full_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->orders()->count() }} orders</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Orders Table --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach(App\Models\Order::latest()->take(8)->get() as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('orders.show', $order) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $order->customer->full_name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->customer->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($order->grand_total, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- System Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">System Status</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Server Time</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ now()->format('H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-300">PHP Version</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ phpversion() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Laravel Version</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ app()->version() }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Storage</h4>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Disk Usage</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Quick Links</h4>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Product
                    </a>
                    <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Order
                    </a>
                    <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Add Customer
                    </a>
                    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        New Blog
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const isDarkMode = () =>
                document.documentElement.classList.contains('dark') ||
                window.matchMedia('(prefers-color-scheme: dark)').matches;

            const getColors = () => ({
                text: isDarkMode() ? '#fff' : '#374151',
                grid: isDarkMode() ? '#374151' : '#E5E7EB',
                ticks: isDarkMode() ? '#9CA3AF' : '#6B7280'
            });

            /* ==========================
            Revenue Line Chart
            ========================== */

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueData),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: getColors().text
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: getColors().ticks
                            },
                            grid: {
                                color: getColors().grid
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: getColors().ticks,
                                callback: value => '$' + value.toLocaleString()
                            },
                            grid: {
                                color: getColors().grid
                            }
                        }
                    }
                }
            });

            /* ==========================
            Order Status Doughnut Chart
            ========================== */

            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [{
                                {
                                    $orderStatusCounts['pending']
                                }
                            },
                            {
                                {
                                    $orderStatusCounts['processing']
                                }
                            },
                            {
                                {
                                    $orderStatusCounts['completed']
                                }
                            },
                            {
                                {
                                    $orderStatusCounts['cancelled']
                                }
                            }
                        ],
                        backgroundColor: [
                            'rgb(251, 191, 36)',
                            'rgb(59, 130, 246)',
                            'rgb(34, 197, 94)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1,
                        borderColor: isDarkMode() ? '#1F2937' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: getColors().text,
                                padding: 20
                            }
                        }
                    }
                }
            });

            /* ==========================
            Dark Mode Sync
            ========================== */

            function updateChartsForTheme() {
                const colors = getColors();

                revenueChart.options.plugins.legend.labels.color = colors.text;
                revenueChart.options.scales.x.ticks.color = colors.ticks;
                revenueChart.options.scales.x.grid.color = colors.grid;
                revenueChart.options.scales.y.ticks.color = colors.ticks;
                revenueChart.options.scales.y.grid.color = colors.grid;
                revenueChart.update();

                orderStatusChart.options.plugins.legend.labels.color = colors.text;
                orderStatusChart.update();
            }

            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', updateChartsForTheme);
        });
    </script>


</x-app-layout>