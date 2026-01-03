@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Bank Details') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Your bank account information for payments') }}
            </p>
        </div>

        <!-- Loading Spinner -->
        <div id="bankDetailsLoading" class="text-center py-12">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading bank details...</p>
        </div>

        <!-- Form Container -->
        <div id="bankDetailsFormContainer" style="display: none;"></div>
    </div>
</div>

@push('scripts')
<script>
    // Bank Details Module - Completely Independent
    class BankDetailsManager {
        constructor() {
            this.formId = 'bankDetailsForm';
            this.loadingDiv = document.getElementById('bankDetailsLoading');
            this.formContainer = document.getElementById('bankDetailsFormContainer');
            this.init();
        }

        init() {
            this.fetchBankDetails();
        }

        // Fetch bank details from server
        async fetchBankDetails() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.store-settings.bank-details") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch bank details');
                
                const data = await response.json();
                this.renderForm(data);
                
            } catch (error) {
                console.error('Error fetching bank details:', error);
                showGlobalMessage('error', 'Failed to load bank details');
            } finally {
                this.showLoading(false);
            }
        }

        // Render form with data
        renderForm(bankDetails) {
            const formHtml = `
                <form id="${this.formId}" onsubmit="event.preventDefault(); bankDetailsManager.updateBankDetails();">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="bank_name" :value="__('Bank Name')" />
                                <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.bank_name || ''}" />
                            </div>

                            <div>
                                <x-input-label for="account_name" :value="__('Account Name')" />
                                <x-text-input id="account_name" name="account_name" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.account_name || ''}" />
                            </div>

                            <div>
                                <x-input-label for="account_number" :value="__('Account Number')" />
                                <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.account_number || ''}" />
                            </div>

                            <div>
                                <x-input-label for="iban" :value="__('IBAN')" />
                                <x-text-input id="iban" name="iban" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.iban || ''}" />
                            </div>

                            <div>
                                <x-input-label for="swift_code" :value="__('SWIFT/BIC Code')" />
                                <x-text-input id="swift_code" name="swift_code" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.swift_code || ''}" />
                            </div>

                            <div>
                                <x-input-label for="branch_name" :value="__('Branch Name')" />
                                <x-text-input id="branch_name" name="branch_name" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.branch_name || ''}" />
                            </div>

                            <div>
                                <x-input-label for="branch_code" :value="__('Branch Code')" />
                                <x-text-input id="branch_code" name="branch_code" type="text" class="mt-1 block w-full"
                                              value="${bankDetails.branch_code || ''}" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit" id="bankDetailsSubmitBtn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Save Bank Details') }}
                        </button>
                    </div>
                </form>
            `;

            this.formContainer.innerHTML = formHtml;
        }

        // Update bank details
        async updateBankDetails() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('bankDetailsSubmitBtn');
            const originalBtnText = submitBtn.innerHTML;
            
            try {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                
                // Clear previous errors
                clearFormErrors(form);
                
                // Get form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                const response = await fetch('{{ route("admin.store-settings.bank-details.update") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    showGlobalMessage('success', data.message);
                } else {
                    showGlobalMessage('error', data.message || 'Failed to save bank details');
                    
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                                errorDiv.textContent = data.errors[field][0];
                                input.parentNode.appendChild(errorDiv);
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error updating bank details:', error);
                showGlobalMessage('error', 'An error occurred. Please try again.');
            } finally {
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }

        // Show/hide loading
        showLoading(show) {
            if (show) {
                this.loadingDiv.style.display = 'block';
                this.formContainer.style.display = 'none';
            } else {
                this.loadingDiv.style.display = 'none';
                this.formContainer.style.display = 'block';
            }
        }
    }

    // Initialize Bank Details Manager
    let bankDetailsManager;
    document.addEventListener('DOMContentLoaded', () => {
        bankDetailsManager = new BankDetailsManager();
    });
</script>
@endpush
@endsection