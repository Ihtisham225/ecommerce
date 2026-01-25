<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplier Payments') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Filters -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Status') }}
                        </label>
                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Supplier Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Supplier') }}
                        </label>
                        <select name="supplier_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Suppliers') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Method Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Payment Method') }}
                        </label>
                        <select name="payment_method" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Methods') }}</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="digital_wallet" {{ request('payment_method') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.supplier-payments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>

                <!-- Date Range Filter -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Date From') }}
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Date To') }}
                        </label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Payments') }}</h3>
                    <a href="{{ route('admin.supplier-payments.create') }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        {{ __('Make Payment') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Date') }}</th>
                                <th class="px-4 py-2 border">{{ __('Reference') }}</th>
                                <th class="px-4 py-2 border">{{ __('Supplier') }}</th>
                                <th class="px-4 py-2 border">{{ __('Expense') }}</th>
                                <th class="px-4 py-2 border">{{ __('Amount') }}</th>
                                <th class="px-4 py-2 border">{{ __('Payment Method') }}</th>
                                <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border">{{ $payment?->payment_date?->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 border">{{ $payment->reference_number }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.suppliers.show', $payment->supplier) }}" 
                                           class="text-indigo-600 hover:text-indigo-800">
                                            {{ $payment->supplier->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if($payment->expense)
                                            <a href="{{ route('admin.expenses.show', $payment->expense) }}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                {{ $payment->expense->reference_number }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border font-semibold text-green-600">
                                        {{ format_currency($payment->amount) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $payment->status == 'completed' ? 'bg-green-100 text-green-800' : ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($payment->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3 justify-center">
                                            <!-- Show -->
                                            <a href="{{ route('admin.supplier-payments.show', $payment) }}" 
                                               class="text-blue-600 hover:text-blue-800" 
                                               title="{{ __('View Details') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                        c4.478 0 8.268 2.943 9.542 7
                                                        -1.274 4.057-5.064 7-9.542 7
                                                        -4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <!-- Status Update Dropdown -->
                                            @if($payment->status != 'completed')
                                            <div class="relative group">
                                                <button class="text-yellow-600 hover:text-yellow-800" 
                                                        title="{{ __('Update Status') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <div class="hidden group-hover:block absolute z-10 bg-white dark:bg-gray-800 shadow-lg rounded border p-2">
                                                    <form method="POST" action="{{ route('admin.supplier-payments.update-status', $payment) }}">
                                                        @csrf
                                                        <select name="status" class="text-sm p-1 border rounded" onchange="this.form.submit()">
                                                            <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Mark as Completed</option>
                                                            <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Mark as Failed</option>
                                                            <option value="cancelled" {{ $payment->status == 'cancelled' ? 'selected' : '' }}>Mark as Cancelled</option>
                                                        </select>
                                                    </form>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.supplier-payments.destroy', $payment) }}" 
                                                  onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" 
                                                        title="{{ __('Delete') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                            a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                            m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3
                                                            H5m14 0H5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-2 text-center">{{ __('No payments found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $payments->links() }}
                </div>

                <!-- Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded">
                            <p class="text-sm text-blue-600 dark:text-blue-300">{{ __('Total Payments') }}</p>
                            <p class="text-2xl font-bold">
                                {{ format_currency($summary['total_completed']) }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded">
                            <p class="text-sm text-yellow-600 dark:text-yellow-300">{{ __('Pending Payments') }}</p>
                            <p class="text-2xl font-bold">
                                {{ format_currency($summary['total_pending']) }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded">
                            <p class="text-sm text-green-600 dark:text-green-300">{{ __('This Month') }}</p>
                            <p class="text-2xl font-bold">
                                {{ format_currency($summary['this_month']) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>