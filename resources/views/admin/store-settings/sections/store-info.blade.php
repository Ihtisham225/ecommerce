@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Store Information') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Basic store details and branding') }}
            </p>
        </div>

        <!-- Loading Spinner -->
        <div id="storeInfoLoading" class="text-center py-12" style="display: none;">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading store info...</p>
        </div>

        <!-- Form Container -->
        <div id="storeInfoFormContainer"></div>
    </div>
</div>

@push('scripts')
<script>
    // Store Info Module - Completely Independent
    class StoreInfoManager {
        constructor() {
            this.formId = 'storeInfoForm';
            this.loadingDiv = document.getElementById('storeInfoLoading');
            this.formContainer = document.getElementById('storeInfoFormContainer');
            this.currencies = @json($currencies);
            this.timezones = @json(timezone_identifiers_list());
            this.init();
        }

        init() {
            this.fetchStoreInfo();
            this.setupEventListeners();
        }

        // Fetch store info from server
        async fetchStoreInfo() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.store-settings.store-info") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch store info');
                
                const data = await response.json();
                this.renderForm(data);
                
            } catch (error) {
                console.error('Error fetching store info:', error);
                showGlobalMessage('error', 'Failed to load store information');
            } finally {
                this.showLoading(false);
            }
        }

        // Render form with data
        renderForm(data) {
            const formHtml = `
                <form id="${this.formId}" onsubmit="event.preventDefault(); storeInfoManager.updateStoreInfo();">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <x-input-label value="{{ __('Store Logo') }}" />
                            <div class="mt-2">
                                <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 rounded-xl p-8 transition-colors">
                                    <div id="logoPreview">
                                        ${data.logo ? this.renderLogoPreview(data.logo) : this.renderLogoUpload()}
                                    </div>
                                    <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Store Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Store Name -->
                            <div>
                                <x-input-label for="store_name" :value="__('Store Name *')" />
                                <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full"
                                              value="${data.store_name || ''}" required />
                            </div>

                            <!-- Store Email -->
                            <div>
                                <x-input-label for="store_email" :value="__('Store Email *')" />
                                <x-text-input id="store_email" name="store_email" type="email" class="mt-1 block w-full"
                                              value="${data.store_email || ''}" required />
                            </div>

                            <!-- Store Phone -->
                            <div>
                                <x-input-label for="store_phone" :value="__('Store Phone')" />
                                <x-text-input id="store_phone" name="store_phone" type="tel" class="mt-1 block w-full"
                                              value="${data.store_phone || ''}" />
                            </div>

                            <!-- Currency -->
                            <div>
                                <x-input-label for="currency_code" :value="__('Currency *')" />
                                <select id="currency_code" name="currency_code" required
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    ${this.renderCurrencyOptions(data.currency_code)}
                                </select>
                            </div>

                            <!-- Timezone -->
                            <div class="md:col-span-2">
                                <x-input-label for="timezone" :value="__('Timezone *')" />
                                <select id="timezone" name="timezone" required
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    ${this.renderTimezoneOptions(data.timezone)}
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        ${data.logo ? `
                        <button type="button" onclick="storeInfoManager.deleteLogo()"
                                class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-700 rounded-md font-semibold text-xs text-red-700 dark:text-red-300 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete Logo') }}
                        </button>
                        ` : ''}
                        <button type="submit" id="storeInfoSubmitBtn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Save Store Info') }}
                        </button>
                    </div>
                </form>
            `;

            this.formContainer.innerHTML = formHtml;
            this.initLogoPreview();
        }

        renderLogoPreview(logoPath) {
            return `
                <div class="text-center">
                    <img src="/storage/${logoPath}" alt="Current Logo" 
                         class="w-32 h-32 object-contain mx-auto rounded-lg shadow-md">
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Current logo') }}
                    </p>
                </div>
            `;
        }

        renderLogoUpload() {
            return `
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <button type="button" onclick="document.getElementById('logo').click()"
                                class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 font-medium">
                            {{ __('Click to upload') }}
                        </button>
                        {{ __('or drag and drop') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        PNG, JPG, GIF up to 2MB
                    </p>
                </div>
            `;
        }

        renderCurrencyOptions(selectedCurrency) {
            let options = '';
            for (const [code, currency] of Object.entries(this.currencies)) {
                options += `
                    <option value="${code}" ${selectedCurrency === code ? 'selected' : ''}>
                        ${currency.name} (${currency.symbol})
                    </option>
                `;
            }
            return options;
        }

        renderTimezoneOptions(selectedTimezone) {
            let options = '';
            this.timezones.forEach(tz => {
                options += `<option value="${tz}" ${selectedTimezone === tz ? 'selected' : ''}>${tz}</option>`;
            });
            return options;
        }

        // Update store info
        async updateStoreInfo() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('storeInfoSubmitBtn');
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
                const response = await fetch('{{ route("admin.store-settings.store-info.update") }}', {
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
                    // Refresh the form with updated data
                    this.fetchStoreInfo();
                } else {
                    showGlobalMessage('error', data.message || 'Failed to save store info');
                    
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
                console.error('Error updating store info:', error);
                showGlobalMessage('error', 'An error occurred. Please try again.');
            } finally {
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }

        // Delete logo
        async deleteLogo() {
            if (!confirm('Are you sure you want to delete the logo?')) return;
            
            try {
                const response = await fetch('{{ route("admin.store-settings.store-info.delete-logo") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showGlobalMessage('success', data.message);
                    // Refresh the form
                    this.fetchStoreInfo();
                } else {
                    showGlobalMessage('error', data.message || 'Failed to delete logo');
                }
            } catch (error) {
                console.error('Error deleting logo:', error);
                showGlobalMessage('error', 'An error occurred. Please try again.');
            }
        }

        // Initialize logo preview
        initLogoPreview() {
            const logoInput = document.getElementById('logo');
            const previewDiv = document.getElementById('logoPreview');
            
            if (logoInput && previewDiv) {
                logoInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            previewDiv.innerHTML = `
                                <div class="text-center">
                                    <img src="${e.target.result}" alt="Logo Preview" 
                                         class="w-32 h-32 object-contain mx-auto rounded-lg shadow-md">
                                    <button type="button" onclick="storeInfoManager.removeLogoPreview()"
                                            class="mt-4 text-sm text-red-600 hover:text-red-800 dark:text-red-400">
                                        {{ __('Remove') }}
                                    </button>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        removeLogoPreview() {
            const previewDiv = document.getElementById('logoPreview');
            const logoInput = document.getElementById('logo');
            
            previewDiv.innerHTML = this.renderLogoUpload();
            
            if (logoInput) {
                logoInput.value = '';
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

        setupEventListeners() {
            // Add any global event listeners here
        }
    }

    // Initialize Store Info Manager
    let storeInfoManager;
    document.addEventListener('DOMContentLoaded', () => {
        storeInfoManager = new StoreInfoManager();
    });
</script>
@endpush
@endsection