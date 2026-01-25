<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Payment') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="bg-blue-600 px-6 py-4 rounded-t-lg">
                    <h1 class="text-2xl font-bold text-white">{{ __('Edit Supplier Payment') }}</h1>
                    <p class="text-blue-100 text-sm mt-1">
                        Payment ID: {{ $supplierPayment->id }} | 
                        Date: {{ $supplierPayment->payment_date->format('M d, Y') }} | 
                        Status: <span class="font-semibold {{ $supplierPayment->status === 'completed' ? 'text-green-300' : ($supplierPayment->status === 'pending' ? 'text-yellow-300' : 'text-red-300') }}">
                            {{ ucfirst($supplierPayment->status) }}
                        </span>
                    </p>
                </div>
                <div class="p-6 border border-gray-200 border-t-0 rounded-b-lg">
                    <form action="{{ route('admin.supplier-payments.update', $supplierPayment) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Pass the correct variable name --}}
                        @include('admin.supplier-payments.partials.form', ['payment' => $supplierPayment])

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <div class="space-x-3">
                                <a href="{{ route('admin.supplier-payments.show', $supplierPayment) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                    ← {{ __('Back to Payment') }}
                                </a>
                                <a href="{{ route('admin.supplier-payments.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-lg">
                                    {{ __('All Payments') }}
                                </a>
                            </div>

                            <div class="space-x-3">
                                @if($supplierPayment->status !== 'cancelled')
                                    <button type="button" onclick="confirmCancellation()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md">
                                        {{ __('Cancel Payment') }}
                                    </button>
                                @endif
                                
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700">
                                    {{ __('Update Payment') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    @if($supplierPayment->status !== 'cancelled')
                        <!-- Cancellation Form -->
                        <form id="cancellation-form" action="{{ route('admin.supplier-payments.update-status', $supplierPayment) }}" method="POST" class="hidden">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <input type="hidden" name="notes" value="Payment cancelled by {{ auth()->user()->name }} on {{ now()->format('Y-m-d') }}">
                        </form>
                    @endif
                </div>
            </div>
            
            <!-- Payment Details Card -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Payment History & Details') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Original Payment Details -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">{{ __('Original Payment Details') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Original Amount:</span>
                                <span class="font-semibold">{{ format_currency($supplierPayment->amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Previous Balance:</span>
                                <span class="font-semibold">{{ format_currency($supplierPayment->previous_balance) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">New Balance After Payment:</span>
                                <span class="font-semibold {{ $supplierPayment->new_balance < $supplierPayment->previous_balance ? 'text-green-600 dark:text-green-400' : 'text-gray-800 dark:text-gray-200' }}">
                                    {{ format_currency($supplierPayment->new_balance) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Type:</span>
                                <span class="font-semibold capitalize">{{ $supplierPayment->payment_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                                <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $supplierPayment->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Created By:</span>
                                <span>{{ $supplierPayment->user->name ?? 'System' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Created At:</span>
                                <span>{{ $supplierPayment->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if($supplierPayment->updated_at != $supplierPayment->created_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                <span>{{ $supplierPayment->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Current Supplier Info -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">{{ __('Current Supplier Status') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Supplier:</span>
                                <span class="font-semibold">{{ $supplierPayment->supplier->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Current Balance:</span>
                                <span class="font-semibold {{ $supplierPayment->supplier->current_balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    {{ format_currency($supplierPayment->supplier->current_balance) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Impact:</span>
                                <span class="font-semibold">
                                    @php
                                        $impact = $supplierPayment->previous_balance - $supplierPayment->new_balance;
                                    @endphp
                                    @if($impact > 0)
                                        <span class="text-green-600 dark:text-green-400">-{{ format_currency($impact) }}</span>
                                    @else
                                        <span class="text-gray-600 dark:text-gray-400">{{ format_currency($impact) }}</span>
                                    @endif
                                </span>
                            </div>
                            @if($supplierPayment->expense)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Applied to Expense:</span>
                                <span class="font-semibold">
                                    <a href="{{ route('admin.expenses.show', $supplierPayment->expense) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $supplierPayment->expense->reference_number }}
                                    </a>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Expense Status:</span>
                                <span class="font-semibold {{ $supplierPayment->expense->status === 'paid' ? 'text-green-600' : ($supplierPayment->expense->status === 'partial' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ ucfirst($supplierPayment->expense->status) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Attachments Preview -->
                        @if($supplierPayment->attachments && count($supplierPayment->attachments) > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments:</h5>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($supplierPayment->attachments as $attachment)
                                    @php
                                        $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $isPdf = strtolower($extension) === 'pdf';
                                        $filename = basename($attachment);
                                    @endphp
                                    <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                        @if($isImage)
                                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @elseif($isPdf)
                                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @endif
                                        <span class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $filename }}</span>
                                        <a href="{{ Storage::url($attachment) }}" 
                                           target="_blank" 
                                           class="ml-auto text-blue-600 dark:text-blue-400 hover:underline text-xs">
                                            View
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmCancellation() {
            if (confirm('Are you sure you want to cancel this payment? This action will reverse the balance adjustment and cannot be undone.')) {
                document.getElementById('cancellation-form').submit();
            }
        }
        
        // Handle attachment removal
        function removeAttachment(index) {
            if (confirm('Are you sure you want to remove this attachment?')) {
                // Add to removed attachments list
                const removedInput = document.getElementById('removed_attachments');
                let removed = removedInput.value ? removedInput.value.split(',') : [];
                if (!removed.includes(index.toString())) {
                    removed.push(index.toString());
                    removedInput.value = removed.join(',');
                }
                
                // Hide the attachment element
                const attachmentElement = event.target.closest('.flex.items-center.justify-between');
                if (attachmentElement) {
                    attachmentElement.style.display = 'none';
                    // Add visual feedback
                    attachmentElement.classList.add('opacity-50', 'line-through');
                }
            }
        }
        
        // Auto-select the supplier's current balance in the dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const supplierSelect = document.getElementById('supplier_id');
            
            // Ensure the supplier is selected (should already be from PHP)
            @if($supplierPayment->supplier_id)
                if (supplierSelect) {
                    supplierSelect.value = {{ $supplierPayment->supplier_id }};
                    
                    // Trigger change event to update balance info
                    if ('createEvent' in document) {
                        var evt = document.createEvent('HTMLEvents');
                        evt.initEvent('change', false, true);
                        supplierSelect.dispatchEvent(evt);
                    } else {
                        supplierSelect.dispatchEvent(new Event('change'));
                    }
                }
            @endif
            
            // Highlight selected expense if any
            const selectedExpenseRadio = document.querySelector('input[name="expense_id"]:checked');
            if (selectedExpenseRadio && selectedExpenseRadio.value) {
                const expenseCard = selectedExpenseRadio.closest('.bg-gray-50, .dark\\:bg-gray-700');
                if (expenseCard) {
                    expenseCard.classList.add('border-indigo-500', 'dark:border-indigo-400', 'ring-2', 'ring-indigo-500');
                    expenseCard.classList.remove('border-gray-200', 'dark:border-gray-600');
                }
            }
        });
    </script>
    @endpush
</x-app-layout>