@extends('admin.store-settings.index')

@section('settings-content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Google Merchant Center') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Sync your products to Google Shopping') }}
            </p>
        </div>

        <!-- Status Card -->
        <div id="googleMerchantStatus" class="mb-8 p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div id="statusIcon" class="p-3 rounded-lg bg-gray-100 dark:bg-gray-700">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 id="statusTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                            Loading...
                        </h4>
                        <p id="statusMessage" class="text-sm text-gray-500 dark:text-gray-400">
                            Checking connection status
                        </p>
                    </div>
                </div>
                <div id="statusActions">
                    <!-- Actions will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="googleMerchantLoading" class="text-center py-12">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading Google Merchant settings...</p>
        </div>

        <!-- Settings Form -->
        <div id="googleMerchantFormContainer" style="display: none;">
            <form id="googleMerchantForm" onsubmit="event.preventDefault(); googleMerchantManager.updateSettings();">
                @csrf
                
                <div class="space-y-6">
                    <!-- Credentials Section -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Credentials</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="merchant_id" :value="__('Merchant ID')" />
                                <x-text-input id="merchant_id" name="merchant_id" type="text" class="mt-1 block w-full" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Find this in your Google Merchant Center account
                                </p>
                            </div>

                            <div>
                                <x-input-label for="client_id" :value="__('Client ID')" />
                                <x-text-input id="client_id" name="client_id" type="text" class="mt-1 block w-full" />
                            </div>

                            <div>
                                <x-input-label for="client_secret" :value="__('Client Secret')" />
                                <x-text-input id="client_secret" name="client_secret" type="password" class="mt-1 block w-full" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="auto_sync" name="auto_sync" value="1" 
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="auto_sync" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Auto-sync products when updated
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 ml-6">
                                Automatically sync product changes to Google Merchant Center
                            </p>
                        </div>
                    </div>

                    <!-- Sync Stats -->
                    <div id="syncStats" class="border-b border-gray-200 dark:border-gray-700 pb-6" style="display: none;">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Sync Statistics</h4>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Synced</p>
                                <p id="totalSynced" class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Last Sync</p>
                                <p id="lastSync" class="text-lg font-semibold text-gray-900 dark:text-white">Never</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p id="syncStatus" class="text-lg font-semibold text-green-600 dark:text-green-400">Active</p>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h5 class="font-medium text-blue-800 dark:text-blue-300 mb-2">How to get your credentials:</h5>
                        <ol class="list-decimal pl-5 text-sm text-blue-700 dark:text-blue-400 space-y-1">
                            <li>Go to <a href="https://console.cloud.google.com/" target="_blank" class="underline">Google Cloud Console</a></li>
                            <li>Create a new project or select existing one</li>
                            <li>Enable "Content API for Shopping"</li>
                            <li>Create OAuth 2.0 credentials</li>
                            <li>Add authorized redirect URI: <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{{ route('admin.google-merchant.callback') }}</code></li>
                            <li>Copy Client ID and Client Secret</li>
                            <li>Find your Merchant ID in Google Merchant Center</li>
                        </ol>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button type="button" id="testConnectionBtn" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        Test Connection
                    </button>
                    <button type="submit" id="saveSettingsBtn"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sync Modal -->
