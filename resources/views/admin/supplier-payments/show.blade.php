<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            <!-- Payment Header Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg mb-6 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-500 to-emerald-600 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $supplierPayment->reference_number }}</h3>
                        <p class="text-sm opacity-90">{{ __('Payment to Supplier') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ format_currency($supplierPayment->amount) }}</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ $supplierPayment->status == 'completed' ? 'bg-green-100 text-green-800' : ($supplierPayment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($supplierPayment->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($supplierPayment->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Payment Information -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Payment Information') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Payment Date') }}</p>
                                <p class="font-medium">{{ $supplierPayment->payment_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Payment Method') }}</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $supplierPayment->payment_method)) }}</p>
                            </div>
                            @if($supplierPayment->payment_reference)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Payment Reference') }}</p>
                                <p class="font-medium">{{ $supplierPayment->payment_reference }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Recorded By') }}</p>
                                <p class="font-medium">{{ $supplierPayment->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created On') }}</p>
                                <p class="font-medium">{{ $supplierPayment->created_at->format('F d, Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Supplier Information -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Supplier Information') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Supplier') }}</p>
                                <p class="font-medium">
                                    <a href="{{ route('admin.suppliers.show', $supplierPayment->supplier) }}" class="text-indigo-600 hover:text-indigo-800">
                                        {{ $supplierPayment->supplier->name }}
                                    </a>
                                </p>
                            </div>
                            @if($supplierPayment->supplier->company_name)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Company') }}</p>
                                <p class="font-medium">{{ $supplierPayment->supplier->company_name }}</p>
                            </div>
                            @endif
                            @if($supplierPayment->supplier->phone)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Phone') }}</p>
                                <p class="font-medium">{{ $supplierPayment->supplier->phone }}</p>
                            </div>
                            @endif
                            @if($supplierPayment->supplier->email)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                                <p class="font-medium">{{ $supplierPayment->supplier->email }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Current Balance') }}</p>
                                <p class="font-medium {{ $supplierPayment->supplier->current_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ format_currency($supplierPayment->supplier->current_balance) }}
                                </p>
                            </div>
                        </div>

                        <!-- Balance Information -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Balance Information') }}</h4>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Payment Amount') }}</p>
                                <p class="font-medium text-green-600">{{ format_currency($supplierPayment->amount) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Previous Balance') }}</p>
                                <p class="font-medium">{{ format_currency($supplierPayment->previous_balance) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('New Balance') }}</p>
                                <p class="font-medium font-bold {{ $supplierPayment->new_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ format_currency($supplierPayment->new_balance) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Balance Difference') }}</p>
                                <p class="font-medium text-green-600">
                                    - {{ format_currency($supplierPayment->amount) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Linked Expense Information -->
                    @if($supplierPayment->expense)
                    <div class="mb-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-4">{{ __('Linked Expense') }}</h4>
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium">{{ $supplierPayment->expense->title }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $supplierPayment->expense->reference_number }} • 
                                        {{ $supplierPayment->expense->date->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">{{ format_currency($supplierPayment->expense->total_amount) }}</p>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $supplierPayment->expense->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($supplierPayment->expense->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 flex space-x-3">
                                <a href="{{ route('admin.expenses.show', $supplierPayment->expense) }}" 
                                   class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                    {{ __('View Expense') }}
                                </a>
                                @if($supplierPayment->expense->status != 'paid')
                                <form method="POST" action="{{ route('admin.expenses.mark-paid', $supplierPayment->expense) }}" 
                                      onsubmit="return confirm('{{ __('Mark this expense as paid?') }}')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                        {{ __('Mark as Paid') }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($supplierPayment->notes)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Notes') }}</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $supplierPayment->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Attachments -->
                    @if($supplierPayment->attachments && count($supplierPayment->attachments) > 0)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Attachments') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($supplierPayment->attachments as $attachment)
                                <div class="border rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium truncate">{{ basename($attachment) }}</p>
                                            <p class="text-xs text-gray-500">{{ __('Attachment') }}</p>
                                        </div>
                                        <a href="{{ Storage::url($attachment) }}" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-800 ml-2" title="{{ __('View') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.supplier-payments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                ← {{ __('Back to Payments') }}
                            </a>
                            <a href="{{ route('admin.suppliers.show', $supplierPayment->supplier) }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                {{ __('View Supplier') }}
                            </a>
                        </div>
                        
                        <div class="flex space-x-3">
                            <!-- Status Update Dropdown -->
                            @if($supplierPayment->status != 'completed')
                            <div class="relative group">
                                <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Update Status') }}
                                </button>
                                <div class="hidden group-hover:block absolute right-0 z-10 bg-white dark:bg-gray-800 shadow-lg rounded border p-4 min-w-[200px]">
                                    <h5 class="font-semibold mb-2">{{ __('Update Payment Status') }}</h5>
                                    <form method="POST" action="{{ route('admin.supplier-payments.update-status', $supplierPayment) }}" class="space-y-2">
                                        @csrf
                                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded p-2 text-sm">
                                            <option value="completed" {{ $supplierPayment->status == 'completed' ? 'selected disabled' : '' }}>Mark as Completed</option>
                                            <option value="failed" {{ $supplierPayment->status == 'failed' ? 'selected disabled' : '' }}>Mark as Failed</option>
                                            <option value="cancelled" {{ $supplierPayment->status == 'cancelled' ? 'selected disabled' : '' }}>Mark as Cancelled</option>
                                            @if($supplierPayment->status != 'pending')
                                            <option value="pending" {{ $supplierPayment->status == 'pending' ? 'selected disabled' : '' }}>Mark as Pending</option>
                                            @endif
                                        </select>
                                        <button type="submit" class="w-full px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                            {{ __('Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            
                            <a href="{{ route('admin.supplier-payments.edit', $supplierPayment) }}" 
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                {{ __('Edit') }}
                            </a>
                            
                            <button onclick="window.print()" 
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                                {{ __('Print') }}
                            </button>
                            
                            <form method="POST" action="{{ route('admin.supplier-payments.destroy', $supplierPayment) }}" 
                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this payment? This action cannot be undone.') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments for Same Supplier -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">{{ __('Recent Payments to') }} {{ $supplierPayment->supplier->name }}</h3>
                    <a href="{{ route('admin.supplier-payments.index', ['supplier_id' => $supplierPayment->supplier_id]) }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800">
                        {{ __('View All') }}
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Date') }}</th>
                                <th class="px-4 py-2 border">{{ __('Reference') }}</th>
                                <th class="px-4 py-2 border">{{ __('Amount') }}</th>
                                <th class="px-4 py-2 border">{{ __('Method') }}</th>
                                <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentPayments = \App\Models\SupplierPayment::where('supplier_id', $supplierPayment->supplier_id)
                                    ->where('id', '!=', $supplierPayment->id)
                                    ->orderBy('payment_date', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($recentPayments as $recentPayment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border">{{ $recentPayment->payment_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.supplier-payments.show', $recentPayment) }}" 
                                           class="text-indigo-600 hover:text-indigo-800">
                                            {{ $recentPayment->reference_number }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 border font-semibold text-green-600">
                                        {{ format_currency($recentPayment->amount) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ ucfirst(str_replace('_', ' ', $recentPayment->payment_method)) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $recentPayment->status == 'completed' ? 'bg-green-100 text-green-800' : ($recentPayment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($recentPayment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.supplier-payments.show', $recentPayment) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center">{{ __('No other payments found for this supplier.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Print functionality
            const printButton = document.querySelector('button[onclick="window.print()"]');
            if (printButton) {
                printButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.print();
                });
            }
            
            // Status update form submission confirmation
            const statusForm = document.querySelector('form[action*="update-status"]');
            if (statusForm) {
                const statusSelect = statusForm.querySelector('select[name="status"]');
                if (statusSelect) {
                    statusSelect.addEventListener('change', function() {
                        const newStatus = this.value;
                        const currentStatus = "{{ $supplierPayment->status }}";
                        
                        if (newStatus !== currentStatus) {
                            const confirmMessage = `Are you sure you want to change the payment status to ${newStatus}?`;
                            if (!confirm(confirmMessage)) {
                                this.value = currentStatus;
                            }
                        }
                    });
                }
            }
            
            // Delete confirmation with enhanced message
            const deleteForms = document.querySelectorAll('form[action*="destroy"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const amount = "{{ format_currency($supplierPayment->amount) }}";
                    const supplier = "{{ $supplierPayment->supplier->name }}";
                    const message = `Are you sure you want to delete this payment of ${amount} to ${supplier}?\n\nThis will:`;
                    const consequences = [
                        'Remove the payment record',
                        'Reverse the supplier balance update',
                        'Mark any linked expense as unpaid'
                    ];
                    
                    if (!confirm(message + '\n• ' + consequences.join('\n• '))) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    @endpush
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
                color: black !important;
            }
            
            .bg-white, .dark\\:bg-gray-800 {
                background: white !important;
                color: black !important;
            }
            
            .shadow-sm, .shadow-xl {
                box-shadow: none !important;
            }
            
            .border, .border-gray-200 {
                border: 1px solid #ccc !important;
            }
            
            a {
                color: black !important;
                text-decoration: none !important;
            }
            
            .text-indigo-600, .text-blue-600, .text-green-600, .text-red-600 {
                color: black !important;
            }
        }
    </style>
</x-app-layout>