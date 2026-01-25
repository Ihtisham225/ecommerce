<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Expense Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Expense Header -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg mb-6 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r {{ $expense->type == 'purchase' ? 'from-blue-600 to-indigo-700' : ($expense->type == 'salary' ? 'from-purple-600 to-pink-700' : 'from-gray-600 to-gray-700') }} text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $expense->title }}</h3>
                        <p class="text-sm opacity-90">{{ $expense->reference_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ format_currency($expense->total_amount) }}</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800' : ($expense->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($expense->status == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Basic Info -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg">{{ __('Basic Information') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Date') }}</p>
                                <p class="font-medium">{{ $expense->date->format('F d, Y') }}</p>
                            </div>
                            @if($expense->due_date)
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Due Date') }}</p>
                                <p class="font-medium {{ $expense->isOverdue() ? 'text-red-600' : '' }}">
                                    {{ $expense->due_date->format('F d, Y') }}
                                </p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Category') }}</p>
                                <p class="font-medium">{{ ucfirst($expense->category) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Type') }}</p>
                                <span class="px-2 py-1 text-xs rounded-full {{ $expense->type == 'purchase' ? 'bg-blue-100 text-blue-800' : ($expense->type == 'salary' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($expense->type) }}
                                </span>
                            </div>
                        </div>

                        <!-- Supplier & Payment -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg">{{ __('Supplier & Payment') }}</h4>
                            @if($expense->supplier)
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Supplier') }}</p>
                                <p class="font-medium">
                                    <a href="{{ route('admin.suppliers.show', $expense->supplier) }}" class="text-indigo-600 hover:text-indigo-800">
                                        {{ $expense->supplier->name }}
                                    </a>
                                </p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Payment Method') }}</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</p>
                            </div>
                            @if($expense->payment_reference)
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Payment Reference') }}</p>
                                <p class="font-medium">{{ $expense->payment_reference }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Recorded By') }}</p>
                                <p class="font-medium">{{ $expense->user->name }}</p>
                            </div>
                        </div>

                        <!-- Financial Details -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg">{{ __('Financial Details') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Amount') }}</p>
                                <p class="font-medium">{{ format_currency($expense->amount) }}</p>
                            </div>
                            @if($expense->tax_amount > 0)
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Tax Amount') }}</p>
                                <p class="font-medium">{{ format_currency($expense->tax_amount) }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Total Amount') }}</p>
                                <p class="font-medium text-lg font-bold">{{ format_currency($expense->total_amount) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($expense->description)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg mb-2">{{ __('Description') }}</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $expense->description }}</p>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($expense->notes)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg mb-2">{{ __('Notes') }}</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $expense->notes }}</p>
                    </div>
                    @endif

                    <!-- Purchase Items (if type is purchase) -->
                    @if($expense->type == 'purchase' && $expense->purchaseItems->count() > 0)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg mb-4">{{ __('Purchase Items') }}</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 border">{{ __('Product') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Variant') }}</th>
                                        <th class="px-4 py-2 border">{{ __('SKU') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Quantity') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Unit Price') }}</th>
                                        <th class="px-4 py-2 border">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expense->purchaseItems as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-2 border">{{ $item->product->translate('title') ?? $item->product->sku }}</td>
                                            <td class="px-4 py-2 border">
                                                @if($item->variant)
                                                    {{ $item->variant->title }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border">
                                                @if($item->variant)
                                                    {{ $item->variant->sku }}
                                                @else
                                                    {{ $item->product->sku }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">{{ $item->quantity }}</td>
                                            <td class="px-4 py-2 border text-right">{{ format_currency($item->unit_price) }}</td>
                                            <td class="px-4 py-2 border text-right font-medium">{{ format_currency($item->total_price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 border text-right">{{ __('Total') }}</td>
                                        <td class="px-4 py-2 border text-right">{{ format_currency($expense->purchaseItems->sum('total_price')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            ← {{ __('Back to Expenses') }}
                        </a>
                        
                        <div class="flex space-x-3">
                            @if($expense->status != 'paid')
                            <form method="POST" action="{{ route('admin.expenses.mark-paid', $expense) }}" 
                                  onsubmit="return confirm('{{ __('Mark this expense as paid?') }}')">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                    {{ __('Mark as Paid') }}
                                </button>
                            </form>
                            @endif
                            
                            @if($expense->supplier)
                            <a href="{{ route('admin.supplier-payments.create', ['supplier_id' => $expense->supplier_id, 'expense_id' => $expense->id]) }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                {{ __('Make Payment') }}
                            </a>
                            @endif
                            
                            <a href="{{ route('admin.expenses.edit', $expense) }}" 
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                {{ __('Edit') }}
                            </a>
                            
                            <button onclick="window.print()" 
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                                {{ __('Print') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>