<div id="syncModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Sync Products to Google
                </h3>
                
                <div id="syncProgress" style="display: none;">
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span>Syncing products...</span>
                            <span id="syncProgressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div id="syncProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                
                <div id="syncResults" style="display: none;">
                    <!-- Results will be shown here -->
                </div>
                
                <div id="syncInitial" class="space-y-4">
                    <p class="text-gray-600 dark:text-gray-300">
                        This will sync all your products to Google Merchant Center. 
                        Existing products will be updated with current information.
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Total products to sync: <span id="totalProductsCount">0</span>
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelSync" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                        Cancel
                    </button>
                    <button type="button" id="startSync" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-150">
                        Start Sync
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Google Merchant Manager
    class GoogleMerchantManager {
        constructor() {
            this.formId = 'googleMerchantForm';
            this.loadingDiv = document.getElementById('googleMerchantLoading');
            this.formContainer = document.getElementById('googleMerchantFormContainer');
            this.statusCard = document.getElementById('googleMerchantStatus');
            this.statusIcon = document.getElementById('statusIcon');
            this.statusTitle = document.getElementById('statusTitle');
            this.statusMessage = document.getElementById('statusMessage');
            this.statusActions = document.getElementById('statusActions');
            this.syncStats = document.getElementById('syncStats');
            this.init();
        }

        init() {
            this.fetchSettings();
            
            // Event listeners
            document.getElementById('testConnectionBtn')?.addEventListener('click', () => this.testConnection());
            document.getElementById('startSync')?.addEventListener('click', () => this.startSync());
            document.getElementById('cancelSync')?.addEventListener('click', () => this.hideModal());
            
            // Modal click outside
            document.getElementById('syncModal')?.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    this.hideModal();
                }
            });
        }

        async fetchSettings() {
            try {
                this.showLoading(true);
                
                const response = await fetch('{{ route("admin.google-merchant.settings") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch settings');
                
                const data = await response.json();
                this.renderSettings(data);
                
            } catch (error) {
                console.error('Error fetching Google Merchant settings:', error);
                showGlobalMessage('error', 'Failed to load settings');
            } finally {
                this.showLoading(false);
            }
        }

        renderSettings(data) {
            if (data.success) {
                const settings = data.settings;
                
                // Fill form
                document.getElementById('merchant_id').value = settings.merchant_id || '';
                document.getElementById('client_id').value = settings.client_id || '';
                document.getElementById('client_secret').value = settings.client_secret || '';
                document.getElementById('auto_sync').checked = settings.auto_sync || false;
                
                // Update status
                this.updateStatus(data.is_connected, settings);
                
                // Load stats
                this.loadStats();
            }
        }

        updateStatus(isConnected, settings) {
            if (isConnected) {
                // Connected state
                this.statusIcon.className = 'p-3 rounded-lg bg-green-100 dark:bg-green-900';
                this.statusIcon.innerHTML = `
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                `;
                this.statusTitle.textContent = 'Connected';
                this.statusMessage.textContent = 'Your store is connected to Google Merchant Center';
                
                this.statusActions.innerHTML = `
                    <div class="flex space-x-3">
                        <button onclick="googleMerchantManager.showSyncModal()" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-150">
                            Sync Products
                        </button>
                        <button onclick="googleMerchantManager.disconnect()" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-150">
                            Disconnect
                        </button>
                    </div>
                `;
                
                this.syncStats.style.display = 'block';
                
            } else if (settings?.client_id && settings?.client_secret) {
                // Credentials saved but not connected
                this.statusIcon.className = 'p-3 rounded-lg bg-yellow-100 dark:bg-yellow-900';
                this.statusIcon.innerHTML = `
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                `;
                this.statusTitle.textContent = 'Ready to Connect';
                this.statusMessage.textContent = 'Save your credentials and connect to Google';
                
                this.statusActions.innerHTML = `
                    <button onclick="googleMerchantManager.connect()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150">
                        Connect to Google
                    </button>
                `;
                
            } else {
                // No credentials
                this.statusIcon.className = 'p-3 rounded-lg bg-gray-100 dark:bg-gray-700';
                this.statusIcon.innerHTML = `
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                `;
                this.statusTitle.textContent = 'Not Configured';
                this.statusMessage.textContent = 'Add your Google Merchant credentials to get started';
                
                this.statusActions.innerHTML = '';
            }
        }

        async updateSettings() {
            const form = document.getElementById(this.formId);
            const submitBtn = document.getElementById('saveSettingsBtn');
            const originalBtnText = submitBtn.innerHTML;
            
            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                
                clearFormErrors(form);
                
                const formData = new FormData(form);
                
                const response = await fetch('{{ route("admin.google-merchant.settings.update") }}', {
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
                    this.fetchSettings(); // Refresh settings
                } else {
                    showGlobalMessage('error', data.message || 'Failed to save settings');
                    
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
                console.error('Error updating settings:', error);
                showGlobalMessage('error', 'An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }

        async connect() {
            try {
                const response = await fetch('{{ route("admin.google-merchant.connect") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success && data.auth_url) {
                    window.open(data.auth_url, '_blank');
                } else {
                    showGlobalMessage('error', data.message || 'Failed to get auth URL');
                }
            } catch (error) {
                console.error('Error connecting:', error);
                showGlobalMessage('error', 'Connection failed');
            }
        }

        async disconnect() {
            if (confirm('Are you sure you want to disconnect from Google Merchant Center?')) {
                try {
                    const response = await fetch('{{ route("admin.google-merchant.disconnect") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        showGlobalMessage('success', data.message);
                        this.fetchSettings();
                    } else {
                        showGlobalMessage('error', data.message || 'Failed to disconnect');
                    }
                } catch (error) {
                    console.error('Error disconnecting:', error);
                    showGlobalMessage('error', 'Disconnect failed');
                }
            }
        }

        async testConnection() {
            const btn = document.getElementById('testConnectionBtn');
            const originalText = btn.textContent;
            
            try {
                btn.disabled = true;
                btn.textContent = 'Testing...';
                
                const response = await fetch('{{ route("admin.google-merchant.test") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showGlobalMessage('success', 'Connection successful!');
                } else {
                    showGlobalMessage('error', 'Connection failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error testing connection:', error);
                showGlobalMessage('error', 'Test failed');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }

        showSyncModal() {
            // First get total product count
            this.getProductCount().then(count => {
                document.getElementById('totalProductsCount').textContent = count;
                document.getElementById('syncModal').classList.remove('hidden');
            });
        }

        hideModal() {
            document.getElementById('syncModal').classList.add('hidden');
            // Reset modal state
            document.getElementById('syncProgress').style.display = 'none';
            document.getElementById('syncResults').style.display = 'none';
            document.getElementById('syncInitial').style.display = 'block';
        }

        async getProductCount() {
            try {
                const response = await fetch('/admin/products/count?sync_to_google=true', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                return data.count || 0;
            } catch (error) {
                console.error('Error getting product count:', error);
                return 0;
            }
        }

        async startSync() {
            const startBtn = document.getElementById('startSync');
            const originalText = startBtn.textContent;
            
            try {
                startBtn.disabled = true;
                startBtn.textContent = 'Starting...';
                
                // Show progress
                document.getElementById('syncInitial').style.display = 'none';
                document.getElementById('syncProgress').style.display = 'block';
                
                // Start sync
                const response = await fetch('{{ route("admin.google-merchant.sync") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                // Hide progress, show results
                document.getElementById('syncProgress').style.display = 'none';
                document.getElementById('syncResults').style.display = 'block';
                
                if (data.success) {
                    const results = data.results;
                    const successRate = results.total > 0 ? Math.round((results.successful / results.total) * 100) : 0;
                    
                    let resultsHtml = `
                        <div class="space-y-4">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900 mb-4">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sync Complete</h4>
                                <p class="text-gray-600 dark:text-gray-300">${results.successful} of ${results.total} products synced successfully (${successRate}%)</p>
                            </div>
                    `;
                    
                    if (results.failed > 0) {
                        resultsHtml += `
                            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded border border-red-200 dark:border-red-800">
                                <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">${results.failed} products failed to sync:</p>
                                <ul class="text-xs text-red-700 dark:text-red-400 space-y-1 max-h-32 overflow-y-auto">
                        `;
                        
                        results.errors.forEach(error => {
                            resultsHtml += `<li>${error.product_name}: ${error.error}</li>`;
                        });
                        
                        resultsHtml += `</ul></div>`;
                    }
                    
                    resultsHtml += `</div>`;
                    
                    document.getElementById('syncResults').innerHTML = resultsHtml;
                    
                    // Update stats
                    this.loadStats();
                } else {
                    document.getElementById('syncResults').innerHTML = `
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sync Failed</h4>
                            <p class="text-gray-600 dark:text-gray-300">${data.message || 'Unknown error occurred'}</p>
                        </div>
                    `;
                }
                
                // Update button to close
                startBtn.textContent = 'Close';
                startBtn.onclick = () => this.hideModal();
                
            } catch (error) {
                console.error('Error during sync:', error);
                document.getElementById('syncProgress').style.display = 'none';
                document.getElementById('syncResults').style.display = 'block';
                document.getElementById('syncResults').innerHTML = `
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sync Error</h4>
                        <p class="text-gray-600 dark:text-gray-300">An unexpected error occurred</p>
                    </div>
                `;
                
                const startBtn = document.getElementById('startSync');
                startBtn.textContent = 'Close';
                startBtn.onclick = () => this.hideModal();
            } finally {
                const startBtn = document.getElementById('startSync');
                startBtn.disabled = false;
            }
        }

        async loadStats() {
            try {
                const response = await fetch('{{ route("admin.google-merchant.stats") }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    const stats = data.stats;
                    document.getElementById('totalSynced').textContent = stats.total_products_synced;
                    document.getElementById('lastSync').textContent = stats.last_sync || 'Never';
                    document.getElementById('syncStatus').textContent = stats.is_connected ? 'Active' : 'Inactive';
                    document.getElementById('syncStatus').className = stats.is_connected 
                        ? 'text-lg font-semibold text-green-600 dark:text-green-400'
                        : 'text-lg font-semibold text-gray-600 dark:text-gray-400';
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        showLoading(show) {
            if (show) {
                this.loadingDiv.style.display = 'block';
                this.formContainer.style.display = 'none';
                this.statusCard.style.display = 'none';
            } else {
                this.loadingDiv.style.display = 'none';
                this.formContainer.style.display = 'block';
                this.statusCard.style.display = 'block';
            }
        }
    }

    // Initialize Google Merchant Manager
    let googleMerchantManager;
    document.addEventListener('DOMContentLoaded', () => {
        googleMerchantManager = new GoogleMerchantManager();
    });
</script>
@endpush
@endsection