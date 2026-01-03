@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Shipping Methods') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Configure available shipping options for customers') }}
            </p>
        </div>

        <!-- Loading Spinner -->
        <div id="shippingMethodsLoading" class="text-center py-12">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading shipping methods...</p>
        </div>

        <!-- Form Container -->
        <div id="shippingMethodsFormContainer" style="display: none;"></div>
    </div>
</div>

@push('scripts')
<script>
    // Shipping Methods Module - Completely Independent
    class ShippingMethodsManager {
        constructor() {
            this.formId = 'shippingMethodsForm';
            this.loadingDiv = document.getElementById('shippingMethodsLoading');
            this.formContainer = document.getElementById('shippingMethodsFormContainer');
            this.currencyCode = '{{ $storeSetting->currency_code ?? "KWD" }}';
            this.init();
        }

        init() {
            this.fetchShippingMethods();
        }

        // Fetch shipping methods from server
        async fetchShippingMethods() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.store-settings.shipping-methods") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch shipping methods');
                
                const data = await response.json();
                this.renderForm(data);
                
            } catch (error) {
                console.error('Error fetching shipping methods:', error);
                showGlobalMessage('error', 'Failed to load shipping methods');
            } finally {
                this.showLoading(false);
            }
        }

        // Render form with data
        renderForm(shippingMethods) {
            const formHtml = `
                <form id="${this.formId}" onsubmit="event.preventDefault(); shippingMethodsManager.updateShippingMethods();">
                    @csrf
                    
                    <div x-data="shippingMethodsState" class="space-y-4">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Add and manage your shipping methods') }}
                                </p>
                            </div>
                            <button type="button" @click="addShippingMethod()"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Add Method') }}
                            </button>
                        </div>

                        <template x-for="(method, index) in shippingMethods" :key="index">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                        <span x-text="method.name || 'New Shipping Method'"></span>
                                    </h4>
                                    <button type="button" @click="removeShippingMethod(index)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label :value="__('Method Name *')" />
                                        <input type="text" 
                                            x-model="method.name" 
                                            :name="'shipping_methods[' + index + '][name]'"
                                            required
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    </div>
                                    
                                    <div>
                                        <x-input-label :value="__('Cost *')" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">${this.currencyCode}</span>
                                            </div>
                                            <input type="number" step="0.01" min="0"
                                                x-model="method.cost" 
                                                :name="'shipping_methods[' + index + '][cost]'"
                                                required
                                                class="pl-12 mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <x-input-label :value="__('Description')" />
                                        <input type="text" 
                                            x-model="method.description" 
                                            :name="'shipping_methods[' + index + '][description]'"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex items-center">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" 
                                            x-model="method.is_active" 
                                            :name="'shipping_methods[' + index + '][is_active]'"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('Active') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Empty State -->
                        <div x-show="shippingMethods.length === 0" 
                            class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">
                                {{ __('No shipping methods added yet.') }}
                            </p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                                {{ __('Click "Add Method" to create your first shipping method.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit" id="shippingMethodsSubmitBtn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Save Shipping Methods') }}
                        </button>
                    </div>
                </form>
            `;

            this.formContainer.innerHTML = formHtml;
            
            // Initialize Alpine.js data
            const script = document.createElement('script');
            script.innerHTML = `
                function shippingMethodsState() {
                    return {
                        shippingMethods: ${JSON.stringify(shippingMethods || [])},
                        addShippingMethod() {
                            this.shippingMethods.push({
                                name: '',
                                cost: 0,
                                description: '',
                                is_active: true
                            });
                        },
                        removeShippingMethod(index) {
                            if (confirm('Are you sure you want to remove this shipping method?')) {
                                this.shippingMethods.splice(index, 1);
                            }
                        }
                    }
                }
            `;
            document.head.appendChild(script);
            
            // Initialize Alpine.js if available
            if (typeof Alpine !== 'undefined') {
                Alpine.start();
            }
        }

        // Update shipping methods
        async updateShippingMethods() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('shippingMethodsSubmitBtn');
            const originalBtnText = submitBtn.innerHTML;
            
            try {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                
                // Clear previous errors
                clearFormErrors(form);
                
                // Get form data
                const formData = new FormData(form);

                // Convert checkbox values to boolean
                const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    // Remove the original checkbox value
                    formData.delete(checkbox.name);
                    // Add boolean value
                    formData.append(checkbox.name, checkbox.checked ? '1' : '0');
                });
                
                // Submit via AJAX
                const response = await fetch('{{ route("admin.store-settings.shipping-methods.update") }}', {
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
                    // Refresh the form
                    this.fetchShippingMethods();
                } else {
                    showGlobalMessage('error', data.message || 'Failed to save shipping methods');
                    
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
                console.error('Error updating shipping methods:', error);
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

    // Initialize Shipping Methods Manager
    let shippingMethodsManager;
    document.addEventListener('DOMContentLoaded', () => {
        shippingMethodsManager = new ShippingMethodsManager();
    });
</script>
@endpush
@endsection