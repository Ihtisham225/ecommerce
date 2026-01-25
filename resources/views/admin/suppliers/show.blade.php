<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplier Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Supplier Info Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg mb-6 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $supplier->name }}</h3>
                        <p class="text-sm opacity-90">{{ $supplier->company_name ?? '' }}</p>
                    </div>
                    <div class="flex space-x-3 items-center">
                        <span class="px-3 py-1 text-sm rounded-full {{ $supplier->status == 'active' ? 'bg-green-100 text-green-800' : ($supplier->status == 'inactive' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                        <span class="px-3 py-1 text-sm rounded-full {{ $supplier->current_balance > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            Balance: {{ format_currency($supplier->current_balance) }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Contact Info -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Contact Information') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                                <p class="font-medium">{{ $supplier->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Phone') }}</p>
                                <p class="font-medium">{{ $supplier->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tax ID') }}</p>
                                <p class="font-medium">{{ $supplier->tax_id ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Payment Terms') }}</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $supplier->payment_terms)) }}</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Address') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Address') }}</p>
                                <p class="font-medium">{{ $supplier->address ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('City/State/Country') }}</p>
                                <p class="font-medium">
                                    {{ $supplier->city ?? '' }}
                                    {{ $supplier->state ? ', ' . $supplier->state : '' }}
                                    {{ $supplier->country ? ', ' . $supplier->country : '' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Postal Code') }}</p>
                                <p class="font-medium">{{ $supplier->postal_code ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Financial Summary') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Opening Balance') }}</p>
                                <p class="font-medium">{{ format_currency($supplier->opening_balance) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Purchases') }}</p>
                                <p class="font-medium">{{ format_currency($totalPurchases) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Payments') }}</p>
                                <p class="font-medium">{{ format_currency($totalPayments) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Current Balance') }}</p>
                                <p class="font-medium {{ $supplier->current_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ format_currency($supplier->current_balance) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($supplier->notes)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Notes') }}</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $supplier->notes }}</p>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                ← {{ __('Back to Suppliers') }}
                            </a>
                            <a href="{{ route('admin.suppliers.balance-sheet', $supplier) }}" 
                               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                                {{ __('View Balance Sheet') }}
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.supplier-payments.create', ['supplier_id' => $supplier->id]) }}" 
                               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                {{ __('Make Payment') }}
                            </a>
                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                {{ __('Edit Supplier') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Expenses -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">{{ __('Recent Purchases') }}</h3>
                        <a href="{{ route('admin.expenses.index', ['supplier_id' => $supplier->id, 'type' => 'purchase']) }}" 
                           class="text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('View All') }}
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($supplier->expenses->where('type', 'purchase')->take(5) as $expense)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                <div>
                                    <p class="font-medium">{{ $expense->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $expense->reference_number }} • {{ $expense->date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">{{ format_currency($expense->total_amount) }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800' : ($expense->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($expense->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">{{ __('No purchase records found.') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">{{ __('Recent Payments') }}</h3>
                        <a href="{{ route('admin.supplier-payments.index', ['supplier_id' => $supplier->id]) }}" 
                           class="text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('View All') }}
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($supplier->payments->take(5) as $payment)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                <div>
                                    <p class="font-medium">{{ $payment->reference_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-green-600">{{ format_currency($payment->amount) }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">{{ __('No payment records found.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>