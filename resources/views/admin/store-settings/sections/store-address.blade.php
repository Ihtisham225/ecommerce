@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Store Address') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Your store\'s physical address') }}
            </p>
        </div>

        <!-- Loading Spinner -->
        <div id="storeAddressLoading" class="text-center py-12" style="display: none;">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading address...</p>
        </div>

        <!-- Form Container -->
        <div id="storeAddressFormContainer"></div>
    </div>
</div>

@push('scripts')
<script>
    // Store Address Module - Completely Independent
    class StoreAddressManager {
        constructor() {
            this.formId = 'storeAddressForm';
            this.loadingDiv = document.getElementById('storeAddressLoading');
            this.formContainer = document.getElementById('storeAddressFormContainer');
            this.init();
        }

        init() {
            this.fetchStoreAddress();
        }

        // Fetch store address from server
        async fetchStoreAddress() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.store-settings.store-address") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch store address');
                
                const data = await response.json();
                this.renderForm(data);
                
            } catch (error) {
                console.error('Error fetching store address:', error);
                showGlobalMessage('error', 'Failed to load store address');
            } finally {
                this.showLoading(false);
            }
        }

        // Render form with data
        renderForm(data) {
            const formHtml = `
                <form id="${this.formId}" onsubmit="event.preventDefault(); storeAddressManager.updateStoreAddress();">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="address_line1" :value="__('Address Line 1')" />
                                <x-text-input id="address_line1" name="address_line1" type="text" class="mt-1 block w-full"
                                              value="${data.address_line1 || ''}" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="address_line2" :value="__('Address Line 2')" />
                                <x-text-input id="address_line2" name="address_line2" type="text" class="mt-1 block w-full"
                                              value="${data.address_line2 || ''}" />
                            </div>

                            <div>
                                <x-input-label for="city" :value="__('City')" />
                                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                                              value="${data.city || ''}" />
                            </div>

                            <div>
                                <x-input-label for="state" :value="__('State/Province')" />
                                <x-text-input id="state" name="state" type="text" class="mt-1 block w-full"
                                              value="${data.state || ''}" />
                            </div>

                            <div>
                                <x-input-label for="country" :value="__('Country')" />
                                <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                                              value="${data.country || ''}" />
                            </div>

                            <div>
                                <x-input-label for="postal_code" :value="__('Postal Code')" />
                                <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full"
                                              value="${data.postal_code || ''}" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit" id="storeAddressSubmitBtn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Save Address') }}
                        </button>
                    </div>
                </form>
            `;

            this.formContainer.innerHTML = formHtml;
        }

        // Update store address
        async updateStoreAddress() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('storeAddressSubmitBtn');
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
                const response = await fetch('{{ route("admin.store-settings.store-address.update") }}', {
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
                    showGlobalMessage('error', data.message || 'Failed to save address');
                    
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
                console.error('Error updating store address:', error);
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

    // Initialize Store Address Manager
    let storeAddressManager;
    document.addEventListener('DOMContentLoaded', () => {
        storeAddressManager = new StoreAddressManager();
    });
</script>
@endpush
@endsection