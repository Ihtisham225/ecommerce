<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Suppliers') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
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
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <!-- Balance Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Balance') }}
                        </label>
                        <select name="balance" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="with_balance" {{ request('balance') == 'with_balance' ? 'selected' : '' }}>With Balance</option>
                            <option value="no_balance" {{ request('balance') == 'no_balance' ? 'selected' : '' }}>No Balance</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search by name, email, company...') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Suppliers Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Suppliers') }}</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.suppliers.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Add Supplier') }}
                        </a>
                        <a href="#"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            {{ __('Supplier Report') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Name') }}</th>
                                <th class="px-4 py-2 border">{{ __('Company') }}</th>
                                <th class="px-4 py-2 border">{{ __('Email') }}</th>
                                <th class="px-4 py-2 border">{{ __('Phone') }}</th>
                                <th class="px-4 py-2 border">{{ __('Balance') }}</th>
                                <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border">{{ $supplier->name }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->company_name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->email ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $supplier->phone ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $supplier->current_balance > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ format_currency($supplier->current_balance) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $supplier->status == 'active' ? 'bg-green-100 text-green-800' : ($supplier->status == 'inactive' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($supplier->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3 justify-center">
                                            <!-- Show -->
                                            <a href="{{ route('admin.suppliers.show', $supplier) }}" 
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
                                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
                                               class="text-yellow-600 hover:text-yellow-800" 
                                               title="{{ __('Edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                                        a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <!-- Balance Sheet -->
                                            <a href="{{ route('admin.suppliers.balance-sheet', $supplier) }}" 
                                               class="text-purple-600 hover:text-purple-800" 
                                               title="{{ __('Balance Sheet') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" 
                                                  onsubmit="return confirm('{{ __('Are you sure? This will also delete related expenses and payments.') }}')">
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
                                    <td colspan="7" class="px-4 py-2 text-center">{{ __('No suppliers found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>