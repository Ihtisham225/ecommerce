@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Tax Settings') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Configure tax calculation for your store') }}
            </p>
        </div>

        <!-- Loading Spinner -->
        <div id="taxSettingsLoading" class="text-center py-12">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading tax settings...</p>
        </div>

        <!-- Form Container -->
        <div id="taxSettingsFormContainer" style="display: none;"></div>
    </div>
</div>

@push('scripts')
<script>
    // Tax Settings Module - Completely Independent
    class TaxSettingsManager {
        constructor() {
            this.formId = 'taxSettingsForm';
            this.loadingDiv = document.getElementById('taxSettingsLoading');
            this.formContainer = document.getElementById('taxSettingsFormContainer');
            this.init();
        }

        init() {
            this.fetchTaxSettings();
        }

        // Fetch tax settings from server
        async fetchTaxSettings() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.store-settings.tax-settings") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch tax settings');
                
                const data = await response.json();
                this.renderForm(data);
                
            } catch (error) {
                console.error('Error fetching tax settings:', error);
                showGlobalMessage('error', 'Failed to load tax settings');
            } finally {
                this.showLoading(false);
            }
        }

        // Render form with data
        renderForm(taxSettings) {
            const formHtml = `
                <form id="${this.formId}" onsubmit="event.preventDefault(); taxSettingsManager.updateTaxSettings();">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Tax Enabled Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                            <div>
                                <x-input-label :value="__('Enable Tax Calculation')" />
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Enable tax calculation for all orders') }}
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="tax_enabled" value="1"
                                       ${taxSettings.tax_enabled ? 'checked' : ''}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Tax Rate -->
                        <div>
                            <x-input-label for="tax_rate" :value="__('Tax Rate (%)')" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" min="0" max="100"
                                              value="${taxSettings.tax_rate || 0}"
                                              class="block w-full pr-12" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Enter the tax rate percentage to apply to orders') }}
                            </p>
                        </div>

                        <!-- Tax Inclusive -->
                        <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                            <input type="checkbox" id="tax_inclusive" name="tax_inclusive" value="1"
                                   ${taxSettings.tax_inclusive ? 'checked' : ''}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="tax_inclusive" class="ml-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Prices include tax') }}
                                </span>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Product prices already include tax amount') }}
                                </p>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit" id="taxSettingsSubmitBtn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Save Tax Settings') }}
                        </button>
                    </div>
                </form>
            `;

            this.formContainer.innerHTML = formHtml;
        }

        // Update tax settings
        async updateTaxSettings() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('taxSettingsSubmitBtn');
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
                const response = await fetch('{{ route("admin.store-settings.tax-settings.update") }}', {
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
                    showGlobalMessage('error', data.message || 'Failed to save tax settings');
                    
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
                console.error('Error updating tax settings:', error);
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

    // Initialize Tax Settings Manager
    let taxSettingsManager;
    document.addEventListener('DOMContentLoaded', () => {
        taxSettingsManager = new TaxSettingsManager();
    });
</script>
@endpush
@endsection