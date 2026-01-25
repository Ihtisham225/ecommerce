<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplier Balance Sheet') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg mb-6 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $supplier->name }}</h3>
                            <p class="text-sm opacity-90">{{ $supplier->company_name ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">{{ __('Current Balance') }}</p>
                            <p class="text-3xl font-bold {{ $supplier->current_balance > 0 ? 'text-red-300' : 'text-green-300' }}">
                                {{ format_currency($supplier->current_balance) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <p class="text-sm text-blue-600 dark:text-blue-300">{{ __('Total Purchases') }}</p>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-200">
                                {{ format_currency($supplier->expenses->where('type', 'purchase')->sum('total_amount')) }}
                            </p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <p class="text-sm text-green-600 dark:text-green-300">{{ __('Total Payments') }}</p>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-200">
                                {{ format_currency($supplier->payments->sum('amount')) }}
                            </p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                            <p class="text-sm text-purple-600 dark:text-purple-300">{{ __('Opening Balance') }}</p>
                            <p class="text-2xl font-bold text-purple-700 dark:text-purple-200">
                                {{ format_currency($supplier->opening_balance) }}
                            </p>
                        </div>
                    </div>

                    <!-- Transaction History -->
                    <div class="mt-8">
                        <h4 class="font-semibold text-lg mb-4">{{ __('Transaction History') }}</h4>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 border">{{ __('Date') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Reference') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Description') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Debit') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Credit') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $runningBalance = $supplier->opening_balance;
                                    @endphp
                                    
                                    <!-- Opening Balance Row -->
                                    <tr class="bg-gray-50 dark:bg-gray-800">
                                        <td class="px-4 py-2 border">{{ $supplier->created_at->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 border">{{ __('Opening') }}</td>
                                        <td class="px-4 py-2 border">{{ __('Opening Balance') }}</td>
                                        <td class="px-4 py-2 border text-right">{{ format_currency($supplier->opening_balance) }}</td>
                                        <td class="px-4 py-2 border text-right">-</td>
                                        <td class="px-4 py-2 border text-right font-semibold {{ $runningBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ format_currency($runningBalance) }}
                                        </td>
                                    </tr>

                                    @foreach($transactions->sortBy('created_at') as $transaction)
                                        @php
                                            if ($transaction->type == 'purchase') {
                                                $runningBalance += $transaction->amount;
                                            } else {
                                                $runningBalance -= $transaction->amount;
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-2 border">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-2 border">{{ $transaction->reference_number }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ $transaction->type == 'purchase' ? 'Purchase' : 'Payment' }}
                                            </td>
                                            <td class="px-4 py-2 border text-right {{ $transaction->type == 'purchase' ? 'text-red-600 font-medium' : '' }}">
                                                {{ $transaction->type == 'purchase' ? format_currency($transaction->amount) : '-' }}
                                            </td>
                                            <td class="px-4 py-2 border text-right {{ $transaction->type == 'payment' ? 'text-green-600 font-medium' : '' }}">
                                                {{ $transaction->type == 'payment' ? format_currency($transaction->amount) : '-' }}
                                            </td>
                                            <td class="px-4 py-2 border text-right font-semibold {{ $runningBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ format_currency($runningBalance) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 border text-right">{{ __('Final Balance') }}</td>
                                        <td class="px-4 py-2 border text-right {{ $supplier->current_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ format_currency($supplier->current_balance) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <a href="{{ route('admin.suppliers.show', $supplier) }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            ← {{ __('Back to Supplier Details') }}
                        </a>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.expenses.create', ['supplier_id' => $supplier->id]) }}" 
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                {{ __('New Purchase') }}
                            </a>
                            <a href="{{ route('admin.supplier-payments.create', ['supplier_id' => $supplier->id]) }}" 
                               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                {{ __('New Payment') }}
                            </a>
                            <button onclick="window.print()" 
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                                {{ __('Print Statement') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>