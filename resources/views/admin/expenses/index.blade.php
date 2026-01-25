<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Expenses') }}
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
                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Type') }}
                        </label>
                        <select name="type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                            <option value="operational" {{ request('type') == 'operational' ? 'selected' : '' }}>Operational</option>
                            <option value="salary" {{ request('type') == 'salary' ? 'selected' : '' }}>Salary</option>
                            <option value="utility" {{ request('type') == 'utility' ? 'selected' : '' }}>Utility</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Status') }}
                        </label>
                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
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

            <!-- Expenses Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Expenses') }}</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.expenses.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Add Expense') }}
                        </a>
                        <a href="#"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            {{ __('Expense Report') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Date') }}</th>
                                <th class="px-4 py-2 border">{{ __('Reference') }}</th>
                                <th class="px-4 py-2 border">{{ __('Supplier') }}</th>
                                <th class="px-4 py-2 border">{{ __('Description') }}</th>
                                <th class="px-4 py-2 border">{{ __('Amount') }}</th>
                                <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border">{{ __('Type') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border">{{ $expense->date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 border">{{ $expense->reference_number }}</td>
                                    <td class="px-4 py-2 border">{{ $expense->supplier?->name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ Str::limit($expense->title, 30) }}</td>
                                    <td class="px-4 py-2 border font-semibold">{{ format_currency($expense->total_amount) }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800' : ($expense->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($expense->status == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $expense->type == 'purchase' ? 'bg-blue-100 text-blue-800' : ($expense->type == 'salary' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($expense->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3 justify-center">
                                            <!-- Show -->
                                            <a href="{{ route('admin.expenses.show', $expense) }}" 
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

                                            <!-- Edit -->
                                            <a href="{{ route('admin.expenses.edit', $expense) }}" 
                                               class="text-yellow-600 hover:text-yellow-800" 
                                               title="{{ __('Edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                                        a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <!-- Mark as Paid -->
                                            @if($expense->status != 'paid')
                                            <form method="POST" action="{{ route('admin.expenses.mark-paid', $expense) }}" 
                                                  onsubmit="return confirm('{{ __('Mark this expense as paid?') }}')">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800" 
                                                        title="{{ __('Mark as Paid') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" 
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
                                    <td colspan="8" class="px-4 py-2 text-center">{{ __('No expenses found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>