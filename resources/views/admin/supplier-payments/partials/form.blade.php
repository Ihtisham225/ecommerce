<div class="space-y-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    
    {{-- Validation Errors --}}
    @if ($errors->any())
        <x-alert type="error" title="Validation Error" :message="$errors->all()" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Payment Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Payment Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Supplier') }} *</label>
                <select name="supplier_id" id="supplier_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                    <option value="">{{ __('Select Supplier') }}</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" 
                                {{ old('supplier_id', $payment?->supplier_id ?? request('supplier_id')) == $supplier->id ? 'selected' : '' }}
                                data-balance="{{ $supplier->current_balance }}">
                            {{ $supplier->name }} (Balance: {{ format_currency($supplier->current_balance) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Balance Info -->
            <div id="balance-info" class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg mb-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Current Balance</p>
                        <p id="current-balance" class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ format_currency(0.00) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Available Balance</p>
                        <p id="available-balance" class="text-lg font-semibold text-green-600 dark:text-green-400">{{ format_currency(0.00) }}</p>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <span id="payment-type-hint">Select payment type to see options</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Type') }} *</label>
                <select name="payment_type" id="payment_type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                    <option value="">{{ __('Select Type') }}</option>
                    <option value="partial" {{ old('payment_type', $payment?->payment_type ?? '') == 'partial' ? 'selected' : '' }}>Partial Payment</option>
                    <option value="full" {{ old('payment_type', $payment?->payment_type ?? '') == 'full' ? 'selected' : '' }}>Full Payment</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount') }} *</label>
                <div class="relative">
                    <input type="number" step="0.01" name="amount" id="amount" 
                           value="{{ old('amount', $payment?->amount ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border pr-16"
                           required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1">
                        <button type="button" id="pay-full" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                            Pay Full
                        </button>
                    </div>
                </div>
                <p id="amount-hint" class="mt-1 text-sm text-gray-500 dark:text-gray-400"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Date') }} *</label>
                <input type="date" name="payment_date" 
                       value="{{ old('payment_date', $payment?->payment_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Method') }} *</label>
                <select name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                    <option value="cash" {{ old('payment_method', $payment?->payment_method ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ old('payment_method', $payment?->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="check" {{ old('payment_method', $payment?->payment_method ?? '') == 'check' ? 'selected' : '' }}>Check</option>
                    <option value="credit_card" {{ old('payment_method', $payment?->payment_method ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="digital_wallet" {{ old('payment_method', $payment?->payment_method ?? '') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                    <option value="other" {{ old('payment_method', $payment?->payment_method ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Reference') }}</label>
                <input type="text" name="payment_reference" 
                       value="{{ old('payment_reference', $payment?->payment_reference ?? '') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                       placeholder="Check #, Transaction ID, etc.">
            </div>
        </div>

        <!-- Additional Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Additional Information') }}</h4>
            
            <!-- Pending Expenses -->
            <div id="pending-expenses-container" class="hidden">
                <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Apply to Expense') }}</h4>
                <div class="space-y-2">
                    <div class="flex items-center mb-2">
                        <input type="radio" name="expense_id" value="" 
                               id="expense_none" 
                               {{ empty(old('expense_id', $payment?->expense_id ?? '')) ? 'checked' : '' }}
                               class="mr-2">
                        <label for="expense_none" class="cursor-pointer text-gray-700 dark:text-gray-300">
                            General Payment (Apply to any expense)
                        </label>
                    </div>
                    
                    @foreach($pendingExpenses as $expense)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center">
                                <input type="radio" name="expense_id" value="{{ $expense->id }}" 
                                       id="expense_{{ $expense->id }}" 
                                       {{ old('expense_id', $payment?->expense_id ?? '') == $expense->id ? 'checked' : '' }}
                                       class="mr-3">
                                <div>
                                    <label for="expense_{{ $expense->id }}" class="cursor-pointer font-medium text-gray-800 dark:text-gray-200">
                                        {{ $expense->reference_number }}
                                    </label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $expense->title }}</p>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <span class="text-xs px-2 py-1 rounded-full {{ $expense->status === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                        @if($expense->remaining_amount > 0)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Remaining: {{ format_currency($expense->remaining_amount) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-800 dark:text-gray-200">
                                    {{ format_currency($expense->total_amount) }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Due: {{ $expense->due_date?->format('M d, Y') ?? 'No due date' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                    <option value="pending" {{ old('status', $payment?->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $payment?->status ?? 'pending') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ old('status', $payment?->status ?? 'pending') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ old('status', $payment?->status ?? 'pending') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
                <textarea name="notes" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('notes', $payment?->notes ?? '') }}</textarea>
            </div>

            <!-- Attachments -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Attachments') }}</label>
                <input type="file" name="attachments[]" 
                    multiple
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300
                            hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Upload payment receipts, bank slips, or other documents (PDF, images, documents). New files will be added to existing attachments.
                </p>
                
                @if(isset($payment) && $payment->attachments && count($payment->attachments) > 0)
                    <div class="mt-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Existing Attachments:</p>
                        <div class="space-y-2">
                            @foreach($payment->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="flex items-center">
                                        @php
                                            $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                            $isPdf = $extension === 'pdf';
                                        @endphp
                                        
                                        @if($isImage)
                                            <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @elseif($isPdf)
                                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @endif
                                        
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ basename($attachment) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ Storage::url($attachment) }}" target="_blank" 
                                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline px-2 py-1">
                                            View
                                        </a>
                                        <button type="button" 
                                                onclick="removeAttachment({{ $index }})"
                                                class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 px-2 py-1">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Hidden input to track removed attachments -->
                        <input type="hidden" name="removed_attachments" id="removed_attachments" value="">
                    </div>
                @endif
            </div>

            @push('scripts')
            <script>
                function removeAttachment(index) {
                    if (confirm('Are you sure you want to remove this attachment?')) {
                        // Add to removed attachments list
                        const removedInput = document.getElementById('removed_attachments');
                        let removed = removedInput.value ? removedInput.value.split(',') : [];
                        removed.push(index);
                        removedInput.value = removed.join(',');
                        
                        // Hide the attachment element
                        event.target.closest('.flex.items-center.justify-between').style.display = 'none';
                    }
                }
            </script>
            @endpush
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.storeCurrency = {
        code: "{{ $currencyCode }}",
        symbol: "{{ $currencySymbol }}"
    };

    document.addEventListener('DOMContentLoaded', function() {
        const supplierSelect = document.getElementById('supplier_id');
        const paymentTypeSelect = document.getElementById('payment_type');
        const amountInput = document.getElementById('amount');
        const payFullButton = document.getElementById('pay-full');
        const balanceInfo = document.getElementById('balance-info');
        const currentBalanceEl = document.getElementById('current-balance');
        const availableBalanceEl = document.getElementById('available-balance');
        const paymentTypeHint = document.getElementById('payment-type-hint');
        const amountHint = document.getElementById('amount-hint');
        const pendingExpensesContainer = document.getElementById('pending-expenses-container');
        
        // Get the form
        const form = document.querySelector('form');

        let currentBalance = 0;
        let selectedExpenseAmount = 0;

        // Format currency
        formatCurrency(amount) {
            amount = parseFloat(amount) || 0;

            const currencyCode = window.storeCurrency?.code || 'USD';
            const currencySymbol = window.storeCurrency?.symbol || '$';

            // Some currencies (PKR, INR, etc.) look better without Intl currency style
            const formatter = new Intl.NumberFormat(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            return `${currencySymbol}${formatter.format(amount)}`;
        }

        // Update balance info when supplier changes
        supplierSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            currentBalance = parseFloat(selectedOption.dataset.balance) || 0;
            
            updateBalanceInfo();
            fetchPendingExpenses();
        });

        // Update payment type hint
        paymentTypeSelect.addEventListener('change', function() {
            updateBalanceInfo();
            updateAmountInput();
        });

        // Pay full button
        payFullButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentBalance > 0) {
                paymentTypeSelect.value = 'full';
                amountInput.value = currentBalance.toFixed(2);
                updateBalanceInfo();
            }
        });

        // Amount input validation
        amountInput.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            
            if (amount >= currentBalance) {
                paymentTypeSelect.value = 'full';
            } else if (amount > 0) {
                paymentTypeSelect.value = 'partial';
            }
            
            updateAmountHint();
        });

        // Form validation before submission
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.field-error').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => 
                el.classList.remove('border-red-500')
            );
            
            // Validate supplier
            if (!supplierSelect.value) {
                isValid = false;
                addFieldError(supplierSelect, 'Please select a supplier');
            }
            
            // Validate payment type
            if (!paymentTypeSelect.value) {
                isValid = false;
                addFieldError(paymentTypeSelect, 'Please select a payment type');
            }
            
            // Validate amount
            const amount = parseFloat(amountInput.value) || 0;
            if (!amountInput.value || amount <= 0) {
                isValid = false;
                addFieldError(amountInput, 'Please enter a valid payment amount');
            } else if (amount > currentBalance) {
                isValid = false;
                addFieldError(amountInput, `Payment amount cannot exceed current balance of ${formatCurrency(currentBalance)}`);
            }
            
            // Validate payment method
            const paymentMethod = document.querySelector('select[name="payment_method"]');
            if (!paymentMethod.value) {
                isValid = false;
                addFieldError(paymentMethod, 'Please select a payment method');
            }
            
            // Validate payment date
            const paymentDate = document.querySelector('input[name="payment_date"]');
            if (!paymentDate.value) {
                isValid = false;
                addFieldError(paymentDate, 'Please select a payment date');
            }
            
            // Prevent submission if validation fails
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                
                // Scroll to first error
                const firstError = document.querySelector('.field-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                return false;
            }
        });

        // Helper function to add field error
        function addFieldError(field, message) {
            field.classList.add('border-red-500');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-sm text-red-600 dark:text-red-400 mt-1';
            errorDiv.textContent = message;
            
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        }

        // Update balance information
        function updateBalanceInfo() {
            if (supplierSelect.value && currentBalance > 0) {
                balanceInfo.classList.remove('hidden');
                currentBalanceEl.textContent = formatCurrency(currentBalance);
                
                const paymentType = paymentTypeSelect.value;
                
                availableBalanceEl.textContent = formatCurrency(currentBalance);
                
                // Update hints
                if (paymentType === 'full') {
                    paymentTypeHint.textContent = 'Full payment will clear the entire balance.';
                    amountInput.setAttribute('max', currentBalance);
                } else if (paymentType === 'partial') {
                    paymentTypeHint.textContent = 'Enter any amount up to the current balance.';
                    amountInput.setAttribute('max', currentBalance);
                } else {
                    paymentTypeHint.textContent = 'Select payment type to see options';
                }
                
                updateAmountHint();
            } else {
                balanceInfo.classList.add('hidden');
            }
        }

        // Update amount input based on payment type
        function updateAmountInput() {
            const paymentType = paymentTypeSelect.value;
            
            if (paymentType === 'full') {
                amountInput.value = currentBalance.toFixed(2);
                amountInput.readOnly = true;
                amountInput.classList.add('bg-gray-100', 'dark:bg-gray-700');
            } else {
                amountInput.readOnly = false;
                amountInput.classList.remove('bg-gray-100', 'dark:bg-gray-700');
            }
            
            updateAmountHint();
        }

        // Update amount hint
        function updateAmountHint() {
            const amount = parseFloat(amountInput.value) || 0;
            const paymentType = paymentTypeSelect.value;
            
            if (amount > currentBalance) {
                amountHint.textContent = `Amount exceeds current balance. Maximum: ${formatCurrency(currentBalance)}`;
                amountHint.classList.remove('text-gray-500', 'dark:text-gray-400');
                amountHint.classList.add('text-red-600', 'dark:text-red-400');
            } else if (amount > 0) {
                if (paymentType === 'full') {
                    amountHint.textContent = 'This will clear the entire balance.';
                } else {
                    const remaining = currentBalance - amount;
                    amountHint.textContent = `Remaining balance after payment: ${formatCurrency(remaining)}`;
                }
                amountHint.classList.remove('text-red-600', 'dark:text-red-400');
                amountHint.classList.add('text-gray-500', 'dark:text-gray-400');
            } else {
                amountHint.textContent = `Enter payment amount. Maximum: ${formatCurrency(currentBalance)}`;
                amountHint.classList.remove('text-red-600', 'dark:text-red-400');
                amountHint.classList.add('text-gray-500', 'dark:text-gray-400');
            }
        }

        // Fetch pending expenses for selected supplier
        function fetchPendingExpenses() {
            if (!supplierSelect.value) {
                // Show placeholder when no supplier selected
                pendingExpensesContainer.innerHTML = `
                    <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Apply to Expense') }}</h4>
                    <div class="text-center py-6">
                        <p class="text-gray-600 dark:text-gray-400">Select a supplier to view pending expenses.</p>
                    </div>
                `;
                pendingExpensesContainer.classList.remove('hidden'); // Keep it visible
                return;
            }
            
            // Show loading state
            pendingExpensesContainer.classList.remove('hidden');
            pendingExpensesContainer.innerHTML = `
                <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Apply to Expense') }}</h4>
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Loading pending expenses...</p>
                </div>
            `;
            
            // Make API call
            fetch(`/admin/supplier-payments/suppliers/get-pending-expenses?supplier_id=${supplierSelect.value}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.expenses && data.expenses.length > 0) {
                        updatePendingExpensesUI(data.expenses);
                    } else {
                        showNoExpensesMessage();
                    }
                })
                .catch(error => {
                    console.error('Error fetching expenses:', error);
                    showErrorLoadingExpenses();
                });
        }

        // Update pending expenses UI with new data
        function updatePendingExpensesUI(expenses) {
            let html = `
                <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Apply to Expense') }}</h4>
                <div class="space-y-2">
                    <div class="flex items-center mb-2">
                        <input type="radio" name="expense_id" value="" 
                            id="expense_none" 
                            checked
                            class="mr-2">
                        <label for="expense_none" class="cursor-pointer text-gray-700 dark:text-gray-300">
                            General Payment (Apply to any expense)
                        </label>
                    </div>
            `;
            
            expenses.forEach(expense => {
                const statusClass = expense.status === 'partial' 
                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' 
                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                
                html += `
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 expense-item" data-expense-id="${expense.id}">
                        <div class="flex items-center">
                            <input type="radio" name="expense_id" value="${expense.id}" 
                                id="expense_${expense.id}" 
                                class="mr-3 expense-radio">
                            <div>
                                <label for="expense_${expense.id}" class="cursor-pointer font-medium text-gray-800 dark:text-gray-200">
                                    ${expense.reference_number}
                                </label>
                                <p class="text-sm text-gray-600 dark:text-gray-400">${expense.title}</p>
                                <div class="flex items-center mt-1 space-x-4">
                                    <span class="text-xs px-2 py-1 rounded-full ${statusClass}">
                                        ${expense.status.charAt(0).toUpperCase() + expense.status.slice(1)}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Remaining: ${formatCurrency(expense.remaining_amount)}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">
                                ${formatCurrency(expense.total_amount)}
                            </span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Due: ${expense.due_date ? new Date(expense.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'No due date'}
                            </p>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            pendingExpensesContainer.innerHTML = html;
            
            // Add event listeners to new expense radio buttons
            attachExpenseRadioListeners();
        }

        // Show message when no expenses found
        function showNoExpensesMessage() {
            pendingExpensesContainer.innerHTML = `
                <div class="text-center py-6">
                    <p class="text-gray-600 dark:text-gray-400">No pending expenses found for this supplier.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Make a general payment instead.</p>
                </div>
            `;
        }

        // Show error message when loading fails
        function showErrorLoadingExpenses() {
            pendingExpensesContainer.innerHTML = `
                <div class="text-center py-6">
                    <p class="text-red-600 dark:text-red-400">Failed to load pending expenses.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Please try again or make a general payment.</p>
                </div>
            `;
        }

        // Attach event listeners to expense radio buttons
        function attachExpenseRadioListeners() {
            document.querySelectorAll('.expense-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value) {
                        const expenseCard = this.closest('.expense-item');
                        if (expenseCard) {
                            // Highlight selected expense
                            document.querySelectorAll('.expense-item').forEach(card => {
                                card.classList.remove('border-indigo-500', 'dark:border-indigo-400');
                                card.classList.add('border-gray-200', 'dark:border-gray-600');
                            });
                            expenseCard.classList.add('border-indigo-500', 'dark:border-indigo-400');
                            expenseCard.classList.remove('border-gray-200', 'dark:border-gray-600');
                        }
                    }
                });
            });
        }

        // Handle initial state on page load
        function handleInitialState() {
            // First, force remove hidden class from pending expenses container
            pendingExpensesContainer.classList.remove('hidden');
            
            if (supplierSelect.value) {
                const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
                currentBalance = parseFloat(selectedOption.dataset.balance) || 0;
                updateBalanceInfo();
                
                // Check if we have initial expenses from PHP by looking for actual expense items
                const hasInitialExpenses = document.querySelectorAll('input[name="expense_id"][value!=""]').length > 0;
                
                console.log('Has initial expenses:', hasInitialExpenses);
                
                if (hasInitialExpenses) {
                    // We have initial expenses from PHP, ensure container is visible
                    pendingExpensesContainer.classList.remove('hidden');
                    
                    // Highlight the selected expense if one is checked
                    const selectedExpenseRadio = document.querySelector('input[name="expense_id"]:checked');
                    if (selectedExpenseRadio && selectedExpenseRadio.value) {
                        const expenseCard = selectedExpenseRadio.closest('.bg-gray-50, .dark\\:bg-gray-700');
                        if (expenseCard) {
                            expenseCard.classList.add('border-indigo-500', 'dark:border-indigo-400');
                            expenseCard.classList.remove('border-gray-200', 'dark:border-gray-600');
                        }
                    }
                    
                    // Attach listeners to existing radio buttons
                    document.querySelectorAll('input[name="expense_id"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            // Remove highlight from all expense cards
                            document.querySelectorAll('.bg-gray-50, .dark\\:bg-gray-700').forEach(card => {
                                card.classList.remove('border-indigo-500', 'dark:border-indigo-400');
                                card.classList.add('border-gray-200', 'dark:border-gray-600');
                            });
                            
                            // Highlight selected expense card
                            if (this.value) {
                                const expenseCard = this.closest('.bg-gray-50, .dark\\:bg-gray-700');
                                if (expenseCard) {
                                    expenseCard.classList.add('border-indigo-500', 'dark:border-indigo-400');
                                    expenseCard.classList.remove('border-gray-200', 'dark:border-gray-600');
                                }
                            }
                        });
                    });
                } else {
                    // No initial expenses, fetch them via AJAX
                    fetchPendingExpenses();
                }
            } else {
                // No supplier selected - show placeholder message
                pendingExpensesContainer.innerHTML = `
                    <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300 mb-2">{{ __('Apply to Expense') }}</h4>
                    <div class="text-center py-6">
                        <p class="text-gray-600 dark:text-gray-400">Select a supplier to view pending expenses.</p>
                    </div>
                `;
            }
        }

        // Initialize on page load
        handleInitialState();
    });
</script>
@endpush