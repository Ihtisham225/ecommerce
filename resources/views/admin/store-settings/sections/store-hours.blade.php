@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Store Hours') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Set your store opening and closing hours') }}
            </p>
        </div>

        <!-- Loading Spinner -->
        <div id="storeHoursLoading" class="text-center py-12">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading store hours...</p>
        </div>

        <!-- Form Container -->
        <div id="storeHoursFormContainer" style="display: none;"></div>
    </div>
</div>

@push('scripts')
<script>
    // Store Hours Module - Completely Independent
    class StoreHoursManager {
        constructor() {
            this.formId = 'storeHoursForm';
            this.loadingDiv = document.getElementById('storeHoursLoading');
            this.formContainer = document.getElementById('storeHoursFormContainer');
            this.init();
        }

        init() {
            this.fetchStoreHours();
        }

        // Fetch store hours from server
        async fetchStoreHours() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.store-settings.store-hours") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch store hours');
                
                const data = await response.json();
                this.renderForm(data);
                
            } catch (error) {
                console.error('Error fetching store hours:', error);
                showGlobalMessage('error', 'Failed to load store hours');
            } finally {
                this.showLoading(false);
            }
        }

        // Render form with data
        renderForm(storeHours) {
            const days = {
                'monday': 'Monday',
                'tuesday': 'Tuesday',
                'wednesday': 'Wednesday',
                'thursday': 'Thursday',
                'friday': 'Friday',
                'saturday': 'Saturday',
                'sunday': 'Sunday'
            };

            let daysHtml = '';
            for (const [dayKey, dayName] of Object.entries(days)) {
                const dayData = storeHours[dayKey] || {
                    day: dayKey,
                    open: '09:00',
                    close: '17:00',
                    is_closed: dayKey === 'sunday'
                };

                daysHtml += `
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            ${dayName}
                        </span>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Closed Checkbox -->
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                    name="store_hours[${dayKey}][is_closed]" 
                                    value="1"
                                    ${dayData.is_closed ? 'checked' : ''}
                                    onclick="toggleStoreHours(this, '${dayKey}')"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Closed') }}
                                </span>
                            </label>
                            
                            <!-- Time Inputs -->
                            <div class="flex items-center space-x-2" id="store-hours-${dayKey}" 
                                style="${dayData.is_closed ? 'display: none;' : ''}">
                                <input type="time" 
                                    name="store_hours[${dayKey}][open]"
                                    value="${dayData.open || '09:00'}"
                                    class="w-28 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="text-gray-500">to</span>
                                <input type="time"
                                    name="store_hours[${dayKey}][close]"
                                    value="${dayData.close || '17:00'}"
                                    class="w-28 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="store_hours[${dayKey}][day]" value="${dayKey}">
                    </div>
                `;
            }

            const formHtml = `
                <form id="${this.formId}" onsubmit="event.preventDefault(); storeHoursManager.updateStoreHours();">
                    @csrf
                    
                    <div class="space-y-3">
                        ${daysHtml}
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit" id="storeHoursSubmitBtn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Save Store Hours') }}
                        </button>
                    </div>
                </form>
            `;

            this.formContainer.innerHTML = formHtml;
        }

        // Update store hours
        async updateStoreHours() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('storeHoursSubmitBtn');
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
                const response = await fetch('{{ route("admin.store-settings.store-hours.update") }}', {
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
                    showGlobalMessage('error', data.message || 'Failed to save store hours');
                    
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
                console.error('Error updating store hours:', error);
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

    // Global function to toggle store hours
    function toggleStoreHours(checkbox, dayKey) {
        const timeContainer = document.getElementById('store-hours-' + dayKey);
        if (checkbox.checked) {
            timeContainer.style.display = 'none';
            const inputs = timeContainer.querySelectorAll('input[type="time"]');
            inputs.forEach(input => input.value = '');
        } else {
            timeContainer.style.display = 'flex';
        }
    }

    // Initialize Store Hours Manager
    let storeHoursManager;
    document.addEventListener('DOMContentLoaded', () => {
        storeHoursManager = new StoreHoursManager();
    });
</script>
@endpush
@endsection